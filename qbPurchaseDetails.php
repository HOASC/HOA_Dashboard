<?php
date_default_timezone_set('America/Los_Angeles');
?>
<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Purchase Details</title>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
var fileData = "";
  var fileName = "";
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
   var table =  $('#example').DataTable( {
        select: true,
        "pageLength":100,
        "scrollY":        "600px",
        "scrollCollapse": true
    } );
   table.order([0,'asc']); 
} 
);

$.fn.textWidth = function(text, font) {
    
    if (!$.fn.textWidth.fakeEl) $.fn.textWidth.fakeEl = $('<span>').hide().appendTo(document.body);
    
    $.fn.textWidth.fakeEl.text(text || this.val() || this.text() || this.attr('placeholder')).css('font', font || this.css('font'));
    
    return $.fn.textWidth.fakeEl.width();
};

$('.width-dynamic').on('input', function() {
    var inputWidth = $(this).textWidth();
    $(this).css({
        width: inputWidth
    })
}).trigger('input');


function inputWidth(elem, minW, maxW) {
    elem = $(this);
    console.log(elem)
}

var targetElem = $('.width-dynamic');

inputWidth(targetElem);

function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}

function getFileData()
{
  var file = document.getElementById("fileInput").files[0];
  if ( file ){
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function (evt) {
         fileData =evt.target.result.split(',')[1];
        return fileData;
    }
    reader.onerror = function (evt) {
        fileData = "Error";
        return fileData;
    }
}

}


function saveChanges(){
    jsonObj = [];
    item = {};
    item["purchase_id"] =   <?php echo $_GET['id']; ?>;
    item["file_data"] = fileData;
    item["file_name"] = fileName;
    jsonObj.push(item);
    var stringJSON = JSON.stringify(jsonObj);
    var request = new XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/qbUpload.php",true);
    request.setRequestHeader("Content-type", "application/json");
    request.send(stringJSON);
    showPleaseWait();
    request.onreadystatechange = function(){
      if ( request.readyState == XMLHttpRequest.DONE ){
      hidePleaseWait();
      if ( request.responseText == "Failed to add attachment"){
        swal(request.responseText,"","error");
      }
      else if ( request.responseText == "Unsupported format." ) {
        swal(request.responseText,"","error");
      }
      else {
            swal("Attachment Added","Please refresh page to view attachment.","success"); 
      }
    }

}
}


  </script>
</head>
<style type="text/css">
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}
.switch input {display:none;}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}
input:checked + .slider {
  background-color: #2196F3;
}
input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}
input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
[hidden] {
  display: none !important;
}
.btn.outline {
  background: none;
  padding: 12px 22px;
}
    .notbold{
    font-weight:normal
}​
body {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 100vh;
}
input {
    padding: 1px;
    align-self: center;
    min-width: 80px;
    max-width: 500px;
}
input, label {
    display:block;
}
</style>
    <body>
<div class="container"> 
        <div id="search">
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
        </div>
        <br>
        <div>
        <center><h3><span class="notbold">Stoneridge Square Association</span></h3></center>

        <br>
        <br>
        <div style="float: left;width: 150px;" >
        <label><?php echo $purchaseResult[0]->EntityRef->type;   ?></label>
        <?php
        echo $purchaseResult[0]->EntityRef->name;
        ?>
        </div>  
        <div style="float: left;padding-left: 80px;width: 500px;">
        <label>Account</label>
        <?php
        echo $purchaseResult[0]->AccountRef->name;
        ?>
        </div> 
        
        <div style="float: right;">
            <h6 style="float: right;">AMOUNT</h6>
            <br>
            <h1><?php echo money_format('%#10n',$purchaseResult[0]->TotalAmt);  ?></h1>
        </div>
        <div style="clear: both;"></div>
        <br>
        <br>
        <br>
        <div style="float: left;width: 150px;" >
        <label>Payment Date</label>
        <?php 
        echo date('d F Y',strtotime($purchaseResult[0]->MetaData->CreateTime));
        ?>
        </div>  

        <div style="float: left;padding-left: 80px;width: 500px;">
        <label>Payment Type</label>
       <?php 
        echo $purchaseResult[0]->PaymentType;
        ?>
        </div> 
        
        <div style="float: right;">
           <label style="float: right;">Ref. Number</label><br>
           <?php
            if( $purchaseResult[0]->DocNumber )
                echo $purchaseResult[0]->DocNumber;
            else {
                echo "<center>-</center>";
            }
           ?>
        </div>
        <div style="clear: both;"></div>
        <br>
        <br>
        <table id="example" class="table " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>ACCOUNT</th>
                <th>DESCRIPTION</th>
                <th>AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $value = 1;
                $IDS = array();
                foreach ($purchaseResult[0]->Line as $purchase) {
                    echo '<tr>';
                        echo '<td>';
                            echo $value;
                        echo '</td>';
                        echo '<td>';
                            echo $purchase->AccountBasedExpenseLineDetail->AccountRef->name;
                        echo '</td>';
                        echo '<td>';
                            echo $purchase->Description;
                        echo '</td>';
                        echo '<td>';
                             echo '<style="float:right;">'.money_format('%#10n',$purchase->Amount).'</>';
                        echo '</td>';
                    echo '</tr>';
                    $value = $value + 1;
                }
            ?>
        </tbody>
        </table>
        <br>
        <div class="form-group">
            <label for="comment">Memo</label>
            <textarea class="form-control" rows="3" id="comment" style="width: 400px;" readonly="readonly"><?php print_r($final->PrivateNote); ?></textarea>
        </div>
        <br>
        <label>Attachment(s)</label>
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
                foreach ($data->Attachable as $attachable) {
                    echo '<a href="'.$attachable->TempDownloadUri.'">'.$attachable->FileName.'</a>';
                    echo '<br>';
                }
            }
            else {
                echo "No attachments found";
            }

        ?>
    <br>
    <br>
    <div>
      
      <label class="btn btn-default" >
      Add Attachment<input type="file" id="fileInput" hidden>
      </label>
      <h4 id="label"></h4>
      </div>
      <br>
      <button type="button" class="btn btn-success" onclick="saveChanges();" id="saveButton" hidden="hidden">Save</button>
      <br>
      <script type="text/javascript">
        document.getElementById('fileInput').onchange = function () {
          var f =  this.value;
          f = f.replace(/.*[\/\\]/, '');
          fileName  = f;
           document.getElementById("label").innerHTML = f;
           document.getElementById("saveButton").hidden = false;
          getFileData();
        };
      </script>


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
