<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();
  include 'includes/api_keys.php';

?>

<!DOCTYPE html>
<html>
  <head>
	
    <?php

      if(@!$_SESSION['hoa_username'])
      	header("Location: https://hoaboardtime.com/logout.php");

      $community_id = $_SESSION['hoa_community_id'];
      $user_id=$_SESSION['hoa_user_id'];

      include 'includes/dbconn.php';

      if($_SESSION['hoa_mode'] == 2)
        header("Location: https://hoaboardtime.com/residentDashboard.php");

      $hoa_id = $_GET['hoa_id'];

      $hoa_id = base64_decode($hoa_id);

      $year = date("Y");
      $month = date("m");
      $end_date = date("t");

      $row = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$hoa_id"));

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
    <link rel="stylesheet" href="plugins/select2/select2.min.css">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    
    <script src="dist/js/googleanalytics.js"></script>

    <script type="text/javascript">
      var dimensionValue1 = '<?php echo $_SESSION['hoa_hoa_id'] ?>';
      var dimensionValue2 = "${communityInfo.communityCode}";
      if(<?php echo $community_id; ?> == 2)
        ga('create', 'UA-102881886-2', 'auto');
      ga('set', 'dimension1', dimensionValue1);
      ga('set', 'dimension2', dimensionValue2);
      ga('send', 'pageview');
    </script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

      <?php include 'boardHeader.php'; ?>
      
      <?php include 'boardNavigationMenu.php'; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">
        
        <section class="content-header">

          <h1><strong>User Dashboard</strong></h1>

          <ol class="breadcrumb">
            
            <li><a href='userDashboard.php'>User Dashboard</a></li>
            <li><?php echo $row['firstname']." ".$row['lastname']; ?></li>
          
          </ol>

        </section>

        <section class="content">

          <div class='row container-fluid'>

            <div class="nav-tabs-custom">
            
              <ul class="nav nav-tabs">

                <li class="active"><a href="#tab_1" data-toggle="tab">Owner &amp; Home</a></li>
                <li><a href="#tab_2" data-toggle="tab">Account Statement</a></li>
                <li><a href="#tab_3" data-toggle="tab">Agreements</a></li>
                <li><a href="#tab_4" data-toggle="tab">Communication</a></li>
                <li><a href="#tab_5" data-toggle="tab">Documents</a></li>
                <li><a href="#tab_6" data-toggle="tab">Inspections</a></li>
                <li><a href="#tab_7" data-toggle="tab">Payments</a></li>
                <li><a href="#tab_8" data-toggle="tab">Statements Mailed</a></li>

              </ul>

              <div class="tab-content">
                
                <div class="tab-pane active" id="tab_1">
                  
                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-success">

                        <div class="box-header">

                          <center><h4><strong>Owner Details</strong></h4></center>
                          
                          <i class="fa fa-"></i>

                          <div class="box-tools pull-right">

                            <a data-toggle="modal" data-target="#editUserDetails" class='btn-xs'><i class='fa fa-edit'></i> Edit</a>

                          </div>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table class="table table-bordered">

                            <thead>
                              
                              <th>Name (HOA ID)</th>
                              <th>Resident Since</th>
                              <th>Role</th>
                              <th>Email</th>
                              <th>Phone</th>

                            </thead>

                            <tbody>

                              <?php

                                $firstname = $row['firstname'];
                                $lastname = $row['lastname'];
                                $valid_from = $row['valid_from'];
                                $valid_until = $row['valid_until'];
                                $email = $row['email'];
                                $cell_no = $row['cell_no'];
                                $role = $row['role_type_id'];
                                $home_id = $row['home_id'];

                                if($cell_no != '')
                                  $cell_no = base64_decode($cell_no);

                                if($email != '')
                                {
                                  
                                  $arr = array();
                                  $arr = explode('@', $email);
                                  $email = $arr[0];
                                  $i = strlen($email);

                                  for($j = 3; $j < $i; $j++)
                                    $email[$j] = '*';

                                  $email = $email.'@'.$arr[1];

                                }

                                if($role != "")
                                {
                                  $row = pg_fetch_assoc(pg_query("SELECT * FROM role_type WHERE role_type_id=$role"));

                                  $role = $row['name'];
                                }

                                echo "<tr><td>$firstname $lastname ($hoa_id)</td><td>$valid_from</td><td>$role</td><td>$email</td><td>$cell_no</td></tr>";

                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                      <div class="modal fade hmodal-success" id="editUserDetails" role="dialog"  aria-hidden="true">
                                        
                        <div class="modal-dialog">
                                            
                          <div class="modal-content">
                                                
                            <div class="color-line"></div>
                                
                            <div class="modal-header">
                                                        
                              <h4 class="modal-title"><strong>User Details</strong></h4>

                            </div>

                            <form class="row" method="post" action="https://hoaboardtime.com/boardEditHOAID.php">
                                                    
                              <div class="modal-body">
                                                        
                                <div class="container-fluid">
                                      
                                  <div class="row container-fluid">
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>First Name</label>
                                      <input type='text' class="form-control" name='edit_firstname' id='edit_firstname' value="<?php echo $firstname; ?>" readonly>
                                    </div>
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Last Name</label>
                                      <input type='text' class="form-control" name='edit_lastname' id='edit_lastname' value="<?php echo $lastname; ?>" readonly>
                                    </div>

                                  </div>

                                  <br>

                                  <div class="row container-fluid">
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Phone</label>
                                      <input type='number' class="form-control" name='edit_cell_no' id='edit_cell_no' value="<?php echo $cell_no; ?>" required>
                                    </div>
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Email</label>
                                      <input type='email' class="form-control" name='edit_email' id='edit_email' value="<?php echo $email; ?>" required>
                                    </div>

                                  </div>

                                  <br>

                                  <div class="row container-fluid">
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Resident Since</label>
                                      <input type='date' class='form-control' name='edit_valid_from' id='edit_valid_from' value='<?php echo $valid_from; ?>' required>
                                    </div>
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Resident Until</label>
                                      <input type='date' class='form-control' name='edit_valid_until' id='edit_valid_until' value='<?php echo $valid_until; ?>' >

                                      <input type='hidden' name='hoa_id' id='hoa_id' value="<?php echo $hoa_id; ?>">
                                    </div>

                                  </div>

                                  <br>

                                  <div class="row text-center">
                                    <button type="submit" name='submit' id='submit' class="btn btn-success btn-xs"><i class='fa fa-check'></i>Save Changes</button>
                                    <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal"><i class='fa fa-close'></i>Cancel</button>
                                  </div>
                                                        
                                </div>

                              </div>

                            </form>

                          </div>
                          
                        </div>

                      </div>

                    </section>

                  </div>

                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-success">

                        <div class="box-header">

                          <center><h4><strong>Home Details</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table class="table table-bordered">

                            <thead>
                              <?php

                                $row = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));


                                $living_status = $row['living_status'];

                              ?>
                              
                              <th>Home Address (Home ID)</th>
                              <th>Living Status</th>
                              <th>Lot</th>
                              <?php if($living_status == 'f') echo "<th>Mailing Address</th>"; ?>

                            </thead>

                            <tbody>

                              <?php

                                $address1 = $row['address1'];
                                $address2 = $row['address2'];
                                $home_id = $row['home_id'];
                                $lot = $row['lot'];

                                if($living_status == "t")
                                  $living_status = "TRUE";
                                else
                                  $living_status = "FALSE";

                                if($living_status == "TRUE")
                                {

                                  $address = $row['address1'];
                                  $address .= " ";
                                  $address .= $row['address2'];
                                  $city = $row['city_id'];
                                  $state = $row['state_id'];
                                  $zip = $row['zip'];

                                }
                                else
                                {
                                  $row = pg_fetch_assoc(pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id"));

                                  $address = $row['address1'];
                                  $address .= " ";
                                  $address .= $row['address2'];
                                  $city = $row['city_id'];
                                  $state = $row['state_id'];
                                  $zip = $row['zip'];
                                }

                                if($city != '')
                                {
                                  $row = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$city"));
                                  $city = $row['city_name'];
                                }

                                if($state != '')
                                {
                                  $row = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$state"));
                                  $state = $row['state_code'];
                                }

                                if($zip != '')
                                {
                                  $row = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$zip"));
                                  $zip = $row['zip_code'];
                                }

                                $row = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$pay_method"));
                                $pay_method = $row['payment_type_name'];

                                echo "<tr><td>$address1 $address2 ($home_id)</td><td>$living_status</td><td>$lot</td>";

                                if($living_status == 'FALSE')
                                  echo "<td>$address, $city, $state $zip</td>";

                                echo "</tr>";

                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-success">

                        <div class="box-header">

                          <center><h4><strong>Persons</strong></h4></center>

                          <i class="fa fa-"></i>

                          <div class="box-tools pull-right">

                            <a data-toggle="modal" data-target="#addPerson" class='btn-xs'><i class='fa fa-plus'></i> Add Person</a>

                          </div>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table class="table table-bordered">

                            <thead>
                              <?php

                                $res = pg_query("SELECT * FROM person WHERE hoa_id=$hoa_id AND home_id=$home_id AND is_active='t'");

                              ?>
                              
                              <th>Name</th>
                              <th>Home</th>
                              <th>Role</th>
                              <th>Relationship</th>
                              <th>Email</th>
                              <th>Cell</th>
                              <th></th>
                              <th></th>

                            </thead>

                            <tbody>

                              <?php

                                while($r = pg_fetch_assoc($res))
                                {

                                  $person_role_type = $r['role_type_id'];
                                  $relationship = $r['relationship_id'];
                                  $person_email = $r['email'];
                                  $person_cell_no = $r['cell_no'];
                                  $person_fname = $r['fname'];
                                  $person_lname = $r['lname'];
                                  $person_home_id = $r['home_id'];
                                  $person_id = $r['id'];

                                  $person_person_email = $person_email;

                                  if($person_person_email != '')
                                  {
                                    
                                    $aux_email = $person_person_email;

                                    $arr = array();
                                    $arr = explode('@', $person_person_email);
                                    $person_person_email = $arr[0];
                                    $i = strlen($person_person_email);

                                    for($j = 3; $j < $i; $j++)
                                      $person_person_email[$j] = '*';

                                    $person_person_email = $person_person_email.'@'.$arr[1];

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
                                                    <input type='hidden' name='hoa_id' id='hoa_id' value='$hoa_id'>
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

                                    $person_person_email = "<a data-toggle='modal' data-target='#send_email_".$hoa_id."'>".$person_person_email."</a>";

                                  }

                                  $person_cell_no = base64_decode($person_cell_no);

                                  $r1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$person_home_id"));
                                  $address = $r1['address1'];

                                  $r1 = pg_fetch_assoc(pg_query("SELECT * FROM role_type WHERE role_type_id=$person_role_type"));
                                  $person_role_type = $r1['name'];

                                  $r1 = pg_fetch_assoc(pg_query("SELECT * FROM relationship WHERE id=$relationship"));
                                  $relationship = $r1['name'];

                                  echo "

                                  <div class='modal fade hmodal-success' id='editPerson_$person_id' role='dialog'  aria-hidden='true'>
                                        
                                    <div class='modal-dialog'>
                                                        
                                      <div class='modal-content'>
                                            
                                        <div class='modal-header'>
                                                                    
                                          <h4 class='modal-title'><strong>Edit Person - $person_fname $person_lname</strong></h4>

                                        </div>

                                        <form class='row' method='post' action='https://hoaboardtime.com/boardEditPerson.php'>
                                                                
                                          <div class='modal-body'>
                                                                    
                                            <div class='container-fluid'>
                                                  
                                              <div class='row container-fluid'>
                                                    
                                                <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                                  <label>First Name</label>
                                                  <input type='text' class='form-control' name='person_firstname' id='person_firstname' value='$person_fname' required>
                                                </div>
                                                    
                                                <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                                  <label>Last Name</label>
                                                  <input type='text' class='form-control' name='person_lastname' id='person_lastname' value='$person_lname' required>
                                                </div>

                                              </div>

                                              <br>

                                              <div class='row container-fluid'>
                                                    
                                                <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                                  <label>Phone</label>
                                                  <input type='number' class='form-control' name='person_cell_no' id='person_cell_no' value='$person_cell_no' required>
                                                </div>
                                                    
                                                <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                                  <label>Email</label>
                                                  <input type='email' class='form-control' name='person_email' id='person_email' value='$person_email' required>
                                                </div>

                                              </div>

                                              <br>

                                              <div class='row container-fluid'>
                                                    
                                                <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                                  <label>Role Type</label>
                                                  <select class='form-control' name='role_type' id='role_type' required>
                                                    <option value='' selected disabled>Select Role Type</option>";
                                                      
                                                      $res1 = pg_query("SELECT * FROM role_type ORDER BY name");

                                                      while ($r1 = pg_fetch_assoc($res1)) 
                                                      {
                                                        $name = $r1['name'];
                                                        $id = $r1['role_type_id'];

                                                        echo "<option value='$id'";

                                                          if($person_role_type == $name)
                                                            echo " selected ";

                                                        echo ">$name</option>";
                                                      }

                                                  echo "</select>
                                                </div>
                                                    
                                                <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>
                                                  <label>Relationship</label>
                                                  <select class='form-control' name='relationship' id='relationship' required>
                                                    <option value='' selected disabled>Select Relationship</option>";

                                                      $res1 = pg_query("SELECT * FROM relationship ORDER BY name");

                                                      while ($r1 = pg_fetch_assoc($res1)) 
                                                      {
                                                        $name = $r1['name'];
                                                        $id = $r1['id'];

                                                        echo "<option value='$id'";

                                                          if($relationship == $name)
                                                            echo " selected ";

                                                        echo ">$name</option>";
                                                      }
                                                    
                                                  echo "</select>

                                                  <input type='hidden' name='person_id' id='person_id' value='$person_id'>
                                                  <input type='hidden' name='hoa_id' id='hoa_id' value='$hoa_id'>
                                                </div>

                                              </div>

                                              <br>

                                              <div class='row text-center'>
                                                <button type='submit' name='submit' id='submit' class='btn btn-success btn-xs'><i class='fa fa-check'></i> Save</button>
                                                <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'><i class='fa fa-close'></i> Cancel</button>
                                              </div>
                                                                    
                                            </div>

                                          </div>

                                        </form>

                                      </div>
                                      
                                    </div>

                                  </div>

                                  ";echo "
                                  
                                  <div class='modal fade hmodal-success' id='removePerson_$person_id' role='dialog'  aria-hidden='true'>
                                        
                                    <div class='modal-dialog'>
                                                      
                                      <div class='modal-content'>
                                                          
                                        <div class='color-line'></div>
                                          
                                          <div class='modal-header'>
                                                                  
                                            <h4 class='modal-title'>Remove Person - $person_fname $person_lname</h4>

                                          </div>

                                          <div class='modal-body'>
                                                
                                            <form method='POST' action='https://hoaboardtime.com/boardRemovePerson.php'>

                                              <center>

                                                <input type='hidden' name='person_id' id='person_id' value='$person_id'>
                                                <input type='hidden' name='hoa_id' id='hoa_id' value='$hoa_id'>

                                                <h4>You are about to remove <strong>$person_fname $person_lname</strong>.</h4><br><br><h3><b>Are you sure you want to continue?</b></h3><br><small>This action cannot be undone.</small><br><br>

                                                <button type='submit' class='btn btn-warning btn-sm'>Remove</button> <button type='button' class='btn btn-success btn-sm' data-dismiss='modal'>Cancel</button>

                                              </center>

                                            </form>

                                          </div>

                                        </div>
                                    
                                      </div>

                                    </div>

                                    ";

                                  echo "<tr><td>$person_fname $person_lname</td><td>$address</td><td>$person_role_type</td><td>$relationship</td><td>$person_person_email</td><td>$person_cell_no</td><td><a data-toggle='modal' data-target='#editPerson_$person_id'>Edit</a></td><td><a data-toggle='modal' data-target='#removePerson_$person_id'>Remove</a></td></tr>";
                                }

                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                      <div class="modal fade hmodal-success" id="addPerson" role="dialog"  aria-hidden="true">
                                        
                        <div class="modal-dialog">
                                            
                          <div class="modal-content">
                                                
                            <div class="color-line"></div>
                                
                            <div class="modal-header">
                                                        
                              <h4 class="modal-title"><strong>Add Person</strong></h4>

                            </div>

                            <form class="row" method="post" action="https://hoaboardtime.com/boardAddPerson.php">
                                                    
                              <div class="modal-body">
                                                        
                                <div class="container-fluid">
                                      
                                  <div class="row container-fluid">
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>First Name</label>
                                      <input type='text' class="form-control" name='person_firstname' id='person_firstname' required>
                                    </div>
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Last Name</label>
                                      <input type='text' class="form-control" name='person_lastname' id='person_lastname' required>
                                    </div>

                                  </div>

                                  <br>

                                  <div class="row container-fluid">
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Phone</label>
                                      <input type='number' class="form-control" name='person_cell_no' id='person_cell_no' required>
                                    </div>
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Email</label>
                                      <input type='email' class="form-control" name='person_email' id='person_email' required>
                                    </div>

                                  </div>

                                  <br>

                                  <div class="row container-fluid">
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Role Type</label>
                                      <select class="form-control" name='role_type' id='role_type' required>
                                        <option value="" selected disabled>Select Role Type</option>
                                        <?php
                                          $res = pg_query("SELECT * FROM role_type ORDER BY name");

                                          while ($r = pg_fetch_assoc($res)) 
                                          {
                                            $name = $r['name'];
                                            $id = $r['role_type_id'];

                                            echo "<option value='$id'>$name</option>";
                                          }
                                        ?>
                                      </select>
                                    </div>
                                        
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                      <label>Relationship</label>
                                      <select class="form-control" name='relationship' id='relationship' required>
                                        <option value="" selected disabled>Select Relationship</option>
                                        <?php
                                          $res = pg_query("SELECT * FROM relationship ORDER BY name");

                                          while ($r = pg_fetch_assoc($res)) 
                                          {
                                            $name = $r['name'];
                                            $id = $r['id'];

                                            echo "<option value='$id'>$name</option>";
                                          }
                                        ?>
                                      </select>

                                      <input type='hidden' name='hoa_id' id='hoa_id' value="<?php echo $hoa_id; ?>">
                                      <input type='hidden' name='home_id' id='home_id' value="<?php echo $home_id; ?>">
                                    </div>

                                  </div>

                                  <br>

                                  <div class="row text-center">
                                    <button type="submit" name='submit' id='submit' class="btn btn-success btn-xs"><i class='fa fa-check'></i> Add</button>
                                    <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal"><i class='fa fa-close'></i> Cancel</button>
                                  </div>
                                                        
                                </div>

                              </div>

                            </form>

                          </div>
                          
                        </div>

                      </div>

                    </section>

                  </div>

                </div>

                <div class="tab-pane" id="tab_2">
                  
                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-info">

                        <div class="box-header">

                          <center><h4><strong>Accounts Statement</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table class="table table-striped">
                    
                            <thead>
                    
                              <tr>
                                
                                <th>Month</th>
                                <th>Document ID</th>
                                <th>Description</th>
                                <th>Charge</th>
                                <th>Payment</th>
                                <th>Balance</th>

                              </tr>
                            
                            </thead>

                            <tbody>
                              
                              <?php

                                for($m = 1; $m <= 12; $m++)
                                {

                                  $last_date = date("Y-m-t", strtotime("$year-$m-1"));
                                  
                                  $charges_results = pg_query("SELECT * FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id AND assessment_date>='$year-$m-1' AND assessment_date<='$last_date' ORDER BY assessment_date");

                                  $payments_results = pg_query("SELECT * FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND process_date>='$year-$m-1' AND process_date<='$last_date' ORDER BY process_date");

                                  $month_charge = 0.0;

                                  while($charges_row = pg_fetch_assoc($charges_results))
                                  {

                                    $month_charge += $charges_row['amount'];
                                    $tdate = $charges_row['assessment_date'];
                                    $desc = $charges_row['assessment_rule_type_id'];

                                    $r = pg_fetch_assoc(pg_query("SELECT * FROM assessment_rule_type WHERE assessment_rule_type_id=$desc"));
                                    $desc = $r['name'];

                                    echo "<tr><td>".date('F', strtotime($tdate))."</td><td>".$charges_row['id']."-".$charges_row['assessment_rule_type_id']."</td><td>".date("m-d-y", strtotime($tdate))."|".$desc."</td><td>$ ".$charges_row['amount']."</td><td></td><td>$ ".$month_charge."</td></tr>";

                                  }

                                  $month_payment = 0.0;

                                  while($payments_row = pg_fetch_assoc($payments_results))
                                  {

                                    $month_payment += $payments_row['amount'];
                                    $tdate = $payments_row['process_date'];

                                    echo "<tr><td>".date('F', strtotime($tdate))."</td><td>".$payments_row['id']."-".$payments_row['payment_type_id']."</td><td>".date("m-d-y", strtotime($tdate))."|"."Payment Received # ".$payments_row['document_num']."</td><td></td><td>$ ".$payments_row['amount']."</td><td>$ ".$month_payment."</td></tr>";

                                  }

                                }

                              ?>

                              <tr><td></td><td></td><td><strong>Total</strong></td><td><?php $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id")); $total_charges = $row['sum']; echo "<strong>$ ".$total_charges."</strong>"; ?></td><td><?php $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND payment_status_id=1")); $total_payments = $row['sum']; if($total_payments == "") $total_payments = 0.0; echo "<strong>$ ".$total_payments."</strong>"; ?></td><td><?php $total = $total_charges - $total_payments; echo "<strong>$ ".$total."</strong>"; ?></td></tr>

                            </tbody>
                  
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                </div>

                <div class="tab-pane" id="tab_3">
                  
                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-info">

                        <div class="box-header">

                          <center><h4><strong>Pending Agreements</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table id='example4' class="table table-bordered">

                            <thead>
                              
                              <th>Agreement Name</th>
                              <th>Email</th>
                              <th>Create Date</th>
                              <th>Send Date</th>
                              <th>Last Updated</th>
                              <th>Esign Document</th>

                            </thead>

                            <tbody>

                              <?php

                                $result =pg_query("SELECT * FROM community_sign_agreements WHERE community_id=$community_id AND agreement_status='OUT_FOR_SIGNATURE' AND board_cancel_requested='f' AND document_to IN (SELECT email FROM person WHERE hoa_id=$hoa_id AND home_id=$home_id)");

                                while($row = pg_fetch_assoc($result))
                                {
                        
                                  $id = $row['id'];
                                  $document_to = $row['document_to'];
                                  $create_date = $row['create_date'];
                                  $send_date = $row['send_date'];
                                  $agreement_name = $row['agreement_name'];
                                  $esign_url = $row['esign_url'];
                                  $last_updated = $row['last_updated'];
                                  $agreement_id = $row['agreement_id'];

                                  if($create_date != "")
                                    $create_date = date('m-d-Y', strtotime($create_date));

                                  if($send_date != "")
                                    $send_date = date('m-d-Y', strtotime($send_date));

                                  if($last_updated != "")
                                    $last_updated = date('m-d-Y', strtotime($last_updated));

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

                                  echo "<tr><td>$document_to</td><td><a title='Click to sign agreement' target='_blank' href='$esign_url'>$agreement_name</a></td><td>$create_date</td><td>$send_date</td><td>$last_updated</td></tr>";

                                }

                              ?>

                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-info">

                        <div class="box-header">

                          <center><h4><strong>Signed Agreements</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table id='example4' class="table table-bordered">

                            <thead>
                              
                              <th>Agreement Name</th>
                              <th>Email</th>
                              <th>Create Date</th>
                              <th>Send Date</th>
                              <th>Last Updated</th>

                            </thead>

                            <tbody>

                              <?php

                                $result = pg_query("SELECT * FROM community_sign_agreements WHERE community_id=$community_id AND agreement_status='SIGNED' AND document_to IN (SELECT email FROM person WHERE hoa_id=$hoa_id AND home_id=$home_id)");

                                while($row = pg_fetch_assoc($result))
                                {
                        
                                  $id = $row['id'];
                                  $document_to = $row['document_to'];
                                  $create_date = $row['create_date'];
                                  $send_date = $row['send_date'];
                                  $agreement_name = $row['agreement_name'];
                                  $last_updated = $row['last_updated'];
                                  $agreement_id = $row['agreement_id'];

                                  if($create_date != "")
                                    $create_date = date('m-d-Y', strtotime($create_date));

                                  if($send_date != "")
                                    $send_date = date('m-d-Y', strtotime($send_date));

                                  if($last_updated != "")
                                    $last_updated = date('m-d-Y', strtotime($last_updated));

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

                                  echo "<tr><td>$document_to</td><td><a target='_blank' href='esignPreview.php?id=$agreement_id'>$agreement_name</a></td><td>$create_date</td><td>$send_date</td><td>$last_updated</td></tr>";

                                }

                              ?>
                                                    
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                </div>

                <div class="tab-pane" id="tab_4">
                  
                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-info">

                        <div class="box-header">

                          <center><h4><strong>Communication Info</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table id='example6' class="table table-bordered table-striped">

                            <thead>
                              
                              <th>Date</th>
                              <th>Email</th>
                              <th>Subject</th>
                              <th>Number of Opens</th>
                              <th>Number of Clicks</th>

                            </thead>

                            <tbody>

                              <?php

                                date_default_timezone_set('America/Los_Angeles');
                                $uri = 'https://mandrillapp.com/api/1.0/messages/search.json';
                                if($community_id == 2)
                                  $api_key = $m_api_key_3;

                                $resss = pg_query("SELECT * FROM person WHERE hoa_id=$hoa_id");
                                
                                while($rooo = pg_fetch_assoc($resss))
                                {

                                  $per_email = $rooo['email'];

                                  $postString = '{
                                    "key": "'.$api_key.'",
                                    "query": "email:'.$per_email.'",
                                    "date_from": "'.date('Y-m-d', strtotime('-90 days')).'"
                                  }';

                                  $ch = curl_init();
                                  curl_setopt($ch, CURLOPT_URL, $uri);
                                  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
                                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
                                  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                  curl_setopt($ch, CURLOPT_POST, true);
                                  curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

                                  $result = curl_exec($ch); 
                                  $result = json_decode($result);

                                  foreach ($result as $result1) {

                                    $document_to = $result1->email;

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

                                    echo "<tr>";

                                    print_r("<td>".date('m-d-Y',$result1->ts)."</td>");
                                    print_r("<td>".$document_to."</td>");
                                    print_r("<td>".$result1->subject."</td>");
                                    print_r("<td>".$result1->opens."</td>");
                                    print_r("<td>".$result1->clicks."</td>");

                                    echo "</tr>";

                                  }

                                  curl_close($ch);

                                }

                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                </div>

                <div class="tab-pane" id="tab_5">
                  
                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-success">

                        <div class="box-header">

                          <center><h4><strong>Documents</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table id='example5' class="table table-bordered">

                            <thead>
                              
                              <th>Uploaded On</th>
                              <th>Description</th>
                              <th>Category</th>

                            </thead>

                            <tbody>

                              <?php

                                $row = pg_fetch_assoc(pg_query("SELECT * FROM member_info WHERE hid='$hoa_id'"));
                                $member_id = $row['member_id'];

                                $row = pg_fetch_assoc(pg_query("SELECT * FROM usr WHERE member_id=$member_id"));
                                $user_id = $row['id'];
                                
                                $result = pg_query("SELECT * FROM document_visibility WHERE user_id=$user_id");
                                
                                while($row = pg_fetch_assoc($result))
                                {
                                  
                                  $document_id = $row['document_id'];

                                  $row1 = pg_fetch_assoc(pg_query("SELECT * FROM document_management WHERE document_id=$document_id"));

                                  $desc = $row1['description'];
                                  $url = $row1['url'];
                                  $category = $row1['document_category_id'];
                                  $uploaded_date = $row1['uploaded_date'];
                                  $community = $row1['community_id'];

                                  if($uploaded_date != "")
                                    $uploaded_date = date('m-d-Y', strtotime($uploaded_date));

                                  if($category != "")
                                  {
                                    $row1 = pg_fetch_assoc(pg_query("SELECT * FROM document_category WHERE document_category_id=$category"));
                                    $category = $row1['document_category_name'];
                                  }
                                  else
                                    $category = "Others";

                                  if($community_id == $community)
                                    echo "<tr><td>$uploaded_date</td><td><a href='https://hoaboardtime.com/getDocumentPreview.php?path=$url&desc=$desc' target='_blank'>$desc</a></td><td>$category</td></tr>";

                                }

                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                </div>

                <div class="tab-pane" id="tab_6">
                  
                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-warning">

                        <div class="box-header">

                          <center><h4><strong>Inspection Notices</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table id='example2' class="table table-bordered">

                            <thead>
                              
                              <th>Inspection Date</th>
                              <th>Status</th>
                              <th>Location</th>
                              <th>Description</th>
                              <th>Category</th>
                              <th>Sub Category</th>
                              <th>Sub Category Rule</th>
                              <th>Sub Category Rule Description</th>
                              <th>Sub Category Rule Explanation</th>
                              <th>Notice Type</th>
                              <th>Document</th>
                              <th>Date of Upload</th>

                            </thead>

                            <tbody>

                              <?php 

                                $result = pg_query("SELECT * FROM inspection_notices WHERE home_id=$home_id AND hoa_id=$hoa_id");

                                while($row = pg_fetch_assoc($result))
                                {

                                  $description = $row['description'];
                                  $document = $row['document_id'];
                                  $inspection_date = $row['inspection_date'];
                                  $location = $row['location_id'];
                                  $violation_category = $row['inspection_category_id'];
                                  $violation_sub_category = $row['inspection_sub_category_id'];
                                  $notice_type = $row['inspection_notice_type_id'];
                                  $date_of_upload = $row['date_of_upload'];
                                  $status = $row['inspection_status_id'];

                                  $row1 = pg_fetch_assoc(pg_query("SELECT * FROM inspection_status WHERE id=$status"));

                                  $status = $row1['inspection_status'];

                                  $row1 = pg_fetch_assoc(pg_query("SELECT * FROM locations_in_community WHERE location_id=$location"));

                                  $location = $row1['location'];

                                  $row1 = pg_fetch_assoc(pg_query("SELECT * FROM inspection_category WHERE id=$violation_category"));

                                  $violation_category = $row1['name'];

                                  $row1 = pg_fetch_assoc(pg_query("SELECT * FROM inspection_sub_category WHERE id=$violation_sub_category"));

                                  $violation_sub_category = $row1['name'];
                                  $violation_sub_category_rule = $row1['rule'];
                                  $violation_sub_category_rule_description = $row1['rule_description'];
                                  $violation_sub_category_rule_explanation = $row1['explanation'];

                                  if($date_of_upload != "")
                                    $date_of_upload = date('m-d-Y', strtotime($date_of_upload));

                                  if($inspection_date != "")
                                    $inspection_date = date('m-d-Y', strtotime($inspection_date));
                                  
                                  echo "<tr><td>".$inspection_date."</td><td>".$status."</td><td>".$location."</td><td>".$description."</td><td>".$violation_category."</td><td>".$violation_sub_category."</td><td>".$violation_sub_category_rule."</td><td>".$violation_sub_category_rule_description."</td><td>".$violation_sub_category_rule_explanation."</td><td>".$notice_type."</td><td>".$document."</td><td>".$date_of_upload."</td></tr>";
                                  
                                }

                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                </div>

                <div class="tab-pane" id="tab_7">
                  
                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-success">

                        <div class="box-header">

                          <center><h4><strong>Payment Details</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table class="table table-bordered">

                            <thead>
                              <?php

                                $row = pg_fetch_assoc(pg_query("SELECT * FROM home_pay_method WHERE home_id=$home_id AND hoa_id=$hoa_id"));

                                $payment_type = $row['payment_type_id'];
                                $recurring_pay = $row['recurring_pay'];

                              ?>
                              
                              <th>Pay Method</th>
                              <th>Recurring Pay</th>
                              <?php if($recurring_pay == 't') echo "<th>Schedule Start Date</th><th>Schedule End Date</th><th>Schedule Expires On</th><th>Next Schedule Date</th><th>Schedule Frequency</th>"; ?>

                            </thead>

                            <tbody>

                              <?php

                                $schedule_start = $row['sch_start'];
                                $schedule_end = $row['sch_end'];
                                $schedule_expires = $row['sch_expires'];
                                $next_schedule = $row['next_sch'];
                                $schedule_frequency = $row['sch_frequency'];

                                if($schedule_start != "")
                                  $schedule_start = date('m-d-Y', strtotime($schedule_start));

                                if($schedule_end != "")
                                  $schedule_end = date('m-d-Y', strtotime($schedule_end));

                                if($schedule_expires != "")
                                  $schedule_expires = date('m-d-Y', strtotime($schedule_expires));

                                if($next_schedule != "")
                                  $next_schedule = date('m-d-Y', strtotime($next_schedule));

                                $row = pg_fetch_assoc(pg_query("SELECT * FROM payment_type WHERE payment_type_id=$payment_type"));
                                $payment_type = $row['payment_type_name'];

                                echo "<tr><td>$payment_type</td>";

                                if($recurring_pay == 't')
                                  echo "<td>Enabled</td><td>$schedule_start</td><td>$schedule_end</td><td>$schedule_expires</td><td>$next_schedule</td><td>$schedule_frequency</td>";
                                else
                                  echo "<td>Not Set</td>";

                                echo "</tr>";

                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-info">

                        <div class="box-header">

                          <center><h4><strong>Current Year Payments Processed</strong></h4></center>
                          
                          <i class="fa fa-"></i>

                          <div class="box-tools pull-right">

                            <a data-toggle="modal" data-target="#editCurrentYearPaymentsProcessed" class='btn-xs'><i class='fa fa-edit'></i> Edit</a>

                          </div>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table class="table table-bordered">

                            <thead>
                              
                              <th>Year</th>
                              <th>January</th>
                              <th>February</th>
                              <th>March</th>
                              <th>April</th>
                              <th>May</th>
                              <th>June</th>
                              <th>July</th>
                              <th>August</th>
                              <th>September</th>
                              <th>October</th>
                              <th>November</th>
                              <th>December</th>

                            </thead>

                            <tbody>

                              <?php

                                $row = pg_fetch_assoc(pg_query("SELECT * FROM current_year_payments_processed WHERE hoa_id=$hoa_id AND home_id=$home_id AND year=$year"));

                                $m[1] = $row['m1_pmt_processed'];
                                $m[2] = $row['m2_pmt_processed'];
                                $m[3] = $row['m3_pmt_processed'];
                                $m[4] = $row['m4_pmt_processed'];
                                $m[5] = $row['m5_pmt_processed'];
                                $m[6] = $row['m6_pmt_processed'];
                                $m[7] = $row['m7_pmt_processed'];
                                $m[8] = $row['m8_pmt_processed'];
                                $m[9] = $row['m9_pmt_processed'];
                                $m[10] = $row['m10_pmt_processed'];
                                $m[11] = $row['m11_pmt_processed'];
                                $m[12] = $row['m12_pmt_processed'];

                                for ($i = 1; $i <= 12; $i++)
                                  $m1[$i] = $m[$i];

                                for ($i = 1; $i <= 12; $i++)
                                {
                                  if($m[$i] == 't')
                                    $m[$i] = "<center><i class='fa fa-check-square text-success'></i></center>";
                                  else
                                    $m[$i] = "<center><i class='fa fa-square-o text-orange'></i></center>";
                                }

                                echo "<tr><td>$year</td><td>$m[1]</td><td>$m[2]</td><td>$m[3]</td><td>$m[4]</td><td>$m[5]</td><td>$m[6]</td><td>$m[7]</td><td>$m[8]</td><td>$m[9]</td><td>$m[10]</td><td>$m[11]</td><td>$m[12]</td></tr>";

                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                      <div class="modal fade hmodal-success" id="editCurrentYearPaymentsProcessed" role="dialog"  aria-hidden="true">
                                        
                        <div class="modal-dialog">
                                            
                          <div class="modal-content">
                                                
                            <div class="color-line"></div>
                                
                            <div class="modal-header">
                                                        
                              <h4 class="modal-title"><strong>Current Year Payments Processed</strong></h4>

                            </div>

                            <form class="row" method="post" action="https://hoaboardtime.com/boardEditCurrentYearPaymentsProcessed.php">
                                                    
                              <div class="modal-body">
                                                        
                                <div class="container-fluid">
                                      
                                  <div class="row container-fluid">
                                        
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>January</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='January' name='month[]' id='month' <?php if($m1[1] == 't') echo "checked"; ?>></div>
                                    </div>
                                        
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>February</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='February' name='month[]' id='month' <?php if($m1[2] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>March</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='March' name='month[]' id='month' <?php if($m1[3] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>April</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='April' name='month[]' id='month' <?php if($m1[4] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>May</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='May' name='month[]' id='month' <?php if($m1[5] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>June</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='June' name='month[]' id='month' <?php if($m1[6] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>July</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='July' name='month[]' id='month' <?php if($m1[7] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>August</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='August' name='month[]' id='month' <?php if($m1[8] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>September</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='September' name='month[]' id='month' <?php if($m1[9] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>October</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='October' name='month[]' id='month' <?php if($m1[10] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>November</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='November' name='month[]' id='month' <?php if($m1[11] == 't') echo "checked"; ?> ></div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><label>December</label></div>
                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6'><input type='checkbox' value='December' name='month[]' id='month' <?php if($m1[12] == 't') echo "checked"; ?> ></div>

                                      <input type="hidden" name="home_id" id='home_id' value="<?php echo $home_id; ?>">
                                      <input type="hidden" name="hoa_id" id='hoa_id' value="<?php echo $hoa_id; ?>">
                                    </div>

                                  </div>

                                  <br>

                                  <div class="row text-center">
                                    <button type="submit" name='submit' id='submit' class="btn btn-success btn-xs"><i class='fa fa-check'></i> Save Changes</button>
                                    <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal"><i class='fa fa-close'></i> Cancel</button>
                                  </div>
                                                        
                                </div>

                              </div>

                            </form>

                          </div>
                          
                        </div>

                      </div>

                    </section>

                  </div>

                  <?php
          
                    if($payment_type == 'ACH')
                    {  

                      echo "<div class='row'>

                        <section class='col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12'>

                          <div class='box box-info'>

                            <div class='box-header'>

                              <center><h4><strong>Forte Transactions</strong></h4></center>

                            </div>

                            <div class='box-body table-responsive'>
                              
                              <table id='example1' class='table table-bordered'>

                                <thead>
                                  
                                  <th>Date</th>
                                  <th>Customer ID</th>
                                  <th>Document Number</th>
                                  <th>Status</th>
                                  <th>Amount</th>

                                </thead>

                                <tbody>";

                                  $ch = curl_init();
                                  $header = array();
                                  $header[] = 'Content-Type: application/json';
                                  
                                  if($community_id == 2)
                                  {
                                      
                                    $header[] = "X-Forte-Auth-Organization-Id:org_332536";
                                    $header[] = "Authorization:Basic ZjNkOGJhZmY1NWM2OTY4MTExNTQ2OTM3ZDU0YTU1ZGU6Zjc0NzdkNTExM2EwNzg4NTUwNmFmYzIzY2U2MmNhYWU=";
                                                                              
                                    curl_setopt($ch, CURLOPT_URL, "https://api.forte.net/v3/organizations/org_332536/locations/loc_190785/transactions?filter=customer_id+eq+'".$hoa_id."'");
                                                                              
                                  }

                                  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

                                  $result = curl_exec($ch);
                                  $obj = json_decode($result);

                                  foreach ($obj->results as $key) 
                                  {  

                                    if($key->customer_id == $hoa_id)
                                      echo "<tr><td>".date('m-d-Y', strtotime($key->received_date))."</td><td>".$key->customer_id."</td><td>".$key->authorization_code."</td><td>".$key->status."</td><td>$ ".$key->authorization_amount."</td></tr>";
                                    
                                  }

                                                                              
                                  curl_close($ch);
                                  
                                echo "</tbody>
                                
                              </table>

                            </div>

                          </div>

                        </section>

                      </div>";

                    }

                  ?>

                </div>

                <div class="tab-pane" id="tab_8">
                  
                  <div class="row">

                    <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-xs-12">

                      <div class="box box-info">

                        <div class="box-header">

                          <center><h4><strong>Statements Mailed</strong></h4></center>

                        </div>

                        <div class="box-body table-responsive">
                          
                          <table id='example3' class="table table-bordered">

                            <thead>
                              
                              <th>Date Sent</th>
                              <th>Total Due</th>
                              <th>Statement File</th>
                              <th>Statement Type</th>
                              <th>Notification Type</th>

                            </thead>

                            <tbody>

                              <?php 

                                $result = pg_query("SELECT * FROM community_statements_mailed WHERE home_id=$home_id AND hoa_id=$hoa_id");

                                while ($row = pg_fetch_assoc($result)) 
                                {
                                  $date_sent = $row['date_sent'];
                                  $total_due = $row['total_due'];
                                  $statement_file = $row['statement_file'];
                                  $statement_type = $row['statement_type'];
                                  $notification_type = $row['notification_type'];

                                  if($total_due != "")
                                    $total_due = "$ ".$total_due;

                                  if($date_sent != "")
                                    $date_sent = date("m-d-Y", strtotime($date_sent));

                                  $row1 = pg_fetch_assoc(pg_query("SELECT * FROM notification_mode WHERE notification_mode_id=$notification_type"));
                                  $notification_type = $row1['notification_mode_type'];

                                  echo "<tr><td>$date_sent</td><td>$total_due</td><td>$statement_file</td><td>$statement_type</td><td>$notification_type</td></tr>";
                                }
                              
                              ?>
                              
                            </tbody>
                            
                          </table>

                        </div>

                      </div>

                    </section>

                  </div>

                </div>

              </div>

            </div>

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
    <script src="plugins/select2/select2.full.min.js"></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50 });
      });
    </script>

  </body>

</html>