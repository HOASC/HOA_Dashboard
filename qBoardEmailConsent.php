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
include 'includes/dbconn.php';
if ( true ) {
   $query = "select hoa_id,home_id,firstname,lastname,email from hoaid where hoa_id=".$_GET['id'];
   $result  = pg_query($query);
   while ($row = pg_fetch_row($result)) {
     $email = $row[4];
     $name = $row[2];
     $name .= $row[3];
     $query2 = "select address1 from homeid where home_id=".$row[1];
     $result2 = pg_query($query2);
     while ($row2 = pg_fetch_row($result2)) {
       $address = $row2[0];
     }
   }
}
$data = '{
  "documentCreationInfo": {
    "fileInfos": [
      {
        "transientDocumentId": "3AAABLblqZhAfW1XzubUeQGiAgeeWXavYNy-BcUZCXgyfZrT6YtcrUsXqLhxfGUUU0XHHW6BlqTnLCf5F23lJSRkAix6EvYfcze6Ba9aY5csVIqs6FInlYH51XHpNyO_OutIEsFD4-N4sW4fzH85jiPOV0tblDrOZvyHdzPcYxg97F3aGKkpjHhfGJlnb6b7dvqMlKjNgyPW1KA2qr0hYh7aiuC4xG0tuvxe53JVd1bNL6IFnxuzUbBk4emMl0-9mSdAATtiplV_6niPVqppWwbBAZ1Vjdx5JThHIh-DS-eOHL7lUWUjV_ajzaTV-pPKjNkT4Dx0dPvz74K1PcJiDx4hC7i3ivMmb"
      }
    ],

 "mergeFieldInfo": [
      {
        "defaultValue": "'.$email.'",
        "fieldName": "email"
      },
      {
        "defaultValue": "'.$address.'",
        "fieldName": "address"
      },
      {
        "defaultValue": "'.$name.'",
        "fieldName": "member_name"
      },
      {
          "defaultValue": "'.$date.'",
          "fieldName": "date"
      }
    ],
    "name": "Email Consent Form",
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

echo "<br><br><br><h3><center><a href=''>Click here</a> if this doesnot automatically redirect in 5 seconds.</center></h3><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardCommunityAgreements.php'},2000);</script>";
?>

</body>
</html>