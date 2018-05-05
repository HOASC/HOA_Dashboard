
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

          $result = pg_query("SELECT * FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'");

        ?>
        
        <section class="content-header">

          <h1><strong>Home Payment Information</strong></h1>

          <ol class="breadcrumb">
            
            <li><a href='boardDashboard.php'><i class="fa fa-dashboard"></i> Board Dashboard</a></li>
            <li>Home Payment Information</li>
          
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
                        
                        <th>Name</th>
                        <th>Living In</th>
                        <th>Recurring Pay</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Continous</th>
                        <th>Expires On</th>
                        <th>Next Schedule Date</th>
                        <th>Frequence</th>
                        <th>Balance</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php

                        $res = pg_query("SELECT * FROM home_pay_method WHERE community_id=$community_id AND payment_type_id=1");

                        if($res)
                        {

                          while($row = pg_fetch_assoc($res))
                          {
                            
                            $hoa_id = $row['hoa_id'];
                            $home_id = $row['home_id'];
                            $recurring_pay = $row['recurring_pay'];
                            $sch_start = $row['sch_start'];
                            $sch_end = $row['sch_end'];
                            $continous = $row['continous'];
                            $sch_expires = $row['sch_expires'];
                            $next_sch = $row['next_sch'];
                            $sch_frequency = $row['sch_frequency'];

                            $ehoa_id = base64_encode($hoa_id);

                            if($recurring_pay == 't')
                              $recurring_pay = 'Enabled';
                            else
                              $recurring_pay = 'Not Set';

                            if($continous == 't')
                              $continous = 'TRUE';
                            else
                              $continous = 'FALSE';

                            if($sch_start != "")
                              $sch_start = date('m-d-Y', strtotime($sch_start));

                            if($sch_end != "")
                              $sch_end = date('m-d-Y', strtotime($sch_end));

                            if($sch_expires != "")
                              $sch_expires = date('m-d-Y', strtotime($sch_expires));

                            if($next_sch != "")
                              $next_sch = date('m-d-Y', strtotime($next_sch));

                            if($hoa_id == "")
                            {

                              $row1 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE home_id=$home_id"));

                              $hoa_id = $row1['hoa_id'];

                            }

                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));

                            $address = $row1['address1'];
                            $living_status = $row1['living_status'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$hoa_id"));

                            $name = $row1['firstname'];
                            $name .= " ";
                            $name .= $row1['lastname'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE hoa_id=$hoa_id AND home_id=$home_id"));

                            $total_charges = $row1['sum'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE hoa_id=$hoa_id AND home_id=$home_id AND payment_status_id=1"));

                            $total_payments = $row1['sum'];

                            if($total_payments == '')
                              $total_payments = 0.0;

                            $balance = $total_charges - $total_payments;

                            echo "<tr";

                            if($living_status != 't')
                              echo " class='text-red' ";

                            echo "><td><a href='userDashboard2.php?hoa_id=$ehoa_id'>$name<br>($hoa_id)</a></td><td>$address<br>($home_id)</td><td>$recurring_pay</td><td>$sch_start</td><td>$sch_end</td><td>$continous</td><td>$sch_expires</td><td>$next_sch</td><td>$sch_frequency</td><td>$ $balance</td></tr>";

                          }

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Name</th>
                        <th>Living In</th>
                        <th>Recurring Pay</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Continous</th>
                        <th>Expires On</th>
                        <th>Next Schedule Date</th>
                        <th>Frequence</th>
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