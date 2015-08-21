<?php 
require_once "file_db.php";

class Util {
  public static function log($msg) {
    $log = fopen("file_server_log.txt","a");
    fwrite($log,date("h:i:sa").": ".$msg."\n");
    fclose($log);
  }

  public static function log_and_echo($msg) {
    self::log($msg);
    echo $msg;
  }

  public static function log_and_die($msg){
    FileDB::close();
    self::log($msg);
    die($msg);
  }

}

?>