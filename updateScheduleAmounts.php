<?php
date_default_timezone_set('America/Los_Angeles');

include 'includes/dbconn.php';

if (date('m') == 12){
$year =  date('Y')+1;
}
else {
 $year =  date('Y');
}
$query = "select * from assessment_amounts where community_id=2 and assessment_rule_type_id=1 and year = ".$year;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$amount = $row['amount'];
$url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/schedules?page_size=100000';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($ch);
$result = json_decode($result);
$OneCount  = 0;
$TwoCount = 0;
$Other = 0;
foreach ($result->results as $schedule) {
	if ( $schedule->schedule_summary->schedule_remaining_quantity == 1 ){
		$OneCount = $OneCount + 1;
		$url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/schedules/'.$schedule->schedule_id.'/scheduleitems';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$scheduleResult = curl_exec($ch);
		$scheduleResult = json_decode($scheduleResult);
		
		foreach ($scheduleResult->results as $scheduleItems) {
			if ($scheduleItems->schedule_item_status == "scheduled" ){
				
				$newUrl = "https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/scheduleitems/".$scheduleItems->schedule_item_id."/";
				print_r($newUrl);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $newUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"schedule_item_amount\": ".$amount."}");
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				$headers = array();
				$headers[] = "Authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU=";
				$headers[] = "Content-Type: application/json";
				$headers[] = "X-Forte-Auth-Organization-Id: org_332536";
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$resultScheduleItemUpdate = curl_exec($ch);
				print_r($resultScheduleItemUpdate);
				print_r(nl2br("\n\n\n\n\n"));
				if (curl_errno($ch)) {
    					echo 'Error:' . curl_error($ch);
				}
				curl_close ($ch);

			}
		}

	}
	else if ( $schedule->schedule_summary->schedule_remaining_quantity == 2  ){
		$TwoCount = $TwoCount + 1;
	}

	else if ( $schedule->schedule_summary->schedule_remaining_quantity > 2  ){
		$Other = $Other + 1;
	}

}	
print_r($OneCount);
print_r(nl2br("\n\n"));
print_r($TwoCount);
print_r(nl2br("\n\n"));
print_r($Other);
?>