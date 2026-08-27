  <div class="card seller-bio mb-3 rounded-0">
  <div class="card-body <?=($lang_dir == "right" ? 'text-right':'')?>">

  <?php 
// Wir nutzen die globalen Variablen $db und $login_seller_id (falls vorhanden)
if(check_status($db, $proposal_seller_id, @$login_seller_id) == "Online"){ 
?>
<div class="is-online"> <i class="fa fa-circle"></i> <?= check_status($db, $proposal_seller_id, @$login_seller_id); ?> </div>
<?php } ?>

  <center class="mb-4">
  <?php if(!empty($proposal_seller_image)){ ?>
  <img src="<?= getImageUrl2("sellers","seller_image",$proposal_seller_image); ?>" width="130" class="rounded-circle">
  <?php }else{ ?>
  <img src="../../user_images/empty-image.png" width="130" class="rounded-circle">
  <?php } ?>
  </center>
  <h3 class="text-center h3">
  <a class="text-success" href="../../<?= $proposal_seller_user_name; ?>" >
  <?= ucfirst($proposal_seller_user_name); ?>
  </a> <span class="divider"> </span> <span class="text-muted"><?= $level_title; ?></span>
  </h3>
  <?php if($proposal_seller_vacation == "on"){ ?>
  <a href="#" class="btn btn-lg btn-block btn-message rounded-0">Sorry I’m away</a>
  <?php }else{ ?>
  <a href="../../conversations/message?seller_id=<?= $proposal_seller_id; ?>" class="btn btn-lg btn-block btn-success rounded-0"><?=$lang["message_me"];?></a>
  <?php } ?>
  <hr>
  <div class="row">
  <div class="col-md-6">
  <p class="text-muted"><i class="fa fa-check pr-1"></i> <?=$lang["from"];?></p>
  </div>
  <div class="col-md-6">
  <p> <?= $proposal_seller_country; ?></p>
  </div>
  <div class="col-md-6">
  <p class="text-muted"><i class="fa fa-check pr-1"></i>  <?=$lang["speaks"];?></p>
  </div>
  <div class="col-md-6">
  <p>
  <?php
  $select_languages_relation = $db->select("languages_relation",array("seller_id" => $proposal_seller_id));
  while($row_languages_relation = $select_languages_relation->fetch()){
     $language_id = $row_languages_relation->language_id;
     
     $get_language = $db->select("seller_languages",array("language_id" => $language_id));
     $row_language = $get_language->fetch();
     $language_title = @$row_language->language_title;

  ?>
  <span><?= $language_title; ?></span>
  <?php } ?>
  </p>
  </div>
  <div class="col-md-6">
  <p class="text-muted"><i class="fa fa-check pr-1"></i><?= $lang['proposal']['positive_reviews'];?></p>
  <p class="text-muted"><i class="fa fa-check pr-1"></i><?= $lang['user_profile']['recent_delivery'];?></p>
  </div>
  <div class="col-md-6">
  <p> <?= $proposal_seller_rating; ?>% </p>
  <p> <?= $proposal_seller_recent_delivery; ?> </p>
  </div>
  </div>
  <hr>
  <p class="text-left <?=($lang_dir == "right" ? 'text-right':'')?>"> <?= $proposal_seller_about; ?> </p>
  <a href="../../<?= $proposal_seller_user_name; ?>" class="text-success"> <?= $lang["to_profile"]; ?> <?=$proposal_seller_user_name;?></a>
</div>

</div>
