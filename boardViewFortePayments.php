<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

	<head>

		<?php
			
			ini_set('max_execution_time', 180);

			if(!$_SESSION['hoa_email'])
				header("Location: logout.php");

            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 86400)) {
                header("Location: logout.php");
            }
            $_SESSION['LAST_ACTIVITY'] = time();

            $community_id = $_SESSION['hoa_community_id'];

			include 'includes/dbconn.php';

            $result = pg_query("SELECT * FROM board_committee_details WHERE user_id=".$_SESSION['hoa_user_id']);
            $num_row = pg_num_rows($result);

            if($num_row == 0)
                header("Location: residentDashboard.php");
            
		?>

		<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Board Dashboard</title>

		<!-- Bootstrap -->
    	<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <link rel='stylesheet' href='https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css'>

	</head>
	<body>

		<center><h3><strong><?php echo $_GET['name']; ?></strong></h3></center>

		<br><br>

		<center><div class="row container">
			<table id='example' class="table container-fluid table-responsive">
				
				<thead>
					
					<th>Date</th>
					<th>Customer ID</th>
					<th>Document Number</th>
					<th>Status</th>
					<th>Amount</th>

				</thead>

				<tbody>

					<?php
						$hoa_id = $_GET['hoa_id'];

						$community_id = $_SESSION['hoa_community_id'];

						$ch = curl_init();
					    $header = array();
					    $header[] = 'Content-Type: application/json';
						if($community_id == 2)
					    {
					        $header[] = "X-Forte-Auth-Organization-Id:org_332536";
					        $header[] = "Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU=";
					                                                
					        curl_setopt($ch, CURLOPT_URL, "https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions?filter=customer_id+eq+'".$hoa_id."'");
					                                                
					    }

					    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					    $result = curl_exec($ch);
					    $obj = json_decode($result);

					    foreach ($obj->results as $key) 
					    {
					        
					        if($key->customer_id == $hoa_id)
					        	echo "<tr><td>".date('m-d-Y', strtotime($key->received_date))."</td><td>".$key->customer_id."</td><td>".$key->authorization_code."</td><td>".$key->status."</td><td>$ ".$key->authorization_amount."</td></tr>";

					    }
					                                                
					    curl_close($ch);
					?>

				</tbody>

			</table>
		</div></center>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="js/bootstrap.min.js"></script>
        <script src='//code.jquery.com/jquery-1.12.4.js'></script>
        <script src='https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js'></script>
        <script src='https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js'></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#example').DataTable({
                    "pageLength": 50
                });
            } );
        </script>

	</body>

</html>