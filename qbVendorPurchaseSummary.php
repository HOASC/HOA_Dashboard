<?php
date_default_timezone_set('America/Los_Angeles');
include 'includes/dbconn.php';
$req = curl_init();
curl_setopt($req, CURLOPT_URL,"https://hoaboardtime.com/qbPurchaseAttachments.php");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_exec($req);
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
    <title><?php echo $_GET['id']; ?> Purchase Summary</title>
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
    jQuery.fn.dataTableExt.oSort['mystring-asc'] = function(x,y) {
var retVal;
x = $.trim(x);
y = $.trim(y);

if (x==y) retVal= 0;
else if (x == "" || x == " ") retVal= 1;
else if (y == "" || y == " ") retVal= -1;
else if (x > y) retVal= 1;
else retVal = -1; // y) retVal= 1;
return retVal;
}
   var table = $('#example').removeAttr('width').DataTable( {
        scrollY:        "600px",
        scrollX:        false,
        scrollCollapse: true,
        paging:         false,
        columnDefs: [
            { width: 200, targets: 1 }
        ],
        fixedColumns: true
    } );

} 
);

function a(text){
    window.location = "https://hoaboardtime.com/qbPurchaseDetails.php?id="+text.id;
}

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
        <center><h4>Purchase Summary</h4></center>
        <br>
        <center><h4><span class="notbold"><?php echo  $_GET['id']; ?></span></h4></center>
        <br>
        <table id="example" class="table " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>DATE</th>
                <th>PAYMENT TYPE</th>
                <th>REFERENCE NO</th>
                <th>PAYEE</th>
                <th width="20%">CATEGORY</th>
                <th>TOTAL</th>
                <th width="30%">ATTACHMENT</th>
            </tr>
        </thead>
        <tbody>
            <?php
            setlocale(LC_MONETARY, 'en_US');

            date_default_timezone_set('America/Los_Angeles');
            $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1509541160",oauth_nonce="4u2GbsqN86U",oauth_version="1.0",oauth_signature="OOpV7UMNAkRACPJjJ2SU%2FzidANE%3D"'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, "select * from purchase MAXRESULTS 1000");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($ch);
            $result =  json_decode($result);
            if ( isset($_GET['id']) ){
            foreach ($result->QueryResponse->Purchase as $purchase) {
                $name = "";
                foreach ($purchase->Line as $accountData) {
                    if ( $name != "" )
                    $name = $name."<br>".$accountData->AccountBasedExpenseLineDetail->AccountRef->name;
                    else 
                    {
                        $name = $accountData->AccountBasedExpenseLineDetail->AccountRef->name;
                    }
                }

                if ( $purchase->EntityRef->name == $_GET['id'] ){
                echo '<tr>';
                     echo '<td>';
                        echo date('Y-m-d',strtotime($purchase->MetaData->CreateTime));
                    echo '</td>';
                    echo '<td>';
                        echo $Purchase->PaymentType;
                    echo '</td>';
                    echo '<td>';
                        echo $purchase->DocNumber;
                    echo '</td>';
                    echo '<td>';
                        echo $purchase->EntityRef->name;
                    echo '</td>';
                     echo '<td >';
                        echo $name;
                    echo '</td>';
                     echo '<td>';
                        echo '<a onClick="a(this);" style="cursor: pointer; cursor: hand;" id="'.$purchase->Id.'">'.money_format('%#10n',  $purchase->TotalAmt).'</a>';
                    echo '</td>';
                    echo '<td width="30%">';
                        $query = "SELECT *  FROM qb_purchase_attchments WHERE COMMUNITY_ID = 2 AND purchase_id=".$purchase->Id;
                        $queryResult = pg_query($query);
                        $name = "";
                        $count  =0;
                        while ($row = pg_fetch_assoc($queryResult)) {
                            $count  = $count + 1;
                            $name =  $row['attachment_name'];
                        }
                        if ( $name == "" ){
                            echo "No attachment(s) found.";
                        }
                        else if ( $count > 1) {
                             echo '<a onClick="a(this);" style="cursor: pointer; cursor: hand;" id="'.$purchase->Id.'">'.$count." attachments found".'</a>';
                        }
                        else {
                            echo '<a onClick="a(this);" style="cursor: pointer; cursor: hand;" id="'.$purchase->Id.'">'.$count." attachment found".'</a>';
                        }
                echo '</td>';
                echo '</tr>';
                }
            }
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
