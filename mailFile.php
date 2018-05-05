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
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>




    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
      



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
var fileData = "";
var fileName = "";
var ghoaID = -1;

function sendFile(){

  var address = $('#memberAddress').val();
  json = [];
  item = {};
  item['file_data'] = fileData;
  item['file_name'] = fileName;
  item['hoa_id'] =  ghoaID;
  item['address'] =  address;
  json.push(item);
  sendData = JSON.stringify(json);
  var request= new XMLHttpRequest();
  request.open("POST", "https://hoaboardtime.com/sendFileToSouthData.php", true);
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
        if (request.responseText.includes("Address incomplete. Please update address first")){
          swal("Error","Address incomplete. Please update address.","error");
        }
        else if ( request.responseText.includes("An error occured. Please try again.") ){
          swal("Error","An error occured. Please try again","error");
        }
        else {
          swal("Successful","File Sent To Southdata.","success");
        }
  }
  }

}

  function getAddress(){


      $("#memberAddress").find('option').remove();
      $("#memberAddress").selectpicker('refresh');
      var selectedMember = $('#memberID').find("option:selected").text();
      if ( selectedMember ){
      

        var pleaseWaitData = '<div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>';
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Please wait...</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html(pleaseWaitData);
        $("#pleaseWaitDialog2").modal("show");
        var memberId = $('#memberID').val();
        ghoaID = memberId;
        json = [];
        item = {};
        item["member_id"] = memberId;
        json.push(item);
        data = JSON.stringify(json);
        var request= new XMLHttpRequest();
        request.open("POST", "https://hoaboardtime.com/getAddress.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(data);
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
              $("#pleaseWaitDialog2").modal("hide");
              $("#memberAddress").append('<option selected="true" data-hidden="true"></option>');
              document.getElementById("memberAddress").options[0].disabled = false;
              var count = 1;
              for(var addresses in JSON.parse(request.responseText)){
                var address = JSON.parse(request.responseText)[addresses];
                $("#memberAddress").append('<option value="'+count+'">'+address+'</option>');
                count = count + 1;
              }
              $("#memberAddress").selectpicker('refresh');
        }
        }
      } 
      else {
        swal("Please select a member","","error");
      }
  }

  function getFileData()
{
  var file = document.getElementById("fileInput").files[0];
  if ( file ){
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function (evt) {
         fileData =evt.target.result.split(',')[1];
        return fileData;
    }
    reader.onerror = function (evt) {
        fileData = "Error";
        return fileData;
    }
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

          <h1><strong>Send To Member</strong></h1>

        </section>
        <br>
        <section class="content" id="content">
                 

                <div class="row-fluid">
                    <label>Select Member</label>
                    <br>
                    <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="memberID" onchange="getAddress();">
                      <option data-hidden="true"></option>
                      <?php

                        $query = "SELECT * FROM HOMEID WHERE COMMUNITY_ID =".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);
                        $homeIDS = array();

                        while ($row = pg_fetch_assoc($queryResult)) {
                          $homeIDS[$row['home_id']] = $row['address1'];
                        }

                        $query = "SELECT * FROM HOAID WHERE COMMUNITY_ID =".$_SESSION['hoa_community_id'];
                        $queryResult = pg_query($query);

                        $hoaIDS = array();
                        $hoaHomeIDS = array();
                        while ($row = pg_fetch_assoc($queryResult)) {
                          $hoaIDS[$row['hoa_id']] = $row['firstname'].' '.$row['lastname'];
                          $hoaHomeIDS[$row['hoa_id']] = $row['home_id'];
                        }

                        foreach ($hoaIDS as $key => $value) {
                          echo '<option value="'.$key.'" data-subtext="'.$value.'('.$key.')'.'">'.$homeIDS[$hoaHomeIDS[$key]].'</option>';
                        }


                      ?>
                    </select>
                </div>

                <br>
                
                <div class="row-fluid">
                    <label>ADDRESS</label>
                    <br>
                    <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="memberAddress" onchange="">
                    </select>
                </div>

                <div style="clear: both;"></div>
                <br>
                <br>
                    <div>
      
      <label class="btn btn-default" >Select File<input type="file" id="fileInput" hidden>
      </label>
      <h4 id="label"></h4>
      </div>
      <br>
      <button type="button" class="btn btn-success" onclick="sendFile();" id="saveButton" disabled="disabled">Send</button>
      <br>
      <script type="text/javascript">
        document.getElementById('fileInput').onchange = function () {
          var f =  this.value;
          f = f.replace(/.*[\/\\]/, '');
          fileName  = f;
           document.getElementById("label").innerHTML = f;
           document.getElementById("saveButton").disabled = false;
          getFileData();
        };
      </script>
                <br>
                <br>

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


  </body>

</html>