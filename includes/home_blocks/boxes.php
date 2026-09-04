<?php
// Expects $block_id (the home_layout_blocks.id for this Boxes instance)
$get_boxes = $db->query("SELECT * FROM section_boxes WHERE language_id = :lang AND block_id = :block_id ORDER BY box_id ASC", ["lang" => $siteLanguage, "block_id" => $block_id]);
?>

<section class="timer">
  <div class="container" style="max-width: 1335px !important;">
    <div class="row">
      <?php
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
