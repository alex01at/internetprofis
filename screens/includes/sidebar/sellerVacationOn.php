<?php if($proposal_seller_user_name == @$_SESSION['seller_user_name']){ ?>
<h4 style="line-height: 25px;">
  <?= $lang['seller_vacation']['sidebar_own_notice']; ?> <span class="badge badge-success">ON</span>. <?= $lang['seller_vacation']['sidebar_own_notice2']; ?> <a class="text-success" href="<?= $site_url; ?>/proposals/view_proposals.php"><?= $lang['seller_vacation']['click_here']; ?></a>
</h4>
<?php }else{ ?>
<h4 style="line-height: 25px;"> <?= $lang['seller_vacation']['sidebar_other_notice']; ?> <span class="badge badge-success">ON</span> </h4>
<?php } ?>