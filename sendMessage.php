<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('America/Los_Angeles');

include 'includes/dbconn.php';

$data = file_get_contents('php://input');
$parseJSON = json_decode($data);

//Getting country prefix numbers 

$countryQuery = "SELECT * FROM COUNTRY";

$countryQueryResult = pg_query($countryQuery);

$countryPrefixArray = array();

while ($row = pg_fetch_assoc($countryQueryResult)) {
	$countryPrefixArray[$row['country_id']] = $row['tel_prefix'];
}



//Configure Auth
		
if ( $parseJSON[0]->community_id == 2){
			$accountID = 'AC9370eeb4b1922b7dc29d94c387b3ab56';
			$authToken  = '3b29450d9ce0e5ec7ba6b328f05525a2';
}


//Get From Number

$telQuery = "SELECT telno FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$parseJSON[0]->community_id;

$telQueryResult = pg_query($telQuery);

$fromPhoneNumber = pg_fetch_assoc($telQueryResult)['telno'];

if ( !$fromPhoneNumber ){
	echo "Community does not have a phone number configured.";
	exit(0);
}



$homeQuery = "SELECT * FROM HOMEID WHERE COMMUNITY_ID=".$parseJSON[0]->community_id;

$homeQueryResult = pg_query($homeQuery);

$homeIDS = array();

$homeCountries = array();

while ($row23 = pg_fetch_assoc($homeQueryResult)) {
	$homeIDS[$row23['home_id']] = $row23['address1'];
	$homeCountries[$row23['home_id']] = $row23['country_id'];
}



$message = $parseJSON[0]->message_body;

$message = str_replace('\n', '%0a', $message);


if ( $parseJSON[0]->mode == "all" ){
	
	$query = "SELECT * FROM COMMUNITY_COMMS WHERE COMMUNITY_ID=".$parseJSON[0]->community_id." AND EVENT_TYPE_ID=".$parseJSON[0]->event_type;
	$queryResult = pg_query($query);
	$personIDS = array();
	while ($row  = pg_fetch_assoc($queryResult)) {
		$personIDS[$row['person_id']] = 1;
	}
	foreach ($personIDS as $key => $value) {
		$messageSub = $message;
		$query = "SELECT * FROM PERSON WHERE ID=".$key;
		$queryResult = pg_query($query);
		$row = pg_fetch_assoc($queryResult);
		if ( $row['cell_no'] ){

		$toNumber = $homeCountries[$row['home_id']].base64_decode($row['cell_no']);

		$messageSub = str_replace('#address#', $homeIDS[$row['home_id']], $messageSub);
		$messageSub = str_replace('#name#', $row['fname'].' '.$row['lname'], $messageSub);
		$messageSub = str_replace('#month#', date('F'), $messageSub);
	

		$url  = 'https://api.twilio.com/2010-04-01/Accounts/'.$accountID.'/Messages.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "Body=".$messageSub."&To=%2B".$toNumber."&From=%2B1".$fromPhoneNumber);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $accountID . ":" . $authToken);
		$headers = array();
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);

		$result = json_decode($result);
		$sid = $result->sid;
		$dateCreated = date('Y-m-d H:i:s',strtotime($result->date_created));
		$dateUpdated = date('Y-m-d H:i:s',strtotime($result->date_updated));
		$toNumber = $result->to;
		$fromNumber = $result->from;
		$status = $result->status;
		$uri = $result->uri;

		$insertToSMS  = "INSERT INTO SMS_SENT(sid,date_created,date_updated,from_number,status,uri,person_id,updated_by,updated_on,sent_by) VALUES('".$sid."','".$dateCreated."','".$dateUpdated."','".$fromNumber."','".$status."','".$uri."',".$key.",".$parseJSON[0]->sender.",'".date('Y-m-d H:i:s')."',".$parseJSON[0]->sender.")";

		if ( !pg_query($insertToSMS)){
				echo $query;
		}
		echo "SMS sent";


		}
		else {
			echo "No Phone number found.";
		}
	}

}
else if ( $parseJSON[0]->mode == "single" ){
	
	$query = "SELECT * FROM HOAID WHERE HOA_ID=".$parseJSON[0]->hoa_id;
	$queryResult = pg_query($query);
	$row = pg_fetch_assoc($queryResult);
	if ( $row['cell_no'] ){
		$messageSub = $message;
		$messageSub = str_replace('#address#', $homeIDS[$row['home_id']], $messageSub);
		$messageSub = str_replace('#name#', $row['firstname'].' '.$row['lastname'], $messageSub);
		$messageSub = str_replace('#month#', date('F'), $messageSub);

		$toNumber = $homeCountries[$row['home_id']].base64_decode($row['cell_no']);
	
		$url  = 'https://api.twilio.com/2010-04-01/Accounts/'.$accountID.'/Messages.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "Body=".$messageSub."&To=%2B".$toNumber."&From=%2B1".$fromPhoneNumber);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $accountID . ":" . $authToken);
		$headers = array();
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);


		$result = json_decode($result);
		$sid = $result->sid;
		$dateCreated = date('Y-m-d H:i:s',strtotime($result->date_created));
		$dateUpdated = date('Y-m-d H:i:s',strtotime($result->date_updated));
		$toNumber = $result->to;
		$fromNumber = $result->from;
		$status = $result->status;
		$uri = $result->uri;

		$personQuery = "SELECT * FROM PERSON WHERE CELL_NO='".base64_encode($row['cell_no'])."'";
		$personQueryResult = pg_query($personQuery);
		$personData = pg_fetch_assoc($personQueryResult);
		$personID  = $personData['id'];
		if ( $personID ){
			$insertToSMS  = "INSERT INTO SMS_SENT(sid,date_created,date_updated,from_number,status,uri,person_id,updated_by,updated_on,sent_by) VALUES('".$sid."','".$dateCreated."','".$dateUpdated."','".$fromNumber."','".$status."','".$uri."',".$personID.",".$parseJSON[0]->sender.",'".date('Y-m-d H:i:s')."',".$parseJSON[0]->sender.")";
			if ( !pg_query($insertToSMS)){
				echo $query;
			}	
		echo "SMS sent";
	}
	else 
	{
		echo "No Phone number found.";
	}
}
}

else {
	exit(0);
}


?>