<?php
date_default_timezone_set('America/Los_Angeles');
$someJSON = file_get_contents('php://input');
$parsedJSON = json_decode($someJSON);
$documentCategory = $parsedJSON[0]->documentCategory;
$documentName = $parsedJSON[0]->documentName;
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

// $data = date('Y-m-d');

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


$agreementInforMation = array();
$query = "SELECT * FROM community_library_documents WHERE COMMUNITY_ID=2";
$queryResult = pg_query($query);
while ($row = pg_fetch_assoc($queryResult)) {
	$agreementInforMation[$row['document_name']] = $row['library_document_id'];
}

$query = "SELECT * FROM community_transient_documents WHERE COMMUNITY_ID=2";
$queryResult = pg_query($query);
while ($row = pg_fetch_assoc($queryResult)) {
	$agreementInforMation[$row['document_name']] = $row['transient_document_id'];
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
// $recipientSetInfos = array("recipientSetInfos" => $recipientSetInfos);

$ccInfo = array();
foreach ($ccs as $key) {
	$insideData = array();
	array_push($ccInfo, $key);
}
// $ccInfo = array("ccs" => $ccInfo);

//formFields
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
// $signatureFlowInfo = array("signatureFlow"=>$signatureFlow);
// $fieldInfo = array("formFields"=>$fieldInfo);
$messageInfo = $customMessage;
$documentID = $agreementInforMation[$documentName];
// $fileInfo = array("fileInfos"=>array(array("transientDocumentId"=>$documentID)));

$nameInfo = $agreementName;
//Generate Final JSON File
if ( $documentCategory == 'Transient Document'){
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
	echo "Agreeement Created Successfully. Agreeement ID : ".$result->agreementId;
}
else {
	echo $result;
}

}
else {
	$finalJSON = array("documentCreationInfo"=>array("signatureType"=>"ESIGN","recipientSetInfos" => $recipientSetInfos,"ccs" => $ccInfo,"signatureFlow"=>$signatureFlow,"formFields"=>$fieldInfo,"message"=>$messageInfo,"fileInfos"=>array(array("libraryDocumentId"=>$documentID)),"name"=>$nameInfo));
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
	echo "Agreeement Created Successfully. Agreeement ID : ".$result->agreementId;
}
else {
	echo $result;
}
}

?>