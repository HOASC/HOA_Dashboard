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

      if($_SESSION['hoa_mode'] == 2)
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

      <?php include "boardHeader.php"; ?>
      
      <?php include 'boardNavigationMenu.php'; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

        	$year = date("Y");
        	$month = date("m");
        	$end_date = date("t");

          $result = pg_query("SELECT * FROM homeid WHERE community_id=$community_id");
          $total_customers = pg_num_rows($result);

        ?>
        
        <section class="content-header">

          <h1><strong>Settling Payments</strong><small> - <?php echo date("F").", ".$year; ?></small></h1>

          <ol class="breadcrumb">
            
            <li><a href='boardDashboard.php'><i class="fa fa-dashboard"></i> Board Dashboard</a></li>
            <li>Settling Payments</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Payment Date</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Confirmation Number</th>
                        <th>Pay Method</th>
                        <th>Amount</th>
                        <th>Balance</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        while($row = pg_fetch_assoc($result))
                        {

                          $home_id = $row['home_id'];
                          $address = $row['address1'];
                          $living_status = $row['living_status'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE home_id=$home_id"));

                          $hoa_id = $row1['hoa_id'];
                          $name = $row1['firstname'];
                          $name .= " ";
                          $name .= $row1['lastname'];

                          $result2 = pg_query("SELECT * FROM current_payments WHERE hoa_id=$hoa_id AND home_id=$home_id  AND payment_status_id=8 AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'");

                          if($result2)
                          {
                            
                            while($row1 = pg_fetch_assoc($result2))
                            {
                              
                              $amount = $row1['amount'];
                              $confirmation = $row1['document_num'];
                              $process_date = $row1['process_date'];
                              $pay_method = $row1['payment_type_id'];

                              $row2 = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$pay_method"));

                              $pay_method = $row2['payment_type_name'];

                              if($confirmation == "")
                                $confirmation = "N/A";

                              $row2 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE home_id=$home_id"));
                              $charge = $row2['sum'];

                              $row2 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND home_id=$home_id"));
                              $payment = $row2['sum'];

                              $balance = $charge - $payment;

                              echo "<tr";

                              if($living_status != 't')
                                echo " class='text-red' ";

                              echo "><td>".date('m-d-Y', strtotime($process_date))."</td><td><a href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>".$name."($hoa_id)</a></td><td>".$address."($home_id)</td><td>".$confirmation."</td><td>".$pay_method."</td><td>$ ".$amount."</td><td>$ ".$balance."</td></tr>";

                            }

                          }

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Payment Date</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Confirmation Number</th>
                        <th>Pay Method</th>
                        <th>Amount</th>
                        <th>Balance</th>

                      </tr>

                    </tfoot>

                  </table>

                </div>

              </div>

            </section>

          </div>

        </section>

      </div>

      <?php include "footer.php"; ?>

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