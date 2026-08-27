<?php
/** * Bereinigter & Optimierter Header 
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once("db.php");
require_once("extra_script.php");

// 1. Grundlegende Daten initialisieren
$error_array = $_SESSION['error_array'] ?? [];

// Einstellungen laden
$get_general_settings = $db->select("general_settings");
$row_general_settings = $get_general_settings->fetch();

$enable_referrals   = $row_general_settings->enable_referrals ?? 0;
$site_color         = $row_general_settings->site_color ?? '#ff0000';
$site_hover_color   = $row_general_settings->site_hover_color ?? '';
$site_border_color  = $row_general_settings->site_border_color ?? '';
$header_color       = $row_general_settings->header_color ?? '#ffffff';
$navbar_color       = $row_general_settings->navbar_color ?? '';
$site_logo_type     = $row_general_settings->site_logo_type ?? 'text';
$site_logo_image    = $row_general_settings->site_logo_image ?? '';
$site_logo_text     = $row_general_settings->site_logo_text ?? 'Logo';
$enable_mobile_logo = $row_general_settings->enable_mobile_logo ?? 0;
$site_mobile_logo   = $row_general_settings->site_mobile_logo ?? '';

// 2. IP-Funktion (NUR EINMAL DEFINIEREN)
if (!function_exists('get_real_user_ip')) {
    function get_real_user_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Falls mehrere IPs vorhanden sind (Proxy), die erste nehmen
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }
}
$ip = get_real_user_ip();

// 3. Login-Logik
if (isset($_SESSION['seller_user_name'])) {
    require_once("seller_levels.php");
    $seller_user_name = $_SESSION['seller_user_name'];
    $get_seller = $db->select("sellers", array("seller_user_name" => $seller_user_name));
    $row_seller = $get_seller->fetch();

    if ($row_seller) {
        $seller_id = $row_seller->seller_id;
        $seller_email = $row_seller->seller_email;
        $seller_verification = $row_seller->seller_verification;
        $seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
        
        $count_cart = $db->count("cart", array("seller_id" => $seller_id));
        
        $select_seller_accounts = $db->select("seller_accounts", array("seller_id" => $seller_id));
        if ($select_seller_accounts->rowCount() == 0) {
            $db->insert("seller_accounts", array("seller_id" => $seller_id, "current_balance" => 0));
            $current_balance = 0;
        } else {
            $row_seller_accounts = $select_seller_accounts->fetch();
            $current_balance = $row_seller_accounts->current_balance;
        }
        $count_active_proposals = $db->count("proposals", array("proposal_seller_id" => $seller_id, "proposal_status" => 'active'));
    }
}

// Suche verarbeiten
if (isset($_POST['search'])) {
    $search_query = $input->post('search_query');
    $_SESSION['search_query'] = $search_query;
    echo "<script>window.open('$site_url/search.php','_self')</script>";
    exit;
}

// Honeypot Spam-Schutz
if (!empty($_POST['honeypot'])) {
    exit;
}

// Ankündigungs-Bar
if (!isset($_COOKIE['close_announcement']) || @$_COOKIE['close_announcement'] != $bar_last_updated) {
    include("comp/announcement_bar.php");
}
?>

<link href="<?= $site_url; ?>/styles/scoped_responsive_and_nav.css" rel="stylesheet">
<link href="<?= $site_url; ?>/styles/vesta_homepage.css" rel="stylesheet">

<div id="gnav-header" class="gnav-header global-nav clear gnav-3" style="background-color: <?= $header_color ?>;">
  <header id="gnav-header-inner" class="gnav-header-inner clear apply-nav-height col-group has-svg-icons body-max-width">
    <div>
      <div id="gigtodo-logo" class="apply-nav-height gigtodo-logo-svg gigtodo-logo-svg-logged-in <?php if(isset($_SESSION["seller_user_name"])){echo"loggedInLogo";} ?>">
        <a href="<?= $site_url; ?>">
          <?php if($site_logo_type == "image"): ?>
            <img class="desktop" src="<?= $site_url; ?>/images/<?= $site_logo_image; ?>" height="50" alt="Logo">
          <?php else: ?>
            <span class="desktop text-logo"><?= htmlspecialchars($site_logo_text); ?></span>
          <?php endif; ?>
          <?php if($enable_mobile_logo == 1): ?>
            <img class="mobile" src="<?= $site_url; ?>/images/<?= $site_mobile_logo; ?>" height="25">
          <?php endif; ?>
        </a>
      </div>

      <button id="mobilemenu" class="unstyled-button mobile-catnav-trigger apply-nav-height icon-b-1 <?= ($enable_mobile_logo == 0)?"left":""; ?>">
        <span class="gigtodo-icon hamburger-icon nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M20,6H4A1,1,0,1,1,4,4H20A1,1,0,0,1,20,6Z" />
              <path d="M20,13H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,13Z" />
              <path d="M20,20H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,20Z" />
            </svg>
        </span>
      </button>

      <div class="catnav-search-bar search-browse-wrapper with-catnav">
        <div class="search-browse-inner">
          <form id="gnav-search" class="search-nav expanded-search apply-nav-height" method="post">
            <div class="gnav-search-inner clearable">
              <div class="search-input-wrapper text-field-wrapper">
                <input id="search-query" class="rounded" name="search_query"
                  placeholder="<?= $lang['search']['placeholder']; ?>" 
                  value="<?= htmlspecialchars($_SESSION["search_query"] ?? ""); ?>" autocomplete="off">
              </div>
              <div class="search-button-wrapper hide">
                <button class="btn btn-primary" style="color:#FFF;background-color: <?= $site_color; ?>" name="search" type="submit">
                  <?= $lang['search']['button']; ?>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <ul class="account-nav apply-nav-height">
        <?php if (!isset($_SESSION["seller_user_name"])): ?>
            <li class="register-link"><a href="<?= $site_url; ?>/freelancers"><?= $lang['freelancers_menu']; ?></a></li>
            <li class="sell-on-gigtodo-link d-none d-lg-block">
                <a href="#" data-toggle="modal" data-target="#register-modal"><?= $lang['become_seller']; ?></a>
            </li>
            <li class="register-link">
                <a href="#" data-toggle="modal" data-target="#login-modal"><?= $lang['sign_in']; ?></a>
            </li>
            <li class="sign-in-link mr-lg-0 mr-3">
                <a href="#" class="btn btn_join" style="color: white; background-color: <?= $site_color; ?>" data-toggle="modal" data-target="#register-modal">
                    <?= ($deviceType == "phone") ? $lang['mobile_join_now'] : $lang['join_now']; ?>
                </a>
            </li>
        <?php else: ?>
            <?php require_once("comp/UserMenu.php"); ?>
        <?php endif; ?>
      </ul>
    </div>
  </header>
</div>

<div class="clearfix"></div>
<?php include("comp/categories_nav.php"); ?>
<div class="clearfix"></div>

<?php if(isset($_GET['not_available'])): ?>
<div class="alert alert-danger text-center mb-0 h6"><?= $lang['not_availble']; ?></div>
<?php endif; ?>

<?php 
if(isset($_SESSION['seller_user_name']) && isset($seller_verification) && $seller_verification != "ok"): 
?>
<div class="alert alert-warning clearfix activate-email-class mb-0">
  <div class="float-left mt-2">
    <i style="font-size: 125%;" class="fa fa-exclamation-circle"></i> 
    <?php
      $message = $lang['popup']['email_confirm']['text'];
      $message = str_replace(['{seller_email}', '{link}'], [$seller_email, "$site_url/customer_support"], $message);
      echo $message;
    ?>
  </div>
  <div class="float-right">
    <button id="send-email" class="btn btn-success btn-sm text-white"><?= $lang["popup"]["email_confirm"]['button']; ?></button>
  </div>
</div>

<script>
  $(document).ready(function(){
    $("#send-email").click(function(){
      $("#wait").addClass('loader');
      $.ajax({
        method: "POST",
        url: "<?= $site_url; ?>/includes/send_email",
        success:function(){
          $("#wait").removeClass('loader');
          $("#send-email").html("Resend Email");
          swal({ type: 'success', text: '<?= $lang['alert']['confirmation_email']; ?>' });
        }
      });
    });
  });
</script>
<?php endif; ?>

<?php 
require_once("register_login_forgot_modals.php"); 
require_once("register_login_forgot.php"); 
require_once("external_stylesheet.php"); 
?>