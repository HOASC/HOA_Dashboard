<html>
  <head>
  <title>Create Payment</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src='https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js'></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src='https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js'></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript">
    <?php
    include 'includes/dbconn.php';
    ?>
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

function payNow(){

  var letters = /^[A-Za-z]+$/;  
  var format = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;



  if ( document.getElementById("routingNumber").value.match(letters) || document.getElementById("routingNumber").value.match(format) ){
    alert("Invalid routing number");
    return;
  }

  if ( document.getElementById("accountNumber").value.match(letters) || document.getElementById("accountNumber").value.match(format) ){
    alert("Invalid Account  Number");
    return;
  }
  
  var input = document.getElementById("auth_amount").value;
    if(!((!isNaN(parseFloat(input)))&& input > 0)) {
    alert("Invalid amount");  
}

  if ( !document.getElementById("routingNumber").value ){
    alert("Invalid routing number");
    return;
  }

  if ( !document.getElementById("accountNumber").value ){
    alert("Invalid Account  Number");
    return;
  }
  showPleaseWait();
  jsonObj = [];
  item = {};
  item["customer_id"] = document.getElementById("customerId").value;
  item["auth_amount"] = document.getElementById("auth_amount").value;
  item["routing_number"] = document.getElementById("routingNumber").value;
  item["account_number"] = document.getElementById("accountNumber").value;
  item["account_holder"] = document.getElementById("accountHolder").value;
  jsonObj.push(item);
  lol = JSON.stringify(jsonObj);
  var request  = new  XMLHttpRequest();
  request.open("POST","https://hoaboardtime.com/processPayment.php",true);
  request.send(lol.toString());
  request.onreadystatechange = function(){
    if ( request.readyState == XMLHttpRequest.DONE ){
      hidePleaseWait();
      // alert(request.responseText);
      var response = request.responseText.split(" ");
      if ( response[0] == "Success" && response[1] != "DUPLICATE"){
        var message = response.slice(1,response.length-1);
            swal({
      title: "Success",
      text: "Payment Status : "+message+".",
      icon: "success",
      button: "Ok",
    });
      }
      else {
      swal({
      title: "Error",
      text: "Payment Status : "+response[1]+".",
      icon: "error",
      button: "Ok",
    });
      }
    }
    hidePleaseWait();
  }
}
function verifyUser(){
  document.getElementById("paymentPage").hidden = true;
var $input = $('#refresh');
    $input.val() == 'yes' ? location.reload(true) : $input.val('yes');
var url = "https://hoaboardtime.com/verifyUser.php?id="+<?php echo $_REQUEST['id'];?>;
var comID = <?php
  $query = "SELECT COMMUNITY_ID FROM HOAID WHERE HOA_ID = ".$_GET['id'];
  $queryResult = pg_query($query);
  $row = pg_fetch_assoc($queryResult);
  if ( $row['community_id'] )
  echo $row['community_id'];
  else {
    echo "null";
  }
?> || null;
if ( comID != 2 ||  !(comID) ) {
  error();
  return;
}
$("#pleaseWaitDialog2").find('.modal-header').html('<h4>Please wait</h4>');
var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
$("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
$("#pleaseWaitDialog2").modal("show");
var source = new EventSource(url);
source.onmessage = function(event){
        $("#pleaseWaitDialog2").find('.modal-header').html('<h4>'+event.data+'</h4>');
        if ( (event.data == "email") ){
        var hoaID = <?php echo $_REQUEST['id']; ?>;
        source.close();
        var data  = event.lastEventId.split(' ');
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Verify to continue</h3>')
        $("#pleaseWaitDialog2").find('.modal-body').html('<div><h4><span class="notbold">Enter your email  to verify. </span><b>'+data[1]+'</b></h4><br><div class="form-group">\
    <input class="form-control input-lg" id="verifydata" type="text" maxlength="'+data[0]+'">\
  </div><br><button type="button" class="btn btn-success btn-lg" onclick="verifyDetails('+hoaID+');">Verify</button></div>');
        }
        if (  (event.data == "number") ){
        var hoaID = <?php echo $_REQUEST['id']; ?>;
        source.close();
        var data  = event.lastEventId.split(' ');
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Verify to continue</h3>')
        $("#pleaseWaitDialog2").find('.modal-body').html('<div><h4><span class="notbold">Enter your phone number to verify.</span><b>'+data[1]+'</b></h4><br><div class="form-group">\
    <input class="form-control input-lg" id="verifydata" type="text" onkeypress="return isNumberKey(event)" maxlength="'+data[0]+'">\
  </div><br><button type="button" class="btn btn-success btn-lg" onclick="verifyDetails('+hoaID+');">Verify</button></div>');
        }
}
}
function verifyDetails(hoaid){
  showPleaseWait();
  var url = "https://hoaboardtime.com/verifyUserData.php?id="+hoaid+"&data="+document.getElementById("verifydata").value;
  var source = new EventSource(url);
  source.onmessage = function(event){
    if ( (event.data == "success") ){
      source.close();
      hidePleaseWait();
      $("#pleaseWaitDialog2").modal("hide");
      document.getElementById("paymentPage").hidden = false;
      swal({
      title: "Success",
      text: "Verified Successfully!",
      icon: "success",
      button: "Ok",
    });
    }
    else if ( (event.data == "failed") ){
      source.close();
      hidePleaseWait();
       swal({
      title: "Failed",
      text: "Verification Failed",
      icon: "error",
      confirmButtonClass: 'btn-success',
      confirmButtonText: 'Ok',
    });
    }
  }
}
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function closeModal(){
  $("#pleaseWaitDialog2").modal("hide");
}

function error(){
   swal({
      title: "Failed",
      text: "HOA ID NOT FOUND FOR THIS COMMUNITY.",
      icon: "error",
      confirmButtonClass: 'btn-success',
      confirmButtonText: 'Ok',
    })
   .then((willDelete) => {
  if (willDelete) {
    window.location = "https://hoaboardtime.com"
  } else {
  }
});
}
</script>
<style type="text/css">
  .notbold{
    font-weight:normal
}​
#errmsg
{
color: red;
}
</style>

  </head>
  <body onload="verifyUser();">
  <input type="hidden" id="refresh" value="no">
    <?php
          $query = "SELECT * FROM HOAID WHERE HOA_ID=".$_REQUEST['id'];
          $queryResult =  pg_query($query);
          $row = pg_fetch_assoc($queryResult);
          $communityID = $row['community_id'];
          $query = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$communityID;
          $queryResult = pg_query($query);
          $row2 = pg_fetch_assoc($queryResult);
          $legalName = $row2['legal_name'];
          $communityEmal = $row2['email'];
          echo '<h1>'.$legalName.'</h1>';
    ?>
    <hr>
    <br><br>
  <div class="container" id="paymentPage" style=" margin: 0 auto;" hidden="hidden" >
                                  <?php 
                                   date_default_timezone_set('America/Los_Angeles');
                                  $query  = "SELECT * FROM CURRENT_CHARGES WHERE HOA_ID=".$_REQUEST['id']."AND ASSESSMENT_YEAR=".date("Y");
                                  $queryResult = pg_query($query);
                                  $currentChargesTotal  = 0;
                                  while ($row = pg_fetch_assoc($queryResult)) {
                                    $currentChargesTotal = $currentChargesTotal+$row['amount'];
                                  }
                                  $query = "SELECT * FROM CURRENT_PAYMENTS WHERE HOA_ID=".$_REQUEST['id']." AND EXTRACT(YEAR FROM PROCESS_DATE)=".date("Y");
                                  $queryResult = pg_query($query);
                                  $currentPaymentsTotal = 0;
                                  while ($row = pg_fetch_assoc($queryResult)) {
                                      $currentPaymentsTotal = $currentPaymentsTotal + $row['amount'];
                                    }
                                    if ( $currentChargesTotal-$currentPaymentsTotal > 0 ){

                                    }
                                    else if (  $currentChargesTotal-$currentPaymentsTotal ==  0 ){
                                      echo '<center><h3><span class="notbold">Current Balance as of '.date('Y-m-d').' is</span> $ '.($currentChargesTotal-$currentPaymentsTotal).'.</h3></center>';
                                    }
                                    ?>
      <br>
      <br>
    <div class="row">
        <div style="width:40%; margin:0 auto; float: left;">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <div class="row display-tr" >
                      <center>
                        <h3 class="panel-title display-td" >Payment Details</h3>
                        </center>
                    </div>                    
                </div>
                <div class="panel-body">
                    <form role="form">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="amount">AMOUNT</label>
                                   <?php 
                                   date_default_timezone_set('America/Los_Angeles');
                                    $query  = "SELECT * FROM CURRENT_CHARGES WHERE HOA_ID=".$_REQUEST['id']."AND ASSESSMENT_YEAR=".date("Y");
                                    $queryResult = pg_query($query);
                                    $currentChargesTotal  = 0;
                                    while ($row = pg_fetch_assoc($queryResult)) {
                                      $currentChargesTotal = $currentChargesTotal+$row['amount'];
                                    }
                                    $query = "SELECT * FROM CURRENT_PAYMENTS WHERE HOA_ID=".$_REQUEST['id']." AND EXTRACT(YEAR FROM PROCESS_DATE)=".date("Y");
                                    $queryResult = pg_query($query);
                                    $currentPaymentsTotal = 0;
                                    while ($row = pg_fetch_assoc($queryResult)) {
                                      $currentPaymentsTotal = $currentPaymentsTotal + $row['amount'];
                                    }
                                    if ( $currentChargesTotal-$currentPaymentsTotal > 0 ){
                                    echo '<input type="text" class="form-control" name="amount" id="auth_amount" value="'.($currentChargesTotal-$currentPaymentsTotal).'" disabled="disabled"/>';
                                    }
                                    else {
                                      echo '<input type="text" class="form-control" id="auth_amount" name="amount" />';
                                    }
                                    ?>
                                </div>                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="routingNumber">ROUTING NUMBER</label>
                                    <input type="text" class="form-control" name="routingNumber" id="routingNumber" />
                                </div>                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="accountNumber">ACCOUNT NUMBER</label>
                                    <input type="text" class="form-control" name="accountNumber" id="accountNumber" />
                                </div>                            
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="accountHolder">ACCOUNT HOLDER</label>
                                    <?php
                                    $query = "SELECT firstname,lastname from hoaid where hoa_id=".$_REQUEST['id'];
                                    $queryRes = pg_query($query);
                                    $row = pg_fetch_row($queryRes);
                                    $name = $row[0].' '.$row[1];
                                    echo '<input type="text" class="form-control" name="accountHolder" id="accountHolder" value="'.$name.'" />'; 
                                    ?>
                                </div>                            
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="customerId">CUSTOMER ID</label>
                                    <?php
                                    echo '<input type="text" class="form-control" name="customerId" id="customerId" value="'.$_REQUEST['id'].'" disabled="disabled"/>';
                                    ?>
                                </div>                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-success btn-lg btn-block" type="button" onclick="payNow();">Pay Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>            
        <div style="float: left; padding-left: 30px;">
          <h4>Contact Us</h4>
          <br>
          <?php
          $query = "SELECT * FROM HOAID WHERE HOA_ID=".$_REQUEST['id'];
          $queryResult =  pg_query($query);
          $row = pg_fetch_assoc($queryResult);
          $communityID = $row['community_id'];
          $query = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$communityID;
          $queryResult = pg_query($query);
          $row2 = pg_fetch_assoc($queryResult);
          $legalName = $row2['legal_name'];
          $address = $row2['mailing_address'];
          $query = "SELECT CITY_NAME FROM CITY WHERE CITY_ID=".$row2['mailing_addr_city'];
          $queryResult = pg_query($query);
          $row = pg_fetch_row($queryResult);
          $cityName  = $row[0];
          $query = "SELECT STATE_CODE FROM STATE WHERE STATE_ID=".$row2['mailing_addr_state'];
          $queryResult = pg_query($query);
          $row  = pg_fetch_row($queryResult);
          $stateName = $row[0];
          $query = "SELECT ZIP_CODE FROM ZIP WHERE ZIP_ID=".$row2['mailing_addr_zip'];
          $queryResult = pg_query($query);
          $row =  pg_fetch_row($queryResult);
          $zipCode = $row[0];
          $finalAddress = '<span class=\'notbold\'>'.$legalName.'<br><br>'.$address.','.$cityName.','.$stateName.' '.$zipCode.'</span>';
          if ( $communityID == 1){
            $finalAddress = $finalAddress.'<br><br>'.'<a href="mailto:'.$communityEmal.'">'.$communityEmal.'</a>';
          }
          else if  ( $communityID == 2){
            $finalAddress = $finalAddress.'<br><br>Phone : <a href="tel:9253996642">(925) 399-6642</a>'.'<br>'.'Email : <a href="mailto:'.$communityEmal.'">'.$communityEmal.'</a>';
          }
          echo '<h4>'.$finalAddress.'</h4>';
          echo '<br>';
          ?>
          <center><img  style="padding-left: 10px;" src="FortePaymentSystemsLogo.png"></center>
        </div>
        </div>
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
  </body>
</html>