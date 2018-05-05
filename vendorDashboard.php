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

          $result = pg_query("SELECT * FROM community_invoices WHERE community_id=$community_id AND reserve_expense='t'");

          $today = date('Y-m-d');

        ?>
        
        <section class="content-header">

          <h1><strong>Vendor Dashboard</strong></h1>

          <ol class="breadcrumb">
            
            <li><i class='fa fa-wrench'></i> Vendor Dashboard</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-xl-offset-2 col-lg-offset-2 col-md-offset-2 col-lg-8 col-xl-8 col-md-8 col-sm-12 col-xs-12">

              <div class="box">

                <div class="box-body table-responsive">
                  
                  <center>

                    <div class="box-header with-border">
              
                      <h3 class="box-title"><?php echo $_SESSION['hoa_community_name']; ?> Vendors</h3>
            
                    </div>

                    <div class="box-body">
                      
                      <table id='example1' class="table table-bordered">

                        <thead>

                          <th>Vendor Name</th>
                          <th>Description</th>

                        </thead>

                        <tbody>

                          <?php

                            $i = 1;

                            $result = pg_query("SELECT * FROM vendor_master WHERE community_id=$community_id");

                            while($row = pg_fetch_assoc($result))
                            {
                              $vendor_id = $row['vendor_id'];
                              $vendor_name = $row['vendor_name'];
                              $evendor_id = base64_encode($vendor_id);

                              $row1 = pg_fetch_assoc(pg_query("SELECT * FROM vendor_pay_method WHERE vendor_id=$vendor_id"));

                              $vendor_description = $row1['desc'];

                              echo "<tr><td><a href='https://hoaboardtime.com/vendorDashboard2.php?select_vendor=$evendor_id' title='Vendor Dashboard'>".$vendor_name."</a></td><td>".$vendor_description."</td></tr>";
                            }

                          ?>
                        
                        </tbody>
                        
                      </table>
                    
                    </div>

                  </center>

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
        $("#example1").DataTable({ "pageLength": 100 });
      });
    </script>

  </body>

</html>