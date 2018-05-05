<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');
include 'includes/dbconn.php';
$query = "SELECT * FROM COMMUNITY_STATEMENTS_MAILED WHERE ORDER_ID IS NOT NULL AND (order_status != 'Billed' OR order_status IS NULL)";
$queryResult = pg_query($query);
while ($row = pg_fetch_assoc($queryResult)) {
	$req = curl_init();
curl_setopt($req, CURLOPT_URL,"http://southdata.us-west-2.elasticbeanstalk.com/GetOrderStatus.aspx?id=".$row['order_id']);
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message = curl_exec($req);

$message = explode('@', $message);
if ( ($message[0] != "Failed." )&&($message[0] != "") ){
$updateQuery = "UPDATE COMMUNITY_STATEMENTS_MAILED SET order_status='".$message[0]."', updated_on='".date('Y-m-d H:i:s')."',updated_by=401 WHERE statement_id=".$row['statement_id'];
pg_query($updateQuery);
}
else {
	echo $row['statement_id'];
	print_r(nl2br("\n\n"));
}

}
?>