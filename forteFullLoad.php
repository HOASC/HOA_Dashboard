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
    <title>Transactions Search</title>
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

function changeOptions2(button){
    showPleaseWait();
    if( button.id == 1 ){
    $('#example').dataTable().fnClearTable();
    var request = new XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/forteFullLoadDetails.php?id="+button.id+"&data1="+document.getElementById("data1").value,true);
    request.send(null);
    request.onreadystatechange = function(){
    if (request.readyState == XMLHttpRequest.DONE){
    let data = request.responseText;
    let data2 = JSON.parse(data);
    var dataSet = new Array();
    for (var i = 0; i < data2.length; i++) {
        var dataSet2 = new Array();
        dataSet2.push(data2[i]['customer_id']);
          var name = data2[i]['first_name']+' '+data2[i]['last_name'];
        dataSet2.push(name);
        dataSet2.push(data2[i]['authorization_amount']);
        dataSet2.push(data2[i]['authorization_code'])
        dataSet2.push(data2[i]['received_date']);
        dataSet2.push(data2[i]['entered_by']);
        dataSet2.push(data2[i]['action']);
        dataSet2.push(data2[i]['status']);
        dataSet2.push(data2[i]['transction_id']);
        if  ( data2[i]['is_inserted'] == "Found" ){
            dataSet2.push("    ");
        }
        else {
        dataSet2.push(data2[i]['is_inserted']);
        }
        dataSet.push(dataSet2);
    }
    if (dataSet.length > 0){
    $('#example').dataTable().fnAddData(dataSet);
    }
    else {
        alert("Data not found");
    }
    hidePleaseWait();
}
} 
    }

    else if  ( button.id == 2 ){
    $('#example').dataTable().fnClearTable();
    var request = new XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/forteFullLoadDetails.php?id="+button.id+"&data1="+document.getElementById("data1").value+"&data2="+document.getElementById("data2").value,true);
    request.send(null);
    request.onreadystatechange = function(){
    if (request.readyState == XMLHttpRequest.DONE){
    let data = request.responseText;
    let data2 = JSON.parse(data);
    var dataSet = new Array();
    for (var i = 0; i < data2.length; i++) {
        var dataSet2 = new Array();
        dataSet2.push(data2[i]['customer_id']);
          var name = data2[i]['first_name']+' '+data2[i]['last_name'];
        dataSet2.push(name);
        dataSet2.push(data2[i]['authorization_amount']);
        dataSet2.push(data2[i]['authorization_code'])
        dataSet2.push(data2[i]['received_date']);
        dataSet2.push(data2[i]['entered_by']);
        dataSet2.push(data2[i]['action']);
        dataSet2.push(data2[i]['status']);
        dataSet2.push(data2[i]['transction_id']);
         if  ( data2[i]['is_inserted'] == "Found" ){
            dataSet2.push("    ");
        }
        else {
        dataSet2.push(data2[i]['is_inserted']);
        }
        dataSet.push(dataSet2);
    }
    if (dataSet.length > 0){
    $('#example').dataTable().fnAddData(dataSet);
    }
    else {
        alert("Data not found");
    }
    hidePleaseWait();
    } 
    }
}

    else if  ( button.id == 3 ){
    $('#example').dataTable().fnClearTable();
    var request = new XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/forteFullLoadDetails.php?id="+button.id+"&data1="+document.getElementById("data1").value,true);
    request.send(null);
    request.onreadystatechange = function(){
    
    if (request.readyState == XMLHttpRequest.DONE){
    let data = request.responseText;
    let data2 = JSON.parse(data);
    var dataSet = new Array();
    for (var i = 0; i < data2.length; i++) {
        var dataSet2 = new Array();
        dataSet2.push(data2[i]['customer_id']);
          var name = data2[i]['first_name']+' '+data2[i]['last_name'];
        dataSet2.push(name);
        dataSet2.push(data2[i]['authorization_amount']);
        dataSet2.push(data2[i]['authorization_code'])
        dataSet2.push(data2[i]['received_date']);
        dataSet2.push(data2[i]['entered_by']);
        dataSet2.push(data2[i]['action']);
        dataSet2.push(data2[i]['status']);
        dataSet2.push(data2[i]['transction_id']);
         if  ( data2[i]['is_inserted'] == "Found" ){
            dataSet2.push("    ");
        }
        else {
        dataSet2.push(data2[i]['is_inserted']);
        }
        dataSet.push(dataSet2);
    }
    if (dataSet.length > 0){
    $('#example').dataTable().fnAddData(dataSet);
    }
    else {
        alert("Data not found");
    }
    hidePleaseWait();
    }
    }
}

    else if ( button.id == 4){
    $('#example').dataTable().fnClearTable();
    var request = new XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/forteFullLoadDetails.php?id="+button.id+"&data1="+document.getElementById("data1").value,true);
    request.send(null);
    request.onreadystatechange = function(){
    
    if (request.readyState == XMLHttpRequest.DONE){
    let data = request.responseText;
    let data2 = JSON.parse(data);
    var dataSet = new Array();
    for (var i = 0; i < data2.length; i++) {
        var dataSet2 = new Array();
        dataSet2.push(data2[i]['customer_id']);
        var name = data2[i]['first_name']+' '+data2[i]['last_name'];
        dataSet2.push(name);
        dataSet2.push(data2[i]['authorization_amount']);
        dataSet2.push(data2[i]['authorization_code'])
        dataSet2.push(data2[i]['received_date']);
        dataSet2.push(data2[i]['entered_by']);
        dataSet2.push(data2[i]['action']);
        dataSet2.push(data2[i]['status']);
        dataSet2.push(data2[i]['transction_id']);
        if  ( data2[i]['is_inserted'] == "Found" ){
            dataSet2.push("    ");
        }
        else {
        dataSet2.push(data2[i]['is_inserted']);
        }
        dataSet.push(dataSet2);
    }
    if (dataSet.length > 0){
    $('#example').dataTable().fnAddData(dataSet);
    }
    else {
        alert("Data not found");
    }
    hidePleaseWait();
    }
    }

}  

}
function closeModal(){
    $("#pleaseWaitDialog2").modal("hide");
}

$(document).ready(function() {
   var table =  $('#example').DataTable( {
        select: true,
        columns: [
            { title: "HOAID " },
            { title: "Name",
            "width" : "25%" },
            { title: "Amount" },
            { title: "AuthorizationCode" },
            { title: "Date ",
            "width" : "15%" },
            { title: "Entered by ",
            "width" : "15%" },
            { title: "Action",
            "width" : "7%" },
            { title: "Status",
            "width" : "15%" },
            { title: "TransactionID" },
            { title: "           ",
            "width" : "10%" }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    if ( aData[9] == "    " )
                    {
                        $('td', nRow).css('background-color', '#cce0cc');
                    }
                }
    } );
} );

function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}

$(window).ready(function() {
    $('#hoaid').closest('label').click(function() { 
        changeOptions3(1);
    });
    $('#fname').closest('label').click(function() { 
        changeOptions3(3);
    });
    $('#lname').closest('label').click(function() { 
        changeOptions3(4);
    });
});


  </script>
</head>
    <body>
<h1>Forte Transactions</h1>
<hr>
<div class="container"> 
        <div>
      <h4>Search by:</h4>
    <div class="btn-group" data-toggle="buttons" > 
    <label class="btn btn-default">
        <input id="hoaid" type="radio"> HOA ID
    </label>
     <label class="btn btn-default"> 
        <input id="fname" type="radio"> First Name
    </label>
     <label class="btn btn-default"> 
        <input id="lname" type="radio"> Last Name
    </label>
    </div>
      <br>
      <br>
    </div>
        <div id="search">
            
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
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Please wait...</h4>
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

  <script type="text/javascript">
      function changeOptions3(id){
    showPleaseWait();
    $('#example').dataTable().fnClearTable();
    if ( id == 1 ){
        data = '<label for="exampleInputEmail1">Enter HOA ID </label>\
    <input type="text" style="width: 35%;" class="form-control" id="data1" aria-describedby="emailHelp" placeholder="">\
    <br><button type="button" id="1" class="btn btn-success" onclick="changeOptions2(this);">Get Transactions</button>';
    document.getElementById("search").innerHTML = data;
    }
    else if ( id == 2 ){
         data = '<label for="exampleInputEmail1">Enter First Name </label>\
    <input type="text" style="width: 35%;" class="form-control" id="data1" aria-describedby="emailHelp" placeholder="">\
    <label for="exampleInputEmail1">Enter Last Name </label>\
    <input type="text" style="width: 35%;" class="form-control" id="data2" aria-describedby="emailHelp" placeholder="">\
    <br><button type="button" id="2" class="btn btn-success" onclick="changeOptions2(this);" >Get Transactions</button>';
    document.getElementById("search").innerHTML = data;
    }
    else if ( id == 3 ){
        data = '<label for="exampleInputEmail1">Enter First Name </label>\
    <input type="text" style="width: 35%;" class="form-control" id="data1" aria-describedby="emailHelp" placeholder="">\
    <br><button type="button" id="3" class="btn btn-success" onclick="changeOptions2(this);" >Get Transactions</button>';
    document.getElementById("search").innerHTML = data;
    }
    else if ( id == 4 ){
        data = '<label for="exampleInputEmail1">Enter Last Name </label>\
    <input type="text" style="width: 35%;" class="form-control" id="data1" aria-describedby="emailHelp" placeholder="">\
    <br><button type="button" id="4" onclick="changeOptions2(this)" class="btn btn-success">Get Transactions</button>';
    document.getElementById("search").innerHTML = data;
    }
    hidePleaseWait(); 
}
  </script>
    </body>
</html>
