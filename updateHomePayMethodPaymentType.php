<?php
date_default_timezone_set('America/Los_Angeles');
include 'includes/dbconn.php';
if ( true ){
	$query = "SELECT A.HOA_ID,A.PAYMENT_TYPE_ID FROM  (SELECT PROCESS_DATE,HOA_ID,PAYMENT_TYPE_ID FROM CURRENT_PAYMENTS CP1 WHERE PROCESS_DATE = ( SELECT MAX(PROCESS_DATE) FROM CURRENT_PAYMENTS WHERE CP1.HOA_ID = CURRENT_PAYMENTS.HOA_ID ) ORDER BY PROCESS_DATE DESC) AS A ORDER BY HOA_ID";
	$qr = pg_query($query);
	while ($row = pg_fetch_assoc($qr)) {
		$qu = "UPDATE HOME_PAY_METHOD SET PAYMENT_TYPE_ID=".$row['payment_type_id'].",updated_on='".date('Y-m-d H:i:s')."',updated_by=401 WHERE HOA_ID=".$row['hoa_id'];
		pg_query($qu);
	}
}
?>