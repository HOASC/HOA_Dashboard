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

    </style>
<script type="text/javascript">
  var message = "";
  var mode;
  function appendAddress(){
    document.getElementById("messageBody").value = document.getElementById("messageBody").value+"#address# ";
  }
  function appendName(){
    document.getElementById("messageBody").value = document.getElementById("messageBody").value+"#name# ";
  }
  function appendMonth(){
    document.getElementById("messageBody").value = document.getElementById("messageBody").value+"#month# ";
  }
  function getSubscribers(){
    var eventID = $("#eventType").find("option:selected").attr("id");
    var communityID = <?php echo $_SESSION['hoa_community_id']; ?>;
    jsonData = [];
    item  = {};
    item['community_id'] = communityID;
    item['event_id']  = eventID;
    jsonData.push(item);
    sendData  = JSON.stringify(jsonData);
    var request  = new XMLHttpRequest();
    request.open("POST", "https://hoaboardtime.com/getEventSubscribers.php", true);
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
              if ( request.responseText.split("@")[0] == 0 ){
                  swal("No subscribers found for this category.","","error");
                  $("#sendToAllButton").text("Send to all subscribers");
                  document.getElementById("sendToAllButton").disabled = true;
              }
              else {

                  $("#sendToAllButton").text("Send to "+request.responseText.split("@")[0]+" subscribers");
                  document.getElementById("sendToAllButton").disabled = false;
              }

              $("#messageBody").text(request.responseText.split("@")[1]);
        }
    }
  }

  function changesButtonState(){
    document.getElementById("sendToAllButton").disabled = true;
  }


  function sendToSingleMember(){

    var hoaID = $("#memberId").find("option:selected").attr("id");

    if ( $("#eventType").find("option:selected").attr("id") ){
    if ( hoaID ){
        jsonData = [];
        item =  {};
        item['mode'] = "single";
        item['event_type'] = $("#eventType").find("option:selected").attr("id");
        item['hoa_id'] = hoaID;
        item['community_id'] = <?php echo $_SESSION['hoa_community_id'];  ?>;
        item['message_body'] = document.getElementById("messageBody").value;
        item['sender'] = <?php echo $_SESSION['hoa_user_id'];?>;
        jsonData.push(item);
        sendData = JSON.stringify(jsonData);


    var request  = new XMLHttpRequest();
    request.open("POST", "https://hoaboardtime.com/sendMessage.php", true);
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
              
              if ( request.responseText.includes("SMS sent") ){
                swal("Message(s) sent.","","success");
              }
              else if ( request.responseText.includes("No Phone number found.") ){
                swal("","One or more phone numbers could not be found","warning");
              }
              else{
                swal("An error occured","","error");
              }
        }
    }


    }
    else {
      swal("Please select a member","","error");
    }
  }
  else {
      swal("Please select an event","","error");
  }

  }
  function sendToAllSubscribers(){
    if ( $("#eventType").find("option:selected").attr("id") ){
        jsonData = [];
        item =  {};
        item['mode'] = "all";
        item['event_type'] = $("#eventType").find("option:selected").attr("id");
        item['community_id'] = <?php echo $_SESSION['hoa_community_id'];  ?>;
        item['message_body'] = document.getElementById("messageBody").value;
        item['sender'] = <?php echo $_SESSION['hoa_user_id'];?>;
        jsonData.push(item);
        sendData = JSON.stringify(jsonData);
    var request  = new XMLHttpRequest();
    request.open("POST", "https://hoaboardtime.com/sendMessage.php", true);
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
              
              if ( request.responseText.includes("SMS sent") ){
                swal("Message(s) sent.","","success");
              }
              else if ( request.responseText.includes("No Phone number found.") ){
                swal("","One or more phone numbers could not be found","warning");
              }
              else{
                swal("An error occured","","error");
              }
        }
    }

    }
    else {
      swal("Please select an event","","error");
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

          <h1><strong>Send SMS</strong></h1>

        </section>
        <br>
        <section class="content" id="content">

        <div>
          <div class="row-fluid">
              <label>Event</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="eventType" onchange="getSubscribers();">
                      <option data-hidden="true"></option>
                      <?php
                        $query = "SELECT * from event_type";
                        $queryResult = pg_query($query);
                        while ($row = pg_fetch_assoc($queryResult)) {

                            if ( $row['notification_type'] ){


                            echo '<option id="'.$row['event_type_id'].'" data-subtext="'.'['.$row['notification_type'].']'.'" disabled="disabled">';
                              echo $row['event_type_name'];
                            echo '</option>';



                            }


                            else {
                            echo '<option id="'.$row['event_type_id'].'">';
                              echo $row['event_type_name'];
                            echo '</option>';
                            }


                        }
                      ?>
              </select>
        </div>
        <br>
        <div class="form-group" style="width: 25%">
                  <label for="comment">Message:</label>
                  <textarea class="form-control" rows="5" id="messageBody"></textarea>
                  <label>Insert:</label>
                  <button onclick="appendName();">Name</button><button onclick="appendAddress();">Address</button><button onclick="appendMonth();">Month</button>
        </div>
        <div>
              <br>
              <label>Select a member</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="memberId" onchange="changesButtonState();">
                      <option data-hidden="true"></option>
                      <?php
                        $homeAddressQuery = "SELECT address1,home_id FROM HOMEID WHERE community_id=".$_SESSION['hoa_community_id'];
                        $homeAddressQueryResult = pg_query($homeAddressQuery);
                        $homeID = array();
                        while ($row2 = pg_fetch_assoc($homeAddressQueryResult)) {
                            $homeID[$row2['home_id']] = $row2['address1'];
                        }
                        $query = "SELECT * from hoaid where community_id =".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);
                        while ($row = pg_fetch_assoc($queryResult)) {
                            echo '<option id="'.$row['hoa_id'].'">';
                              echo $row['firstname'].' '.$row['lastname'].'('.$homeID[$row['home_id']].')';
                            echo '</option>';
                        }
                      ?>
              </select>
              <br>
              <br>
              <button type="button" class="btn btn-success" onclick="sendToSingleMember();" id="sendToSingleMemberButton">Send</button>
              <br>
              <br>
              <button type="button" class="btn btn-success" onclick="sendToAllSubscribers();" id="sendToAllButton" disabled="disabled">Send to all subscribers</button>
        </div>
        </div>
     
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
<!-- <script>
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
</script> -->

      <script type="text/javascript">
            $('.daterange').daterangepicker({
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