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
      include 'includes/globalvar.php';

      $mode = $_SESSION['hoa_mode'];

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="dist/js/googleanalytics.js"></script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

      <?php if($mode == 1) include 'boardHeader.php'; else if($mode == 2) include 'residentHeader.php'; ?>
      
      <?php if($mode == 1) include 'boardNavigationMenu.php'; else if($mode == 2) include 'residentNavigationMenu.php'; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

        	$year = date("Y");
        	$month = date("m");
        	$end_date = date("t");

        ?>
        
        <section class="content-header">

          <h1><strong>Reserves Dashboard</strong><small> - 2017</small></h1>

          <ol class="breadcrumb">
            
            <li><i class="fa fa-support"></i> Reserves Dashboard</li>
          
          </ol>

        </section>

        <section class="content">

          <div class="row container-fluid" style="background-color: #ffffff;">

            <br>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $depreciation = pg_fetch_assoc(pg_query("SELECT * FROM community_reserves WHERE community_id=$community_id AND year=2017"));

                      $depreciation = $depreciation['depreciation'];

                      $depreciation = round($depreciation, 0);

                      echo "<h1 class='text-info'><strong>$ ".$depreciation."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Deterioration Cost / Yr</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $assets = pg_num_rows(pg_query("SELECT * FROM community_assets WHERE community_id=$community_id AND year=2017"));

                      if($assets > 0)
                        echo "<h1 class='text-green'><strong><a class='text-green' href='viewCommunityAssets.php?year=2017'>$assets</a></strong></h1>";
                      else
                        echo "<h1 class='text-info'><strong>".$assets."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Assets</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $row = pg_fetch_assoc(pg_query("SELECT sum(invoice_amount) FROM community_invoices WHERE reserve_expense='t' AND community_id=$community_id AND invoice_date>='2017-01-01' AND invoice_date<='2017-12-31'"));

                      $repairs = $row['sum'];

                      $repairs = round($repairs, 0);

                      if($repairs > 0)
                        echo "<h1 class='text-green'><strong><a class='text-green' href='reserveRepairs.php?year=2017'>$ $repairs</a></strong></h1>";
                      else
                        echo "<h1 class='text-info'><strong>$ ".$repairs."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Completed Repairs</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $result = pg_query("SELECT * FROM community_reserves WHERE community_id=$community_id AND year=2017");

                      $result = pg_fetch_assoc($result);

                      $isb = $result['ideal_start_bal'];
                      $bb = $result['begin_bal'];
                      $tu = $result['total_units'];

                      $result = ($isb - $bb) / $tu;

                      $result = round($result, 0);

                      echo "<h1 class='text-red'><strong>$ ".$result."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Deficit Per Home</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      echo "<h1 class='text-red'><strong>$ ".($result * $tu)."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Total Deficit</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $row = pg_fetch_assoc(pg_query("SELECT * FROM community_reserves WHERE community_id=$community_id AND year=2017"));

                      $cur_bal_vs_ideal_bal = $row['cur_bal_vs_ideal_bal'];

                      echo "<h1 class='text-orange'><strong>".$cur_bal_vs_ideal_bal." %</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Reserves Funded</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $year = date('Y');
                      $month = date('m');

                      $row = pg_fetch_assoc(pg_query("SELECT * FROM community_reserves WHERE community_id=$community_id AND year=2017"));

                      $recommended_monthly_allocation_units = $row['rec_mthly_alloc_unit'];

                      $reserve_allocation = $recommended_monthly_allocation_units * $month;

                      $reserve_allocation = round($reserve_allocation, 0);

                      if($cur_bal_vs_ideal_bal >= 70)
                        echo "<h1 class='text-green'><strong>".$reserve_allocation."</strong></h1>";
                      else
                        echo "<h1 class='text-orange'><strong>".$reserve_allocation."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>YTD Allocation</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <br>

          </div>

        </section>

        <section class="content-header">

          <h1><strong>Reserves Dashboard</strong><small> - 2018</small></h1>

        </section>

        <section class="content">

          <div class="row container-fluid" style="background-color: #ffffff;">

            <br>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $depreciation = pg_fetch_assoc(pg_query("SELECT * FROM community_reserves WHERE community_id=$community_id AND year=2018"));

                      $depreciation = $depreciation['depreciation'];

                      $depreciation = round($depreciation, 0);

                      echo "<h1 class='text-info'><strong>$ ".$depreciation."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Deterioration Cost / Yr</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $assets = pg_num_rows(pg_query("SELECT * FROM community_assets WHERE community_id=$community_id AND year=2018"));

                      if($assets > 0)
                        echo "<h1 class='text-green'><strong><a class='text-green' href='viewCommunityAssets.php?year=2018'>$assets</a></strong></h1>";
                      else
                        echo "<h1 class='text-info'><strong>".$assets."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Assets</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $row = pg_fetch_assoc(pg_query("SELECT sum(invoice_amount) FROM community_invoices WHERE reserve_expense='t' AND community_id=$community_id AND invoice_date>='2018-01-01' AND invoice_date<='2018-12-31'"));

                      $repairs = $row['sum'];

                      $repairs = round($repairs, 0);

                      if($assets > 0)
                        echo "<h1 class='text-green'><strong><a class='text-green' href='reserveRepairs.php?year=2018'>$ $repairs</a></strong></h1>";
                      else
                        echo "<h1 class='text-info'><strong>$ ".$repairs."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Projected Repairs</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $result = pg_query("SELECT * FROM community_reserves WHERE community_id=$community_id AND year=2018");

                      $result = pg_fetch_assoc($result);

                      $isb = $result['ideal_start_bal'];
                      $bb = $result['begin_bal'];
                      $tu = $result['total_units'];

                      $result = ($isb - $bb) / $tu;

                      $result = round($result, 0);

                      echo "<h1 class='text-red'><strong>$ ".$result."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Deficit Per Home</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      echo "<h1 class='text-red'><strong>$ ".($result * $tu)."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Total Deficit</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $row = pg_fetch_assoc(pg_query("SELECT * FROM community_reserves WHERE community_id=$community_id AND year=2018"));

                      $cur_bal_vs_ideal_bal = $row['cur_bal_vs_ideal_bal'];

                      echo "<h1 class='text-orange'><strong>".$cur_bal_vs_ideal_bal." %</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Reserves Funded</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid text-left">

                <br>

                <div class="row container-fluid">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php 

                      $year = date('Y');
                      $month = date('m');

                      $row = pg_fetch_assoc(pg_query("SELECT * FROM community_reserves WHERE community_id=$community_id AND year=2018"));

                      $recommended_monthly_allocation_units = $row['rec_mthly_alloc_unit'];

                      $reserve_allocation = $recommended_monthly_allocation_units * $month;

                      $reserve_allocation = round($reserve_allocation, 0);

                      if($cur_bal_vs_ideal_bal >= 70)
                        echo "<h1 class='text-green'><strong>".$reserve_allocation."</strong></h1>";
                      else
                        echo "<h1 class='text-orange'><strong>".$reserve_allocation."</strong></h1>";

                    ?>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>YTD Allocation</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <br>

          </div>

        </section>

      </div>

      <?php include "footer.php"; ?>

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
