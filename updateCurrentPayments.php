<?php
date_default_timezone_set('America/Los_Angeles');
$insertCount = 0;
$updateCount  = 0;
include 'includes/dbconn.php';
$query  = "SELECT * FROM current_payments WHERE community_id=2 AND  date_part('year',last_updated_on) = EXTRACT(year FROM CURRENT_DATE) AND date_part('month',last_updated_on) = EXTRACT(month FROM CURRENT_DATE)";
$homeIDQuery = "SELECT * FROM hoaid WHERE community_id=2";
$hoIDresult = pg_query($homeIDQuery);
$hoaIDSArray = array();
while( $hoarow = pg_fetch_assoc($hoIDresult)){
	$hoaIDSArray[$hoarow['hoa_id']] =  $hoarow['home_id'];
}
$result = pg_query($query);
$bankTransactionsIDSArray = array();
while ($row = pg_fetch_assoc($result)) {
	$bankTransactionsIDSArray[$row['bank_transaction_id']] = $row['payment_status_id'];
}
$startDate = date('Y-m-01');
$failedTransactionIDS = array();
$url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions?filter=start_received_date%20eq%20';
$url = $url.$startDate;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result);
if ($result->number_results <= 10000){
$url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions?filter=start_received_date%20eq%20';
$url = $url.$startDate.'&page_size='.$result->number_results;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result);
$val = 0;
foreach ($result->results as $transaction) {
		$updateHPM = 0;
	if ( $bankTransactionsIDSArray[$transaction->transaction_id] ){
		if ( $transaction->status != 'voided'){
		if ( $transaction->status == 'funded' ){
			$paymentStatusIDUpdate = 1;
		}
		else if ( $transaction->status == 'settling' ){
			$paymentStatusIDUpdate = 8;
			$updateHPM = 1;
		}
		else if ( $transaction->status == 'approved' ){
			$paymentStatusIDUpdate = 6;
			$updateHPM = 1;
		}
		else if ( $transaction->status == 'ready' ){
			$paymentStatusIDUpdate = 10;
			$updateHPM = 1;
		}
		$val = $val+1;
		$transactionAmount = $transaction->authorization_amount;
		if ($transaction->action == 'credit' || $transaction->action == 'CREDIT'){
			$transactionAmount = -$transaction->authorization_amount;
		}

		$updateQuery = "UPDATE current_payments SET amount=".$transactionAmount.",payment_status_id=".$paymentStatusIDUpdate.",last_updated_on='".date("Y-m-d")."' WHERE bank_transaction_id='".$transaction->transaction_id."' RETURNING HOA_ID,PROCESS_DATE,DOCUMENT_NUM";
		// print_r("Count is ".$val." ".$updateQuery.nl2br("\n"));
		$VAL = pg_query($updateQuery);
		$VAL = pg_fetch_assoc($VAL);


		$val = $VAL['hoa_id'];

		$processDate = $VAL['process_date'];

		$documentNumber = $VAL['document_num'];



		if ( $val ){
		if ( $paymentStatusIDUpdate == 1 ){
			if ( $bankTransactionsIDSArray[$transaction->transaction_id] != 1){
				//SEND SMS
				$url23 = "https://hoaboardtime.com/sendAlert.php?cid=2&eid=4&hoa_id=".$val."&process_date=".$processDate."&doc_number=".$documentNumber;
				print_r($url23);
				$req = curl_init();
				curl_setopt($req, CURLOPT_URL,$url23);
				curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
				$message  = curl_exec($req);
				print_r($message);
				print_r(nl2br("\n\n\n"));
			}
		}
		}

		$updateCount = $updateCount + 1;
		if ( $updateHPM ){
			$qr = "UPDATE HOME_PAY_METHOD SET PAYMENT_TYPE_ID=1 WHERE HOA_ID=".$val;
			pg_query($qr);
		}
	}
	}
	else{

	if ( $transaction->status != 'voided'){
		// print_r(nl2br("\n"));
		// print_r($transaction->transaction_id);
		if ( $transaction->customer_id ){
			$hoaID = $transaction->customer_id;
		}
		else if ( $transaction->order_number){
			$hoaID = $transaction->order_number;
		}
		else if ( $transaction->reference_id ){
			$hoaID = $transaction->reference_id;
		}
		else if ( $transaction->echeck->item_description ){
			$hoaID = $transaction->echeck->item_description;
		}
		if ( $transaction->status == 'funded' ){
			$paymentStatusID = 1;
			$updateHPM = 1;
		}
		else if ( $transaction->status == 'settling' ){
			$paymentStatusID = 8;
			$updateHPM = 1;
		}
		else if ( $transaction->status == 'approved' ){
			$paymentStatusID = 6;
			$updateHPM = 1;
		}
		else if ( $transaction->status == 'ready' ){
			$paymentStatusID = 10;
			$updateHPM = 1;
		}
		$transactionAmount = $transaction->authorization_amount;
		if ($transaction->action == 'credit' || $transaction->action == 'CREDIT'){
			$transactionAmount = -$transaction->authorization_amount;
		}

		if ( $hoaID ){
		$paymentID = $hoaID.$hoaIDSArray[$hoaID];
		$insertQuery = "INSERT INTO current_payments (\"payment_id\",\"home_id\",\"payment_type_id\",\"amount\",\"process_date\",\"document_num\",\"community_id\",\"hoa_id\",\"referred_to_attorney\",\"payment_status_id\",\"transaction_balance\",\"last_updated_on\",\"email_notification_sent\",\"updated_by\",\"bank_transaction_id\") VALUES(".$paymentID.",".$hoaIDSArray[$hoaID].",1,".$transactionAmount.",'".$transaction->received_date."',".$transaction->response->authorization_code.",2,".$hoaID.",'FALSE',".$paymentStatusID.",0,'".date("Y-m-d")."','TRUE',401,'".$transaction->transaction_id."')";
		
		pg_query($insertQuery);
		$insertCount  = $insertCount + 1;
		if ( $updateHPM ){
			$qr = "UPDATE HOME_PAY_METHOD SET PAYMENT_TYPE_ID=1 WHERE HOA_ID=".$hoaID;
			pg_query($qr);
		}
		// print_r("Inserting new record ".$transaction->transaction_id.nl2br("\n").$insertQuery.nl2br("\n"));
	}
	else{
		$failedTransactionIDS[$transaction->transaction_id] = 0;
	}
	}
	}
}
if (!empty($failedTransactionIDS)){
	foreach ($failedTransactionIDS as $key => $value) {
		$updateHPM = 0;
		// print_r("Transaction ID is".$key.nl2br("\n"));
		// print_r("Checking echeck object......".nl2br("\n"));
		$transactionURL = "https://api.forte.net/v3/transactions/";
		$transactionURL = $transactionURL.$key;
		$ch = curl_init($transactionURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$transactionresult = curl_exec($ch);
		curl_close($ch);
		$transactionresult = json_decode($transactionresult);
		$paymentStatusID = 0;

		if ( $transactionresult->status == 'funded' || $transactionresult->status == 'FUNDED'){
			$paymentStatusID = 1;
			$updateHPM = 1;
		}
		else if ( $transactionresult->status == 'settling' || $transactionresult->status == 'SETTLING' ){
			$paymentStatusID = 8;
			$updateHPM = 1;
		}
		else if ( $transactionresult->status == 'approved' || $transactionresult->status == 'APPROVED'){
			$paymentStatusID = 6;
			$updateHPM = 1;
		}
		else if ( $transactionresult->status == 'ready'){
			$paymentStatusID = 10;
			$updateHPM = 1;
		}

		if ($transactionresult->action == 'credit' || $transactionresult->action == 'CREDIT'){
			$transactionAmount = -$transactionresult->authorization_amount;
		}
		else {
			$transactionAmount = $transactionresult->authorization_amount;
		}
		
		if ( is_numeric($transactionresult->echeck->item_description) ){
			// print_r("Found hoa id in echeck object");
			$hoaID = $transactionresult->echeck->item_description;
			$paymentID = $hoaID.$hoaIDSArray[$hoaID];
			$insertQuery = "INSERT INTO current_payments (\"payment_id\",\"home_id\",\"payment_type_id\",\"amount\",\"process_date\",\"document_num\",\"community_id\",\"hoa_id\",\"referred_to_attorney\",\"payment_status_id\",\"transaction_balance\",\"last_updated_on\",\"email_notification_sent\",\"updated_by\",\"bank_transaction_id\") VALUES(".$paymentID.",".$hoaIDSArray[$hoaID].",1,".$transactionAmount.",'".$transactionresult->received_date."',".$transactionresult->response->authorization_code.",1,".$hoaID.",'FALSE',".$paymentStatusID.",0,'".date("Y-m-d")."','TRUE',401,'".$transactionresult->transaction_id."')";
			pg_query($insertQuery);
			$insertCount  = $insertCount + 1;
			if ( $updateHPM ){
			$qr = "UPDATE HOME_PAY_METHOD SET PAYMENT_TYPE_ID=1 WHERE HOA_ID=".$hoaID;
			pg_query($qr);		
		}
			// print_r("Inserting new record ".$transaction->transaction_id.nl2br("\n"));
		}
	}
}
else {
}
}
else{
	// print_r("Greater than 1000");
}
print_r("Records inserted ".$insertCount." . Records Updated ".$updateCount.".");
?>