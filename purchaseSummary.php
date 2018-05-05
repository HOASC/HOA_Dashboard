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

        ?>
        
        <section class="content-header">

          <h1><strong>Purchase Summary</strong></h1>

          <ol class="breadcrumb">
            
            <li><a href='financeDashboard.php'><i class="fa fa-dollar"></i> Finance Dashboard</a></li>
            <li>Purchase Summary</li>
          
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
                        
                        <th>Date</th>
                        <th>Payment Type</th>
                        <th>Reference Number</th>
                        <th>Payee</th>
                        <th>Category</th>
                        <th>Total</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php

                          setlocale(LC_MONETARY, 'en_US');
                          date_default_timezone_set('America/Los_Angeles');
                          error_reporting(E_ERROR | E_PARSE);
                          ini_set('display_errors', 1);
                          
                          if($community_id == 2)
                          {
                            
                            $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query');
            
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509541160",oauth_nonce="4u2GbsqN86U",oauth_version="1.0",oauth_signature="OOpV7UMNAkRACPJjJ2SU%2FzidANE%3D"'));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "select * from purchase MAXRESULTS 1000");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            
                            $result = curl_exec($ch);
                            $result =  json_decode($result);
            
                            foreach ($result->QueryResponse->Purchase as $purchase) 
                            {

                                $name = "";

                                foreach ($purchase->Line as $accountData) 
                                {

                                    if ( $name != "" )
                                      $name = $name."<br>".$accountData->AccountBasedExpenseLineDetail->AccountRef->name;
                                    else
                                      $name = $accountData->AccountBasedExpenseLineDetail->AccountRef->name;
                                
                                }
                                
                                echo '<tr><td>'.date('Y-m-d',strtotime($purchase->MetaData->CreateTime)).'</td><td>'.$Purchase->PaymentType.'</td><td>'.$purchase->DocNumber.'</td><td>'.$purchase->EntityRef->name.'</td><td>'.$name.'</td><td><a href="purchaseSummaryDetails.php?id='.$purchase->Id.'">'.money_format('%#10n',  $purchase->TotalAmt).'</a></td></tr>';

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
        $("#example1").DataTable({ "paging": false, "pageLength": 500, "info": false });
      });
    </script>

  </body>

</html>