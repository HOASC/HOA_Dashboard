<?php
ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');

include 'includes/dbconn.php';

function getFileCount($file){
        if(file_exists($file)) {
            if($handle = @fopen($file, "rb")) {
                $count = 0;
                $i=0;
                while (!feof($handle)) {
                    if($i > 0) {
                        $contents .= fread($handle,8152);
                    }
                    else {
                          $contents = fread($handle, 1000);
                        if(preg_match("/\/N\s+([0-9]+)/", $contents, $found)) {
                            return $found[1];
                        }
                    }
                    $i++;
                }
                fclose($handle);             
                if(preg_match_all("/\/Type\s*\/Pages\s*.*\s*\/Count\s+([0-9]+)/", $contents, $capture, PREG_SET_ORDER)) {
                    foreach($capture as $c) {
                        if($c[1] > $count)
                            $count = $c[1];
                    }
                    return $count;            
                }
            }
        }
        return 0;

}




$data = file_get_contents('php://input');
$parseJSON = json_decode($data);

$handler = fopen($parseJSON[0]->file_name, "w");
fwrite($handler, base64_decode($parseJSON[0]->file_data));
fclose($handler);



    $number = getFileCount($parseJSON[0]->file_name);

    if ( isset($response->error_summary) ){
        echo "An error occured. Failed to upload file.";
    }
    else {

    //Creating tab file
    $hoaID = $parseJSON[0]->hoa_id;

    $query = "SELECT * FROM HOAID WHERE HOA_ID=".$hoaID;
    $queryResult = pg_query($query);

    $row = pg_fetch_assoc($queryResult);

    $name = $row['firstname'].' '.$row['lastname'];


    if ( $parseJSON[0]->address == 1){

        $addressQuery = "SELECT * FROM HOMEID WHERE HOME_ID=".$row['home_id'];
        $addressQueryResult = pg_query($addressQuery);
        $addressQueryResult = pg_fetch_assoc($addressQueryResult);
        $address1 = $addressQueryResult['address1'];
        $address2 = $addressQueryResult['address2'];

        $cityQuery = "SELECT CITY_NAME FROM CITY WHERE CITY_ID=".$addressQueryResult['city_id'];
        $cityQueryResult = pg_query($cityQuery);
        $cityQueryResult = pg_fetch_assoc($cityQueryResult);
        $cityName = $cityQueryResult['city_name'];

        $stateQuery = "SELECT STATE_CODE FROM STATE WHERE STATE_ID=".$addressQueryResult['state_id'];
        $stateQueryResult = pg_query($stateQuery);
        $stateQueryResult2 = pg_fetch_assoc($stateQueryResult);
        $personStateName = $stateQueryResult2['state_code'];

        $zipQuery = "SELECT ZIP_CODE FROM ZIP WHERE ZIP_ID=".$addressQueryResult['zip_id'];
        $zipQueryResult = pg_query($zipQuery);
        $zipQueryResult = pg_fetch_assoc($zipQueryResult);
        $zipCode = $zipQueryResult['zip_code'];


        $communityQuery = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$addressQueryResult['community_id'];
        $communityQueryResult = pg_query($communityQuery);
        $communityQueryResult = pg_fetch_assoc($communityQueryResult);

        $communityLegalName = $communityQueryResult['legal_name'];

        $communityMailingAddress = $communityQueryResult['mailing_address'];

        $communityMailingCity = $communityQueryResult['mailing_addr_city'];
        $communityMailingState = $communityQueryResult['mailing_addr_state'];
        $communityMailingZip = $communityQueryResult['mailing_addr_zip'];


        $communityCityQuery = "SELECT CITY_NAME FROM CITY WHERE CITY_ID=".$communityMailingCity;
        $communityCityQuery = pg_query($communityCityQuery);
        $communityCityQuery = pg_fetch_assoc($communityCityQuery);
        $communityCityName = $communityCityQuery['city_name'];

        $communityStateQuery = "SELECT STATE_CODE FROM STATE WHERE STATE_ID=".$communityMailingState;
        $communityStateQueryResult = pg_query($communityStateQuery);
        $communityStateName = pg_fetch_assoc($communityStateQueryResult);

        $communityStateName = $communityStateName['state_code'];

        $communityZipQuery = "SELECT ZIP_CODE FROM ZIP WHERE ZIP_ID=".$communityMailingZip;
        $communityZipQueryResult = pg_query($communityZipQuery);
        $communityZipCode = pg_fetch_assoc($communityZipQueryResult)['zip_code'];


        $handler = fopen('data.tab', 'w');
        fwrite($handler, "1"."\t".$name."\t".$address1."\t".$cityName." ".$personStateName." ".$zipCode."\t\t\t1\t".$number."\t".$parseJSON[0]->file_name."\t".$communityLegalName."\t".$communityMailingAddress." ".$communityCityName." ".$communityStateName." ".$communityZipCode."\t\t\t".$communityLegalName);
        fclose($handler);


        $zipFileNameFinal = mt_rand().'.zip';
        $zip = new ZipArchive;
        if ($zip->open($zipFileNameFinal,  ZipArchive::CREATE)) {
            $zip->addFile($parseJSON[0]->file_name, $parseJSON[0]->file_name);
            $zip->addFile("data.tab", "data.tab");
            $zip->close();
             $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
            $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
            $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);
            
            $url = 'https://content.dropboxapi.com/2/files/upload';
            $pdfFileContent = file_get_contents($zipFileNameFinal);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Sent Files/'.$zipFileNameFinal.'","mode": "overwrite","autorename": false,"mute": false}'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $pdfFileContent); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($ch);
            $response = json_decode($response);
            
            $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','/Sent Files/".$zipFileNameFinal."','".date('Y-m-d H:i:s')."')";
            pg_query($dropboxInsertQuery);

            if ( isset($response->error_summary) ){
                echo "An error occured. Please try again.";
                exit(0);
            }
            $dbResponse = $response;
            $response = file_get_contents($parseJSON[0]->file_name);
            $fileContent  = base64_encode($pdfFileContent);
            $url = "http://southdata.us-west-2.elasticbeanstalk.com/TestOrderMailing.aspx?file_id=".$dbResponse->id."&hoaid=".$hoaID."&type_id=0";
            $req = curl_init();
            curl_setopt($req, CURLOPT_URL,$url);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            if(curl_exec($req) === false)
            {
                $message =  "An error occured. Please try again.";
                echo $message;
                exit(0);
            }
            else 
            {   
                $message = "File uploaded to South Data.";
                echo $message;       
            }  
            
            $query = "INSERT INTO files_sent(hoa_id,file_tech_id,sent_date,file_name) VALUES(".$hoaID.",'".$dbResponse->id."','".date('Y-m-d H:i:s')."','".$parseJSON[0]->file_name."')";
            pg_query($query);
            unlink($zipFileNameFinal);
        }







    }
    else if ( $parseJSON[0]->address == 2 ){


        $addressQuery = "SELECT * FROM HOME_MAILING_ADDRESS WHERE HOME_ID=".$row['home_id'];
        $addressQueryResult = pg_query($addressQuery);
        $addressQueryResult = pg_fetch_assoc($addressQueryResult);
        $address1 = $addressQueryResult['address1'];
        $address2 = $addressQueryResult['address2'];

        $cityQuery = "SELECT CITY_NAME FROM CITY WHERE CITY_ID=".$addressQueryResult['city_id'];
        $cityQueryResult = pg_query($cityQuery);
        $cityQueryResult = pg_fetch_assoc($cityQueryResult);
        $cityName = $cityQueryResult['city_name'];  

        if ( !($cityName) ){
            echo "Address incomplete. Please update address first";
            exit(0);
        }


        $stateQuery = "SELECT STATE_CODE FROM STATE WHERE STATE_ID=".$addressQueryResult['state_id'];
        $stateQueryResult = pg_query($stateQuery);
        $stateQueryResult = pg_fetch_assoc($stateQueryResult);
        $stateName = $stateQueryResult['state_code'];

        if ( !($stateName) ){
            echo "Address incomplete. Please update address first";
            exit(0);
        }

        $zipQuery = "SELECT ZIP_CODE FROM ZIP WHERE ZIP_ID=".$addressQueryResult['zip_id'];
        $zipQueryResult = pg_query($zipQuery);
        $zipQueryResult = pg_fetch_assoc($zipQueryResult);
        $zipCode = $zipQueryResult['zip_code'];

        if ( !($zipCode) ){
            echo "Address incomplete. Please update address first";
            exit(0);
        }

        $communityQuery = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$addressQueryResult['community_id'];
        $communityQueryResult = pg_query($communityQuery);
        $communityQueryResult = pg_fetch_assoc($communityQueryResult);

        $communityLegalName = $communityQueryResult['legal_name'];

        $communityMailingAddress = $communityQueryResult['mailing_address'];

        $communityMailingCity = $communityQueryResult['mailing_addr_city'];
        $communityMailingState = $communityQueryResult['mailing_addr_state'];
        $communityMailingZip = $communityQueryResult['mailing_addr_zip'];


        $communityCityQuery = "SELECT CITY_NAME FROM CITY WHERE CITY_ID=".$communityMailingCity;
        $communityCityQuery = pg_query($communityCityQuery);
        $communityCityQuery = pg_fetch_assoc($communityCityQuery);
        $communityCityName = $communityCityQuery['city_name'];

        $communityStateQuery = "SELECT STATE_CODE FROM STATE WHERE STATE_ID=".$communityMailingState;
        $communityStateQueryResult = pg_query($communityStateQuery);
        $communityStateName = pg_fetch_assoc($communityStateQueryResult)['state_code'];

        $communityZipQuery = "SELECT ZIP_CODE FROM ZIP WHERE ZIP_ID=".$communityMailingZip;
        $communityZipQueryResult = pg_query($communityZipQuery);
        $communityZipCode = pg_fetch_assoc($communityZipQueryResult)['zip_code'];


        $handler = fopen('data.tab', 'w');
        fwrite($handler, "1"."\t".$name."\t".$address1." ".$address2."\t".$cityName." ".$stateName." ".$zipCode."\t\t\t1\t".$number."\t".$parseJSON[0]->file_name."\t".$communityLegalName."\t".$communityMailingAddress." ".$communityCityName." ".$communityStateName." ".$communityZipCode."\t\t\t".$communityLegalName);
        fclose($handler);



        $zipFileNameFinal = mt_rand().'.zip';
        $zip = new ZipArchive;
        if ($zip->open($zipFileNameFinal,  ZipArchive::CREATE)) {
            $zip->addFile($parseJSON[0]->file_name, $parseJSON[0]->file_name);
            $zip->addFile("data.tab", "data.tab");
            $zip->close();

                         $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
            $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
            $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

            $url = 'https://content.dropboxapi.com/2/files/upload';
            $pdfFileContent = file_get_contents($zipFileNameFinal);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Content-Type:application/octet-stream','Dropbox-API-Arg: {"path": "/Sent Files/'.$zipFileNameFinal.'","mode": "overwrite","autorename": false,"mute": false}'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $pdfFileContent); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($ch);
            $response = json_decode($response);

            $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'UPLOAD','/Sent Files/".$zipFileNameFinal."','".date('Y-m-d H:i:s')."')";
            pg_query($dropboxInsertQuery);


            if ( isset($response->error_summary) ){
                echo "An error occured. Please try again.";
                exit(0);
            }
            $dbResponse = $response;

            echo $dbResponse;

            $fileContent  = base64_encode($pdfFileContent);

            $url = "http://southdata.us-west-2.elasticbeanstalk.com/TestOrderMailing.aspx?file_id=".$dbResponse->id."&hoaid=".$hoaID."&type=0";

            $req = curl_init();
            curl_setopt($req, CURLOPT_URL,$url);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            if(curl_exec($req) === false)
            {
                $message =  "An error occured. Please try again.";
                echo $message;
                exit(0);
            }
            else 
            {   
                $message = "File uploaded to South Data.";
                echo $message;       
            }       
            $query = "INSERT INTO files_sent(hoa_id,file_tech_id,sent_date,file_name) VALUES(".$hoaID.",'".$dbResponse->id."','".date('Y-m-d H:i:s')."','".$parseJSON[0]->file_name."')";
            pg_query($query);

            unlink($zipFileNameFinal);
        }




        

    }


    }
    




//Deleting created file
unlink($parseJSON[0]->file_name);
?>