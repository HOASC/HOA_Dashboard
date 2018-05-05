<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>
<html>
  <head>
	
    <?php
      date_default_timezone_set('America/Los_Angeles');

      if(@!$_SESSION['hoa_username'])
      	header("Location: https://hoaboardtime.com/logout.php");

      $community_id = $_SESSION['hoa_community_id'];
      $user_id=$_SESSION['hoa_user_id'];

      include 'includes/dbconn.php';

      $board = pg_num_rows(pg_query("SELECT * FROM board_committee_details WHERE user_id=$user_id AND community_id=$community_id"));

		  if($board == 0)
			 header("Location: https://hoaboardtime.com/residentDashboard.php");
      
      if($_SESSION['hoa_mode'] == 2)
        $_SESSION['hoa_mode'] = 1;

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

    <script type="text/javascript">
      var dimensionValue1 = '<?php echo $_SESSION['hoa_hoa_id'] ?>';
      var dimensionValue2 = "";
      if(<?php echo $community_id; ?> == 2)
        ga('create', 'UA-102881886-2', 'auto');
      ga('set', 'dimension1', dimensionValue1);
      ga('set', 'dimension2', dimensionValue2);
      ga('send', 'pageview');
    </script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

      <?php include 'boardHeader.php'; ?>
      
      <?php include 'boardNavigationMenu.php'; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

        	$year = date("Y");
        	$month = date("m");
        	$end_date = date("t");

        	$row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'"));

        	$amount_recieved = $row['sum'];

          if($amount_recieved == "")
            $amount_recieved = 0.0;

        	$row = pg_fetch_assoc(pg_query("SELECT count(hoa_id) FROM hoaid WHERE community_id=$community_id"));

        	$total_customers = $row['count'];

        	$row = pg_fetch_assoc(pg_query("SELECT amount FROM assessment_amounts WHERE community_id=$community_id   AND year=".date('Y')));

        	$assessment_amount = $row['amount'];

        	$total_amount = ( $total_customers * $assessment_amount );
        	$amount_percentage = (( $amount_recieved / $total_amount ) * 100 );

        	$paid_customers = pg_num_rows(pg_query("SELECT DISTINCT hoa_id FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'"));

        	$paid_percentage = (( $paid_customers / $total_customers) * 100 );

        	$del_acc = 0;
          $del = 3;

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

            if($del_amount <= ($assessment_charges - $payments) && $balance >= $del_amount)
              $del_acc++;

          }

          $result = pg_query("SELECT * FROM community_sign_agreements WHERE community_id=$community_id AND document_to!=';' AND agreement_status='SIGNED'");
          $signed_agreements = pg_num_rows($result);

          $result = pg_query("SELECT * FROM community_sign_agreements WHERE community_id=$community_id AND document_to!=';' AND agreement_status='OUT_FOR_SIGNATURE'");
          $pending_agreements = pg_num_rows($result);

          $inspections = 0;

          $result = pg_query("SELECT * FROM inspection_notices WHERE community_id=$community_id");

          while($row = pg_fetch_assoc($result))
          {
            $status = $row['inspection_status_id'];

            if($status != 2 && $status != 6 && $status != 9 && $status != 14 && $status != 13)
              $inspections++;
          }

          $deposits = pg_num_rows(pg_query("SELECT * FROM community_deposits WHERE community_id=$community_id"));

          $settling_customers = pg_num_rows(pg_query("SELECT * FROM current_payments WHERE community_id=$community_id AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date' AND payment_status_id=8"));

          $ress = pg_query("UPDATE reminders SET reminder_status_id=2 WHERE reminder_status_id=1 AND due_date<='".date('Y-m-d')."'");

        ?>
        
        <section class="content-header">

          <h1><strong>Board Dashboard</strong><small> - <?php echo date("F").", ".$year; ?></small></h1>

          <ol class="breadcrumb">
            
            <li><i class="fa fa-dashboard"></i> Board Dashboard</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

          	<section class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
            
	            <div class="box box-info">
	                
	                <div class="box-header">
	                  
	                  	<i class="fa fa-pie-chart"></i>

	                  	<h3 class="box-title">Payment Status</h3>
	                  
	                  	<div class="pull-right box-tools">
	                    
	                    	<button type="button" class="btn btn-info btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>

	                  	</div>
	                  
	                </div>

	                <div class="box-body container-fluid" style='text-align: justify;'>
	                  
                    <div class='row container-fluid'>
	                    
                      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-center" style="border-right: 1px solid #f4f4f4">
	                  
	                  		<a href="amountReceived.php">
	                  		 
                         <input type="text" class="knob" data-thickness="0.2" value="<?php if($amount_percentage < 100) echo round($amount_percentage, 1); else echo "100"; ?>" data-width="100" data-height="100" data-fgColor="#00c0ef" data-readonly="true">

	                  		 <div class="knob-label" style="font-size: 15pt;"><strong>Amount Received</strong><sup>( % )</sup></div>
	                  		
                        </a>

	                	  </div>

	                	  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-center">
	                  
	                  		<a href='amountReceived.php'>
	                  		
                          <input type="text" class="knob" data-thickness="0.2" value="<?php echo round($paid_percentage, 1); ?>" data-width="100" data-height="100" data-fgColor="#00c0ef" data-readonly="true">

	                  		 <div class="knob-label" style="font-size: 15pt;"><strong>Members Paid</strong><sup>( % )</sup></div>
	                  		
                        </a>

	                	  </div>

                    </div>

                    <br><br>

                    <div class='row text-center container-fluid'>

                      <a href='homePayMethod.php'>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3"  style="border: solid; border-right: none;">

                          <strong>ACH : <?php $row=pg_fetch_assoc(pg_query("SELECT count(home_id) FROM home_pay_method WHERE community_id=$community_id AND payment_type_id=1")); $ach = $row['count']; echo $ach; ?></strong>

                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3" style="border: solid; border-right: none; border-left: none;">

                          <strong>BillPay : <?php $row=pg_fetch_assoc(pg_query("SELECT count(home_id) FROM home_pay_method WHERE community_id=$community_id AND payment_type_id=2")); $billpay = $row['count']; echo $billpay; ?></strong>

                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3" style="border: solid; border-right: none; border-left: none;">

                          <strong>Check : <?php $row=pg_fetch_assoc(pg_query("SELECT count(home_id) FROM home_pay_method WHERE community_id=$community_id AND payment_type_id=3")); $check = $row['count']; echo $check; ?></strong>

                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3" style="border: solid; border-left: none;">

                          <strong>Others : <?php echo $total_customers-$ach-$billpay-$check; ?></strong>

                        </div>

                      </a>

                    </div>

	                </div>

	            </div>

            </section>

            <section class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
            
	            <div class="info-box">
                  
                <div class='row container-fluid text-center'>

                  <br>

                  <span class="info-box-text"><strong>Bank Account Balance</strong></span>

                  <br>

                </div>
                      	
                <div class="row text-center">

                  <?php 

                    if($community_id == 2)
                    {
                      
                      $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/account/33?minorversion=8');      
                      curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
                      curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1506682054",oauth_nonce="skPZikoZJCt",oauth_version="1.0",oauth_signature="aEBIdXcJdXSWiLp5k9gxlVuvsbs%3D"'));
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                      $result = curl_exec($ch);
                      $json_decode = json_decode($result,TRUE);
                      $primarySavings = $json_decode['Account'];
                      $var_primary_Savings_CurrentBalance = $primarySavings['CurrentBalance'];

                      curl_close($ch);

                      $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/account/32?minorversion=8');      
                      
                      curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
                      curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1492203509",oauth_nonce="Q2Ck7t",oauth_version="1.0",oauth_signature="5IDpz%2F%2FItyjMYbh4Ke3JoBx3YGY%3D"'));
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                      $result2 = curl_exec($ch);
                      $json_decode2 = json_decode($result2,TRUE);
                      $var = $json_decode2['Account'];
                      $var_savings = $var['CurrentBalance'];

                      curl_close($ch);

                      $ch  = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/account/31?minorversion=8');
                      
                      curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
                      curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1506681985",oauth_nonce="H7DXVHb2Qdp",oauth_version="1.0",oauth_signature="HDWt%2BfIz3NrAhJE9fO9G%2FI8Q%2Fcg%3D"'));
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                      $result3 = curl_exec($ch);
                      $json_decode3 = json_decode($result3,TRUE);
                      $s_t_a = $json_decode3['Account'];
                      $s_t_a_Balance = $s_t_a['CurrentBalance'];

                      echo "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'>Checkings</div><div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'>Savings</div><div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'>Investments</div></div><div class='row text-center'>";

                      echo "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'><strong><a href='communityIncome.php'>$ ".$var_primary_Savings_CurrentBalance."</a></strong></div><div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'><strong><a href='communityIncome.php'>$ ".$var_savings."</a></strong></div><div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'><strong><a href='communityIncome.php'>$ ".$s_t_a_Balance."</a></strong></div>";
                    }

                    $documents = pg_num_rows(pg_query("SELECT * FROM document_management WHERE community_id=$community_id"));

                  ?>

                  <br><br>
                  
                </div>

              </div>

              <div class="info-box">
                  
                <div class='row container-fluid text-center'>

                  <br>

                  <span class="info-box-text"><strong>Payment Information</strong> - <?php echo date('F').", ".date('Y'); ?></span>

                  <br>

                </div>
                        
                <div class="row text-center">

                  <?php 

                    echo "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'>Amount<br>Received</div><div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'>Paid<br>Customers</div><div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'>Settling<br>Payments</div></div><div class='row text-center'>";

                    echo "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'><strong><a href='amountReceived.php'>$ ".$amount_recieved."</a></strong></div><div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'><strong><a href='amountReceived.php'>".$paid_customers."</a></strong></div><div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4'><strong><a href='currentMonthSettlingMembers.php'>".$settling_customers."</a></strong></div>";
                  ?>

                  <br><br>
                  
                </div>

              </div>

            </section>

          </div>

          <div class="row container-fluid" style="background-color: #ffffff;">

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <div class="row container-fluid">

                <br>

                <div class="row container-fluid text-left">

                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                    <?php

                      $total_needed = $assessment_amount * 12 * $total_customers;

                      $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND community_id=$community_id WHERE year=$year"));
                      $total_received = $row['sum'];

                    ?>

                    <h1 class="text-info"><strong><?php echo round((($total_received/$total_needed)*100), 2); ?>%</strong></h1>

                  </div>

                </div>

                <div class="row container-fluid text-center">

                  <h5><strong>Amount Received (<?php echo $year; ?>)</strong></h5>

                </div>

                <br>

              </div>

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardCommunityDeposit.php'-->

                <div class="row container-fluid">

                  <br>

                  <div class="row container-fluid text-left">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='communityDeposits.php'>

                        <h1 class="text-info"><strong><?php echo $deposits; ?></strong></h1>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Community Deposits</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardCommunityDocuments.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='communityDocuments.php'>

                        <h1 class="text-info"><strong><?php echo $documents; ?></strong></h1>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Community Documents</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardDelinquentAccounts.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='delinquentAccounts.php'>

                        <?php 

                          if($del_acc == 0 || $del_acc == "")
                            echo "<h1 class='text-green'><strong>".$del_acc."</strong></h1>"; 
                          else
                            echo "<h1 class='text-red'><strong>".$del_acc."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Delinquent Accounts</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardViolationHomes.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='boardViolationHomes.php'>

                        <?php 

                          $inspection_homes = 0;
                          $res = pg_query("SELECT DISTINCT home_id FROM inspection_notices WHERE community_id=$community_id");

                          while ($r = pg_fetch_assoc($res)) 
                          {
                            $status = $r['inspection_status_id'];

                            if($status != 2 && $status != 6 && $status != 9 && $status != 14 && $status != 13)
                              $inspection_homes++;
                          }

                          if($inspection_homes > 0)
                            echo "<h1 class='text-orange'><strong>".$inspection_homes."</strong></h1>"; 
                          else
                            echo "<h1 class='text-green'><strong>".$inspection_homes."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Inspection Homes</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardViolationCitations.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='boardViolationCitations.php'>

                        <?php 

                          if($inspections > 0)
                            echo "<h1 class='text-orange'><strong>".$inspections."</strong></h1>"; 
                          else
                            echo "<h1 class='text-green'><strong>".$inspections."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Inspection Notices</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardCurrentMonthLatePayments.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='boardCurrentMonthLatePayments.php'>

                        <?php 
                          
                          $result = pg_query("SELECT distinct home_id FROM current_payments WHERE payment_status_id=1 AND community_id=".$community_id." AND process_date>='$year-$month-16' AND process_date<='$year-$month-$end_date'");
                          $late = pg_num_rows($result);

                          if($late > 0)
                            echo "<h1 class='text-orange'><strong>".$late."</strong></h1>"; 
                          else
                            echo "<h1 class='text-green'><strong>".$late."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Late Payments - <?php echo date("F"); ?></strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardParkingTags.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='boardParkingTags.php'>

                        <?php 

                          $row = pg_num_rows(pg_query("SELECT * FROM home_tags WHERE community_id=$community_id AND type=1"));

                          echo "<h1 class='text-info'><strong>".$row."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Parking Tags</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardCommunityPendingAgreements.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='boardCommunityPendingAgreements.php'>

                        <?php 

                          if($pending_agreements > 0)
                            echo "<h1 class='text-orange'><strong>".$pending_agreements."</strong></h1>"; 
                          else
                            echo "<h1 class='text-green'><strong>".$pending_agreements."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Pending Agreements</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardCurrentMonthPendingPayments.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='currentMonthPendingPayments.php'>

                        <?php 

                          $pending = 0;

                          $result = pg_query("SELECT * FROM homeid WHERE community_id=$community_id AND home_id NOT IN (SELECT home_id FROM current_payments WHERE community_id=$community_id AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date')");

                          while($row = pg_fetch_assoc($result))
                          {

                            $home_id = $row['home_id'];
                            
                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE home_id=$home_id"));

                            $hoa_id = $row1['hoa_id'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE hoa_id=$hoa_id AND home_id=$home_id"));
                            $charges = $row1['sum'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE hoa_id=$hoa_id AND home_id=$home_id"));
                            $payments = $row1['sum'];

                            $balance = $charges - $payments;

                            if($balance > 0)
                              $pending++;
                              
                          }

                          if($pending > 0)
                            echo "<h1 class='text-orange'><strong>".$pending."</strong></h1>"; 
                          else
                            echo "<h1 class='text-green'><strong>".$pending."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Pending Payments</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardCurrentMonthPrePaidMembers.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='currentMonthPrePaidMembers.php'>

                        <?php

                          $ma = 0 - $assessment_amount;

                          $result = pg_query("SELECT h.home_id FROM homeid h WHERE community_id=$community_id AND (SELECT sum(amount) FROM current_charges WHERE home_id=h.home_id)-(SELECT sum(amount) FROM current_payments WHERE home_id=h.home_id AND payment_status_id=1)<=$ma");

                          $rows = pg_num_rows($result);

                          if($rows > 0)
                            echo "<h1 class='text-orange'><strong>".$rows."</strong></h1>"; 
                          else
                            echo "<h1 class='text-green'><strong>".$rows."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Pre-Paid Members</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">

              <!--a href='https://hoaboardtime.com/boardCommunitySignedAgreements.php'-->

                <div class="row container-fluid text-left">

                  <br>

                  <div class="row container-fluid">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                      <a href='signedAgreements.php'>

                        <?php 

                          if($pending_agreements > 0)
                            echo "<h1 class='text-green'><strong>".$signed_agreements."</strong></h1>"; 
                          else
                            echo "<h1 class='text-info'><strong>".$signed_agreements."</strong></h1>";

                        ?>

                      </a>

                    </div>

                  </div>

                  <div class="row container-fluid text-center">

                    <h5><strong>Signed Agreements</strong></h5>

                  </div>

                  <br>

                </div>

              <!--/a-->

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
