<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
print_r(phpversion());
date_default_timezone_set('America/Los_Angeles');
include 'includes/dbconn.php';

if (1){
//Connection success
$query = "SELECT * FROM community_transient_documents WHERE  COMMUNITY_ID=2";
$results = pg_query($query);
//Reading each row
while($row = pg_fetch_assoc($results)){
$valid_until = $row['valid_until'];
$fileName = $row['document_name'];
$documentID = $row['id'];
$valid_until = date_create($valid_until);
date_sub($valid_until, date_interval_create_from_date_string('1 days'));
$valid_until = date_format($valid_until,'Y-m-d H:i:s');
$current_time = date('Y-m-d H:i:s');
//Record needs to be updated
if ( $current_time >= $valid_until ){
	//Create a new valid until
	$newDateTime = date_create($current_time);
	date_add($newDateTime,date_interval_create_from_date_string('7 days'));
	$newDateTime = date_format($newDateTime,'Y-m-d H:i:s');
	print_r("Updating transient document ");
	$path = "/";
	$path = $fileName.".pdf";
	$data = array('File' => '@'.$path);
	$ch = curl_init('https://api.na1.echosign.com/api/rest/v5/transientDocuments');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: multipart/form-data', 
    'Access-Token: 3AAABLblqZhBbuoGJoQZXIMhISUIAnh7R_qmzGgn_COsBf1G0kXyDFiaXxE-oM8ZMaL1LPybdYz1U2gYXszLLzpLuenZ3Ojfm'));
	$result = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($result);
	$transientDocumentID = $result->transientDocumentId;
	$updateQuery = "UPDATE community_transient_documents SET transient_document_id='".$transientDocumentID."',valid_until='".$newDateTime."',updated_by = 401,updated_on='".date('Y-m-d H:i:s')."' WHERE id=".$documentID;
	$tableName = "community_transient_documents";
	$insertData = array();
	$insertData['transient_document_id'] = $transientDocumentID;
	$insertData['valid_until'] = $newDateTime;
	$condition = array();
	$condition['id'] = $documentID;
	pg_update($dbconnection, $tableName, $insertData, $condition);
}//End Record need to be updated block
//No need to update record
else {
	print_r("Document need not be updated");
	$updateQuery23 = "UPDATE community_transient_documents SET updated_by = 401,updated_on='".date('Y-m-d H:i:s')."' WHERE id=".$documentID;
	pg_query($updateQuery23);
	// $tableName = "community_transient_documents";
}//End of No need to update record block
}//End of while block
}//End of Connection Success If case block
//Connection Failed
else {
	print_r("Failed to connect to database");
}//End of Connection Failed block
?>