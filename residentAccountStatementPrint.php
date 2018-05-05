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
  <title><?php echo $_SESSION['hoa_username']; ?> | Account Statement</title>
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

          $home_id = $_SESSION['hoa_home_id'];
          $hoa_id = $_SESSION['hoa_hoa_id'];
          $year = date("Y");
          $month = date("m");
          $end_date = date("t");

          $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND hoa_id=$hoa_id AND home_id=$home_id"));

          $total_payments = $row['sum'];

          $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE community_id=$community_id AND hoa_id=$hoa_id AND home_id=$home_id"));

          $total_charges = $row['sum'];

          $balance = ( $total_charges - $total_payments );

          $row = pg_fetch_assoc(pg_query("SELECT * FROM community_info WHERE community_id=$community_id"));

          $legal_name = $row['legal_name'];
          $tax_id = $row['taxid'];
          $payment_address = $row['remit_payment_address'];
          $community_website_url = $row['community_website_url'];
          $payment_city = $row['payment_city'];
          $payment_state = $row['payment_addr_state'];
          $payment_zip = $row['payment_addr_zip'];
          $payment_email = $row['email'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$payment_city"));

          $payment_city = $row['city_name'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$payment_state"));

          $payment_state = $row['state_code'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$payment_zip"));

          $payment_zip = $row['zip_code'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE community_id=$community_id AND hoa_id=$hoa_id AND home_id=$home_id"));

          $name = $row['firstname'];
          $name .= " ";
          $name .= $row['lastname'];
          $email = $row['email'];
          $phone = $row['cell_no'];

          if($email == "")
            $email = "N/A";

          if($phone == "")
            $phone = "N/A";

          $row = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE community_id=$community_id AND home_id=$home_id"));

          $address = $row['address1'];
          $living_status = $row['living_status'];
          $city = $row['city_id'];
          $state = $row['state_id'];
          $zip = $row['zip_id'];

          if(!$living_status)
          {

            $row = pg_fetch_assoc(pg_query("SELECT * FROM home_mailing_address WHERE community_id=$community_id AND home_id=$home_id"));

            $address = $row['address1'];
            $city = $row['city_id'];
            $state = $row['state_id'];
            $zip = $row['zip_id'];

          }

          $row = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$city"));

          $city = $row['city_name'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$state"));

          $state = $row['state_code'];

          $row = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$zip"));

          $zip = $row['zip_code'];

        ?>
  <!-- Main content -->
  <section class="invoice">
    <div class="row container-fluid invoice-info">
        
                Statement Of

                <address>
                  <strong><?php echo $name; ?></strong><br>
                  <?php echo $address; ?><br>
                  <?php echo $city; ?>, <?php echo $state; ?> <?php echo $zip; ?><br>
                  Phone : <?php echo $phone; ?><br>
                  Email : <?php echo $email; ?>
                </address>

              </div>
    <!-- /.row -->

    <!-- Table row -->
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

                            echo "<tr><td>".date('F', strtotime($tdate))."</td><td>".$charges_row['id']."-".$hoa_id."".$home_id."-".$home_id."-".$charges_row['assessment_rule_type_id']."</td><td>".date("m-d-y", strtotime($tdate))."|".$desc."</td><td>$ ".$charges_row['amount']."</td><td></td><td>$ ".$month_charge."</td></tr>";

                          }

                          $month_payment = 0.0;

                          while($payments_row = pg_fetch_assoc($payments_results))
                          {

                            $month_payment += $payments_row['amount'];
                            $tdate = $payments_row['process_date'];

                            echo "<tr><td>".date('F', strtotime($tdate))."</td><td>".$payments_row['id']."-".$hoa_id."".$home_id."-".$home_id."-".$payments_row['payment_type_id']."</td><td>".date("m-d-y", strtotime($tdate))."|"."Payment Received # ".$payments_row['document_num']."</td><td></td><td>$ ".$payments_row['amount']."</td><td>$ ".$month_payment."</td></tr>";

                          }

                        }

                      ?>

                      <tr><td></td><td></td><td><strong>Total</strong></td><td><?php $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id")); $total_charges = $row['sum']; echo "$ ".$total_charges; ?></td><td><?php $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND payment_status_id=1")); $total_payments = $row['sum']; echo "$ ".$total_payments; ?></td><td><?php $total = $total_charges - $total_payments; echo "$ ".$total; ?></td></tr>

                    </tbody>
          
                  </table>
        
                </div>

              </div>
    
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>