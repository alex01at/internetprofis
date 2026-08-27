<?php

require_once("$dir/functions/email.php");
require_once("$dir/functions/mailer.php");

$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$site_email_address = $row_general_settings->site_email_address;
$site_name = $row_general_settings->site_name;
$signup_email = $row_general_settings->signup_email;
$referral_money = $row_general_settings->referral_money;

/** 
 * REGISTRIERUNG 
 */
if(isset($_POST['register'])){
    $rules = array(
        "name" => "required",
        "u_name" => "required",
        "email" => "email|required",
        "pass" => "required",
        "con_pass" => "required"
    );

    $messages = array(
        "name" => "Full Name Is Required.",
        "u_name" => "User Name Is Required.",
        "pass" => "Password Is Required.",
        "con_pass" => "Confirm Password Is Required."
    );
    
    $val = new Validator($_POST,$rules,$messages);

    if($val->run() == false){
        Flash::add("register_errors",$val->get_all_errors());
        Flash::add("form_data",$_POST);
        echo "<script>window.open('index','_self')</script>";
    }else{
        $error_array = array();
        
        // Daten bereinigen
        $name = strip_tags($input->post('name'));
        $u_name = strip_tags($input->post('u_name'));
        $email = strip_tags($input->post('email'));
        $phone = strip_tags($input->post('phone'));
        $country_code = strip_tags($input->post('country_code'));
        $pass = strip_tags($input->post('pass'));
        $con_pass = strip_tags($input->post('con_pass'));
        $referral = strip_tags($input->post('referral'));

        // Sessions für Formular-Wiederherstellung
        $_SESSION['name'] = $name;
        $_SESSION['u_name'] = $u_name;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['country_code'] = $country_code;

        $full_phone = $country_code . " " . $phone;

        // --- GEOLOCATION FIX FÜR LOCALHOST ---
        $ip = get_real_user_ip(); 
        if($ip == '::1' || $ip == '127.0.0.1'){
            $country = "Germany"; // Standardwert für Lokal
        } else {
            $ip_api = @file_get_contents("http://www.geoplugin.net/php.gp?ip=".$ip);
            if($ip_api !== false){
                $geoplugin = unserialize($ip_api);
                $country = $geoplugin['geoplugin_countryName'] ?? "Germany";
            } else {
                $country = "Germany";
            }
        }
        if(empty($country)){ $country = "Germany"; }

        $regsiter_date = date("F d, Y");
        $date = date("F d, Y");
    
        // Validierungen
        $check_seller_username = $db->count("sellers",array("seller_user_name" => $u_name));
        $check_seller_email = $db->count("sellers",array("seller_email" => $email));

        if(preg_match('/[اأإء-ي]/ui', $u_name)){
          array_push($error_array, "Foreign characters are not allowed in username.");
        }
        if($check_seller_username > 0 ){
          array_push($error_array, "Opps! This username has already been taken.");
        }
        if(strpos($u_name, ' ') !== false){
            array_push($error_array, "Spaces Are Not Allowed In Username.");
        }
        if($check_seller_email > 0){
          array_push($error_array, "Email has already been taken.");
        }
        if($pass != $con_pass){
          array_push($error_array, "Passwords don't match.");
        }
    
        if(empty($error_array)){
            $referral_code = mt_rand();
            $verification_code = ($signup_email == "yes") ? mt_rand() : "ok";
            $encrypted_password = password_hash($pass, PASSWORD_DEFAULT);
            $seller_activity = date("Y-m-d H:i:s");
            
            // Timezone Logik
            $timezone_offset_minutes = $input->post('timezone');  
            $timezone = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
            if(!$timezone) $timezone = "UTC";
            
            $insert_seller = $db->insert("sellers",array(
                "seller_name" => $name,
                "seller_user_name" => $u_name,
                "seller_email" => $email,
                "seller_phone" => $full_phone,
                "seller_pass" => $encrypted_password,
                "seller_country" => $country,
                "seller_level" => 1,
                "seller_recent_delivery" => 'none',
                "seller_rating" => 0,
                "seller_offers" => 10,
                "seller_referral" => $referral_code,
                "seller_ip" => $ip,
                "seller_verification" => $verification_code,
                "seller_vacation" => 'off',
                "seller_register_date" => $regsiter_date,
                "seller_activity" => $seller_activity,
                "seller_timezone" => $timezone,
                "seller_status" => 'online'
            ));
                    
            $regsiter_seller_id = $db->lastInsertId();

            if($insert_seller){
                $_SESSION['seller_user_name'] = $u_name;
                $db->insert("seller_accounts", array("seller_id" => $regsiter_seller_id));
                
                if(isset($paymentGateway) && $paymentGateway == 1){
                    $db->insert("seller_settings", array("seller_id" => $regsiter_seller_id));
                }

                // Referral Logik
                if(!empty($referral)){
                    $sel_seller = $db->select("sellers", array("seller_referral" => $referral));     
                    if($row_seller = $sel_seller->fetch()){
                        $ref_seller_id = $row_seller->seller_id;    
                        $ref_seller_ip = $row_seller->seller_ip;
                        
                        if($ref_seller_ip != $ip){
                            $count_referrals = $db->count("referrals", array("ip" => $ip));  
                            if($count_referrals == 0){
                                $db->insert("referrals", array(
                                    "seller_id" => $ref_seller_id,
                                    "referred_id" => $regsiter_seller_id,
                                    "comission" => $referral_money,
                                    "date" => $date,
                                    "ip" => $ip,
                                    "status" => 'pending'
                                ));
                            }
                        }
                    } 
                }

                if($signup_email == "yes"){
                    userSignupEmail($email);
                }

                echo "<script>
                    swal({
                        type: 'success',
                        text: '".str_replace("{name}",$name,$lang['alert']['successfully_registered'])."',
                        timer: 2000,
                        onOpen: function(){ swal.showLoading() }
                    }).then(function(){
                        window.open('$site_url','_self');
                    });
                </script>";

                // Sessions leeren
                unset($_SESSION['name'], $_SESSION['u_name'], $_SESSION['email'], $_SESSION['phone'], $_SESSION['country_code']);
                $_SESSION['error_array'] = array();
            }
        } else {
            $_SESSION['error_array'] = $error_array;
            echo "<script>
                swal({
                    type: 'warning',
                    html: $('<div>').text('{$lang['alert']['errors']}'),
                    animation: false,
                    customClass: 'animated tada'
                }).then(function(){ window.open('index','_self') });
            </script>";
        }
    }
}

/** 
 * LOGIN 
 */
if(isset($_POST['login'])){
    $rules = array("seller_user_name" => "required", "seller_pass" => "required");
    $messages = array("seller_user_name" => "Username Is Required.", "seller_pass" => "Password Is Required.");
    $val = new Validator($_POST, $rules, $messages);

    if($val->run() == false){
        Flash::add("login_errors", $val->get_all_errors());
        echo "<script>window.open('index','_self')</script>";
    }else{
        $seller_user_name = $input->post('seller_user_name');
        $seller_pass = $input->post('seller_pass');

        $select_seller = $db->query("select * from sellers where binary seller_user_name=:u_name OR seller_email=:u_email", array(":u_name"=>$seller_user_name, ":u_email"=>$seller_user_name));
        
        if($row_seller = $select_seller->fetch()){
            if(password_verify($seller_pass, $row_seller->seller_pass)){
                
                if($row_seller->seller_status == "block-ban"){
                    echo "<script>swal({type:'warning', text:'{$lang['alert']['blocked']}'})</script>";
                } elseif($row_seller->seller_status == "deactivated"){
                    echo "<script>swal({type:'warning', text:'{$lang['alert']['deactivated']}'})</script>";
                } else {
                    // --- SQL FIX: KLAMMERN GEGEN LOGIKFEHLER ---
                    $check_login = $db->query("select * from sellers where (seller_email=:u_email OR seller_user_name=:u_name) AND seller_pass=:u_pass", 
                        array("u_email"=>$seller_user_name, "u_name"=>$seller_user_name, "u_pass"=>$row_seller->seller_pass));
                    
                    if($check_login->rowCount() == 1){
                        $_SESSION['seller_user_name'] = $row_seller->seller_user_name;
                        $db->update("sellers", array("seller_status"=>'online', "seller_ip"=>get_real_user_ip()), array("seller_id"=>$row_seller->seller_id));
                        
                        $display_name = ucfirst($row_seller->seller_user_name);
                        echo "<script>
                            swal({
                                type: 'success',
                                text: '".str_replace('{seller_user_name}',$display_name,$lang['alert']['successfully_login'])."',
                                timer: 2000,
                                onOpen: function(){ swal.showLoading() }
                            }).then(function(){ window.open('index','_self') });
                        </script>";
                    }
                }
            } else {
                echo "<script>swal({type:'warning', text:'{$lang['alert']['incorrect_login']}'})</script>";
            }
        } else {
            echo "<script>swal({type:'warning', text:'{$lang['alert']['incorrect_login']}'})</script>";
        }
    }
}

/** 
 * PASSWORT VERGESSEN 
 */
if(isset($_POST['forgot'])){
    $forgot_email = $input->post('forgot_email');
    $select_seller = $db->select("sellers", array("seller_email" => $forgot_email));
    
    if($row_seller = $select_seller->fetch()){
        $data = [
            'template' => "forgot_password",
            'to' => $forgot_email,
            'subject' => "$site_name: Password Reset",
            'user_name' => $row_seller->seller_user_name,
            'forgot_link' => "$site_url/change_password?username=".$row_seller->seller_user_name."&code=".$row_seller->seller_pass
        ];

        if(send_mail($data)){
            echo "<script>swal({type:'success', text:'{$lang['alert']['successfully_forgot_pass']}'})</script>";
        }
    } else {
        echo "<script>swal({type:'warning', text:'{$lang['alert']['no_email']}'})</script>";
    }
}