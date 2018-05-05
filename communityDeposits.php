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

      <?php if($mode == 1) include "boardHeader.php"; else include "residentHeader.php"; ?>
      
      <?php if($mode == 1) include 'boardNavigationMenu.php'; else include "residentNavigationMenu.php"; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

            $year = date("Y");
            $month = date("m");
            $end_date = date("t");

          $result = pg_query("SELECT * FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'");

        ?>
        
        <section class="content-header">

          <h1><strong>Community Deposits</strong><small> - <?php echo date("F").", ".$year; ?></small></h1>

          <?php

          if($mode == 1)

            echo "<ol class='breadcrumb'>
            
                <li><a href='boardDashboard.php'><i class='fa fa-dashboard'></i> Board Dashboard</a></li>
                <li>Community Deposits</li>
              
              </ol>";

          ?>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
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

                            if($community_id == 2)
                            {

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

                                                            <strong>";

                                                                if($mode == 1)
                                                                    echo "<div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>Name<br>(HOA ID)</div>
                                                                        <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>Address<br>(Home ID)</div>
                                                                        <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>ID</div>
                                                                        <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>Status</div>
                                                                        <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>Amount</div>
                                                                        <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>Received Date</div>";
                                                                else
                                                                    echo "<div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>ID</div>
                                                                        <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>Status</div>
                                                                        <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>Amount</div>
                                                                        <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>Received Date</div>";

                                                            echo "</strong>

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

                                                                $row11 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$funding_hoa_id"));

                                                                $name = $row11['firstname'];
                                                                $name .= " ";
                                                                $name .= $row11['lastname'];
                                                                $t_home_id = $row11['home_id'];

                                                                $row11 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$t_home_id"));

                                                                $address = $row11['address1'];
                                                                $living_status = $row11['living_status'];

                                                                if($name != " " && $address != "")
                                                                {
                                                    
                                                                    $name = "$name<br>($funding_hoa_id)";
                                                                    $address = "$address<br>($t_home_id)";

                                                                }
                                                                else
                                                                {

                                                                    echo "

                                                                    <div class='modal fade hmodal-success' id='addHOAID_$id1_$id' role='dialog'  aria-hidden='true'>
                                        
                                                                        <div class='modal-dialog'>
                                                                        
                                                                            <div class='modal-content'>

                                                                                <div class='modal-header table-responsive'>

                                                                                    <h4>Add Hoa ID - <strong>$id</strong></h4>

                                                                                </div>

                                                                                <div class='modal-body table-responsive'>

                                                                                    <form method='POST' action='https://hoaboardtime.com/boardEditDepositsHOAID.php'>
                                                            
                                                                                        <center>

                                                                                        <select class='form-control select2' name='select_hoa' id='select_hoa' style='width: 100%;' required>

                                                                                            <option value='' disabled selected>Select User</option>";

                                                                                            $result000 = pg_query("SELECT * FROM homeid WHERE community_id=$community_id");

                                                                                            while($row000 = pg_fetch_assoc($result000))
                                                                                            {

                                                                                                $add_home_id = $row000['home_id'];
                                                                                                $add_address1 = $row000['address1'];
                                                                                              
                                                                                                $row111 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE home_id=$add_home_id"));

                                                                                                $add_name = $row111['firstname'];
                                                                                                $add_name .= " ";
                                                                                                $add_name .= $row111['lastname'];
                                                                                                $add_hoa_id = $row111['hoa_id'];

                                                                                                echo "<option value='".$add_hoa_id."'>".$add_name." - ".$add_address1."</option>";

                                                                                            }

                                                                                        echo "</select>

                                                                                        <input type='hidden' name='current_payments_id' id='current_payments_id' value='$id'>

                                                                                        <br><br>

                                                                                        <button class='btn btn-xs btn-info' type='submit'>Update</button>

                                                                                    </center>

                                                                                    </form>

                                                                                </div>

                                                                                <br>

                                                                            </div>
                                                      
                                                                        </div>

                                                                    </div>";//End

                                                                    $name = "<a data-toggle='modal' data-target='#addHOAID_$id1_$id' title='Add HOA ID'>N/A</a>";
                                                                    $address = "<a data-toggle='modal' data-target='#addHOAID_$id1_$id' title='Add HOA ID'>N/A</a>";

                                                                }

                                                                echo "<div class='row text-center";

                                                                if($living_status != 't')
                                                                    echo " text-red";

                                                                $et_hoa_id = base64_encode($t_hoa_id);

                                                                if($mode == 1)
                                                                    echo "'><div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'><a href='userDashboard2.php?hoa_id=".$et_hoa_id."' title='User Dashboard'>$name</a></div><div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>$address</div><div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>$id</div><div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>$funding_status</div><div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>$amount</div><div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2'>$received_date</div></div><br>";
                                                                else
                                                                    echo "'><div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>$id</div><div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>$funding_status</div><div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>$amount</div><div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3'>$received_date</div></div><br>";

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