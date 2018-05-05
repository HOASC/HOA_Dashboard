<?php
ini_set("session.save_path","/var/www/html/session/");
session_start();
include 'includes/dbconn.php';

if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}

date_default_timezone_set('America/Los_Angeles');
if ( $_GET['id'] ){

  $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);



	$fileName = $_GET['id'].'.zip';
	$url = 'https://content.dropboxapi.com/2/files/download';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Dropbox-API-Arg: {"path": "'.$_GET['data'].'"}'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);
	$file = fopen($fileName, "w");
	fwrite($file, $response);
	fclose($file);
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . basename($fileName) . "\""); 
	readfile($fileName);
	unlink($fileName);
	$dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'DOWNLOAD','".$_GET['data']."','".date('Y-m-d H:i:s')."')";
	if ( !pg_query($dropboxInsertQuery) ){
    	// print_r("Failed to insert to dropbox_stats");
    	// print_r(nl2br("\n\n"));
	}
}
?>