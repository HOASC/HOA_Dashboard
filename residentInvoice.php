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

        include 'includes/dbconn.php';

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

          $home_id = $_SESSION['hoa_home_id'];
          $hoa_id = $_SESSION['hoa_hoa_id'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$hoa_id"));

          $cus_name = $row['firstname'];
          $cus_name .= " ";
          $cus_name .= $row['lastname'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM community_info WHERE community_id=$community_id"));

          $city = $row['payment_city'];
          $c_name = $row['legal_name'];
          $pobox = $row['remit_payment_address'];
          $state = $row['payment_addr_state'];
          $zip = $row['payment_addr_zip'];
          $tax_id = $row['taxid'];
          $c_email = $row['email'];
          $c_website = $row['community_website_url'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$state"));
          $state = $row['state_code'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$city"));
          $city = $row['city_name'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$zip"));
          $zip = $row['zip_code'];

          $result = pg_query("SELECT * FROM current_charges WHERE home_id=".$home_id." ORDER BY assessment_date DESC LIMIT 1");

          $row = pg_fetch_assoc($result);
          $adate = $row['assessment_date'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));

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

        <section class="content">
          
          <div class="row">

            <section class="invoice">

              <div class="row invoice-info">
        
                <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6'>
                      
                  From : 

                  <address style="font-size: 14pt;">
                        
                    <?php echo "<strong>".$c_name."</strong><br>".$pobox."<br>".$city.", ".$state." ".$zip; ?>

                  </address>

                </div>

                <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6 text-right'>
                      
                  <span><strong>Invoice No : </strong><?php echo $community_id."-".$home_id."-".$hoa_id ?>-<script type='text/javascript'>document.write(new Date().getFullYear())</script></span><br>
                  <span><strong>Invoice Date : </strong><?php echo date("m-d-y", strtotime($adate)); ?></span><br>
                  <span><strong>Due Date : </strong><?php if(date("m", strtotime($adate)) == date("m"))$month = date("m"); else if(date("m", strtotime($adate)) < date("m")) $month = date("m")-1; else if(date("m", strtotime($adate)) > date("m")) $month = date("m")+1; echo $month."-15-".date("y"); ?></span>
                      
                </div>

              </div>

              <br><br><br><br>

              <div class="row">

                <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6'>
                      
                  To :

                  <address style="font-size: 14pt;">
                        
                    <?php echo "<strong>".$cus_name."</strong><br>".$cus_addr."<br>".$cus_city.", ".$cus_state." ".$cus_zip; ?>

                  </address>

                </div>

              </div>

              <div class="row">

                <div class="col-xs-12 table-responsive">
          
                  <table class="table table-striped">
            
                    <thead>
            
                      <tr>
                        
                        <th>Month</th>
                        <th>Document ID</th>
                        <th>Description</th>
                        <th>Charge</th>
                        <th>Payment</th>
                        <th>Balance</th>

                      </tr>
                    
                    </thead>

                    <tbody>
                      
                      <?php

                        for($m = 1; $m <= 12; $m++)
                        {

                          $last_date = date("Y-m-t", strtotime("$year-$m-1"));
                          
                          $charges_results = pg_query("SELECT * FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id AND assessment_date>='$year-$m-1' AND assessment_date<='$last_date' ORDER BY assessment_date");

                          $payments_results = pg_query("SELECT * FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND process_date>='$year-$m-1' AND process_date<='$last_date' ORDER BY process_date");

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

                      <tr><td></td><td></td><td><strong>Total</strong></td><td><?php $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id")); $total_charges = $row['sum']; echo "$ ".$total_charges; ?></td><td><?php $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND payment_status_id=1")); $total_payments = $row['sum']; echo "$ ".$total_payments; ?></td><td><?php $total = $total_charges - $total_payments; echo "$ ".$total; ?></td></tr>

                    </tbody>
          
                  </table>
        
                </div>

              </div>

              <div class="row">

                <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6'>
                      
                  <strong>Note</strong><br>

                  BillPay Address : 

                  <address>
                        
                    <?php echo "<strong>".$c_name."</strong><br>".$pobox."<br>".$city.", ".$state." ".$zip."<br>EIN : ".$tax_id; ?>

                  </address>

                  Send an email to <?php echo "<u><a href='mailto:".$c_email."'>".$c_email."</a></u>"; ?> for HOA related queries.<br>All updates will be posted at <?php echo "<u><a href='".$c_website."'>".$c_website."</a></u>"; ?>

                </div>

              </div>

              <br><br>

              <div class="row no-print">
                
                <div class="col-xs-12">
                  
                  <?php echo "<a href='https://hoaboardtime.com/residentInvoicePrint.php?hoa_id=$hoa_id&home_id=$home_id' target='_blank' class='btn btn-default'><i class='fa fa-print'></i> Print</a>"; ?>
          
                </div>
      
              </div>
    
            </section>

            <div class="clearfix"></div>

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