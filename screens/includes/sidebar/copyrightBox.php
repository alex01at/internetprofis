<div class="mb-5 <?=($lang_dir == "left" ? 'text-center':'')?> copyright-box <?=($lang_dir == "right" ? 'text-right':'')?>"><!--- copyright-box Starts --->
<p class="mb-2 <?=($lang_dir == "right" ? 'text-right':'')?>">
Copyright &copy; <a href="../../<?= $proposal_seller_user_name; ?>" class="strike"><?= ucfirst($proposal_seller_user_name); ?></a>. All Rights Reserved.
</p>

<?php
if(isset($_SESSION['seller_user_name'])){
	if($login_seller_id != $proposal_seller_id){
		$count_reports = $db->count("reports",array("reporter_id"=>$login_seller_id,"content_id"=>$proposal_id));
			// if($count_reports == 0){
?>
<p><a class="text-danger <?=($lang_dir == "right" ? 'text-right':'')?>" href="#" data-toggle="modal" data-target="#report-modal"><i class="fa fa-flag"></i><?=$lang["report_proposal"];?></a></p>
<?php 
		// }
	}
}
?>
</div><!--- copyright-box Ends --->
