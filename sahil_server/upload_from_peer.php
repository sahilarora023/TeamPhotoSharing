<?php 
// server name
$server_name = "sahil";

require_once "file_db.php";
require_once "utility.php";
require_once "file_server_lib.php";

// ======================================================================================================
// Configuration block begins
// ======================================================================================================

// limit upload file to image types
$allowed_types  = array(
  "jpg",
  "jpeg",
  "bmp",
  "gif",
  "png",
  "tiff"
  );

// maxmum upload size
define("MAXSIZE", 4096*1000);

// ======================================================================================================
// Main block begins
// ======================================================================================================
Util::log("Request received: peer upload");

// extract picture information
$file     = $_FILES['file'];
$from     = $_POST['from'];
$md5_id   = $_POST['md5_id'];
$title    = $_POST['title'];
$category = $_POST['category'];
$desc     = $_POST['desc'];

// check file data
if(!$file) {
  Util::log_and_die("Bad peer upload request: no file data");
}

// check required field
if(!$file || !$from || !$md5_id || !$title) {
  Util::log_and_die("Bad peer upload request: required fields are missing");
}

// initiate db connection
FileDB::init();

// duplication check
if (FileDB::check_duplicate($md5_id)){
  Util::log_and_die("Bad peer upload request: duplicated file for ".$md5_id);
};

// type and size check
$type = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
$size = $_FILES['file']['size'];
if ($size>MAXSIZE) {
  Util::log_and_die("Bad client upload request: file exceed size limit(".MAXSIZE."kb)");
} elseif (!in_array($type, $allowed_types)) {
  Util::log_and_die("Bad client upload request: unacceptable file format");
}


// build upload path
$upload_dir   = "uploads/";
$ext          = $type;
$upload_path  = $upload_dir.$md5_id.".".$ext;

// save the uploaded file to filesystem and add record to database
$success = move_uploaded_file($file["tmp_name"], $upload_path) && FileDB::insert_record($upload_path, $from, $md5_id, $title, $category, $desc);
if ($success) {
} else {
  Util::log_and_die("Server error: upload failed");
}

FileDB::close();

Util::log_and_echo("Request processed: file uploaded successfully");
?>