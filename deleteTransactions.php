<?php
include 'includes/dbconn.php';
$delCount = 0;
$transactionList = array();
array_push($transactionList, 'trn_1936243f-caa9-43c8-9c03-544aaefaf8ed');
array_push($transactionList, 'trn_393a5258-3e4a-4cf3-b4b9-78effd507860');
array_push($transactionList, 'trn_57c26af6-0239-41eb-af9a-b5a69711c58f');
array_push($transactionList, 'trn_582c64fd-d294-47f0-92e8-3040ed660d3e');
array_push($transactionList, 'trn_d577dfc6-4e88-4ab1-b485-76723e157d6f');
foreach ($transactionList as $key) {
	$query = "DELETE FROM CURRENT_PAYMENTS WHERE BANK_TRANSACTION_ID = '".$key."'";
	if ( pg_query($query) ){
		$delCount = $delCount + 1;
	}
}
print_r("RECORDS DELETED : ".$delCount);
?>