<html>
  <head>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
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
/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
[hidden] {
  display: none !important;
}
.disabledbutton {
    pointer-events: none;
    opacity: 0.4;
}
</style>
<script type="text/javascript">
var fileData  = "";
var fileName = "";
var x = 0;
function getFileData()
{
  var file = document.getElementById("fileInput").files[0];
  if ( file ){
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function (evt) {
        fileData =evt.target.result.split(',')[1];
        x = 1;
        document.getElementById("agreementTitle").value  = fileName.split('.')[0];
        $("#docSelection").addClass("disabledbutton");
        return fileData;
    }
    reader.onerror = function (evt) {
        fileData = "Error";
        x = 0;
        return fileData;
    }
}
}

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
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 100%">\
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
<?php
include 'includes/dbconn.php';
$hoaidquery = "SELECT * FROM HOAID WHERE COMMUNITY_ID=2";
        $hoaidqueryresult = pg_query($hoaidquery);
        $hoaIDArray = array();
        $userEmails = array();
        while ($row = pg_fetch_assoc($hoaidqueryresult)) {
          $name = $row['firstname'];
          $name = $name.' ';
          $name = $name.$row['lastname'];
         $hoaIDArray[$row['hoa_id']]  = $name;
         $userEmails[$row['hoa_id']] = $row['email'];
        }
?>
  function calc()
{
  document.getElementById("authPassword").disabled = !(document.getElementById("authPassword").disabled);
}
function changeEmail(){
  showPleaseWait();
  var selectedHoaID = $("#hoaID").find("option:selected").text();
  var request = new XMLHttpRequest();
  request.open("POST","https://www.hoaboardtime.com/getEmails.php",true);
  request.send(selectedHoaID);
  request.onreadystatechange = function (){
      if (request.readyState == XMLHttpRequest.DONE) {
        hidePleaseWait();
            if ( request.responseText == "Failed to connect to database"){
                alert("Failed to connect to database.Please try again");
                return;
            }
            else if (request.responseText == "An error occured" ){
              alert(request.responseText);
              return;
            }
            var json = JSON.parse(request.responseText);
            var str = "";
            for ( var i = 0 ;i<json.length;i++){
               str = str.concat(json[i].email);
               str = str.concat(" ");
            }
            document.getElementById("emails").value =  str; 
        }
  }
}
function sendData(){

  if ( Boolean(x) ){
    var selectedEmails = document.getElementById("emails").value;
  if ( !selectedEmails ){
    alert("One or more required fields empty");
    return;
  }
  var selectedHoaID = $("#hoaID").find("option:selected").text();
  var agreementTitle = document.getElementById('agreementTitle').value;
  var ccEmails = document.getElementById('ccEmails').value;
  var signatureType  = document.getElementById('signatureType').value;
  var role = document.getElementById('signerRole').value;
  var signatureFlow = document.getElementById('signatureFlow').value;
  var customMessage = document.getElementById('customMessage').value;
  var completeInOrder = $('#completeInOrder').is(':checked');
  var enablePassword = $('#enablePassword').is(':checked');
  var setPassword = document.getElementById('authPassword').value;
  if ( enablePassword && !setPassword){
    alert("One or more required fields empty");
  }
  if ( !emails ){
    alert("One or more required fields is empty");
  }
  else {
  jsonObj = [];
  item = {};
  item["file_data"] =  fileData;
  item["agreementTitle"] = agreementTitle;
  item["emailAddresses"] = selectedEmails;
  item["ccAddresses"] = ccEmails;
  item["signType"] = signatureType;
  item["roleType"] = role;
  item["signFlow"] = signatureFlow;
  item["customMessage"] = customMessage;
  item["completeInOrder"] = completeInOrder;
  item["passwordStatus"] = enablePassword;
  item["setPassword"] = setPassword;
  item["hoaID"] = selectedHoaID;
  jsonObj.push(item);
  lol =  JSON.stringify(jsonObj);
  var request= new XMLHttpRequest();
  request.open("POST", "https://www.hoaboardtime.com/adobeSign3.php", true);
  request.setRequestHeader("Content-type", "application/json");
  request.send(lol);
  showPleaseWait();
  request.onreadystatechange = function () {
        if (request.readyState == XMLHttpRequest.DONE) {
            hidePleaseWait();
            // alert(request.responseText);
            if ( request.responseText.includes("An error occured") ){
              swal("An error occured",request.responseText.split('^')[1],"error");
            }
            else {
              swal("Agreement Created","Agreement ID is "+request.responseText,"success");
            }
        }
  }
  }

  }



  else {
  var documentCategory = document.getElementById('documentCategory').value;
  if ( documentCategory == ""){
    alert("One or more required fields empty");
    return;
  }
  var selectedDocument = $("#documentType").find("option:selected").text();
  if ( selectedDocument == ""){
    alert("One or more required fields empty");
    return;
  }
  var selectedEmails = document.getElementById("emails").value;
  if ( !selectedEmails ){
    alert("One or more required fields empty");
    return;
  }
  var selectedHoaID = $("#hoaID").find("option:selected").text();
  var agreementTitle = document.getElementById('agreementTitle').value;
  var documentName = document.getElementById('documentType').value;
  var ccEmails = document.getElementById('ccEmails').value;
  var signatureType  = document.getElementById('signatureType').value;
  var role = document.getElementById('signerRole').value;
  var signatureFlow = document.getElementById('signatureFlow').value;
  var customMessage = document.getElementById('customMessage').value;
  var completeInOrder = $('#completeInOrder').is(':checked');
  var enablePassword = $('#enablePassword').is(':checked');
  var setPassword = document.getElementById('authPassword').value;
  if ( enablePassword && !setPassword){
    alert("One or more required fields empty");
  }
  if ( !emails ){
    alert("One or more required fields is empty");
  }
  else {
  jsonObj = [];
  item = {};
  item["documentCategory"] = documentCategory;
  item["documentName"]  = documentName;
  item["agreementTitle"] = agreementTitle;
  item["emailAddresses"] = selectedEmails;
  item["ccAddresses"] = ccEmails;
  item["signType"] = signatureType;
  item["roleType"] = role;
  item["signFlow"] = signatureFlow;
  item["customMessage"] = customMessage;
  item["completeInOrder"] = completeInOrder;
  item["passwordStatus"] = enablePassword;
  item["setPassword"] = setPassword;
  item["hoaID"] = selectedHoaID;
  jsonObj.push(item);
  lol =  JSON.stringify(jsonObj);
  var request= new XMLHttpRequest();
  request.open("POST", "https://www.hoaboardtime.com/adobeSign2.php", true);
  request.setRequestHeader("Content-type", "application/json");
  request.send(lol);
  showPleaseWait();
  request.onreadystatechange = function () {
        if (request.readyState == XMLHttpRequest.DONE) {
            hidePleaseWait();
            alert(request.responseText);
        }
        }
  }
}



//  var documentName = document.getElementById('documentType').value;
//  var agreementTitle = document.getElementById('agreementTitle').value;
//  var emailAddresses = document.getElementById('emails').value;
//  var ccAddresses = document.getElementById('ccEmails').value;
//  var signType = document.getElementById('signatureType').value;
//  var roleType = document.getElementById('signerRole').value;
//  var signFlow = document.getElementById('signatureFlow').value;
//  var customeMessage = document.getElementById('customMessage').value;
//  var completeInOrder  = $('#completeInOrder').is(':checked');
//  var passwordStatus = $('#enablePassword').is(':checked');
//  var setPassword = document.getElementById('authPassword').value;
// if( documentName == "" || emailAddresses == ""){
//   window.alert("One or more required fileds empty");
//   return;
// }
// if ( passwordStatus && !setPassword){
//   window.alert("Password cannot be empty");
// }

}
function updateName(){
  document.getElementById("agreementTitle").value = $("#documentType").find("option:selected").text();
  $("#fileUpload").addClass("disabledbutton");

}
function changeOptions(){
$("#documentType").find('option').remove();
$("#documentType").selectpicker('refresh');
var selectedHoaID = $("#documentCategory").find("option:selected").text();
if ( selectedHoaID){
  jsonObj = [];
  item = {};
  item["documentName"] = "1";
  jsonObj.push(item);
  lol =  JSON.stringify(jsonObj);
  var request= new XMLHttpRequest();
  if( selectedHoaID == "Library Document"){
  request.open("POST", "https://www.hoaboardtime.com/getLibraryDocuments.php", true);
  }
  else {
   request.open("POST", "https://www.hoaboardtime.com/getTransientDocuments.php", true); 
  }
  request.setRequestHeader("Content-type", "application/json");
  showPleaseWait();
  request.send(lol);
  request.onreadystatechange = function () {
        if (request.readyState == XMLHttpRequest.DONE) {
          hidePleaseWait();
            var jsonData = JSON.parse(request.responseText);
            $("#documentType").append('<option selected="true" disabled="disabled"></option>');
            document.getElementById("documentType").options[0].disabled = false;
            for(i=0 ; i<jsonData.length ; i++){
            $("#documentType").append('<option >'+jsonData[i]+'</option>');
            }
            $("#documentType").selectpicker('refresh');
        }
    }
}
else {
  window.alert("Please select a valid document category");
}
updateName();
}
</script>
  </head>
      <h2>Adobe Sign - Send Agreement</h2>
      <hr/>
    <div class="container">
      <h4>DOCUMENT SELECTION</h4>
      <hr>

    <div id="docSelection">
    <u><h4>Choose Existing Document</h4></u>
    <br>
    <div class="row-fluid" style="float: left;">
      <h5>Type of document</h5>
      <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="documentCategory" onchange="changeOptions();">
        <option></option>
        <option data-subtext="Can be prefilled">Transient Document</option>
        <option data-subtext="Can not be prefilled">Library Document</option>
      </select>
    </div>
    <div class="row-fluid" style="float: left;padding-left: 10">
      <h5>Select document to send</h5>
      <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="documentType" onchange="updateName();">
      </select>
    </div>
    <div style="clear: both;"></div>
  </div>
    <h4><center>OR</center><h4>
    <div id="fileUpload">
      <u><h4>Upload New Document</h4></u>
      <br>
      <h4 id="label"></h4>
      <label class="btn btn-default" >
      Browse <input type="file" id="fileInput" hidden>
      </label>
    </div>
     <script type="text/javascript">
        document.getElementById('fileInput').onchange = function () {
          var f =  this.value;
          f = f.replace(/.*[\/\\]/, '');
          fileName  = f;
          document.getElementById("label").innerHTML = "Selected File : "+f;
          getFileData();
        };
      </script>
    <br>
    <h4>RECIPIENT SELECTION</h4>
      <hr>
    <div class="row-fluid">
      <h5>Select HOA ID</h5>
      <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="hoaID" onchange="changeEmail();">
      <?php
        echo '<option></option>';
        foreach ($hoaIDArray as $key => $value) {
          echo '<option data-subtext="'.$value.'">'.$key.'</option>';
        }
      ?>
      </select>
    </div>
      <div style="width: 35%;">
      <h5>Email(s)</h5>
      <input type="email" class="form-control" id="emails" aria-describedby="emailHelp" placeholder="Enter email" >
      <small id="emailHelp" class="form-text text-muted">Email is filled automatically. Change if incorrect</small>
      </div>
      <br>
      <br> 
      <h4>AGREEMENT CONFIGURATION</h4>
      <hr>
      <div class="form-group">
        <label for="Agreement Title">Enter Agreement Title</label>
      <input type="text" class="form-control" id="agreementTitle" aria-describedby="titleHelp" placeholder="Enter Title" style="width: 35%">
      <small id="titleHelp" class="form-text text-muted">This will appear in subject of email being sent</small>
      <br>
      <label for="emails">CCS</label>
      <input type="email" class="form-control" id="ccEmails" aria-describedby="ccHelp" placeholder="Enter email" style="width: 35%">
      <small id="ccHelp" class="form-text text-muted">Enter multiple emails seperated by space</small>
      </div>
      <span class="help-inline"></span>
      <div class="row-fluid" style="float: left;">
      <h4>Signature Type</h4>
      <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="signatureType">
        <option data-subtext="">ESIGN</option>
        <option data-subtext="">WRITTEN</option>
      </select>
    </div>
    <div class="row-fluid" style="float: left;padding-left: 10">
      <h4>Role</h4>
      <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="signerRole">
        <option data-subtext="">SIGNER</option>
        <option data-subtext="">APPROVER</option>
        <option data-subtext="">ACCEPTOR</option>
        <option data-subtext="">FORM_FILLER</option>
        <option data-subtext="">CERTIFIED_RECIPIENT</option>
        <option data-subtext="">DELEGATE_TO_SIGNER</option>
        <option data-subtext="">DELEGATE_TO_APPROVER</option>
        <option data-subtext="">DELEGATE_TO_ACCEPTOR</option>
        <option data-subtext="">DELEGATE_TO_FORM_FILLER</option>
        <option data-subtext="">DELEGATE_TO_CERTIFIED_RECIPIENT</option>
      </select>
    </div>
    <div class="row-fluid" style="float: left; padding-left:10;">
      <h4>Signature Flow</h4>
      <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="signatureFlow">
        <option data-subtext="">SENDER_SIGNATURE_NOT_REQUIRED</option>
        <option data-subtext="">SENDER_SIGNS_LAST</option>
        <option data-subtext="">SENDER_SIGNS_FIRST</option>
        <option data-subtext="">SEQUENTIAL</option>
        <option data-subtext="">PARALLEL</option>
        <option data-subtext="">SENDER_SIGNS_ONLY</option>
      </select>
    </div>
    <div style="clear: both;padding-left: 10dp;"></div>
    <div class="form-group">
          <h4>Custom Message</h4>
          <textarea class="form-control" rows="5" id="customMessage" style="width: 35%"></textarea>
    </div>
      <div>
      <h4>Complete in order</h4>
      <label class="switch" >
        <input type="checkbox" id="completeInOrder" >
        <span class="slider round"></span>
      </label>
      </div>
      <div>
      <h4>Enable Password ?</h4>
      <label class="switch" >
        <input type="checkbox" id="enablePassword" onclick="calc();">
        <span class="slider round"></span>
      </label>
      <input type="password" class="form-control" id="authPassword" aria-describedby="passwordhelp" placeholder="Enter password" disabled="disabled" style="width: 35%">
      <small id="passwordhelp" class="form-text text-muted">Signer needs to enter this password berfore signing</small>
      </div>
      <div style="clear: both;"></div>
      <br>
      <button type="button" class="btn btn-primary btn-md" onclick="sendData();">Send for signature</button>
  </div>
</html>