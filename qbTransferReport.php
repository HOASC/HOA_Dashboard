<?php

date_default_timezone_set('America/Los_Angeles');

include "includes/api_keys.php";

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
    <title>Transfer Summary</title>
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
   var table =  $('#example').DataTable( {
        select: true,
         "scrollY":        "500px",
        "scrollCollapse": true,
    } );
} );

function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}
  </script>
</head>
<style type="text/css">
    .notbold{
    font-weight:normal
}​
</style>
    <body>
<div class="container"> 
        <div id="search">
            
        </div>
        <br>
        <div>
        <center><h4><span class="notbold">Stoneridge Square Association</span></h4></center>
        <br>
        <center><h4>TRANSFER SUMMARY</h4></center>
        <center> <h4><span class="notbold"><?php echo date('F d',strtotime('first day of january this year')).' - '.date('F d');  ?></span></h4></center>
        <br>
        <br>
        <table id="example" class="table " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Date</th>
                <th>Amount </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Date</th>
                <th>Amount </th>
            </tr>
        </tfoot>
        <tbody>
            <?php
                $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query?minorversion=8');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
                // curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="'.$oauth_consumer_key.'",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509358132",oauth_nonce="3lUoyos1rhR",oauth_version="1.0",oauth_signature="LmW6fQbpxh97DMOo9ifRFrChI54%3D"'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, "select * from transfer");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $result = curl_exec($ch);
                $result = json_decode($result);
                foreach ($result->QueryResponse->Transfer as $transfer) {
                     setlocale(LC_MONETARY, 'en_US');
                    $finalAmount = money_format('%#10n', $transfer->Amount);
                    echo '<tr>';
                        echo '<td>';
                            echo $transfer->FromAccountRef->name;
                        echo '</td>';
                        echo '<td>';
                            echo $transfer->ToAccountRef->name;
                        echo '</td>';
                       
                        echo '<td>';
                            echo date('d F,Y  h:i A', strtotime($transfer->MetaData->CreateTime));
                        echo '</td>';
                         echo '<td>';
                            echo $finalAmount;
                        echo '</td>';
                    echo '</tr>';
                }
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
