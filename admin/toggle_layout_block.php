<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

if(isset($_GET['toggle_layout_block'])){

	$block_id = $input->get('toggle_layout_block');

	$get_block = $db->select("home_layout_blocks",array("id" => $block_id));
	$row_block = $get_block->fetch();

	if($row_block){
		$new_status = ($row_block->enabled == "yes") ? "no" : "yes";
		$db->update("home_layout_blocks",array("enabled" => $new_status),array("id" => $block_id));
	}

	echo "<script>window.open('index?theme_settings','_self');</script>";

}

}
