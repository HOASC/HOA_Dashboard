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

<!--     <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" /> -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<!--       <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>   -->

    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <style type="text/css">
      body{
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
#content{
  flex: 1;
  background-color: white;
}

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

td {
    padding-top: 5px;
    padding-right: 5px;
    padding-bottom: 5px;
    padding-left: 5px;
}
    </style>
<script type="text/javascript">
var fileData = "";
var fileName = "";
// var objLoc = document.getElementById('pdf');
function updateContent(){
  
  document.getElementById('fileInput').disabled = false;
  var type = $("#fileType").val();

  if ( type == "Legal document" ){
    document.getElementById("legalContent").hidden = false;
    document.getElementById("disclosuresContent").hidden = true;
    document.getElementById("minutesContent").hidden = true;
    document.getElementById("contractsContent").hidden = true;
    document.getElementById("invoicesContent").hidden = true;
  }
  else if ( type == "Disclosure" ){
      document.getElementById("legalContent").hidden = true;
      document.getElementById("minutesContent").hidden = true;
      document.getElementById("disclosuresContent").hidden = false;
      document.getElementById("contractsContent").hidden = true;
      document.getElementById("invoicesContent").hidden = true;
  }
  else if( type == "Minutes" ){
    document.getElementById("minutesContent").hidden = false;
    document.getElementById("legalContent").hidden = true;
    document.getElementById("disclosuresContent").hidden = true;
    document.getElementById("contractsContent").hidden = true;
    document.getElementById("invoicesContent").hidden = true;
  }
  else if ( type == "Contracts" ) {
    document.getElementById("minutesContent").hidden = true;
    document.getElementById("legalContent").hidden = true;
    document.getElementById("disclosuresContent").hidden = true;
    document.getElementById("contractsContent").hidden = false;
    document.getElementById("invoicesContent").hidden = true;

  }
  else if ( type == "Invoices" ) {
    document.getElementById("minutesContent").hidden = true;
    document.getElementById("legalContent").hidden = true;
    document.getElementById("disclosuresContent").hidden = true;
    document.getElementById("contractsContent").hidden = true;
    document.getElementById("invoicesContent").hidden = false;
  }
  else {
      document.getElementById("legalContent").hidden = true;
      document.getElementById("disclosuresContent").hidden = true;
      document.getElementById("minutesContent").hidden = true;
      document.getElementById("invoicesContent").hidden = true;
      document.getElementById("contractsContent").hidden = true;
       
  }
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
  document.getElementById("saveButton").disabled = false;
}



function uploadFile(){
  if($("#fileType").val() == "Legal document"){
      var name = document.getElementById("name").value;
      var shortDesc = document.getElementById("short_desc").value;
      var dateRange = document.getElementById("daterange").value;
      var dates = dateRange.split("-");
      var startDate = dates[0].trim();
      var endDate = dates[1].trim();
      jsonData = [];
      item = {};
      item["uploader_id"] = <?php echo $_SESSION['hoa_user_id']; ?>;
      item["file_name"] = fileName;
      item["name"] = name;
      item["short_desc"] = shortDesc;
      item["valid_from"] = startDate;
      item["valid_until"] = endDate;
      item["file_content"] = fileData;
      item["file_type"] = "legal";
      item["file_sub_category"] = $("#fileSubCategory").find("option:selected").attr("id");
      jsonData.push(item);
      sendData = JSON.stringify(jsonData);
        var request  = new XMLHttpRequest();
        request.open("POST", "https://hoaboardtime.com/uploadFileToDropbox.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);

        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Please wait...</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        $("#pleaseWaitDialog2").modal("show");
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
            $("#pleaseWaitDialog2").modal("hide");
          if (request.responseText == "An error occured."){

              swal({
                    title: 'An error occured',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "error"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });



          }
          else {

                swal({
                    title: 'Record Created',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "success"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });
          }
        }
        }
      } 
      else if ( $("#fileType").val() == "Disclosure" ){
        if ( (document.getElementById('deliveryType').value == 'undefined' ) || (document.getElementById('deliveryType').value == '' ) ){
          swal("Delivery type is empty","","error");
          return;
        }
        jsonData = [];
        item = {};
        item['sub_category'] = $("#disclosureFileSubCategory").find("option:selected").attr("id");
        item['legal_date_from'] = document.getElementById('legalDateActualDate').value.split("-")[0];
        item['legal_date_to'] = document.getElementById('legalDateActualDate').value.split("-")[1];
        item['delivery_type'] = document.getElementById('deliveryType').value;
        item['fiscal_year_start'] = document.getElementById('fiscalYearStartEnd').value.split("-")[0];
        item['fiscal_year_end'] = document.getElementById('fiscalYearStartEnd').value.split("-")[1];
        item['legal_date_until'] = document.getElementById('legalDateUntil').value;
        item['notes'] = document.getElementById("comment").value;
        item['file_content']  = fileData;
        item['file_name'] = fileName;
        item['changed_this_year'] = $("#changedThisYear").val();
        item['file_type'] = "disclosure";
        item['uploader_id'] = <?php echo $_SESSION['hoa_user_id']; ?>;
        jsonData.push(item);
        sendData = JSON.stringify(jsonData);

        var request  = new XMLHttpRequest();
        request.open("POST", "https://hoaboardtime.com/uploadFileToDropbox.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);

        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Please wait...</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        $("#pleaseWaitDialog2").modal("show");
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
            $("#pleaseWaitDialog2").modal("hide");
          if (request.responseText == "An error occured."){

              swal({
                    title: 'An error occured',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "error"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });


          }
          else if ( request.responseText == "Record Created" ){


                swal({
                    title: 'Record Created',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "success"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });



          }

          else {
                swal({
                    title: 'Record Created',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "success"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });
          }
        }
        }
        


      }
      else if ( $("#fileType").val() == "Minutes" ) {
        jsonData = [];
        item = {};
        item['file_type'] = 'minutes';
        if ( typeof $("#boardMeetingList").find("option:selected").attr("id") == 'undefined' ){
          item['board_meeting'] = 'undefined';
        }
        else {
          item['board_meeting'] = $("#boardMeetingList").find("option:selected").attr("id");
        }
        if ( typeof $("#boardMeetingType").find("option:selected").attr("id") == 'undefined' ){
          item['board_meeting_type'] = 'undefined';
        }
        else {
        item['board_meeting_type'] = $("#boardMeetingType").find("option:selected").attr("id");
        }
        item['meeting_minutes_date'] = document.getElementById("daterange").value;
        item['meeting_file_name'] =  fileName;
        item['meeting_file_data'] = fileData;
        item['community_id'] = <?php echo $_SESSION['hoa_community_id']; ?>;
        item['user_id'] = <?php echo $_SESSION['hoa_user_id']; ?>;
        jsonData.push(item);
        sendData = JSON.stringify(jsonData);
        var request  = new XMLHttpRequest();
        request.open("POST", "https://hoaboardtime.com/uploadFileToDropbox.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);

        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Please wait...</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        $("#pleaseWaitDialog2").modal("show");
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
            $("#pleaseWaitDialog2").modal("hide");
            if ( request.responseText == "An error occured."){
              swal({
                    title: 'An error occured',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "error"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });

            }
          else if ( request.responseText == "Success." ){ 
                swal({
                    title: 'Record Created',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "success"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });
          }
        }
        }
      }
      else if ( $("#fileType").val()  == "Contracts" ) {
        jsonData = [];
        item = {};
        item['file_type'] = 'contracts';
        item['date'] = document.getElementById('daterange').value; 
        item['board_approval_id'] =  document.getElementById('boardApprovalId').value;
        item['vendor_id'] =  $("#vendorList").find("option:selected").attr("id");
        item['vendor_type'] = $("#vendorType").find("option:selected").attr("id");
        item['active_contract'] = $("#activeContract").find("option:selected").attr("id");
        item['future_contract'] =  $("#futureContract").find("option:selected").attr("id");
        item['yearly_contract'] = document.getElementById("yearlyAmount").value;
        item['short_desc'] = document.getElementById("shortDesc").value;
        item['desc'] = document.getElementById("description").value;
        item['file_name'] =  fileName;
        item['file_data'] = fileData;
        item['community_id'] = <?php echo $_SESSION['hoa_community_id']; ?>;
        item['user_id'] = <?php echo $_SESSION['hoa_user_id']; ?>;
        jsonData.push(item);
        sendData = JSON.stringify(jsonData);
        var request  = new XMLHttpRequest();
        request.open("POST", "https://hoaboardtime.com/uploadFileToDropbox.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);
        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Please wait...</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        $("#pleaseWaitDialog2").modal("show");
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
            $("#pleaseWaitDialog2").modal("hide");
            if ( request.responseText == "An error occured."){
              swal({
                    title: 'An error occured',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "error"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });
          }
          else if ( request.responseText == "Success." ){
              swal({
                    title: 'Record Created',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "success"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });
          }
        }
        }
      }
      else if ( $("#fileType").val() == "Invoices" ) {
        jsonData = [];
        item = {};
        item['file_type'] =  'invoices';
        item['file_name'] =  fileName;
        item['file_data'] = fileData;
        item['community_id'] = <?php echo $_SESSION['hoa_community_id']; ?>;
        item['user_id'] = <?php echo $_SESSION['hoa_user_id']; ?>;
        item['invoice_id']  = document.getElementById("invoiceID").value;
        item['invoice_date'] = document.getElementById("singleDate").value;
        item['invoice_amount'] = document.getElementById("invoiceAmount").value;
        item['vendor_id'] = $("#vendorList").find("option:selected").attr("id");
        item['work_status'] = '';
        item['payment_status'] = '';
        item['account_number'] = document.getElementById("accountNumber").value;
        item['due_date'] = document.getElementById("dueDate").value;
        item['reserve_expense'] = $("#reserveExpense").val();
        item['valid_until']=  document.getElementById("validUntil").value;
        jsonData.push(item);
        sendData = JSON.stringify(jsonData);  
        var request  = new XMLHttpRequest();
        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Please wait...</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        $("#pleaseWaitDialog2").modal("show");
        request.open("POST", "https://hoaboardtime.com/uploadFileToDropbox.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
          $("#pleaseWaitDialog2").modal("hide");
          if (request.responseText == "An error occured."){


              swal({
                    title: 'An error occured',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "error"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });


          }
          else if ( request.responseText == "Success." ) {
                swal({
                    title: 'Record Created',
                    text: 'Page will refresh automatically',
                    timer: 1000,
                    buttons: false,
                    icon: "success"
                }).then(function() {
                    window.location = "https://hoaboardtime.com/uploadFile.php";
                });
        }
        }
        }
      }
      else {
        swal("Please select a Category","","error");
      }
}

  function getFileDetails(){
    if ( $("#fileType").val() == "Legal document" ){
        document.getElementById("legalRecordExisitsStatus").innerHTML = "";
        jsonData = [];
        item = {};
        item['type'] = "legal";
        item['community_id'] = <?php echo $_SESSION['hoa_community_id'];  ?>;
        item['sub_category'] = $("#fileSubCategory").val();

        jsonData.push(item);

        sendData = JSON.stringify(jsonData);

        var request  = new XMLHttpRequest();
        request.open("POST", "https://hoaboardtime.com/getFileCategoryDetails.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);

        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Please wait...</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        $("#pleaseWaitDialog2").modal("show");
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
            $("#pleaseWaitDialog2").modal("hide");
          if (request.responseText == "An error occured."){
            swal("An error ocuured. Please try again. ","","error");
          }
          else {
              var date23 = request.responseText.split('@');
              document.getElementById('name').value = date23[0];
              document.getElementById('short_desc').value = date23[1];
              $('#daterange').data('daterangepicker').setStartDate(date23[2]);
              $('#daterange').data('daterangepicker').setEndDate(date23[3]);
              if ( request.responseText.includes("Not found") ){
                document.getElementById("legalRecordExisitsStatus").innerHTML = "";
              }
              else 
              {
                document.getElementById("legalRecordExisitsStatus").innerHTML = "A record exisits for current category. <a href=\"https://hoaboardtime.com/documentPreview.php?"+"path="+date23[5]+"&desc=preview\" target=\"_blank\">Click here </a>to view document.";
              }

          }
        }
    }
  }

  else if ( $("#fileType").val() == "Disclosure" ){
        document.getElementById("recordExisitsStatus").innerHTML = "";
        jsonData = [];
        item = {};
        item['type'] = "disclosure";
        item['community_id'] = <?php echo $_SESSION['hoa_community_id'];  ?>;
        item['sub_category'] = $("#disclosureFileSubCategory").val();

        jsonData.push(item);

        sendData = JSON.stringify(jsonData);

        var request  = new XMLHttpRequest();
        request.open("POST", "https://hoaboardtime.com/getFileCategoryDetails.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(sendData);

        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Please wait...</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        $("#pleaseWaitDialog2").modal("show");
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
            $("#pleaseWaitDialog2").modal("hide");
          if (request.responseText == "An error occured."){
            swal("An error ocuured. Please try again. ","","error");
          }
          else {
              var date23 = request.responseText.split('@');
              $('#fiscalYearStartEnd').data('daterangepicker').setStartDate(date23[2]);
              $('#fiscalYearStartEnd').data('daterangepicker').setEndDate(date23[3]);
              $('#legalDateActualDate').data('daterangepicker').setStartDate(date23[2]);
              $('#legalDateActualDate').data('daterangepicker').setEndDate(date23[3]);
              $('#legalDateUntil').data('daterangepicker').setStartDate(date23[3]);
              if ( request.responseText.includes("Not found") ){
                document.getElementById("recordExisitsStatus").innerHTML = "";
              }
              else if ( request.responseText.includes("document missing") ){
                document.getElementById("recordExisitsStatus").innerHTML = "A record exisits for current category. No file found.";
              }
              else 
              {
                
                document.getElementById("recordExisitsStatus").innerHTML = "A record exisits for current category. <a href=\"https://hoaboardtime.com/documentPreview.php?"+"path="+date23[5]+"&desc=preview\" target=\"_blank\">Click here </a>to view document.";
              }
          }
        }
    }
  }

  else if ( $("#fileType").val() == "Minutes" ) {

  }
  }





</script>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

      <?php if($mode == 1) include "boardHeader.php"; ?>
      
      <?php if($mode == 1) include 'boardNavigationMenu.php'; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

          $year = date("Y");
          $month = date("m");
          $end_date = date("t");

          $result = pg_query("SELECT * FROM community_invoices WHERE community_id=$community_id AND reserve_expense='t'");

        ?>
        
        <section class="content-header">

          <h1><strong>Upload file</strong></h1>

        </section>
        <br>
        <section class="content" id="content">
        <div class="row-fluid">
              <label>File Category</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="fileType" onchange="updateContent();">
                      <option data-hidden="true"></option>
                      <option>Legal document</option>
                      <option>Disclosure</option>
                      <option>Minutes</option>
                      <option>Contracts</option>
                      <option>Invoices</option>
              </select>
        </div>
      <br>
      <label class="btn btn-default">Select File<input type="file" id="fileInput" hidden disabled="disabled">      
      </label>
      <button type="button" class="btn btn-success" onclick="uploadFile();" id="saveButton" disabled="disabled">Upload</button>
      <h5 id="label"></h5>
      <br>
      <br>
      <div  class="row" id="legalContent" hidden="hidden">
      <div class="col-xs-6">
      <div class="row-fluid">
      <label>Sub Category</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="fileSubCategory" onchange="getFileDetails();">
                      <option data-hidden="true"></option>
                      <?php
                        $query = "SELECT * from legal_docs_type where community_id=".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);
                        while ($row = pg_fetch_assoc($queryResult)) {
                            echo '<option id="'.$row['id'].'">';
                              echo $row['name'];
                            echo '</option>';
                        }
                      ?>
              </select>
      </div>
      <h5 id="legalRecordExisitsStatus">
      
      </h5>
      <br>
      <label>Valid From - Valid Until </label>
      <input type="text" class="form-control daterange" id="daterange"/>
      <br>
      <div >
        <label for="name">Name</label>
        <input class="form-control" id="name" type="text">
      </div>
      <br>
      <div >
        <label for="short_desc">Short Description</label>
        <input class="form-control" id="short_desc" type="text">
      </div>
      <br>
      </div> 
      <div class="col-xs-6">
          <h5>Existing Documents : </h5>
          <?php 

            $query2 = "SELECT * FROM legal_docs_type where community_id=".$_SESSION['hoa_community_id'];
            $queryResult2  = pg_query($query2);

            $links = array();

            while ($row2 = pg_fetch_assoc($queryResult2)) {

                    $query = "SELECT * FROM DOC_MAPPING WHERE COMMUNITY_ID=".$_SESSION['hoa_community_id']." AND TABLE_NAME='legal_docs_type' AND SUB_CATEGORY='".$row2['name']."'";
                    $queryResult = pg_query($query);   
                    $row = pg_fetch_assoc($queryResult);
                    if ( !(isset($row['valid_from'])) ){
                     $secondQuery = "SELECT * FROM COMMUNITY_LEGAL_DOCS WHERE legal_docs_type_id=(SELECT ID FROM legal_docs_type WHERE name='".$row2['name']."' and community_id=".$_SESSION['hoa_community_id'].") AND COMMUNITY_ID=".$_SESSION['hoa_community_id']." ORDER BY last_updated_on";
                     }
                     else {
                        $secondQuery = "SELECT * FROM COMMUNITY_LEGAL_DOCS WHERE legal_docs_type_id=(SELECT ID FROM legal_docs_type WHERE name='".$row2['name']."' and community_id=".$_SESSION['hoa_community_id'].") AND COMMUNITY_ID=".$_SESSION['hoa_community_id']." and valid_from='".$row['valid_from']."' and valid_until='".$row['valid_until']."' ORDER BY last_updated_on";
                    }
                    $secondQueryResult = pg_query($secondQuery);
                    while( $row24 = pg_fetch_assoc($secondQueryResult) ){
                      $row23 = $row24;
                    }
                    if ( isset($row23) ){
                          if ( $row23['id'] ){
                          if ( $row23['document_id'] ){
                            array_push($links, '<a href="https://hoaboardtime.com/documentPreview.php?path='.$row23['document_id'].'&desc=preview" target="_blank">'.$row2['name'].'</a>');
                          }
                          else {
                            echo "Not found";
                    }
                    }
                    else {
                      echo "Not found";
                    }
                    }
            }

            echo '<table>';

            echo '<tr>';
            echo '<th></th>';
            echo '<th></th>';
            echo '<th></th>';
            echo '</tr>';

            $counter = 0;
            foreach ($links as $key) {
                echo '<tr><td>'.$links[$counter].'</td><td>'.$links[$counter+1].'</td><td>'.$links[$counter+2].'</td></tr>';
                $counter = $counter + 3;
            }
            echo '</table>';
          ?>
      </div> 
      </div>
      <div id="disclosuresContent" class="row" hidden="hidden">
      <div class="col-xs-6">
      <div class="row-fluid">
      <label>Disclosure Type</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="disclosureFileSubCategory" onchange="getFileDetails();">
                      <option data-hidden="true"></option>
                      <?php
                        $query = "SELECT * from disclosure_type where community_id=".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);
                        while ($row = pg_fetch_assoc($queryResult)) {
                            echo '<option id="'.$row['id'].'">';
                              echo $row['name'];
                            echo '</option>';
                        }
                      ?>
              </select>
      </div>
      <h5 id="recordExisitsStatus">
      
      </h5>
      <br>
      <label>Legal date from - Actual Date</label>
      <input type="text" class="form-control daterange" id="legalDateActualDate"/>
      <br>
      <div >
        <label for="deliveryType" onchange="changeState();">Delivery Type</label>
        <input class="form-control" id="deliveryType" type="text">
      </div>
      <br>
      <label>Fiscal Year Start - End</label>
      <input type="text" class="form-control daterange" id="fiscalYearStartEnd"/>
      <br>
      <label>Legal Date Until</label>
      <input type="text" class="form-control daterange" id="legalDateUntil"/>
      <br>
      <div class="form-group" style="width: 35%">
        <label for="comment">Notes:</label>
        <textarea class="form-control" rows="3" id="comment"></textarea>
      </div>
      <div class="row-fluid">
      <label>Changed this year</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="changedThisYear" >
                      <option>TRUE</option>
                      <option>FALSE</option>
              </select>
      </div>
      <br>
       <button type="button" class="btn btn-success" onclick="uploadFile();" id="saveButton2" disabled="disabled">Save without file</button>
      </div>
      <div class="col-xs-6" >
          <h5>Existing Documents : </h5>
          <?php 

            $query2 = "SELECT * FROM disclosure_type where community_id=".$_SESSION['hoa_community_id'];
            $queryResult2  = pg_query($query2);
            $links = array();
            while ($row2 = pg_fetch_assoc($queryResult2)) {

                    $query = "SELECT * FROM DOC_MAPPING WHERE COMMUNITY_ID=".$_SESSION['hoa_community_id']." AND TABLE_NAME='disclosure_type' AND SUB_CATEGORY='".$row2['name']."'";
                    $queryResult = pg_query($query);   
                    $row = pg_fetch_assoc($queryResult);
                    if ( !(isset($row['valid_from'])) ){
                      $secondQuery = "SELECT * FROM community_disclosures where type_id=(select id from disclosure_type where name = '".$row2['name']."' and community_id=".$_SESSION['hoa_community_id'].") and community_id =".$_SESSION['hoa_community_id']."' ORDER BY updated_on";

                     }
                     else {
                        $secondQuery = "SELECT * FROM community_disclosures where type_id=(select id from disclosure_type where name = '".$row2['name']."' and community_id=".$_SESSION['hoa_community_id'].") and community_id =".$_SESSION['hoa_community_id']." and fiscal_year_start='".$row['valid_from']."' and fiscal_year_end='".$row['valid_until']."' ORDER BY updated_on";
                    }
                    $secondQueryResult = pg_query($secondQuery);
                    while( $row24 = pg_fetch_assoc($secondQueryResult) ){
                      $row23 = $row24;
                    }
                    if ( isset($row23) ){
                          if ( $row23['id'] ){
                          if ( $row23['document_id'] ){
                            array_push($links, '<a style="display:inline-block; margin-right:20px;" href="https://hoaboardtime.com/documentPreview.php?path='.$row23['document_id'].'&desc=preview" target="_blank">'.$row2['name'].' ('.$row2['name'].')'.'</a>');

                          }
                          else {
                            echo "Not found";
                    }
                    }
                    else {
                      echo "Not found";
                    }
                    }
            }


            echo '<table>';

            echo '<tr>';
            echo '<th></th>';
            echo '<th></th>';
            echo '<th></th>';
            echo '</tr>';

            $counter = 0;
            foreach ($links as $key) {
                echo '<tr><td>'.$links[$counter].'</td><td>'.$links[$counter+1].'</td><td>'.$links[$counter+2].'</td></tr>';
                $counter = $counter + 3;
            }
            echo '</table>';
          ?>
      </div>


      </div>
      <div class="row" id="minutesContent" hidden="hidden">
      <div class="col-xs-6">
      <div class="row-fluid">
      <label>Board Meeting </label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="boardMeetingList" >
                      <?php
                        $query = "SELECT * from board_meeting where community_id=".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);
                        $counter = 0;
                        while ($row = pg_fetch_assoc($queryResult)) {
                            echo '<option id="'.$row['id'].'">';
                              echo $row['meeting_title'];
                            echo '</option>';
                            $counter = $counter + 1;
                        }
                        if ( $counter == 0 ){
                          echo '<option data-hidden="true">No meetings exisits.</option>';
                        }
                      ?>
              </select>
      </div>
      <br>
      <label>Valid From - Valid Until </label>
      <input type="text" class="form-control daterange"  id="daterange"/>
      <br>
      <div class="row-fluid">
      <label>Board Meeting Type</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="boardMeetingType" >
                      <option data-hidden="true"></option>
                      <?php
                        $query = "SELECT * from board_meeting_type where community_id=".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);
                        while ($row = pg_fetch_assoc($queryResult)) {
                            echo '<option id="'.$row['id'].'">';
                              echo $row['name'];
                            echo '</option>';
                        }
                      ?>
              </select>
      </div>
    </div>
    <div class="col-xs-6">
      <h5>Existing documents :</h5>
              <?php 

              $query = "SELECT * FROM community_minutes WHERE EXTRACT( YEAR FROM created_on) >=".date('Y');
              $queryResult = pg_query($query);

              $links = array();
              $counter = 0;
              while ($row = pg_fetch_assoc($queryResult)) {
                  $subQuery = "SELECT meeting_title,start_date FROM  board_meeting WHERE id=".$row['board_meeting_id'];
                  $subQueryResult = pg_query($subQuery);
                  $subRow = pg_fetch_assoc($subQueryResult);
                  $counter = $counter  + 1;
                  
                  if (isset($subRow['meeting_title'])  ){
                    $name = $subRow['meeting_title'];
                  }
                  else if ( isset($subRow['start_date']) ){
                    $name  = 'Board Meeting - '.$subRow['start_date'];
                  }
                  else {
                    $name = 'Board Meeting '.($counter + 1);
                  }

                  array_push($links,'<a href="https://hoaboardtime.com/documentPreview.php?path='.$row['document_id'].'&desc=preview" target="_blank">'.$name.'</a>');
              }
              if ( $counter == 0  ){
                echo '<br>No documents found.</br>';
              }
              else {

                echo '<table>';
                echo '<tr>';
                echo '<th></th>';
                echo '<th></th>';
                echo '<th></th>';
                echo '</tr>';
                $counter = 0;
                foreach ($links as $key) {
                  echo '<tr><td>'.$links[$counter].'</td><td>'.$links[$counter+1].'</td><td>'.$links[$counter+2].'</td></tr>';
                  $counter = $counter + 3;
                }
                echo '</table>';
              }

        ?>
    </div>
     </div>


      <div class="row" id="contractsContent" hidden="hidden">

      <div class="col-xs-6">
      <label>Active From - Active Until </label>
      <input type="text" class="form-control daterange"  id="daterange"/>
      <br>
      <label>Board Approval ID</label>
      <input type="text" class="form-control" style="width: 35%;"  id="boardApprovalId"/>
      <br>

      <div class="row-fluid">
      <label>Vendor</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="vendorList" >
                      <?php
                        $query = "SELECT * from vendor_master where community_id=".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);
                        $counter = 0;
                        while ($row = pg_fetch_assoc($queryResult)) {
                            echo '<option id="'.$row['vendor_id'].'">';
                              echo $row['vendor_name'];
                            echo '</option>';
                            $counter = $counter + 1;
                        }
                        if ( $counter == 0 ){
                          echo '<option data-hidden="true">No Vendors found.</option>';
                        }
                      ?>
              </select>
      </div>
      <br>
      <div class="row-fluid">
      <label>Vendor Type</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="vendorType" >
                      <option data-hidden="true"></option>
                      <?php
                        $query = "SELECT * from vendor_type";
                        $queryResult = pg_query($query);
                        while ($row = pg_fetch_assoc($queryResult)) {
                            echo '<option id="'.$row['vendor_type_id'].'">';
                              echo $row['vendor_type_name'];
                            echo '</option>';
                        }
                      ?>
              </select>
      </div>

      <br>
      <div class="row-fluid">
      <label>Active Contract</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="activeContract" >
                      <option>YES</option>
                      <option>NO</option>
              </select>
      </div>

      <div class="row-fluid">
      <label>Future Contract</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="futureContract" >
                      <option>YES</option>
                      <option>NO</option>
              </select>
      </div>

      <br>
      <label>Yearly Amount</label>
      <input type="text" class="form-control" style="width: 35%;"  id="yearlyAmount"/>
      <br>
      <label>Short Description</label>
      <input type="text" class="form-control" style="width: 35%;"  id="shortDesc"/>
      <br>
      <label>Description</label>
      <textarea class="form-control" rows="5" style="width: 35%" id="description"></textarea>
      <br>
      </div>
      <div class="col-xs-6">
        <h5>Existing documents : </h5>
        <?php 
              $query = "SELECT * FROM COMMUNITY_CONTRACTS WHERE EXTRACT( YEAR FROM ACTIVE_UNTIL) >=".date('Y');
              $queryResult = pg_query($query);

              $links = array();
              $counter = 0;
              while ($row = pg_fetch_assoc($queryResult)) {
                  $counter = $counter  + 1;
                  array_push($links,'<a href="https://hoaboardtime.com/documentPreview.php?path='.$row['document_id'].'&desc=preview" target="_blank">'.$row['desc'].'</a>');

              }
              if ( $counter == 0  ){
                echo '<br>No documents found.</br>';
              }
              else {
                echo '<table>';
                echo '<tr>';
                echo '<th></th>';
                echo '<th></th>';
                echo '<th></th>';
                echo '</tr>';
                $counter = 0;
                foreach ($links as $key) {
                  echo '<tr><td>'.$links[$counter].'</td><td>'.$links[$counter+1].'</td><td>'.$links[$counter+2].'</td></tr>';
                  $counter = $counter + 3;
                }
                echo '</table>';
              }
        ?>
      </div>
     </div>
      <div class="row" id="invoicesContent" hidden="hidden">
      <div class="col-xs-6">
      <label>Invoice ID</label>
      <input type="text" class="form-control" style="width: 35%;"  id="invoiceID"/>
      <br>
      <label>Invoice Date</label>
      <input type="text" class="form-control daterange"  id="singleDate"/>
      <br>
      <label>Invoice Amount</label>
      <input type="text" class="form-control" style="width: 35%;"  id="invoiceAmount"/>
      <br>

      <div class="row-fluid">
      <label>Vendor</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="vendorList" >
                      <?php
                        $query = "SELECT * from vendor_master where community_id=".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);
                        $counter = 0;
                        while ($row = pg_fetch_assoc($queryResult)) {
                            echo '<option id="'.$row['vendor_id'].'">';
                              echo $row['vendor_name'];
                            echo '</option>';
                            $counter = $counter + 1;
                        }
                        if ( $counter == 0 ){
                          echo '<option data-hidden="true">No Vendors found.</option>';
                        }
                      ?>
              </select>
      </div>
      <br>
      <label>Account Number</label>
      <input type="text" class="form-control" style="width: 35%;"  id="accountNumber"/>
      <br>
      <label>Due Date</label>
      <input type="text" class="form-control daterange"  id="dueDate"/>
      <br>
      <div class="row-fluid">
      <label>Reserve Expense</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="reserveExpense" >
                      <option>TRUE</option>
                      <option>FALSE</option>
              </select>
      </div>
      <br>
      <label>Valid Until</label>
      <input type="text" class="form-control daterange"  id="validUntil"/>
      </div>
      <div class="col-xs-6">
        <center><label id="preview">Select a file to load preview</label></center>
        <div id="embedDiv">
            
        </div>
      </div>
      </div>
      </div>
      <br>
      <br>
      <script type="text/javascript">
        document.getElementById('fileInput').onchange = function () {
          var f =  this.value;
          f = f.replace(/.*[\/\\]/, '');
          fileName  = f;
          var res = f.split(".");
          document.getElementById("name").value = res[res.length-2];
          document.getElementById("saveButton").disabled = false;
          document.getElementById("label").innerHTML = fileName;
          document.getElementById("preview").innerHTML = "Preview";
          var divHeight = document.getElementById('invoicesContent').clientHeight;
          document.getElementById("embedDiv").innerHTML = '<embed src="'+window.URL.createObjectURL(new Blob([this.files[0]], {"type":"application/pdf"}))+'" width="100%" height="400px" name="plugin" id="embdLink" />';
          document.getElementById("embdLink").height = divHeight;
          // $('#embdLink')[0].src = window.URL.createObjectURL(new Blob([this.files[0]], {"type":"application/pdf"}));
          // document.getElementById("embdLink").src = window.URL.createObjectURL(new Blob([this.files[0]], {"type":"application/pdf"}));
          getFileData();
        };
      </script>
          </section>
    </div>
      </div>
      <?php include 'footer.php'; ?>
      <div class="control-sidebar-bg"></div>
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
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/demo.js"></script>
<script>
$(document).ready(
     function changeState(){
    if ( document.getElementById('deliveryType').value != "" ){
      document.getElementById("saveButton2").disabled = false;
    }
    else {
      document.getElementById("saveButton2").disabled = true;
    }
  }

);
</script>

      <script type="text/javascript">
            $('.daterange').daterangepicker({
              showDropdowns: true
            });
            $("#singleDate").daterangepicker({
              singleDatePicker: true,
              showDropdowns: true
            });
            $("#dueDate").daterangepicker({
              singleDatePicker: true,
              showDropdowns: true
            });
            $("#validUntil").daterangepicker({
              singleDatePicker: true,
              showDropdowns: true
            });
            $("#legalDateUntil").daterangepicker({
              singleDatePicker: true,
              showDropdowns: true
            });
            $("#deliveryType").change(function(){
                if ( $(this).val() ){
                document.getElementById("saveButton2").disabled = false;
                }
            });
      </script>
  </body>

</html>