<?php
ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}
include 'includes/dbconn.php';
include 'includes/globalvar.php';
header("Content-Type: text/event-stream\n\n");
$someJSON = file_get_contents('php://input');
$parsedJSON = json_decode($someJSON);
$hoaID = $parsedJSON[0]->hoaid;

if ($hoaID){	

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/singleBillingStatementGeneration.php?id=".$hoaID);
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);

	
	$dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  	$dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  	$accessToken = base64_decode($dropboxQueryResult['oauth2_key']);


	$url = 'https://content.dropboxapi.com/2/files/download';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Dropbox-API-Arg: {"path": "/Billing_Statements/'.$community_name.'/'.date('Y').'/ZIP/'.$hoaID.'.zip"}'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	$dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'DOWNLOAD','/Inspection_Attachments/".date('Y')."/".$fileName."','".date('Y-m-d H:i:s')."')";
	pg_query($dropboxInsertQuery);
	if ( strpos($response, "Error in call to API function") !== false ) {
		$message =  "An error occured. Please try again.";
		echo $message."";
		exit(0);
	}
	else {
		$fileContent = base64_encode($response);
		$req = curl_init();
		curl_setopt($req, CURLOPT_URL,"http://southdata.us-west-2.elasticbeanstalk.com/TestOrderMailing.aspx?id=".$fileContent."&hoaid=".$hoaID);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		if(curl_exec($req) === false)
		{
    			$message =  "An error occured. Please try again.";
    			echo $message."\n\n";
				exit(0);
		}
		else 
		{	
				$message = "File uploaded to South Data.";
				echo $message."\n\n";  		
		}		
	}

}
else {
	$message =  "An error occured. Invalid HOAID.";
	echo $message."\n\n";  
	
	
	exit(0);
}

?>