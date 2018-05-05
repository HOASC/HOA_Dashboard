<?php

			date_default_timezone_set('America/Los_Angeles');
			$data = file_get_contents('php://input');
			$parsJSON = json_decode($data);
			
            include 'includes/dbconn.php';
			include 'includes/api_keys.php';
            include 'includes/globalvar.php';

            $hoaID = $parsJSON[0]->hoa_id;

            $fileID = $parsJSON[0]->pdf_id;

            $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
            $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
            $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

            //Download file from dropbox

            $url = 'https://content.dropboxapi.com/2/files/download';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Dropbox-API-Arg: {"path": "'.$fileID.'"}'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($ch);
            
            $response23 = json_decode($response);

            if ( ($response23->error_summary)){
                echo "An error occured.";
            }

            else {

                $dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(401,'DOWNLOAD','".$fileID."','".date('Y-m-d H:i:s')."')";
                if ( !pg_query($dropboxInsertQuery) ){
                        // print_r("Failed to insert to dropbox_stats");
                        // print_r(nl2br("\n\n"));
                } 




                $fileData = base64_encode($response);


            


                $query = "SELECT * FROM HOAID WHERE HOA_ID=".$hoaID;
                $queryResult = pg_query($query);

                $row = pg_fetch_assoc($queryResult);

                $toEmail = $row['email'];

                $name = $row['firstname'].' '.$row['lastname'];

                if ( !(isset($toEmail)) ){
                    echo "An error occured.";
                    exit(0);
                }

                $communityID = $row['community_id'];

                $cquery  = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$communityID;

                $cqueryResult = pg_query($cquery);

                $row23 = pg_fetch_assoc($cqueryResult);

                $hquery = "SELECT * FROM HOMEID WHERE HOME_ID=".$row['home_id'] ;

                $hqueryResult = pg_query($hquery);

                $hrow = pg_fetch_assoc($hqueryResult);




                if ( $communityID == 2 ){
                    $communityLogo = "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";

                    $subject  = "Inspection Notice  - ".$hrow['address1'];

                    $mailingData = array("key" => $m_api_key_3, "message" => array("html" => "<center><img src=\"cid:".$mandrill_cid."\" alt=\"Community Logo\"></center><br><b>Hello ".$row['firstname'].' '.$row['lastname'].", </b><br><br>Inspection deficiency, please address this issue ASAP.<br><br>Thanks,<br>".$row23['legal_name'],"subject" => $subject,"from_email" => $row23['email'],"from_name" => $row23['legal_name'],"to" => array(array("email"=>$toEmail,"name"=>$name)),"improtant"=>"true","track_opens" => "true","track_clicks" => "true","attachments" => array(array("type" => "application/pdf","name" => "inspection_notice.pdf","content" => $fileData)),"images"=>array( array("type" => "image/jpg","name" => $mandrill_name,"content" => $communityLogo) ),"send_at"=>"2000-01-01 00:00:00")); 

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://mandrillapp.com/api/1.0/messages/send.json");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mailingData));
                    curl_setopt($ch, CURLOPT_POST, 1);
                    $headers = array();
                    $headers[] = "Content-Type: application/x-www-form-urlencoded";
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $result = curl_exec($ch);

                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                            exit(0);
                        }
                        curl_close ($ch);
                        $result  = json_decode($result);
                        if ( $result[0]->status == 'error'){
                                echo "An error occured.Mandrill";
                        }
                        else if ( ($result[0]->status == 'sent' ) || ($result[0]->status == 'queued') ){
                            $message  = "Mail sent successfully";
                            $query  = "SELECT HOME_ID,COMMUNITY_ID FROM HOAID WHERE HOA_ID = ".$_GET['hoaid'];
                            $queryResult = pg_query($query);
                            $row = pg_fetch_assoc($queryResult);
                            $homeID = $row['home_id'];
                            $query = "INSERT INTO community_emails_sent(from_email,to_email,email_subject,api_mail_id,sent_date,status,community_id,update_date,updated_by,hoa_id,message_type,document_id) VALUES('".$row23['email']."','".$toEmail."','Inspection Notice - ".$hrow['address1']."','".$result[0]->_id."','".date('Y-m-d H:i:s')."','".$result[0]->status."',".$communityID.",'".date('Y-m-d H:i:s')."',401,".$hoaID.",2,'".$fileID."')";
                            pg_query($query);
                            echo "Email sent";
                        }
                }



            }



            // $req = curl_init();
            // curl_setopt($req, CURLOPT_URL,$url);
            // curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            // if(curl_exec($req) === false)
            // {
            //     $message =  "An error occured.";
            //     echo $message;
            //     exit(0);
            // }
            // else 
            // {   
            //     $message = "File uploaded to South Data.";
            //     echo $message;       
            // }       
            // $query = "INSERT INTO files_sent(hoa_id,file_tech_id,sent_date,file_name) VALUES(".$parsJSON[0]->hoa_id.",'".$parsJSON[0]->zip_id."','".date('Y-m-d H:i:s')."','Inspection Notice')";
            // pg_query($query);
?>