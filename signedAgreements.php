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

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

      <?php if($mode == 1) include "boardHeader.php"; else if($mode == 2) include "residentHeader.php"; ?>
      
      <?php if($mode == 1) include 'boardNavigationMenu.php'; else if($mode == 2) include "residentNavigationMenu.php"; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

        	$year = date("Y");
        	$month = date("m");
        	$end_date = date("t");

          $result = pg_query("SELECT * FROM community_sign_agreements WHERE community_id=$community_id AND agreement_status='SIGNED' AND board_cancel_requested='f'");

        ?>
        
        <section class="content-header">

          <h1><strong>Signed Agreements</strong></h1>

          <?php

          if($mode == 1)
            echo "

              <ol class='breadcrumb'>
                
                <li><a href='boardDashboard.php'><i class='fa fa-dashboard'></i> Board Dashboard</a></li>
                <li>Signed Agreements</li>
              
              </ol>

            ";

          ?>

        </section>

        <section class="content">
          
          <div class="row">

          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Agreement To</th>
                        <th>Email</th>
                        <th>Agreement Name</th>
                        <th>Create Date</th>
                        <th>Send Date</th>
                        <th>Last Updated</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        while($row = pg_fetch_assoc($result))
                        {
                          
                          $id = $row['id'];
                          $document_to = $row['document_to'];
                          $create_date = $row['create_date'];
                          $send_date = $row['send_date'];
                          $agreement_name = $row['agreement_name'];
                          $last_updated = $row['last_updated'];
                          $agreement_id = $row['agreement_id'];
                          $hoa_id = $row['hoa_id'];

                          if($create_date != "")
                            $create_date = date('m-d-Y', strtotime($create_date));

                          if($send_date != "")
                            $send_date = date('m-d-Y', strtotime($send_date));

                          if($last_updated != "")
                            $last_updated = date('m-d-Y', strtotime($last_updated));

                          if($document_to != "")
                          {  

                            echo "<tr>";
                              
                            $result1 = pg_query("SELECT * FROM hoaid WHERE email='".$document_to."'");

                            if(pg_num_rows($result1))
                            {

                              $row1 = pg_fetch_assoc($result1);
                                
                              $name = $row1['firstname'];
                              $name .= " ";
                              $name .= $row1['lastname'];
                              $hoa_id = $row1['hoa_id'];

                              echo "<td>".$name."<br>($hoa_id)</td>";

                            }
                            else if($hoa_id != "")
                            {

                              $result1 = pg_query("SELECT * FROM hoaid WHERE hoa_id='".$hoa_id."'");

                              $row1 = pg_fetch_assoc($result1);
                                
                              $name = $row1['firstname'];
                              $name .= " ";
                              $name .= $row1['lastname'];

                              echo "<td>".$name."<br>($hoa_id)</td>";
                            }
                            else
                            {
                              
                              $result1 = pg_query("SELECT * FROM vendor_master WHERE email='".$document_to."'");

                              if(pg_num_rows($result1))
                              {  

                                $row1 = pg_fetch_assoc($result1);

                                echo "<td>".$row1['vendor_name']."</td>";

                              }
                              else
                              {  

                                echo "

                                <div class='modal fade hmodal-success' id='addHOAId_".$id."' role='dialog'  aria-hidden='true'>
                                
                                  <div class='modal-dialog'>
                                                      
                                    <div class='modal-content'>
                                          
                                      <div class='modal-header'>
                                                                  
                                        <h4 class='modal-title'>Agreement sent to <strong>".$document_to."</strong></h4>

                                      </div>

                                      <div class='modal-body'>
                                                                  
                                        <div class='container-fluid'>

                                          <form class='row' method='post' action='https://hoaboardtime.com/addAgreementHOAID.php'>

                                            <div class='row container-fluid'>

                                              <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                              
                                                <center>Select User</center>

                                                <br>

                                                <select class='form-control select2' name='select_hoa' id='select_hoa' style='width: 100%;' >

                                                  <option value='' disabled selected>Select User</option>";

                                                  $result000 = pg_query("SELECT * FROM hoaid WHERE community_id=$community_id ORDER BY firstname");

                                                  while($row000 = pg_fetch_assoc($result000))
                                                  {

                                                    $add_hoa_id = $row000['hoa_id'];
                                                    $name = $row000['firstname'];
                                                    $name .= " ";
                                                    $name .= $row000['lastname'];

                                                    echo "<option value='".$add_hoa_id."'>".$name."</option>";
                                                  }

                                                echo "</select>

                                                <input type='hidden' name='document_to' id='document_to' value='".$document_to."'>
                                                <input type='hidden' name='id' id='id' value='".$id."'>

                                                <br><br><center>OR</center><br><br>

                                                <center>Select Vendor</center>

                                                <br>
                                                
                                                <select class='form-control select2' name='select_vendor' id='select_vendor' style='width: 100%;' >

                                                  <option value='' disabled selected>Select Vendor</option>";

                                                  $result000 = pg_query("SELECT * FROM vendor_master WHERE community_id=$community_id ORDER BY vendor_name");

                                                  while($row000 = pg_fetch_assoc($result000))
                                                  {

                                                    $add_vendor_id = $row000['vendor_id'];
                                                    $vendor_name = $row000['vendor_name'];

                                                    echo "<option value='".$add_vendor_id."'>".$vendor_name."</option>";
                                                  }

                                                echo "</select>

                                                <br><br>

                                                <center><input type='checkbox' name='board_document' id='board_document' value='Yes'> <label> Is board document?</label></center>

                                                <input type='hidden' name='flag' id='flag' value='1'>

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

                                echo "<td><a data-toggle='modal' data-target='#addHOAId_".$id."'>N/A</a></td>";

                              }
                              
                            }

                            if($document_to != '')
                            {
                                    
                              $arr = array();
                              $arr = explode('@', $document_to);
                              $document_to = $arr[0];
                              $i = strlen($document_to);

                              for($j = 3; $j < $i; $j++)
                                $document_to[$j] = '*';

                              $document_to = $document_to.'@'.$arr[1];

                            }

                            echo "<td>".$document_to."</td><td><a target='_blank' href='https://hoaboardtime.com/esignPreview.php?id=".$agreement_id."'>".$agreement_name."</a></td><td>".$create_date."</td><td>".$send_date."</td><td>".$last_updated."</td></tr>";

                          }

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Agreement To</th>
                        <th>Email</th>
                        <th>Agreement Name</th>
                        <th>Create Date</th>
                        <th>Send Date</th>
                        <th>Last Updated</th>

                      </tr>

                    </tfoot>

                  </table>

                </div>

              </div>

            </section>

          </div>

        </section>

      </div>

      <?php include "footer.php"; ?>

      <div class="control-sidebar-bg"></div>

    </div>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/demo.js"></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50 });
      });
    </script>

  </body>

</html>