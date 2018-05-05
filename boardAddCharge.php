<?php

    ini_set("session.save_path","/var/www/html/session/");

    session_start();
            
?>

<!DOCTYPE html>
<html>

	<head>
		<?php
			
            if(@!$_SESSION['hoa_username'])
                header("Location: https://hoaboardtime.com/logout.php");

            $result = pg_query("SELECT * FROM board_committee_details WHERE user_id=$user_id AND community_id=$community_id");
            $num_row = pg_num_rows($result);

            if($num_row == 0)
                header("Location: https://hoaboardtime.com/residentDashboard.php");

			$community_id = $_SESSION['hoa_community_id'];

            include 'includes/dbconn.php';
            
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

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	</head>
	<body>

		<script>
            $(document).ready(function(){
                $('[data-toggle="popover"]').popover();   
            });
        </script>

		<div class="row container-fluid">
			
            <div class="row container-fluid">
	            
                <div class="col-xl-offset-1 col-lg-offset-1 col-xl-10 col-lg-10 col-md-12 col-xs-12">            
    	            
                    <?php
                        $assessment_rule_type_id = $_POST['assement_rule_type_id'];
                        $amount = $_POST['amount'];
                        $assessment_date = $_POST['assessment_date'];
                        $assessment_month = $_POST['assessment_month'];
                        $assessment_year = $_POST['assessment_year'];
                        $hoa_id = $_POST['hoa_id'];

                        $result = pg_query("SELECT home_id FROM hoaid WHERE hoa_id=".$hoa_id);
                        $row = pg_fetch_assoc($result);

                        $home_id = $row['home_id'];

                        if($assessment_rule_type_id == 9)
                            $amount = 0-$amount;

                        $result = pg_query("INSERT INTO current_charges (home_id, hoa_id, amount, assessment_rule_type_id, assessment_year, assessment_month, assessment_date, community_id) VALUES($home_id, $hoa_id, $amount, $assessment_rule_type_id, $assessment_year, $assessment_month, '$assessment_date', $community_id)");
                        if($result)
                            echo "<center><h4>Assessment charge added successfully</h4></center>";
                        else
                            echo "<center><h4>Some error occured. Please try again later.</h4>";

                        echo "<center><a href='https://hoaboardtime.com/boardCharges.php'>Click here</a> if this page doenot redirect automatically in 1 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardCharges.php'},1000);</script>";
                    ?>
                    

                </div>
                
	        </div>

		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="js/bootstrap.min.js"></script>
        <script src='//code.jquery.com/jquery-1.12.4.js'></script>

	</body>

</html>