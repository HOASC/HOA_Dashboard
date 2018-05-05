<?php
date_default_timezone_set('America/Los_Angeles');
$updateCount = 0;
$insertCount = 0;
include 'includes/dbconn.php';
$pullAgreementsQuery = "SELECT mega_sign_id FROM community_mega_sign_agreements WHERE COMMUNITY_ID = 2";
$result  = pg_query($pullAgreementsQuery);

while ($row = pg_fetch_row($result)) {
    $agreementIDS[$row[0]] = 1;
}
$megaSignAgreementsArray = array();
$megaSignAgreementsQuery  = "SELECT * FROM COMMUNITY_SIGN_AGREEMENTS WHERE IS_MEGA_SIGN_AGREEMENT IS TRUE AND COMMUNITY_ID = 2";
$megaSignAgreementsQueryResponse = pg_query($megaSignAgreementsQuery);
while ($row = pg_fetch_assoc($megaSignAgreementsQueryResponse)) {
    $megaSignAgreementsArray[$row['agreement_id']] =  $row['document_to'];
}
foreach ($agreementIDS as $key => $value) {
    $url = "https://api.na1.echosign.com:443/api/rest/v5/megaSigns/".$key."/agreements";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Access-Token:3AAABLblqZhBbuoGJoQZXIMhISUIAnh7R_qmzGgn_COsBf1G0kXyDFiaXxE-oM8ZMaL1LPybdYz1U2gYXszLLzpLuenZ3Ojfm'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $megaAgreements = curl_exec($ch);
    curl_close($ch);
    $megaSignAgreements = json_decode($megaAgreements);
    if($megaSignAgreements->code){
        // print_r("An error occured occured.... Adobe error message".$megaSignAgreements->code);
    }
    else {
        foreach ($megaSignAgreements->megaSignChildAgreementList as $megaSignChildAgreementsDetails) {
            if ( $megaSignAgreementsArray[$megaSignChildAgreementsDetails->agreementId] ){
                $updateQuery = "UPDATE COMMUNITY_SIGN_AGREEMENTS SET AGREEMENT_STATUS='".$megaSignChildAgreementsDetails->status."',last_updated='".date('Y-m-d H:i:s')."' WHERE agreement_id='".$megaSignChildAgreementsDetails->agreementId."' AND document_to='".$megaSignAgreementsArray[$megaSignChildAgreementsDetails->agreementId]."'";
                if ( !(pg_query($updateQuery))){
                    // print_r("Failed".$updateQuery.nl2br("\n"));
                }
                else {
                    $updateCount = $updateCount + 1;
                }
            }
            else {
                $agreeementURL = 'https://api.na1.echosign.com:443/api/rest/v5/agreements/'.$megaSignChildAgreementsDetails->agreementId;
                $agreeementCURL = curl_init($agreeementURL);
                curl_setopt($agreeementCURL, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($agreeementCURL, CURLOPT_HTTPHEADER, array('Access-Token:3AAABLblqZhBbuoGJoQZXIMhISUIAnh7R_qmzGgn_COsBf1G0kXyDFiaXxE-oM8ZMaL1LPybdYz1U2gYXszLLzpLuenZ3Ojfm'));
                curl_setopt($agreeementCURL, CURLOPT_RETURNTRANSFER, TRUE);
                $agreementInformation = curl_exec($agreeementCURL);
                $agreementInformation = json_decode($agreementInformation);
                foreach ($agreementInformation->events as $event) {
                    if ( $event->type == 'SIGNATURE_REQUESTED'){
                        $insertQuery  = "INSERT INTO COMMUNITY_SIGN_AGREEMENTS(\"community_id\",\"document_to\",\"document_type\",\"agreement_id\",\"create_date\",\"send_date\",\"agreement_status\",\"agreement_name\",\"sent_to_count\",\"last_updated\",\"is_mega_sign_agreement\",\"mega_sign_id\") VALUES(2,'".$event->participantEmail."',1,'".$agreementInformation->agreementId."','".date('Y-m-d H:i:s',strtotime($megaSignChildAgreementsDetails->displayDate))."','".date('Y-m-d H:i:s',strtotime($megaSignChildAgreementsDetails->displayDate))."','".$megaSignChildAgreementsDetails->status."','".$megaSignChildAgreementsDetails->name."',1,'".date('Y-m-d H:i:s')."','TRUE','".$key."')";
                        if ( !(pg_query($insertQuery)) ){
                                // print_r("Failed".nl2br("\n").$insertQuery);
                        }
                        else {
                            $insertCount  = $insertCount + 1;
                        }
                        break;
                    }
                }
            }
        }
    }
}
<<<<<<< HEAD
echo "Insert Count : " + $insertCount;
echo "Update Count : " + $updateCount;
=======
print_r("Records inserted : ".$insertCount." . Records Updated: ".$updateCount);
>>>>>>> 7fd5e19af8ed00258485152e1e09e492139e728d
pg_close();
?>