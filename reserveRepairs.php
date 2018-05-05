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

      <?php if($mode == 1) include "boardHeader.php"; else if($mode == 2) include "residentHeader.php"; ?>
      
      <?php if($mode == 1) include 'boardNavigationMenu.php'; else if($mode == 2) include "residentNavigationMenu.php"; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

          $year = date("Y");
          $month = date("m");
          $end_date = date("t");

          $ryear = $_GET['year'];

        ?>
        
        <section class="content-header">

          <h1><strong>Reserve Repairs</strong><small> - <?php echo $ryear; ?></small></h1>

          <ol class="breadcrumb">
            
            <li><a href='reservesDashboard.php'><i class="fa fa-support"></i> Reserves Dashboard</a></li>
            <li>Reserve Repairs</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="row container-fluid" style="background-color: white;">

                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Invoice Date</th>
                        <th>Vendor Name<?php if($mode == 1) echo " (Vendor ID)"; ?></th>
                        <th>Work Status</th>
                        <th>Payment Status</th>
                        <th>Invoice Amount</th>
                        <th>Invoice</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        $result = pg_query("SELECT * FROM community_invoices WHERE reserve_expense='t' AND community_id=$community_id AND invoice_date>='$ryear-01-01' AND invoice_date<='$ryear-12-31'");

                        while ($row = pg_fetch_assoc($result)) 
                        {

                          $invoice_date = $row['invoice_date'];
                          $vendor_id = $row['vendor_id'];
                          $work_status = $row['work_status'];
                          $invoice_amount = $row['invoice_amount'];
                          $payment_status = $row['payment_status'];
                          $invoice_id = $row['invoice_id'];

                          if($invoice_date != '')
                            $invoice_date = date('m-d-Y', strtotime($invoice_date));

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM vendor_master WHERE vendor_id=$vendor_id"));
                          $vendor_name = $row1['vendor_name'];

                          if($invoice_amount != '')
                            $invoice_amount = "$ ".$invoice_amount;

                          echo "<tr><td>$invoice_date</td><td>$vendor_name";

                          if($mode == 1)
                            echo " ($vendor_id)";

                          echo "</td><td>$work_status</td><td>$payment_status</td><td>$invoice_amount</td><td>$invoice_id</td></tr>";

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Invoice Date</th>
                        <th>Vendor Name (Vendor ID)</th>
                        <th>Work Status</th>
                        <th>Payment Status</th>
                        <th>Invoice Amount</th>
                        <th>Invoice</th>

                      </tr>

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
    <script src="plugins/select2/select2.full.min.js"></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50 });
      });
    </script>

  </body>

</html>