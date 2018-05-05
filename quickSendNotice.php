<?php
error_reporting(E_ERROR | E_PARSE);
$jsonData = json_decode(file_get_contents('php://input'));

include 'includes/dbconn.php';
include 'includes/globalvar.php';

$hoaID =  $jsonData->hoa_id;
$noticeName =  $jsonData->notice_name;
if ( $noticeName == 'Trash Can' ){
	$category = 9;
	$subCategory = 35;
	$description = "Trash Can";
	$location = 8;
	$legalDocs  = 3;
}
else if ( $noticeName == 'Basketball' ){
	$category = 11;
	$subCategory = 8;
	$description = "Basketball";
	$location = 8;
	$legalDocs  =  3;
}
else if  ( $noticeName == 'Unsightly Items' ){
	$category = 12;
	$subCategory = 32;
	$description = "Unsightly Item";
	$location = 8;
}
else if ( $noticeName == 'RV' ){
	$category = 13;
	$subCategory = 34;
	$description = "RV";
	$location = 8;

}
else if ( $noticeName == 'Garage Use' ){
	$category = 2;
	$subCategory = 6;
	$description = "Improper Garage Use";
	$location = 8;
	$legalDocs  = 3;
}
$query  = "SELECT * FROM HOAID WHERE HOA_ID = ".$hoaID;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$homeID = $row['home_id'];
$communityID = $row['community_id'];
$toEmail = $row['email'];
$inspectionNoticeType = 1;
$inspectionStatus = 1;
	
	if ( $legalDocs ){
	$query  = "INSERT INTO INSPECTION_NOTICES(\"inspection_date\",\"description\",\"community_id\",\"home_id\",\"date_of_upload\",\"location_id\",\"inspection_category_id\",\"inspection_sub_category_id\",\"hoa_id\",\"inspection_notice_type_id\",\"inspection_status_id\",\"compliance_date\",\"updated_date\",\"updated_by\",\"legal_docs_id\",\"item\") VALUES('".date('Y-m-d')."','".$description."',".$communityID.",".$homeID.",'".date('Y-m-d')."',".$location.",".$category.",".$subCategory.",".$hoaID.",".$inspectionNoticeType.",".$inspectionStatus.",'".date('Y-m-d')."','".date('Y-m-d')."',401,".$legalDocs.",'".$description."') RETURNING id";
	}
	else {
		$query  = "INSERT INTO INSPECTION_NOTICES(\"inspection_date\",\"description\",\"community_id\",\"home_id\",\"date_of_upload\",\"location_id\",\"inspection_category_id\",\"inspection_sub_category_id\",\"hoa_id\",\"inspection_notice_type_id\",\"inspection_status_id\",\"compliance_date\",\"updated_date\",\"updated_by\",\"item\") VALUES('".date('Y-m-d')."','".$description."',".$communityID.",".$homeID.",'".date('Y-m-d')."',".$location.",".$category.",".$subCategory.",".$hoaID.",".$inspectionNoticeType.",".$inspectionStatus.",'".date('Y-m-d')."','".date('Y-m-d')."',401,'".$description."') RETURNING id";

	}
	$queryResult  = pg_query($query);
	$row = pg_fetch_assoc($queryResult);
	$id = $row['id'];
	$req = curl_init();
	curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/generateSingleInspectionNoticeMandrill.php?id=".$id);
	curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
	$docid = curl_exec($req);
	if ( $docid ){
	$subject = "Inspection Notice";
	$body  = "<center><img src=\"cid:".$mandrill_cid."\"></center><br>During regular inspection we found that property was out of compliance with the rules and regulations of the community. Inspection notice is attached with this email.<br>";
	$docID = $result;
	$email = $toEmail;
	$req = curl_init();
	$url = "https://hoaboardtime.com/dropboxToMandrill.php?docid=".$docid."&subject=".$subject."&body=".$body."&email=".$email."&hoaid=".$hoaID;
	$url = str_replace ( ' ', '%20', $url );
	curl_setopt($req, CURLOPT_URL,$url);
	curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($req);
	if ( $result == "An error occured" ) {
		echo "An error occured";
	}
	else {
		echo "Email Sent";
	}
	}
	else{
		echo "Failed to send";
	}
?>