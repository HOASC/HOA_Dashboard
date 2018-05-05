<?php

	include 'includes/dbconn.php';

	$ch = curl_init('https://us12.api.mailchimp.com/3.0/lists/09692e90bd/members');

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: apikey af5b50b9f714f9c2cb81b91281b84218-us12'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            
    $result = curl_exec($ch);
    $json_decode = json_decode($result,TRUE);

    #print_r($json_decode);

    $total = sizeof($json_decode['members']);

    echo "<table border=2>";

    for ($i = 0; $i < $total; $i++) {

    	$row = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE email='".$json_decode['members'][$i]['email_address']."' OR hoa_id=".$json_decode['members'][$i]['merge_fields']['LINKID']));
    	echo "<tr><td>";

    	print_r($json_decode['members'][$i]['id']);

    	echo "</td><td>";

    	print_r($json_decode['members'][$i]['email_address']);

	    echo "</td><td>";

	    print_r($json_decode['members'][$i]['merge_fields']['FNAME']);

	    echo "</td><td>";

	    print_r($json_decode['members'][$i]['merge_fields']['LNAME']);

	    echo "</td><td>";

	    print_r($row['hoa_id']);

	    echo "</td><td>";

	    print_r($row['firstname']);

	    echo "</td><td>";

	    print_r($row['lastname']);

	    echo "</td><td>";

	    print_r($row['email']);

	    echo "</td></tr>";

	}

	echo "</table>";

?>