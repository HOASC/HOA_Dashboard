<?php

			date_default_timezone_set('America/Los_Angeles');
			$data = file_get_contents('php://input');
			$parsJSON = json_decode($data);
			
            include 'includes/dbconn.php';

			$url = "http://southdata.us-west-2.elasticbeanstalk.com/TestOrderMailing.aspx?file_id=".$parsJSON[0]->zip_id."&hoaid=".$parsJSON[0]->hoa_id."&type=0";

            $req = curl_init();
            curl_setopt($req, CURLOPT_URL,$url);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            if(curl_exec($req) === false)
            {
                $message =  "An error occured.";
                echo $message;
                exit(0);
            }
            else 
            {   
                $message = "File uploaded to South Data.";
                echo $message;       
            }       
            $query = "INSERT INTO files_sent(hoa_id,file_tech_id,sent_date,file_name) VALUES(".$parsJSON[0]->hoa_id.",'".$parsJSON[0]->zip_id."','".date('Y-m-d H:i:s')."','Inspection Notice')";
            pg_query($query);
?>