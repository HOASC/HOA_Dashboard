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

      <?php if($mode == 1) include "boardHeader.php"; ?>
      
      <?php if($mode == 1) include 'boardNavigationMenu.php'; ?>

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
            <li><a href='purchaseSummary.php'>Purchase Summary</a></li>
            <li>Purchase Summary Details</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

            <div class='box'>

              <div class='box-body box-responsive'>

                <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 module'>
                
                  <?php

                            setlocale(LC_MONETARY, 'en_US');
                            date_default_timezone_set('America/Los_Angeles');
                            
                            $purchaseID = $_GET['id'];
                          
                          $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query');
                          
                          curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
                          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509571171",oauth_nonce="XXAaTNNoZOx",oauth_version="1.0",oauth_signature="YqQesEYb0Fo%2FmAlv81W3UpT43bs%3D"'));
                          curl_setopt($ch, CURLOPT_POSTFIELDS, "select * from purchase where id = '".$purchaseID."'");
                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                
                          $result = curl_exec($ch);
                          $purchaseResult  = json_decode($result);
                          $purchaseResult  = $purchaseResult->QueryResponse->Purchase;
                          $final = $purchaseResult[0];
                
                        ?>

                        <div class='row'>

                          <div class='col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6'>

                            <label><strong><?php echo $purchaseResult[0]->EntityRef->type; ?></strong></label>
                            
                            <br>

                          <h4><?php echo $purchaseResult[0]->EntityRef->name; ?></h4>

                          </div>

                          <div class='col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6'>

                            <label><strong>Account</strong></label>
                            
                            <br>

                          <h4><?php echo $purchaseResult[0]->AccountRef->name; ?></h4>

                          </div>

                          <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 text-right'>

                            <label><strong>Amount</strong></label>
                            
                            <br>

                          <h2><?php echo money_format('%#10n',$purchaseResult[0]->TotalAmt); ?></h2>

                          </div>

                        </div>
                  
                  <br><br>

                        <div class='row'>

                          <div class='col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6'>

                            <label><strong>Payment Date</strong></label>
                            
                            <br>

                          <h4><?php echo date('d F Y',strtotime($purchaseResult[0]->MetaData->CreateTime)); ?></h4>

                          </div>

                          <div class='col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6'>

                            <label><strong>Payment Type</strong></label>
                            
                            <br>

                          <h4><?php echo $purchaseResult[0]->PaymentType; ?></h4>

                          </div>

                          <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 text-right'>

                            <label><strong>Reference Number</strong></label>
                            
                            <br>

                          <h4>

                            <?php

                              if( $purchaseResult[0]->DocNumber )
                                    echo $purchaseResult[0]->DocNumber;
                                else
                                    echo "<center>-</center>";

                            ?>

                          </h4>

                          </div>

                        </div>

                        <br><br>

                        <div class='row'>

                          <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive'>

                            <table id='example1' class='table table-striped' style='color: black;'>

                              <thead>
                                
                                <th>#</th>
                                <th>Account</th>
                                <th>Description</th>
                                <th>Amount</th>

                              </thead>

                              <tbody>
                                
                                <?php

                                    $value = 1;
                                    $IDS = array();
                                    
                                    foreach ($purchaseResult[0]->Line as $purchase) 
                                    {
                                        
                                        echo '<tr><td>'.$value.'</td><td>'.$purchase->AccountBasedExpenseLineDetail->AccountRef->name.'</td><td>'.$purchase->Description.'</td><td><style="float:right;">'.money_format('%#10n',$purchase->Amount).'</></td></tr>';

                                        $value = $value + 1;

                                    }
                  
                                ?>

                              </tbody>
                              
                            </table>

                          </div>

                        </div>

                        <br><br>

                        <div class='row'>

                          <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                            <label><strong>Memo</strong></label>

                            <br>

                            <textarea class="form-control" rows="3" id="comment" style="width: 400px;" readonly="readonly"><?php print_r($final->PrivateNote); ?></textarea>

                          </div>

                          <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                            <label><strong>Attachment(s)</strong></label>

                            <br>

                            <?php

                              $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query');

                              curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
                              curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509569266",oauth_nonce="8N0tvCVCsWK",oauth_version="1.0",oauth_signature="ZoQHffDGFCgQUgP8R5Owiix6pec%3D"'));
                              curl_setopt($ch, CURLOPT_POSTFIELDS, "Select * from Attachable where AttachableRef.EntityRef.Type = 'purchase' AND AttachableRef.EntityRef.value = '".$purchaseID."'");
                              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                              $result = curl_exec($ch);
                              $result  = json_decode($result);
                              $data = $result->QueryResponse;
                
                              if ( isset( $data->Attachable ) )
                              {
                    
                                  foreach ($data->Attachable as $attachable)
                                    echo '<a target="_blank" href="'.$attachable->TempDownloadUri.'">'.$attachable->FileName.'</a><br>';

                              }
                              else
                                echo "No attachments found";

                          ?>

                          </div>

                        </div>

                </div>

              </div>

            </div>

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