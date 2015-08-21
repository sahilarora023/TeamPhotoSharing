<?php 
// server name
$server_name = "sahil";

require_once "file_db.php";
require_once "utility.php";
require_once "file_server_lib.php";

// ======================================================================================================
// Main block begins
// ======================================================================================================

Util::log("Request received: client upload");

// extract picture information
$action   = $_POST['action'];
$from     = $_POST['from'];
$md5_id   = $_POST['md5_id'];
$title    = $_POST['title'];
$category = $_POST['category'];
$desc     = $_POST['desc'];

if ($action != "update" && $action != "delete") {
  Util::log_and_die("Bad Request: unknown action: ".$action);
}

// file id check
if(!$md5_id) {
  Util::log_and_die("Bad Request: file's md5 id is missing");
}

// perform task depending on notification type
FileDB::init();

if ($action == "update") {
  $success = FileDB::update_record($md5_id, $title, $category, $desc);
  if(!$success) {
    Util::log_and_die("Server error: file info update failed");
  }
  Util::log_and_echo("Request processed: file info updated successfully");

} elseif ($action == "delete") {
  $file_path = FileDB::get_file_path($md5_id);
  $success = unlink($file_path);
  if(!$success) {
    Util::log_and_die("Server error: file deletion failed");
  }
  Util::log_and_echo("Request processed: file deleted successfully");
}

FileDB::close();

?>