<?php
@session_start();
require_once("includes/db.php"); // also pulls in includes/s3-config.php (uploadToS3, getFolderName, $allowedImageExtensions, etc.)

header('Content-Type: application/json');

if(!isset($_SESSION['admin_email'])){
	http_response_code(403);
	echo json_encode(array("error" => "Not logged in."));
	exit;
}

$table = $input->post('table');
$folder = getFolderName($table);

if(empty($folder) || empty($_FILES['file']['name'])){
	http_response_code(400);
	echo json_encode(array("error" => "Missing table or file."));
	exit;
}

$filename = basename($_FILES['file']['name']);
$tmp_name = $_FILES['file']['tmp_name'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if(!in_array($ext, $allowedImageExtensions)){
	http_response_code(400);
	echo json_encode(array("error" => "This file extension is not supported."));
	exit;
}

// languages and admins historically upload with a different literal key
// prefix than getFolderName($table) would give (see how insert_language.php
// and insert_user.php call uploadToS3() themselves) - uploadToS3()'s local
// fallback resolves folders in a way that depends on this exact prefix, so
// we replicate it here rather than using getFolderName() directly.
if($table == "languages"){
	$upload_key_folder = "languages_images";
}elseif($table == "admins"){
	$upload_key_folder = "admin_images";
}else{
	$upload_key_folder = $folder;
}

uploadToS3("$upload_key_folder/$filename", $tmp_name);

$main_folder = getMainFolderName($folder, $table);
$url_path = ($main_folder != "" ? "$main_folder/" : "")."$folder/".rawurlencode($filename);

echo json_encode(array(
	"filename" => $filename,
	"url" => "$site_url/$url_path"
));
