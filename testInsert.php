<?php
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/updateCurrentPayments.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
$message  = $message.curl_exec($req);
echo $message;
// echo 'data: '.$message."\n\n";  
// ob_end_flush();
// flush();
?>