<?php
// error_reporting(E_ERROR | E_PARSE);

include 'includes/dbconn.php';
ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}


$jsonData = json_decode(file_get_contents('php://input'));
try{
if (true){
	$hoaID = $jsonData[0]->hoa_id;
	$homeID = $jsonData[0]->home_id;
	$category = $jsonData[0]->category;
	$subCategory = $jsonData[0]->sub_category;
	$location = $jsonData[0]->location;
	$description = $jsonData[0]->description;
	$noticeType = $jsonData[0]->notice_type;
	$status = $jsonData[0]->status;
	$complianceDate = $jsonData[0]->compliance_date;
	$fileData = $jsonData[0]->file_data;
	$fileName = $jsonData[0]->file_name;
	$legalDocument = $jsonData[0]->legal_document;
	$techID = "";
	if ( $fileData != ""){
		//Upload to dropbox
		$name =  uniqid();
		$type = explode('.', $name);
		$type = end($type);
		$name = $name.".".$type;
		$file = fopen($name, "w");
		fwrite($file, base64_decode($fileData));
		fclose($file);

  		$dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  		$dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  		$accessToken = base64_decode($dropboxQueryResult['oauth2_key']);



		$url = 'https://content.dropboxapi.com/2/files/upload';
		$fileContents = file_get_contents($name);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Inspection_Attachments/'.date('Y').'/'.$fileName.'","mode": "add","autorename": true,"mute": false}'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContents); 
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		$dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','/Inspection_Attachments/".date('Y')."/".$fileName."','".date('Y-m-d H:i:s')."')";
		pg_query($dropboxInsertQuery);
		unlink($name);
		
		$decodeData = json_decode($response);
		$fileID  = $decodeData->id;
		$query = "INSERT INTO DOCUMENT_MANAGEMENT(\"active\",\"description\",\"month_of_upload\",\"uploaded_date\",\"url\",\"year_of_upload\",\"community_id\",\"member_id\",\"hoa_id\",\"tech_id\") VALUES('TRUE','".$fileName."','".date('M')."','".date('Y-m-d H:i:s')."','/Inspection_Attachments/".date('Y')."/',".date('Y').",(SELECT COMMUNITY_ID FROM HOAID WHERE HOA_ID=".$hoaID."),(SELECT MEMBER_ID FROM MEMBER_INFO WHERE HOA_ID=".$hoaID."),".$hoaID.",'".$fileID."') RETURNING document_id";
				$result = pg_query($query);
		$row = pg_fetch_assoc($result);
		$techID =  $row['document_id'];
		$query = "INSERT INTO INSPECTION_NOTICES(\"attachment\",\"inspection_date\",\"description\",\"community_id\",\"home_id\",\"date_of_upload\",\"location_id\",\"inspection_category_id\",\"inspection_sub_category_id\",\"hoa_id\",\"inspection_notice_type_id\",\"document_id\",\"inspection_status_id\",\"compliance_date\",\"updated_date\",\"updated_by\",\"legal_docs_id\") VALUES('".$fileName."','".date('Y-m-d')."','".$description."',(SELECT COMMUNITY_ID FROM HOAID WHERE HOA_ID=".$hoaID."),"."(SELECT HOME_ID FROM HOMEID WHERE ADDRESS1='$homeID')".",'".date('Y-m-d')."',(SELECT LOCATION_ID FROM LOCATIONS_IN_COMMUNITY WHERE LOCATION='".$location."' AND COMMUNITY_ID = 2),(SELECT ID FROM INSPECTION_CATEGORY WHERE NAME='".$category."'),(SELECT ID FROM INSPECTION_SUB_CATEGORY WHERE NAME='".$subCategory."' AND inspection_category_id=(SELECT ID FROM INSPECTION_CATEGORY WHERE NAME='".$category."') ),".$hoaID.",(SELECT ID FROM INSPECTION_NOTICE_TYPE WHERE NAME='".$noticeType."'),".$techID.",(SELECT ID FROM INSPECTION_STATUS WHERE INSPECTION_STATUS='".$status."'),'".$complianceDate."','".date('Y-m-d')."',401,(SELECT ID FROM COMMUNITY_LEGAL_DOCS WHERE NAME='".$legalDocument."')) RETURNING ID";
		$queryResult = pg_query($query);
		$row = pg_fetch_assoc($queryResult);
		echo $row['id'];
		exit(0);
	}
	else {
	$query = "INSERT INTO INSPECTION_NOTICES(\"inspection_date\",\"description\",\"community_id\",\"home_id\",\"date_of_upload\",\"location_id\",\"inspection_category_id\",\"inspection_sub_category_id\",\"hoa_id\",\"inspection_notice_type_id\",\"inspection_status_id\",\"compliance_date\",\"updated_date\",\"updated_by\",\"legal_docs_id\") VALUES('".date('Y-m-d')."','".$description."',(SELECT COMMUNITY_ID FROM HOAID WHERE HOA_ID=".$hoaID."),"."(SELECT HOME_ID FROM HOMEID WHERE ADDRESS1='$homeID')".",'".date('Y-m-d')."',(SELECT LOCATION_ID FROM LOCATIONS_IN_COMMUNITY WHERE LOCATION='".$location."' AND COMMUNITY_ID = 2),(SELECT ID FROM INSPECTION_CATEGORY WHERE NAME='".$category."'),(SELECT ID FROM INSPECTION_SUB_CATEGORY WHERE NAME='".$subCategory."' AND inspection_category_id=(SELECT ID FROM INSPECTION_CATEGORY WHERE NAME='".$category."') ),".$hoaID.",(SELECT ID FROM INSPECTION_NOTICE_TYPE WHERE NAME='".$noticeType."'),(SELECT ID FROM INSPECTION_STATUS WHERE INSPECTION_STATUS='".$status."'),'".$complianceDate."','".date('Y-m-d')."',401,(SELECT ID FROM COMMUNITY_LEGAL_DOCS WHERE NAME='".$legalDocument."')) RETURNING ID";
	$queryResult = pg_query($query);
	$row = pg_fetch_assoc($queryResult);
	echo $row['id'];
	exit(0);
}
}
}
catch(Exception  $e){
	echo "An error occured";
}
?>