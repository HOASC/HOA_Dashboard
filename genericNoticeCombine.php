<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require('mc_table.php');
require('includes/dbconn.php')
ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}

    try{
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
        $inspectionNoticeID = $_GET['id'];
        $allInspectionQuery = "SELECT * FROM INSPECTION_NOTICES WHERE ID=$inspectionNoticeID";
        $allInspectionQueryResult = pg_query($allInspectionQuery);
        while($row = pg_fetch_assoc($allInspectionQueryResult))
        {



        $id = $row['id']; 
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
        $communityInfoQuery = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$inspectionCommunityIDFinal;
        $communityInfoQueryResult = pg_query($communityInfoQuery);
        $row = pg_fetch_assoc($communityInfoQueryResult);
        $communityLegalName = $row['legal_name'];
        $communityCodeName = $row['community_code'];
        $communityMailingAddress = $row['mailing_address'];
        $communityMailingAddressCity  = $row['mailing_addr_city'];
        $communityMailingAddressState = $row['mailing_addr_state'];
        $communityMailingAddressZip = $row['mailing_addr_zip'];
       
        $inspectionIDS = array();

        $newQuery = "SELECT * FROM INSPECTION_NOTICES WHERE HOME_ID=".$inspectionHomeID." AND (INSPECTION_STATUS_ID != 2 AND INSPECTION_STATUS_ID != 13 AND INSPECTION_STATUS_ID != 14) ORDER BY ID";

        $newQueryResult = pg_query($newQuery);

        while ($newRow = pg_fetch_assoc($newQueryResult)) {
            $inspectionIDS[$newRow['id']] = 1;
        }


        $pdf=new PDF_MC_Table();
        $pdf->SetFont('Arial','B',8);



        foreach ($inspectionIDS as $key => $value) {


        $allInspectionQuery = "SELECT * FROM INSPECTION_NOTICES WHERE ID=".$key." ORDER  BY ID ASC";
        $allInspectionQueryResult = pg_query($allInspectionQuery);
        $row = pg_fetch_assoc($allInspectionQueryResult);
        $id = $row['id']; 
        $inspectionDateFinal = $row['inspection_date'];
        $inspectionStatusIDFinal = $row['inspection_status_id'];
        $inspectionDescriptionFinal = $row['description'];
        $inspectionNoticeTypeFinal  = $row['inspection_notice_type_id'];
        $inspectionCommunityIDFinal = $row['community_id'];
        $inspectionCategoryID = $row['inspection_category_id'];
        $inspectionLocationID = $row['location_id'];
        $inspectionSubCategoryID = $row['inspection_sub_category_id'];


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
        }
        date_default_timezone_set('America/Los_Angeles');
        
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
        $pdf->MultiCell(0,3.5,$homeAddress1Final."\n".$cityArray[$homeAddressCityFinal].", ".$stateArray[$homeAddressStateFinal].",".$zipArray[$homeAddressZipFinal]."\n\n\n".date('M d, Y',strtotime($inspectionDateFinal))."",0,'0',false);
        $pdf->SetFont('','B',9);
        $pdf->MultiCell(0,3.5,"\n\nRE: ".$re[0]." - ".$inspectionStatusTextFinal." ",0,'0',false);
        $pdf->SetFont('','',9);
        $pdf->MultiCell(0,3.5,"\n\nDear ".$personFirstName." ".$personLastName." OR Current Resident:\n\n".$communityLegalName." is a planned community governed by covenants, conditions and restrictions. Compliance with these rules benefits the entire community and all property owners are responsible for protecting the aesthetics and harmony of the neighborhood.
\n\nBy now you have probably already corrected the following issue at ".$homeAddress1Final.". If not, then this is a courtesy reminder from ".$communityLegalName.".\n\nIt has been reported or observed during a routine site inspection on ".date('m/d/y',strtotime($inspectionDateFinal))." that the property was out of compliance with the community rules and regulations.",0,'0',false);
        $pdf->WriteHTML("<br><b>This violation specifically regards the following item(s): ".$inspectionDescriptionFinal."</b> It was noted that this violation occurred in the following location: <b>".$locationArray[$inspectionLocationID]."</b>.");
        $pdf->Ln();
        $pdf->WriteHTML('<br>If you have already corrected the issue noted above, please disregard this courtesy notice, since no further action is required.<br><br>Thank you for your cooperation in maintaining the appearance and value of '.$communityLegalName.'. If you have any questions, please contact us via our Resident Portal at <a href="https://hoaboardtime.com">https://hoaboardtime.com</a><br><br>'.$communityLegalName);
        $pdf->Rect($pdf->w,$pdf->h,100,1);

        if (file_exists('data.zip')) { 
                unlink ('data.zip'); 
    
        }
        if (file_exists('data.tab')) { 
                unlink ('data.tab');   
        }
        $fileNameFinal = mt_rand();
        $pdfFileNameFinal  = $fileNameFinal.'.pdf';
        $tabFileNameFinal  = $fileNameFinal.'.tab';
        $zipFileNameFinal = $fileNameFinal.'.zip';
} 
    

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






            $fileData = file_get_contents($zipFileNameFinal);

              $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

            $url = 'https://content.dropboxapi.com/2/files/upload';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Inspection_Notices_New/ZIP/'.$zipFileNameFinal.'","mode": "overwrite","autorename": true,"mute": false}'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($ch);

            $dropboxPath = "/Inspection_Notices_New/ZIP/".$zipFileNameFinal;

            $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','".$dropboxPath."','')";
            if ( !pg_query($dropboxInsertQuery) ){
                    print_r("Failed to insert to dropbox_stats");
                    print_r(nl2br("\n\n"));
            }

            $zipTechID = json_decode($response)->id;

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

            $dropboxPath = "/Inspection_Notices_New/PDF/".$pdfFileNameFinal;
            $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','".$dropboxPath."','".date('Y-m-d H:i:s')."')";
            if ( !pg_query($dropboxInsertQuery) ){
                print_r("Failed to insert to dropbox_stats");
                print_r(nl2br("\n\n"));
            }


            curl_close($ch);
            unlink($zipFileNameFinal);
            unlink($tabFileNameFinal);
            unlink($pdfFileNameFinal);


            $pdfTechID = json_decode($response)->id;

            if ( $pdfTechID && $zipTechID) {
                $idsArray = array();
                $idsArray["zip"] = $zipTechID;
                $idsArray["pdf"] = $pdfTechID;
                $idsArray["hoaid"] = $inspectionHOAID;
                $jsonData = json_encode($idsArray);
                echo $jsonData;
                echo "@File Uploaded.";
            }
            else{
                echo "An error occured.";
            }

        }
}
}
catch( Exception $ex){
    print_r($ex->getMessage());
    echo "An error occured.";
    exit(0);
}
?>
