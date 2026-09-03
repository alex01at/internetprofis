<?php
/**
 * Optimierte Home-Section
 */

// 1. Hero-Bereich laden
$get_section = $db->select("home_section", ["language_id" => $siteLanguage]);
$row_section = $get_section->fetch();
$section_heading = $row_section->section_heading ?? "";
$section_short_heading = $row_section->section_short_heading ?? "";

// Slider-Bild (nur das erste)
$row_slides = $db->query("SELECT slide_image FROM home_section_slider LIMIT 1")->fetch();
$slide_image = $row_slides->slide_image ?? "";

// 2. Kategorien vorab laden (JOIN spart Queries in der Schleife)
// Wir laden direkt 8 Stück, um sie später aufzuteilen
$cat_limit = ($lang_dir == "right") ? "DESC" : "ASC";
$get_categories = $db->query("
    SELECT c.cat_id, c.cat_url, c.cat_image, m.cat_title 
    FROM categories c 
    INNER JOIN cats_meta m ON c.cat_id = m.cat_id 
    WHERE c.cat_featured = 'yes' AND m.language_id = :lang 
    ORDER BY c.cat_id $cat_limit 
    LIMIT 8", 
    ["lang" => $siteLanguage]
);
$all_featured_cats = $get_categories->fetchAll();
?>

<section class="pb-5 pt-5">
  <div class="container">
    <div class="row flex-center">
      <div class="col-lg-6 col-md-5 order-md-1">
        <object type="image/svg+xml" data="images/computer-desk.svg" width="100%" aria-label="Computer Desk Illustration"></object>
      </div>
      <div class="col-md-7 col-lg-6 mt-5 text-center text-md-start">
        <h1><?= htmlspecialchars($section_heading); ?></h1>
        <p class="mt-3 mb-4"><?= htmlspecialchars($section_short_heading); ?></p>
        <a class="btn btn-lg btn-danger hover-top btn-glow" href="<?= ($siteLanguage == 2 ? 'how-it-works-de' : 'how-it-works'); ?>">
            <i class="fa fa-question-circle mr-1"></i> <?= $lang['titles']['how_it_works']; ?>
        </a>
      </div>
    </div>
  </div>
</section>

<div class="container mb-5 cards" style="max-width: 1360px !important;">
  <div class="row">
    <div class="col-md-12">
      <div class="owl-carousel home-cards-carousel owl-theme">
        <?php
        $get_cards = $db->select("home_cards", ["language_id" => $siteLanguage]);
        while($row_cards = $get_cards->fetch()):
          $card_image = getImageUrl("home_cards", $row_cards->card_image); 
        ?>
        <div class="card-box">
          <div>
            <a href="<?= $row_cards->card_link; ?>" class="subcategory">
              <h4>
                <small><?= htmlspecialchars($row_cards->card_desc); ?></small>
                <?= htmlspecialchars($row_cards->card_title); ?>
              </h4>
              <picture>
                <img src="<?= $card_image; ?>" alt="<?= htmlspecialchars($row_cards->alt ?? $row_cards->card_title); ?>">
              </picture>
            </a>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>

<section class="market">
  <div class="container" style="max-width: 1360px !important;">
    <div class="row">
      <div class="col-md-12">
        
        <div class="row space80">
          <?php 
          $first_row = array_slice($all_featured_cats, 0, 4);
          foreach($first_row as $cat): 
            $cat_img = getImageUrl("categories", $cat->cat_image);
          ?>
          <div class="col-md-3 col-6">
            <a href="categories/<?= $cat->cat_url; ?>">
              <div class="grn_box">
                <img src="<?= $cat_img; ?>" alt="<?= htmlspecialchars($cat->cat_title); ?>" class="mx-auto d-block" width="96" height="96">
                <p><?= htmlspecialchars($cat->cat_title); ?></p>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="space80 hidden-xs"></div>
        <div class="space20 visible-xs"></div>

        <div class="row space80">
          <?php 
          $second_row = array_slice($all_featured_cats, 4, 4);
          foreach($second_row as $cat): 
            $cat_img = getImageUrl("categories", $cat->cat_image);
          ?>
          <div class="col-md-3 col-6">
            <a href="categories/<?= $cat->cat_url; ?>">
              <div class="grn_box">
                <img src="<?= $cat_img; ?>" alt="<?= htmlspecialchars($cat->cat_title); ?>" class="mx-auto d-block" width="96" height="96">
                <p><?= htmlspecialchars($cat->cat_title); ?></p>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </div>
</section>

<section class="timer">
  <div class="container" style="max-width: 1335px !important;">
    <div class="row">
      <?php
      // Wir holen alle Boxen auf einmal
      $get_boxes = $db->query("SELECT * FROM section_boxes WHERE language_id = :lang ORDER BY box_id ASC", ["lang" => $siteLanguage]);
      $i = 0;
      while($row_boxes = $get_boxes->fetch()):
        $box_image = getImageUrl("section_boxes", $row_boxes->box_image);
        $bg_class = ($i == 0) ? "blu_box" : "blu_box1"; // Wechselnde CSS Klassen
      ?>
      <div class="col-md-4 pad0">
        <div class="box">
          <h5><?= $row_boxes->box_title; ?></h5>
          <p><?= $row_boxes->box_desc; ?></p>
        </div>
      </div>
      <div class="col-md-4 pad0">
        <div class="blu_box <?= $bg_class; ?>">
          <img src="<?= $box_image; ?>" class="img-fluid mx-auto d-block" alt="<?= $row_boxes->box_title; ?>">
        </div>
      </div>
      <?php $i++; endwhile; ?>
    </div>
  </div>
</section>

<section class="top mb-0">
  <div class="container" style="max-width: 1360px !important;">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center"><?= $lang['home']['proposals']['title']; ?></h1>
        <h4 class="text-center"><?= $lang['home']['proposals']['desc']; ?></h4>
        
        <?php 
        // Proposals holen - JOIN mit Sellers spart massiv Queries
        $get_proposals = $db->query("
            SELECT p.*, s.seller_user_name, s.seller_image, s.seller_level, s.seller_status 
            FROM proposals p 
            INNER JOIN sellers s ON p.proposal_seller_id = s.seller_id 
            WHERE p.proposal_featured = 'yes' AND p.proposal_status = 'active' 
            LIMIT 10");
        ?>
        
        <div class="mt-5">
          <div class="row">
            <?php
            while($row_proposals = $get_proposals->fetch()):
              $proposal_id = $row_proposals->proposal_id;
              
              // Preis-Logik optimiert
              $proposal_price = $row_proposals->proposal_price;
              if($proposal_price == 0){
                $get_p_1 = $db->select("proposal_packages", ["proposal_id" => $proposal_id, "package_name" => "Basic"]);
                $proposal_price = $get_p_1->fetch()->price ?? 0;
              }

              // Verkäufer Daten (bereits im JOIN geladen!)
              $seller_user_name = $row_proposals->seller_user_name;
              $seller_image = getImageUrl2("sellers", "seller_image", $row_proposals->seller_image);
              if(empty($row_proposals->seller_image)) $seller_image = "empty-image.png";

              // Rating-Logik (Könnte man noch in SQL lösen, hier erst mal bereinigt)
              $select_buyer_reviews = $db->select("buyer_reviews", ["proposal_id" => $proposal_id]);
              $count_reviews = $select_buyer_reviews->rowCount();
              $average_rating = 0;
              if($count_reviews > 0){
                $total_rating = 0;
                while($review = $select_buyer_reviews->fetch()) { $total_rating += $review->buyer_rating; }
                $average_rating = $total_rating / $count_reviews;
              }
            ?>
            <div class="col-xl-2dot4 col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-4">
              <?php require("includes/proposals.php"); ?>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>