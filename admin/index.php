<?php

session_start();
include("includes/db.php");
include("../functions/mailer.php");
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}


if(!$_SESSION['adminLanguage']){
	$_SESSION['adminLanguage'] = 1;
}

$adminLanguage = $_SESSION['adminLanguage'];
$row_language = $db->select("languages",array("id"=>$adminLanguage))->fetch();
$currentLanguage = $row_language->title;
$admin_email = $_SESSION['admin_email'];

$get_admin = $db->query("select * from admins where admin_email=:a_email OR admin_user_name=:a_user_name",array("a_email"=>$admin_email,"a_user_name"=>$admin_email));
$row_admin = $get_admin->fetch();
$admin_id = $row_admin->admin_id;
$login_admin_id = $row_admin->admin_id;
$admin_name = $row_admin->admin_name;
$admin_user_name = $row_admin->admin_user_name;
$admin_image = $row_admin->admin_image;
$admin_country = $row_admin->admin_country;
$admin_job = $row_admin->admin_job;
$admin_contact = $row_admin->admin_contact;
$admin_about = $row_admin->admin_about;
$login_time = $row_admin->login_time;
if(isset($_GET['from'])){
    $from = $input->get('from');
    $to = (isset($_GET['to']) AND !empty($input->get('to'))) ? $input->get('to') : date("Y-m-d");
    $filter_q = "where date Between '$from' AND '$to'";
    $filterUrl = "&from=$from&to=$to";
  }else{
    $from = "";
    $to = "";
    $filter_q = "";
    $filterUrl = "";
  }
$count_active_proposals = $db->count("proposals",array("proposal_status" => "active"));
$totalSale = $db->query("SELECT SUM(amount) AS total FROM sales $filter_q")->fetch()->total;
if(empty($totalSale)){$totalSale=0;}

if((time() - $_SESSION['loggedin_time']) > $login_time){
	echo "<script>window.open('logout?session_expired','_self');</script>";
}
$get_rights = $db->select("admin_rights", array("admin_id" => $admin_id));
$row_rights = $get_rights->fetch();

// Falls gar kein Eintrag gefunden wurde, erstellen wir ein leeres Objekt
if (!$row_rights) {
    $row_rights = new stdClass();
}

// Jetzt weisen wir die Variablen mit dem ?? Operator zu
$a_settings = $row_rights->settings ?? '';
$a_plugins = $row_rights->plugins ?? '';
$a_pages = $row_rights->pages ?? '';
$a_blog = $row_rights->blog ?? '';
$a_feedback = $row_rights->feedback ?? '';
$a_video_schedules = $row_rights->video_schedules ?? '';
$a_proposals = $row_rights->proposals ?? '';
$a_accounting = $row_rights->accounting ?? '';
$a_payouts = $row_rights->payouts ?? '';
$a_reports = $row_rights->reports ?? '';
$a_inbox = $row_rights->inbox ?? '';
$a_reviews = $row_rights->reviews ?? '';
$a_buyer_requests = $row_rights->buyer_requests ?? '';
$a_restricted_words = $row_rights->restricted_words ?? '';
$a_country_list = $row_rights->country_list ?? '';
$a_alerts = $row_rights->notifications ?? '';
$a_cats = $row_rights->cats ?? '';
$a_delivery_times = $row_rights->delivery_times ?? '';
$a_seller_languages = $row_rights->seller_languages ?? '';
$a_seller_skills = $row_rights->seller_skills ?? '';
$a_seller_levels = $row_rights->seller_levels ?? '';
$a_customer_support = $row_rights->customer_support ?? '';
$a_coupons = $row_rights->coupons ?? '';
$a_slides = $row_rights->slides ?? '';
$a_sellers = $row_rights->sellers ?? '';
$a_terms = $row_rights->terms ?? '';
$a_orders = $row_rights->orders ?? '';
$a_referrals = $row_rights->referrals ?? '';
$a_files = $row_rights->files ?? '';
$a_knowledge_bank = $row_rights->knowledge_bank ?? '';
$a_currencies = $row_rights->currencies ?? '';
$a_languages = $row_rights->languages ?? '';
$a_admins = $row_rights->admins ?? '';

// $get_app_license = $db->select("app_license");
// $row_app_license = $get_app_license->fetch();
// $purchase_code = $row_app_license->purchase_code;
// $license_type = $row_app_license->license_type;
// $website = $row_app_license->website;

$count_sellers = $db->count("sellers");
$count_ideas = $db->count("ideas");
$count_comments = $db->count("comments");
// $count_suggestions = $db->count("suggest_category");
$count_notifications = $db->count("admin_notifications",array("status" => "unread"));
$count_orders = $db->count("orders",array("order_active" => "yes"));
$count_proposals = $db->count("proposals",array("proposal_status" => "pending"));
$count_support_tickets = $db->count("support_tickets",array("status" => "open"));
$count_requests = $db->count("buyer_requests",array("request_status" => "pending"));
$count_referrals = $db->count("referrals",array("status" => "pending"));
$count_proposals_referrals = $db->count("proposal_referrals",array("status" => "pending"));

// Ändere diesen Teil:
if (!empty($newsserver)) {
    $newsContent = @file_get_contents($newsserver);
    if ($newsContent !== false && trim($newsContent) !== '') {
        $news = $newsContent;
    } else {
        $news = null;
    }
} else {
    $news = null;
}

// Und das gleiche für Sponsor (Zeile 114 ca.):
if (!empty($sponsord)) {
    $sponsorContent = @file_get_contents($sponsord);
    if ($sponsorContent !== false && trim($sponsorContent) !== '') {
        $sponsord_ad = $sponsorContent;
    } else {
        $sponsord_ad = null;
    }
} else {
    $sponsord_ad = null;
}

function autoLoader($className){
  require_once("../functions/$className.php");
}
spl_autoload_register('autoLoader');

$core = new Core;
$paymentGateway = $core->checkPlugin("paymentGateway");
$videoPlugin = $core->checkPlugin("videoPlugin");
$notifierPlugin = $core->checkPlugin("notifierPlugin");

if($notifierPlugin == 1){ 
	require_once("$dir/plugins/notifierPlugin/functions.php");
}


?>
<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Admin Panel - Control Your Entire Site.</title>
	<meta name="description" content="With the GigToDoScript admin panel, controlling your website has never been eassier.">
	<meta name="author" content="GigToDoScript">

	<!-- Site favicon -->
	<?php if(!empty($site_favicon)){ ?>
		<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/costum.css">
	<script src="vendors/scripts/jquery.js"></script>
    <script src="vendors/scripts/popper.js"></script>
	<script type="text/javascript" src="vendors/scripts/sweat_alert.js"></script>
	


	<script>
	
	function alert_error(text){
		Swal('',text,'error');
	}

	function alert_success(text,url){
	  swal({
	  type: 'success',
	  timer : 1000,
	  text: text,
	  onOpen: function(){
	    swal.showLoading()
	  }
	  }).then(function(){
	  	if(url != ""){
			window.open(url,'_self');
		}
	  });
	}
	
	function alert_error(text,url){
	  swal({
		type: 'error',
		timer: 3000,
		text: text,
		onOpen: function(){
		 swal.showLoading()
		}
	  }).then(function(){
	  	if(url != ""){
			window.open(url,'_self');
		}
	  });
	}
	
	function alert_confirm(text,url){
		swal({
		  text: text,
		  type: 'warning',
		  showCancelButton: true 
		}).then((result) => {
			if(result.value){
			  if(url != ""){ window.open(url,'_self'); }
			}
		});
	}

	</script>
</head>
<body class="header-light sidebar-light">


	<div class="header">
		<?php include('includes/header.php'); ?>
	</div>

	

	
	<div class="mobile-menu-overlay"></div>

	<?php include('includes/menu.php'); ?>
	<!-- js -->
	<?php 
	include('includes/body.php'); 
	include('includes/footer.php'); 
	?>
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="vendors/scripts/image_picker.js"></script>
	<script src="src/plugins/apexcharts/apexcharts.min.js"></script>
	<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<?php
$currentURL = $_SERVER['REQUEST_URI'];
$targetPath = 'index?dashboard';
if (strpos($currentURL, $targetPath) !== false) {
   echo "<script src='vendors/scripts/dashboard.js'></script>";
}


?>

</body>
</html>