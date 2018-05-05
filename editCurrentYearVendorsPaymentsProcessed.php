<?php
	
	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$vendor_id = $_POST['vendor_id'];
	$vendor_name = $_POST['vendor_name'];
	$cypp_year = $_POST['cypp_year'];
	@$m[0] = $_POST['month'][0];
	@$m[1] = $_POST['month'][1];
	@$m[2] = $_POST['month'][2];
	@$m[3] = $_POST['month'][3];
	@$m[4] = $_POST['month'][4];
	@$m[5] = $_POST['month'][5];
	@$m[6] = $_POST['month'][6];
	@$m[7] = $_POST['month'][7];
	@$m[8] = $_POST['month'][8];
	@$m[9] = $_POST['month'][9];
	@$m[10] = $_POST['month'][10];
	@$m[11] = $_POST['month'][11];
	$updated_by = $_SESSION['hoa_user_id'];
	$community_id = $_SESSION['hoa_community_id'];

	$d = date("Y-m-d");

	$m1 = 'f';
	$m2 = 'f';
	$m3 = 'f';
	$m4 = 'f';
	$m5 = 'f';
	$m6 = 'f';
	$m7 = 'f';
	$m8 = 'f';
	$m9 = 'f';
	$m10 = 'f';
	$m11 = 'f';
	$m12 = 'f';

	include 'includes/dbconn.php';

    $query = "SELECT * FROM current_year_vendors_pmt_processed WHERE vendor_id=".$home_id." AND hoa_id=".$hoa_id;   
    $result = pg_query($query);


    if($result)
    {
    	$row = pg_fetch_assoc($result);

    	$db_home_id = $row['home_id'];
		$db_hoa_id = $row['hoa_id'];
		$db_m1 = $row['m1_pmt_processed'];
		$db_m2 = $row['m2_pmt_processed'];
		$db_m3 = $row['m3_pmt_processed'];
		$db_m4 = $row['m4_pmt_processed'];
		$db_m5 = $row['m5_pmt_processed'];
		$db_m6 = $row['m6_pmt_processed'];
		$db_m7 = $row['m7_pmt_processed'];
		$db_m8 = $row['m8_pmt_processed'];
		$db_m9 = $row['m9_pmt_processed'];
		$db_m10 = $row['m10_pmt_processed'];
		$db_m11 = $row['m11_pmt_processed'];
		$db_m12 = $row['m12_pmt_processed'];

		for ($i=0; $i < 12; $i++) 
		{ 
			switch ($m[$i]) {
				case 'January':
					$m1 = 't';
					break;

				case 'February':
					$m2 = 't';
					break;

				case 'March':
					$m3 = 't';
					break;

				case 'April':
					$m4 = 't';
					break;

				case 'May':
					$m5 = 't';
					break;

				case 'June':
					$m6 = 't';
					break;

				case 'July':
					$m7 = 't';
					break;

				case 'August':
					$m8 = 't';
					break;

				case 'September':
					$m9 = 't';
					break;

				case 'October':
					$m10 = 't';
					break;

				case 'November':
					$m11 = 't';
					break;

				case 'December':
					$m12 = 't';
					break;
			}
		}

		echo "<br><br><br><center><h2>Vendor : $vendor_name</h2></center>";

    	if($m1 != $db_m1)
    	{
    		$query = "UPDATE current_year_payments_processed SET m1_pmt_processed='".$m1."', m1_pmt_process_date='".$d."', m1_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>January Payment Processed Updated</center>";
		    }
    	}

    	if($m2 != $db_m2)
    	{
    		$query = "UPDATE current_year_payments_processed SET m2_pmt_processed='".$m2."', m2_pmt_process_date='".$d."', m2_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>February Payment Processed Updated</center>";
		    }
    	}

    	if($m3 != $db_m3)
    	{
    		$query = "UPDATE current_year_payments_processed SET m3_pmt_processed='".$m3."', m3_pmt_process_date='".$d."', m3_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>March Payment Processed Updated</center>";
		    }
    	}

    	if($m4 != $db_m4)
    	{
    		$query = "UPDATE current_year_payments_processed SET m4_pmt_processed='".$m4."', m4_pmt_process_date='".$d."', m4_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>April Payment Processed Updated</center>";
		    }
    	}

    	if($m5 != $db_m5)
    	{
    		$query = "UPDATE current_year_payments_processed SET m5_pmt_processed='".$m5."', m5_pmt_process_date='".$d."', m5_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>May Payment Processed Updated</center>";
		    }
    	}

    	if($m6 != $db_m6)
    	{
    		$query = "UPDATE current_year_payments_processed SET m6_pmt_processed='".$m6."', m6_pmt_process_date='".$d."', m6_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>June Payment Processed Updated</center>";
		    }
    	}

    	if($m7 != $db_m7)
    	{
    		$query = "UPDATE current_year_payments_processed SET m7_pmt_processed='".$m7."', m7_pmt_process_date='".$d."', m7_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>July Payment Processed Updated</center>";
		    }
    	}

    	if($m8 != $db_m8)
    	{
    		$query = "UPDATE current_year_payments_processed SET m8_pmt_processed='".$m8."', m8_pmt_process_date='".$d."', m8_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>August Payment Processed Updated</center>";
		    }
    	}

    	if($m9 != $db_m9)
    	{
    		$query = "UPDATE current_year_payments_processed SET m9_pmt_processed='".$m9."', m9_pmt_process_date='".$d."', m9_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>September Payment Processed Updated</center>";
		    }
    	}

    	if($m10 != $db_m10)
    	{
    		$query = "UPDATE current_year_payments_processed SET m10_pmt_processed='".$m10."', m10_pmt_process_date='".$d."', m10_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>October Payment Processed Updated</center>";
		    }
    	}

    	if($m11 != $db_m11)
    	{
    		$query = "UPDATE current_year_payments_processed SET m11_pmt_processed='".$m11."', m11_pmt_process_date='".$d."', m11_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>November Payment Processed Updated</center>";
		    }
    	}

    	if($m12 != $db_m12)
    	{
    		$query = "UPDATE current_year_payments_processed SET m12_pmt_processed='".$m12."', m12_pmt_process_date='".$d."', m12_pmt_processed_by=".$updated_by." WHERE home_id=".$home_id." AND hoa_id=".$hoa_id;   
		    $result = pg_query($query);


		    if($result)
		    {
		    	echo "<center><br><br>December Payment Processed Updated</center>";
		    }
    	}
    }

    echo "<center><a href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=".$hoa_id."'>Click here</a> if this doesnot redirect automatically in 5 second.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=".$hoa_id."'},1000);</script>";

?>