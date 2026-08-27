<?php
session_start();
require_once("includes/db.php");
require_once("social-config.php");

// 1. Prüfung: Ist der Nutzer bereits eingeloggt?
if(isset($_SESSION['seller_user_name'])){
    header("Location: index");
    exit();
}

$login_errors = [];

// 2. Login-Logik (Bevor HTML gesendet wird)
if(isset($_POST['access'])){
    $rules = [
        "seller_user_name" => "required",
        "seller_pass" => "required"
    ];
    $messages = [
        "seller_user_name" => "Benutzername oder E-Mail ist erforderlich.",
        "seller_pass" => "Passwort ist erforderlich."
    ];
    
    $val = new Validator($_POST, $rules, $messages);

    if($val->run() == false){
        Flash::add("login2_errors", $val->get_all_errors());
    } else {
        $seller_user_name = $input->post('seller_user_name');
        $seller_pass = $input->post('seller_pass');

        // Sicherer SQL-Check ohne LIKE (exakter Match)
        $select_seller = $db->query("SELECT * FROM sellers WHERE seller_user_name = :u_name OR seller_email = :u_email", [
            ":u_name" => $seller_user_name,
            ":u_email" => $seller_user_name
        ]);

        $row_seller = $select_seller->fetch();

        // Prüfen, ob Nutzer existiert
        if($row_seller){
            $hashed_password = $row_seller->seller_pass;
            $seller_status = $row_seller->seller_status;

            if(password_verify($seller_pass, $hashed_password)){
                // Passwort korrekt, nun Status prüfen
                if($seller_status == "block-ban"){
                    $error_type = "blocked";
                } elseif($seller_status == "deactivated") {
                    $error_type = "deactivated";
                } else {
                    // Login erfolgreich
                    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0] : $_SERVER['REMOTE_ADDR'];
                    
                    $db->update("sellers", 
                        ["seller_status" => 'online', "seller_ip" => $ip], 
                        ["seller_id" => $row_seller->seller_id]
                    );

                    $_SESSION['seller_user_name'] = $row_seller->seller_user_name;
                    $success_login_msg = str_replace('{seller_user_name}', ucfirst($row_seller->seller_user_name), $lang['alert']['successfully_login']);
                    
                    // Wir speichern den Erfolg in einer Variable für das JS unten
                    $login_success = true;
                }
            } else {
                $error_type = "incorrect";
            }
        } else {
            $error_type = "incorrect";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de" class="ui-toolkit">
<head>
    <title><?= $site_name; ?> - <?= $lang['titles']['login']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
     
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <link href="styles/animate.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/sweat_alert.js"></script>
    <?php if(!empty($site_favicon)){ ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
</head>
<body class="is-responsive">

<?php require_once("includes/header.php"); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <h2 class="text-center"><?= str_replace('{site_name}', $site_name, $lang['login']['title']); ?></h2>
            <div class="box-login mt-4">
                <h2 class="text-center mb-3 mt-3"><i class="fa fa-unlock-alt"></i></h2>

                <?php 
                $form_errors = Flash::render("login2_errors");
                if(is_array($form_errors)){
                ?>
                <div class="alert alert-danger">
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($form_errors as $i => $error): ?>
                        <li><?= ($i+1) ?>. <?= ucfirst($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php } ?>

                <form action="" method="post">
                    <div class="form-group">
                        <input type="text" name="seller_user_name" class="form-control" placeholder="<?= $lang['placeholder']['username_or_email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="seller_pass" class="form-control" placeholder="<?= $lang['placeholder']['password']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="access" class="btn btn-success btn-block" value="<?= $lang['button']['login']; ?>">
                    </div>
                </form>

                <?php if($enable_social_login == "yes"){ ?>
                    <div class="text-center pt-2 pb-2"><?= $lang['modals']['login']['or']; ?></div>
                    <hr class="my-2">
                    <div class="text-center">
                        <?php if(!empty($fb_app_id)){ ?>
                            <a href="<?= $fLoginURL ?>" class="btn btn-primary text-white"><i class="fa fa-facebook"></i> FACEBOOK</a>
                        <?php } ?>
                        <?php if(!empty($g_client_id)){ ?>
                            <a href="<?= $gLoginURL ?>" class="btn btn-danger text-white"><i class="fa fa-google"></i> GOOGLE</a>
                        <?php } ?>
                    </div>
                <?php } ?>

                <div class="text-center mt-3">
                    <a href="#" data-toggle="modal" data-target="#register-modal"><i class="fa fa-user-plus"></i> <?= $lang['modals']['login']['not_registerd']; ?></a>
                    <span class="mx-2">|</span>
                    <a href="#" data-toggle="modal" data-target="#forgot-modal"><i class="fa fa-meh-o"></i> <?= $lang['modals']['login']['forgot_password']; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>

<script>
<?php if(isset($error_type)): ?>
    <?php if($error_type == "incorrect"): ?>
        swal({ type: 'warning', html: $('<div>').text('<?= $lang['alert']['incorrect_login']; ?>'), animation: false, customClass: 'animated tada' });
    <?php elseif($error_type == "blocked"): ?>
        swal({ type: 'warning', html: $('<div>').text('<?= $lang['alert']['blocked']; ?>'), animation: false, customClass: 'animated tada' });
    <?php elseif($error_type == "deactivated"): ?>
        swal({ type: 'warning', html: $('<div>').text('<?= $lang['alert']['deactivated']; ?>'), animation: false, customClass: 'animated tada' });
    <?php endif; ?>
<?php endif; ?>

<?php if(isset($login_success)): ?>
    swal({
        type: 'success',
        text: '<?= $success_login_msg; ?>',
        timer: 2000,
        onOpen: function(){ swal.showLoading() }
    }).then(function(){
        window.open('<?= $site_url; ?>', '_self');
    });
<?php endif; ?>
</script>

</body>
</html>