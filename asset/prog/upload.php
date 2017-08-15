<?php
require_once("db_conf.php");
 date_default_timezone_set("Asia/Taipei");
 switch ($_FILES['mfile'][ 'error' ]){
      case 1:
          // 檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE
          print( "The file is too large (server)." );
          break ;
   
      case 2:
          // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE
          print( "The file is too large (form)." );
          break ;
   
      case 3:
          //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL
          print( "The file was only partially uploaded." );
          break ;
   
      case 4:
          //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE
         print( "No file was uploaded." );
          break ;
   
      case 5:
          //伺服器臨時檔案遺失  
         print( "The servers temporary folder is missing." );
          break ;
   
      case 6:
          //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR
          print( "Failed to write to the temporary folder." );
          break ;

      case 7:
          //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE
         print( "Failed to write file to disk." );
          break ;


      case 8:
          //UPLOAD_ERR_EXTENSION
          $this ->setError( "File upload stopped by extension." );
          break ;
 } 
 echo "tempname :".$_FILES['mfile']['name'];
 echo "tempsize :".$_FILES['mfile']['size'];
$a = explode(".",$_FILES['mfile']['name']);
print_r($a);
 $tempFile = $_FILES['mfile']['tmp_name']; 
print($tempFile);
print($page_path.'.'.$a[sizeof($a)-1]);


$name = $_POST['name'];
$parent = $_POST['parent'];
$Page_Own = $_POST['Page_Own'];
$page_path = $_POST['path'].'.'.$a[sizeof($a)-1];
move_uploaded_file($tempFile,$page_path); // Move uploaded file to destination.


$db->exec("INSERT INTO `tb_webpage` (`Page_Id`, `Page_Name`, `Page_Parent`, `Page_Own`,`Page_Path`,`Page_Type`) VALUES (NULL, '".$name.'.'.$a[sizeof($a)-1]."', '".$parent."', '".$Page_Own."','".$page_path."','1')");




?>  