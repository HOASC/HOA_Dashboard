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

          <h1><strong>Delinquent Accounts</strong><small> - <?php echo date("F").", ".$year; ?></small></h1>

          <ol class="breadcrumb">
            
            <li><a href='boardDashboard.php'><i class="fa fa-dashboard"></i> Board Dashboard</a></li>
            <li>Delinquent Accounts</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-xl-offset-2 col-lg-offset-2 col-md-offset-1 col-xl-8 col-lg-8 col-md-10 col-sm-12 col-xs-12">
              
              <div class="row container-fluid">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                  <div class="alert alert-info alert-dismissible">
                
                    <form method="POST" action="delinquentAccounts.php">
                      
                      <center>

                      <?php

                        if( isset($_POST['submit']) )
                        {
                          
                          if( $_POST['due'] == 1 )
                          {

                            echo "Show customers with due ";

                            echo "<input type='radio' checked name='due' id='due' value='1'> 30 Days <input type='radio' name='due' id='due' value='2'> 60 Days <input type='radio' name='due' id='due' value='3'> 90 Days <br><br><input class='btn btn-warning' type='submit' name='submit' id='submit' value='Show'>"; 

                            $del = 1;

                          }
                          else if( $_POST['due'] == 2 )
                          {

                            echo "Show customers with due ";

                            echo "<input type='radio' name='due' id='due' value='1'> 30 Days <input type='radio' checked name='due' id='due' value='2'> 60 Days <input type='radio' name='due' id='due' value='3'> 90 Days <br><br><input class='btn btn-warning' type='submit' name='submit' id='submit' value='Show'>"; 

                            $del = 2;

                          }
                          else
                          {

                            echo "Show customers with due ";

                            echo "<input type='radio' name='due' id='due' value='1'> 30 Days <input type='radio' name='due' id='due' value='2'> 60 Days <input type='radio' name='due' id='due' checked value='3'> 90 Days <br><br><input class='btn btn-warning' type='submit' name='submit' id='submit' value='Show'>"; 

                            $del = 3;

                          }

                        }
                        else
                        {
                          
                          echo "Show customers with due ";

                          echo "<input type='radio' name='due' id='due' value='1'> 30 Days <input type='radio' name='due' id='due' value='2'> 60 Days <input type='radio' name='due' checked id='due' value='3'> 90 Days <br><br><input class='btn btn-warning' type='submit' name='submit' id='submit' value='Show'>"; 

                          $del = 3;

                        }

                      ?>

                      </center>
                    
                    </form>
                      
                  </div>

                </div>

              </div>

            </section>

          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Name</th>
                        <th>Property Address</th>
                        <th>Total Due</th>
                        <th>Phone</th>
                        <th>Email</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        $result = pg_query("SELECT amount FROM assessment_amounts WHERE community_id=$community_id");
                        $row = pg_fetch_assoc($result);

                        $assessment_amount = $row['amount'];

                        $del_amount = $assessment_amount * $del;

                        $result = pg_query("SELECT home_id, sum(amount) FROM current_charges WHERE assessment_rule_type_id=1 AND community_id=$community_id GROUP BY home_id ORDER BY home_id");

                        while($row = pg_fetch_assoc($result))
                        {

                          $home_id = $row['home_id'];
                          $assessment_charges = $row['sum'];

                          $query2 = "SELECT hoa_id, firstname, lastname, cell_no, email FROM hoaid WHERE home_id=".$home_id;
                          $result2 = pg_query($query2);
                          $row2 = pg_fetch_assoc($result2);

                          $firstname = $row2['firstname'];
                          $lastname = $row2['lastname'];
                          $hoa_id = $row2['hoa_id'];
                          $cell_no = $row2['cell_no'];
                          $email = $row2['email'];

                          $query2 = "SELECT sum(amount) FROM current_charges WHERE hoa_id=".$hoa_id;
                          $result2 = pg_query($query2);
                          $row2 = pg_fetch_assoc($result2);
                          $charges = $row2['sum'];

                          $query2 = "SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND hoa_id=".$hoa_id;
                          $result2 = pg_query($query2);
                          $row2 = pg_fetch_assoc($result2);
                          $payments = $row2['sum'];

                          $balance = $charges - $payments;

                          $query2 = "SELECT address1 FROM homeid WHERE home_id=".$home_id;
                          $result2 = pg_query($query2);
                          $row2 = pg_fetch_assoc($result2);
                          $address1 = $row2['address1'];

                          if($email != '')
                          {

                            $aux_email = $email;
                                    
                            $arr = array();
                            $arr = explode('@', $email);
                            $email = $arr[0];
                            $i = strlen($email);

                            for($j = 3; $j < $i; $j++)
                              $email[$j] = '*';

                            $email = $email.'@'.$arr[1];

                            echo "<div class='modal fade hmodal-success' id='send_email_".$hoa_id."' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                              
                              <div class='modal-content'>
                                                  
                                <div class='color-line'></div>
                                  
                                  <div class='modal-header'>
                                                          
                                    <h4 class='modal-title'>Send Email to ".$name." - ".$email."</h4>

                                  </div>

                                  <form class='row' method='post' action='https://hoaboardtime.com/sendEmailToCustomer.php'>
                                                      
                                    <div class='modal-body'>
                                        
                                        <div class='row container-fluid'>
                                
                                          <label>Subject</label>
                                          <input class='form-control' type='text' name='mail_subject' id='mail_subject' required placeholder='Enter Mail Subject'>

                                        </div>

                                        <br>

                                        <div class='row container-fluid'>
                                          
                                          <label>Message</label>
                                          <textarea class='form-control' name='mail_body' id='mail_body' required placeholder='Enter Email Body'></textarea>

                                          <input type='hidden' name='mail_email' id='mail_email' value='$aux_email'>
                                          <input type='hidden' name='token' id='token' value='3'>

                                        </div>

                                        <br><br>

                                        <center>
                                        <button type='submit' name='submit' id='submit' class='btn btn-success btn-xs'><i class='fa fa-check'></i>Send Email</button>
                                        <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'><i class='fa fa-close'></i>Close</button>
                                        </center>

                                    </div>

                                  </form>

                                </div>
                            
                              </div>

                            </div>";

                            $email = "<a data-toggle='modal' data-target='#send_email_".$hoa_id."'>".$email."</a>";

                          }

                          if($del_amount <= ($assessment_charges - $payments) && $balance >= $del_amount)
                            echo "<tr><td><a title='User Dashboard' href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>".$firstname." ".$lastname."</a></td><td> ".$address1."</td><td>$ ".$balance."</td><td>".$cell_no."</td><td>".$email."</td></tr>";

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Name</th>
                        <th>Property Address</th>
                        <th>Total Due</th>
                        <th>Phone</th>
                        <th>Email</th>

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