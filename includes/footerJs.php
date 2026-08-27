<?php
// 1. Optimierung: Nur abfragen, wenn eingeloggt und Daten noch nicht vorhanden
if(isset($_SESSION['seller_user_name'])){
    $login_seller_user_name = $_SESSION['seller_user_name'];
    
    // Wir holen nur die Spalten, die wir wirklich für JS brauchen
    $select_login_seller = $db->select("sellers", ["seller_user_name" => $login_seller_user_name]);
    $row_login_seller = $select_login_seller->fetch();
    
    if($row_login_seller){
        $login_seller_id = $row_login_seller->seller_id;
        $login_seller_enable_sound = $row_login_seller->enable_sound ?? '0';
        $login_seller_enable_notifications = $row_login_seller->enable_notifications ?? '0';
    }
}
?>

<div id="wait"></div>

<!-- Google Analytics: Nur laden, wenn ID vorhanden -->
<?php if(!empty($google_analytics)): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $google_analytics; ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= $google_analytics; ?>');
  </script>
<?php endif; ?>

<!-- Plugins & Libraries -->
<script src="<?= $site_url; ?>/js/msdropdown.js"></script>
<script src="<?= $site_url; ?>/js/jquery.sticky.js"></script>

<!-- Zentrales Custom JS mit Daten-Übergabe -->
<script 
  type="text/javascript" 
  id="custom-js" 
  src="<?= $site_url; ?>/js/customjs.js" 
  data-logged-id="<?= $login_seller_id ?? ''; ?>" 
  data-base-url="<?= $site_url; ?>" 
  data-enable-sound="<?= $login_seller_enable_sound ?? '0'; ?>"
  data-enable-notifications="<?= $login_seller_enable_notifications ?? '0'; ?>"
  data-disable-messages="<?= $disable_messages ?? '0'; ?>"
  defer>
</script>

<!-- Google Translate: Nur laden, wenn aktiviert -->
<?php if($enable_google_translate == 1): ?>
  <script>
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'en',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
      }, 'google_translate_element');
    }
  </script>
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>
<?php endif; ?>

<!-- UI Komponenten -->
<script src="<?= $site_url; ?>/js/categoriesProposal.js" defer></script>
<script src="<?= $site_url; ?>/js/popper.min.js"></script>
<script src="<?= $site_url; ?>/js/owl.carousel.min.js"></script>
<script src="<?= $site_url; ?>/js/bootstrap.js"></script>
<script src="<?= $site_url; ?>/js/summernote.js"></script>