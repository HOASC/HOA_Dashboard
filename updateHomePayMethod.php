<?php
date_default_timezone_set('America/Los_Angeles');
$insertCount = 0;
$updateCount = 0;

include 'includes/dbconn.php';

$homeidquery = "SELECT * FROM hoaid WHERE community_id = 2";
$homeresult = pg_query($homeidquery);
$homeIDSARRAY = array();




print_r(nl2br("\n\n\n"));

while ($row = pg_fetch_assoc($homeresult)) {
	$homeIDSARRAY[$row['hoa_id']] = $row['home_id'];
}



$customerQuery = "SELECT * FROM HOME_PAY_METHOD WHERE COMMUNITY_ID = 2 AND CLIENTID IS NOT NULL";

$customerQueryResult = pg_query($customerQuery);

$clientIDS = array();


while ($rpw = pg_fetch_assoc($customerQueryResult)) {
	$clientIDS[$rpw['clientid']] = 0;
}
	

$url = "https://api.forte.net/v3/schedules?page_size=10000";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result);
$failedScheduleIDS = array();
$completedSchedules = array();
if ( $result->number_results <= 10000){
foreach ($result->results as $schedule) {


	
	$clientIDS[$schedule->customer_token] = 1;

	if ( $schedule->schedule_summary->schedule_remaining_quantity == 0){
		$completedSchedules[$schedule->schedule_id] = 0;
	}



	if ( $schedule->schedule_summary->schedule_remaining_quantity > 1){

		if ( $schedule->schedule_status == 'active') {
		$url =  "https://api.forte.net/v3/schedules/".$schedule->schedule_id."/scheduleitems?page_size=10000";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$scheduleItemResult = curl_exec($ch);

		$scheduleItemResult = json_decode($scheduleItemResult);

		foreach ($scheduleItemResult->results as $scheduleItemsList) {
			if ( $scheduleItemsList->schedule_item_status == 'scheduled') {
				$updateAmount = $scheduleItemsList->schedule_item_amount;
				break;
			}
			}
		}

		if ( !isset($updateAmount) ) { 
			$updateAmount = $schedule->schedule_amount;
		}

		$remainingMonth = $schedule->schedule_summary->schedule_remaining_quantity;
		$rm = "+".$remainingMonth." months";
		$day = date('d',strtotime($schedule->schedule_start_date));
		$scheduleExpiry = date('Y-m-d', strtotime($rm,strtotime(date('Y-m-'.$day))));
		$updateQuery = "UPDATE home_pay_method SET payment_type_id=1,community_id=2,hoa_id=".$schedule->customer_id.",clientid='".$schedule->customer_token."',sch_start='".date('Y-m-d',strtotime($schedule->schedule_start_date))."',sch_end='".$scheduleExpiry."',sch_expires='".$scheduleExpiry."',next_sch='".date('Y-m-d',strtotime($schedule->schedule_summary->schedule_next_date))."',sch_create_date='".date('Y-m-d',strtotime($schedule->schedule_created_date))."',schedule_qty='".$schedule->schedule_quantity."',no_sch_succ_compltd='".$schedule->schedule_summary->schedule_successful_quantity."',sch_amt=".$updateAmount.",sch_status='".$schedule->schedule_status."',sch_frequency='".$schedule->schedule_frequency."',updated_on='".date('Y-m-d H:i:s')."',updated_by=401 WHERE home_id=".$homeIDSARRAY[$schedule->customer_id];
		// print_r($updateQuery.nl2br("\n\n"));


		if ( pg_query($updateQuery) ){
		$updateCount  = $updateCount + 1;
		}

	}
	else if ( $schedule->schedule_summary->schedule_remaining_quantity == 1) {
		$failedScheduleIDS[$schedule->schedule_id] = 1;
	}
}
}
else {
	// print_r("Number of schedules greater than 1000".nl2br("\n").$failedScheduleIDS);
}

	// print_r(nl2br("\n"));
foreach ($failedScheduleIDS as $key => $value) {
	$url = "https://api.forte.net/v3/schedules/";
	$url = $url.$key."/";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch);
	curl_close($ch);
	$schedule = json_decode($result);
	if ( $schedule->schedule_summary->schedule_next_date){
	$remainingMonth = $schedule->schedule_summary->schedule_remaining_quantity;
		$rm = "+".$remainingMonth." months";
		$day = date('d',strtotime($schedule->schedule_start_date));
		$scheduleExpiry = date('Y-m-d', strtotime($rm,strtotime(date('Y-m-'.$day))));

		if ( $schedule->customer_id ){
			$finalHoaID  = $schedule->customer_id;
		}

		if ( !$finalHoaID ){
			$finalHoaID = $schedule->xdata->xdata_1;
		}

		if ( $schedule->schedule_status == 'active') {
		$url2 =  "https://api.forte.net/v3/schedules/".$schedule->schedule_id."/scheduleitems?page_size=10000";
		$ch2 = curl_init($url2);
		curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch2, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
		$scir2 = curl_exec($ch2);

		$scir2 = json_decode($scir2);

		foreach ($scir2->results as $sil2) {
			if ( $sil2->schedule_item_status == 'scheduled') {
				$updateAmount = $sil2->schedule_item_amount;
				break;
			}
			}
		}

		if ( !isset($updateAmount) ) { 
			$updateAmount = $schedule->schedule_amount;
		}


		$updateQuery = "UPDATE home_pay_method SET payment_type_id=1,community_id=2,hoa_id=".$finalHoaID.",clientid='".$schedule->customer_token."',sch_start='".date('Y-m-d',strtotime($schedule->schedule_start_date))."',sch_end='".$scheduleExpiry."',sch_expires='".$scheduleExpiry."',next_sch='".date('Y-m-d',strtotime($schedule->schedule_summary->schedule_next_date))."',sch_create_date='".date('Y-m-d',strtotime($schedule->schedule_created_date))."',schedule_qty='".$schedule->schedule_quantity."',no_sch_succ_compltd='".$schedule->schedule_summary->schedule_successful_quantity."',sch_amt=".$updateAmount.",sch_status='".$schedule->schedule_status."',sch_frequency='".$schedule->schedule_frequency."',updated_on='".date('Y-m-d H:i:s')."',updated_by=401 WHERE home_id=".$homeIDSARRAY[$finalHoaID];
		// print_r($updateQuery.nl2br("\n\n"));
		if (pg_query($updateQuery)){
			$updateCount  = $updateCount + 1;
		}
		else {
			// print_r($updateQuery.nl2br("\n"));
		}

		// print_r($updateQuery.nl2br("\n"));
	}
	else{
		// print_r($url.nl2br("\n"));
	}
}



foreach ($clientIDS as $key => $value) {
	if ( $value == 0 ){
				$url = "https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/customers/".$key;
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$result = curl_exec($ch);
				curl_close($ch);
				$result = json_decode($result);
				if ( $result->response->response_desc == "This API Access ID does not have permission to access the requested customer_token." ){
					print_r("Delete record ... ".$key);
					$deleteQuery = "DELETE FROM HOME_PAY_METHOD WHERE CLIENTID = '".$key."'";

					if ( !(pg_query($deleteQuery)) ) {
						print_r($deleteQuery);
					}
					else {
						print_r("Removed ".$key);
					}
				}
	}
}

print_r("Records updated : ".$updateCount);
?>