<?php

	ini_set("session.save_path","/var/www/html/session/");

    session_start();

	$community_id = $_SESSION['hoa_community_id'];

    include 'includes/dbconn.php';
    
	header ( "Content-Type: application/vnd.ms-excel" );
    header ( "Content-disposition: attachment; filename=".$_SESSION['hoa_community_code']."-HOA_&_Home_Info-_".date('m-d-Y H:i:s').".csv" );
    header ( "Content-Type: application/force-download" );
    header ( "Content-Transfer-Encoding: binary" );
    header ( "Pragma: no-cache" );
    header ( "Expires: 0" );

    echo ('"HOA ID","First Name","Last Name","Email","Phone","Home ID","Living In","Balance","Mailing Address","Pay Method","Active Reminders","Recurring Pay"');
	
	echo "\n";

	$i = 0;

	$result = pg_query("SELECT * FROM hoaid WHERE community_id=$community_id ORDER BY hoa_id");

	while($row = pg_fetch_assoc($result))
    {

	    $hoa_id = $row['hoa_id'];
	    $firstname = $row['firstname'];
	    $lastname = $row['lastname'];
	    $email = $row['email'];
	    $phone = $row['cell_no'];
	    $home_id = $row['home_id'];

	    $row1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));

	    $address = $row1['address1'];
	    $living_status = $row1['living_status'];

	    if($living_status)
	    {
	        $mailing_address = $row1['address1'];
	        $mailing_city = $row1['city_id'];
	        $mailing_state = $row1['state_id'];
	        $mailing_zip = $row1['zip_id'];
	    }
	    else
	    {
	       	$row1 = pg_fetch_assoc(pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id"));

	        $mailing_address = $row1['address1'];
	        $mailing_city = $row1['city_id'];
	        $mailing_state = $row1['state_id'];
	        $mailing_zip = $row1['zip_id'];
	    }

        $row1 = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$mailing_city"));
        $mailing_city = $row1['city_name'];

        $row1 = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$mailing_state"));
        $mailing_state = $row1['state_code'];

        $row1 = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$mailing_zip"));
        $mailing_zip = $row1['zip_code'];

        $mailing_address .= $mailing_city;
        $mailing_address .= ", ";
        $mailing_address .= $mailing_state;
        $mailing_address .= " ";
        $mailing_address .= $mailing_zip;

        $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id"));
        $charges = $row1['sum'];

        $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND home_id=$home_id AND hoa_id=$hoa_id"));
        $payments = $row1['sum'];

        $balance = $charges - $payments;

        $row1 = pg_fetch_assoc(pg_query("SELECT * FROM home_pay_method WHERE home_id=$home_id"));
        $home_pay_method = $row1['payment_type_id'];
        $recurring_pay = $row1['recurring_pay'];

        if($recurring_pay == 't')
            $recurring_pay = "TRUE";
        else
            $recurring_pay = "FALSE";

        $row1 = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$home_pay_method"));
        $home_pay_method = $row1['payment_type_name'];

        $reminders = pg_num_rows(pg_query("SELECT * FROM reminders WHERE home_id=$home_id AND hoa_id=$hoa_id"));
                          
        if($reminders == 0)
            $reminders = "FALSE";
        else
            $reminders = "TRUE";

        echo ('"'.$hoa_id.'","'.$firstname.'","'.$lastname.'","'.$email.'","'.$phone.'","'.$home_id.'","'.$address.'","$ '.$balance.'","'.$mailing_address.'","'.$home_pay_method.'","'.$reminders.'","'.$recurring_pay.'"');
		echo "\n";

    }

?>