<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

if(isset($_GET['insert_layout_block'])){

	$block_type = $input->get('block_type');
	$valid_types = array("hero","cards","categories","boxes","proposals");
	// Hero, Categories and Proposals are singleton per language - only
	// one instance can exist at a time (Cards/Boxes allow duplicates).
	$singleton_types = array("hero","categories","proposals");

	if(in_array($block_type,$valid_types)){

		$allowed = true;
		if(in_array($block_type,$singleton_types)){
			$count_existing = $db->count("home_layout_blocks",array("language_id" => $adminLanguage,"block_type" => $block_type));
			$allowed = ($count_existing == 0);
		}

		if($allowed){
			$get_max = $db->query("SELECT MAX(position) AS max_position FROM home_layout_blocks WHERE language_id = :lang",array("lang" => $adminLanguage));
			$row_max = $get_max->fetch();
			$next_position = (int)($row_max->max_position ?? 0) + 1;

			$db->insert("home_layout_blocks",array(
				"language_id" => $adminLanguage,
				"block_type" => $block_type,
				"position" => $next_position,
				"enabled" => "yes"
			));
		}

	}

	echo "<script>window.open('index?theme_settings','_self');</script>";

}

}
