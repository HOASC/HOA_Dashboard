<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}

header("Content-Type: text/event-stream\n\n");
date_default_timezone_set('America/Los_Angeles');
$message  = "Please Wait...";
  echo 'data: '.$message."\n\n";  
  ob_end_flush();
  flush();
include 'includes/dbconn.php';
$cityArray = array();
$query = "SELECT * FROM CITY";
$queryResult = pg_query($query);
while ($row = pg_fetch_assoc($queryResult)) {
  $cityArray[$row['city_id']]  = $row['city_name'];
}
$stateArray = array();
$query = "SELECT * FROM STATE";
$queryResult = pg_query($query);
while ($row = pg_fetch_assoc($queryResult)) {
  $stateArray[$row['state_id']] = $row['state_code'];
}
$zipArray = array();
$query = "SELECT * FROM ZIP";
$queryResult = pg_query($query);
while ($row = pg_fetch_assoc($queryResult)) {
  $zipArray[$row['zip_id']] = $row['zip_code'];
}
if ( !($_GET['id'])){
  $message  = "Failed to generate notice. No HOAID found.";
  echo 'data: '.$message."\n\n";  
  ob_end_flush();
  flush();
  exit(0);
}
if ( !($_GET['doc_id']) ){
    $message  = "Document id not found. Try re generating notice.";
  echo 'data: '.$message."\n\n";  
  ob_end_flush();
  flush();
  exit(0);
}
$message  = "Fetching Inspection Notice...Please Wait...";
  echo 'data: '.$message."\n\n";  
  ob_end_flush();
  flush();
if ( ($_GET['id']) && ($_GET['doc_id']) ){
$hoaID = $_GET['id'];
$documentID = $_GET['doc_id'];

  $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

//Downloading document from Dropbox
$url = 'https://content.dropboxapi.com/2/files/download';
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Dropbox-API-Arg: {"path": "'.$documentID.'"}'));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 $response = curl_exec($ch);
 curl_close($ch);

$dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'DOWNLOAD','".$documentID."','".date('Y-m-d H:i:s')."')";
if ( !pg_query($dropboxInsertQuery) ){
    print_r("Failed to insert to dropbox_stats");
    print_r(nl2br("\n\n"));
}

$pdfFileContent = $response;
$message  = "Generating Tab File....Please Wait...";
  echo 'data: '.$message."\n\n";  
  ob_end_flush();
  flush();
//Creating Tab File
$query = "SELECT FIRSTNAME,LASTNAME,HOME_ID, COMMUNITY_ID FROM HOAID WHERE HOA_ID=".$hoaID;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$communityID = $row['community_id'];
$homeID = $row['home_id'];
$finalAddress1 = $row['firstname'].' '.$row['lastname'];
//Home address
$query = "SELECT * FROM HOMEID WHERE HOME_ID=".$homeID;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$status  = $row['living_status'];
if ( ($status == 'true') || ($status == 'TRUE') || ($status == 't') ){
  $finalAddress2 = $row['address1'];
  $cityID = $row['city_id'];
  $stateID = $row['state_id'];
  $zipID = $row['zip_id'];
}
else {
  $query = "SELECT * FROM HOME_MAILING_ADDRESS WHERE HOME_ID=".$homeID;
  $queryResult = pg_query($query);
  $row  = pg_fetch_assoc($queryResult);
  $finalAddress2 = $row['address1'];
  $cityID = $row['city_id'];
  $stateID = $row['state_id'];
  $zipID = $row['zip_id'];
}
$finalAddress3  = $cityArray[$cityID];
$finalAddress4 = $stateArray[$stateID].' '.$zipArray[$zipID];
$finalAddress5 = "";
$query = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$communityID;
$queryResult = pg_query($query);
while ($row = pg_fetch_assoc($queryResult)) {
        $communityFinalReturnAddress = $row['legal_name'];
        $communityFinalReturnAddress2 = $row['mailing_address'];
        $communityFinalReturnAddress3 = $row['mailing_addr_city'];
        $communityFinalReturnAddress4 = $row['mailing_addr_state'];
        $communityFinalReturnAddress5 = $row['mailing_addr_zip'];
}
 $finalreturnAddress1 = $communityFinalReturnAddress;
    $finalreturnAddress2 = $communityFinalReturnAddress2;
    $finalreturnAddress3 = $cityArray[$communityFinalReturnAddress3];
    $finalreturnAddress4 = $stateArray[$communityFinalReturnAddress4]." ".$zipArray[$communityFinalReturnAddress5];
    $finalPayee = $communityFinalReturnAddress;
//Creating tab file
$handler = fopen($hoaID.'.tab', 'w');
$finalWriteData = "1"."\t".$finalAddress1."\t".$finalAddress2."\t".$finalAddress3." ".$finalAddress4." ".$finalAddress5."\t\t\t1\t"."1"."\t".$hoaID.".pdf\t".$finalreturnAddress1."\t".$finalreturnAddress2." ".$finalreturnAddress3." ".$finalreturnAddress4."\t\t\t".$finalPayee;
fwrite($handler, $finalWriteData);
fclose($handler);
//Creating PDF file
$handler = fopen($hoaID.'.pdf', 'w');
$finalWriteData = $pdfFileContent;
fwrite($handler, $finalWriteData);
fclose($handler);
//Creating ZIP file
if ( file_exists($hoaID.'.zip')){
unlink($hoaID.'.zip');
}
$zip = new ZipArchive;
if ($zip->open($hoaID.'.zip',  ZipArchive::CREATE)) {
$zip->addFile($hoaID.'.pdf', $hoaID.'.pdf');
$zip->addFile($hoaID.'.tab', $hoaID.'.tab');
$zip->close();
$message  = "File will be downloaded shortly.";
$id = $hoaID;
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
}

//Deleting pdf & tab files
unlink($hoaID.'.pdf');
unlink($hoaID.'.tab');
// unlink($hoaID.'.zip');
exit(0);
}
?>