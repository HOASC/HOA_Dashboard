 <?php
 error_reporting(E_ALL);
ini_set('display_errors', 1);

		$jsonData = json_decode(file_get_contents('php://input'));
		$purchaseId = $jsonData[0]->purchase_id;
		$fileName = $jsonData[0]->file_name;
		$fileContents = $jsonData[0]->file_data;
		$format23 = explode('.', $fileName);
		$format = end($format23);
//Setting content type 
if ( $format == 'ai' ){
	$format = 'application/postscript';
}
else if ( $format == 'csv' ){
	$format = 'text/csv';
}
else if ( $format == 'doc' ){
	$format = 'application/msword';
}
else if ( $format == 'docx' ) {
	$format = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
}
else if ( $format == 'eps' ){
	$format = 'application/postscript';
}
else if ( $format == 'gif' ){
	$format = 'image/gif';
}
else if ( $format == 'jpeg' ){
	$format = 'image/jpeg';
}
else if ( $format == 'jpg' ){
	$format = 'image/jpg';

}
else if ( $format == 'ods' ){
	$format = 'application/vnd.oasis.opendocument.spreadsheet';
}
else if ( $format == 'pdf' ){
	$format = 'application/pdf';
}
else if ( $format == 'png' ){
	$format = 'image/png';

}
else if ( $format == 'rtf' ){
	$format = 'text/rtf';
}
else if ( $format == 'tif' ) {
	$format = 'image/tiff';
}
else if ( $format == 'xls' ){
	$format = 'application/vnd.ms-excel';
}
else if ( $format == 'xlsx' ){
	$format = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
}
else if ( $format == 'xml' ) {
	$format = 'text/xml';
}
else {
	echo "Unsupported format.";
	exit(0);
}
$uploadData = '--37a1965f87babd849241a530ad71e169
Content-Disposition: form-data; name="file_metadata_0"
Content-Type: application/json; charset=UTF-8
Content-Transfer-Encoding: 8bit

{
    "AttachableRef": [
    {
      "EntityRef": {
        "type": "Purchase",
        "value": "'.$purchaseId.'"
      }
    }
  ],
   "FileName": "'.$fileName.'",
    "ContentType": "'.$format.'"
  }
--37a1965f87babd849241a530ad71e169
Content-Disposition: form-data; name="file_content_0"; filename="398535758.jpg"
Content-Type: image/jpeg
Content-Transfer-Encoding: base64

'.$fileContents.'

--37a1965f87babd849241a530ad71e169--';
 		$ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/upload');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Content-Type:multipart/form-data;boundary=37a1965f87babd849241a530ad71e169','Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1492203509",oauth_nonce="Q2Ck7t",oauth_version="1.0",oauth_signature="u%2FhXjOu3eeGH9%2FbTM%2BwTV%2BfXzNc%3D"'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $uploadData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($ch);
            $result = json_decode($result);

            if ( $result != "" ) {
    			echo "Attachment Added.";
			}
            else {
            	echo "Failed to add attachment";
            }
?>