<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$dir = __DIR__;
$dir = str_replace("admin/includes", '', $dir);
$dir = str_replace("admin\includes", '', $dir);

include("$dir/includes/config.php");

if(empty(DB_HOST) && empty(DB_USER) && empty(DB_NAME)){
   echo "<script>window.open('../install.php','_self'); </script>";
   exit();
} else {

   include $dir.'libs/database.php';
   include $dir.'libs/input.php';
   include $dir.'libs/validator.php';
   include $dir.'libs/flash.php';

   $db->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

   // --- General Settings abrufen ---
   $get_general_settings = $db->select("general_settings");   
   $row_general_settings = $get_general_settings->fetch();

   if($row_general_settings) {
       $site_email_address = $row_general_settings->site_email_address;
       $site_url = $row_general_settings->site_url;
       $tinymce_api_key = $row_general_settings->tinymce_api_key;
       $site_name = $row_general_settings->site_name;
       $site_keywords = $row_general_settings->site_keywords;
       $site_author = $row_general_settings->site_author;
       $site_desc = $row_general_settings->site_desc;
       $site_logo_image = $row_general_settings->site_logo_image;
       $site_currency = $row_general_settings->site_currency;
       $currency_position = $row_general_settings->currency_position;
       $currency_format = $row_general_settings->currency_format;
       $site_timezone = $row_general_settings->site_timezone;
   }

   // --- Währung abrufen ---
   $get_currencies = $db->select("currencies", array("id" => $site_currency ?? 1));
   $row_currencies = $get_currencies->fetch();
   
   $s_currency_name = $row_currencies->name ?? "USD";
   $s_currency = $row_currencies->symbol ?? "$";

   // --- SMTP Settings ---
   $get_smtp_settings = $db->select("smtp_settings");
   if($row_smtp_settings = $get_smtp_settings->fetch()) {
       $enable_smtp = $row_smtp_settings->enable_smtp;
       $s_host = $row_smtp_settings->host;
       $s_port = $row_smtp_settings->port;
       $s_secure = $row_smtp_settings->secure;
       $s_username = $row_smtp_settings->username;
       $s_password = $row_smtp_settings->password;
   }

   // --- API Settings ---
   $get_api_settings = $db->select("api_settings");
   if($row_api_settings = $get_api_settings->fetch()) {
       $enable_s3 = $row_api_settings->enable_s3;
   }

   include("$dir/includes/s3-config.php");

   // --- SPRACH-LOGIK (Der kritische Teil) ---
   if(!isset($_SESSION['adminLanguage'])){
      $get_default_lang = $db->select("languages", ["default_lang" => 1])->fetch();
      $_SESSION['adminLanguage'] = ($get_default_lang) ? $get_default_lang->id : 1;
   }

   $sel_language = $db->select("languages", array("id" => $_SESSION['adminLanguage']))->fetch();

   // Falls die ID in der Session nicht mehr existiert, hole die erste verfügbare Sprache
   if(!$sel_language) {
       $sel_language = $db->select("languages")->fetch();
   }

   if($sel_language) {
       $template_folder = $sel_language->template_folder; 
       $lang_file = $dir . "languages/" . strtolower($sel_language->title) . ".php";
       
       if(file_exists($lang_file)) {
           require($lang_file);
       } else {
           // Fallback, falls die spezifische Datei fehlt, versuche english.php
           if(file_exists($dir . "languages/english.php")) {
               require($dir . "languages/english.php");
           }
       }
   }

   // --- Bilder-URLs ---
   if($row_general_settings) {
       $site_favicon = getImageUrl2("general_settings", "site_favicon", $row_general_settings->site_favicon);
       $site_logo_image = getImageUrl2("general_settings", "site_logo_image", $row_general_settings->site_logo_image);
       $site_logo = getImageUrl2("general_settings", "site_logo", $row_general_settings->site_logo);
   }

}

require_once("$dir/includes/commonFunctions.php");