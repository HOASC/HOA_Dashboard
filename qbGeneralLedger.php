<?php
date_default_timezone_set('America/Los_Angeles');
?>
<!DOCTYPE html>
<html>
    <head>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>General Ledger</title>
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

function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}

$(document).ready(function() {
    var table = $('#example').DataTable( {
        "ordering": false,
        "paging": false,
        "scrollY":        "600px",
        "scrollCollapse": true,
    } );
} );
  </script>
</head>
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
<?php
date_default_timezone_set('America/Los_Angeles');
?>
    <body>
<div class="container">
    <center><h3><span class="notbold">Stoneridge Square Association</span></h3></center>
    <br>
    <center><h4>GENERAL LEDGER</h4></center>
     <center><h4><span class="notbold">January - December <?php echo date('Y')?></span></h4></center>
     <br>
<table class="table table-responsive table-hover" id="example" style="font-size: 12px;">
  <thead>
        <tr><th style="width: 15%;">DATE</th><th>TRANSACTIONTYPE</th><th>NUM</th><th>NAME</th><th>MEMEO/DESCRIPTION</th><th>SPLIT</th><th>AMOUNT</th><th>BALANCE</th></tr>
    </thead>
    <tbody>
            <?php
            setlocale(LC_MONETARY, 'en_US');
            error_reporting(E_ERROR | E_PARSE);
            ini_set('display_errors', 1);
            $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/GeneralLedger?date_macro=This%20Fiscal%20Year');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509663029",oauth_nonce="VobfNZi3JwU",oauth_version="1.0",oauth_signature="4XCX1HNmLhF1DFp08eLyDXlwomI%3D"'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($ch);
            $result = json_decode($result);
            $idNum = 1;
            foreach ($result->Rows->Row as $generalLedger) {
                    $idVal = "row".$idNum;
                  if ( isset($generalLedger->Header->ColData[0]->value) )
                  {
                    $printVal = '<tr class="clickable" data-toggle="collapse" id="'.$idVal.'" data-target=".'.$idVal.'">'.'<td><i class="glyphicon glyphicon-plus"></i>'.$generalLedger->Header->ColData[0]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[1]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[2]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[3]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[4]->value.'</td>'.'<td>'.$generalLedger->Header->ColData[5]->value.'</td>'.'<td>'.money_format('%#10n',$generalLedger->Header->ColData[6]->value).'</td>'.'<td>'.money_format('%#10n',$generalLedger->Header->ColData[7]->value).'</td>'.'</tr>';
                    echo $printVal;
                    foreach ($generalLedger->Rows->Row as $childTrans) {
                              if ( isset($childTrans->ColData[0]->value) ){
                        echo '<tr class="collapse '.$idVal.'">
                                <td>'.$childTrans->ColData[0]->value.'</td>
                                <td>'.$childTrans->ColData[1]->value.'</td>
                                <td>'.$childTrans->ColData[2]->value.'</td>  
                                <td>'.$childTrans->ColData[3]->value.'</td>
                                <td>'.$childTrans->ColData[4]->value.'</td>
                                <td>'.$childTrans->ColData[5]->value.'</td>  
                                <td>'.money_format('%#10n',$childTrans->ColData[6]->value).'</td>
                                <td>'.money_format('%#10n',  $childTrans->ColData[7]->value).'</td>
                                </tr>';
                             
                       }
                       
                  }
                  if ( isset($generalLedger->Summary->ColData[0]->value)  ){
                                    echo '<tr class="collapse '.$idVal.'">
                                <td><b>'.$generalLedger->Summary->ColData[0]->value.'</b></td>
                                <td><b>'.$generalLedger->Summary->ColData[1]->value.'</b></td>
                                <td><b>'.$generalLedger->Summary->ColData[2]->value.'</b></td>  
                                <td><b>'.$generalLedger->Summary->ColData[3]->value.'</b></td>
                                <td><b>'.$generalLedger->Summary->ColData[4]->value.'</b></td>
                                <td><b>'.$generalLedger->Summary->ColData[5]->value.'</b></td>  
                                <td><b>'.money_format('%#10n',$generalLedger->Summary->ColData[6]->value).'</b></td>
                                <td><b>'.money_format('%#10n',  $generalLedger->Summary->ColData[7]->value).'</b></td>
                                </tr>';
                    }
                }
                  else if ( isset($generalLedger->Summary->ColData[0]->value) ){
                        $printVal = '<b><tr class="clickable" data-toggle="collapse" id="'.$idVal.'" data-target=".'.$idVal.'">'.'<td><b>'.$generalLedger->Summary->ColData[0]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[1]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[2]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[3]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[4]->value.'</b></td>'.'<td><b>'.$generalLedger->Summary->ColData[5]->value.'</b></td>'.'<td><b>'.money_format('%#10n', $generalLedger->Summary->ColData[6]->value).'</b></td>'.'<td><b>'.money_format('%#10n',  $generalLedger->Summary->ColData[7]->value).'</b></td>'.'</tr></b>';
                    echo $printVal;
                  }
                  else {
                                            
                 }
                 $idNum = $idNum +  1;
                 
            }

        ?>
    </tbody>
  </table>
</div>
  
    </body>
</html>
