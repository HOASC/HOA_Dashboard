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
    <title>Inspeciton Management</title>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">

var pdf  = "";
var zip = "";
var hoaid;
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
function changeOptions2(){
    showPleaseWait();
    $('#example').dataTable().fnClearTable();
    var request = new XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/getInspectionData.php?id="+(document.getElementById("status").selectedIndex),true);
    request.send(null);
    request.onreadystatechange = function(){
    hidePleaseWait();
    if (request.readyState == XMLHttpRequest.DONE){
    let data = request.responseText;
    let data2 = JSON.parse(data);
    var dataSet = new Array();
    for (var i = 0; i < data2.length; i++) {
        var dataSet2 = new Array();
        dataSet2.push('<input type="button" id="'+data2[i]['id']+'" value="Preview & Send" onclick="previewAndGenerate(this)"></input>');
        dataSet2.push(data2[i]['home_id']);
        dataSet2.push(data2[i]['hoa_id']);
        dataSet2.push(data2[i]['description']);
        dataSet2.push(data2[i]['inspection_category_id']);
        dataSet2.push(data2[i]['inspection_sub_category_id'])
        dataSet2.push(data2[i]['location_id']);
        dataSet2.push(data2[i]['inspection_notice_type_id']);
        dataSet2.push(data2[i]['inspection_status']);
        dataSet.push(dataSet2);
    }
    if (dataSet.length > 0){
    $('#example').dataTable().fnAddData(dataSet);
    }
    else {
        alert("Data not found");
    }
}
} 
}

function showPreview(){
    window.open("https://hoaboardtime.com/getDocumentPreviewTest.php?t=-1&path="+pdf);
}


function editNotice(editButton){

    $("#myModal2").modal("hide");
    showPleaseWait();
    id  = 1;
    var jsonObj = [];
    var item = {};
    item["id"] = editButton;
    jsonObj.push(item);
    var lol = JSON.stringify(jsonObj);
    var request  = new  XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/getInspectionDetails.php",true);
    request.send(lol.toString());
    request.onreadystatechange = function(){
    if ( request.readyState == XMLHttpRequest.DONE ){
      hidePleaseWait();
      var dal = request.responseText
      var data = JSON.parse(dal);
      var fieldData = '<label for="inspectionID">ID</label>'+'<input type="text" id="inspectionID" disabled="disabled" class = "form-control" value="'+data['id']+'"/>'+'<label for="inspectionDescription">Description</label>'+'<textarea class="form-control" rows="2" id="inspectionDescription">'+data['description']+'</textarea>'+'<label for="inspection_category_id">Category</label>'+'<input type="text" id="inspection_category_id"  class = "form-control" value="'+data['inspection_category_id']+'"/>'+'<label for="inspection_sub_category_id">Sub Category</label>'+'<input type="text" id="inspection_sub_category_id"  class = "form-control" value="'+data['inspection_sub_category_id']+'"/>'+'<label for="homeID">HOME ID</label>'+'<input type="text" id="homeID" class = "form-control" value="'+data['home_id']+'"/>'+'<label for="hoaID">HOA ID</label>'+'<input type="text" id="hoaID"  class = "form-control" value="'+data['hoa_id']+'"/>'+'<label for="location_id">Location ID</label>'+'<input type="text" id="location_id" class = "form-control" value="'+data['location_id']+'"/>'+'<label for="inspection_status_id">Inspection Status</label>'+'<input type="text" id="inspection_status_id" class = "form-control" value="'+data['inspection_status_id']+'"/>'+'<label for="item">Item</label>'+'<input type="text" id="item" class = "form-control" value="'+data['item']+'"/>';
        $("#myModal").modal("show");
        $("#myModal").find('.modal-body').html(fieldData);
    }
  }
}

function sleep(miliseconds) {
   var currentTime = new Date().getTime();
   while (currentTime + miliseconds >= new Date().getTime()) {
   }
}
function generateNotice(button){
    var fieldData =     '<h4 class="modal-title">Generating Notice...Please Wait...</h4>';
    $("#pleaseWaitDialog2").modal("show");
    $("#pleaseWaitDialog2").find('.modal-header').html(fieldData);
    var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
    $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
var source = new EventSource("https://hoaboardtime.com/genericNotice.php?id="+button.id);
source.onmessage = function(event) {
    $("#pleaseWaitDialog2").find('.modal-header').html('<h4 class="modal-title">'+event.data+'</h4>');
    if ( (event.data == "Uploaded to Dropbox Successfully") || (event.data == "Failed to upload to Dropbox. Please try agin.") ){
        source.close();
        $("#pleaseWaitDialog2").find('.modal-body').html('<button type="button" class="btn btn-primary pull-right" onclick="closeModal();">Close</button><button type="button" id="'+event.lastEventId+'" class="btn btn-primary" onclick="mailStatement(this);" >Mail Statement</button><button type="button"  class="btn btn-primary" id="'+event.lastEventId+'" style="padding-left: 10px" onclick="generateForSouthData(this);">Send via South Data</button>');
    }
};
}
function mailStatement(docID){  
    var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
    $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
    var mailingInformation =  docID.id.split(" ");
    var source = new EventSource("https://hoaboardtime.com/sendInspectionNotice.php?id="+mailingInformation[1]+"&doc_id="+mailingInformation[0]);
    source.onmessage = function(event){
        $("#pleaseWaitDialog2").find('.modal-header').html('<h4 class="modal-title">'+event.data+'</h4>');
        if ( (event.data == "Failed to mail statement. Error: No HOA ID provided.") || (event.data == "Mail sent successfully") ){
        source.close();
        $("#pleaseWaitDialog2").find('.modal-body').html('<button type="button" class="btn btn-primary" onclick="closeModal();">Close</button>');
    }
    };
}
function generateForSouthData(docID){
    var mailingInformation = docID.id.split(" ");
    var hoaIDDownload = mailingInformation[1];
    var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
    $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
    var url = "https://hoaboardtime.com/generateSouthData.php?id="+mailingInformation[1]+"&doc_id="+mailingInformation[0];
    var source = new EventSource(url);
    source.onmessage = function(event){
        $("#pleaseWaitDialog2").find('.modal-header').html('<h4 class="modal-title">'+event.data+'</h4>');
        if ( (event.data == "File will be downloaded shortly.")  ){
        source.close();
        $downloadURL = "https://hoaboardtime.com/downloadFile.php?id="+hoaIDDownload;
        // alert($downloadURL);
        document.location = $downloadURL;
        $("#pleaseWaitDialog2").find('.modal-body').html('<button type="button" class="btn btn-primary" onclick="closeModal();">Ok</button>');
    }
     if ( (event.data == "Failed to generate notice. No HOAID found.") || (event.data == "Document id not found. Try re generating notice.")  ){
        source.close();
        $("#pleaseWaitDialog2").find('.modal-body').html('<button type="button" class="btn btn-primary" onclick="closeModal();">Ok</button>');
    }
    };


}
function saveChanges(){
    jsonObj = [];
    item = {};
    item["inspection_id"] = document.getElementById("inspectionID").value;
    item["inspection_description"] = document.getElementById("inspectionDescription").value;
    item["inspection_category_id"] = document.getElementById("inspection_category_id").value;
    item["inspection_sub_category_id"] = document.getElementById("inspection_sub_category_id").value;
    item["homeID"] = document.getElementById("homeID").value;
    item["hoaID"] = document.getElementById("hoaID").value;
    item["location_id"] = document.getElementById("location_id").value;
    item["inspection_status_id"] = document.getElementById("inspection_status_id").value;
    item["item"] = document.getElementById("item").value;
    jsonObj.push(item);
    $("#myModal").modal("hide");
    var request = new XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/updateInspectionData.php",true);
    request.setRequestHeader("Content-type", "application/json");
    request.send(JSON.stringify(jsonObj));
    showPleaseWait();
    request.onreadystatechange = function(){
        if(request.readyState == XMLHttpRequest.DONE){
            hidePleaseWait();

            alert(request.responseText);
        }
    }
}
function closeModal(){
    $("#pleaseWaitDialog2").modal("hide");
}

function previewAndGenerate(button){
    var fieldData =     '<h4 class="modal-title">Generating Preview...Please Wait...</h4>';
    $("#pleaseWaitDialog2").modal("show");
    $("#pleaseWaitDialog2").find('.modal-header').html(fieldData);
    var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
    $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);

    var url  = "https://hoaboardtime.com/genericNoticeCombine.php?id="+button.id;

    var request  = new XMLHttpRequest();
    request.open("POST", url, true);
    request.setRequestHeader("Content-type", "application/json");
    request.send(null);

    request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
              $("#pleaseWaitDialog2").modal("hide");
              if ( request.responseText.includes("An error occured.") ) {
                swal("An error occured.","","error");
              }
              else {
                var data = JSON.parse(request.responseText.split("@")[0]);
                pdf = data.pdf;
                zip = data.zip;
                hoaid = data.hoaid;
                var fieldData =     '<h4 class="modal-title">Preview Generated</h4>';

                $("#pleaseWaitDialog2").find('.modal-header').html(fieldData);

                var fieldData = '<button type="button" class="btn btn-success" onclick="showPreview()">View</button>\
          <button type="button" class="btn btn-primary pull-right" onclick="sendViaSouthData();" style="padding-left: 5px;">Send Notice(s) - SouthData</button>\
          <button type="button" class="btn btn-primary pull-right" onclick="mailStatement();" style="padding-right: 5px;">Email Notice(s)</button>';
                $("#pleaseWaitDialog2").find('.modal-body').html(fieldData);

                var fieldData = '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';

                $("#pleaseWaitDialog2").find('.modal-footer').html(fieldData);


                $("#pleaseWaitDialog2").modal("show");

              }
        }
    }
}

function sendViaSouthData(){

        $("#pleaseWaitDialog2").modal("hide");
        var fieldData =     '<h4 class="modal-title">Generating Preview...Please Wait...</h4>';
        $("#pleaseWaitDialog2").find('.modal-header').html(fieldData);
        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        jsonData = [];
        item = {};
        item["hoa_id"] = hoaid;
        item["zip_id"] = zip;
        jsonData.push(item);
        sendData = JSON.stringify(jsonData);
        var url  = "https://hoaboardtime.com/mailFileToSouthData.php";
        var request  = new XMLHttpRequest();
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);
        $("#pleaseWaitDialog2").modal("show");
        request.onreadystatechange = function() {
            if ( request.readyState == XMLHttpRequest.DONE ) {
                $("#pleaseWaitDialog2").modal("hide");
                if ( request.responseText.includes("An error occured.") ){
                    swal("Failed to send statement","","error");
                }
                else {
                    swal("Statement Sent","","success");
                }

            }
        }




}

function mailStatement(){

        $("#pleaseWaitDialog2").modal("hide");
        var fieldData =     '<h4 class="modal-title">Generating Preview...Please Wait...</h4>';
        $("#pleaseWaitDialog2").find('.modal-header').html(fieldData);
        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        jsonData = [];
        item = {};
        item["hoa_id"] = hoaid;
        item["pdf_id"] = pdf;
        jsonData.push(item);
        sendData = JSON.stringify(jsonData);
        var url  = "https://hoaboardtime.com/emailFileToSouthData.php";
        var request  = new XMLHttpRequest();
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);
        $("#pleaseWaitDialog2").modal("show");
        request.onreadystatechange = function() {
            if ( request.readyState == XMLHttpRequest.DONE ) {
                $("#pleaseWaitDialog2").modal("hide");
                if ( request.responseText.includes("An error occured.") ){
                    swal("Failed to send statement","","error");
                }
                else {
                    swal("Statement Mailed","","success");
                }

            }
        }

}

function sendCombinedDocumentMail(hoaid){
    $("#myModal2").modal("hide");
    $("#pleaseWaitDialog2").modal("show");
    $("#pleaseWaitDialog2").find('.modal-header').html('<h4 class="modal-title">Please wait...</h4>');
    var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
    $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
    var source = new EventSource("https://hoaboardtime.com/sendCombinedNoticeMail.php?id="+hoaid);
    source.onmessage = function(event){
        $("#pleaseWaitDialog2").find('.modal-header').html('<h4 class="modal-title">'+event.data+'</h4>');
        if ( (event.data == "Failed to mail statement. Error: No HOA ID provided.") || (event.data == "Mail sent successfully") ){
        source.close();
        $("#pleaseWaitDialog2").find('.modal-body').html('<button type="button" class="btn btn-primary" onclick="closeModal();">Close</button>');
    }
    };
}
function sendCombinedDocumentSouthData(hoaid){
    $("#myModal2").modal("hide");
    var hoaIDDownload = hoaid;
    $("#pleaseWaitDialog2").modal("show");
    $("#pleaseWaitDialog2").find('.modal-header').html('<h4 class="modal-title">Please wait...</h4>');
    var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
    $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
    var url = "https://hoaboardtime.com/generateCombinedNoticeSouthData.php?id="+hoaIDDownload;
    var source = new EventSource(url);
    source.onmessage = function(event){
        $("#pleaseWaitDialog2").find('.modal-header').html('<h4 class="modal-title">'+event.data+'</h4>');
        if ( (event.data == "File will be downloaded shortly.")  ){
        source.close();
        $downloadURL = "https://hoaboardtime.com/downloadFile.php?id="+hoaIDDownload;
        // alert($downloadURL);
        document.location = $downloadURL;
        $("#pleaseWaitDialog2").find('.modal-body').html('<button type="button" class="btn btn-primary" onclick="closeModal();">Ok</button>');
    }
     if ( (event.data == "Failed to generate notice. No HOAID found.") || (event.data == "Document id not found. Try re generating notice.")  ){
        source.close();
        $("#pleaseWaitDialog2").find('.modal-body').html('<button type="button" class="btn btn-primary" onclick="closeModal();">Ok</button>');
    }
    };  
}
function loadData(){
    showPleaseWait();
    var request = new XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/getInspectionData.php",true);
    request.send(null);
    request.onreadystatechange = function(){
    if (request.readyState == XMLHttpRequest.DONE){
    let data = request.responseText;
    let data2 = JSON.parse(data);
    var dataSet = new Array();
    for (var i = 0; i < data2.length; i++) {
        var dataSet2 = new Array();
        dataSet2.push('<input type="button" id="'+data2[i]['id']+'" value="Preview & Send" onclick="previewAndGenerate(this)"></input>');
        dataSet2.push(data2[i]['home_id']);
        dataSet2.push(data2[i]['hoa_id']);
        dataSet2.push(data2[i]['description']);
        dataSet2.push(data2[i]['inspection_category_id']);
        dataSet2.push(data2[i]['inspection_sub_category_id'])
        dataSet2.push(data2[i]['location_id']);
        dataSet2.push(data2[i]['inspection_notice_type_id']);
        dataSet2.push(data2[i]['inspection_status']);
        dataSet.push(dataSet2);
}
$(document).ready(function() {
   var table =  $('#example').DataTable( {
        data: dataSet,
        select: true,
        columns: [
            { title: ""},
            { title: "HomeID" },
            { title: "HoaID" },
            { title: "Description",
            "width" : "30%" },
            { title: "Category" },
            { title: "SubCategory" },
            { title: "Location",
            "width" : "15%" },
            { title: "Type" },
            { title: "Status",
            "width" : "20%" }
            
        ]
    } );

     $('#example tbody').on('click', 'td:not(:first-child)', function () {
        $(this).closest('tr').toggleClass('selected');
        $("#button").text("Send  "+table.rows('.selected').data().length+"  notices");   

        if ( table.rows('.selected').data().length == 0 ){
             $("#button").text("Select Rows to send Multiple");
        }
    });

     $('#button').click( function () {
        if ( table.rows('.selected').data().length == 0 ){
             alert("Please select atleast one row.");
             return;
        }
        showPleaseWait();
        var request = new XMLHttpRequest();
        request.open("POST","https://hoaboardtime.sendMultipleNotices.php",true);
        request.send(null);
        request.onreadystatechange = function(){
            if ( request.readyState == XMLHttpRequest.DONE){
                hidePleaseWait();
            }
        }
    });
} );
hidePleaseWait();
}
}
}
function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}
  </script>
  </style>
    </head>
    <body onload="loadData();">
<h1>Inspection Management</h1>
<hr>
<div class="container"> 
        <div>
      <h4>Display:</h4>
      <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="status" onchange="changeOptions2();">
        <option disabled="disabled" selected="selected">Select Status</option>
        <option data-subtext="">Open Inspections</option>
        <option data-subtext="">Closed Inspections</option>
        <option>Board Review</option>
        <option>Archived</option>
        <option>Vendor Review</option>
        <option>Closed By Vendor</option>
        <option>Due From First Notice</option>
        <option>Due From Second Notice</option>
        <option>Closed By Member</option>
        <option>Request Board Review</option>
        <option>Request Vendor Review </option>
        <option>Past Due from First Notice</option>
        <option>Resolved</option>
        <option>Closed by CIS</option>
      </select>
      <br>
      <br>
      <button type="button" id="button" class="btn btn-primary" >Select Rows to send Multiple</button>
        </div>
        <br>
        <div>
        <table id="example" class="display" cellspacing="0" width="100%"></table>   
        </div>
        <br><br>
  </div>
  <div class="modal" id="pleaseWaitDialog2" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Edit Inspection</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="reset" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveChanges();">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          
        </div>
        <div class="modal-body" id="previewBody">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
    </body>
</html>
