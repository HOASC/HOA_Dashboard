<?php
if ( $_GET['id'] ){
$file_url = $_GET['id'].'.zip';
if( !file_exists($file_url)){
	echo "Cannot find file";
	exit(0);
}
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
readfile($file_url);
unlink($_GET['id'].'.zip');
}
?>