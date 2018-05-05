<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <?php

      	if(@!$_SESSION['hoa_username'])
      		header("Location: logout.php");

      	$community_id = $_SESSION['hoa_community_id'];
      	$user_id=$_SESSION['hoa_user_id'];

      	include 'includes/dbconn.php';
      	include 'includes/globalvar.php';

      	$result = pg_query("SELECT * FROM board_committee_details WHERE user_id=$user_id AND community_id=$community_id");
    		$num_row = pg_num_rows($result);

    		if($num_row == 0)
    			header("Location: residentDashboard.php");

    ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <title><?php echo $_SESSION['hoa_community_name']; ?></title>
    
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

      	<header class="main-header">
        
        	<a class="logo">
          
          		<span class="logo-mini"><?php echo $_SESSION['hoa_community_code']; ?></span>
          
          		<span class="logo-lg"><?php echo $_SESSION['hoa_community_name']; ?></span>

        	</a>
        
        	<nav class="navbar navbar-static-top">
          
          		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            		
            		<span class="sr-only">Toggle navigation</span>

          		</a>

	          	<div class="navbar-custom-menu">
	            
	            	<ul class="nav navbar-nav">

		          		<li class="dropdown user user-menu">
	              
		            		<a href="https://hoaboardtime.com/residentDashboard.php">Resident Dashboard</a>

		          		</li>

		          		<li class="dropdown user user-menu">

		            		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		              
		              			<i class="fa fa-user"></i> <span class="hidden-xs"><?php echo $_SESSION['hoa_username']; ?></span>

		            		</a>

			            	<ul class="dropdown-menu">
			              
			              		<li class="user-header">
			                
			                		<i class="fa fa-user fa-5x"></i>

			                		<p>
			                  
					                  	<?php echo $_SESSION['hoa_username']; ?>

					                  	<br>

					                  	<small><?php echo $_SESSION['hoa_address']; ?></small>

					                  	<a href="https://hoaboardtime.com/logout.php" class="btn btn-warning">Log Out</a>

					                	<br>

					                </p>

			              		</li>

			            	</ul>

		          		</li>

	            	</ul>

	          	</div>

        	</nav>

      	</header>
      
      	<aside class="main-sidebar">
        
          <section class="sidebar">
          
              <ul class="sidebar-menu">
            
                <?php if($community_id == 2)
                echo "<li class='header text-center'>".$communtiy_logo."</li>"; ?>
            
                <li class="header text-center"> Quick Links </li>

                <li class="treeview">
              
                  <a href='https://hoaboardtime.com/boardDashboard.php'>
                
                    <i class="fa fa-dashboard"></i> <span>Board Dashboard</span>

                  </a>

                </li>
            
                <li class="treeview">
              
                  <a href="#">

                    <i class="glyphicon glyphicon-hdd"></i> <span>Document Management</span>

                    <span class="pull-right-container">
                        
                      <i class="fa fa-angle-left pull-right"></i>

                    </span>

                  </a>

                  <ul class="treeview-menu">
                
                    <li><a><i class="fa fa-male text-green"></i> Member Documents</a></li>
                    <li><a><i class="fa fa-wrench text-red"></i> Vendor Documents</a></li>

                  </ul>

                </li>
             
                <li class="treeview">

                  <a href='https://hoaboardtime.com/boardProcessPayment.php'>

                    <i class='fa fa-dollar'></i> <span>Process Payments</span>
              
                  </a>

                </li>
            
                <li class="active treeview">
              
                  <a href='https://hoaboardtime.com/boardSetReminder.php'>

                    <i class='fa fa-bell'></i> <span>Create Reminder</span>

                  </a>

                </li>

                <li class="header text-center"> Other Links </li>

                <!-- Board -->
                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardCharges.php">

                    <i class="fa fa-users text-blue"></i> <span>Late Fee / Write Off </span>

                  </a>

                </li>

                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardCommunityDisclosures.php">

                    <i class="fa fa-users text-blue"></i> <span>Community Disclosures</span>

                  </a>

                </li>

                <li class='treeview'>

                  <a>

                    <i class="fa fa-users text-blue"></i> <span>Digital Board Room</span>

                  </a>

                </li>

                <li class='treeview'>
                  
                  <a href="https://hoaboardtime.com/boardPreviousMonthsPayments.php">

                    <i class="fa fa-users text-blue"></i> <span>Previous Months Payments</span>

                  </a>

                </li>

                <li class="treeview">
                  
                  <a href="https://hoaboardtime.com/boardSurveyDetails.php">

                    <i class="fa fa-users text-blue"></i> <span>Survey Details</span>

                  </a>

                </li>

                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardCommunityExpenditureSummary.php">

                    <i class="fa fa-users text-blue"></i> <span>YTD Expenses</span>

                  </a>

                </li>

                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardCommunityDeposits.php">

                    <i class="fa fa-users text-blue"></i> <span>YTD Income</span>

                  </a>

                </li>

                <!-- Member -->
                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardMailingList.php">

                    <i class="fa fa-street-view text-green"></i> <span>Community Mailing List</span>

                  </a>

                </li>
                      
                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardCustomerBalance.php">

                    <i class="fa fa-street-view text-green"></i> <span>Customer Balance</span>

                  </a>

                </li>
                
                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardHOAHomeInfo.php">

                    <i class="fa fa-street-view text-green"></i> <span>HOA &amp; Home Info</span>

                  </a>

                </li>

                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardUserDashboard.php">

                    <i class="fa fa-street-view text-green"></i> <span>User Dashbord</span>

                  </a>

                </li>
                
                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardViewReminders.php">

                    <i class="fa fa-street-view text-green"></i> <span>View Reminders</span>

                  </a>

                </li>

                <!-- Vendor -->
                <li class='treeview'>

                  <a href="https://hoaboardtime.com/boardVendorDashboard.php">

                    <i class="fa fa-wrench text-red"></i> <span>Vendor Dashboard</span>

                  </a>

                </li>

              </ul>

          </section>

        </aside>

      	<div class="content-wrapper">

	        <?php

	        	$year = date("Y");
	        	$month = date("m");
	        	$end_date = date("t");

	          	$name = $_REQUEST['name'];
	          	$living_in = $_REQUEST['living_in'];
	          	$hoa_id = $_REQUEST['hoa_id'];
	          	$home_id = $_REQUEST['home_id'];
	          	$email = $_REQUEST['email'];

	        ?>
	        
	        <section class="content-header">

	          <h1><strong>Create Reminder</strong></h1>

	        </section>

	        <section class="content">
	          
	          <div class="row">

	          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

	              	<div class="box">
	                
		                <form method="POST" action="https://hoaboardtime.com/boardSetReminder3.php">

			                <div class="box-body">

			                	<br>
			                  
			            		<div class="row">

				            		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center">

				            			<h5>Name : <strong><?php echo $name; ?></strong></h5>
				            			<input type="hidden" name="hoa_id" id="hoa_id" value="<?php echo $hoa_id; ?>">

				            		</div>

				            		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center">

				            			<h5>Living In : <strong><?php echo $living_in; ?></strong></h5>
				            			<input type="hidden" name="home_id" id="home_id" value="<?php echo $home_id; ?>">

				            		</div>

			            		</div>

			            		<br>

			            		<div class="row container-fluid">

			            			<label><h5><strong>Reminder Type : </strong></h5></label>

			            		</div>

			            		<br>

			            		<div class="row">

			            			<?php

			            				$result = pg_query("SELECT * FROM reminder_type");

			            				$i = 0;

			            				while($row = pg_fetch_assoc($result))
			            				{

			            					$id = $row['id'];
			            					$reminder_type = $row['reminder_type'];

			            					echo "<div class='col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6'><input type='radio' "; if($i == 0) {echo "checked"; $i++; } echo " name='reminder_type' id='reminder_type' value='$id'> $reminder_type</div>";

			            				}

			            			?>
			            			
			            		</div>

			            		<br>

			            		<div class='row container-fluid'>
				            		
				            		<div class='col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12'>
					            		
					            		<div class="row container-fluid">

					            			<label><h5><strong>Open Date : </strong></h5></label>

					            		</div>

					            		<div class="row container-fluid">

	                						<div class="input-group date">
	                  							
	                  							<input type="date" placeholder="yyyy-mm-dd" disabled value="<?php echo date('Y-m-d'); ?>" required>
	                  							<input type="hidden" value="<?php echo date('Y-m-d'); ?>" name='open_date' value='open_date'>
	                						
	                						</div>

	              						</div>

				            		</div>

				            		<div class='col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12'>
					            		
					            		<div class="row container-fluid">

					            			<label><h5><strong>Due Date : </strong></h5></label>

					            		</div>

					            		<div class="row container-fluid">

	                						<div class="input-group date">

	                  							<?php

	                  							$date = date("Y-m-d");

												$i = 7;
												
	                  							?>
	                  							
	                  							<input type="date" placeholder="yyyy-mm-dd" disabled value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
	                  							<input type="hidden"  value="<?php echo date('Y-m-d', strtotime("+7 days")); ?>" name='due_date' id='due_date'>
	                  							<input type="hidden"  value="<?php echo date('Y-m-d'); ?>" name='update_date' id='update_date'>
	                						
	                						</div>

	              						</div>

				            		</div>

				            		<div class='col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12'>
					            		
					            		<div class="row container-fluid">

					            			<label><h5><strong>Assigned To : </strong></h5></label>

					            		</div>

					            		<div class="row container-fluid">

	                						<div class="input-group date">

	                  							<?php

	                  								$row = pg_fetch_assoc(pg_query("SELECT id FROM usr WHERE email='$email'"));
	                  								$assigned_to = $row['id'];
												
	                  							?>
	                  							
	                  							<strong><?php echo $assigned_to; ?></strong>
	                  							<input type='hidden' value="<?php echo $assigned_to; ?>" name='assigned_to' id='assigned_to'>
	                						
	                						</div>

	              						</div>

				            		</div>

				            		<div class='col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 container-fluid'>
					            		
					            		<div class="row container-fluid">

					            			<label><h5><strong>Vendor Assigned : </strong></h5></label>

					            		</div>

					            		<div class="row container-fluid">

	                						<div class="input-group container-fluid">

	                  							<select class='form-control' name='vendor_assigned' id='vendor_assigned'>

		                  							<option selected value=''>NONE</option>

		                  							<?php

		                  								$result = pg_query("SELECT * FROM vendor_master WHERE community_id=$community_id");

		                  								while($row = pg_fetch_assoc($result))
		                  								{
		                  									$vendor_id = $row['vendor_id'];
		                  									$vendor_name = $row['vendor_name'];

		                  									echo "<option value='$vendor_id'>$vendor_name</option>";

		                  								}
													
		                  							?>
	                  							
	                  							</select>
	                						
	                						</div>

	              						</div>

				            		</div>

			            		</div>

			            		<br>

			            		<div class="row container-fluid">

			            			<label><h5><strong>Comment : </strong></h5></label>

			            		</div>

			            		<br>

			            		<div class="row container-fluid">

			            			<textarea id='comment' name='comment' class='form-control' required></textarea>
			            			
			            		</div>

			            		<br><br>

			            		<div class="row">

			            			<center><button type="submit" class="btn btn-info btn-xs">Set Reminder</button></center>

			            		</div>

			            		<br>

			                </div>

		                </form>

	              	</div>

	            </section>

	          </div>

	        </section>

      	</div>

      	<footer class="main-footer">

	        <div class="pull-right hidden-xs"></div>
	        
	        <strong>Copyright &copy; <?php echo date('Y'); ?> <a target='_blank' href="<?php echo $_SESSION['hoa_community_website_url']; ?>"><?php echo $_SESSION['hoa_community_name']; ?></a>.</strong> All rights reserved.

      	</footer>

      	<div class="control-sidebar-bg"></div>

    </div>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="dist/js/demo.js"></script>

    <script>
      	$(function () {
        	$("#example1").DataTable({ "pageLength": 50, "order": [[1, "asc"]] });
      	});

      	$('#datepicker').datepicker({
      		autoclose: true
    	});

    	$('#datepicker1').datepicker({
      		autoclose: true
    	});
    </script>

  </body>

</html>