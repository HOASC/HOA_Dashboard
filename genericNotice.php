<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
date_default_timezone_set("America/New_York");
header("Content-Type: text/event-stream\n\n");
ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}

$message  = "Generating Inspection Notice...Please Wait...";
  echo 'data: '.$message."\n\n";  
  ob_end_flush();
  flush();
 	include 'includes/dbconn.php';
 	include 'includes/globalvar.php';
	if ( true ){
	$query = "SELECT * FROM COMMUNITY_INFO";
	$queryResult = pg_query($query);
	$communityLegalNames = array();
	$communityAddresses = array();
	while ($row = pg_fetch_assoc($queryResult)) {
		$communityLegalNames[$row['community_id']] = $row['legal_name'];
		$communityAddresses[$row['community_id']]  = $row['mailing_address'];
	}
	$query = "SELECT HOME_ID,HOA_ID FROM INSPECTION_NOTICES WHERE ID=".$_GET['id'];
	$queryResult = pg_query($query);
	$row  = pg_fetch_assoc($queryResult);
	$homeID = $row['home_id'];
	$hoaID = $row['hoa_id'];
	$query = "SELECT * FROM HOMEID WHERE HOME_ID=".$homeID;
	$queryResult = pg_query($query);
	$homeAddress = "";
	while ( $row  = pg_fetch_assoc($queryResult)) {
			$communityID = $row['community_id'];
			$homeAddress = $row['address1'];
			$query = "SELECT CITY_NAME FROM CITY WHERE CITY_ID=".$row['city_id'];
			$qr = pg_query($query);
			$homeCityName = pg_fetch_assoc($qr)['city_name'];
			$query = "SELECT STATE_CODE FROM STATE WHERE STATE_ID=".$row['state_id'];
			$qr = pg_query($query);
			$homeStateName = pg_fetch_assoc($qr)['state_code'];
			$query = "SELECT ZIP_CODE FROM ZIP WHERE ZIP_ID=".$row['zip_id'];
			$qr = pg_query($query);
			$homeZipCode = pg_fetch_assoc($qr)['zip_code'];
			$query =  "SELECT * FROM PERSON WHERE HOME_ID=".$homeID." ORDER BY RELATIONSHIP_ID";
			$qr = pg_query($query);
			$names = '';
			$name = "";
			while ($row = pg_fetch_assoc($qr)) {
				if ( $name == "" ){
					$name = $row['fname'].' '.$row['lname'];
				}
				$names = $names.$row['fname'].' '.$row['lname'].'<br>';
			}
	}
	$communityName = $communityLegalNames[$communityID];
	require('mc_table.php');
	class PDF extends PDF_MC_Table{
	function Header()
	{
	global $communityName;
  	$this->SetFont('Times','','15');
    $this->WriteHtml("<i><b><p align=\"center\">".$communityName."</p></b></i><br>");
    $this->SetFont('Times','','10');
    $this->WriteHtml("<b><p align=\"center\">Homeowner's Association</p></b><br>");
	}
	function Footer()
	{
	global $communityName;
	global $communityAddresses;
	global $communityID;
	$query = "SELECT MAILING_ADDR_CITY,MAILING_ADDR_STATE,MAILING_ADDR_ZIP FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$communityID;
	$qr = pg_query($query);
	while ($row = pg_fetch_assoc($qr)) {
		$query = "SELECT CITY_NAME FROM CITY WHERE CITY_ID=".$row['mailing_addr_city'];
		$qr = pg_query($query);
		while ($r = pg_fetch_assoc($qr)) {
			$communityCity  = $r['city_name'];
		}
		$query = "SELECT STATE_NAME FROM STATE WHERE STATE_ID=".$row['mailing_addr_state'];
		$qr = pg_query($query);
		while ($r = pg_fetch_assoc($qr)) {
			$communityState = $r['state_name'];
		}
		$query = "SELECT ZIP_CODE FROM ZIP WHERE ZIP_ID=".$row['mailing_addr_zip'];
		$qr = pg_query($query);
		while ($r = pg_fetch_assoc($qr)) {
			$communityZipCode = $r['zip_code'];
		}
	}
    $this->SetY(-15);
    $this->SetFont('Arial','',8);
    $this->MultiCell('',15,"".$communityName.",".$communityAddresses[$communityID].",".$communityCity.",".$communityState.",".$communityZipCode."",0,'C',false);
	}
	}
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',10);
	$pdf->WriteHtml("".date('M d,Y')."<br><br>".$names.$homeAddress."<br>".$homeCityName.",".$homeStateName.",".$homeZipCode."<br><br>"."<b>RE: Lot 82 Issuance of Courtesy Notice</b><br>Dear ".$name." :<br><br>As managing agent, of the ".$communityName.", one of our various administrative responsibilities include the task of initiating informing homeowners of issues of concern that are related to owner's maintenance requirements as defined in the governing documents. <br><br>This notice is being sent to advise you that as a result ofa recent routine inspection of the Association, many homes are in need of repair or painting and yours is one of those identified. We understand that you may not be aware of this condition and are extending an opportunity for you to respond or make the necessary repairs. <br><br>In accordance with your CC&Rs, all homeowners are responsible for maintaining the Separate Interest in good condition and repair.<br><br>To preserve the aesthetic appeal of the community, we request that you take necessary action to improve the exterior of your home upon receipt of this notice. The color of paint used should be as close to the original color as possible. You will be required to provide this office with the color scheme that you intend to use for approval prior to painting your house. <br><br> It is our obligation to inform you that if you choose not to comply with this request or discuss this matter with this office within thirty (30) days of receipt of this letter, then you will be scheduled to attend a Hearing before the Board of Directors to resolve the outstanding condition or you may be subjected to disciplinary action, i.e.; fines and/or loss of membership privileges.<br><br>Your special attention to this matter is appreciated in advance. If you have any questions regarding these issues, please leave a message at (925) 399-6642. <br><br>Sincerely,<br>Board of Directors<br>".$communityName);
	$pdf->Output($homeAddress.'.pdf','F');
	}
	$message  = "Uploading to Dropbox...Please Wait...";
  echo 'data: '.$message."\n\n";  
  ob_end_flush();
  flush();

    $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);


  //Dropbox Upload
  $url = 'https://content.dropboxapi.com/2/files/upload';
  $ch = curl_init($url);
  $fileContents = file_get_contents($homeAddress.'.pdf');
  unlink($homeAddress.'.pdf');
  if ( $homeID < 144 ){
  $pathVar = '/Inspection_Notices/'.date('Y').'/'.$homeAddress.'_'.$_GET['id'].'.pdf';
}
else if ( $homeID < 287 ){
	$pathVar = '/Inspection_Notices/'.$community_name.'/'.date('Y').'/'.$homeAddress.'_'.$_GET['id'].'.pdf';
	}
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "'.$pathVar.'","mode": "overwrite","autorename": false,"mute": false}'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContents); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
curl_close($ch);
$dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','".$pathVar."','".date('Y-m-d H:i:s')."')";
if ( !pg_query($dropboxInsertQuery) ){
    // print_r("Failed to insert to dropbox_stats");
    // print_r(nl2br("\n\n"));
}
$jsonDecode = json_decode($response);
if ( $jsonDecode->id ){
	$message  = "Uploaded to Dropbox Successfully";
	//Insert to document management
	$id = $jsonDecode->id;
	$id = $id.' '.$hoaID;

}
else {
		$message  = "Failed to upload to Dropbox. Please try agin.";
}

echo "id: $id\n";
  echo 'data: '.$message."\n\n";  
  ob_end_flush();
  flush();
  exit(0);
?>