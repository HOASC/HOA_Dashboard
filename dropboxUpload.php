<?php

  $path = 'residentDashboard.php';
  $fp = fopen($path, 'rb');
  $size = filesize($path);

  include 'includes/dbconn.php';
  $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);


  $cheaders = array('Authorization: Bearer '.$accessToken,
                    'Content-Type: application/octet-stream',
                    'Dropbox-API-Arg: {"path":"/'.$path.'", "mode":"add"}');

  $ch = curl_init('https://content.dropboxapi.com/2/files/upload');
  curl_setopt($ch, CURLOPT_HTTPHEADER, $cheaders);
  curl_setopt($ch, CURLOPT_PUT, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_INFILE, $fp);
  curl_setopt($ch, CURLOPT_INFILESIZE, $size);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);

  echo $response;
  curl_close($ch);
  fclose($fp);

  #Key : dz9ohpqbhptmb9n
  #Secret : rmihz90xgng7q1n

?>