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
    <title>Trial Balance</title>
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
        "pageLength":100,
        columns: [
        { title: "",
        "width" : "50%"},
        { title: "DEBIT",
        "width": "25%"},
        {
            title:"CREDIT",
            "width": "25%"
        }
        ],
         "scrollY":        "500px",
        "scrollCollapse": true,
        "order": [[ 0, "asc" ]]
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
</style>
    <body>
<div class="container"> 
        <div id="search">
            
        </div>
        <br>
        <div>
        <center><h4><span class="notbold">Stoneridge Square Association</span></h4></center>
        <br>
        <center><h4>TRIAL BALANCE</h4></center>
        <center><h4><span class="notbold">As of <?php echo date('F d,Y'); ?></span></h4></center>
        <br>
        <br>
        <table id="example" class="table " cellspacing="0" width="100%">
        <tbody>
            <?php
            setlocale(LC_MONETARY, 'en_US');
            $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/reports/TrialBalance?minorversion=8');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Accept:application/json','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1492203509",oauth_nonce="Q2Ck7t",oauth_version="1.0",oauth_signature="yeSJRub0GHEFGr7Z%2FrWPdBljvm4%3D"'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($ch);
            
            $result =  json_decode($result);
            foreach ($result->Rows->Row as $row) {
                if ( $row->ColData ){
                        echo '<tr>';
                            echo '<td>';
                                echo  $row->ColData[0]->value;
                            echo '</td>';
                            echo '<td>';
                                if ( $row->ColData[1]->value != "" )
                                echo money_format('%#10n',  $row->ColData[1]->value);
                                else 
                                echo "";
                            echo '</td>';
                            echo '<td>';
                                if ( $row->ColData[2]->value != "" )
                                echo money_format('%#10n',$row->ColData[2]->value);
                                else 
                                echo "";
                            echo '</td>';
                        echo '</tr>';
                }
                else if ( $row->Summary ){
                    
                    $finalDebitAmount =   money_format('%#10n', $row->Summary->ColData[1]->value);
                    $finalCreditAmount =  money_format('%#10n', $row->Summary->ColData[2]->value);

                }
            }
        ?>  
        <tfoot>
            <tr>
                <th style="width: 50%">TOTAL</th>
                <th style="width: 25%"><?php echo $finalDebitAmount;?></th>
                <th style="width: 25%"><?php echo $finalCreditAmount; ?></th>
            </tr>
        </tfoot>         
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
