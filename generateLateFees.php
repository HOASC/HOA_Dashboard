<html>
  <head>
  <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">   
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
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
</style>
<style>
* {
  box-sizing: border-box;
}

#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myTable {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
  font-size: 18px;
}

#myTable th, #myTable td {
  text-align: left;
  padding: 12px;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}
</style>
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

</script>
  </head>
  <div class="container">
    <div class="row">
      <h2>Late Fee</h2>
      <hr/>
    </div>
    <div>
      <table id="myTable" class="table table-striped" style="font-size: 14">  
        <thead>  
          <tr>  
            <th>HOME ID</th>  
            <th>Name</th>
            <th>Charges</th>  
            <th>Paid </th>  
            <th>Address</th>
            <th>Amount to be paid</th>
          </tr>  
        </thead>  
        <tbody>  
          <?php
          error_reporting(E_ALL);
          ini_set('display_errors', 1);
          date_default_timezone_set('America/Los_Angeles');
          include 'includes/dbconn.php';
          if ( true ){
            $query = "SELECT * FROM ASSESSMENT_RULES";
            $queryResult = pg_query($query);
            $regularAssessments = array();
            while ($row = pg_fetch_assoc($queryResult)) {
              if ( $row['assessment_rule_type'] == 1 ){
              $regularAssessments[$row['community_id']] = $row['assessment_amount'];
            }
            else if ( $row['assessment_rule_type'] == 3) {
              $lateFee[$row['community_id']] = $row['assessment_amount'];
            }
            }
            $query = "SELECT * FROM HOMEID WHERE COMMUNITY_ID = 2 ORDER BY HOME_ID";
            $queryResult = pg_query($query);
            $homeIDSArray = array();
            while ($row = pg_fetch_assoc($queryResult)) {
              $homeIDSArray[$row['home_id']] = $row['address1'];
            }

            $query = "SELECT home_id,firstname,lastname FROM HOAID";
            $queryResult = pg_query($query);

            $namesArray = array();

            while ($row = pg_fetch_row($queryResult)) {
              $namesArray[$row[0]]  = $row[1].' '.$row[2];
            }

            foreach ($homeIDSArray as $key => $value) {
                $homeID = $key;

                echo '<tr>';
                if ( $homeID < 144 ){

                $query = "SELECT COUNT(*) FROM CURRENT_CHARGES WHERE HOME_ID=".$homeID." AND ASSESSMENT_RULE_TYPE_ID = 1";
                $queryResult  = pg_query($query);
                $row = pg_fetch_row($queryResult);
                $month = $row[0];

                $query = "SELECT EXTRACT(MONTH FROM PROCESS_DATE) AS MONTH,AMOUNT  FROM CURRENT_PAYMENTS  WHERE HOME_ID = ".$key." AND EXTRACT(YEAR FROM PROCESS_DATE) = ".date('Y')." AND  AMOUNT >= ".$regularAssessments[1]." ORDER BY MONTH";
                $queryResult = pg_query($query);
                $monthlyPayments  = array();
                $paymentsTotal = 0;
                $paymentsArray = array();
                while ($row = pg_fetch_assoc($queryResult)) {
                  $paymentsTotal = $paymentsTotal + $row['amount'];
                  $paymentsArray[$row['month']] = $row['amount'];
                }

                
                $totalVal = $month*$regularAssessments[1];

                if ( $totalVal > $paymentsTotal){
                  echo '<td>';
                  echo $homeID;
                  echo '</td>';
                  echo '<td>';
                  echo $namesArray[$homeID];
                  echo '</td>';
                  echo '<td>';
                  echo $totalVal;
                  echo '</td>';
                  echo '<td>';
                  echo $paymentsTotal;
                  echo '</td>';
                  echo '<td>';
                  echo $homeIDSArray[$homeID];
                  echo '</td>';
                  echo '<td>';
                  echo $totalVal - $paymentsTotal;
                  echo '</td>';
                  
                }
              }
              else {
                $query = "SELECT COUNT(*) FROM CURRENT_CHARGES WHERE HOME_ID=".$homeID." AND ASSESSMENT_RULE_TYPE_ID = 1";
                $queryResult  = pg_query($query);
                $row = pg_fetch_row($queryResult);
                $month = $row[0];
                $query = "SELECT EXTRACT(MONTH FROM PROCESS_DATE) AS MONTH,AMOUNT  FROM CURRENT_PAYMENTS  WHERE HOME_ID = ".$key." AND EXTRACT(YEAR FROM PROCESS_DATE) = ".date('Y')." AND  AMOUNT >= ".$regularAssessments[2]." ORDER BY MONTH";
                $queryResult = pg_query($query);
                $monthlyPayments  = array();
                $paymentsTotal = 0;
                $paymentsArray = array();
                while ($row = pg_fetch_assoc($queryResult)) {
                  $paymentsTotal = $paymentsTotal + $row['amount'];
                  $paymentsArray[$row['month']] = $row['amount'];
                }
                $totalVal = $month*$regularAssessments[2];
                if ( $totalVal > $paymentsTotal){
                  echo '<td>';
                  echo $homeID;
                  echo '</td>';
                  echo '<td>';
                  echo $namesArray[$homeID];
                  echo '</td>';
                  echo '<td>';
                  echo $totalVal;
                  echo '</td>';
                  echo '<td>';
                  echo $paymentsTotal;
                  echo '</td>';
                  echo '<td>';
                  echo $homeIDSArray[$homeID];
                  echo '</td>';
                  echo '<td>';
                  echo $totalVal - $paymentsTotal;
                  echo '</td>';
                }
                echo '</tr>';
              }
          }
         }
          ?>
        </tbody>  
      </table>  

      <script>
$(document).ready(function(){
    $('#myTable').dataTable();
});
</script>
    </div>
  </div>
</html>