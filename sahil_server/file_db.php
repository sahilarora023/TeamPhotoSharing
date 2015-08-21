<?php

require_once "utility.php";
require_once "confidentials.php";

class FileDB {
  private static $conn;

  public static function init() {
    self::$conn = mysql_connect($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpass']);
    if(!self::$conn) {
      Util::log_and_die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($GLOBALS['dbname']);
  }

  public static function get_all_pictures() {
    $sql = 'SELECT `file_path`, `from`, `title`, `category`, `desc`
              FROM pictures';
    $records = mysql_query($sql, self::$conn) or Util::log_and_die('Query failed: ' . mysql_error());
    return $records;
  }

  public static function get_file_path($md5_id) {
    // clean user input to avoid sql injection
    // $md5_id = mysqli_escape_string($md5_id);

    $sql = 'SELECT file_path FROM pictures '.
         'WHERE id = "'.$md5_id.'";';
    $records = mysql_query($sql, self::$conn) or Util::log_and_die('Query failed: ' . mysql_error());
    $row = mysql_fetch_row($records);

    return $row and $row[0];
  }

  public static function check_duplicate($md5_id) {
    // clean user input to avoid sql injection
    // $md5_id = mysqli_escape_string($md5_id);

    $sql = 'SELECT file_path FROM pictures '.
         'WHERE id = "'.$md5_id.'";';
    $records = mysql_query($sql, self::$conn) or Util::log_and_die('Query failed: ' . mysql_error());
    $is_duplicate = mysql_num_rows($records)>0;
    return $is_duplicate;
  }

  public static function insert_record($file_path, $from, $md5_id, $title, $category, $desc) {
    // README: assume duplication check has been done
    
    // clean user input to avoid sql injection 
    // $file_path = mysqli_escape_string($file_path);
    // $from = mysqli_escape_string($from);
    // $md5_id = mysqli_escape_string($md5_id);
    // $title = mysqli_escape_string($title);
    // $category = mysqli_escape_string($category);
    // $desc = mysqli_escape_string($desc);

    $sql = 'INSERT INTO pictures '.
         '(`id`, `file_path`, `from`, `title`, `category`, `desc`, `created_at`) '.
         'VALUES ( "'.$md5_id.'", "'.$file_path.'", "'.$from.'", "'.$title.'", "'.$category.'", "'.$desc.'", now() )';
    $success = mysql_query($sql, self::$conn);
    if (!$success) {
      Util::log_and_echo('Insert error: ' . mysql_error());
    }
    return $success;
  }

  public static function update_record($md5_id, $title, $category, $desc) {
    // README: assume duplication check has been done
    
    // clean user input to avoid sql injection 
    // $md5_id = mysqli_escape_string($md5_id);
    // $title = mysqli_escape_string($title);
    // $category = mysqli_escape_string($category);
    // $desc = mysqli_escape_string($desc);

    // build the update string; do not update the field if it is not set
    $set_str_arr = array();
    if ($title) array_push($set_str_arr, 'title="'.$title.'"');
    if ($category) array_push($set_str_arr, 'category="'.$category.'"');
    if ($desc) array_push($set_str_arr, 'desc="'.$desc.'"');
    $set_str = implode(", ", $set_str_arr);

    $sql = 'UPDATE pictures '.
         'SET '.$set_str.
         'WHERE id = "'.$md5_id.'";';
    $success = mysql_query($sql, self::$conn);
    if (!$success) {
      Util::log_and_echo('Update error: ' . mysql_error());
    }
    return $success;
  }

  public static function delete_record($md5_id) {
    // clean user input to avoid sql injection 
    // $md5_id = mysqli_escape_string($md5_id);

    $sql = 'DELETE FROM pictures '.
         'WHERE id = "'.$md5_id.'";';
    $success = mysql_query($sql, self::$conn);
    if (!$success) {
      Util::log_and_echo('delete error: ' . mysql_error());
    }
    return $success;
  }

  public static function close() {
    mysql_close(self::$conn);
  }
  
}


?>