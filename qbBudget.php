<?php
date_default_timezone_set('America/Los_Angeles');
include 'includes/dbconn.php';
include 'includes/globalvar.php';
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/qbProfitLossMonth.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);
$query = "INSERT INTO BACKGROUND_JOBS(\"COMMUNITY_ID\",\"JOB_CATEGORY_ID\",\"JOB_SUB_CATEGORY_ID\",\"START_TIME\") VALUES(2,7,10,'".date('Y-m-d H:i:s')."')";
pg_query($query);
?>
<!DOCTYPE html>
<html>
    <head>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.2.3/js/dataTables.fixedColumns.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.3/css/fixedColumns.dataTables.min.css">
    <title>Budget Vs Actuals</title>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">

function showPleaseWait() {
    var modalLoading = '<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false role="dialog">\
        <div class="modal-dialog">\
            <div class="modal-content">\
                <div class="modal-header">\
                    <h4 class="modal-title">Please wait...</h4>\
                </div>\
                <div class="modal-body">\
                    <div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 100px">\
                      </div>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>';
    $(document.body).append(modalLoading);
    $("#pleaseWaitDialog").modal("show");
}
$(document).ready(function() {
   var table = $('#example').DataTable( {
        scrollY:        "600px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        "order": [[ 1, "desc" ]],
        fixedColumns:   {
            leftColumns: 1,
            rightColumns: 4
        }
    } );
} 

);

function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}
  </script>
</head>
<style type="text/css">
    .notbold{
    font-weight:normal
}​
th, td {
        white-space: nowrap;
        padding-right: 1px !important;
}
.total{
    border-left:  1px solid black !important;
}
</style>
    <body>
<div class="container"> 
        <div id="search">
            
        </div>
        <br>
        <div>
        <center><h4><span class="notbold">Stoneridge Square Association</span></h4></center>
        <br>
        <center><h4>Budget Vs Actuals</h4></center>
        <center><h4><span class="notbold"><?php echo $community_name.' 2017'?></span></h4></center>
        <br>
        <br>
<table id="example" class="cell-border" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th rowspan="2"></th>
                <th colspan="4">January</th>
                <th colspan="4">February</th>
                <th colspan="4">March</th>
                <th colspan="4">April</th>
                <th colspan="4">May</th>
                <th colspan="4">June</th>
                <th colspan="4">July</th>
                <th colspan="4">August</th>
                <th colspan="4">September</th>
                <th colspan="4">October</th>
                <th colspan="4">November</th>
                <th colspan="4">December</th>
                <th colspan="4">Total</th>
            </tr>
            <tr>
                <th >ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th>ACTUAL</th>
                <th>BUDGET</th>
                <th>OVER BUDGET</th>
                <th>% OF BUDGET</th>
                <th class="total">YTD ACTUAL</th>
                <th class="total">YTD BUDGET</th>
                <th class="total">YTD OVER BUDGET</th>
                <th class="total">YTD % OF BUDGET</th>

            </tr>
        </thead>
        <tbody>
           <?php
   date_default_timezone_set('America/Los_Angeles');
   setlocale(LC_MONETARY, 'en_US');
   
   $query = "SELECT * FROM community_accounts WHERE COMMUNITY_ID = 2";
   $queryResult = pg_query($query);

   $accountTypes = array();

   while ($row = pg_fetch_assoc($queryResult)) {
       $accountTypes[$row['qb_id']] = $row['category'];
   }


   $query  = "select * from qb_monthly_actuals where year = ".date('Y');
   $queryResult = pg_query($query);
   $dbData  = array();

   while ($row = pg_fetch_assoc($queryResult)) {
      $dbData[$row['qb_vendor_id'].$row['month'].$row['year']] = $row['amount'];
   }
   $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query');
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509983625",oauth_nonce="c14fWRf8XyQ",oauth_version="1.0",oauth_signature="lWDlUObdtq5u8GvxpRz07Or0d9c%3D"'));
   curl_setopt($ch, CURLOPT_POSTFIELDS, "Select * from Budget");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
   $result = curl_exec($ch);
   $result = json_decode($result);
   $result = ($result->QueryResponse->Budget[0]);
   $val = 0;
   $prevVal = 0 ;
   $valString = "";
   $totalBudget = 0;
   $totalActuals = 0;
   $fbName = "";
   foreach ($result->BudgetDetail as $budget) {
    if ($accountTypes[$budget->AccountRef->value]  == 2)
        $color = "red";
    else if ( $accountTypes[$budget->AccountRef->value] == 3)
        $color = "green";
    else 
        $color = "black";
      if ( $val == 0  ){
        $prevVal =  $budget->AccountRef->value;
         $month = date('m',strtotime($budget->BudgetDate)) ;
         $month  = ltrim($month, '0');
         $year = date('Y');
         $bName = $budget->AccountRef->name;
         $fbName = $bName;
         $actual = $dbData[$budget->AccountRef->value.$month.$year];
         $budget = $budget->Amount;

         $totalActuals = $actual;
         $totalBudget = $budget;

        $overBudget = $actual-$budget;
         if($budget != 0 ){
         $budgetPercentage = ($actual/$budget)*100;
         }
         else {
            $budgetPercentage = "";
         }
         $valString .= "<tr>";

         $valString .= "<td style=\"color: $color;\">";
         $valString .= $bName;
         $valString .= "</td>";

         $valString .= "<td>";
            $valString .= money_format('%#10n',  $actual);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= money_format('%#10n',  $budget);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= money_format('%#10n',  $overBudget);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= round((float)$budgetPercentage,2) . '%';
         $valString .= "</td>";

      }
      else {
         if ( $prevVal ==  $budget->AccountRef->value ){
         $month = date('m',strtotime($budget->BudgetDate)) ;
         $month  = ltrim($month, '0');
         $year = date('Y');
         $bName = $budget->AccountRef->name;
         $actual = $dbData[$budget->AccountRef->value.$month.$year];
         $budget = $budget->Amount;
         $totalBudget = $totalBudget + $budget;
         $totalActuals = $totalActuals + $actual;
         $overBudget = $actual-$budget;
         if($budget != 0 ){
         $budgetPercentage = ($actual/$budget)*100;
         }
         else {
            $budgetPercentage = "";
         }
         $valString .= "<td>";
            $valString .= money_format('%#10n',  $actual);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= money_format('%#10n',  $budget);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= money_format('%#10n',  $overBudget);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= round((float)$budgetPercentage,2) . '%';
         $valString .= "</td>";
         $fbName = $bName;
         }
         else {
         $prevVal = $budget->AccountRef->value;
         $month = date('m',strtotime($budget->BudgetDate)) ;
         $month  = ltrim($month, '0');
         $year = date('Y');
         $bName = $budget->AccountRef->name;
         $actual = $dbData[$budget->AccountRef->value.$month.$year];
         $budget = $budget->Amount;
       
         $overBudget = $actual-$budget;
         if($budget != 0 ){
         $budgetPercentage = ($actual/$budget)*100;
         }
         else {
            $budgetPercentage = "";
         }

         $valString .= "<td class=\"total\">";
            $valString .= money_format('%#10n',$totalActuals);
         $valString .= "</td>";
         $valString .= "<td class=\"total\">";
            $valString .= money_format('%#10n',$totalBudget);
         $valString .= "</td>";
         $valString .= "<td class=\"total\">";
            $valString .= money_format('%#10n',$totalActuals-$totalBudget);
         $valString .= "</td>";
         $valString .= "<td class=\"total\">";
            $valString .=  round((float)(($totalActuals/$totalBudget)*100),2) . '%'; 
         $valString .= "</td>";
         $valString .= "</tr>";
           $totalBudget = $budget;
         $totalActuals = $actual;
         $valString .= "<tr>";
         $valString .= "<td  style=\"color: $color;\">";
         $valString .= $bName;
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= money_format('%#10n',  $actual);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= money_format('%#10n',  $budget);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= money_format('%#10n',  $overBudget);
         $valString .= "</td>";
         $valString .= "<td>";
            $valString .= round((float)$budgetPercentage,2) . '%';
         $valString .= "</td>";

         }
         }
            $val = $val + 1;
      }
      $valString .= "<td class=\"total\">";
            $valString .= money_format('%#10n',$totalActuals);
         $valString .= "</td>";
         $valString .= "<td class=\"total\">";
            $valString .= money_format('%#10n',$totalBudget);
         $valString .= "</td>";
         $valString .= "<td class=\"total\">";
            $valString .= money_format('%#10n',$totalActuals-$totalBudget);
         $valString .= "</td>";
         $valString .= "<td class=\"total\">";
            $valString .=  round((float)(($totalActuals/$totalBudget)*100),2) . '%'; 
         $valString .= "</td>";
      $valString .= "</tr>";
      print_r($valString);

?>
        </tbody>
        
    </table>     
        </div>
        <br><br>
  </div>

  <script type="text/javascript">
      function changeOptions3(id){
        hidePleaseWait(); 
    }
  </script>
    </body>
</html>
s