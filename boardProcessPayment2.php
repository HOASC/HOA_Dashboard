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
             
                <li class="active treeview">

                  <a href='https://hoaboardtime.com/boardProcessPayment.php'>

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

          $home_id = $_POST['home_id'];
          $hoa_id = $_POST['hoa_id'];
          $cus_name = $_POST['cus_name'];
          $cus_address = $_POST['cus_address'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM community_info WHERE community_id=$community_id"));

          $city = $row['payment_city'];
          $c_name = $row['legal_name'];
          $pobox = $row['remit_payment_address'];
          $state = $row['payment_addr_state'];
          $zip = $row['payment_addr_zip'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$state"));
          $state = $row['state_code'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$city"));
          $city = $row['city_name'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$zip"));
          $zip = $row['zip_code'];

          $result = pg_query("SELECT * FROM current_charges WHERE home_id=".$home_id." ORDER BY assessment_date DESC LIMIT 1");

          $row = pg_fetch_assoc($result);
          $adate = $row['assessment_date'];

          $result = pg_query("SELECT * FROM homeid WHERE home_id=$home_id");
          
          $row = pg_fetch_assoc($result);

          $living_status = $row['living_status'];

          if($living_status == 't')
          {
            $cus_addr = $row['address1'];
            $cus_state = $row['state_id'];
            $cus_city = $row['city_id'];
            $cus_zip = $row['zip_id'];
          }
          else
          {
            
            $result = pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id");

            $row = pg_fetch_assoc($result);

            $cus_addr = $row['address1'];
            $cus_state = $row['state_id'];
            $cus_city = $row['city_id'];
            $cus_zip = $row['zip_id'];
          
          }

          $row = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$cus_state"));
          $cus_state = $row['state_code'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$cus_city"));
          $cus_city = $row['city_name'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$cus_zip"));
          $cus_zip = $row['zip_code'];

          $row = pg_fetch_assoc(pg_query("SELECT amount FROM assessment_amounts WHERE community_id=$community_id"));
          $assessment_amount = $row['amount'];

        ?>
        
        <section class="content-header">

          <h1><strong>Process Payment</strong><small> - <?php echo $cus_name.", ".$cus_address; ?></small></h1>

        </section>

        <section class="content">
          
          <div class="row">

          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-header">

                  <div class="row">
                  
                    <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6'>
                      
                      From : 

                      <address>
                        
                        <?php echo "<strong>".$c_name."</strong><br>".$pobox."<br>".$city.", ".$state." ".$zip; ?>

                      </address>

                    </div>

                    <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6 text-right'>
                      
                      <span><strong>Invoice No : </strong><?php echo $community_id."-".$home_id."-".$hoa_id ?>-<script type='text/javascript'>document.write(new Date().getFullYear())</script></span><br>
                      <span><strong>Invoice Date : </strong><?php echo date("m-d-y", strtotime($adate)); ?></span><br>
                      <span><strong>Due Date : </strong><?php if(date("m", strtotime($adate)) == date("m"))$month = date("m"); else if(date("m", strtotime($adate)) < date("m")) $month = date("m")-1; else if(date("m", strtotime($adate)) > date("m")) $month = date("m")+1; echo $month."-15-".date("y"); ?></span>
                      
                    </div>

                  </div>

                  <div class="row">

                    <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6'>
                      
                      To : 

                      <address>
                        
                        <?php echo "<strong>".$cus_name."</strong><br>".$cus_addr."<br>".$cus_city.", ".$cus_state." ".$cus_zip; ?>

                      </address>

                    </div>

                  </div>

                </div>

              </div>

            </section>

          </div>

          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">

                <div class="box-body table-responsive">

                  <table class="table table-responsive table-bordered table-striped">
                    
                    <thead>
                      
                      <th>Month</th>
                      <th>Document ID</th>
                      <th>Description</th>
                      <th>Charge</th>
                      <th>Payment</th>
                      <th>Total</th>

                    </thead>

                    <tbody>
                      
                      <?php

                        for($m = 1; $m <= 12; $m++)
                        {

                          $last_date = date("Y-m-t", strtotime("$year-$m-1"));
                          
                          $charges_results = pg_query("SELECT * FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id AND assessment_date>='$year-$m-1' AND assessment_date<='$last_date' ORDER BY assessment_date");

                          $payments_results = pg_query("SELECT * FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND payment_status_id=1 AND process_date>='$year-$m-1' AND process_date<='$last_date' ORDER BY process_date");

                          $month_charge = 0.0;

                          while($charges_row = pg_fetch_assoc($charges_results))
                          {

                            $month_charge += $charges_row['amount'];
                            $tdate = $charges_row['assessment_date'];
                            $desc = $charges_row['assessment_rule_type_id'];

                            $r = pg_fetch_assoc(pg_query("SELECT * FROM assessment_rule_type WHERE assessment_rule_type_id=$desc"));
                            $desc = $r['name'];

                            echo "<tr><td>".date('F', strtotime($tdate))."</td><td>".$charges_row['id']."-".$charges_row['assessment_rule_type_id']."</td><td>".date("m-d-y", strtotime($tdate))."|".$desc."</td><td>$ ".$charges_row['amount']."</td><td></td><td>$ ".$month_charge."</td></tr>";

                          }

                          $month_payment = 0.0;

                          while($payments_row = pg_fetch_assoc($payments_results))
                          {

                            $month_payment += $payments_row['amount'];
                            $tdate = $payments_row['process_date'];

                            echo "<tr><td>".date('F', strtotime($tdate))."</td><td>".$payments_row['id']."-".$payments_row['payment_type_id']."</td><td>".date("m-d-y", strtotime($tdate))."|"."Payment Received # ".$payments_row['document_num']."</td><td></td><td>$ ".$payments_row['amount']."</td><td>$ ".$month_payment."</td></tr>";

                          }

                        }

                      ?>

                    </tbody>

                  </table>

                </div>

              </div>

            </section>

          </div>

          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">

                <div class="box-body table-responsive">

                  <table class="table table-responsive table-bordered table-striped">
                    
                    <thead>
                      
                      <th>Total Charges</th>
                      <th>Total Payments</th>
                      <th>Total Balance</th>

                    </thead>

                    <tbody>
                      
                      <tr>
                        
                        <td><?php $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id")); $total_charges = $row['sum']; echo "$ ".$total_charges; ?></td>

                        <td><?php $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND payment_status_id=1")); $total_payments = $row['sum']; if($total_payments == "") $total_payments = 0.0; echo "$ ".$total_payments; ?></td>

                        <td><?php $total = $total_charges - $total_payments; echo "$ ".$total; ?></td>

                      </tr>

                    </tbody>

                  </table>

                </div>

              </div>

            </section>

          </div>

          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">

                <div class="box-body table-responsive">

                  <center><h4><strong>Current Year Payments Processed</strong></h4></center>

                  <form method='POST' action='https://hoaboardtime.com/boardUpdateCurrentYearPaymentsProcessed.php'>

                    <table class="table table-responsive table-bordered table-striped">
                      
                      <thead>
                        
                        <th>January</th>
                        <th>February</th>
                        <th>March</th>
                        <th>April</th>
                        <th>May</th>
                        <th>June</th>
                        <th>July</th>
                        <th>August</th>
                        <th>September</th>
                        <th>October</th>
                        <th>November</th>
                        <th>December</th>

                      </thead>

                      <tbody>
                        
                         <?php

                          $query = "SELECT * FROM current_year_payments_processed WHERE home_id=".$home_id." AND year=$year";   
                          $result = pg_query($query);

                          while ($row=pg_fetch_assoc($result)) 
                          {

                            $home_id = $row['home_id'];
                            $hoa_id = $row['hoa_id'];
                            $m1 = $row['m1_pmt_processed'];
                            $m2 = $row['m2_pmt_processed'];
                            $m3 = $row['m3_pmt_processed'];
                            $m4 = $row['m4_pmt_processed'];
                            $m5 = $row['m5_pmt_processed'];
                            $m6 = $row['m6_pmt_processed'];
                            $m7 = $row['m7_pmt_processed'];
                            $m8 = $row['m8_pmt_processed'];
                            $m9 = $row['m9_pmt_processed'];
                            $m10 = $row['m10_pmt_processed'];
                            $m11 = $row['m11_pmt_processed'];
                            $m12 = $row['m12_pmt_processed'];

                            echo "<tr><td><input type='checkbox' value='January' name='month[]' id='month'"; if($m1 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='February' name='month[]' id='month'"; if($m2 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='March' name='month[]' id='month'"; if($m3 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='April' name='month[]' id='month'"; if($m4 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='May' name='month[]' id='month'"; if($m5 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='June' name='month[]' id='month'"; if($m6 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='July' name='month[]' id='month'"; if($m7 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='August' name='month[]' id='month'"; if($m8 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='September' name='month[]' id='month'"; if($m9 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='October' name='month[]' id='month'"; if($m10 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='November' name='month[]' id='month'"; if($m11 == 't') echo " checked"; echo "></td><td><input type='checkbox' value='December' name='month[]' id='month'"; if($m12 == 't') echo " checked"; echo "></td><input type='hidden' name='updated_by' id='updated_by' value='".$_SESSION['hoa_user_id']."' required></tr>";

                            echo "<input type='hidden' name='home_id' id='home_id' value='".$home_id."' required><input type='hidden' name='hoa_id' id='hoa_id' value='".$hoa_id."' required>";

                          }
                                                                          
                        ?>

                      </tbody>

                    </table>

                    <center><button type='submit' class="btn btn-success btn-sm">Update</button></center>

                  </form>

                </div>

              </div>

            </section>

          </div>

          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">

                <div class="box-body">

                  <center><h4><strong>Process Payment</strong></h4></center>

                  <form method='POST' class="col-xl-offset-2 col-lg-offset-2 col-md-offset-1 col-xl-8 col-lg-8 col-md-10 col-xs-12" action='https://hoaboardtime.com/boardProcessPayment3.php'>

                    <div class="row form-group">

                      <label class='col-xl-12 col-lg-12 col-md-12 col-xs-12' for='ptype'>Payment Type</label>

                      <br>

                      <?php

                        $result1 = pg_query("SELECT id FROM current_payments ORDER BY id DESC LIMIT 1");
                        $row = pg_fetch_assoc($result1);
                                       
                        $id = $row['id'];
                        $id++;

                        echo "<input type='text' name='id' id='id' value='".$id."' hidden required>
                              <input type='text' name='hoa_id' id='hoa_id' value='".$hoa_id."' hidden required>
                              <input type='text' name='home_id' id='home_id' value='".$home_id."' hidden required>
                              <input type='text' name='payment_id' id='payment_id' value='".$hoa_id."".$home_id."' hidden required>";

                        $result1 = pg_query("SELECT payment_type_id FROM home_pay_method WHERE home_id=$home_id");
                        $row = pg_fetch_assoc($result1);
                        $pay = $row['payment_type_id'];

                        $result1 = pg_query("SELECT payment_type_id, payment_type_name FROM payment_type ORDER BY payment_type_id");
                                                    
                        while($row = pg_fetch_assoc($result1))
                        {
                          $payment_type_id = $row['payment_type_id'];
                          $payment_type_name = $row['payment_type_name'];

                          if($pay == $payment_type_id)
                            echo "<div class='col-lg-3 col-xl-3 col-md-4 col-xs-6'><input type='radio' name='ptype' id='ptype' value='".$payment_type_id."' checked> ". $payment_type_name."</div>"; 
                          else
                            echo "<div class='col-lg-3 col-xl-3 col-md-4 col-xs-6'><input type='radio' name='ptype' id='ptype' value='".$payment_type_id."' required> ". $payment_type_name."</div>";
                        }

                      ?>

                    </div>

                    <div class="row form-group text-center">

                      <label for='amount'>Amount</label>
                      <input type="number" step='0.01' name="amount" id='amount' value="<?php echo $assessment_amount; ?>">

                      <br>

                      <label for='process_date'>Process Date</label>
                      <input type='date' name='process_date' id='process_date' value="<?php echo date('Y-m-d',strtotime('-1 days')); ?>" required>

                      <br>

                      <label for='document_num'>Document Number</label>
                      <input type='text' name='document_num' id='document_num' required>

                      <input type='text' name='community_id' id='community_id' value='<?php echo $community_id; ?>' hidden required>

                    </div>

                    <center><button type='submit' class="btn btn-success btn-sm">Process Payment</button></center>

                  </form>

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
