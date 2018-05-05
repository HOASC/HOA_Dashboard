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

      if($mode == 2)
        header("Location: residentDashboard.php");

    ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <title><?php echo $_SESSION['hoa_community_name'].' Year End Report '.date('Y',strtotime('-1 year')); ?></title>
    
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

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
      function emailStatement(hoaid){
          showPleaseWait();
            var request = new XMLHttpRequest();
  request.open("POST","https://hoaboardtime.com/getEmails.php",true);
  request.send(hoaid.id);
  request.onreadystatechange = function (){
      if (request.readyState == XMLHttpRequest.DONE) {
        hidePleaseWait();
            if ( request.responseText == "Failed to connect to database"){
                alert("Failed to connect to database.Please try again");
                return;
            }
            else if (request.responseText == "An error occured" ){
              alert(request.responseText);
              return;
            }
            var json = JSON.parse(request.responseText);
            var str = "";
            var count = 0;
            for ( var i = 0 ;i<json.length;i++){
               str = str.concat(json[i].email);
               str = str.concat(" ");
               count = count + 1;
            }
            if ( count > 1){
            showEmails(str,hoaid.id);
            }
            else {
              mailStatement2(hoaid.id,str);
            }
        }
      }
      }
      function sendSouthData(hoaid){
        showPleaseWait();
        jsonObj = [];
        item = {};
        item["hoaid"] = hoaid.id;
        jsonObj.push(item);
        lol =  JSON.stringify(jsonObj);
        var request= new XMLHttpRequest();
        request.open("POST", "https://hoaboardtime.com/sendViaSouthData.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(lol);
        request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
              hidePleaseWait();
              alert(request.responseText);
          }
          }
      }
      function showEmails(email,hoaid){
        $("#pleaseWaitDialog2").find('.modal-header').html('<h3>Edit Email</h3>');
        $("#pleaseWaitDialog2").find('.modal-body').html('\
        <label for="example-text-input">Emails</label>\
        <div>\
        <input class="form-control" type="text" value="'+email+'" id="emails">\
        </div>\
        ');
        $("#pleaseWaitDialog2").find('.modal-footer').html('<button type="button" id="'+hoaid+'" class="btn btn-success btn-lg" onclick="mailStatement(this);">Mail Statement</button>\
          <button type="button" class="btn btn-danger btn-lg" onclick="closeModal();">Close</button>');
        $("#pleaseWaitDialog2").modal("show");
      }
      function closeModal(){
         $("#pleaseWaitDialog2").modal("hide");
      }

      function mailStatement(button){
        closeModal();
        hidePleaseWait();
        showPleaseWait();
          var emails = document.getElementById("emails").value;
           jsonObj = [];
          item = {};
          item["hoaid"] = button.id;
    item["email"] = emails;
    jsonObj.push(item);
    lol =  JSON.stringify(jsonObj);
    var request= new XMLHttpRequest();
    request.open("POST", "https://hoaboardtime.com/sendViaMandrillEmails.php", true);
    request.setRequestHeader("Content-type", "application/json");
    request.send(lol);
    showPleaseWait();
    request.onreadystatechange = function () {
          if (request.readyState == XMLHttpRequest.DONE) {
              hidePleaseWait();
              alert(request.responseText);
          }
          }
      }
      function mailStatement2(hoaid,email){
        closeModal();
        hidePleaseWait();
        showPleaseWait();
        jsonObj = [];
        item = {};
        item["hoaid"] = hoaid;
  item["email"] = email;
  jsonObj.push(item);
  lol =  JSON.stringify(jsonObj);
  var request= new XMLHttpRequest();
  request.open("POST", "https://hoaboardtime.com/sendViaMandrillEmails.php", true);
  request.setRequestHeader("Content-type", "application/json");
  request.send(lol);
  showPleaseWait();
  request.onreadystatechange = function () {
        if (request.readyState == XMLHttpRequest.DONE) {
            hidePleaseWait();
            alert(request.responseText);
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

          $today = date('Y-m-d');

        ?>
        
        <section class="content-header">

          <h1><strong>Year End Report <?php  echo date('Y',strtotime('-1 year')); ?></strong></h1>

          <ol class="breadcrumb">
            
            <?php if($mode == 1) echo "<li><i class='fa fa-street-view'></i> Users</li>"; ?>

            <li>Customer Balance</li>
          
          </ol>

        </section>

        <section class="content">

          <div class="row">

            <center>
              
              <form method='POST' action='yearEndReport.php'>
                  
                <?php

                  if(isset($_POST['submit']))
                  {    
                                    
                    switch($_POST['having'])
                    {
                                        
                      case 1 :
                        echo "Show customers having balance <input type='radio' name='having' checked id='having' value='1' required> Greater than <input type='radio' name='having' id='having' value='2'> Equal to <input type='radio' name='having' id='having' value='3'> Less than <b>$</b> <input type='number' name='value' size=2 id='value' step='0.01' value='".$_POST['value']."' required><br><br><input type='submit' class='btn btn-warning btn-xs' name='submit' id='submit' value='Show Customers'>";
                      break; 

                      case 2 :
                        echo "Show customers having balance <input type='radio' name='having' id='having' value='1' required> Greater than <input type='radio' name='having' id='having' checked value='2'> Equal to <input type='radio' name='having' id='having' value='3'> Less than <b>$</b> <input type='number' name='value' size=2 id='value' step='0.01' value='".$_POST['value']."' required><br><br><input type='submit' class='btn btn-warning btn-xs' name='submit' id='submit' value='Show Customers'>";
                      break;

                      case 3 :
                        echo "Show customers having balance <input type='radio' name='having' id='having' value='1' required> Greater than <input type='radio' name='having' id='having' value='2'> Equal to <input type='radio' name='having' id='having' checked value='3'> Less than <b>$</b> <input type='number' name='value' size=2 id='value' step='0.01' value='".$_POST['value']."' required><br><br><input type='submit' class='btn btn-warning btn-xs' name='submit' id='submit' value='Show Customers'>";
                      break;

                    }

                  }
                  else
                    echo "Show customers having balance <input type='radio' name='having' id='having' value='1' checked required> Greater than <input type='radio' name='having' id='having' value='2'> Equal to <input type='radio' name='having' id='having' value='3'> Less than $ <input type='number' size=2 name='value' id='value' step='0.01' value='0.00' required><br><br><input type='submit' class='btn btn-warning btn-xs' name='submit' id='submit' value='Show Customers'>";

                ?>

              </form>

            </center>

          </div>

          <br><br>
          
          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">

                <div class="box-body table-responsive">
                  
                  <?php
                    
                    if(isset($_POST['submit']))
                    {
                                
                      $having = $_POST['having'];
                      $value = $_POST['value'];

                      if($having == 1)
                      {
                        
                        $result = pg_query("SELECT cc.hoa_id, cc.home_id FROM current_charges cc GROUP BY cc.home_id, cc.hoa_id HAVING (sum(cc.amount)-(SELECT sum(amount) FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND home_id=cc.home_id AND hoa_id=cc.hoa_id))>".$value." ORDER BY cc.home_id"); 

                      }
                      else if ($having == 2) 
                      {
                        
                        $result = pg_query("SELECT cc.hoa_id, cc.home_id FROM current_charges cc GROUP BY cc.home_id, cc.hoa_id HAVING (sum(cc.amount)-(SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND community_id=$community_id AND home_id=cc.home_id AND hoa_id=cc.hoa_id))=".$value." ORDER BY cc.home_id"); 

                      }
                      else
                      {
                        
                        $result = pg_query("SELECT cc.hoa_id, cc.home_id FROM current_charges cc GROUP BY cc.home_id, cc.hoa_id HAVING (sum(cc.amount)-(SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND community_id=$community_id AND home_id=cc.home_id AND hoa_id=cc.hoa_id))<".$value." ORDER BY cc.home_id"); 

                      }

                      echo "<br><center>Total Number of records fetched : ".pg_num_rows($result)."</center><br>";

                      echo "<table class='table table-striped table-bordered' id='example1' width=100%>";

                     echo "<thead><th>Name</th><th>Living In</th><th>Total Charges</th><th>Total Payments</th><th>Total Balance</th></thead><tbody>";

                        $totalPayments = 0;
                        $totalCharges  = 0;
                        $totalBalance = 0;

                      while ($row = pg_fetch_assoc($result)) 
                      {


                        
                        $hoa_id = $row['hoa_id'];
                        $home_id = $row['home_id'];

                        $result1 = pg_query("SELECT firstname, community_id, lastname, email, cell_no FROM hoaid WHERE hoa_id=".$hoa_id);
                        $row1 = pg_fetch_assoc($result1);

                        $community_id = $row1['community_id'];
                        $name = $row1['firstname'];
                        $name .= " ";
                        $name .= $row1['lastname'];
                        $email = $row1['email'];
                        $phone = $row1['cell_no'];

                        if($phone != '')
                          $phone = base64_decode($phone);

                        $result1 = pg_query("SELECT address1 FROM homeid WHERE community_id=$community_id AND home_id=$home_id");
                        $row1 = pg_fetch_assoc($result1);

                        $address = $row1['address1'];

                        $result1 = pg_query("SELECT sum(amount) FROM current_charges WHERE community_id=$community_id AND home_id=$home_id AND hoa_id=$hoa_id");
                        $row1 = pg_fetch_assoc($result1);

                        $charges = $row1['sum'];

                        $result1 = pg_query("SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND community_id=$community_id AND home_id=$home_id AND hoa_id=$hoa_id");
                        $row1 = pg_fetch_assoc($result1);

                        $payments = $row1['sum'];

                        if($payments == "")
                          $payments = 0.0;

                        $balance = $charges - $payments;

                        $result1 = pg_query("SELECT * FROM reminders WHERE home_id=$home_id AND is_active='t'");
                        $numrow1 = pg_num_rows($result1);
                        
                        if($numrow1 != 0 )
                        {  

                          $row1 = pg_fetch_assoc($result1);
                          $rid = $row1['id'];
                          $o_date = $row1['open_date'];
                          $d_date = $row1['due_date'];
                          $reminder_type_id = $row1['reminder_type_id'];
                          $comment = $row1['comments'];

                          echo "

                          <div class='modal fade hmodal-success' id='editReminder_$rid' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                                      
                              <div class='modal-content'>
                                          
                                <div class='modal-header'>
                                                                  
                                  <h4 class='modal-title'>Edit Reminder - <strong>".$name."</strong></h4>

                                </div>

                                <div class='modal-body'>
                                                                  
                                  <div class='container-fluid'>

                                    <form class='row' method='post' action='boardEditReminder.php'>

                                      <div class='row container-fluid'>

                                        <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                          <label>Open Date</label>
                                          <input class='form-control' type='date' name='edit_reminder_open_date' id='edit_reminder_open_date' value='$o_date' readonly>

                                        </div>

                                        <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                          <label>Due Date</label>
                                          <input class='form-control' type='date' name='edit_reminder_due_date' id='edit_reminder_due_date' value='$d_date' required>

                                        </div>

                                      </div>

                                      <br>

                                      <div class='row container-fluid'>

                                        <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                          <label>Reminder Type</label>
                                          <select class='form-control' type='date' name='edit_reminder_type' id='edit_reminder_type' required>

                                            <option value='' selected disabled>Select Reminder Type</option>";

                                            $ree = pg_query("SELECT * FROM reminder_type ORDER BY reminder_type");

                                            while($roo = pg_fetch_assoc($ree))
                                            {

                                              $r_id = $roo['id'];
                                              $r_type = $roo['reminder_type'];

                                              echo "<option ";

                                              if($r_id == $reminder_type_id)
                                                echo " selected ";

                                              echo "value='$r_id'>$r_type</option>";
                                            }

                                          echo "</select>

                                        </div>

                                        <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                          <label>Vendor Assigned</label>
                                          <select class='form-control' type='date' name='edit_vendor' id='edit_vendor'>

                                            <option value='' selected>NONE</option>";

                                            $ree = pg_query("SELECT * FROM vendor_master WHERE community_id=$community_id");

                                            while($roo = pg_fetch_assoc($ree))
                                            {

                                              $vendor_id = $roo['vendor_id'];
                                              $vendor_name = $roo['vendor_name'];

                                              echo "<option value='$vendor_id'>$vendor_name</option>";
                                            }

                                          echo "</select>

                                          <input type='hidden' name='reminder_id' id='reminder_id' value='$rid'>

                                        </div>

                                      </div>

                                      <br>

                                      <div class='row container-fluid'>

                                        <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                          <label>Comment</label>
                                          <textarea id='edit_comment' name='edit_comment' class='form-control' required>$comment</textarea>

                                        </div>

                                      </div>

                                      <br>

                                      <div class='row container-fluid text-center'>
                                              
                                        <button type='submit' name='submit' id='submit' class='btn btn-success btn-xs'><i class='fa fa-check'></i> Update</button>
                                        <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'><i class='fa fa-close'></i> Cancel</button>

                                      </div>

                                    </form>
                                                                  
                                  </div>

                                </div>

                              </div>
                                    
                            </div>

                          </div>

                          ";

                          $reminder = "<center><a data-toggle='modal' data-target='#editReminder_$rid'><i class='fa fa-bell text-green'></i></a></center>";
                        }
                        else
                          $reminder = "<center><a title='Set Reminder' href='boardSetReminder2.php?name=$name&living_in=$address&hoa_id=$hoa_id&home_id=$home_id&email=$email'><i class='fa fa-bell'></i></a></center>";

                        if($email != '')
                        {
                                    
                          $aux_email = $email;
                                    
                          $arr = array();
                          $arr = explode('@', $email);
                          $email = $arr[0];
                          $i = strlen($email);

                          for($j = 3; $j < $i; $j++)
                            $email[$j] = '*';

                          $email = $email.'@'.$arr[1];

                          echo "<div class='modal fade hmodal-success' id='send_email_".$hoa_id."' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                              
                              <div class='modal-content'>
                                                  
                                <div class='color-line'></div>
                                  
                                  <div class='modal-header'>
                                                          
                                    <h4 class='modal-title'>Send Email to ".$name." - ".$email."</h4>

                                  </div>

                                  <form class='row' method='post' action='sendEmailToCustomer.php'>
                                                      
                                    <div class='modal-body'>
                                        
                                        <div class='row container-fluid'>
                                
                                          <label>Subject</label>
                                          <input class='form-control' type='text' name='mail_subject' id='mail_subject' required placeholder='Enter Mail Subject'>

                                        </div>

                                        <br>

                                        <div class='row container-fluid'>
                                          
                                          <label>Message</label>
                                          <textarea class='form-control' name='mail_body' id='mail_body' required placeholder='Enter Email Body'></textarea>

                                          <input type='hidden' name='mail_email' id='mail_email' value='$aux_email'>
                                          <input type='hidden' name='token' id='token' value='1'>

                                        </div>

                                        <br><br>

                                        <center>
                                        <button type='submit' name='submit' id='submit' class='btn btn-success btn-xs'><i class='fa fa-check'></i>Send Email</button>
                                        <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'><i class='fa fa-close'></i>Close</button>
                                        </center>

                                    </div>

                                  </form>

                                </div>
                            
                              </div>

                            </div>";

                          $email = "<a data-toggle='modal' data-target='#send_email_".$hoa_id."'>".$email."</a>";

                        }

                        $totalCharges = $totalCharges + $charges;

                        $totalPayments = $totalPayments  + $payments;

                        $totalBalance = $totalBalance + $balance;

                        echo "<tr><td><a title='User Dashboard' href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>$name ($hoa_id)</a></td><td>$address ($home_id)</td><td>$ $charges</td><td>$ $payments</td><td>$ $balance</td></tr>";


                      }

                      echo"</tbody><tfoot><th></th><th></th><th>$ $totalCharges</th><th>$totalPayments</th><th>$ $totalBalance</th></tfoot><table>";



                    }
                    else
                    {

                      $totalBalance = 0 ;

                      $totalCharges = 0;

                      $totalPayments = 0;
                                
                      $having = 1;
                      $value = 0;

                      $result = pg_query("SELECT cc.hoa_id, cc.home_id FROM current_charges cc GROUP BY cc.home_id, cc.hoa_id HAVING (sum(cc.amount)-(SELECT sum(amount) FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND home_id=cc.home_id AND hoa_id=cc.hoa_id))>".$value." ORDER BY cc.home_id");

                      echo "<br><center>Total Number of records fetched : ".pg_num_rows($result)."</center><br>";

                      echo "<table class='table table-striped table-bordered' id='example1' width=100%>";

                      echo "<thead><th>Name</th><th>Living In</th><th>Total Charges</th><th>Total Payments</th><th>Total Balance</th></thead><tbody>";

                      $counter = 0 ;
                      while ($row = pg_fetch_assoc($result)) 
                      {

                        $counter = $counter + 1;
                        
                        $hoa_id = $row['hoa_id'];
                        $home_id = $row['home_id'];

                        $result1 = pg_query("SELECT firstname, community_id, lastname, email, cell_no FROM hoaid WHERE hoa_id=".$hoa_id);
                        $row1 = pg_fetch_assoc($result1);

                        $community_id = $row1['community_id'];
                        $name = $row1['firstname'];
                        $name .= " ";
                        $name .= $row1['lastname'];
                        $email = $row1['email'];
                        $phone = $row1['cell_no'];

                        if($phone != '')
                          $phone = base64_decode($phone);

                        $result1 = pg_query("SELECT address1 FROM homeid WHERE community_id=$community_id AND home_id=$home_id");
                        $row1 = pg_fetch_assoc($result1);

                        $address = $row1['address1'];

                        $result1 = pg_query("SELECT sum(amount) FROM current_charges WHERE community_id=$community_id AND home_id=$home_id AND hoa_id=$hoa_id");
                        $row1 = pg_fetch_assoc($result1);

                        $charges = $row1['sum'];

                        $result1 = pg_query("SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND community_id=$community_id AND home_id=$home_id AND hoa_id=$hoa_id");
                        $row1 = pg_fetch_assoc($result1);

                        $payments = $row1['sum'];

                        if($payments == "")
                                      $payments = 0.0;

                        $balance = $charges - $payments;

                        $result1 = pg_query("SELECT * FROM reminders WHERE home_id=$home_id AND hoa_id=$hoa_id");
                        $numrow1 = pg_num_rows($result1);

                        $row1 = pg_fetch_assoc($result1);

                        $o_date = $row1['open_date'];
                        $d_date = $row1['due_date'];

                        if($numrow1 != 0 && $today>=$d_date)
                        {
                          $reminder = "<center><a data-toggle='modal' data-target='#editReminder_$rid'><i class='fa fa-bell text-green'></i></a></center>";
                        }
                        else
                          $reminder = "<center><a title='Set Reminder' href='https://hoaboardtime.com/boardSetReminder2.php?name=$name&living_in=$address&hoa_id=$hoa_id&home_id=$home_id&email=$email'><i class='fa fa-bell'></i></a></center>";

                        if($email != '')
                        {
                                    
                          $aux_email = $email;
                                    
                          $arr = array();
                          $arr = explode('@', $email);
                          $email = $arr[0];
                          $i = strlen($email);

                          for($j = 3; $j < $i; $j++)
                            $email[$j] = '*';

                          $email = $email.'@'.$arr[1];

                          echo "<div class='modal fade hmodal-success' id='send_email_".$hoa_id."' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                              
                              <div class='modal-content'>
                                                  
                                <div class='color-line'></div>
                                  
                                  <div class='modal-header'>
                                                          
                                    <h4 class='modal-title'>Send Email to ".$name." - ".$email."</h4>

                                  </div>

                                  <form class='row' method='post' action='https://hoaboardtime.com/sendEmailToCustomer.php'>
                                                      
                                    <div class='modal-body'>
                                        
                                        <div class='row container-fluid'>
                                
                                          <label>Subject</label>
                                          <input class='form-control' type='text' name='mail_subject' id='mail_subject' required placeholder='Enter Mail Subject'>

                                        </div>

                                        <br>

                                        <div class='row container-fluid'>
                                          
                                          <label>Message</label>
                                          <textarea class='form-control' name='mail_body' id='mail_body' required placeholder='Enter Email Body'></textarea>

                                          <input type='hidden' name='mail_email' id='mail_email' value='$aux_email'>
                                          <input type='hidden' name='token' id='token' value='6'>

                                        </div>

                                        <br><br>

                                        <center>
                                        <button type='submit' name='submit' id='submit' class='btn btn-success btn-xs'><i class='fa fa-check'></i>Send Email</button>
                                        <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'><i class='fa fa-close'></i>Close</button>
                                        </center>

                                    </div>

                                  </form>

                                </div>
                            
                              </div>

                            </div>";

                          $email = "<a data-toggle='modal' data-target='#send_email_".$hoa_id."'>".$email."</a>";

                        }

                        $totalCharges = $totalCharges + $charges;

                        $totalPayments = $totalPayments  + $payments;

                        $totalBalance = $totalBalance + $balance;

                        echo "<tr><td><a title='User Dashboard' href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>$name ($hoa_id)</a></td><td>$address ($home_id)</td><td>$ $charges</td><td>$ $payments</td><td>$ $balance</td></tr>";


                      }



                      echo"</tbody><tfoot><th></th><th></th><th>$ $totalCharges</th><th>$totalPayments</th><th>$ $totalBalance</th></tfoot><table>";


                    }
                  
                  ?>

                </div>

              </div>

            </section>

          </div>

        </section>

      </div>

      <?php include 'footer.php'; ?>

      <div class="control-sidebar-bg"></div>

    </div>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/demo.js"></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50,
        dom: 'Bfrtip',
         buttons: [
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ] });
      });
    </script>

  </body>

</html>