<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forte Customer Transaction</title>
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
</script>
</head>
<body>
<div class="container">
  <h2>Forte Customer Transaction</h2>
  <hr>
</div>
<div class="container"> 
<br style="clear: both;">
    <br>
        <table id="example" class="table table-striped" cellspacing="0" width="100%" >
                                <thead>
                                    <tr>
                                       <center>
                                       <th>Origination Date</th>
                                        <th>Received Date</th>
                                        <th>Amount</th>
                                        <th>Authorization Code</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                       </center>
                                    </tr>
                                </thead>
                                <tbody>
<?php
          $url = "https://api.forte.net/v3/organizations/org_332536/customers/".$_GET['id'].'/transactions?page_size=1000';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','X-Forte-Auth-Organization-Id:org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        $jsonResult = json_decode($result);
          foreach ($jsonResult->results as $transactions) {
          echo '<tr>';

          echo '<td>';
              if($transactions->origination_date){
              echo date('Y-m-d',strtotime($transactions->origination_date));
              }
              else {
                echo "";
              }
          echo '</td>';

          echo '<td>';
              echo date('Y-m-d',strtotime($transactions->received_date));
          echo '</td>';

          echo '<td>';
              echo $transactions->authorization_amount;
          echo '</td>';

          echo '<td>';
            echo $transactions->authorization_code;
          echo '</td>';

          echo '<td>';
              echo $transactions->action;
          echo '</td>';

          echo '<td>';
            echo $transactions->status;
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