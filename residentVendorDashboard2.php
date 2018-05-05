<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>
  
  <head>
    
    <?php

      include 'includes/dbconn.php';
      include 'includes/globalvar.php';

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
    <link rel="stylesheet" href="plugins/select2/select2.min.css">

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

          $vendor_id = $_POST['select_vendor'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM vendor_master WHERE vendor_id=$vendor_id"));

        ?>
        
        <section class="content-header">

          <h1><strong>Vendor Dashboard</strong><small> - <?php echo $row['vendor_name']; ?></small></h1>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

              <div class="box">

                <div class="box-header">

                  <center><h4><strong>Vendor Details</strong></h4></center>

                </div>

                <div class="box-body table-responsive">
                  
                  <table class="table table-bordered">

                    <thead>
                      
                      <th>Vendor Name</th>
                      <th>Active From</th>
                      <th>Approved</th>
                      <th>Vendor Type</th>
                      <th>Payment Method</th>
                      <th>Tax ID</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Address</th>

                    </thead>

                    <tbody>

                      <?php

                        $vendor_name = $row['vendor_name'];
                        $active_from = $row['active_from'];
                        $approved = $row['approved'];
                        $vendor_type = $row['vendor_type_id'];
                        $payment_type = $row['payment_type_id'];
                        $tax_id = $row['tax_id'];
                        $email = $row['email'];
                        $phone = $row['phone_no'];
                        $address = $row['address'];

                        if($active_from != "")
                          $active_from = date('Y-m-d',strtotime($active_from));

                        if($approved == 't')
                          $approved = 'TRUE';
                        else
                          $approved = 'FALSE';

                        if($vendor_type != "")
                        {
                          $row = pg_fetch_assoc(pg_query("SELECT * FROM vendor_type WHERE vendor_type_id=$vendor_type"));

                          $vendor_type = $row['vendor_type_name'];
                        }

                        if($payment_type != "")
                        {
                          $row = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$payment_type"));

                          $payment_type = $row['payment_type_name'];
                        }

                        echo "<tr><td>$vendor_name</td><td>$active_from</td><td>$approved</td><td>$vendor_type</td><td>$payment_type</td><td>$tax_id</td><td>$email</td><td>$phone</td><td>$address</td></tr>";

                      ?>
                      
                    </tbody>
                    
                  </table>

                </div>

                <div class="box-body table-responsive">
                  
                  <table class="table table-bordered">

                    <thead>
                      
                      <th>Service Address</th>
                      <th>Recurrnig Pay</th>
                      <th>Account ID</th>
                      <th>Recurring Pay Day</th>
                      <th>Bill Recurs every</th>
                      <th>Quickbooks Vendor Payments</th>

                    </thead>

                    <tbody>

                      <?php

                        $row = pg_fetch_assoc(pg_query("SELECT * FROM vendor_pay_method WHERE vendor_id=$vendor_id"));

                        $service_address = $row['service_address'];
                        $recurring_pay = $row['recurring_pay'];
                        $account_id = $row['account_id'];
                        $recurring_pay_day = $row['recurring_pay_day_of_month'];
                        $bill_recurs_every = $row['recurs_every_in_days'];

                        if($recurring_pay == 't')
                          $recurring_pay = "TRUE";
                        else
                          $recurring_pay = "FALSE";

                        echo "<tr><td>".$service_address."</td><td>".$recurring_pay."</td><td>".$account_id."</td><td>".$recurring_pay_day."</td><td>".$bill_recurs_every."</td><td></td></tr>";

                      ?>
                      
                    </tbody>
                    
                  </table>

                </div>

              </div>

            </section>

          </div>

          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

              <div class="box">

                <div class="box-header">

                  <center><h4><strong>Current Year Vendor Payment Processed</strong></h4></center>

                </div>

                <div class="box-body table-responsive">
                  
                  <table class="table table-bordered">

                    <thead>
                      
                      <th>Year</th>
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

                        $row = pg_fetch_assoc(pg_query("SELECT * FROM current_year_vendors_pmt_processed WHERE vendor_id=$vendor_id AND community_id=$community_id AND year=$year"));

                        $current_year = $row['year'];
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

                        for ($i = 1; $i <= 12; $i++)
                        {
                          if($m[$i] == 't')
                            $m[$i] = "<center><i class='fa fa-check-square text-success'></i></center>";
                          else
                            $m[$i] = "<center><i class='fa fa-square-o text-orange'></i></center>";
                        }

                        echo "<tr><td>$year</td><td>$m[1]</td><td>$m[2]</td><td>$m[3]</td><td>$m[4]</td><td>$m[5]</td><td>$m[6]</td><td>$m[7]</td><td>$m[8]</td><td>$m[9]</td><td>$m[10]</td><td>$m[11]</td><td>$m[12]</td></tr>";

                      ?>
                      
                    </tbody>
                    
                  </table>

                </div>

              </div>

            </section>

          </div>

          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

              <div class="box">

                <div class="box-header">

                  <center><h4><strong>Accounts Payable</strong></h4></center>

                </div>

                <div class="box-body table-responsive">
                  
                  <table id='example1' class="table table-bordered">

                    <thead>
                      
                      <th>Pay Date</th>
                      <th>Payment Type</th>
                      <th>Amount</th>
                      <th>Bank Account</th>
                      <th>Payment Cleared</th>
                      <th>Date Payment Cleared</th>
                      <th>Closing Year</th>
                      <th>Closing Month</th>
                      <th>Document</th>

                    </thead>

                    <tbody>

                      <?php

                        $res = pg_query("SELECT * FROM accounts_payable WHERE vendor_id=$vendor_id AND community_id=$community_id ORDER BY pay_date DESC");

                        if($res)
                        {

                          while($row = pg_fetch_assoc($res))
                          {
                            
                            $pay_date = $row['pay_date'];
                            $payment_type = $row['payment_type_id'];
                            $amount = $row['amount'];
                            $bank_account = $row['bank_account_id'];
                            $payment_cleared = $row['payment_cleared'];
                            $date_payment_cleared = $row['date_payment_cleared'];
                            $closing_year = $row['closing_year'];
                            $closing_month = $row['closing_month'];
                            $id = $row['id'];

                            if($payment_cleared == 't')
                              $payment_cleared = 'TRUE';
                            else
                              $payment_cleared = 'FALSE';

                            if($payment_type != "")
                            {
                              $row1 = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$payment_type"));

                              $payment_type = $row1['payment_type_name'];
                            }

                            if($bank_account != "")
                            {
                              $row1 = pg_fetch_assoc(pg_query("SELECT * FROM bank_account WHERE community_id=$community_id AND id=$bank_account"));

                              $bank_account = $row1['bank_name'];
                              $bank_account .= " ";
                              $bank_account .= $row1['last4_account_no'];
                            }

                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM accounts_payable_documents WHERE accounts_payable_id=$id"));

                            $document = $row1['document_id'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM document_management WHERE document_id=$document"));

                            $document_url = $row1['url'];

                            if($document_url == "")
                              $document_url = "N/A";
                            else
                              $document_url = "<a href='".$document_url."' target='_blank'><i class='fa fa-file-pdf-o'></i></a>";

                            echo "<tr><td>$pay_date</td><td>$payment_type</td><td>$ $amount</td><td>$bank_account</td><td>$payment_cleared</td><td>$date_payment_cleared</td><td>$closing_year</td><td>$closing_month</td><td>$document_url</td></tr>";

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

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

              <div class="box">

                <div class="box-header">

                  <center><h4><strong>Vendor Contracts</strong></h4></center>

                </div>

                <div class="box-body table-responsive">
                  
                  <table id='example1' class="table table-bordered">

                    <thead>
                      
                      <th>Active From</th>
                      <th>Active Until</th>
                      <th>Yearly Amount</th>
                      <th>Description</th>
                      <th>Document</th>

                    </thead>

                    <tbody>

                      <?php

                        $res = pg_query("SELECT * FROM community_vendor_contracts WHERE vendor_id=$vendor_id AND community_id=$community_id ORDER BY active_from DESC");

                        if($res)
                        {

                          while($row = pg_fetch_assoc($res))
                          {
                            
                            $active_from = $row['active_from'];
                            $active_until = $row['active_until'];
                            $document = $row['document'];
                            $yearly_amount = $row['yearly_amount'];
                            $desc = $row['desc'];

                            if($document != "")
                            {

                              $row1 = pg_fetch_assoc(pg_query("SELECT * FROM document_management WHERE document_id=$document"));

                              $document_url = $row1['url'];

                              if($document_url == "")
                                $document_url = "N/A";
                              else
                                $document_url = "<a href='".$document_url."' target='_blank'><i class='fa fa-file-pdf-o'></i></a>";

                            }
                            else
                              $document_url = "N/A";

                            echo "<tr><td>$active_from</td><td>$active_until</td><td>$ $yearly_amount</td><td>$desc</td><td>$document_url</td></tr>";

                          }

                        }

                      ?>
                      
                    </tbody>
                    
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
        $(".select2").select2();
      });
    </script>

  </body>

</html>