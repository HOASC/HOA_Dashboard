<?php 

include 'includes/dbconn.php';
include 'includes/api_keys.php';

$query = "SELECT * FROM COMMUNITY_EMAILS_SENT WHERE COMMUNITY_ID = 2 AND API_MAIL_ID IS NOT NULL";

$queryResult = pg_query($query);

while ($row = pg_fetch_assoc($queryResult)) {



$sendData = array("key" => $m_api_key_3,"id" => $row['api_mail_id']);

$sendData = json_encode($sendData);

print_r($sendData);
print_r(nl2br("\n\n"));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://mandrillapp.com/api/1.0/messages/content.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$sendData);
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = "Content-Type: application/x-www-form-urlencoded";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
$result2  = json_decode($result);
if ( $result2->status == "error" ){
  print_r("An error ocuured");
}
else {
  $query = "UPDATE COMMUNITY_EMAILS_SENT SET BODY='".$result."' WHERE api_mail_id = '".$row['api_mail_id']."'";
  if ( !pg_query($query) ){
  	print_r($query);
  }
  else{
  	print_r("Updated");
  }
}

print_r(nl2br("\n\n\n\n"));
}








?>