<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <?php

        include 'includes/dbconn.php';

        if(@!$_SESSION['hoa_username'])
          header("Location: logout.php");

        $community_id = $_SESSION['hoa_community_id'];

        $query = "SELECT * FROM board_committee_details WHERE user_id=".$_SESSION['hoa_user_id'];
        $result = pg_query($query);
        $board = pg_num_rows($result);

        $hoa_id = $_SESSION['hoa_hoa_id'];
        $home_id = $_SESSION['hoa_home_id'];

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

                  <?php

                    if($board)
                    echo "<li class='dropdown user user-menu'>
                  
                      <a href='boardDashboard.php'>Board Dashboard</a>

                    </li>";

                  ?>

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

                              <a href="logout.php" class="btn btn-warning">Log Out</a>

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
            
                <li class="header text-center"> Quick Links </li>

                <li class="treeview">
              
                    <a href="https://hoaboardtime.com/residentDashboard.php">
                
                      <i class="fa fa-dashboard"></i> <span><?php if($board) echo "Resident "; ?>Dashboard</span>

                    </a>

                </li>
            
                <li class="treeview">
              
                    <a href="https://hoaboardtime.com/residentDocumentManagement.php">

                      <i class="glyphicon glyphicon-hdd"></i> <span>Document Management</span>

                    </a>

                </li>
             
                <li class="treeview">

                    <a href='https://hoaboardtime.com/residentViewMeetingMinutes.php'>

                      <i class='fa fa-folder'></i> <span>Meeting Minutes</span>
              
                    </a>

                </li>
            
                <li class="treeview">
              
                    <a href='https://hoaboardtime.com/residentQuickPay.php'>

                      <i class='fa fa-dollar'></i> <span>Quick Pay</span>

                    </a>

                </li>

                <li class="treeview">

                    <a href='https://hoaboardtime.com/residentRecurringPay.php'>

                      <i class='fa fa-repeat'></i> <span>Recurring Pay</span>
              
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

          <h1><strong>My Signed Agreements</strong></h1>

        </section>

        <section class="content">
          
          <div class="row">

          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="row container-fluid"  style="background-color: white;">

                <div class="box-body table-responsive">
                  
                  <table id='example1' class="table table-striped table-bordered">

                    <thead>
                      
                      <th>Fund Received On</th>
                      <th>ID</th>
                      <th>Net Amount</th>
                      <th>Number of Transactions</th>
                      <th>Status</th>
                      <th>Fund Sent On</th>

                    </thead>

                    <tbody>

                      <?php

                        $result = pg_query("SELECT * FROM community_deposits WHERE community_id=$community_id");

                        while($row = pg_fetch_assoc($result))
                        {

                          $funding_id = $row['funding_id'];
                          $id1 = $row['id'];
                          $status = $row['status'];
                          $net_amount = $row['net_amount'];
                          $number_of_transactions = $row['number_of_transactions'];
                          $effective_date = $row['effective_date'];
                          $origination_date = $row['origination_date'];
                          $routing_number = $row['routing_number'];
                          $account_number = $row['account_number_last_four_digits'];
                          $entry_description = $row['entry_description'];

                          if($effective_date != '')
                            $effective_date = date('m-d-Y', strtotime($effective_date));

                          if($origination_date != '')
                            $origination_date = date('m-d-Y', strtotime($origination_date));

                          echo "<div class='modal fade hmodal-success' id='funding_id_".$funding_id."' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                              
                              <div class='modal-content'>
                                                  
                                <div class='color-line'></div>

                                  <div class='modal-body table-responsive'>";

                                    $result1 = pg_query("SELECT * FROM community_funding_transactions WHERE funding_id='$funding_id' ORDER BY id");

                                    echo "<div class='row container-fluid'>

                                      <div class='row text-center'>

                                        <strong>

                                          <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>ID</div>
                                          <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>Status</div>
                                          <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>Amount</div>
                                          <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>Received Date</div>

                                        </strong>

                                      </div>

                                      <br>

                                      <div class='row container-fluid'>";

                                        while($row1 = pg_fetch_assoc($result1))
                                        {

                                          $id = $row1['id'];
                                          $transaction_id = $row1['transaction_id'];
                                          $funding_status = $row1['status'];
                                          $amount = $row1['amount'];
                                          $received_date = $row1['received_date'];
                                          $funding_hoa_id = $row1['hoa_id'];

                                          if($received_date != '')
                                            $received_date = date('m-d-Y', strtotime($received_date));

                                          $row11 = pg_fetch_assoc(pg_query("SELECT * FROM current_payments WHERE bank_transaction_id='$transaction_id'"));

                                          $t_hoa_id = $row11['hoa_id'];
                                          $t_home_id = $row11['home_id'];

                                          $row11 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$t_hoa_id"));

                                          $name = $row11['firstname'];
                                          $name .= " ";
                                          $name .= $row11['lastname'];

                                          $row11 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$t_home_id"));

                                          $address = $row11['address1'];

                                          echo "<div class='row text-center'><div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>$id</div><div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>$funding_status</div><div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>$amount</div><div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>$received_date</div></div><br>";

                                        }

                                      echo "</div>

                                    </div>";

                                  echo "</div>

                                  <br>

                                </div>
                            
                              </div>

                            </div>";

                          echo "<tr><td>".$effective_date."</td><td><a data-toggle='modal' data-target='#funding_id_".$funding_id."'>".$id1."</td><td><a data-toggle='modal' data-target='#funding_id_".$funding_id."'>$ ".$net_amount."</a></td><td><a data-toggle='modal' data-target='#funding_id_".$funding_id."'>".$number_of_transactions."</a></td><td>".$status."</td><td>".$origination_date."</td></tr>";

                        }

                      ?>

                    </tbody>

                    <tfoot>
                      
                      <th>Fund Received On</th>
                      <th>Funding Id</th>
                      <th>Net Amount</th>
                      <th>Number of Transactions</th>
                      <th>Status</th>
                      <th>Fund Sent On</th>

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