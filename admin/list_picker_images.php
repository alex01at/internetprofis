<?php
@session_start();
require_once("includes/db.php"); // also pulls in includes/s3-config.php (getFolderName, $allowedImageExtensions, etc.)

header('Content-Type: application/json');

if(!isset($_SESSION['admin_email'])){
	http_response_code(403);
	echo json_encode(array("error" => "Not logged in."));
	exit;
}

$table = $input->get('table');
$folder = getFolderName($table);

if(empty($folder)){
	echo json_encode(array());
	exit;
}

$main_folder = getMainFolderName($folder, $table);
$full_path = $dir.($main_folder != "" ? "$main_folder/" : "")."$folder";

$images = array();

if(is_dir($full_path)){
	$files = scandir($full_path);
	$entries = array();
	foreach($files as $file){
		if($file === "." || $file === "..") continue;
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if(!in_array($ext, $allowedImageExtensions)) continue;
		$entries[$file] = filemtime("$full_path/$file");
	}
	arsort($entries); // newest first

	foreach($entries as $file => $mtime){
		$url_path = ($main_folder != "" ? "$main_folder/" : "")."$folder/".rawurlencode($file);
		$images[] = array(
			"filename" => $file,
			"url" => "$site_url/$url_path"
		);
	}
}

echo json_encode($images);
