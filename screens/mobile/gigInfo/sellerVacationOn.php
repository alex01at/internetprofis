<?php if($proposal_seller_user_name == @$_SESSION['seller_user_name']){ ?>
<h5 class="line-height-full mb-0">
	<?= $lang['seller_vacation']['mobile_own_notice']; ?> <span class="badge badge-success"><?= $lang['on']; ?></span>
	<?= $lang['seller_vacation']['mobile_own_notice2']; ?> <span class="badge badge-success"><?= $lang['off']; ?></span><br> <?= $lang['seller_vacation']['mobile_own_notice3']; ?>
	<a class="text-success" href="<?= $site_url; ?>/proposals/view_proposals.php"><?= $lang['seller_vacation']['click_here']; ?></a>
</h5>
<?php }else{ ?>
<h5 class="line-height-full mb-0">
	<?= $lang['seller_vacation']['mobile_other_notice']; ?> <span class="badge badge-success"> <?= $lang['on']; ?> </span>
	<?= $lang['seller_vacation']['mobile_other_notice2']; ?> <span class="badge badge-success"><?= $lang['off']; ?></span>.
</h5>
<?php } ?>