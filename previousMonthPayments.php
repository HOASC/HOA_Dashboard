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
      $user_id = $_SESSION['hoa_user_id'];
      $mode = $_SESSION['hoa_mode'];

      include 'includes/dbconn.php';

      if($mode == 2)
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

      <?php if($mode == 1) include "boardHeader.php"; ?>
      
      <?php if($mode == 1) include 'boardNavigationMenu.php'; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

          $year = date("Y");
          $month = date("m");
          $end_date = date("t");

          $result = pg_query("SELECT * FROM community_invoices WHERE community_id=$community_id AND reserve_expense='t'");

          $today = date('Y-m-d');

        ?>
        
        <section class="content-header">

          <h1><strong>Previous Month Payments</strong></h1>

          <ol class="breadcrumb">
            
            <?php if($mode == 1) echo "<li><i class='fa fa-institution'></i> Community</li>"; ?>

            <li>Previous Month Payments</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

            <center>
              
              <form method='POST' action='previousMonthPayments.php'>
                  
                <?php

                  if(isset($_POST['submit']))
                  {    
                                    
                    echo "Showing payments of <select id='month' name='month' required><option "; if($_POST['month'] == 1) echo "selected"; echo " value='1'>January</option><option "; if($_POST['month'] == 2) echo "selected"; echo " value='2'>February</option><option "; if($_POST['month'] == 3) echo "selected"; echo " value='3'>March</option><option "; if($_POST['month'] == 4) echo "selected"; echo " value='4'>April</option><option "; if($_POST['month'] == 5) echo "selected"; echo " value='5'>May</option><option "; if($_POST['month'] == 6) echo "selected"; echo " value='6'>June</option><option "; if($_POST['month'] == 7) echo "selected"; echo " value='7'>July</option><option "; if($_POST['month'] == 8) echo "selected"; echo " value='8'>August</option><option "; if($_POST['month'] == 9) echo "selected"; echo " value='9'>September</option><option "; if($_POST['month'] == 10) echo "selected"; echo " value='10'>October</option><option "; if($_POST['month'] == 11) echo "selected"; echo " value='11'>November</option><option "; if($_POST['month'] == 12) echo "selected"; echo " value='12'>December</option></select><br><br><input type='submit' class='btn btn-warning btn-xs' name='submit' id='submit' value='Show Payments'>";

                  }
                  else
                  {
                    $m = (date("m") - 1); 

                    echo "Show payments of <select id='month' name='month' required><option ";

                    if($m == 1)
                      echo "selected ";

                    echo "value='1'>January</option><option ";

                    if($m == 2)
                      echo "selected ";

                    echo "value='2'>February</option><option ";

                    if($m == 3)
                      echo " selected ";

                    echo "value='3'>March</option><option ";

                    if($m == 4)
                      echo " selected ";

                    echo "value='4'>April</option><option ";

                    if($m == 5)
                      echo " selected ";

                    echo "value='5'>May</option><option ";

                    if($m == 6)
                      echo " selected ";

                    echo "value='6'>June</option><option ";

                    if($m == 7)
                      echo " selected ";

                    echo "value='7'>July</option><option ";

                    if($m == 8)
                      echo " selected ";

                    echo "value='8'>August</option><option ";

                    if($m == 9)
                      echo " selected ";

                    echo "value='9'>September</option><option ";

                    if($m == 10)
                      echo " selected ";

                    echo "value='10'>October</option><option ";

                    if($m == 11)
                      echo " selected ";

                    echo "value='11'>November</option><option ";

                    if($m == 12)
                      echo " selected ";

                    echo "value='12'>December</option></select><br><br><input type='submit' class='btn btn-warning btn-xs' name='submit' id='submit' value='Show Payments'>";

                  }

                ?>

              </form>

            </center>

          </div>

          <br><br>

          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-body table-responsive">
                  
                  <table id='example1' class="table table-striped table-bordered">

                    <thead>
                      
                      <th>Payment Date</th>
                      <th>Name</th>
                      <th>Living In</th>
                      <th>Confirmation Number</th>
                      <th>Pay Method</th>
                      <th>Amount</th>
                      <th>Current Balance</th>

                    </thead>

                    <tbody>

                      <?php
                        
                        if(isset($_POST['submit']))
                        {
                                    
                          $month = $_POST['month'];
                          $start_date = $year."-".$month."-1";
                          $end_date = $year."-".$month."-".date('t', strtotime($start_date));

                          $result = pg_query("SELECT * FROM hoaid WHERE community_id=$community_id");

                          while($row = pg_fetch_assoc($result))
                          {

                            $home_id = $row['home_id'];
                            $hoa_id = $row['hoa_id'];
                            $name = $row['firstname'];
                            $name .= " ";
                            $name .= $row['lastname'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));

                            $living_in = $row1['address1'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE hoa_id=$hoa_id AND home_id=$home_id"));
                            $payments = $row1['sum'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE hoa_id=$hoa_id AND home_id=$home_id"));
                            $charges = $row1['sum'];

                            $balance = $charges - $payments;

                            $result1 = pg_query("SELECT * FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND process_date>='$start_date' AND process_date<='$end_date' AND hoa_id=$hoa_id AND home_id=$home_id");

                            if(pg_num_rows($result1))
                            {

                              while($row1 = pg_fetch_assoc($result1))
                              {
                              
                                $process_date = $row1['process_date'];
                                $confirmation = $row1['document_num'];
                                $pay_method = $row1['payment_type_id'];
                                $amount = $row1['amount'];

                                $row2 = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$pay_method"));
                                $pay_method = $row2['payment_type_name'];

                                echo "<tr><td>".date('m-d-Y', strtotime($process_date))."</td><td>$name<br>($hoa_id)</td><td>$living_in<br>($home_id)</td><td>$confirmation</td><td>$pay_method</td><td>$amount</td><td>$ $balance</td></tr>";

                              }

                            }
                            else
                            {
                              
                              echo "<tr class='text-danger'><td></td><td>$name<br>($hoa_id)</td><td>$living_in<br>($home_id)</td><td></td><td></td><td></td><td>$ $balance</td></tr>";

                            }

                          }

                        }
                        else
                        {
                                    
                          $month = date('m') - 1;
                          $start_date = $year."-".$month."-1";
                          $end_date = $year."-".$month."-".date('t', strtotime($start_date));

                          $result = pg_query("SELECT * FROM hoaid WHERE community_id=$community_id");

                          while($row = pg_fetch_assoc($result))
                          {

                            $home_id = $row['home_id'];
                            $hoa_id = $row['hoa_id'];
                            $name = $row['firstname'];
                            $name .= " ";
                            $name .= $row['lastname'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));

                            $living_in = $row1['address1'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE hoa_id=$hoa_id AND home_id=$home_id"));
                            $payments = $row1['sum'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE hoa_id=$hoa_id AND home_id=$home_id"));
                            $charges = $row1['sum'];

                            $balance = $charges - $payments;

                            $result1 = pg_query("SELECT * FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND process_date>='$start_date' AND process_date<='$end_date' AND hoa_id=$hoa_id AND home_id=$home_id");

                            if(pg_num_rows($result1))
                            {

                              while($row1 = pg_fetch_assoc($result1))
                              {
                              
                                $process_date = $row1['process_date'];
                                $confirmation = $row1['document_num'];
                                $pay_method = $row1['payment_type_id'];
                                $amount = $row1['amount'];

                                $row2 = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$pay_method"));
                                $pay_method = $row2['payment_type_name'];

                                echo "<tr><td>".date('m-d-Y', strtotime($process_date))."</td><td><a title='User Dashboard' href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>$name<br>($hoa_id)</a></td><td>$living_in<br>($home_id)</td><td>$confirmation</td><td>$pay_method</td><td>$amount</td><td>$ $balance</td></tr>";

                              }

                            }
                            else
                            {
                              
                              echo "<tr class='text-danger'><td></td><td><a title='User Dashboard' href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>$name<br>($hoa_id)</a></td><td>$living_in<br>($home_id)</td><td></td><td></td><td></td><td>$ $balance</td></tr>";

                            }

                          }

                        } 
                      
                      ?>

                    </tbody>

                    <tfoot>
                      
                      <th>Payment Date</th>
                      <th>Name</th>
                      <th>Living In</th>
                      <th>Confirmation Number</th>
                      <th>Pay Method</th>
                      <th>Amount</th>
                      <th>Current Balance</th>

                    </tfoot>

                  </table>

                </div>

              </div>

            </section>

          </div>

        </section>

      </div>

      <?php include 'footer.php'; ?>

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