<?php
            setlocale(LC_MONETARY, 'en_US');
            
            include 'includes/dbconn.php';
            
            date_default_timezone_set('America/Los_Angeles');
            $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509541160",oauth_nonce="4u2GbsqN86U",oauth_version="1.0",oauth_signature="OOpV7UMNAkRACPJjJ2SU%2FzidANE%3D"'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, "Select * from Attachable where AttachableRef.EntityRef.Type='Purchase' MAXRESULTS 1000");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($ch);
            $result =  json_decode($result);
            foreach ($result->QueryResponse->Attachable as $attachable) {


                foreach ($attachable->AttachableRef as $attachableRef) {
                  $query = "INSERT INTO qb_purchase_attchments(community_id,purchase_id,attachment_name,updated_on,updated_by,qb_attachable_id)  VALUES(2,".$attachableRef->EntityRef->value.",'".$attachable->FileName."','".date('Y-m-d H:i:s')."',401,".$attachable->Id.") ON CONFLICT(qb_attachable_id) DO UPDATE SET updated_on='".date('Y-m-d H:i:s')."',updated_by = 401";
                if ( !pg_query($query)) {
                    print_r($query);
                }

                }
                 
            }
?>

