<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

if(isset($_GET['move_layout_block'])){

	$block_id = $input->get('move_layout_block');
	$dir = $input->get('dir');

	$get_block = $db->select("home_layout_blocks",array("id" => $block_id));
	$row_block = $get_block->fetch();

	if($row_block){

		$language_id = $row_block->language_id;
		$position = $row_block->position;

		if($dir == "up"){
			$get_neighbor = $db->query("SELECT * FROM home_layout_blocks WHERE language_id = :lang AND position < :pos ORDER BY position DESC LIMIT 1",array("lang" => $language_id,"pos" => $position));
		}else{
			$get_neighbor = $db->query("SELECT * FROM home_layout_blocks WHERE language_id = :lang AND position > :pos ORDER BY position ASC LIMIT 1",array("lang" => $language_id,"pos" => $position));
		}
		$row_neighbor = $get_neighbor->fetch();

		if($row_neighbor){
			$db->update("home_layout_blocks",array("position" => $row_neighbor->position),array("id" => $row_block->id));
			$db->update("home_layout_blocks",array("position" => $position),array("id" => $row_neighbor->id));
		}

	}

	echo "<script>window.open('index?theme_settings','_self');</script>";

}

}
