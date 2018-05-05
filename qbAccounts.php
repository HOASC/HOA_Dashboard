<?php
date_default_timezone_set('America/Los_Angeles');
setlocale(LC_MONETARY, 'en_US');
include 'includes/dbconn.php';
$ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query');
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1510096508",oauth_nonce="k1v4mlVIwhI",oauth_version="1.0",oauth_signature="qEH58oWNfZPyw7YpjpyftHQQRd4%3D"'));
   curl_setopt($ch, CURLOPT_POSTFIELDS, "Select * from Account MAXRESULTS 1000");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
   $result = curl_exec($ch);
   $result = json_decode($result);
   foreach ($result->QueryResponse->Account as $account) {
       
        if ( strtolower($account->Classification) == 'revenue'){
            $classification  = 3;
        }
        else if ( strtolower($account->Classification) == 'liability'){
            $classification = 1;
        }
        else  if ( strtolower($account->Classification) == 'asset' ){
            $classification = 4;
        }
        else if ( strtolower($account->Classification) == 'expense' ) {
            $classification = 2;
        }
        else if ( strtolower($account->Classification) == 'equity' ){
            $classification = 5;
        }
        else {
            echo "failed cannot find classification";
            exit(0);
        }

        if ( $account->Active == 'true' ){
            $stat = 'true';
        }
        else {
            $stat = 'false';
        }


        if ( isset($account->ParentRef->value) ){
       $query = "INSERT INTO community_accounts (community_id,category,is_active,updated_on,updated_by,account_type,display_name,display_desc,is_sub_account,parent_account,account_sub_type,qb_id) VALUES(2,$classification,'".$stat."','".date('Y-m-d H:i:s')."',401,'".$account->AccountType."','".$account->Name."','".$account->Description."','true',(SELECT ID FROM community_accounts WHERE COMMUNITY_ID = 2 AND qb_id=".$account->ParentRef->value."),'".$account->AccountSubType."',".$account->Id.") ON CONFLICT (community_id,qb_id) DO UPDATE SET updated_on='".date('Y-m-d H:i:s')."',updated_by=401";
       pg_query($query);
        print_r(nl2br("\n\n\n"));
        }
        else{
             $query = "INSERT INTO community_accounts (community_id,category,is_active,updated_on,updated_by,account_type,display_name,display_desc,is_sub_account,account_sub_type,qb_id) VALUES(2,$classification,'".$stat."','".date('Y-m-d H:i:s')."',401,'".$account->AccountType."','".$account->Name."','".$account->Description."','false','".$account->AccountSubType."',".$account->Id.") ON CONFLICT (community_id,qb_id) DO UPDATE SET updated_on='".date('Y-m-d H:i:s')."',updated_by=401";
             pg_query($query);
            print_r(nl2br("\n\n\n"));
        }

   }
?>
