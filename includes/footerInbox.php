<?php if(!isset($_COOKIE['close_cookie'])){ ?>
<section class="clearfix cookies_footer row animated slideInLeft">
<div class="col-md-4">
<img src="<?= $site_url; ?>/images/cookie.png" class="img-fluid" alt="">
</div>
<div class="col-md-8">
<div class="float-right close btn btn-sm"><i class="fa fa-times"></i></div>
<h4 class="mt-0 mt-lg-2"><?= $lang['cookie_box']['title']; ?></h4>
<p class="mb-1"><?= str_replace('{link}', $site_url.'/terms_and_conditions', $lang['cookie_box']['desc']); ?></p>
<a href="#" class="btn btn-success btn-sm"><?= $lang['cookie_box']['button']; ?></a>
</div>
</section>
<?php } ?>

<section class="messagePopup animated slideInRight"></section>

<link rel="stylesheet" href="<?= $site_url; ?>/styles/msdropdown.css"/>
<?php $disable_messages = 1; require("footerJs.php"); ?>