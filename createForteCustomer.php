<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forte Create Customer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src='https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js'></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src='https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js'></script>
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
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
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

function cancel(){
  window.location = "https://hoaboardtime.com/forteMangeCustomers.php";
}
function createCustomer(){
  showPleaseWait();
}
</script>
</head>
<body>
<div class="container">
  <h2>Forte Create Customer</h2>
  <hr>
</div>
<div class="container"> 
      <h3>Basic Information</h3>
      <hr>
      <div class="form-group" style="float: left;">
        <label for="fname">First Name:</label>
        <input type="text" class="form-control" id="fname" >
      </div>
      <div class="form-group" style="float: left;padding-left:10px">
        <label for="lname">Last Name:</label>
        <input type="text" class="form-control" id="lname" >
      </div>
      <div class="form-group" style="float: left; padding-left:10px">
        <label for="cname">Company Name:</label>
        <input type="text" class="form-control" id="cname" >
      </div>
      <div style="clear: both;"></div>
      <h3>Address</h3>
      <hr>
      <div class="form-group" style="width: 40%">
        <label for="adlabel">Address Label:</label>
        <input type="text" class="form-control" id="adlabel" >
      </div>
      <br>

      <div class="form-group" style="float: left;">
        <label for="afname">First Name:</label>
        <input type="text" class="form-control" id="afname" >
      </div>
      <div class="form-group" style="float: left; padding-left:10px">
        <label for="alname">Last Name:</label>
        <input type="text" class="form-control" id="alname" >
      </div>
      <div class="form-group" style="float: left; padding-left:10px">
        <label for="acname">Company Name:</label>
        <input type="text" class="form-control" id="acname" >
      </div>
      <div style="clear: both;"></div>
      <br>

      <div class="form-group" style="float: left;">
        <label for="phone">Phone:</label>
        <input type="text" class="form-control" id="phones" >
      </div>
      <div class="form-group" style="float: left; padding-left:10px">
        <label for="email">Email:</label>
        <input type="text" class="form-control" id="email" >
      </div>
      <div style="clear: both;"></div>
      <br>
      <div class="form-group" style="float: left;">
        <label for="satype">Shipping Address Type</label>
        <input type="text" class="form-control" id="satype" >
      </div>
      <div class="form-group" style="float: left; padding-left:10px">
        <label for="atype"> Address Type</label>
        <input type="text" class="form-control" id="atype" >
      </div>
      <div style="clear: both;"></div>
      <div class="form-group"">
        <label for="ad1">Address Line 1</label>
        <input type="text" class="form-control" id="ad1" >
      </div>
      <br>
      <div class="form-group"">
        <label for="ad2">Address Line 2</label>
        <input type="text" class="form-control" id="ad2" >
      </div>
      <br>
      <div class="form-group"">
        <label for="locality">Locality</label>
        <input type="text" class="form-control" id="locality" >
      </div>
      <div class="form-group"">
        <label for="region">Region</label>
        <input type="text" class="form-control" id="region" >
      </div>
      <div class="form-group"">
        <label for="pcode">Postal Code</label>
        <input type="text" class="form-control" id="pcode" >
      </div>
      <h3>Paymethod</h3>
      <div class="form-group"">
        <label for="paylabel">Label</label>
        <input type="text" class="form-control" id="paylabel">
      </div>
      <br>
      <div class="form-group"">
        <label for="notes">Notes</label>
        <input type="text" class="form-control" id="notes" >
      </div>
      <br>
      <div class="form-group">
        <label for="acnum">Account Number</label>
        <input type="text" class="form-control" id="acnum" >
      </div>
      <div class="form-group" style="float: left;">
        <label for="month">Expiry Month</label>
        <input type="text" class="form-control" id="month" >
      </div>
      <div class="form-group" style="float: left;padding-left: 10px;">
        <label for="year">Expiry Year</label>
        <input type="text" class="form-control" id="year" >
      </div>
      <div class="form-group" style="float: left;padding-left: 10px;">
        <label for="cvv">CVV</label>
        <input type="text" class="form-control" id="cvv" >
      </div>
      <div style="clear: both;"></div>
      <div class="row-fluid">
      <h6>Card Type</h6>
      <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="ctype">
        <option data-subtext="">visa</option>
        <option data-subtext="">amex</option>
        <option data-subtext="">mast</option>
        <option data-subtext="">disc</option>
        <option data-subtext="">dine</option>
        <option data-subtext="">jcb</option>
      </select>
    </div>
    <br>
    <br>
    <div class="form-group">
        <label for="nc">Name On Card</label>
        <input type="text" class="form-control" id="nc" >
      </div>
    <br>
    <button type="button" class="btn btn-primary" onclick="createCustomer();">Create Customer</button>
    <br>
    <br>
    <br>
</div>
</body>
</html>