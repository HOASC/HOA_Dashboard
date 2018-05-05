<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forte Edit Customer</title>
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
function updateCustomerData(button){
  var customerToken = button.id;
  var customerFname = document.getElementById('fname').value;
  var customerLname = document.getElementById('lname').value;
  var customerID = document.getElementById('customerid').value;
  var e = document.getElementById("status");
  var status = e.options[e.selectedIndex].text;
  showPleaseWait();
  jsonObj = [];
  item = {};
  item["customer_token"] = customerToken;
  item["customer_id"] = customerID;
  item["status"] = status;
  item["first_name"] = customerFname;
  item["last_name"] = customerLname;
  jsonObj.push(item);
  lol =  JSON.stringify(jsonObj);
  var request= new XMLHttpRequest();
  request.open("POST", "https://hoaboardtime.com/updateCustomerDataBG.php", true);
  request.setRequestHeader("Content-type", "application/json");
  request.send(lol);
  request.onreadystatechange = function () {
        if (request.readyState == XMLHttpRequest.DONE) {
            hidePleaseWait();
            alert(request.responseText);
        }
  }
}
function cancel(){
  window.location = "https://hoaboardtime.com/forteMangeCustomers.php";
}
</script>
</head>
<body>
<div class="container">
  <h2>Forte Edit Customer</h2>
  <hr>
</div>
<div class="container"> 
      <?php
      $url = "https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/customers/".$_GET['id'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','X-Forte-Auth-Organization-Id:org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        $jsonResult = json_decode($result);
        echo '<h4 style="float:left;">'.$jsonResult->display_name.'</h2>';
        if ( $jsonResult->updated_date ){
        echo '<h4 style="float:right;">Last Updated : '.date('Y-m-d H:i:s',strtotime($jsonResult->updated_date)).'</h2>';
        }
        echo '<hr style="clear:both;">';
        echo '<div class="form-group">';
        echo '<label for="fname">First Name:</label>';
        echo '<input type="text" class="form-control" id="fname" style="width: 35%;" value="'.$jsonResult->first_name.'">';
        echo '<br>';
        echo '<label for="lname">Last Name:</label>';
        echo '<input type="text" class="form-control" id="lname" style="width: 35%;" value="'.$jsonResult->last_name.'">';
        echo '<br>';
        echo '<label for="customerid">Customer ID</label>';
        echo '<input type="text" class="form-control" id="customerid" style="width: 35%;" value="'.$jsonResult->customer_id.'">';
        echo '<br>';
        echo '<label for="status">Status (Current Status : '.$jsonResult->status.')</label>';
        echo '<br>';
        echo '<select class="selectpicker" data-show-subtext="true" data-live-search="true" id="status">
        <option data-subtext="">active</option>
        <option data-subtext="">suspended</option>
      </select>';
      echo '<br>';
      echo '<br>';
      echo '<button type="button" class="btn btn-primary" id="'.$_GET['id'].'" onclick="updateCustomerData(this);">Update</button>';
      echo '<button type="button" class="btn btn-primary" onclick="cancel();">Cancel</button>';
      echo '</div>';
      ?>   
</div>
</body>
</html>