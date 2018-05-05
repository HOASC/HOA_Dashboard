<?php
$someJSON = file_get_contents('php://input');
$parsedJSON = json_decode($someJSON);
$customerToken = $parsedJSON[0]->customer_token;
$url = "https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/customers/".$customerToken;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','X-Forte-Auth-Organization-Id:org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        $jsonResult = json_decode($result);
        echo $jsonResult;
?>