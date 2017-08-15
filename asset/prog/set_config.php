<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php
require_once('db_conf.php');
$name = $_POST['web_name'];
$account = $_POST['web_account'];
$password = $_POST['web_password'];
#print($_SERVER['DOCUMENT_ROOT'].'/'.$name);
if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$name)) {
        mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$name, 0777, true);
        mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$name.'/asset', 0777, true);
        mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$name.'/asset/icon', 0777, true);
        //print($_SERVER['DOCUMENT_ROOT'].'/'.$name.'/asset/icon/'.$_FILES["web_icon"]["name"]);
        if (move_uploaded_file($_FILES["web_icon"]["tmp_name"],$_SERVER['DOCUMENT_ROOT'].'/'.$name.'/asset/icon/icon.png')) {
            $db->exec("INSERT INTO tb_config (Conf_Id,Conf_Name, Conf_Path, Conf_Account, Conf_Password) VALUES (NULL,'".$name."','".$_SERVER['DOCUMENT_ROOT'].'/'.$name."','".$account."','".$password."')");
           # print("INSERT INTO tb_config (Conf_Id,Conf_Name, Conf_Path, Conf_Account, Conf_Password ) VALUES (NULL,'".$name."','".$_SERVER['DOCUMENT_ROOT'].'/'.$name."','".$account."','".$password."')");
           # print('<script>location.href="../../get_start.php"');
       
            
   
            $Page_Own = $name;
            $name = 'index.html';
            $myfile = fopen($_SERVER['DOCUMENT_ROOT'].'/'.$Page_Own."/".$name, "wx")or die("Unable to open file!");
fwrite($myfile,'
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="Shortcut Icon" type="image/x-icon" href="asset/icon/icon.png" />
        <title>新文件</title>
    </head>
    <body>

    </body>
</html>
');
            fclose($myfile);
            $db->exec("INSERT INTO `tb_webpage` (`Page_Id`, `Page_Name`, `Page_Parent`, `Page_Own`,`Page_Path`) VALUES (NULL, '".$name."', '0', '".$Page_Own."','".$_SERVER['DOCUMENT_ROOT'].'/'.$Page_Own."/".$name."')");
            print('<script>alert("'.$_SERVER['DOCUMENT_ROOT'].'/'.$Page_Own."/".$name.'");location.href="../../get_start.php"</script>');
        }else{
           delTree($_SERVER['DOCUMENT_ROOT'].'/'.$name) ;
             goout("發生錯誤!無法建立網站");  
        }
}else{
   goout("網站名稱已經存在");
}

function goout($a){ die($a.json_encode(array("static"=>"error","message"=>$a)));
}

 function delTree($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
    } 
    return rmdir($dir); 
  } 
?>
</body>
</html>