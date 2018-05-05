<!DOCTYPE html>
<html>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Transactions</title>
        <link rel="stylesheet" href="bootstrap.css"/>
        <link rel="stylesheet" href="../bower_components/Font-Awesome/css/font-awesome.css"/>
        <link rel="stylesheet" href="build.css"/>
        <link rel="stylesheet" href="dist/css/bootstrap-select.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/i18n/defaults-*.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <link rel='stylesheet' href='vendor/metisMenu/dist/metisMenu.css' />
        <link rel='stylesheet' href='vendor/animate.css/animate.css' />
        <link rel='stylesheet' href='fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css' />
        <link rel='stylesheet' href='fonts/pe-icon-7-stroke/css/helper.css' />
        <link rel='stylesheet' href='styles/style.css'>
        <script src="dist/js/bootstrap-select.js"></script>
        <script type="text/javascript">
        var id = "";
        function test(checkbox){
            if (checkbox.checked == false) {
                if (!checkbox.disabled) {
                    document.getElementById("setreminder").disabled = true;
                }
            }
            else  if(checkbox.checked == true){
                document.getElementById("setreminder").disabled = false;
                id = checkbox.id;
            }
        }
        function buttonPressed() {
            // alert(id);
            var date = document.getElementById('inputdefault').value;
            // window.location  = "localhost/forteTransactions.php?id=".date;
            window.location = "http://localhost/forteTransactions.php?date=".concat(date);
        }
        function todayButtonPressed() {
            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            window.location = "http://localhost/forteTransactions.php?date=".concat(date);
        }
  </script>
  <style >     
      .btn:hover {
        background-position: 0px;
    }
     input[type="radio"], input[type="radio"]+label img {
    vertical-align: middle;
  }
  </style>
    </head>
    <body>
        <div class='splash'> 
            <div class='color-line'></div>
            <div class='splash-title'>
                <h1></h1>
                <div class='spinner'> 
                    <div class='rect1'></div>
                    <div class='rect2'></div>
                    <div class='rect3'></div> 
                    <div class='rect4'></div>
                    <div class='rect5'></div>
                </div>
            </div>
        </div>
        <h1>Fundings</h1>
<hr>
<div class="container">

		<div class="form-group" style="width: 200px;">
    <!--   <label for="inputdefault">Enter Date</label>
      <input class="form-control" id="inputdefault" type="text" placeholder="YYYY-MM-DD">
      <input type="button" name="Some Button" value="Get Transactions" onclick="buttonPressed()">
      <input type="button" name="Current Date" value="Today Transactions" oncancel="buttonPressed()"> -->
    </div>
    
        <br>
        <div>
    	<!-- <input type="button" name="" value="Update Database" style="float: right;" onclick="updateDatabase();"> -->
    </div>
        <table id="example" class="table table-striped" cellspacing="0" width="100%" style="font-size: 16px;">
                                <thead>
                                    <tr>
                                    	<th>FundingID</th>
                                        <th>Transaction Count</th>
                                    	<th>Status</th>
                                        <th>EffectiveDate</th>
                                        <th>OriginationDate</th>
                                        <th>Net Amount</th>
                                        <th>Funding Source</th>
                                        <th>Bank Information</th>
                                        <th>Entry Description</th>
                                        <th>Account Number</th>
                                        <th>Routing Number</th>
                                    </tr>
                                </thead>
                                <tbody>      
                               <?php
                                require 'includes/dbconn.php';
                                $insertCount = 0;
                                $updateCount = 0;
                                $getQuery = "SELECT funding_id FROM community_deposits WHERE community_id=2";
                                $getResults = pg_query($getQuery);
                                $fundingArray  = array();
                                while ($row = pg_fetch_row($getResults)) {
                                    $fundingArray[$row[0]] = 1;
                                }
                               date_default_timezone_set('America/Los_Angeles');
                                $url  = 'https://api.forte.net/v3/organizations/org_332536/fundings/';
                                $ch = curl_init($url);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','X-Forte-Auth-Organization-Id:org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                $result = curl_exec($ch);
                                $result = json_decode($result,TRUE);
                                curl_close($ch);
                                $number_of_results = $result['number_results'];
                                $url = $url.'?page_size='.$number_of_results;
                                $ch = curl_init($url);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','X-Forte-Auth-Organization-Id:org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                $result = curl_exec($ch);
                                $result = json_decode($result,TRUE);
                                curl_close($ch);
                                foreach ($result['results'] as $results) {
                                    $url = $results['links']['transactions'];
                                    $ch = curl_init($url);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','X-Forte-Auth-Organization-Id:org_332536','Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU='));
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                    $resultss = curl_exec($ch);
                                    $resultss = json_decode($resultss,TRUE);
                                    curl_close($ch);
                                    $number_of_results = $resultss['number_results'];
                                    echo '<tr>';
                                    echo '<td>';
                                        echo '<a href="http://localhost/forteFundingDetails.php?url='.$results['links']['transactions'].'">'.str_replace("fnd_","",$results['funding_id'])."</a>";
                                    echo '</td>';
                                    echo '<td>';
                                    $fundingID = str_replace("fnd_","",$results['funding_id']);
                                    $numberOfResults = $number_of_results;
                                    echo $number_of_results;
                                    echo '</td>';
                                    echo '<td><font color="green";>';
                                        if ( $results['status'] == 'completed' ) {
                                            $resstatus = $results['status'];
                                            echo strtoupper($results['status']);

                                        }
                                        else {
                                            $resstatus = $results['status'];
                                        echo strtoupper($results['status']);
                                    }
                                    echo '</font></td>';
                                    echo '<td>';
                                        $effectiveDate = date("Y-m-d",strtotime($results['effective_date']));
                                        echo date("Y-m-d",strtotime($results['effective_date']));
                                    echo '</td>';
                                    echo '<td>';
                                        $originationDateR = date("Y-m-d",strtotime($results['origination_date']));
                                        echo date("Y-m-d",strtotime($results['origination_date']));
                                    echo '</td>';
                                    echo '<td>';
                                        $netAmount = $results['net_amount'];
                                        echo '$ '.$results['net_amount'];
                                    echo '</td>';
                                    echo '<td>';
                                        $fundingSourceCode = $results['funding_source']['code'];
                                    echo $results['funding_source']['code'];
                                    echo '</td>';
                                    echo '<td>';
                                        $fundingDescription = $results['funding_source']['description'];
                                        echo $results['bank_information'];
                                    echo '</td>';
                                    echo '<td>';
                                        $entryDescription = $results['entry_description'];
                                        echo $results['entry_description'];
                                    echo '</td>';
                                    echo '<td>';
                                        $accountLastFourDigits = $results['last_4_account_number'];
                                        echo $results['last_4_account_number'];
                                    echo '</td>';
                                    echo '<td>';
                                        $routingNumber = $results['routing_number'];
                                        echo $results['routing_number'];
                                    echo '</td>';
                                    echo '</tr>';
                                    if ( $fundingArray[$fundingID] == 1){
                                        $updateQuery = "UPDATE community_deposits SET status='";
                                        $updateQuery = $updateQuery.$resstatus."' WHERE funding_id='";
                                        $updateQuery = $updateQuery.$fundingID."'";
                                        // print_r("Update Requested......".$updateQuery);
                                        pg_query($updateQuery);

                                        if ( !(pg_query($updateQuery)) ){
                                            print_r("Failed to update.Funding ID:".$updateQuery.$fundingID." ");
                                        }
                                        else {
                                            print_r("Updated Funding ID:".$$updateQuery.$fundingID." ");
                                        }
                                        print_r("     ");
                                    }
                                    else {
                                    $insertQuery = 'INSERT INTO community_deposits("community_id","funding_id","net_amount","number_of_transactions","status","effective_date","origination_date","routing_number","account_number_last_four_digits","funding_source_code","funding_source_description","entry_description") VALUES(2,';
                                    $insertQuery = $insertQuery."'".$fundingID."',";
                                    $insertQuery = $insertQuery."".round($netAmount,2).",";
                                    $insertQuery = $insertQuery."".$numberOfResults.",";
                                    $insertQuery = $insertQuery."'".$resstatus."',";
                                    $insertQuery = $insertQuery."'".$effectiveDate."',";
                                    $insertQuery = $insertQuery."'".$originationDateR."',";
                                    $insertQuery = $insertQuery."".$routingNumber.",";
                                    $insertQuery = $insertQuery."".$accountLastFourDigits.",";
                                    $insertQuery = $insertQuery."'".$fundingSourceCode."',";
                                    $insertQuery = $insertQuery."'".$fundingDescription."',";
                                    $insertQuery = $insertQuery."'".$entryDescription."')";  

                                    if  ( !(pg_query($insertQuery)) ){
                                        print_r("Insert failed.");
                                        print_r($insertQuery);
                                        print_r(nl2br("\n\n"));
                                    }
                                    else {
                                        print_r("Inserted");
                                    }
                                }
                                }
                                
                               ?>
                                </tbody>
        </table>
        <br><br>
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
            } ); 
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
        <script src='vendor/slimScroll/jquery.slimscroll.min.js'></script>
        <script src='vendor/metisMenu/dist/metisMenu.min.js'></script>
        <script src='scripts/homer.js'></script>
        <script src='//code.jquery.com/jquery-1.12.4.js'></script>
        <script src='https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js'></script>
        <script src='https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js'></script>
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
    <script src='scripts/homer.js'></script>
          <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>This is a small modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
    </body>
</html>
