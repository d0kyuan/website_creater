<?php

require_once('db_conf.php');

$id = $_POST['id'];
$parent = $_POST['parent'];
$newpath = $_POST['path'];
$oldpath = $_POST['oldpath'];
$db->exec("update  `tb_webpage` set Page_Parent = '".$parent."',Page_Path = '".$newpath."' where Page_Id = '".$id."' ");
rename($oldpath,$newpath);


?>