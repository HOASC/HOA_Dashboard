<?php
	date_default_timezone_set('America/Los_Angeles');
	include 'includes/dbconn.php';
	$communityID = $_GET['cid'];
	$eventID = $_GET['eid'];

	if ( !isset($communityID) ){
		exit(0);
	}
	if ( !isset($eventID) ){
		exit(0);
	}

	//Getting list of members

	if (  isset($communityID) && isset($eventID) ){
		$query = "SELECT * FROM COMMUNITY_COMMS WHERE COMMUNITY_ID =".$communityID." AND EVENT_TYPE_ID = ".$eventID." AND email='true'";
		$queryResult = pg_query($query);

		if ( $eventID == 5 ){

			while ($row = pg_fetch_assoc($queryResult)) {
				$hoaID = $row['hoa_id'];
				$personID = $row['person_id'];
				$subQuery = "SELECT EMAIL FROM PERSON WHERE ID=".$personID;
				$subQueryResult = pg_query($subQuery);
				$subRow = pg_fetch_assoc($subQueryResult);
				$email = $subRow['email'];
				if ( isset($email) ){
						$url = "https://hoaboardtime.com/sendViaMandrill.php?hoaid=".$hoaID."&email=".$email;
						$req = curl_init();
						curl_setopt($req, CURLOPT_URL,$url);
						curl_exec($req);					
				}
			}
		}


	}
	else {
		echo "Invalid input given.";
		exit(0);
	}

?>