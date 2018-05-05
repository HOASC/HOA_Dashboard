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

          <h1><strong>HOA &amp; Home Info</strong></h1>

          <ol class="breadcrumb">
            
            <?php if($mode == 1) echo "<li><i class='fa fa-street-view'></i> Users</li>"; ?>

            <li>HOA &amp; Home Info</li>
          
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Living In</th>
                        <th>Balance</th>
                        <th>Mailing Address</th>
                        <th>Pay Method</th>
                        <th>Active Reminder</th>
                        <th>Recurring Pay</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        $result = pg_query("SELECT * FROM hoaid WHERE community_id=$community_id");

                        while($row = pg_fetch_assoc($result))
                        {

                          $hoa_id = $row['hoa_id'];
                          $firstname = $row['firstname'];
                          $lastname = $row['lastname'];
                          $valid_from = $row['valid_from'];
                          $valid_until = $row['valid_until'];
                          $email = $row['email'];
                          $cell_no = $row['cell_no'];
                          $home_id = $row['home_id'];

                          if($cell_no != '')
                            $cell_no = base64_decode($cell_no);

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));

                          $address = $row1['address1'];
                          $living_status = $row1['living_status'];

                          if($living_status)
                          {
                            $mailing_address = $row1['address1'];
                            $mailing_city = $row1['city_id'];
                            $mailing_state = $row1['state_id'];
                            $mailing_zip = $row1['zip_id'];
                          }
                          else
                          {
                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id"));

                            $mailing_address = $row1['address1'];
                            $mailing_city = $row1['city_id'];
                            $mailing_state = $row1['state_id'];
                            $mailing_zip = $row1['zip_id'];
                          }

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$mailing_city"));
                          $mailing_city = $row1['city_name'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$mailing_state"));
                          $mailing_state = $row1['state_code'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$mailing_zip"));
                          $mailing_zip = $row1['zip_code'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id"));
                          $charges = $row1['sum'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND home_id=$home_id AND hoa_id=$hoa_id"));
                          $payments = $row1['sum'];

                          $balance = $charges - $payments;

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM home_pay_method WHERE home_id=$home_id"));
                          $home_pay_method = $row1['payment_type_id'];
                          $recurring_pay = $row1['recurring_pay'];

                          if($recurring_pay == 't')
                            $recurring_pay = "TRUE";
                          else
                            $recurring_pay = "FALSE";

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$home_pay_method"));
                          $home_pay_method = $row1['payment_type_name'];

                          $reminders = pg_num_rows(pg_query("SELECT * FROM reminders WHERE home_id=$home_id AND hoa_id=$hoa_id"));
                          
                          if($reminders == 0)
                            $reminders = "FALSE";
                          else
                            $reminders = "TRUE";

                          echo "<div class='modal fade hmodal-success' id='HOAInfo_".$hoa_id."' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                              
                              <div class='modal-content'>
                                                  
                                <div class='color-line'></div>
                                  
                                  <div class='modal-header'>
                                                          
                                    <h4 class='modal-title'>HOA Info - ".$firstname." ".$lastname." - ".$hoa_id."</h4>

                                  </div>

                                  <form class='row' method='post' action='https://hoaboardtime.com/boardEditHOAID2.php'>
                                                      
                                    <div class='modal-body'>
                                        
                                        <div class='row container-fluid'>
                                
                                          <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                            <label>First Name</label>
                                            <input type='text' class='form-control' name='edit_firstname' id='edit_firstname' value='$firstname' readonly>
                                          </div>
                                                
                                          <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                            <label>Last Name</label>
                                            <input type='text' class='form-control' name='edit_lastname' id='edit_lastname' value='$lastname' readonly>
                                          </div>

                                        </div>

                                        <br>

                                        <div class='row container-fluid'>
                                          
                                          <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                            <label>Phone</label>
                                            <input type='number' class='form-control' name='edit_cell_no' id='edit_cell_no' value='$cell_no' required>
                                          </div>
                                              
                                          <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                            <label>Email</label>
                                            <input type='email' class='form-control' name='edit_email' id='edit_email' value='$email' required>
                                          </div>

                                        </div>

                                        <br>

                                        <div class='row container-fluid'>
                                          
                                          <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                            <label>Resident Since</label>
                                            <input type='date' class='form-control' name='edit_valid_from' id='edit_valid_from' value='$valid_from' required>
                                          </div>
                                              
                                          <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                            <label>Resident Until</label>
                                            <input type='date' class='form-control' name='edit_valid_until' id='edit_valid_until' value='$valid_until' >

                                            <input type='hidden' name='hoa_id' id='hoa_id' value='$hoa_id'>
                                          </div>

                                        </div>

                                        <br><br>

                                        <center>
                                        <button type='submit' name='submit' id='submit' class='btn btn-success btn-xs'><i class='fa fa-check'></i>Update</button>
                                        <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'><i class='fa fa-close'></i>Cancel</button>
                                        </center>

                                    </div>

                                  </form>

                                </div>
                            
                              </div>

                            </div>";

                          echo "<div class='modal fade hmodal-success' id='paymentInfo_".$hoa_id."' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                              
                              <div class='modal-content'>
                                                  
                                <div class='color-line'></div>
                                  
                                  <div class='modal-header'>
                                                          
                                    <h4 class='modal-title'>Payment Info - ".$firstname." ".$lastname."</h4>

                                  </div>

                                  <div class='modal-body'>
                                                          
                                    <center>
                                        
                                      <div class='row container-fluid'>
                                          
                                        <div class='col-xl-3 col-lg-3 col-md-3 col-md-3 col-xs-3'><strong>Date</strong></div>
                                        <div class='col-xl-3 col-lg-3 col-md-3 col-md-3 col-xs-3'><strong>Confirmation</strong></div>
                                        <div class='col-xl-3 col-lg-3 col-md-3 col-md-3 col-xs-3'><strong>Payment Method</strong></div>
                                        <div class='col-xl-3 col-lg-3 col-md-3 col-md-3 col-xs-3'><strong>Amount</strong></div>

                                      </div>

                                      <br>";

                                      $result1 = pg_query("SELECT * FROM current_payments WHERE payment_status_id=1 AND home_id=$home_id AND hoa_id=$hoa_id");

                                      while($row1 = pg_fetch_assoc($result1))
                                      {

                                        $process_date = $row1['process_date'];
                                        $confirmation = $row1['document_num'];
                                        $pay_method = $row1['payment_type_id'];
                                        $amount = $row1['amount'];

                                        echo "<div class='row container-fluid'>
                                          
                                        <div class='col-xl-3 col-lg-3 col-md-3 col-md-3 col-xs-3'>".$process_date."</div>
                                        <div class='col-xl-3 col-lg-3 col-md-3 col-md-3 col-xs-3'>".$confirmation."</div>
                                        <div class='col-xl-3 col-lg-3 col-md-3 col-md-3 col-xs-3'>".$pay_method."</div>
                                        <div class='col-xl-3 col-lg-3 col-md-3 col-md-3 col-xs-3'>$ ".$amount."</div>

                                        </div>";

                                      }

                                      echo "<br>

                                      <div class='row container-fluid'>
                                          
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M1</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M2</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M3</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M4</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M5</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M6</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M7</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M8</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M9</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M10</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M11</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>M12</strong></div>

                                      </div>";

                                      $row1 = pg_fetch_assoc(pg_query("SELECT * FROM current_year_payments_processed WHERE hoa_id=$hoa_id AND home_id=$home_id AND year=$year"));

                                      $m1 = $row1['m1_pmt_processed'];
                                      $m2 = $row1['m2_pmt_processed'];
                                      $m3 = $row1['m3_pmt_processed'];
                                      $m4 = $row1['m4_pmt_processed'];
                                      $m5 = $row1['m5_pmt_processed'];
                                      $m6 = $row1['m6_pmt_processed'];
                                      $m7 = $row1['m7_pmt_processed'];
                                      $m8 = $row1['m8_pmt_processed'];
                                      $m9 = $row1['m9_pmt_processed'];
                                      $m10 = $row1['m10_pmt_processed'];
                                      $m11 = $row1['m11_pmt_processed'];
                                      $m12 = $row1['m12_pmt_processed'];

                                      if($m1 == 't')
                                        $m1 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m1 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m2 == 't')
                                        $m2 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m2 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m3 == 't')
                                        $m3 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m3 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m4 == 't')
                                        $m4 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m4 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m5 == 't')
                                        $m5 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m5 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m6 == 't')
                                        $m6 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m6 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m7 == 't')
                                        $m7 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m7 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m8 == 't')
                                        $m8 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m8 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m9 == 't')
                                        $m9 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m9 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m10 == 't')
                                        $m10 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m10 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m11 == 't')
                                        $m11 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m11 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      if($m12 == 't')
                                        $m12 = "<center><i class='fa fa-check-square text-success'></i></center>";
                                      else
                                        $m12 = "<center><i class='fa fa-square-o text-orange'></i></center>";

                                      echo "<div class='row container-fluid'>

                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m1."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m2."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m3."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m4."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m5."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m6."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m7."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m8."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m9."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m10."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m11."</strong></div>
                                        <div class='col-xl-1 col-lg-1 col-md-1 col-md-1 col-xs-1'><strong>".$m12."</strong></div>

                                      </div>";

                                      echo "<br><br>

                                      <a target='_blank' href='https://hoaboardtime.com/boardViewFortePayments.php?hoa_id=".$hoa_id."' class='btn btn-info btn-sm'>View Forte Payments</a>
                                      <button type='button' class='btn btn-warning btn-sm' data-dismiss='modal'>Cancel</button>
                                                          
                                  </center>

                                </div>

                              </div>
                            
                            </div>

                          </div>";

                          echo "
                          <div class='modal fade hmodal-success' id='homePayMethod_".$hoa_id."' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                              
                              <div class='modal-content'>
                                                  
                                <div class='color-line'></div>
                                  
                                  <div class='modal-header'>
                                                          
                                    <h4 class='modal-title'>Update Pay Method - ".$firstname." ".$lastname."</h4>

                                  </div>

                                  <div class='modal-body'>

                                    <form action='https://hoaboardtime.com/boardUpdateHomePayMethod.php' method='POST'>

                                      <label>Select Payment Method</label>

                                      <select name='edit_home_pay_method' id='edit_home_pay_method' class='form-control' required>

                                        <option value='' selected disabled>Select Payment Method</option>";

                                        $res = pg_query("SELECT * FROM payment_type");

                                        while ($ro = pg_fetch_assoc($res)) 
                                        {
                                         
                                          $updateId = $ro['payment_type_id'];
                                          $updateName = $ro['payment_type_name'];

                                          echo "<option value='$updateId'";

                                          if($home_pay_method == $updateName)
                                            echo " selected ";

                                          echo ">$updateName</option>";
                                        }

                                      echo "</select>

                                      <br><br>

                                      <input type='hidden' name='home_id' id='home_id' value='$home_id'>
                                      <input type='hidden' name='hoa_id' id='hoa_id' value='$hoa_id'>

                                      <center><button class='btn btn-success btn-xs' type='submit'>Update</button>
                                      <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'>Cancel</button></center>

                                      <br>

                                    </form>             
                                  
                                  </div>

                                </div>

                              </div>
                            
                            </div>

                          </div>";

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
                                          <input type='hidden' name='token' id='token' value='5'>

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

                          echo "<tr><td><a data-toggle='modal' data-target='#HOAInfo_".$hoa_id."'>".$firstname." ".$lastname."<br>(".$hoa_id.")</a></td><td>".$email."</td><td>".$cell_no."</td><td>".$address."<br>(".$home_id.")</td><td><a data-toggle='modal' data-target='#paymentInfo_".$hoa_id."'>$ ".$balance."</a></td><td>".$mailing_address."<br>".$mailing_city."<br>".$mailing_state." ".$mailing_zip."</td><td><a data-toggle='modal' data-target='#homePayMethod_".$hoa_id."'>".$home_pay_method."</a></td><td>".$reminders."</td><td>".$recurring_pay."</td></tr>";

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Living In</th>
                        <th>Balance</th>
                        <th>Mailing Address</th>
                        <th>Pay Method</th>
                        <th>Active Reminder</th>
                        <th>Recurring Pay</th>

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

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50, "order": [[1, "asc"]] });
      });
    </script>

  </body>

</html>