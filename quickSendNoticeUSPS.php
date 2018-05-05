<?php
error_reporting(E_ERROR | E_PARSE);
$jsonData = json_decode(file_get_contents('php://input'));

include 'includes/dbconn.php';
ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}

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
	curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/generateSingleInspectionNoticeSouthData.php?id=".$id);
	curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
	$docid = curl_exec($req);

	 $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  	$dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  	$accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

	$url = 'https://content.dropboxapi.com/2/files/download';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Dropbox-API-Arg: {"path": "'.$docid.'"}'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	$fileContents = base64_encode($response);
	$req = curl_init();

	$dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'DOWNLOAD','".$docid."','".date('Y-m-d H:i:s')."')";
	pg_query($dropboxInsertQuery);

		curl_setopt($req, CURLOPT_URL,"http://southdata.us-west-2.elasticbeanstalk.com/TestOrderMailing.aspx?id=".$fileContent."&hoaid=".$hoaID);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		if(curl_exec($req) === false)
		{
    			$message =  "An error occured. Please try again.";
    			echo $message;
				exit(0);
		}
		else 
		{	
				$message = "File uploaded to South Data.";
				echo $message;  		
		}
?>