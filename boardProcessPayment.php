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

        $community_id = $_SESSION['hoa_community_id'];
        $user_id=$_SESSION['hoa_user_id'];

        include 'includes/dbconn.php';

        $result = pg_query("SELECT * FROM board_committee_details WHERE user_id=$user_id AND community_id=$community_id");
        $num_row = pg_num_rows($result);

        if($num_row == 0)
         header("Location: https://hoaboardtime.com/residentDashboard.php");

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
                echo "<li class='header text-center'>

                  <img src='logo.JPG'>

                </li>"; ?>
            
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
             
                <li class="active treeview">

                  <a>

                    <i class='fa fa-dollar'></i> <span>Process Payments</span>
              
                  </a>

                </li>
            
                <li class="treeview">
              
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

        ?>
        
        <section class="content-header">

          <h1><strong>Process Payments</strong></h1>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
              
                <div class="row container-fluid">

                  <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-xl-6 col-md-offset-2 col-lg-offset-3 col-xl-offset-3">

                    <div class="alert alert-info alert-dismissible">
                
                      <center>

                      <form method='POST' action='boardProcessPayment.php'>

                      <?php

                        if( isset($_POST['submit']) )
                        {
                          
                          if( $_POST['show'] == 1 )
                          {

                            echo "View ";

                            echo "<input type='radio' checked name='show' id='show' value='1'> All accounts <input type='radio' name='show' id='show' value='2'> Pending Accounts <br><br> <input class='btn btn-warning' type='submit' name='submit' id='submit' value='View'>"; 

                          }
                          else if( $_POST['show'] == 2 )
                          {

                            echo "View ";

                            echo "<input type='radio' name='show' id='show' value='1'> All accounts <input type='radio' checked name='show' id='show' value='2'> Pending Accounts <br><br> <input class='btn btn-warning' type='submit' name='submit' id='submit' value='View'>"; 

                          }
                        }
                        else
                        {
                          
                          echo "View ";

                          echo "<input type='radio' name='show' id='show' value='1'> All accounts <input type='radio' checked name='show' id='show' value='2'> Pending Accounts <br><br> <input class='btn btn-warning' type='submit' name='submit' id='submit' value='View'>"; 

                        }

                      ?>

                      </form>

                      </center>
                      
                    </div>

                  </div>

                </div>

            </section>

          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Name</th>
                        <th>Address</th>
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Aug</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dec</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        $mo = $month - 0;

                        if( isset($_POST['submit']) )
                        {
                          if( $_POST['show'] == 1 )
                            $result = pg_query("SELECT * FROM current_year_payments_processed WHERE community_id=$community_id AND year=$year");
                          else
                          {
                            $result = pg_query("SELECT * FROM current_year_payments_processed WHERE community_id=$community_id AND year=$year AND m".$mo."_pmt_processed='f'");
                          }
                        }
                        else
                        {
                          $result = pg_query("SELECT * FROM current_year_payments_processed WHERE community_id=$community_id AND year=$year AND m".$mo."_pmt_processed='f'");
                        }

                        while($row = pg_fetch_assoc($result))
                        {

                          $home_id = $row['home_id'];
                          $hoa_id = $row['hoa_id'];
                          $m[1] = $row['m1_pmt_processed'];
                          $m[2] = $row['m2_pmt_processed'];
                          $m[3] = $row['m3_pmt_processed'];
                          $m[4] = $row['m4_pmt_processed'];
                          $m[5] = $row['m5_pmt_processed'];
                          $m[6] = $row['m6_pmt_processed'];
                          $m[7] = $row['m7_pmt_processed'];
                          $m[8] = $row['m8_pmt_processed'];
                          $m[9] = $row['m9_pmt_processed'];
                          $m[10] = $row['m10_pmt_processed'];
                          $m[11] = $row['m11_pmt_processed'];
                          $m[12] = $row['m12_pmt_processed'];

                          $result2 = pg_query("SELECT firstname, lastname FROM hoaid WHERE hoa_id=$hoa_id");
                          $row2 = pg_fetch_assoc($result2);

                          $firstname = $row2['firstname'];
                          $lastname = $row2['lastname'];

                          $result2 = pg_query("SELECT address1 FROM homeid WHERE home_id=$home_id");
                          $row2 = pg_fetch_assoc($result2);
                          $address = $row2['address1'];

                          for ($i = 1; $i <= 12; $i++)
                          {
                            if($m[$i] == 't')
                              $m[$i] = "<center><i class='fa fa-check-square text-success'></i></center>";
                            else
                              $m[$i] = "<center><i class='fa fa-square-o text-orange'></i></center>";
                          }

                          echo "<tr><form method='POST' action='boardProcessPayment2.php'><td><input type='hidden' value='$home_id' name='home_id' id='home_id'><input type='hidden' value='$hoa_id' name='hoa_id' id='hoa_id'><input type='hidden' value='".$firstname." ".$lastname."' name='cus_name' id='cus_name'><input type='hidden' value='$address' name='cus_address' id='cus_address'><button type='submit' class='btn btn-link'>".$firstname." ".$lastname."<br>($hoa_id)</button></td><td><button type='submit' class='btn btn-link'>".$address."<br>($home_id)</button></td><td>".$m[1]."</td><td>".$m[2]."</td><td>".$m[3]."</td><td>".$m[4]."</td><td>".$m[5]."</td><td>".$m[6]."</td><td>".$m[7]."</td><td>".$m[8]."</td><td>".$m[9]."</td><td>".$m[10]."</td><td>".$m[11]."</td><td>".$m[12]."</td></form></tr>";

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Name</th>
                        <th>Address</th>
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Aug</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dec</th>

                      </tr>

                    </tfoot>

                  </table>

                </div>

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
    <script src="dist/js/demo.js"></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50 });
      });
    </script>

  </body>

</html>
