<?php
$id = $_GET['id'];
$email = "";
include 'includes/dbconn.php';
if ( true ) {
   $query = "select hoa_id,home_id,firstname,lastname,email from hoaid where hoa_id=".$_GET['id'];
   $result  = pg_query($query);
   while ($row = pg_fetch_row($result)) {
     $email = $row[4];
   }
}
$data = '{
  "documentCreationInfo": {
    "fileInfos": [
      {
        "transientDocumentId": "3AAABLblqZhDuZ_AlnWj9LayO58q5Lplb_eZ9LuHHuyToYzBWn7lhpmreDgtQ8l9L10DJ9S0t_ZqhLw4It-Rnk4PWr3D4sTQ-r_7Nwy_e_AM8gg-glPqbW0F9UmJuBpPcebMfS4qLQ2pqVzQ_Z9t8w313xgdfpHJuKJal-ExqTvDw223W519qA0Kzx264l3o4U3lVfPvQv8ZtHfaRt5a5TxRdzAjQ4fI8Tubtnw3YE21QLxKncXgWySghV1GzD9gG4pjC_ij9HwGg4153lbPe45SQJczavRO4D6SRXkeCnxntI9dai6rHbolkIC3BFPyPk8cjPmvy8t6ZhVkcZPDeLOgTnZIiA5d_"
      }
    ],
    "name": "Collection Policy",
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
  print_r($result);
}
else {
  echo "Successfully Sent.";
  echo "<br>";
  echo "Agreement Id ";
  echo $result['agreementId'];
}

echo "<br><br><br><h3><center><a href=''>Click here</a> if this doesnot automatically redirect in 5 seconds.</center></h3><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardCommunityAgreements.php'},2000);</script>";

?>