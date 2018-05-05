<!DOCTYPE html>
<html>
<head>
  <title>Email Consent</title>
</head>
<body>
</body>
</html>
<?php
$email = '';
$currentdate = date("Y/m/d");
$name = '';
$address = 'Address';
$phonenumber = '';
$homephonenumber = '';
$key_fob_number = '';
include 'includes/dbconn.php';
if ( true ) {
   $query = "select hoa_id,home_id,firstname,lastname,email,cell_no from hoaid where hoa_id=".$_GET['id'];
   $result  = pg_query($query);
   while ($row = pg_fetch_row($result)) {
     $email = $row[4];
     $name = $row[2];
     $name .= $row[3];
     if ( !$phonenumber ) {
     $phonenumber = $row[5];
   }
     $query2 = "select address1 from homeid where home_id=".$row[1];
     $result2 = pg_query($query2);
     while ($row2 = pg_fetch_row($result2)) {
       $address = $row2[0];
     }
   }
}
echo $email,$address,$phonenumber,$name;
$data = '{
  "documentCreationInfo": {
    "fileInfos": [
      {
        "transientDocumentId": "3AAABLblqZhDRjuExwo-WKYVbHDHVqq9uc67qGcaXMxNUlIPowJz6ADYfopBO4PM9AHcGtyDwYixfMJpMBVE6lbUJPdSXJYR5azPw--wZV23AAdQYdk-j4MWZAdvgT4XIeZdp3bBnYjFN3Tc9gP5gVnSc0l3FY0k5tMYRUCI9AVf67Oo54DrxUxRFSyrkPEVCFKqko09Vt53EvcQTxdPdSdFBGCPdoP38aOIKaWYbo7uJd5PA6kbhGs-SoTZfRZsNOXWWDO52pytJvImtyEViKVECjFKJfS1PjKLwZff4MwsB3xet72RKZgqmFr4J7kdlU-3f0IP4wNG3QAIkDNca3uAbFo2V16MQ"
      }
    ],
     "mergeFieldInfo": [
      {
        "defaultValue": "'.$name.'",
        "fieldName": "home_owner_names"
      },
      {
        "defaultValue": "'.$address.'",
        "fieldName": "property_address"
      },
      {
          "defaultValue": "'.$homephonenumber.'",
          "fieldName": "owner_home_phone_number"
      },
      {
        "defaultValue": "'.$phonenumber.'",
        "fieldName": "owner_cell_phone_number"
      },
      {
        "defaultValue": "'.$email.'",
        "fieldName": "owner_email_address"
      }
    ],
    "name": "Pool Rules",
    "recipientSetInfos": [
      {
        "recipientSetMemberInfos": [
          {
            "email": "'.$email.'"
          }
        ],
        "recipientSetRole": "SIGNER"
      }
    ],
    "signatureType": "ESIGN",
    "signatureFlow": "SEQUENTIAL"
  }
}
';
$ch = curl_init('https://api.na1.echosign.com/api/rest/v5/agreements');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json', 
    'Access-Token: 3AAABLblqZhBbuoGJoQZXIMhISUIAnh7R_qmzGgn_COsBf1G0kXyDFiaXxE-oM8ZMaL1LPybdYz1U2gYXszLLzpLuenZ3Ojfm'));
$result = curl_exec($ch);

$result = json_decode($result,true);

if (strpos($result['code'], 'REQUIRED')) {
  echo "Failed to send.";
}
else {
  echo "Successfully Sent.";
  echo "<br>";
  echo "Agreement Id ";
  echo $result['agreementId'];
}
?>

echo "<br><br><br><h3><center><a href=''>Click here</a> if this doesnot automatically redirect in 5 seconds.</center></h3><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardCommunityAgreements.php'},2000);</script>";

</body>
</html>