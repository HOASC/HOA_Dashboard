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
             
                <li class="treeview">

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

          $result = pg_query("SELECT * FROM inspection_notices WHERE community_id=$community_id");

        ?>
        
        <section class="content-header">

          <h1><strong>Inspection Notices</strong><small> - <?php echo $_SESSION['hoa_community_name']; ?></small></h1>

        </section>

        <section class="content">
          
          <div class="row">

          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">

                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Inspection Date</th>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Document</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        while($row = pg_fetch_assoc($result))
                        {

                          $id = $row['id'];
                          $item = $row['item'];
                          $home_id = $row['home_id'];
                          $hoa_id = $row['hoa_id'];
                          $description = $row['description'];
                          $document = $row['document_id'];
                          $inspection_date = $row['inspection_date'];
                          $location = $row['location_id'];
                          $violation_category = $row['inspection_category_id'];
                          $violation_sub_category = $row['inspection_sub_category_id'];
                          $notice_type = $row['inspection_notice_type_id'];
                          $date_of_upload = $row['date_of_upload'];
                          $status_id = $row['inspection_status_id'];
                          $compliance_date = $row['compliance_date'];

                          if($inspection_date != "")
                            $inspection_date = date('m-d-Y', strtotime($inspection_date));

                          if($compliance_date != "")
                            $compliance_date = date('m-d-Y', strtotime($compliance_date));

                          if($date_of_upload != "")
                            $date_of_upload = date('m-d-Y', strtotime($date_of_upload));

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$hoa_id"));

                          $hoa_id = $row1['hoa_id'];
                          $name = $row1['firstname'];
                          $name .= " ";
                          $name .= $row1['lastname'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));

                          $address = $row1['address1'];
                          $living_status = $row1['living_status'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM inspection_status WHERE id=$status_id"));

                          $status = $row1['inspection_status'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM inspection_category WHERE id=$violation_category"));

                          $violation_category = $row1['name'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM locations_in_community WHERE location_id=$location"));

                          $location = $row1['location'];

                          echo "

                          <div class='modal fade hmodal-success' id='sendInspectionReply_$id' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                                
                              <div class='modal-content'>
                                                    
                                <div class='color-line'></div>
                                    
                                <div class='modal-header'>
                                                            
                                  <h4 class='modal-title'><strong>Inspection Notice - $location</strong></h4>

                                </div>

                                <div class='modal-body'>

                                  <form method='post' action='https://hoaboardtime.com/boardSendInspectionNotes.php'>
                                                            
                                    <div class='row container-fluid'>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Date : </label> $date
                                        <input type='hidden' name='id' id='id' value='".$id."' />
                                        <input type='hidden' name='home' id='home' value='".$_SESSION['hoa_address']."' />
                                        <input type='hidden' name='owner' id='owner' value='".$_SESSION['hoa_username']."' />
                                        <input type='hidden' name='date' id='date' value='$date' />

                                        <br>

                                      </div>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Inspection Notice : </label> $id
                                        <input type='hidden' name='inspection_notice' id='inspection_notice' value='$id' />

                                        <br>

                                      </div>

                                    </div>

                                    <div class='row container-fluid'>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Compliance Date : </label> $compliance_date
                                        <input type='hidden' name='compliance_date' id='compliance_date' value='".$compliance_date."' />

                                        <br>

                                      </div>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Viewed From : </label> $location
                                        <input type='hidden' name='viewed_from' id='viewed_from' value='$location' />

                                        <br>

                                      </div>

                                    </div>

                                    <div class='row container-fluid'>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Item : </label> $item
                                        <input type='hidden' name='item' id='item' value='".$item."' />

                                        <br>

                                      </div>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Observation : </label> $description
                                        <input type='hidden' name='observation' id='observation' value='".$description."' />

                                        <br>

                                      </div>

                                    </div>

                                    <div class='row container-fluid'>

                                      <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                        <label>Followup Questions / Comments : </label>
                                        <textarea class='form-control' name='notice_summary' placeholder='Max 500 characters' id='notice_summary' required maxlength='500'></textarea>

                                        <br>

                                      </div>

                                    </div>

                                    <div class='row text-center'>

                                      <br>
                                        
                                      <button type='submit' name='submit' id='submit' value='3' class='btn btn-success btn-xs'>Send</button>

                                      <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'>Cancel</button>

                                    </div>

                                  </form>

                                </div>

                              </div>
                              
                            </div>

                          </div>

                          ";
                          
                          if($status != 'Closed By Vendor' && $status != 'Request Closed By Member' && $status != 'Closed' && $status != 'Closed by CIS' && $status != 'Resolved')
                          {  

                            echo "<tr";

                            if($living_status != 't')
                              echo " class='text-red' ";

                            echo "><td><a data-toggle='modal' data-target='#sendInspectionReply_$id'>".$inspection_date."</a></td><td>".$status."</td><td><a href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>".$name."<br>($hoa_id)</a></td><td>".$address."<br>($home_id)</td><td>".$location."</td><td>".$description."</td><td>".$violation_category."</td><td>".$document."</td></tr>";

                          }
                          
                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Inspection Date</th>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Document</th>

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
        $("#example1").DataTable({ "pageLength": 50, "order": [[0, 'desc']] });
      });
    </script>

  </body>

</html>