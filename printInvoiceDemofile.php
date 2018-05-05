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

  ?>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?php echo $_GET['name']; ?> | Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body onload="window.print();">
<div class="wrapper">

  <?php

          $year = date("Y");
          $month = date("m");
          $end_date = date("t");

          $home_id = $_GET['home_id'];
          $hoa_id = $_GET['hoa_id'];

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

          $property = $row['address1'];
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
  <!-- Main content -->
  <section class="invoice">
    <div class="row invoice-info">
        
                <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6'>
                      
                  From : 

                  <address style="font-size: 14pt;">
                        
                    <?php echo "<strong>".$c_name."</strong><br>".$pobox."<br>".$city.", ".$state." ".$zip; ?>

                  </address>

                </div>

                <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6 text-right'>
                      
                  <span><strong>HOA Account Number : </strong><?php echo $hoa_id; ?></span><br>
                  <span><strong>Invoice Date : </strong><?php echo date("m-d-y", strtotime($adate)); ?></span><br>
                  <span><strong>Due Date : </strong><?php if(date("m", strtotime($adate)) == date("m"))$month = date("m"); else if(date("m", strtotime($adate)) < date("m")) $month = date("m")-1; else if(date("m", strtotime($adate)) > date("m")) $month = date("m")+1; echo $month."-15-".date("y"); ?></span>
                      
                </div>

              </div>

              <br><br><br>

              <div class="row">

                <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6'>
                      
                  To :

                  <address style="font-size: 14pt;">
                        
                    <?php echo "<strong>".$cus_name."</strong><br>".$cus_addr."<br>".$cus_city.", ".$cus_state." ".$cus_zip; ?>

                  </address>

                </div>

                <div class='col-xl-6 col-lg-6 col-md-6 col-xs-6'>
                      
                  Property Address :

                  <address style="font-size: 14pt;">
                        
                    <?php echo "<strong>".$property."</strong>"; ?>

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

              <div class="row container-fluid">

                BillPay Address : 

                <?php echo "<strong>".$c_name."</strong>, ".$pobox.", ".$city.", ".$state." ".$zip.". EIN : ".$tax_id; ?><br>

                Send an email to <?php echo $c_email; ?> for HOA related queries. All updates will be posted at <?php echo $c_website; ?>

              </div>
    
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>