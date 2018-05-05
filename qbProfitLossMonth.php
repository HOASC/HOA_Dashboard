<?php
  
   include 'includes/dbconn.php';
   
   function get_data($receivedData,$month){
   $year = 2017;
   $communityid = 2;
     if ( isset($receivedData->Rows->Row) ){
         foreach ($receivedData->Rows->Row as $row) {
            get_data($row,$month);
         }
     }
     else {
      if (  isset($receivedData->ColData) ){

        $query = "INSERT INTO qb_monthly_actuals (qb_vendor_id, month, year,amount,community_id) 
VALUES(".$receivedData->ColData[0]->id.",$month,$year,".$receivedData->ColData[1]->value.",$communityid)
ON CONFLICT (qb_vendor_id,month,year) DO UPDATE 
  SET amount =".$receivedData->ColData[1]->value;
        if ( !pg_query($query) ){
            print_r($query);
        }
      }
     }
   }


   date_default_timezone_set('America/Los_Angeles');
  
  if ( date("m") == 12){
   $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/ProfitAndLoss?start_date=2017-12-01&end_date=2017-12-31');
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509922131",oauth_nonce="ET4UxbHJdXH",oauth_version="1.0",oauth_signature="rb1q6MRKHMREvtYvrETPpp4JErg%3D"'));
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
   $result = curl_exec($ch);
   $result =  json_decode($result);
   
   $value = get_data($result) ;

   print_r($value);
 }
 else if ( date("m") == 11 ){
     $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/ProfitAndLoss?start_date=2017-11-01&end_date=2017-11-30');
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509922065",oauth_nonce="c96EXnHhHKG",oauth_version="1.0",oauth_signature="82bJ8zeKGRPdp2l767%2B23m4hGDg%3D"'));
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
   $result = curl_exec($ch);
   $result =  json_decode($result);
   $value = get_data($result,date("m")) ;
   print_r($value);
 }
   
?>