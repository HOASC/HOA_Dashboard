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

          <h1><strong>Statement Of Activity</strong></h1>

          <ol class="breadcrumb">
            
            <li><a href='financeDashboard.php'><i class="fa fa-dollar"></i> Finance Dashboard</a></li>
            <li>Statement Of Activity</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row container-fluid">

            <?php
        
              $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/ProfitAndLoss?start_date=2017-01-01&end_date=2017-12-31');
              
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
              curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Content-Type:application/text','Content-Type:application/text','Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1515082510",oauth_nonce="898Bg7Pqr6D",oauth_version="1.0",oauth_signature="V5jZ8mkwgFBY%2BVr3Y2If%2B0YwQ6o%3D"'));
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
              $profitandloss = curl_exec($ch);
              $jsonprofitandloss = json_decode($profitandloss,TRUE);
              $data = $jsonprofitandloss['Rows']['Row'];












              foreach ($data as $key ) {

                foreach ($key['Summary'] as $Summary) {


                  $count = 0;
                  
                  foreach ($Summary as $summary) {
    
                    if ($summary['value'] == 'Total Revenue') {
                      
                      $count = 1;
                      continue;

                    }
                    if ( $count == 1 ) {
                      
                      $income = $summary['value'];
                      continue;

                    } 

                    if ( $summary['value'] == 'Total Expenditures') {
                      
                      $count = 2;
                      continue;

                    }

                    if ( $count == 2) {
                      
                      $expenditure = $summary['value'];
                      continue;

                    }

                    if ( $summary['value'] == 'Net Revenue') {
                      
                      $count = 3;
                      continue;

                    }

                    if ( $count == 3) {
                      
                      $revenue = $summary['value'];
                      continue;

                    }

                    if ( $summary['value'] == 'Total Office Supplies & Software'){
                      
                      $count = 4;
                      continue;

                    }
                    if( $count == 4) {
                      
                      $officetotal = $summary['value'];
                      continue;

                    }

                  }

                }

                foreach ($key['Rows'] as $allRows) {
                  
                  foreach ($allRows as $individualRows) {
  
                    foreach ($individualRows as $colData) {
      
                      foreach ($colData as $keyColData) {
                        
                        $count = 0;
         
                        foreach ($keyColData as $keyColData2) {
           
                          if ( $keyColData2['value'] == 'Total 6410 Office/General Administrative Expenses') {
                            
                            $count = 1;
                            continue;

                          }
                          else if ( $keyColData2['value'] == 'Total 5420 Repairs & Maintenance') {
                            
                            $count = 2;
                            continue;

                          }

                          if ( $count == 2 ){
                            
                            $repair = $keyColData2['value'];
                            $count = 0;
                            continue;

                          }

                          if ( $count == 1) {
            
                            $officegeneral = $keyColData2['value'];
                            $count = 0;
                            continue;
                          
                          }
                        
                        }
                      
                      }  
      
                    }
                  
                  }

                }
              
              }
            
            ?>

            <section style="background-color: white;">

              <br>

              <?php

                $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query?minorversion=8');
                
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Content-Type:application/text','Content-Type:application/text','Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1515082542",oauth_nonce="opMToEmDTCl",oauth_version="1.0",oauth_signature="xVAT99QaU9U9qmufuVYh9tX2SaI%3D"'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "Select * from CompanyInfo");
                
                $companyInfo = curl_exec($ch);
                
                curl_close($ch);
                
                $companyInfo = json_decode($companyInfo,TRUE);
                $companyInfo  = $companyInfo['QueryResponse'];
                $companyInfo = $companyInfo['CompanyInfo'];
                
                curl_close($ch);
              
                $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/ProfitAndLossDetail?start_date=2017-01-01&end_date=2017-12-31');
                
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Content-Type:application/text','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1515082574",oauth_nonce="o2D3SF4fblm",oauth_version="1.0",oauth_signature="vSN1BD2SzdCX8xChXafm76DEBGc%3D"'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                
                $result = curl_exec($ch);
                $json_Decode = json_decode($result,TRUE);
                $header = $json_Decode['Header'];
                $startDate  = $header['StartPeriod'];
                $endDate = $header['EndPeriod'];
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                $startDate = date('F jS ',$startDate);
                $endDate = date('F jS, Y',$endDate);
                
                curl_close($ch);

              ?>

              <div class="row container-fluid">

                <?php

                  $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/ProfitAndLoss?start_date=2017-01-01&end_date=2017-12-31');
                  
                  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                  curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Content-Type:application/text','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1515082510",oauth_nonce="898Bg7Pqr6D",oauth_version="1.0",oauth_signature="V5jZ8mkwgFBY%2BVr3Y2If%2B0YwQ6o%3D"'));
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                  
                  $profitandloss = curl_exec($ch);
                  $jsonprofitandloss = json_decode($profitandloss,TRUE);
                  $profitandlossrows = $jsonprofitandloss['Rows']['Row'];
                  
                  foreach ($profitandlossrows as $profitandlosstest) {
                    
                    foreach ($profitandlosstest['Header'] as $keyprofitandloss) {
                      
                      foreach ($keyprofitandloss as $keyprofitandloss) {
                        
                        $keyprofitandloss = $keyprofitandloss['value'];
                        
                        if ( $keyprofitandloss == "Expenditures") {
                          
                          foreach ($profitandlosstest['Rows'] as $keyprofitandlosstester) {
                            
                            foreach ($keyprofitandlosstester as $helloworld) {
                              
                              $helloworld2 = $helloworld['Header'];
                              $count = 0;
                              
                              foreach ($helloworld2['ColData'] as $keycoldata) {
                                
                                $count = $count + 1;
                                if ( $count == 2 ){
                                  
                                }

                              }

                            }

                          }

                        }

                      }

                    }

                  }

                ?>

                <div class="col-xl-offset-1 col-lg-offset-1 col-md-offset-1 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-xs-12">
                  
                  <table class="table table-hover table-bordered container-fluid">
                    
                    <thead>
                        
                        <tr>
                            
                            <th></th>
                            <th>Total</th>

                        </tr>

                    </thead>
                    
                    <tbody>
            
                        
                        <?php

                           $case = 0;
                           $subCase = 0;

                          foreach ($data as $profitAndLossAll) {
                                
                                if ( isset($profitAndLossAll['Header']['ColData'][0]['value']) ){
                                      $string = $profitAndLossAll['Header']['ColData'][0]['value'];
                                      $mainStirng = $string;
                                      $case = 1;
                                }
                                if ( $case == 1 ){
                                  if ( isset($profitAndLossAll['Summary']['ColData'][1]['value']) ){
                                      $case = 0;
                                      echo '<tr><td>'.$mainStirng.'</td>';
                                      echo '<td><b>$ '.$profitAndLossAll['Summary']['ColData'][1]['value'].'</b></td></tr>';
                                 }
                                }


                                if ( isset($profitAndLossAll['Rows']['Row']) ){
                                  foreach ($profitAndLossAll['Rows']['Row'] as $row) {
                                      if ( isset($row['ColData'][1]['value']) ){
                                      echo '<tr>';
                                      echo '<td>';
                                      echo '<ul>';
                                        echo '<h4>'.$row['ColData'][0]['value'].'<span style="float:right;">$ '.$row['ColData'][1]['value'].'</span></h4>';
                                      echo '</ul>';
                                      echo '</td>';
                                      echo '</tr>';
                                      }
                                      else if ( isset($row['ColData'][0]['value']) ){
                                      echo '<tr>';
                                      echo '<td>';
                                      echo '<ul>';
                                        echo '<h4>'.$row['ColData'][0]['value'].'</h4>';
                                      echo '</ul>';
                                      echo '</td>';
                                      echo '</tr>';
                                    }
                                    else {
                                      if ( isset($row['Header']['ColData'][0]['value'])){
                                        $subAccountString = $row['Header']['ColData'][0]['value'];
                                        $subAccountMainString = $subAccountString;
                                        $subCase = 1 ;
                                      }
                                      if ( $subCase == 1 ){
                                        if( isset($row['Summary']['ColData'][1]['value'])){
                                          $subCase = 0;
                                              echo '<tr>';
                                              echo '<td>';
                                              echo '<ul>';
                                                echo '<h4>'.$subAccountMainString.'<span style="float:right;">$ '.$row['Summary']['ColData'][1]['value'].'</span></h4>';
                                              echo '</ul>';
                                              echo '</td>';
                                              echo '</tr>';
                                        }
                                      }


                                      if ( isset($row['Rows']['Row']) ){
                                        foreach ($row['Rows']['Row'] as $subRow) {
                                          if ( isset($subRow['ColData'][1]['value']) ){
                                            echo '<tr>';
                                            echo '<td>';
                                            echo '<ul>';
                                              echo '<h4>'.$subRow['ColData'][0]['value'].'<span style="float:right;">$ '.$subRow['ColData'][1]['value'].'</span></h4>';
                                            echo '</ul>';
                                            echo '</td>';
                                            echo '</tr>';
                                          }
                                          else if ( isset($subRow['ColData'][0]['value']) ){  
                                            echo '<tr>';
                                            echo '<td>';
                                            echo '<ul>';
                                            echo '<h4>'.$subRow['ColData'][0]['value'].'</h4>';
                                            echo '</ul>';
                                            echo '</td>';
                                            echo '</tr>';
                                          }
                                        }
                                      }

                                     if ( isset($row['Summary']['ColData'][0]['value']) ){
                                          $subAccountString = $row['Summary']['ColData'][0]['value'];
                                          $subCase = 2;
                                      }

                                      if ( $subCase == 2 ){
                                         if ( isset($row['Summary']['ColData'][0]['value']) ){
                                            echo '<tr>';
                                            echo '<td><ul><h4><b>'.$subAccountString.'<span style="float:right;">$ '.$row['Summary']['ColData'][1]['value'].'</b></span></ul></h4></td>';
                                            echo '</tr>';
                                            $case = 0;
                                        }
                                      }

                                    }
                                  }
                                if ( isset($profitAndLossAll['Summary']['ColData'][0]['value']) ){
                                      $string = $profitAndLossAll['Summary']['ColData'][0]['value'];
                                       $case = 2;
                                }
                                if ( $case == 2 ){
                                  if ( isset($profitAndLossAll['Summary']['ColData'][0]['value']) ){
                                    echo '<tr>';
                                    echo '<td><ul><h4><b>'.$string.'<span style="float:right;">$ '.$profitAndLossAll['Summary']['ColData'][1]['value'].'</b></span></ul></h4></td>';
                                    echo '</tr>';
                                    $case = 0;
                                  }
                                }
                                }


                         }
                        ?>
                    
                    </tbody>

                  </table>

                </div>

              </div>

              <br>

            </section>

          </div>

        </section>

      </div>

      <?php include 'footer.php'; ?>

      <div class="control-sidebar-bg"></div>

    </div>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/demo.js"></script>

  </body>

</html>