<?php
date_default_timezone_set('America/Los_Angeles');

include 'includes/dbconn.php';

$query = "SELECT * FROM current_payments WHERE payment_status_id !=1 AND community_id = 2 AND last_updated_on < '".date('Y-m-d')."' AND bank_transaction_id IS NOT NULL";
$queryResult = pg_query($query);
while ($row = pg_fetch_assoc($queryResult)) {
$url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions/';
$url = $url.$row['bank_transaction_id'];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($ch);
$result = json_decode($result);
if ( $result->status != 'voided'){
		if ( $result->status == 'funded' ){
			$paymentStatusIDUpdate = 1;
		}
		else if ( $result->status == 'settling' ){
			$paymentStatusIDUpdate = 8;
			$updateHPM = 1;
		}
		else if ( $result->status == 'approved' ){
			$paymentStatusIDUpdate = 6;
			$updateHPM = 1;
		}
		else if ( $result->status == 'ready' ){
			$paymentStatusIDUpdate = 10;
			$updateHPM = 1;
		}
		$updateStatus = "UPDATE current_payments SET payment_status_id=$paymentStatusIDUpdate , last_updated_on='".date('Y-m-d')."' , updated_by = 401 WHERE id=".$row['id'];
		pg_query($updateStatus);
}
}
?>