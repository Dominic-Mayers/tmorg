<!DOCTYPE html>
<html>
<head>
<link href="css/sss.css" rel="stylesheet" type="text/css" />
</head>
<?php
include ("/home/dominic/app_download_dev/parsedown/Parsedown.php");
$parsedown = new Parsedown(); 
echo $parsedown->text(file_get_contents("/home/dominic/Documents/TM_Teaching/Intro/Intro_TM_brainstorm.md")); 
?>
</html>
