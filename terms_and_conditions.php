<?php
  session_start();
  require_once("includes/db.php");
  require_once("social-config.php");
  ?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
  <head>
    <title><?= $site_name; ?> - <?=$lang['terms_title'];?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/categories_nav_styles.css" rel="stylesheet">
     
    <link href="styles/owl.carousel.css" rel="stylesheet">
    <link href="styles/owl.theme.default.css" rel="stylesheet">
    <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
  </head>
  <body class="is-responsive">
    <?php require_once("includes/header.php"); ?>
    <div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h1><?= $lang['our_policies']; ?></h1>
            <p class="lead pb-4"><?=$lang['terms_desc'];?></p>
        </div>
    </div>
    <?php

// Abrufen der AGB-Begriffe
$get_terms = $db->query("SELECT * FROM terms WHERE language_id='$siteLanguage'");
$terms = $get_terms->fetchAll();

?>

<div class="row terms-page" style="<?= ($lang_dir == "right" ? 'direction: rtl;' : '') ?>">
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-pills flex-column mt-2">
          <?php foreach ($terms as $term) : ?>
            <li class="nav-item">
              <a class="nav-link <?= $term === $terms[0] ? 'active' : '' ?>" data-toggle="pill" href="#<?= $term->term_link; ?>">
                <?= $term->term_title; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-md-9">
    <div class="card">
      <div class="card-body">
        <div class="tab-content">
          <?php foreach ($terms as $term) : ?>
            <div id="<?= $term->term_link; ?>" class="tab-pane fade <?= $term === $terms[0] ? 'show active' : '' ?>">
              <h2 class="mb-4"><?= $term->term_title; ?></h2>
              <p class="text-justify">
                <?= $term->term_description; ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>


    <?php require_once("includes/footer.php"); ?>
  </body>
</html>