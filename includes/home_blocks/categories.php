<?php
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
