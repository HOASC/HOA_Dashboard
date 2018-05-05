<!DOCTYPE html>

<html lang='en'>

	<head>

		<?php

			include 'includes/dbconn.php';

			$community_id = 2;
			$today = date('Y-m-d');

		?>

		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	    
	    <title>Stoneridge Square Association</title>
	    
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
	    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
	    <link rel="stylesheet" href="plugins/morris/morris.css">
	    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
	    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
	    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
	    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	   
	    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

	</head>

	<body>

		<!-- Layout-->
		<div class='layout'>

			<!-- Wrapper-->
			<div class='wrapper'>

				<br><br><br><br><br>

				<?php

					echo "

						<section class='module module-gray p-b-0'>

							<div class='container'>
								
								<form method='POST' class='col-xl-4 col-lg-4 col-md-6 col-sm-8 col-xs-8 col-xl-offset-4 col-lg-offset-4 col-md-offset-3 col-sm-offset-2 col-xs-offset-2' action='https://hoaboardtime.com/paymentPage2.php'>

									<label><strong>Select User</strong></label>
									<input type='number' name='id' id='id' required placeholder='Enter HOA Account Number' class='form-control'>

									<br><br>

									<center><button name='submit' class='btn btn-success btn-xs' id='submit' type='submit'>Make Payment</button></center>

									<br><br>

								</form>

							</div>

						</section>

					";

				?>

				<a class='scroll-top' href='#top'><i class='fa fa-angle-up'></i></a>

			</div>
			<!-- Wrapper end-->

		</div>
		<!-- Layout end-->

		<!-- Scripts-->
		<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	    <script>
	      $.widget.bridge('uibutton', $.ui.button);
	    </script>
	    <script src="bootstrap/js/bootstrap.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	    <script src="plugins/morris/morris.min.js"></script>
	    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
	    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	    <script src="plugins/knob/jquery.knob.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
	    <script src="plugins/daterangepicker/daterangepicker.js"></script>
	    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
	    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
	    <script src="plugins/fastclick/fastclick.js"></script>
	    <script src="dist/js/app.min.js"></script>
	    <script src="dist/js/pages/dashboard.js"></script>
	    <script src="dist/js/demo.js"></script>

	</body>

</html>