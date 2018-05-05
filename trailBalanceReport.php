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

          <h1><strong>Trail Balance Report</strong><small> - Till <?php echo date('F d,Y'); ?></small></h1>

          <ol class="breadcrumb">
            
            <li><a href='financeDashboard.php'><i class="fa fa-dollar"></i> Finance Dashboard</a></li>
            <li>Trail Balance Report</li>
          
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
                        
                        <th></th>
                        <th>Debit</th>
                        <th>Credit</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php

                        $totalDebitAmount = "NULL";
                        $totalCreditAmount = "NULL";

                        if($community_id == 2)
                        {

                          $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/TrialBalance?minorversion=8');
                          curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
                          curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1492203509",oauth_nonce="Q2Ck7t",oauth_version="1.0",oauth_signature="yeSJRub0GHEFGr7Z%2FrWPdBljvm4%3D"'));
                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                          $result = curl_exec($ch);
                            
                          $result =  json_decode($result);

                          foreach ($result->Rows->Row as $row) 
                          {

                            if ( $row->ColData )
                            {

                              echo "<tr><td>".$row->ColData[0]->value."</td><td>";
                                          
                              if ( $row->ColData[1]->value != "" )
                                echo "$ ".$row->ColData[1]->value;
                                  
                              echo "</td><td>";
                                          
                              if ( $row->ColData[2]->value != "" )
                                echo "$ ".$row->ColData[2]->value;
                                  
                              echo "</td></tr>";

                            }
                            else if ( $row->Summary )
                            {
                      
                              $totalDebitAmount = $row->Summary->ColData[1]->value;
                              $totalCreditAmount = $row->Summary->ColData[2]->value;

                            }

                          }

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Total</th>
                        <th><?php if($totalDebitAmount != 'NULL') echo "$ ".$totalDebitAmount; ?></th>
                        <th><?php if($totalCreditAmount != 'NULL') echo "$ ".$totalCreditAmount; ?></th>

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
        $("#example1").DataTable({ "paging": false, "pageLength": 500, "info": false });
      });
    </script>

  </body>

</html>