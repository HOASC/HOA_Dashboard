<?php
date_default_timezone_set('America/Los_Angeles');
$someJSON = file_get_contents('php://input');
$parsedJSON = json_decode($someJSON);
$agreementTitle = $parsedJSON[0]->agreementTitle;
$emailAd = $parsedJSON[0]->emailAddresses;
$ccEMails = $parsedJSON[0]->ccAddresses;
$signatureType = $parsedJSON[0]->signType;
$roleFinal = $parsedJSON[0]->roleType;
$signFlow = $parsedJSON[0]->signFlow;
$custom = $parsedJSON[0]->customMessage;
$inORder = $parsedJSON[0]->completeInOrder;
$pass = $parsedJSON[0]->passwordStatus;
$setPassword = $parsedJSON[0]->setPassword;
$hoaID = $parsedJSON[0]->hoaID;
$fileData  = $parsedJSON[0]->file_data;
$fileName = md5(microtime().rand());
$fileName = $fileName.".pdf";
$myfile = fopen($fileName, "w");
fwrite($myfile, base64_decode($fileData));
fclose($myfile);
$path = $fileName;
$postData = array('File' => '@'.$path);
$ch = curl_init('https://api.na1.echosign.com/api/rest/v5/transientDocuments');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: multipart/form-data', 
    'Access-Token: 3AAABLblqZhBbuoGJoQZXIMhISUIAnh7R_qmzGgn_COsBf1G0kXyDFiaXxE-oM8ZMaL1LPybdYz1U2gYXszLLzpLuenZ3Ojfm'));
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result);
$transientDocumentID = "";
if (  $result->transientDocumentId ){
$transientDocumentID = $result->transientDocumentId;
}
else {
	echo "An error occured.^Failed to upload file";
	exit(0);
}
$data = date('Y-m-d');

include 'includes/dbconn.php';
//Fetch UserDetails 
$query = "SELECT * FROM HOAID WHERE HOA_ID=".$hoaID;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$memberName = $row['firstname'].' '.$row['lastname'];
$cellNumber = $row['cell_no'];
$homeID = $row['home_id'];
$query = "SELECT * FROM HOMEID WHERE HOME_ID=".$homeID;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$homeAddress = $row['address1'];
//Fetch Owner Details
$query = "SELECT * FROM PERSON WHERE HOME_ID=".$homeID;
$queryResult = pg_query($query);

$ownerNames = "";
$tenantNames = "";

while ($row = pg_fetch_assoc($queryResult)) {
	if ( ($row['role_type_id'] == 1) ) {
		$ownerNames  = $ownerNames.' '.$row['fname'].' '.$row['lname'];
	}
}

$emailArrays = explode(' ', $emailAd);
$ccs = explode(' ', $ccEMails);
$role = $roleFinal;
$signatureFlow = $signFlow;
$top = 50;
$customMessage = $custom;
$password = "PASSWORD";
$agreementName = $agreementTitle;
$recipientSetInfos = array();
foreach ($emailArrays as $key) {
	$insideData = array("recipientSetMemberInfos"=>array(array("email" => $key)),"recipientSetRole"=>"SIGNER");
	array_push($recipientSetInfos, $insideData);
}

$ccInfo = array();
foreach ($ccs as $key) {
	$insideData = array();
	array_push($ccInfo, $key);
}

$count = 1 ;
$top = 50;
$fieldInfo = array();
foreach ($emailArrays as $key ) {
	$insideData = array("displayLabel"=>"SIGN BLOCK ".$count,"recipientIndex"=>"".$count."","contentType"=>"SIGNATURE",
		"maxLength"=>"50","minLength"=>"1","locations" => array(array("height"=>"20","width"=>"100","pageNumber"=>"1","left"=>"155","top"=>"".$top."")),"name"=>"sign_".$count,"inputType"=>"SIGNATURE","required"=>"true","defaultValue"=>"","fontSize"=>"8.0","readOnly"=>"false","borderWidth"=>"1.0","tooltip"=>"Sign by entering your name or drawing your signature.");
	$count = $count+1;
	$top = $top+55;
	array_push($fieldInfo, $insideData);
}

$messageInfo = $customMessage;
$documentID = $transientDocumentID;

$nameInfo = $agreementName;


$mergeFieldInfoArray = array();

array_push($mergeFieldInfoArray, array("defaultValue" => $emailAd,"fieldName"=>"email") );
array_push($mergeFieldInfoArray, array("defaultValue" => $memberName,"fieldName"=> "member_name") );
array_push($mergeFieldInfoArray, array("defaultValue" => $homeAddress ,"fieldName"=>"address"));
array_push($mergeFieldInfoArray, array("defaultValue" => $homeAddress ,"fieldName"=>"property_address") );
array_push($mergeFieldInfoArray, array("defaultValue" => $ownerNames ,"fieldName"=> "home_owner_names") );
array_push($mergeFieldInfoArray, array("defaultValue" => $cellNumber ,"fieldName"=> "owner_cell_phone_number") );



$finalJSON = array("documentCreationInfo"=>array("signatureType"=>"ESIGN","mergeFieldInfo"=> $mergeFieldInfoArray,"recipientSetInfos" => $recipientSetInfos,"ccs" => $ccInfo,"signatureFlow"=>$signatureFlow,"formFields"=>$fieldInfo,"message"=>$messageInfo,"fileInfos"=>array(array("transientDocumentId"=>$documentID)),"name"=>$nameInfo));
$finalJSON = json_encode($finalJSON);

$ch = curl_init('https://api.na1.echosign.com/api/rest/v5/agreements');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $finalJSON);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json', 
    'Access-Token: 3AAABLblqZhBbuoGJoQZXIMhISUIAnh7R_qmzGgn_COsBf1G0kXyDFiaXxE-oM8ZMaL1LPybdYz1U2gYXszLLzpLuenZ3Ojfm'));
$result = curl_exec($ch);
$result = json_decode($result);

if($result->agreementId){
	echo $result->agreementId;
}
else {
	echo "An error occured.^".$result;
}
unlink($fileName);

?>