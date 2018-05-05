<?php
  $someJSON = file_get_contents('php://input');
$parsedJSON = json_decode($someJSON);
$url = "https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/customers/customers/".$parsedJSON[0]->customer_token;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','X-Forte-Auth-Organization-Id:org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
curl_setopt($curl, CURLOPT_POSTFIELDS, array('"first_name": "'.$parsedJSON[0]->first_name.'"','"last_name": "'.$parsedJSON[0]->last_name.'"','"customer_id": "'.$parsedJSON[0]->customer_id.'"','"status": "'.$parsedJSON[0]->status.'"'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($ch);
curl_close($ch);
echo $result;
?>