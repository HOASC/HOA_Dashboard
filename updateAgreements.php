<?php
date_default_timezone_set('America/Los_Angeles');
$insertCount = 0;
$updateCount = 0;
include 'includes/dbconn.php';
$pullAgreementsQuery = "SELECT agreement_id FROM community_sign_agreements WHERE COMMUNITY_ID = 2";
$pullAgreementsQueryResult = pg_query($pullAgreementsQuery);
$agreementIDS = array();
if($pullAgreementsQueryResult){
while ($row = pg_fetch_row($pullAgreementsQueryResult)) {
    $agreementIDS[$row[0]] = 1;
}
}

 $url  = 'https://api.na1.echosign.com:443/api/rest/v5/agreements';
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Access-Token:3AAABLblqZhBbuoGJoQZXIMhISUIAnh7R_qmzGgn_COsBf1G0kXyDFiaXxE-oM8ZMaL1LPybdYz1U2gYXszLLzpLuenZ3Ojfm'));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 $result = curl_exec($ch);
 $result = json_decode($result);
foreach ($result->userAgreementList as $agreement) {
    if ( $agreementIDS[$agreement->agreementId] ){
        
        $updateQuery = "UPDATE COMMUNITY_SIGN_AGREEMENTS SET AGREEMENT_STATUS='".$agreement->status."',LAST_UPDATED='".date('Y-m-d H:i:s')."',UPDATED_BY = 401 WHERE agreement_id='".$agreement->agreementId."'";
        if (!pg_query($updateQuery)){
                // print_r("Failed to update".nl2br("\n"));
        }
        else {
            $updateCount = $updateCount + 1;
        }

    }
    else {
            $sendToCount = 0;
            $sendToNames = array();
            foreach ($agreement->displayUserSetInfos as $user) {
                foreach ($user->displayUserSetMemberInfos as $userInfo) {
                    $sendToCount = $sendToCount + 1;
                    $sendToNames[$userInfo->email] = 1;
                }
            }
        foreach ($sendToNames as $key => $value) {
                $insertQuery  = "INSERT INTO COMMUNITY_SIGN_AGREEMENTS(\"community_id\",\"document_to\",\"document_type\",\"agreement_id\",\"create_date\",\"send_date\",\"agreement_status\",\"agreement_name\",\"sent_to_count\",\"last_updated\") VALUES(2,'".$key."',1,'".$agreement->agreementId."','".$agreement->displayDate."','".$agreement->displayDate."','".$agreement->status."','".$agreement->name."',".$sendToCount.",'".date('Y-m-d H:i:s')."')";
                if (pg_query($insertQuery)){

                }
                else{
                    // print_r("Failed".$insertQuery);
                    $insertCount = $insertCount + 1;
                }
        }
    }
}
print_r("Records inserted ".$insertCount.". Records Updated ".$updateCount);
?>