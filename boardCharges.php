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
    <link rel="stylesheet" href="plugins/select2/select2.min.css">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <style>
      .example-modal .modal {
        position: relative;
        top: auto;
        bottom: auto;
        right: auto;
        left: auto;
        display: block;
        z-index: 1;
      }

      .example-modal .modal {
        background: transparent !important;
      }
    </style>

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
                echo "<li class='header text-center'>".$communtiy_logo."</li>"
                ; ?>
            
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
                <li class='active treeview'>

                  <a>

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

          $result100 = pg_query("SELECT * FROM current_charges WHERE community_id=$community_id AND (assessment_rule_type_id=9 OR assessment_rule_type_id=3 OR assessment_rule_type_id=5)");

        ?>
        
        <section class="content-header">

          <h1><strong>Late Fee / Board Write Off</strong></h1>

        </section>

        <div id='myModel' class='modal fade' role='dialog'>
            
            <div class='modal-dialog'>

              <!-- Modal content-->
              <div class='modal-content'>

                <div class='modal-header'>
                                    
                  <button type='button' class='close' data-dismiss='modal'>&times;</button>
                  <h4 class='modal-title text-success'><strong>Add Board Fee</strong></h4>

                </div>

                <div class='modal-body row'>

                  <div class='container-fluid row'>

                    <form method="POST" action="boardAddCharge.php" class="col-xl-offset-1 col-lg-offset-1 col-xl-10 col-lg-10 col-md-12 col-xs-12" role="form">

                      <div class='row form-group'>
                                                
                        <label for="hoa_id">Select Member</label>
                        <select class="select2" id='hoa_id' name='hoa_id' required>

                          <?php
                                                        
                            $result = pg_query("SELECT * FROM hoaid WHERE community_id=".$community_id." ORDER BY firstname");

                            while ($row = pg_fetch_assoc($result)) 
                            {
                              $hoa_id = $row['hoa_id'];
                              $home_id = $row['home_id'];
                              $name = $row['firstname'];
                              $name .= " ";
                              $name .= $row['lastname'];

                              $res = pg_query("SELECT address1 FROM homeid WHERE home_id=".$home_id);
                              $ro = pg_fetch_assoc($res);

                              $address = $ro['address1'];

                              echo "<option value='".$hoa_id."'>".$name." - ".$address."</option>";
                            }

                          ?>

                        </select>

                      </div>

                      <br>

                      <div class="row text-center">
                                                
                        <label>Assessment Type </label>
                        <input type="radio" name="assement_rule_type_id" id="assement_rule_type_id" value="9" checked> Board Write Off <input type="radio" name="assement_rule_type_id" id="assement_rule_type_id" value="3"> Late Fee <input type="radio" name="assement_rule_type_id" id="assement_rule_type_id" value="5"> Opening Balance
                      
                      </div>

                      <br>

                      <div class="row">
                                                
                        <div class='col-xl-6 col-lg-6 col-md-6 col-xs-12 form-group'>
                                                    
                          <label for="amount">Assessment Amount (in $)</label>
                          <input type="number" min="0.01" class="form-control" name="amount" id="amount" step="0.01" required>
                                                
                        </div>  
                                                
                        <div class='col-xl-6 col-lg-6 col-md-6 col-xs-12 form-group'>
                                                    
                          <label for="assessment_date">Assessment Date</label>
                          <input type="date" class="form-control" name="assessment_date" id="assessment_date" value="<?php echo date("Y-m-d"); ?>" required>
                                                
                        </div>
                      </div>     

                      <div class="row">

                        <div class='col-xl-6 col-lg-6 col-md-6 col-xs-12 form-group'>

                          <label for="assessment_month">Assessment Month</label>
                          <select class="form-control" name="assessment_month" id="assessment_month" required>

                            <option <?php if(date('m') == 1) echo "selected" ?> value="1">January</option>
                            <option <?php if(date('m') == 2) echo "selected" ?> value="2">February</option>
                            <option <?php if(date('m') == 3) echo "selected" ?> value="3">March</option>
                            <option <?php if(date('m') == 4) echo "selected" ?> value="4">April</option>
                            <option <?php if(date('m') == 5) echo "selected" ?> value="5">May</option>
                            <option <?php if(date('m') == 6) echo "selected" ?> value="6">June</option>
                            <option <?php if(date('m') == 7) echo "selected" ?> value="7">July</option>
                            <option <?php if(date('m') == 8) echo "selected" ?> value="8">August</option>
                            <option <?php if(date('m') == 9) echo "selected" ?> value="9">September</option>
                            <option <?php if(date('m') == 10) echo "selected" ?> value="10">October</option>
                            <option <?php if(date('m') == 11) echo "selected" ?> value="11">November</option>
                            <option <?php if(date('m') == 12) echo "selected" ?> value="12">December</option>

                          </select>

                      </div>

                      <div class='col-xl-6 col-lg-6 col-md-6 col-xs-12 form-group'>
                                                    
                        <label for="assessment_date">Assessment Year</label>
                        <input type="number" class="form-control" name="assessment_year" id="assessment_year" value="<?php echo date("Y"); ?>" required>

                      </div> 

                    </div>

                    <div class='modal-footer'>

                      <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                      <button type="submit" class='btn btn-success'>Add</button>

                    </div>

                  </form>

                </div>

              </div>

            </div>

          </div>

        </div>

        <section class="content">
          
          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-header">
                  <i class="fa fa-"></i>

                  <div class="box-tools pull-right">

                    <a data-toggle='modal' href='#myModel' data-toggle='popover' data-trigger='hover' type="button" class="btn bg-teal btn-sm">Add Charge</a>

                  </div>

                </div>

                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Assessment Date</th>
                        <th>Name</th>
                        <th>Home Address</th>
                        <th>Assessment Type</th>
                        <th>Assessment Month</th>
                        <th>Assessment Year</th>
                        <th>Amount</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        while($row = pg_fetch_assoc($result100))
                        {

                          $hoa_id = $row['hoa_id'];
                          $home_id = $row['home_id'];
                          $assessment_rule = $row['assessment_rule_type_id'];
                          $amount = $row['amount'];
                          $assessment_date = $row['assessment_date'];
                          $assessment_month = $row['assessment_month'];
                          $assessment_year = $row['assessment_year'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$hoa_id"));
                          $name = $row1['firstname'];
                          $name .= " ";
                          $name .= $row1['lastname'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));
                          $address = $row1['address1'];
                          $living_status = $row1['living_status'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM assessment_rule_type WHERE assessment_rule_type_id=$assessment_rule"));
                          $assessment_rule = $row1['name'];

                          echo "<tr";

                          if($living_status != 't')
                            echo " class='text-red' ";

                          echo "><td>".date('m-d-Y', strtotime($assessment_date))."</td><td><a title='User Dashboard' href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>".$name."($hoa_id)</a></td><td>".$address."($home_id)</td><td>".$assessment_rule."</td><td>".$assessment_month."</td><td>".$assessment_year."</td><td>".$amount."</td></tr>";

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Assessment Date</th>
                        <th>Name</th>
                        <th>Home Address</th>
                        <th>Assessment Type</th>
                        <th>Assessment Month</th>
                        <th>Assessment Year</th>
                        <th>Amount</th>

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
    <script src="plugins/select2/select2.full.min.js"></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50, "order": [[ 0, "desc" ]] });

        $(".select2").select2();
      });
    </script>

  </body>

</html>
