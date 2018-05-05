<?php
	date_default_timezone_set('America/Los_Angeles');
	include 'includes/dbconn.php';
	$communityID = $_GET['cid'];
	$eventID = $_GET['eid'];
	$telnoquery = "SELECT TELNO FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$communityID;
	$telnoqueryResult = pg_query($telnoquery);

	$telnoassoc = pg_fetch_assoc($telnoqueryResult);

	$telno = $telnoassoc['telno'];



	$query=  "SELECT * FROM COMMUNITY_COMMS WHERE EVENT_TYPE_ID=$eventID AND PERSON_ID IN (SELECT ID FROM PERSON WHERE COMMUNITY_ID  = $communityID) ORDER BY PERSON_ID";
	$queryResult = pg_query($query);
	$phoneAlerts = array();
	$pushAlerts = array();
	$emailAlerts = array(); 
	$hoaPersonIDS  =  array();
	while ($row = pg_fetch_assoc($queryResult)) {
		$hoaPersonIDS[ $row['person_id'] ] = $row['hoa_id'];
		if ( $row['phone'] == 't' ){
			array_push($emailAlerts, $row['person_id']);
		}
		if ( $row['email'] == 't' ){
			array_push($phoneAlerts, $row['person_id']);
		}
		if ( $row['push'] == 't' ){
			array_push($pushAlerts, $row['person_id']);
		}
	}
	$countryQuery = "SELECT * FROM COUNTRY ORDER BY COUNTRY_ID";
	$countryQueryResult = pg_query($countryQuery);

	$countryCodes = array();
	while ($row = pg_fetch_assoc($countryQueryResult)) {
		$countryCodes[$row['country_id']] = $row['tel_prefix'];
	}


	$personCountry = array();

	foreach ($hoaPersonIDS as $key => $value) {
		$hoaQuery = "SELECT HOME_ID FROM HOAID WHERE HOA_ID=$value";
		$hoaQueryResult = pg_query($hoaQuery);
		$homeId = pg_fetch_assoc($hoaQueryResult);
		$homeId = $homeId['home_id'];
		$homeQ = "SELECT * FROM HOMEID WHERE HOME_ID=$homeId";
		$homeQR = pg_query($homeQ);
		$home = pg_fetch_assoc($homeQR);
		if ( $home['living_status'] == 't' ){
			$personCountry[$key] = $home['country_id'];
		}
		else {
			$mailing = "SELECT * FROM HOME_MAILING_ADDRESS WHERE HOME_ID=$homeId";
			$mailingResult = pg_query($mailing);
			$qr = pg_fetch_assoc($mailingResult);
			$personCountry[$key] = $qr['country_id'];
		}
	}


	$toPhoneNumbers = array();
	$personPhoneNumbers = array();
	$toEmails = array();
	$personHOMEID  = array();
	foreach ($phoneAlerts as $key) {
		$phoneNumberQ  = "SELECT CELL_NO,HOME_ID FROM PERSON WHERE ID=$key";
		$phoneNumberQR = pg_query($phoneNumberQ);
		$number = pg_fetch_assoc($phoneNumberQR);
		$personHOMEID[$key] = $number['home_id'];
		$number = $number['cell_no'];

		if ($number){
		$number = base64_decode($number);
		$toPhoneNumbers[$personCountry[$key].$number] = $key;
		$personPhoneNumbers[$key] = $personCountry[$key].$number;
	}
	} 
	foreach ($emailAlerts as $key) {
		$emailQ = "SELECT EMAIL,HOME_ID FROM PERSON WHERE ID=$key";
		$emailQR = pg_query($emailQ);
		$email = pg_fetch_assoc($emailQR);
		$personHOMEID[$key] = $email['home_id'];
		$email = $email['email'];
		if ( $email ){
			array_push($toEmails, $email);
		}
	}

	foreach ($personHOMEID as $key => $value) {
		$qr = "SELECT * FROM HOMEID WHERE HOME_ID=".$value;
		$qrr = pg_query($qr);
		$r = pg_fetch_assoc($qrr);
		$personHOMEID[$key] = $r['address1'];
	}



	$mq = "SELECT BODY FROM MOBILE_NOTIF_BODY WHERE COMMUNITY_ID = $communityID AND  EVENT_TYPE_ID = $eventID";
	$mqr = pg_query($mq);
	$message = pg_fetch_assoc($mqr);
	$body = $message['body'];

	if ( $eventID == 5 ){
		$body = str_replace('#month#', date('F', strtotime('+1 month')) , $body) ;
		$cq = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID = $communityID";
		$cqR = pg_query($cq);
		$cname = pg_fetch_assoc($cqR);
		$cname = $cname['legal_name'];
		$body = $body.' - '.$cname;
		$mbody = $body;
		//SENDING SMS
		foreach ($toPhoneNumbers as $key => $value) {
		$body =  $mbody;
		$body = str_replace('#homeid#', $personHOMEID[$value] , $body) ;
		if ( $communityID == 2 ){
			$accountID = 'AC9370eeb4b1922b7dc29d94c387b3ab56';
			$authToken  = '3b29450d9ce0e5ec7ba6b328f05525a2';
		}


		$url  = 'https://api.twilio.com/2010-04-01/Accounts/'.$accountID.'/Messages.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "Body=$body&To=%2B$key&From=%2B1$telno");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $accountID . ":" . $authToken);
		$headers = array();
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
    		echo 'Error:' . curl_error($ch);
		}
		curl_close ($ch);
		print_r(nl2br("\n\n"));
		print_r("Response is ".$result);

		print_r(nl2br("\n\n"));
		$result = json_decode($result);
		$sid = $result->sid;
		$dateCreated = date('Y-m-d H:i:s',strtotime($result->date_created));
		$dateUpdated = date('Y-m-d H:i:s',strtotime($result->date_updated));
		$toNumber = $result->to;
		$fromNumber = $result->from;
		$status = $result->status;
		$uri = $result->uri;
		$insert = "INSERT INTO SMS_SENT(SID,DATE_CREATED,DATE_UPDATED,FROM_NUMBER,STATUS,URI,PERSON_ID) VALUES('$sid','$dateCreated','$dateUpdated','$fromNumber','$status','$uri',$value)";
		pg_query($insert);
		}
	}
	else if ( $eventID == 4 ){
		$processDate = $_GET['process_date'];
		$month = date('F');
		$docNumber = $_GET['doc_number'];
		$hoaID = $_GET['hoa_id'];
		$query = "SELECT * FROM COMMUNITY_COMMS WHERE HOA_ID=".$hoaID." AND EVENT_TYPE_ID=".$eventID."AND COMMUNITY_ID=".$communityID;
		$queryResult  = pg_query($query);
		$row = pg_fetch_assoc($queryResult);
		if ( $row['person_id'] ){
			if($personPhoneNumbers[$row['person_id']]){

						print_r($body);

						print_r(nl2br("\n\n"));

						$body = str_replace("#month#", date('F'), $body);

						$body = str_replace("#homeid#", $personHOMEID[$row['person_id']], $body);

						$body = str_replace("#document_num#", $docNumber, $body);

						$body = str_replace("#process_date#", $processDate, $body);




						$key = $personPhoneNumbers[$row['person_id']];


						
						if ( $communityID == 2 ){
							$accountID = 'AC9370eeb4b1922b7dc29d94c387b3ab56';
							$authToken  = '3b29450d9ce0e5ec7ba6b328f05525a2';
						}

						$url  = 'https://api.twilio.com/2010-04-01/Accounts/'.$accountID.'/Messages.json';
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, "Body=$body&To=%2B$key&From=%2B1$telno");
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_USERPWD, $accountID . ":" . $authToken);
						$headers = array();
						$headers[] = "Content-Type: application/x-www-form-urlencoded";
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						$result = curl_exec($ch);
						curl_close($ch);
						$result = json_decode($result);
						$sid = $result->sid;
						$dateCreated = date('Y-m-d H:i:s',strtotime($result->date_created));
						$dateUpdated = date('Y-m-d H:i:s',strtotime($result->date_updated));
						$toNumber = $result->to;
						$fromNumber = $result->from;
						$status = $result->status;
						$uri = $result->uri;
						$insert = "INSERT INTO SMS_SENT(SID,DATE_CREATED,DATE_UPDATED,FROM_NUMBER,STATUS,URI,PERSON_ID,updated_by,updated_on,sent_by) VALUES('$sid','$dateCreated','$dateUpdated','$fromNumber','$status','$uri',".$row['person_id'].",401,'".date('Y-m-d H:i:s')."',401)";
						pg_query($insert);
						


			}
		}
		else {
			echo $hoaID." Member not subscribed";
			echo nl2br("\n");
		}
	}
	else {
		exit(0);
	}
?>