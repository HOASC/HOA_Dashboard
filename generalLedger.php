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

    <style type="text/css">
      
      .collapsing {
  
          position: relative;
          height: 0;
          overflow: hidden;
          -webkit-transform: translateZ(0);
          -webkit-transition: height 0.35s ease 1s linear;
          -moz-transition: height 0.35s ease 1s linear;
          -o-transition: height 0.35s ease 1s linear;
          -ms-transition: height 0.35s ease 1s linear;
          transition: height 0.35s ease 1s linear;
          -webkit-transition: height 0.35s ease;
          transition: height 0.35s ease;
      }

      table .collapse.in {
    
          display:table-row;
    
      }

        .notbold{
    
          font-weight:normal
      }​

    </style>

    <script type="text/javascript">

      $(document).ready(function() {

          var table = $('#example').DataTable( {
        
              "ordering": false,
              "paging": false,
              "scrollY":        "600px",
              "scrollCollapse": true

          } );
      } );

      </script>

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

          <h1><strong>General Ledger</strong></h1>

          <ol class="breadcrumb">
            
            <li><a href='financeDashboard.php'><i class="fa fa-dollar"></i> Finance Dashboard</a></li>
            <li>General Ledger</li>
          
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
                        <th>Transaction Type</th>
                        <th>Num</th>
                        <th>Name</th>
                        <th>Memo / Description</th>
                        <th>Split</th>
                        <th>Amount</th>
                        <th>Balance</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php

                        setlocale(LC_MONETARY, 'en_US');
                        error_reporting(E_ERROR | E_PARSE);
                        ini_set('display_errors', 1);
                          
                        if($community_id == 2)
                        {

                          $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/GeneralLedger?date_macro=This%20Fiscal%20Year');
              
                          curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
                          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509663029",oauth_nonce="VobfNZi3JwU",oauth_version="1.0",oauth_signature="4XCX1HNmLhF1DFp08eLyDXlwomI%3D"'));
                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                          $result = curl_exec($ch);
                          $result = json_decode($result);
                          $idNum = 1;
              
                          foreach ($result->Rows->Row as $generalLedger) 
                          {

                            $idVal = "row".$idNum;

                            if ( isset($generalLedger->Header->ColData[0]->value) )
                            {

                              $printVal = '<tr class="clickable" data-toggle="collapse" id="'.$idVal.'" data-target=".'.$idVal.'">'.'<td><i class="fa fa-plus"></i> '.$generalLedger->Header->ColData[0]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[1]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[2]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[3]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[4]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[5]->value.'</td>'.'<td>'.money_format('%#10n',$generalLedger->Header->ColData[6]->value).'</td>'.'<td>'.money_format('%#10n',$generalLedger->Header->ColData[7]->value).'</td>'.'</tr>';

                              echo $printVal;

                              foreach ($generalLedger->Rows->Row as $childTrans) 
                              {

                                if ( isset($childTrans->ColData[0]->value) )
                                {

                                  echo '<tr class="collapse '.$idVal.'"><td>'.$childTrans->ColData[0]->value.'</td><td>'.$childTrans->ColData[1]->value.'</td><td>'.$childTrans->ColData[2]->value.'</td><td>'.$childTrans->ColData[3]->value.'</td><td>'.$childTrans->ColData[4]->value.'</td><td>'.$childTrans->ColData[5]->value.'</td><td>'.money_format('%#10n',$childTrans->ColData[6]->value).'</td><td>'.money_format('%#10n',  $childTrans->ColData[7]->value).'</td></tr>';
                               
                                }
                         
                              }

                              if ( isset($generalLedger->Summary->ColData[0]->value)  )
                              {

                                echo '<tr class="collapse '.$idVal.'"><td><b>'.$generalLedger->Summary->ColData[0]->value.'</b></td><td><b>'.$generalLedger->Summary->ColData[1]->value.'</b></td><td><b>'.$generalLedger->Summary->ColData[2]->value.'</b></td><td><b>'.$generalLedger->Summary->ColData[3]->value.'</b></td><td><b>'.$generalLedger->Summary->ColData[4]->value.'</b></td><td><b>'.$generalLedger->Summary->ColData[5]->value.'</b></td><td><b>'.money_format('%#10n',$generalLedger->Summary->ColData[6]->value).'</b></td><td><b>'.money_format('%#10n',  $generalLedger->Summary->ColData[7]->value).'</b></td></tr>';

                              }

                            }
                            else if ( isset($generalLedger->Summary->ColData[0]->value) )
                            {

                              $printVal = '<b><tr class="clickable" data-toggle="collapse" id="'.$idVal.'" data-target=".'.$idVal.'">'.'<td><b>'.$generalLedger->Summary->ColData[0]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[1]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[2]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[3]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[4]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[5]->value.'</b></td>'.'<td><b>'.money_format('%#10n', $generalLedger->Summary->ColData[6]->value).'</b></td>'.'<td><b>'.money_format('%#10n',  $generalLedger->Summary->ColData[7]->value).'</b></td>'.'</tr></b>';
                                    
                              echo $printVal;

                            }
                                
                            $idNum = $idNum +  1;
                   
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
    <script src='https://code.jquery.com/jquery-2.2.4.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/tether/1.1.1/js/tether.min.js'></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "paging": false, "pageLength": 500, "info": false });
      });
    </script>

  </body>

</html>