<?php

require_once('db_conf.php');

$name = $_POST['name'];
$parent = $_POST['parent'];
$Page_Own = $_POST['Page_Own'];
$page_path = $_POST['path'];

$e = $db->query("select * from tb_config where Conf_Name like '".$Page_Own."'");
$row = $e->fetch(PDO::FETCH_ASSOC); 
$myfile = fopen($row['Conf_Path'].'/'.$name, "wx")or die("Unable to open file!");
fwrite($myfile,'<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="Shortcut Icon" type="image/x-icon" href="asset/icon/icon.png" />
    <title>新文件</title>
</head>
<body>
    
</body>
</html>');
fclose($myfile);
print($row['Conf_Path'].'/'.$name);

$db->exec("INSERT INTO `tb_webpage` (`Page_Id`, `Page_Name`, `Page_Parent`, `Page_Own`,`Page_Path`,`Page_Type`) VALUES (NULL, '".$name."', '".$parent."', '".$Page_Own."','".$page_path."','1')");



?>