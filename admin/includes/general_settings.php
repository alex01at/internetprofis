<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$site_title = $row_general_settings->site_title;
$site_www = $row_general_settings->site_www;
$site_desc = $row_general_settings->site_desc;
$site_keywords = $row_general_settings->site_keywords;
$site_author = $row_general_settings->site_author;
$site_favicon = getImageUrl2("general_settings","site_favicon",$row_general_settings->site_favicon);
$site_logo_type = $row_general_settings->site_logo_type;
$site_logo_text = $row_general_settings->site_logo_text;
$site_name = $row_general_settings->site_name;
$enable_mobile_logo = $row_general_settings->enable_mobile_logo;
$site_logo_image = getImageUrl2("general_settings","site_logo_image",$row_general_settings->site_logo_image);
$site_mobile_logo = getImageUrl2("general_settings","site_mobile_logo",$row_general_settings->site_mobile_logo);
$site_logo = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);
$s_favicon = $row_general_settings->site_favicon;
$s_logo_image = $row_general_settings->site_logo_image;
$s_mobile_logo = $row_general_settings->site_mobile_logo;
$s_logo = $row_general_settings->site_logo;
$s_favicon_s3 = $row_general_settings->site_favicon_s3;
$s_mobile_logo_s3 = $row_general_settings->site_mobile_logo_s3;
$s_logo_image_s3 = $row_general_settings->site_logo_image_s3;
$s_logo_s3 = $row_general_settings->site_logo_s3;
$s_watermark = $row_general_settings->site_watermark;
$site_url = $row_general_settings->site_url;
$site_email_address = $row_general_settings->site_email_address;
$site_copyright = $row_general_settings->site_copyright;
$site_timezone = $row_general_settings->site_timezone;
$site_currency = $row_general_settings->site_currency;
$currency_position = $row_general_settings->currency_position;
$currency_format = $row_general_settings->currency_format;
$recaptcha_site_key = $row_general_settings->recaptcha_site_key;
$recaptcha_secret_key = $row_general_settings->recaptcha_secret_key;
$google_app_link = $row_general_settings->google_app_link;
$apple_app_link = $row_general_settings->apple_app_link;
$enable_social_login = $row_general_settings->enable_social_login;
$fb_app_id = $row_general_settings->fb_app_id;
$fb_app_secret = $row_general_settings->fb_app_secret;
$g_client_id = $row_general_settings->g_client_id;
$g_client_secret = $row_general_settings->g_client_secret;
$jwplayer_code = $row_general_settings->jwplayer_code;
$level_one_rating = $row_general_settings->level_one_rating;
$level_one_orders = $row_general_settings->level_one_orders;
$level_two_orders = $row_general_settings->level_two_orders;
$level_two_rating = $row_general_settings->level_two_rating;
$level_top_rating = $row_general_settings->level_top_rating;
$level_top_orders = $row_general_settings->level_top_orders;
$approve_proposals = $row_general_settings->approve_proposals;
$disable_local_video = $row_general_settings->disable_local_video;
$edited_proposals = $row_general_settings->edited_proposals;
$proposal_email = $row_general_settings->proposal_email;
$revisions_list = $row_general_settings->revisions_list;
$enable_unlimited_revisions = $row_general_settings->enable_unlimited_revisions;
$signup_email = $row_general_settings->signup_email;
$relevant_requests = $row_general_settings->relevant_requests;
$enable_referrals = $row_general_settings->enable_referrals;
$knowledge_bank = $row_general_settings->knowledge_bank;
$referral_money = $row_general_settings->referral_money;
$enable_maintenance_mode = $row_general_settings->enable_maintenance_mode;
$enable_cookie_notice = $row_general_settings->enable_cookie_notice;
$order_auto_complete = $row_general_settings->order_auto_complete;
$wish_do_manual_payouts = $row_general_settings->wish_do_manual_payouts;
$language_switcher = $row_general_settings->language_switcher;
$enable_google_translate = $row_general_settings->enable_google_translate;
$site_color = $row_general_settings->site_color;
$site_hover_color = $row_general_settings->site_hover_color;
$site_border_color = $row_general_settings->site_border_color;
$google_analytics = $row_general_settings->google_analytics;
$enable_websocket = $row_general_settings->enable_websocket;
$websocket_address = $row_general_settings->websocket_address;
$make_phone_number_required = $row_general_settings->make_phone_number_required;
$mobileApp_apiKey = $row_general_settings->mobileApp_apiKey;

$get_proposals = $db->query("
        SELECT * 
        FROM proposals 
        WHERE proposal_featured = 'yes' 
          AND proposal_status   = 'active' 
        LIMIT 0,10
    ");

    // jetzt das Row‐Objekt holen
    $row_proposals = $get_proposals->fetch();

    // nur benutzen, wenn es nicht null ist
    if($row_proposals){
        $proposal_referral_money = $row_proposals->proposal_referral_money;
    } else {
        // Default‐Wert, falls keine Featured‐Proposals gefunden wurden
        $proposal_referral_money = 0;
    }

require 'updateHtaccess.php';
require 'timezones.php';

} ?>