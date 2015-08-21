<?php  
$base_path = "./upload/"; 
$target_path = $base_path . basename ( $_FILES ['uploadfile'] ['name'] ); 
     if ($_FILES["uploadfile"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["uploadfile"]["error"] ;
    }
     else if (file_exists("upload/" . $_FILES["uploadfile"]["name"]))
      {
        echo $_FILES["uploadfile"]["name"] . " already exists. ";
      }
     else 
       {
       move_uploaded_file ( $_FILES ['uploadfile'] ['tmp_name'], $target_path ); 
$target_url = 'http://www.ankitgajbhiye.com/';
$file_name_with_full_path = realpath('./upload/'.$_FILES["uploadfile"]["name"]);
      
$post = array('extra_info' => '123456','uploadfile'=>'@'.$file_name_with_full_path);
 
        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$result=curl_exec ($ch);
	curl_close ($ch);
	echo $result;
$target_url = 'http://www.randeepsingh.net/';
$file_name_with_full_path = realpath('./upload/'.$_FILES["uploadfile"]["name"]);
      
$post = array('extra_info' => '123456','uploadfile'=>'@'.$file_name_with_full_path);
 
        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$result=curl_exec ($ch);
	curl_close ($ch);
	echo $result;

$target_url = 'http://www.martin-xu.net/';
$file_name_with_full_path = realpath('./upload/'.$_FILES["uploadfile"]["name"]);
      
$post = array('extra_info' => '123456','uploadfile'=>'@'.$file_name_with_full_path);
 
        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$result=curl_exec ($ch);
	curl_close ($ch);
	echo $result;

       echo "Upload: " . $_FILES["uploadfile"]["name"] ."\n";
       echo "Stored in: " . "upload/" . $_FILES["uploadfile"]["name"]."\n";  
       echo "Size: " . ($_FILES["uploadfile"]["size"] / 1024) ."\n";
       echo "Temp file: " . $_FILES["uploadfile"]["tmp_name"]."\n" ;
      } 

?>