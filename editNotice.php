<html>
  <head>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
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
function updateData(){
   var subject = document.getElementById("subject").value;
  var body = document.getElementById("desc").value;
  var loc  = document.getElementById("loc").value;
  var string = "https://hoaboardtime.com/viewInvoice2.php?id=".concat(<?php echo $_GET['id'];?>).concat("&sub=").concat(subject).concat("&bod=").concat(body).concat("&loc=").concat(loc);;
  window.location = string;
  
}
function sendData(){
  var subject = document.getElementById("subject").value;
  var body = document.getElementById("desc").value;
  var loc  = document.getElementById("loc").value;
  var string = "https://hoaboardtime.com/viewInvoice1.php?id=".concat(<?php echo $_GET['id'];?>).concat("&sub=").concat(subject).concat("&bod=").concat(body).concat("&loc=").concat(loc);
  window.location = string;
}
</script>
  </head>
  <div class="container">
    <div class="row">
      <h2>Edit Notice</h2>
      <hr />
    </div>
      <div style="width: 100%;">
      <h4>Subject</h4>
      <?php
      include 'includes/dbconn.php';
      $query = "SELECT * FROM INSPECTION_NOTICES WHERE ID = ".$_GET['id'];
      $queryResult = pg_query($query);
      $queryResult = pg_fetch_assoc($queryResult);
      $description = $queryResult['description'];

      $subject = explode('.', $description);
         $cityQuery = "SELECT * FROM CITY";
        $cityQueryResult = pg_query($cityQuery);
        $cityArray = array();
        while($row = pg_fetch_assoc($cityQueryResult)){
                $cityArray[$row['city_id']] = $row['city_name'];
        }
        $zipArray = array();
        $zipQuery = "SELECT * FROM ZIP";
        $zipQueryResult = pg_query($zipQuery);
        while($row = pg_fetch_assoc($zipQueryResult)){
            $zipArray[$row['zip_id']] = $row['zip_code'];
        } 
        $stateQuery = "SELECT * FROM STATE";
        $stateQueryResult = pg_query($stateQuery);
        $stateArray = array();
        while($row = pg_fetch_assoc($stateQueryResult)){
            $stateArray[$row['state_id']] = $row['state_name'];
        }
        $locationArray = array();
        $locationQuery = "SELECT * FROM LOCATIONS_IN_COMMUNITY";
        $locationQueryResult = pg_query($locationQuery);
        while($row = pg_fetch_assoc($locationQueryResult)){
            $locationArray[$row['location_id']] = $row['location'];
        }
        $id = $_GET['id'];
        $inspectionQuery = "SELECT * FROM INSPECTION_NOTICES WHERE ID=".$id;
        $inspectionQueryResult = pg_query($inspectionQuery);
        $inspectionData  = array();
        $row = pg_fetch_assoc($inspectionQueryResult);
        $inspectionDateFinal = $row['inspection_date'];
        $inspectionStatusIDFinal = $row['inspection_status_id'];
        $inspectionDescriptionFinal = $row['description'];
        $inspectionNoticeTypeFinal  = $row['inspection_notice_type_id'];
        $inspectionCommunityIDFinal = $row['community_id'];
        $inspectionCategoryID = $row['inspection_category_id'];
        $inspectionLocationID = $row['location_id'];
        $inspectionSubCategoryID = $row['inspection_sub_category_id'];
        $inspectionHOAID = $row['hoa_id'];
        $inspectionTypeID = $row['inspection_notice_type_id'];
        $inspectionHomeID =  $row['home_id'];
        $homeDetailsQuery = "SELECT * FROM HOMEID WHERE HOME_ID=".$inspectionHomeID;
        $homeDetailsQueryResult = pg_query($homeDetailsQuery);
        $hoaDetailsQuery = "SELECT * FROM HOAID WHERE HOA_ID=".$inspectionHOAID;
        $hoaDetailsQueryResult = pg_query($hoaDetailsQuery);
        $row = pg_fetch_assoc($hoaDetailsQueryResult);
        $personFirstName = $row['firstname'];
        $personLastName = $row['lastname'];
        $row = pg_fetch_assoc($homeDetailsQueryResult);
        $homeAddress1Final = $row['address1'];
        $homeAddressCityFinal  =$row['city_id'];
        $homeAddressStateFinal  = $row['state_id'];
        $homeAddressDistrictFinal  = $row['district_id'];
        $homeAddressZipFinal  = $row['zip_id'];
        $homeAddressCommunityIdFinal = $row['community_id'];
        $currentLivingStatus = $row['living_status'];
        $communityInfoQuery = "SELECT * FROM COMMUNITY_INFO WHERE COMMUNITY_ID=".$inspectionCommunityIDFinal;
        $communityInfoQueryResult = pg_query($communityInfoQuery);
        $row = pg_fetch_assoc($communityInfoQueryResult);
        $communityLegalName = $row['legal_name'];
        $communityCodeName = $row['community_code'];
        $communityMailingAddress = $row['mailing_address'];
        $communityMailingAddressCity  = $row['mailing_addr_city'];
        $communityMailingAddressState = $row['mailing_addr_state'];
        $communityMailingAddressZip = $row['mailing_addr_zip'];
        $inspectionTypeNameFinal = "";
        $inspectionStatusQuery = "SELECT * FROM INSPECTION_STATUS WHERE ID=".$inspectionStatusIDFinal;
        $inspectionStatusQueryResult = pg_query($inspectionStatusQuery);
        $roww = pg_fetch_assoc($inspectionStatusQueryResult);
        $inspectionStatusTextFinal = $roww['inspection_status'];
        if($inspectionTypeID)
        {
        $inspectionNoticeTypeQuery = "SELECT * FROM INSPECTION_NOTICE_TYPE WHERE ID =".$inspectionTypeID;
        $inspectionNoticeTypeQueryResult = pg_query($inspectionNoticeTypeQuery);
        $row = pg_fetch_assoc($inspectionNoticeTypeQueryResult);
        $inspectionTypeNameFinal = $row['name'];
        }
        if ( $inspectionCategoryID ){
        $inspectionCategoryIDQuery = "SELECT * FROM INSPECTION_CATEGORY WHERE ID=".$inspectionCategoryID." AND IS_ACTIVE='YES'";
        $inspectionCategoryIDQueryResult = pg_query($inspectionCategoryIDQuery);
        $row = pg_fetch_assoc($inspectionCategoryIDQueryResult);
        $inspectionCategoryName =  $row['name'];
        }
        $inspectionSubCategoryNameFinal = "";
        if ($inspectionSubCategoryID ){
        $inspectionSubCategoryIDQuery = "SELECT * FROM INSPECTION_SUB_CATEGORY WHERE ID=".$inspectionSubCategoryID." AND IS_ACTIVE='YES'";
        $inspectionSubCategoryIDQueryResult = pg_query($inspectionSubCategoryIDQuery);
        $row = pg_fetch_assoc($inspectionSubCategoryIDQueryResult);
        $inspectionSubCategoryNameFinal  = $row['name'];
        $inspectionSubCategoryRuleDescription = $row['rule_description'];
        $inspectionSubCategoryExplanation = $row['explanation'];
        }

      echo '<input type="email" class="form-control" id="subject" aria-describedby="emailHelp" placeholder="Subject" value="'.$subject[0].'"></input>';
      echo '<small id="emailHelp" class="form-text text-muted">Subject is filled automatically. Change if incorrect.</small>'
      ?>
      </div>
      <br>
      <div>
        <h4>Violation Description</h4>
        <?php
        echo '<input type="email" class="form-control" id="desc" aria-describedby="emailHelp" placeholder="Subject" value="'.$description.'"></input>';
      echo '<small id="emailHelp" class="form-text text-muted">Description is filled automatically. Change if incorrect.</small>'
        ?>
      </div>
    <div style="clear: both;padding-left: 10dp;"></div>
    <div>
        <h4>Location</h4>
        <?php
        echo '<input type="email" class="form-control" id="loc" aria-describedby="emailHelp" placeholder="Subject" value="'.$locationArray[$inspectionLocationID].'"></input>';
      echo '<small id="emailHelp" class="form-text text-muted">Location is filled automatically. Change if incorrect.</small>'
        ?>
      </div>
      <br>
      <button type="button" class="btn btn-primary btn-md" onclick="sendData();">Generate Notice</button>
      <button type="button" class="btn btn-primary btn-md" onclick="updateData();">Update in table & Generate Notice</button>
  </div>

  
</html>