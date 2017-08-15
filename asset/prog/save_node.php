<?php
$path = $_POST['path'];
$content= $_POST['content'];
$myfile = fopen($path, "wx")or die("Unable to open file!");
fwrite($myfile,$content);
fclose($myfile);


?>