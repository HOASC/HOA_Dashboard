<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require('mc_table.php');

ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}

    include 'includes/dbconn.php';
        $cityQuery = "SELECT * FROM CITY";
        $cityQueryResult = pg_query($cityQuery);
        $cityArray = array();
        while($row = pg_fetch_assoc($cityQueryResult)){
                $cityArray[$row['city_id']] = $row['city_name'];
        }
        $zipArray = array();
        $zipQuery = "SELECT * FROM ZIP";
        $zipQueryResult = pg_query($zipQuery);
        while($row = pg_fetch_assoc($zipQueryResult)){
            $zipArray[$row['zip_id']] = $row['zip_code'];
        } 
        $stateQuery = "SELECT * FROM STATE";
        $stateQueryResult = pg_query($stateQuery);
        $stateArray = array();
        while($row = pg_fetch_assoc($stateQueryResult)){
            $stateArray[$row['state_id']] = $row['state_name'];
        }
        $locationArray = array();
        $locationQuery = "SELECT * FROM LOCATIONS_IN_COMMUNITY";
        $locationQueryResult = pg_query($locationQuery);
        while($row = pg_fetch_assoc($locationQueryResult)){
            $locationArray[$row['location_id']] = $row['location'];
        }
        $allInspectionQuery = "SELECT * FROM INSPECTION_NOTICES WHERE ID=".$_GET['id'];
        $allInspectionQueryResult = pg_query($allInspectionQuery);
        while($row = pg_fetch_assoc($allInspectionQueryResult))
        {
        $id = $row['id']; 
        $documentID = $row['document_id'];
        if ( $documentID ){
            $docQuery = "SELECT * FROM DOCUMENT_MANAGEMENT WHERE DOCUMENT_ID=$documentID";
            $docQueryResult  = pg_query($docQuery);
            $docRess = pg_fetch_assoc($docQueryResult);
            $techID = $docRess['tech_id'];
            $attachDescription = $docRess['description'];
        }
        $inspectionDateFinal = $row['inspection_date'];
        $inspectionStatusIDFinal = $row['inspection_status_id'];
        $inspectionDescriptionFinal = $row['description'];
        $inspectionNoticeTypeFinal  = $row['inspection_notice_type_id'];
        $inspectionCommunityIDFinal = $row['community_id'];
        $inspectionCategoryID = $row['inspection_category_id'];
        $inspectionLocationID = $row['location_id'];
        $inspectionSubCategoryID = $row['inspection_sub_category_id'];
        $inspectionHOAID = $row['hoa_id'];
        $inspectionTypeID = $row['inspection_notice_type_id'];
        $inspectionHomeID =  $row['home_id'];
        $homeDetailsQuery = "SELECT * FROM HOMEID WHERE HOME_ID=".$inspectionHomeID;
        $homeDetailsQueryResult = pg_query($homeDetailsQuery);
        $hoaDetailsQuery = "SELECT * FROM HOAID WHERE HOA_ID=".$inspectionHOAID;
        $hoaDetailsQueryResult = pg_query($hoaDetailsQuery);
        $row = pg_fetch_assoc($hoaDetailsQueryResult);
        $personFirstName = $row['firstname'];
        $personLastName = $row['lastname'];
        $row = pg_fetch_assoc($homeDetailsQueryResult);
        $homeAddress1Final = $row['address1'];
        $homeAddressCityFinal  =$row['city_id'];
        $homeAddressStateFinal  = $row['state_id'];
        $homeAddressDistrictFinal  = $row['district_id'];
        $homeAddressZipFinal  = $row['zip_id'];
        $homeAddressCommunityIdFinal = $row['community_id'];
        $currentLivingStatus = $row['living_status'];
        $sendToEmail = $row['email'];
        $communityInfoQuery = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$inspectionCommunityIDFinal;
        $communityInfoQueryResult = pg_query($communityInfoQuery);
        $row = pg_fetch_assoc($communityInfoQueryResult);
        $communityLegalName = $row['legal_name'];
        $communityCodeName = $row['community_code'];
        $communityMailingAddress = $row['mailing_address'];
        $communityMailingAddressCity  = $row['mailing_addr_city'];
        $communityMailingAddressState = $row['mailing_addr_state'];
        $communityMailingAddressZip = $row['mailing_addr_zip'];
        $inspectionTypeNameFinal = "";
        $inspectionStatusQuery = "SELECT * FROM INSPECTION_STATUS WHERE ID=".$inspectionStatusIDFinal;
        $inspectionStatusQueryResult = pg_query($inspectionStatusQuery);
        $roww = pg_fetch_assoc($inspectionStatusQueryResult);
        $inspectionStatusTextFinal = $roww['inspection_status'];
        if($inspectionTypeID)
        {
        $inspectionNoticeTypeQuery = "SELECT * FROM INSPECTION_NOTICE_TYPE WHERE ID =".$inspectionTypeID;
        $inspectionNoticeTypeQueryResult = pg_query($inspectionNoticeTypeQuery);
        $row = pg_fetch_assoc($inspectionNoticeTypeQueryResult);
        $inspectionTypeNameFinal = $row['name'];
        }
        if ( $inspectionCategoryID ){
        $inspectionCategoryIDQuery = "SELECT * FROM INSPECTION_CATEGORY WHERE ID=".$inspectionCategoryID." AND IS_ACTIVE='YES'";
        $inspectionCategoryIDQueryResult = pg_query($inspectionCategoryIDQuery);
        $row = pg_fetch_assoc($inspectionCategoryIDQueryResult);
        $inspectionCategoryName =  $row['name'];
        }
        $inspectionSubCategoryNameFinal = "";
        if ($inspectionSubCategoryID ){
        $inspectionSubCategoryIDQuery = "SELECT * FROM INSPECTION_SUB_CATEGORY WHERE ID=".$inspectionSubCategoryID." AND IS_ACTIVE='YES'";
        $inspectionSubCategoryIDQueryResult = pg_query($inspectionSubCategoryIDQuery);
        $row = pg_fetch_assoc($inspectionSubCategoryIDQueryResult);
        $inspectionSubCategoryNameFinal  = $row['name'];
        $inspectionSubCategoryRuleDescription = $row['rule_description'];
        $inspectionSubCategoryExplanation = $row['explanation'];
        $inspectionSubCategorySection  = $row['section'];
        $inspectionLegalDocsID = $row['community_legal_docs_id'];
        $inspectionFooter = $row['footer'];
        }
        date_default_timezone_set('America/Los_Angeles');
        
        $pdf=new PDF_MC_Table();
        $pdf->SetFont('Arial','B',8);
        $pdf->AddPage();
        $pdf->SetTextColor(0,0,128);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->MultiCell(0,6,$communityLegalName,0,'0',false);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0,3,$communityMailingAddress."\n".$cityArray[$communityMailingAddressCity]."\n".$stateArray[$communityMailingAddressState]." ".$zipArray[$communityMailingAddressZip],0,'0',false);
        $pdf->Ln();
        $pdf->SetX(113);
        $pdf->SetWidths(array(40,50));
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',9);
        $pdf->Row(array('Account Number',$inspectionHOAID));
        $pdf->SetX(113);
        $pdf->Row(array('Community ID',$communityCodeName));
        $pdf->SetX(113);
        $pdf->Row(array('Property Address',$homeAddress1Final));
        $pdf->SetX(113);
        $pdf->Row(array('Violation Notice ID',$id));
        $pdf->SetX(113);
        $pdf->Row(array('Notice Type', $inspectionTypeNameFinal));
        $pdf->Ln();
        $pdf->SetY(52.5);
        $re = explode('.', $inspectionDescriptionFinal);
        $pdf->MultiCell(0,6,$personFirstName." ".$personLastName." OR Current Resident",0,'0',false);
        $pdf->SetFont('','',9);
        $pdf->MultiCell(0,3.5,$homeAddress1Final."\n".$cityArray[$homeAddressCityFinal].", ".$stateArray[$homeAddressStateFinal].",".$zipArray[$homeAddressZipFinal]."\n\n\n".date('M d,Y',strtotime($inspectionDateFinal))."",0,'0',false);
        $pdf->SetFont('','B',9);
        $pdf->MultiCell(0,3.5,"\n\nRE: ".$re[0]." - ".$inspectionStatusTextFinal." ",0,'0',false);
        $pdf->SetFont('','',9);
    $pdf->MultiCell(0,3.5,"\n\nDear ".$personFirstName." ".$personLastName." OR Current Resident:\n\n".$communityLegalName." is a planned community governed by covenants, conditions and restrictions. Compliance with these rules benefits the entire community and all property owners are responsible for protecting the aesthetics and harmony of the neighborhood.
\n\nIt has been reported or observed during a routine site inspection on ".date('m/d/y',strtotime($inspectionDateFinal))." that the property was out of compliance with the community rules and regulations.",0,'0',false);
$pdf->WriteHTML("<br><b>This violation specifically regards the following item(s): ".$inspectionDescriptionFinal."</b> It was noted that this violation occurred in the following location: <b>".$locationArray[$inspectionLocationID]."</b>.");
$pdf->Ln();
if ( $inspectionSubCategorySection ){
    $queryy = "SELECT NAME FROM COMMUNITY_LEGAL_DOCS WHERE ID=".$inspectionLegalDocsID;
    $queryyResult = pg_query($queryy);
    $row = pg_fetch_assoc($queryyResult);
    $name  = $row['name'];
    $pdf->WriteHTML("According to ".$inspectionSubCategorySection." of ".$name." ".$inspectionSubCategoryRuleDescription.$inspectionFooter);

}
else {
    $pdf->WriteHTML($inspectionSubCategoryRuleDescription.$inspectionFooter);
}
$pdf->Ln();
$pdf->WriteHTML('<br>If you have already corrected the issue noted above, please disregard this courtesy notice, since no further action is required.<br><br>Thank you for your cooperation in maintaining the appearance and value of '.$communityLegalName.'. If you have any questions, please contact us via our Resident Portal at <a href="https://hoaboardtime.com">https://hoaboardtime.com</a><br><br>'.$communityLegalName);
$pdf->Rect($pdf->w,$pdf->h,100,1);
    if ( $documentID ){
        $format = explode('.', $attachDescription);
        $format = end($format);

        $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
        $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
        $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

        $url = 'https://content.dropboxapi.com/2/files/download';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Dropbox-API-Arg: {"path": "'.$techID.'"}'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'DOWNLOAD','".$techID."','".date('Y-m-d H:i:s')."')";
        if ( !pg_query($dropboxInsertQuery) ){
            // print_r("Failed to insert to dropbox_stats");
            // print_r(nl2br("\n\n"));
        }

        $attachmentName = uniqid();
        $attachmentName = $attachmentName.".".$format;
        $file = fopen($attachmentName, "w");
        fwrite($file, $response);
        fclose($file);
        $pdf->WriteHTML($pdf->Image($attachmentName,null,null,0,90));
        unlink($attachmentName);
    }

 if (file_exists('data.zip')) { 
    unlink ('data.zip'); 
    
}
 if (file_exists('data.tab')) { 
    unlink ('data.tab');   
}
$fileNameFinal = $inspectionHOAID.'-'.$inspectionHomeID.'-'.$id.'-'.$inspectionDateFinal;
$pdfFileNameFinal  = $fileNameFinal.'.pdf';
$tabFileNameFinal  = $fileNameFinal.'.tab';
$zipFileNameFinal = $fileNameFinal.'.zip';
$pdf->Output($pdfFileNameFinal,'F');
$handler = fopen($tabFileNameFinal, 'w');
$finalWriteData = "1"."\t".$personFirstName.' '.$personLastName."\t".$homeAddress1Final."\t".$cityArray[$homeAddressCityFinal]." ".$stateArray[$homeAddressStateFinal]." ".$zipArray[$homeAddressZipFinal]."\t\t\t1\t".$pdf->PageNo()."\t".$pdfFileNameFinal."\t".$communityMailingAddress."\t".$cityArray[$communityMailingAddressCity]." ".$stateArray[$communityMailingAddressState]." ".$zipArray[$communityMailingAddressZip]."\t\t\t".$communityLegalName;
fwrite($handler, $finalWriteData);
fclose($handler);
$zip = new ZipArchive;
if ($zip->open($zipFileNameFinal,  ZipArchive::CREATE)) {
$zip->addFile($pdfFileNameFinal, $pdfFileNameFinal);
$zip->addFile($tabFileNameFinal, $tabFileNameFinal);
$zip->close();
$fileData = file_get_contents($pdfFileNameFinal);

  $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);


$url = 'https://content.dropboxapi.com/2/files/upload';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Inspection_Notices_New/PDF/'.$pdfFileNameFinal.'","mode": "overwrite","autorename": true,"mute": false}'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    $response = json_decode($response);
    $fileID = $response->id;
    curl_close($ch);

    $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(401,'UPLOAD','/Inspection_Notices_New/PDF/".$pdfFileNameFinal."','".date('Y-m-d H:i:s')."')";
    if ( !pg_query($dropboxInsertQuery) ){
        print_r("Failed to insert to dropbox_stats");
        print_r(nl2br("\n\n"));
    }

    unlink($zipFileNameFinal);
    unlink($tabFileNameFinal);
    unlink($pdfFileNameFinal);
    $subject = "Inspection Notice";
    $documentID = $fileID;
    $body = "";
    $email = $sendToEmail;
    $hoaID = $inspectionHOAID;
    $sendURL = "https://hoaboardtime.com/dropboxInspectionToMandrill.php?hoaid=".$hoaID."&docid=".$documentID."&email=".$email;
    print_r($sendURL);
    $req = curl_init();
    curl_setopt($req, CURLOPT_URL,$sendURL);
    curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
    $message = curl_exec($req);
    print_r($message);
    }
}
?>
