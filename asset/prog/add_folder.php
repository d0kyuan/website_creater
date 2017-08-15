<?php

require_once('db_conf.php');

$name = $_POST['name'];
$parent = $_POST['parent'];
$Page_Own = $_POST['Page_Own'];
$page_path = $_POST['path'];
$db->exec("INSERT INTO `tb_webpage` (`Page_Id`, `Page_Name`, `Page_Parent`, `Page_Own`,`Page_Path`,`Page_Type`) VALUES (NULL, '".$name."', '".$parent."', '".$Page_Own."','".$page_path."','0')");

mkdir($page_path, 0777, true);
?>