<?php
$get_section = $db->select("home_section", ["language_id" => $siteLanguage]);
$row_section = $get_section->fetch();
$section_heading = $row_section->section_heading ?? "";
$section_short_heading = $row_section->section_short_heading ?? "";

$row_slides = $db->query("SELECT slide_image FROM home_section_slider LIMIT 1")->fetch();
$slide_image = $row_slides->slide_image ?? "";
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
