<?php
  session_start();
  require_once("../includes/db.php");
  require_once("../functions/functions.php");

  // Initialisierung
  $cat_id = null;
  $cat_child_id = null;
  $cat_title = "";
  $cat_desc = "";

  if(isset($_GET['cat_url'])){
    if(isset($_GET['cat_child_url'])){
      $array = explode("/", $input->get('cat_url'));
      $cat_url = $array[0];
    } else {
      $cat_url = $input->get('cat_url');
    }
    
    unset($_SESSION['cat_child_id']);
    $get_cat = $db->select("categories", array('cat_url' => urlencode($cat_url)));
    
    if($get_cat->rowCount() == 0){
      echo "<script>window.open('$site_url/index?not_available','_self');</script>";
      exit();
    }
    
    $row_cat = $get_cat->fetch();
    $cat_id = $row_cat->cat_id;
    $_SESSION['cat_id'] = $cat_id;
  }

  if(isset($_GET['cat_child_url'])){
    unset($_SESSION['cat_id']);
    // Wir brauchen die Parent ID für die Suche nach der Child-Kategorie
    $get_cat = $db->select("categories", array('cat_url' => urlencode($cat_url)));
    $row_parent = $get_cat->fetch();
    $parent_id = $row_parent ? $row_parent->cat_id : 0;

    $get_child = $db->select("categories_children", array(
      'child_parent_id' => $parent_id,
      'child_url' => urlencode($input->get('cat_child_url'))
    ));
    
    if($get_child->rowCount() == 0){
      echo "<script>window.open('$site_url/index?not_available','_self');</script>";
      exit();
    }
    
    $row_child = $get_child->fetch();
    $cat_child_id = $row_child->child_id;
    $_SESSION['cat_child_id'] = $cat_child_id;
  }

  // Metadaten einmalig abrufen
  if(isset($_SESSION['cat_id'])){
    $get_meta = $db->select("cats_meta", array("cat_id" => $_SESSION['cat_id'], "language_id" => $siteLanguage));
    if($row_meta = $get_meta->fetch()){
      $cat_title = $row_meta->cat_title;
      $cat_desc = $row_meta->cat_desc;
    }
  } elseif(isset($_SESSION['cat_child_id'])){
    $get_meta = $db->select("child_cats_meta", array("child_id" => $_SESSION['cat_child_id'], "language_id" => $siteLanguage));
    if($row_meta = $get_meta->fetch()){
      $cat_title = $row_meta->child_title;
      $cat_desc = $row_meta->child_desc;
    }
  }
?>
<!DOCTYPE html>
<html lang="de" class="ui-toolkit">
<head>
  <title><?= $site_name; ?> - <?= $cat_title; ?></title>
  <meta name="description" content="<?= $cat_desc; ?>" >
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="<?= $site_url; ?>/styles/bootstrap.css" rel="stylesheet">
  <link href="<?= $site_url; ?>/styles/custom.css" rel="stylesheet">
  <link href="<?= $site_url; ?>/styles/styles.css" rel="stylesheet">
  <link href="<?= $site_url; ?>/styles/categories_nav_styles.css" rel="stylesheet">
  <link href="<?= $site_url; ?>/font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="<?= $site_url; ?>/styles/scoped_responsive_and_nav.css" rel="stylesheet">
  <link href="<?= $site_url; ?>/styles/vesta_homepage.css" rel="stylesheet">
  <link href="<?= $site_url; ?>/styles/sweat_alert.css" rel="stylesheet">
  <script src="<?= $site_url; ?>/js/ie.js"></script>
  <script type="text/javascript" src="<?= $site_url; ?>/js/sweat_alert.js"></script>
  <script type="text/javascript" src="<?= $site_url; ?>/js/jquery.min.js"></script>
  <?php if(!empty($site_favicon)){ ?>
  <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
</head>
<body class="bg-white is-responsive">
<?php require_once("../includes/header.php"); ?>

<div class="container-fluid mt-2">
  <div class="p-5 text-center bg-image" style="background-image: url('https://mdbcdn.b-cdn.net/img/new/slides/041.webp'); height: 350px; margin-top: 28px;">
    <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
      <div class="d-flex justify-content-center align-items-center h-100 m-2 p-2">
        <div class="text-white">
          <h1 class="mb-3"><?= $cat_title; ?></h1>
          <h4 class="mb-3"><?= $cat_desc; ?></h4>
          <?php if(!isset($_SESSION['seller_user_name'])){ ?>
            <a href="#" data-toggle="modal" data-target="#register-modal" class="btn btn-outline-light btn-lg"><?= $lang['become_seller']; ?></a>
            <a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-outline-light btn-lg"><?= $lang['sign_in']; ?></a>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-lg-3 col-md-4 col-sm-12 <?=($lang_dir == "right" ? 'order-2 order-sm-1':'')?>">
      <?php require_once("../includes/category_sidebar.php"); ?>
    </div>
    <div class="col-lg-9 col-md-8 col-sm-12 <?=($lang_dir == "right" ? 'order-1 order-sm-2':'')?>">
      <div class="row flex-wrap proposals <?=($lang_dir == "right" ? 'justify-content':'')?>" id="category_proposals">
        <?php get_category_proposals(); ?>
      </div>
      <div id="wait"></div>
      <br>
      <div class="row justify-content-center mb-5 mt-0">
        <nav>
          <ul class="pagination" id="category_pagination">
            <?php get_category_pagination(); ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</div>

<div class="append-modal"></div>
<?php require_once("../includes/footer.php"); ?>

<script>
  function get_category_proposals(){
    var sPath = ''; 
    
    // Checkboxen sammeln (Online Sellers)
    var aOnline = $('.get_online_sellers:checked').map(function(){ return $(this).val(); }).get();
    $.each(aOnline, function(i, val){ sPath += 'online_sellers[]=' + val + '&'; });

    var instant_delivery = $('.get_instant_delivery:checked').val() || 0;
    sPath += 'instant_delivery[]=' + instant_delivery + '&'; 

    var order = $('.get_order:checked').val() || "DESC";
    sPath += 'order[]=' + order + '&';

    // Länder sammeln
    $('.get_seller_country:checked').each(function(){ sPath += 'seller_country[]=' + $(this).val() + '&'; });

    // Städte sammeln
    $('.get_seller_city:checked').each(function(){ sPath += 'seller_city[]=' + $(this).val() + '&'; });

    var cat_url = "<?= htmlspecialchars($input->get('cat_url')); ?>";
    sPath += 'cat_url=' + cat_url + '&';

    <?php if(isset($_REQUEST['cat_child_url'])){ ?>
      var cat_child_url = "<?= htmlspecialchars($input->get('cat_child_url')); ?>";
      sPath += 'cat_child_url=' + cat_child_url + '&';
      var url_plus = "../";
    <?php } else { ?>
      var url_plus = "";
    <?php } ?>

    // Weitere Filter
    $('.get_delivery_time:checked').each(function(){ sPath += 'delivery_time[]=' + $(this).val() + '&'; });
    $('.get_seller_level:checked').each(function(){ sPath += 'seller_level[]=' + $(this).val() + '&'; });
    $('.get_seller_language:checked').each(function(){ sPath += 'seller_language[]=' + $(this).val() + '&'; });

    $('#wait').addClass("loader");    
    
    $.ajax({  
      url: url_plus + "../category_load",  
      method: "POST",  
      data: sPath + 'zAction=get_category_proposals',  
      success: function(data){
        $('#category_proposals').html(data);
        $('#wait').removeClass("loader");
      }  
    });               
    
    $.ajax({  
      url: url_plus + "../category_load",  
      method: "POST",  
      data: sPath + 'zAction=get_category_pagination',  
      success: function(data){  
        $('#category_pagination').html(data); 
      }  
    });
  }

  // Event Listener für alle Filter
  $(document).on('click', '.get_online_sellers, .get_instant_delivery, .get_order, .get_seller_country, .get_seller_city, .get_delivery_time, .get_seller_level, .get_seller_language', function(){
    get_category_proposals();
  });

  $(document).ready(function(){
    // Land/Stadt Logik
    $(document).on('click', ".get_seller_country", function(){
      if($(".get_seller_country:checked").length > 0){
        $(".clear_seller_country").show();
        $('.seller-cities li').addClass('d-none');
        
        $(".get_seller_country:checked").each(function(){
          var country = $(this).val();
          $('.seller-cities li[data-country="'+country+'"]').removeClass('d-none');
        });

        if($('.seller-cities li:not(.d-none)').length > 0){
          $(".seller-cities").removeClass('d-none');
        } else {
          $(".seller-cities").addClass('d-none');
        }
      } else {
        $(".seller-cities").addClass('d-none');
        $(".clear_seller_country").hide();
        clearCity();
      }
    });

    // Sichtbarkeit der "Clear" Buttons
    $(document).on('click', '.get_seller_city', function(){ ( $(".get_seller_city:checked").length > 0 ) ? $(".clear_seller_city").show() : $(".clear_seller_city").hide(); });
    $(document).on('click', '.get_delivery_time', function(){ ( $(".get_delivery_time:checked").length > 0 ) ? $(".clear_delivery_time").show() : $(".clear_delivery_time").hide(); });
    $(document).on('click', '.get_seller_level', function(){ ( $(".get_seller_level:checked").length > 0 ) ? $(".clear_seller_level").show() : $(".clear_seller_level").hide(); });
    $(document).on('click', '.get_seller_language', function(){ ( $(".get_seller_language:checked").length > 0 ) ? $(".clear_seller_language").show() : $(".clear_seller_language").hide(); });
  });

  // Clear Funktionen
  function clearCountry(){ $('.get_seller_country, .get_seller_city').prop('checked',false); $(".seller-cities, .clear_seller_country").hide(); get_category_proposals(); }
  function clearCity(){ $('.get_seller_city').prop('checked',false); $(".clear_seller_city").hide(); get_category_proposals(); }
  function clearDelivery(){ $('.get_delivery_time').prop('checked',false); $(".clear_delivery_time").hide(); get_category_proposals(); }
  function clearLevel(){ $('.get_seller_level').prop('checked',false); $(".clear_seller_level").hide(); get_category_proposals(); }
  function clearLanguage(){ $('.get_seller_language').prop('checked',false); $(".clear_seller_language").hide(); get_category_proposals(); }
</script>
</body>
</html>