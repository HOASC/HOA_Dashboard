<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: text/event-stream\n\n");
date_default_timezone_set('America/Los_Angeles');

include 'includes/dbconn.php';

$message  = "Please Wait...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

if ( isset($_GET['id'])){
if ( $_GET['id'] == 1){



// UPDATION
$message  = "Updating  Transactions...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateCurrentPayments.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,1,1,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$message  = "Updating  paymethods...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateHomePayMethod.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,1,8,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$message  = "Updating  deposits...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/qCommunityDeposits.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);


$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,1,2,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$message  = "Updating  deposit transactions...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/communityDepositsTransactions.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);


$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,1,3,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$id = date('Y-m-d H:i:s');
$message  = "Done!!!";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateHomePayMethodPaymentType.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);


}
else if ( $_GET['id'] == 2){


//
$message  = "Updating  Agreements...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateAgreements.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,2,4,'".date('Y-m-d H:i:s')."')";
pg_query($query);


$message  = "Updating  Mega Sign Agreements...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateMegaSignAgreements.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,2,5,'".date('Y-m-d H:i:s')."')";
pg_query($query);


$message  = "Updating  Mega Sign Child Agreements...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateMegaSignChildAgreements.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,2,6,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$id = date('Y-m-d H:i:s');
$message  = "Done!!!";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

}

else if ( $_GET['id'] == 3){
$message  = "Please wait.....";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/allBillingStatementGeneration.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);
$id = date('Y-m-d H:i:s');
$message  = "Genereated and uploaded to Dropbox";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$id = date('Y-m-d H:i:s');
$message  = "Done!!!";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,3,7,'".date('Y-m-d H:i:s')."')";
pg_query($query);
}
else if ( $_GET['id'] == 4 ){
$message  = "Please wait.....";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$message  = "Updating email data...Please wait.....";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/mandrillUpdateStats.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);
$id = date('Y-m-d H:i:s');
$message  = "Done!!!";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"START_TIME\") VALUES(2,4,'".date('Y-m-d H:i:s')."')";
pg_query($query);
}

else if ( $_GET['id'] == 5 ){
$message  = "Updating sms data...Please wait.....";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateSMSStatus.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);
$id = date('Y-m-d H:i:s');
$message  = "Done!!!";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"START_TIME\") VALUES(2,6,'".$id."')";
pg_query($query);
}

}
else {
	
// UPDATION
$message  = "Updating  Transactions...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateCurrentPayments.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,1,1,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$message  = "Updating  paymethods...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateHomePayMethod.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,1,8,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$message  = "Updating  deposits...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/communityDeposits.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);


$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,1,2,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$message  = "Updating  deposit transactions...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/communityDepositsTransactions.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);


$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,1,3,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$id = date('Y-m-d H:i:s');
$message  = "Done!!!";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();


//
$message  = "Updating  Agreements...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateAgreements.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,2,4,'".date('Y-m-d H:i:s')."')";
pg_query($query);


$message  = "Updating  Mega Sign Agreements...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateMegaSignAgreements.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,2,5,'".date('Y-m-d H:i:s')."')";
pg_query($query);


$message  = "Updating  Mega Sign Child Agreements...";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateMegaSignChildAgreements.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = curl_exec($req);
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,2,6,'".date('Y-m-d H:i:s')."')";
pg_query($query);
$id = date('Y-m-d H:i:s');
$message  = "Done!!!";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$message  = "Please wait.....";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/allBillingStatementGeneration.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);
$id = date('Y-m-d H:i:s');
$message  = "Genereated and uploaded to Dropbox";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$message  = "Done!!!";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,3,7,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$message  = "Please wait.....";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$message  = "Updating email data...Please wait.....";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/mandrillUpdateStats.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);
$message  = "Done!!!";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();

$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"START_TIME\") VALUES(2,4,'".date('Y-m-d H:i:s')."')";
pg_query($query);

$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateHomePayMethodPaymentType.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);

$message  = "Updating sms data...Please wait.....";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateSMSStatus.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);
$id = date('Y-m-d H:i:s');
$message  = "Done!!!";
echo "id: $id\n";
echo 'data: '.$message."\n\n";  
ob_end_flush();
flush();
$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"START_TIME\") VALUES(2,6,'".$id."')";
pg_query($query);

}

?>