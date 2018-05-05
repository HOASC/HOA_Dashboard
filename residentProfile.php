<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <?php

      	if(@!$_SESSION['hoa_username'])
      		header("Location: logout.php");

      	$community_id = $_SESSION['hoa_community_id'];

        include 'includes/dbconn.php';

        $query = "SELECT * FROM board_committee_details WHERE user_id=".$_SESSION['hoa_user_id'];
        $result = pg_query($query);
        $board = pg_num_rows($result);

        $hoa_id = $_SESSION['hoa_hoa_id'];
        $home_id = $_SESSION['hoa_home_id'];

    ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <title><?php echo $_SESSION['hoa_community_name']; ?></title>
    
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
   
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

      	<header class="main-header">
        
        	<a class="logo">
          
          		<span class="logo-mini"><?php echo $_SESSION['hoa_community_code']; ?></span>
          
          		<span class="logo-lg"><?php echo $_SESSION['hoa_community_name']; ?></span>

        	</a>
        
        	<nav class="navbar navbar-static-top">
          
          		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            		
            		<span class="sr-only">Toggle navigation</span>

          		</a>

	          	<div class="navbar-custom-menu">
	            
	            	<ul class="nav navbar-nav">

		          		<?php

                    if($board)
                    echo "<li class='dropdown user user-menu'>
  	              
  		            		<a href='boardDashboard.php'>Board Dashboard</a>

  		          		</li>";

                  ?>

		          		<li class="dropdown user user-menu">

		            		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		              
		              			<i class="fa fa-user"></i> <span class="hidden-xs"><?php echo $_SESSION['hoa_username']; ?></span>

		            		</a>

			            	<ul class="dropdown-menu">
			              
			              		<li class="user-header">
			                
			                		<i class="fa fa-user fa-5x"></i>

			                		<p>
			                  
					                  	<?php echo $_SESSION['hoa_username']; ?>

					                  	<br>

					                  	<small><?php echo $_SESSION['hoa_address']; ?></small>

					                  	<a href="logout.php" class="btn btn-warning">Log Out</a>

					                	<br>

					                </p>

			              		</li>

			            	</ul>

		          		</li>

	            	</ul>

	          	</div>

        	</nav>

      	</header>
      
      	<aside class="main-sidebar">
        
          <section class="sidebar">
          
              <ul class="sidebar-menu">
            
                <li class="header text-center"> Quick Links </li>

                <li class="treeview">
              
                    <a href="https://hoaboardtime.com/residentDashboard.php">
                
                      <i class="fa fa-dashboard"></i> <span><?php if($board) echo "Resident "; ?>Dashboard</span>

                    </a>

                </li>
            
                <li class="treeview">
              
                    <a href="https://hoaboardtime.com/residentDocumentManagement.php">

                      <i class="glyphicon glyphicon-hdd"></i> <span>Document Management</span>

                    </a>

                </li>
             
                <li class="treeview">

                    <a href='https://hoaboardtime.com/residentViewMeetingMinutes.php'>

                      <i class='fa fa-folder'></i> <span>Meeting Minutes</span>
              
                    </a>

                </li>
            
                <li class="treeview">
              
                    <a href='https://hoaboardtime.com/residentQuickPay.php'>

                      <i class='fa fa-dollar'></i> <span>Quick Pay</span>

                    </a>

                </li>

                <li class="treeview">

                    <a href='https://hoaboardtime.com/residentRecurringPay.php'>

                      <i class='fa fa-repeat'></i> <span>Recurring Pay</span>
              
                    </a>

                </li>

              </ul>

          </section>

        </aside>

      <div class="content-wrapper">

        <?php

        	$year = date("Y");
        	$month = date("m");
        	$end_date = date("t");

          $row = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$hoa_id"));

          $email = $row['email'];
          $cell_no = $row['cell_no'];
          $firstname = $row['firstname'];
          $lastname = $row['lastname'];

          $cell_no = base64_decode($cell_no);

        ?>

        <section class="content-header">

          <h1><strong>My Profile</strong></h1>

        </section>

        <section class="content">
          
          <div class="row">

            <div class="row container-fluid" style="background-color: white;">

              <br><br>

              <form class="container-fluid" method="POST" action="residentUpdateProfile.php">
              
                <div class="row container-fluid">

                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xl-12">

                    <strong>Name</strong>

                    <br>

                    <input type="text" class="form-control" name="firstname" id="firstname" required readonly value="<?php echo $firstname." ".$lastname; ?>">

                  </div>

                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xl-12">

                    <strong>Email</strong>

                    <br>

                    <input type="email" class="form-control" name="email" id="email" required <?php if($email != "") echo "readonly"; ?> value="<?php echo $email; ?>">

                  </div>

                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xl-12">

                    <strong>Cell Number</strong>

                    <br>

                    <input type="number" class="form-control" name="cell_no" id="cell_no" required value="<?php echo $cell_no; ?>">

                  </div>

                </div>

                <br><br>

                <div class="row container-fluid text-center">

                  <button class="btn btn-info btn-xs" type="submit">Update</button>

                </div>

              </form>

              <br><br>

            </div>

            <div class="clearfix"></div>

          </div>

        </section>

        <section class="content-header">

          <h1><strong>Change Password</strong></h1>

        </section>

        <section class="content">
          
          <div class="row">

            <div class="row container-fluid" style="background-color: white;">

              <br><br>

              <form class="row container-fluid" method="post" action="https://hoaboardtime.com/residentChangePassword.php">

                <div class="row container-fluid">

                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">

                    <br>

                    <strong>Current Password</strong>

                    <br>

                    <input type='password' class='form-control' name='old_password' id='old_password' size='20' placeholder="Old Password" required>

                  </div>

                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">

                    <br>

                    <strong>New Password</strong>

                    <br>

                    <input type='password' class='form-control' name='new_password' id='new_password' size='20' placeholder="New Password" required>

                  </div>

                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">

                    <br>

                    <strong>Confirm Password</strong>

                    <br>

                    <input type='password' class='form-control' name='confirm_password' id='confirm_password' size='20' placeholder="Confirm Password" required>

                  </div>

                </div>

                <br><br>

                <div class="row container-fluid text-center">

                  <button type="submit" name='submit' id='submit' class="btn btn-success btn-xs">Change Password</button>

                </div>
                
              </form>

              <br><br>

            </div>

            <div class="clearfix"></div>

          </div>

        </section>

        <?php

          $result = pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id");

          if(pg_num_rows($result))
          {

            $row = pg_fetch_assoc($result);

            $address1 = $row['address1'];
            $address2 = $row['address2'];

            $city = $row['city_id'];
            $state = $row['state_id'];
            $zip = $row['zip_id'];

            echo "<section class='content-header'>

                    <h1><strong>Home Mailing Address</strong></h1>

                  </section>

                  <section class='content'>
                    
                    <div class='row'>

                      <div class='row container-fluid' style='background-color: white;'>

                        <br><br>

                        <form class='row container-fluid' method='post' action='https://hoaboardtime.com/residentChangeMailingAddress.php'>

                          <div class='row container-fluid'>

                            <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'>

                              <br>

                              <strong>Address 1 *</strong>

                              <br>

                              <input type='text' class='form-control' name='address1' id='address1' value='".$address1."' placeholder='Address1' required>

                            </div>

                            <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'>

                              <br>

                              <strong>Address 2</strong>

                              <br>

                              <input type='text' class='form-control' name='address2' id='address2' value='".$address2."' placeholder='Address 2 (Optional)'>

                            </div>

                            <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'>

                              <br>

                              <strong>City *</strong>

                              <br>

                              <select name='city' id='city' class='form-control' required>";

                              $result = pg_query("SELECT * FROM city");

                              if($city == "")
                                echo "<option disabled selected>Select City</option>";

                              while ($row = pg_fetch_assoc($result)) 
                              {

                                $id = $row['city_id'];
                                $name = $row['city_name'];

                                echo "<option ";

                                if($city == $id)
                                  echo "selected";

                                echo " value='".$id."'>$name</option>";
                                
                              }

                              echo "</select>

                            </div>

                            <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'>

                              <br>

                              <strong>State *</strong>

                              <br>

                              <select name='state' id='state' class='form-control' required>";

                              $result = pg_query("SELECT * FROM state");

                              if($state == "")
                                echo "<option disabled selected>Select State</option>";

                              while ($row = pg_fetch_assoc($result)) 
                              {

                                $id = $row['state_id'];
                                $name = $row['state_name'];

                                echo "<option ";

                                if($state == $id)
                                  echo "selected";

                                echo " value='".$id."'>$name</option>";
                                
                              }

                              echo "</select>

                            </div>

                            <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'>

                              <br>

                              <strong>Country *</strong>

                              <br>

                              <select name='country' id='country' class='form-control' required>";

                              $result = pg_query("SELECT * FROM country");

                              if($country == "")
                                echo "<option disabled selected>Select Country</option>";

                              while ($row = pg_fetch_assoc($result)) 
                              {

                                $id = $row['country_id'];
                                $name = $row['country_name'];

                                echo "<option ";

                                if($country == $id)
                                  echo "selected";

                                echo " value='".$id."'>$name</option>";
                                
                              }

                              echo "</select>

                            </div>

                            <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'>

                              <br>

                              <strong>Zip *</strong>

                              <br>

                              <select name='zip' id='zip' class='form-control' required>";

                              $result = pg_query("SELECT * FROM zip");

                              if($zip == "")
                                echo "<option disabled selected>Select Zip</option>";

                              while ($row = pg_fetch_assoc($result)) 
                              {

                                $id = $row['zip_id'];
                                $name = $row['zip_code'];

                                echo "<option ";

                                if($zip == $id)
                                  echo "selected";

                                echo " value='".$id."'>$name</option>";
                                
                              }

                              echo "</select>

                            </div>

                          </div>

                          <br><br>

                          <div class='row container-fluid text-center'>

                            <button type='submit' name='submit' id='submit' class='btn btn-success btn-xs'>Update</button>

                          </div>
                          
                        </form>

                        <br><br>

                      </div>

                      <div class='clearfix'></div>

                    </div>

                  </section>";

          }

        ?>

        <section class="content-header">

          <h1><strong>Persons</strong></h1>

        </section>

        <section class="content">
          
          <div class="box box-info">

            <div class="box-header">

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

                      $role_type = $r['role_type_id'];
                      $relationship = $r['relationship_id'];
                      $person_email = $r['email'];
                      $person_cell_no = $r['cell_no'];
                      $person_fname = $r['fname'];
                      $person_lname = $r['lname'];
                      $person_home_id = $r['home_id'];
                      $person_id = $r['id'];

                      $person_cell_no = base64_decode($person_cell_no);

                      $r1 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$person_home_id"));
                      $address = $r1['address1'];

                      $r1 = pg_fetch_assoc(pg_query("SELECT * FROM role_type WHERE role_type_id=$role_type"));
                      $role_type = $r1['name'];

                      $r1 = pg_fetch_assoc(pg_query("SELECT * FROM relationship WHERE id=$relationship"));
                      $relationship = $r1['name'];

                      echo "

                      <div class='modal fade hmodal-success' id='editPerson_$person_id' role='dialog'  aria-hidden='true'>
                                
                        <div class='modal-dialog'>
                                                
                          <div class='modal-content'>
                                    
                            <div class='modal-header'>
                                                            
                              <h4 class='modal-title'><strong>Edit Person - $person_fname $person_lname</strong></h4>

                            </div>

                            <form class='row' method='post' action='https://hoaboardtime.com/residentEditPerson.php'>
                                                        
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

                                              if($role_type == $name)
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

                      ";

                      echo "
                          
                      <div class='modal fade hmodal-success' id='removePerson_$person_id' role='dialog'  aria-hidden='true'>
                                
                        <div class='modal-dialog'>
                                              
                          <div class='modal-content'>
                                                  
                            <div class='color-line'></div>
                                  
                              <div class='modal-header'>
                                                          
                                <h4 class='modal-title'>Remove Person - $person_fname $person_lname</h4>

                              </div>

                              <div class='modal-body'>
                                        
                                <form method='POST' action='https://hoaboardtime.com/residentRemovePerson.php'>

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

                    echo "<tr><td>$person_fname $person_lname</td><td>$address</td><td>$role_type</td><td>$relationship</td><td>$person_email</td><td>$person_cell_no</td><td><a data-toggle='modal' data-target='#editPerson_$person_id'>Edit</a></td><td><a data-toggle='modal' data-target='#removePerson_$person_id'>Remove</a></td></tr>";
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

                <form class="row" method="post" action="https://hoaboardtime.com/residentAddPerson.php">
                                            
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

      <footer class="main-footer">

        <div class="pull-right hidden-xs"></div>
        
        <strong>Copyright &copy; <?php echo date('Y'); ?> <a target='_blank' href="<?php echo $_SESSION['hoa_community_website_url']; ?>"><?php echo $_SESSION['hoa_community_name']; ?></a>.</strong> All rights reserved.

      </footer>

      <div class="control-sidebar-bg"></div>

    </div>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="plugins/knob/jquery.knob.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/pages/dashboard.js"></script>
    <script src="dist/js/demo.js"></script>

  </body>

</html>
