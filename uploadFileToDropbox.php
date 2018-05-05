<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}

date_default_timezone_set('America/Los_Angeles');

include 'includes/dbconn.php';

$data = file_get_contents('php://input');
$parseJSON = json_decode($data);

if ( $parseJSON[0]->file_type == "legal" ){
$fileName = $parseJSON[0]->file_name;
$fileNameArray = explode('.', $fileName);
$extension = end($fileNameArray);

$fileName = array_slice($fileNameArray, 0, -1);

$fileName = implode(" ", $fileName);

$fileName = $fileName.date('Y-m-d H:i:s');

$fileName = $fileName.'.'.$extension;

$name = $parseJSON[0]->name;
$uploaderId = $parseJSON[0]->uploader_id;
$shortDesc = $parseJSON[0]->short_desc;
$validFrom = $parseJSON[0]->valid_from;
$validUntil = $parseJSON[0]->valid_until;
$fileContent = base64_decode($parseJSON[0]->file_content);
$subCategory = $parseJSON[0]->file_sub_category;
//Get community Info

$query = "SELECT * FROM USR WHERE ID=".$uploaderId;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$communityID = $row['community_id'];

$query = "SELECT * FROM COMMUNITY_INFO WHERE community_id=".$communityID;
$queryResult = pg_query($query);

$row = pg_fetch_assoc($queryResult);

$communityCode = $row['community_code'];



//Upload file to dropbox

$path = "/Legal Documents/".$communityCode."/".$fileName;

 $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

 $url = 'https://content.dropboxapi.com/2/files/upload';
 
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Legal Documents/'.$communityCode.'/'.$fileName.'","mode": "add","autorename": true,"mute": false}'));
 curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 $response = curl_exec($ch);
 $dbResponse = $response;
 $response = json_decode($response);

$dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','".$path."','".date('Y-m-d H:i:s')."')";
pg_query($dropboxInsertQuery);

 if ( isset($response->error_summary) ){
     echo "An error occured.";
     exit(0);
 }

 else if (strpos($dbResponse, 'Error') !== false) {
     echo "An error occured.";
     exit(0);
}

 //Insert to community legal docs

 else {

 	$query =  "INSERT INTO community_legal_docs(community_id,name,short_desc,document_id,last_updated_on,updated_by,upload_date,uploaded_by,valid_until,valid_from,legal_docs_type_id) VALUES(".$communityID.",'".$name."','".$shortDesc."','".$response->id."','".date('Y-m-d')."',".$uploaderId.",'".date('Y-m-d')."',".$uploaderId.",'".date('Y-m-d',strtotime($validUntil))."','".date('Y-m-d',strtotime($validFrom))."',".$subCategory.")";
 	if ( !(pg_query($query)) ){
 		echo "An error occured.";
 	}	
 }





}

else if ( $parseJSON[0]->file_type == "disclosure"  ){

$uploaderId = $parseJSON[0]->uploader_id;


$fileName = $parseJSON[0]->file_name;


$fileNameArray = explode('.', $fileName);
$extension = end($fileNameArray);

$fileName = array_slice($fileNameArray, 0, -1);

$fileName = implode(" ", $fileName);

$fileName = $fileName.date('Y-m-d H:i:s');

$fileName = $fileName.'.'.$extension;


$fileContent = base64_decode($parseJSON[0]->file_content);



//Get community info 
$query = "SELECT * FROM USR WHERE ID=".$uploaderId;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$communityID = $row['community_id'];

$query = "SELECT * FROM COMMUNITY_INFO WHERE community_id=".$communityID;
$queryResult = pg_query($query);

$row = pg_fetch_assoc($queryResult);

$communityCode = $row['community_code'];

// Upload to Dropbox
	
if ( $fileContent ){

   $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

 $url = 'https://content.dropboxapi.com/2/files/upload';
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Disclosures/'.$communityCode.'/'.$fileName.'","mode": "add","autorename": true,"mute": false}'));
 curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 $response = curl_exec($ch); 
 $dbResponse = $response;
 $response = json_decode($response);
 $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','/Disclosures/".$communityCode."/".$fileName."','".date('Y-m-d H:i:s')."')";
pg_query($dropboxInsertQuery);
 if ( isset($response->error_summary) ){
     echo "An error occured.";
     exit(0);
 }

 else if (strpos($dbResponse, 'Error') !== false) {
     echo "An error occured.";
     exit(0);
}


//Insert to community disclosures

 else {

 	$query  = "INSERT INTO community_disclosures(community_id,type_id,legal_date_from,actual_date,delivery_type,fiscal_year_start,fiscal_year_end,legal_date_until,applicable,notes,document_id,changed_this_year,updated_on,updated_by) VALUES(".$communityID.",".$parseJSON[0]->sub_category.",'".$parseJSON[0]->legal_date_from."','".$parseJSON[0]->legal_date_to."',".$parseJSON[0]->delivery_type.",'".$parseJSON[0]->fiscal_year_start."','".$parseJSON[0]->fiscal_year_end."','".$parseJSON[0]->legal_date_until."','TRUE','".$parseJSON[0]->notes."','".$response->id."',".$parseJSON[0]->changed_this_year.",'".date('Y-m-d H:i:s')."',".$uploaderId.")";
     echo $query;
 	if ( !(pg_query($query)) ){
 		echo "An error occured.";
 		exit(0);
 	}
 }
}
else {
     $query  = "INSERT INTO community_disclosures(community_id,type_id,legal_date_from,actual_date,delivery_type,fiscal_year_start,fiscal_year_end,legal_date_until,applicable,notes,changed_this_year,updated_on,updated_by) VALUES(".$communityID.",".$parseJSON[0]->sub_category.",'".$parseJSON[0]->legal_date_from."','".$parseJSON[0]->legal_date_to."',".$parseJSON[0]->delivery_type.",'".$parseJSON[0]->fiscal_year_start."','".$parseJSON[0]->fiscal_year_end."','".$parseJSON[0]->legal_date_until."','TRUE','".$parseJSON[0]->notes."',".$parseJSON[0]->changed_this_year.",'".date('Y-m-d H:i:s')."',".$uploaderId.")";
     if ( !(pg_query($query)) ){
          echo "An error occured.";
          exit(0);
     }
     else{
          echo "Record Created";
     }
}
}

else if ( $parseJSON[0]->file_type == "minutes" ){
    $boardMeetingType = $parseJSON[0]->board_meeting_type;
    $boardMeetingDate23 = $parseJSON[0]->meeting_minutes_date;
    $boardMeetingFileName = $parseJSON[0]->meeting_file_name;
    $boardMeetingFileData = $parseJSON[0]->meeting_file_data;
    $boardMeetingDate = explode('-', $boardMeetingDate23);
    $boardMeeting = $parseJSON[0]->board_meeting;
    $communityID = $parseJSON[0]->community_id;
    $userID = $parseJSON[0]->user_id;

    $query = "SELECT * FROM COMMUNITY_INFO WHERE community_id=".$communityID;
    $queryResult = pg_query($query);
    $row = pg_fetch_assoc($queryResult);
    $communityCode = $row['community_code'];

     $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

    $url = 'https://content.dropboxapi.com/2/files/upload';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Meeting Minutes/'.$communityCode.'/'.$boardMeetingFileName.'","mode": "add","autorename": true,"mute": false}'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, base64_decode($boardMeetingFileData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch); 
    $dbResponse = $response;
    $response = json_decode($response);
    $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','/Meeting Minutes/".$communityCode."/".$boardMeetingFileName."','".date('Y-m-d H:i:s')."')";
    pg_query($dropboxInsertQuery);
    if ( isset($response->error_summary) ){
        echo "An error occured.";
         exit(0);
    }

    else if (strpos($dbResponse, 'Error') !== false) {
        echo "An error occured.";
         exit(0);
    }

    $documentID = $response->id;

    if ( ($boardMeetingType == 'undefined') && ($boardMeeting == 'undefined') ){
        $query = "INSERT INTO community_minutes(community_id,updated_by,updated_on,document_id,created_on,created_by,valid_until,valid_from) VALUES(".$communityID.",".$userID.",'".date('Y-m-d H:i:s')."','".$documentID."','".date('Y-m-d H:i:s')."',".$userID.",'".$boardMeetingDate[1]."','".$boardMeetingDate[0]."')";
        if ( !pg_query($query) ){
            echo "An error occured.";
        }
        else {
            echo "Success.";
        }  
    }
    else  if ( ($boardMeetingType == 'undefined')  ) { 

        $query = "INSERT INTO community_minutes(board_meeting_id,community_id,updated_by,updated_on,document_id,created_on,created_by,valid_until,valid_from) VALUES(".$boardMeeting.",".$communityID.",".$userID.",'".date('Y-m-d H:i:s')."','".$documentID."','".date('Y-m-d H:i:s')."',".$userID.",'".$boardMeetingDate[1]."','".$boardMeetingDate[0]."')";
        if ( !pg_query($query) ){
            echo "An error occured.";
        }
        else {
            echo "Success.";
        }  

    }
    else if ( ($boardMeeting == 'undefined') ) {
        $query = "INSERT INTO community_minutes(community_id,updated_by,updated_on,document_id,created_on,created_by,valid_until,valid_from,board_meeting_type_id) VALUES(".$communityID.",".$userID.",'".date('Y-m-d H:i:s')."','".$documentID."','".date('Y-m-d H:i:s')."',".$userID.",'".$boardMeetingDate[1]."','".$boardMeetingDate[0]."',".$boardMeetingType.")";
        if ( !pg_query($query) ){
            echo "An error occured.";
        }
        else {
            echo "Success.";
        }  
    }

    else {

        $query = "INSERT INTO community_minutes(board_meeting_id,community_id,updated_by,updated_on,document_id,created_on,created_by,valid_until,valid_from,board_meeting_type_id) VALUES(".$boardMeeting.",".$communityID.",".$userID.",'".date('Y-m-d H:i:s')."','".$documentID."','".date('Y-m-d H:i:s')."',".$userID.",'".date('Y-m-d H:i:s',strtotime($boardMeetingDate[1]))."','".date('Y-m-d H:i:s',strtotime($boardMeetingDate[0]))."',".$boardMeetingType.")";
        if ( !pg_query($query) ){
            echo "An error occured.";
        }
        else {
            echo "Success.";
        }  

    }
}

else if ( $parseJSON[0]->file_type == "contracts"){
  // print_r($parseJSON);

    $communityID = $parseJSON[0]->community_id;
    $userID = $parseJSON[0]->user_id;

    $fileName = $parseJSON[0]->file_name;
    $fileData = $parseJSON[0]->file_data;

    $query = "SELECT * FROM COMMUNITY_INFO WHERE community_id=".$communityID;
    $queryResult = pg_query($query);
    $row = pg_fetch_assoc($queryResult);
    $communityCode = $row['community_code'];

     $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);


    $url = 'https://content.dropboxapi.com/2/files/upload';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Contracts/'.$communityCode.'/'.$fileName.'","mode": "add","autorename": true,"mute": false}'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, base64_decode($fileData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch); 
    $dbResponse = $response;
    $response = json_decode($response);
    $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','/Contracts/".$communityCode."/".$fileName."','".date('Y-m-d H:i:s')."')";
    pg_query($dropboxInsertQuery);
    if ( isset($response->error_summary) ){
        echo "An error occured.";
         exit(0);
    }

    else if (strpos($dbResponse, 'Error') !== false) {
        echo "An error occured.";
         exit(0);
    }
    $documentID = $response->id;
    $date  = $parseJSON[0]->date; 
    $boardApprovalID = $parseJSON[0]->board_approval_id;
    $vendorID = $parseJSON[0]->vendor_id;
    $vendorType = $parseJSON[0]->vendor_type;
    $activeContract = $parseJSON[0]->active_contract;
    $futureContract = $parseJSON[0]->future_contract;
    $yearlyContract = $parseJSON[0]->yearly_contract;
    $shortDescription = $parseJSON[0]->short_desc;
    $description = $parseJSON[0]->desc;
    $dates = explode('-', $date);

    if ( $activeContract == 'YES' ){
      $activeContract = 'TRUE';
      $expiredContract = 'FALSE';
    }
    else {
      $activeContract = 'FALSE';
      $expiredContract = 'TRUE';
    }

    if ( $futureContract == 'YES' ){
      $futureContract = 'TRUE';
    }
    else {
      $futureContract = 'FALSE';
    }


    $query = "INSERT INTO community_contracts(active_from,active_until,board_approval_id,vendor_id,vendor_type_id,active_contract,expired_contract,future_contract,community_id,document_id,yearly_amount,description,created_on,created_by,updated_on,updated_by,short_desc,upload_date,uploaded_by) VALUES('".date('Y-m-d H:i:s',strtotime($dates[0]))."','".date('Y-m-d H:i:s',strtotime($dates[1]))."','".$boardApprovalID."','".$vendorID."',".$vendorType.",'".$activeContract."','".$expiredContract."','".$futureContract."',".$communityID.",'".$documentID."',".$yearlyContract.",'".$description."','".date('Y-m-d H:i:s')."',".$userID.",'".date('Y-m-d H:i:s')."',".$userID.",'".$shortDescription."','".date('Y-m-d H:i:s')."',".$userID.")"; 
    if ( pg_query($query) ){
      echo "Success.";
    }
    else {
      echo "An error occured.";
    }
    echo $query;
}
else if ( $parseJSON[0]->file_type == 'invoices' ){ 
  // print_r($parseJSON);
  $userID  = $parseJSON[0]->user_id;
  $communityID = $parseJSON[0]->community_id;
  $fileName = $parseJSON[0]->file_name;
  $fileContent = base64_decode($parseJSON[0]->file_data);
  $invoiceID = $parseJSON[0]->invoice_id;
  $invoiceDate = date('Y-m-d H:i:s',strtotime($parseJSON[0]->invoice_date));
  $invoiceAmount=  $parseJSON[0]->invoice_amount;
  $vendorID  = $parseJSON[0]->vendor_id;
  $workStatus = $parseJSON[0]->work_status;
  $paymentStatus = $parseJSON[0]->payment_status;
  $accountNumber = $parseJSON[0]->account_number;
  $dueDate = date('Y-m-d H:i:s',strtotime($parseJSON[0]->due_date));
  $reserveExpense = $parseJSON[0]->reserve_expense;
  $validUntil = date('Y-m-d H:i:s',strtotime($parseJSON[0]->valid_until));
  $query = "SELECT * FROM COMMUNITY_INFO WHERE community_id=".$communityID;
  $queryResult = pg_query($query);
  $row = pg_fetch_assoc($queryResult);
  $communityCode = $row['community_code'];

   $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

    $url = 'https://content.dropboxapi.com/2/files/upload';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Invoice/'.$communityCode.'/'.$fileName.'","mode": "add","autorename": true,"mute": false}'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch); 
    $dbResponse = $response;
    $response = json_decode($response);
    $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','/Contracts/".$communityCode."/".$fileName."','".date('Y-m-d H:i:s')."')";
    pg_query($dropboxInsertQuery);
    if ( isset($response->error_summary) ){
        echo "An error occured.";
         exit(0);
    }

    else if (strpos($dbResponse, 'Error') !== false) {
        echo "An error occured.";
         exit(0);
    }
  $documentID = $response->id;
  $insertQuery = "INSERT INTO community_invoices(community_id,invoice_id,invoice_date,invoice_amount,vendor_id,work_status,payment_status,account_number,due_date,document_id,reserve_expense,uploaded_by,updated_on,created_by,created_on,valid_until) VALUES(".$communityID.",'".$invoiceID."','".$invoiceDate."',".$invoiceAmount.",".$vendorID.",'".$workStatus."','".$paymentStatus."','".$accountNumber."','".$dueDate."','".$documentID."','".$reserveExpense."',".$userID.",'".date('Y-m-d H:i:s')."',".$userID.",'".date('Y-m-d H:i:s')."','".$validUntil."') ";
  if ( !pg_query($insertQuery) ) {
    echo "An error occured.";
  }
  else {
    echo "Success.";
  }
  // echo $insertQuery;
}

else {
	echo "An error occured.";
}

?>