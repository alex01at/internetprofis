<?php
// Expects $block_id (the home_layout_blocks.id for this Cards instance)
$get_cards = $db->select("home_cards", ["language_id" => $siteLanguage, "block_id" => $block_id]);
?>

<div class="container mb-5 cards" style="max-width: 1360px !important;">
  <div class="row">
    <div class="col-md-12">
      <div class="owl-carousel home-cards-carousel owl-theme">
        <?php
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
