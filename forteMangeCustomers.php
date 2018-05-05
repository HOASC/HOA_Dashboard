<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forte Manage Customers</title>
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

function modifyCustomer(button){
  // window.alert(button.id);
  var url  = "https://hoaboardtime.com/modifyForteCustomer.php?id="+button.id;
  window.location = url;
}

function deleteCustomer(button){
  showPleaseWait();
  jsonObj = [];
  item = {};
  item["customer_token"] = button.id;
  jsonObj.push(item);
  lol =  JSON.stringify(jsonObj);
  var request= new XMLHttpRequest();
  request.open("POST", "https://hoaboardtime.com/deleteForteCustomer.php", true);
  request.setRequestHeader("Content-type", "application/json");
  request.send(lol);
  request.onreadystatechange = function () {
        if (request.readyState == XMLHttpRequest.DONE) {
            hidePleaseWait();
            alert(request.responseText);
        }
        }
}
function createCustomer(){
  window.location = "https://hoaboardtime.com/createForteCustomer.php";
}
</script>
</head>
<body>
<div class="container">
  <h2>Forte Mange Customers</h2>
  <hr>
</div>
<div class="container"> 
<button type="button" class="btn btn-primary" onclick="createCustomer();" style="float: right;">Create Customer</button>
<br style="clear: both;">
        <table id="example" class="table table-striped" cellspacing="0" width="100%" >
                                <thead>
                                    <tr>
                                       <center>
                                       <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th></th>
                                       </center>
                                    </tr>
                                </thead>
                                <tbody>
<?php
          $url = "https://api.forte.net/v3/organizations/org_332536/customers?page_size=1000";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','X-Forte-Auth-Organization-Id:org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        $jsonResult = json_decode($result);
          foreach ($jsonResult->results as $customer) {
          $customerToken = $customer->customer_token;
          echo '<tr>';
          echo '<td>';
          echo '<a href="https://hoaboardtime.com/viewCustomerTransactions.php?id='.$customer->customer_token.'">'.$customer->customer_id.'</a>';
          echo '</td>';
          echo '<td>';
          echo $customer->display_name;
          echo '</td>';
          echo '<td>';
          if ($customer->addresses[0]->email){
          echo $customer->addresses[0]->email;
          }
          else {
          echo "";
          }
          echo '</td>';
          echo '<td>';
          if ($customer->addresses[0]->physical_address->street_line1){
          echo $customer->addresses[0]->physical_address->street_line1;
          }
          else {
          echo "";
          }
          echo '</td>';
          echo '<td>';
          echo $customer->status;
          echo '</td>';
          echo '<td>';
          echo '<input type="button" id="'.$customerToken.'" value="Modify" onclick="modifyCustomer(this);">';
          echo '<input type="button" id="'.$customerToken.'" value="Delete" onclick="deleteCustomer(this);">';
          echo '</td>';
          echo '</tr>';
        }
          ?>
</tbody>
</table>
<script type="text/javascript">
        $(document).ready(function() {
              $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'My button',
                action: function ( e, dt, node, config ) {
                    alert( 'Button activated' );
                }
            }
            ]
            }); 
            var table = $('#example').DataTable({
            dom: 'l<"toolbar">frtip',
            initComplete: function(){
            $('.datatable').dataTable({
                "sPaginationType": "bs_four_button"
            }); 
            $('.datatable').each(function(){
                var datatable = $(this);
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.attr('placeholder', 'Search');
                search_input.addClass('form-control input-sm');
                var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                length_sel.addClass('form-control input-sm');
            });
</script>
  <script type="text/javascript">
            $(document).ready(function() {
                $('#example').DataTable();
            } );
        </script> 
<script type="text/javascript">
    function changeState(el) {
        if (el.readOnly) el.checked=el.readOnly=false;
        else if (!el.checked) el.readOnly=el.indeterminate=true;
    }
</script>
</div>
</body>
</html>