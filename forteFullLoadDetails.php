<?php
include 'includes/dbconn.php';
if ( true ){
    $transactionIDSQuery = "SELECT document_num FROM CURRENT_PAYMENTS WHERE COMMUNITY_ID = 2 AND document_num IS NOT NULL";
    $transactionIDSQueryResult = pg_query($transactionIDSQuery);
    $transactionsArray = array();
    while ($row = pg_fetch_assoc($transactionIDSQueryResult)) {
            $transactionsArray[$row['document_num']] = 1;
    }
    if ( $_GET['id'] == 1 ){
    $url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions?filter=customer_id+eq+'.$_GET['data1'].'+and+start_received_date+eq+\'2016-01-01\'+and+end_received_date+eq+\''.date('Y-m-d').'\'&page_size=100000';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     $result = curl_exec($ch);
     $result = json_decode($result);
     $sendData = array();
     foreach ($result->results as $transaction) {
         $data  = array();
         $data['transction_id']  = $transaction->transaction_id;
         $data['customer_token'] = $transaction->customer_token;
         $data['customer_id'] = $transaction->customer_id;
         $data['status'] = $transaction->status;
         $data['action'] = $transaction->action;
         $data['authorization_amount'] = $transaction->authorization_amount;
         if ( $transaction->action == 'credit' ){
            $data['authorization_amount'] = -$transaction->authorization_amount;
         }
         $data['authorization_code'] = $transaction->authorization_code;
         $data['entered_by'] = $transaction->entered_by;
         $data['received_date'] = date('Y-m-d H:i:s',strtotime($transaction->received_date));
         $data['first_name'] = $transaction->billing_address->first_name;
         $data['last_name'] = $transaction->billing_address->last_name;
         $data['masked_account_number'] = $transaction->echeck->masked_account_number;

        if ( $transactionsArray[$transaction->authorization_code] == 1){
            $data['is_inserted'] = 'Found';
         }
         else {
            $data['is_inserted'] = 'Not Found';
         }


         array_push($sendData, $data);
     }
    }
    else if ( $_GET['id'] == 2 ) {

        $url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions?filter=bill_to_first_name+eq+'.$_GET['data1'].'+and+bill_to_last_name+eq+'.$_GET['data2'].'+and+start_received_date+eq+\'2016-01-01\'+and+end_received_date+eq+\''.date('Y-m-d').'\'&page_size=100000';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     $result = curl_exec($ch);
     $result = json_decode($result);
     $sendData = array();
     foreach ($result->results as $transaction) {
         $data  = array();
         $data['transction_id']  = $transaction->transaction_id;
         $data['customer_token'] = $transaction->customer_token;
         $data['customer_id'] = $transaction->customer_id;
         $data['status'] = $transaction->status;
         $data['action'] = $transaction->action;
         $data['authorization_amount'] = $transaction->authorization_amount;
         if ( $transaction->action == 'credit' ){
            $data['authorization_amount'] = -$transaction->authorization_amount;
         }
         $data['authorization_code'] = $transaction->authorization_code;
         $data['entered_by'] = $transaction->entered_by;
         $data['received_date'] = date('Y-m-d H:i:s',strtotime($transaction->received_date));
         $data['first_name'] = $transaction->billing_address->first_name;
         $data['last_name'] = $transaction->billing_address->last_name;
         $data['masked_account_number'] = $transaction->echeck->masked_account_number;

         if ( $transactionsArray[$transaction->authorization_code] == 1){
            $data['is_inserted'] = 'Found';
         }
         else {
            $data['is_inserted'] = 'Not Found';
         }


         array_push($sendData, $data);
     }

    }
    else if ( $_GET['id'] == 3 ){

        $url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions?filter=bill_to_first_name+eq+'.$_GET['data1'].'+and+start_received_date+eq+\'2016-01-01\'+and+end_received_date+eq+\''.date('Y-m-d').'\'&page_size=100000';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     $result = curl_exec($ch);
     $result = json_decode($result);
     $sendData = array();
     foreach ($result->results as $transaction) {
         $data  = array();
         $data['transction_id']  = $transaction->transaction_id;
         $data['customer_token'] = $transaction->customer_token;
         $data['customer_id'] = $transaction->customer_id;
         $data['status'] = $transaction->status;
         $data['action'] = $transaction->action;
         $data['authorization_amount'] = $transaction->authorization_amount;
         if ( $transaction->action == 'credit' ){
            $data['authorization_amount'] = -$transaction->authorization_amount;
         }
         $data['authorization_code'] = $transaction->authorization_code;
         $data['entered_by'] = $transaction->entered_by;
         $data['received_date'] = date('Y-m-d H:i:s',strtotime($transaction->received_date));
         $data['first_name'] = $transaction->billing_address->first_name;
         $data['last_name'] = $transaction->billing_address->last_name;
         $data['masked_account_number'] = $transaction->echeck->masked_account_number;

         if ( $transactionsArray[$transaction->authorization_code] == 1){
            $data['is_inserted'] = 'Found';
         }
         else {
            $data['is_inserted'] = 'Not Found';
         }

         array_push($sendData, $data);
     }

    }
    else if ( $_GET['id'] == 4 ){

        $url = 'https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions?filter=bill_to_last_name+eq+'.$_GET['data1'].'+and+start_received_date+eq+\'2016-01-01\'+and+end_received_date+eq+\''.date('Y-m-d').'\'&page_size=100000';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json','x-forte-auth-organization-id: org_332536','authorization: Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     $result = curl_exec($ch);
     $result = json_decode($result);
     $sendData = array();
     foreach ($result->results as $transaction) {
         $data  = array();
         $data['transction_id']  = $transaction->transaction_id;
         $data['customer_token'] = $transaction->customer_token;
         $data['customer_id'] = $transaction->customer_id;
         $data['status'] = $transaction->status;
         $data['action'] = $transaction->action;
         $data['authorization_amount'] = $transaction->authorization_amount;
         if ( $transaction->action == 'credit' ){
            $data['authorization_amount'] = -$transaction->authorization_amount;
         }
         $data['authorization_code'] = $transaction->authorization_code;
         $data['entered_by'] = $transaction->entered_by;
         $data['received_date'] = date('Y-m-d H:i:s',strtotime($transaction->received_date));
         $data['first_name'] = $transaction->billing_address->first_name;
         $data['last_name'] = $transaction->billing_address->last_name;
         $data['masked_account_number'] = $transaction->echeck->masked_account_number;

        if ( $transactionsArray[$transaction->authorization_code] == 1){
            $data['is_inserted'] = 'Found';
         }
         else {
            $data['is_inserted'] = 'Not Found';
         }
         array_push($sendData, $data);
     }


    }
}
echo json_encode($sendData);
?>