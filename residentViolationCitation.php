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
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

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

          $result = pg_query("SELECT * FROM inspection_notices WHERE community_id=$community_id AND hoa_id=$hoa_id AND home_id=$home_id");

        ?>
        
        <section class="content-header">

          <h1><strong>Inspection Notices</strong><small> - <?php echo $_SESSION['hoa_community_name']; ?></small></h1>

        </section>

        <section class="content">
          
          <div class="row">

          	<section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Inspection Date</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Document</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        while($row = pg_fetch_assoc($result))
                        {

                          $id = $row['id'];
                          $home_id = $row['home_id'];
                          $hoa_id = $row['hoa_id'];
                          $item = $row['item'];
                          $description = $row['description'];
                          $document = $row['document_id'];
                          $inspection_date = $row['inspection_date'];
                          $location = $row['location_id'];
                          $violation_category = $row['inspection_category_id'];
                          $violation_sub_category = $row['inspection_sub_category_id'];
                          $notice_type = $row['inspection_notice_type_id'];
                          $date_of_upload = $row['date_of_upload'];
                          $status = $row['inspection_status_id'];
                          $compliance_date = $row['compliance_date'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM inspection_category WHERE id=$violation_category"));

                          $violation_category = $row1['name'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM inspection_status WHERE id=$status"));

                          $status = $row1['inspection_status'];

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM locations_in_community WHERE location_id=$location"));

                          $location = $row1['location'];

                          if($date_of_upload != "")
                            $date_of_upload = date('m-d-Y', strtotime($date_of_upload));

                          if($compliance_date != "")
                            $compliance_date = date('m-d-Y', strtotime($compliance_date));

                          if($inspection_date != "")
                            $inspection_date = date('m-d-Y', strtotime($inspection_date));

                          $date = date('m-d-Y');

                          echo "

                          <div class='modal fade hmodal-success' id='sendInspectionReply_$id' role='dialog'  aria-hidden='true'>
                                
                            <div class='modal-dialog'>
                                                
                              <div class='modal-content'>
                                                    
                                <div class='color-line'></div>
                                    
                                <div class='modal-header'>
                                                            
                                  <h4 class='modal-title'><strong>Inspection Notice - $location</strong></h4>

                                </div>

                                <div class='modal-body'>

                                  <form method='post' action='https://hoaboardtime.com/residentSendInspectionNotes.php'>
                                                            
                                    <div class='row container-fluid'>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Date : </label> $date
                                        <input type='hidden' name='id' id='id' value='".$id."' />
                                        <input type='hidden' name='home' id='home' value='".$_SESSION['hoa_address']."' />
                                        <input type='hidden' name='owner' id='owner' value='".$_SESSION['hoa_username']."' />
                                        <input type='hidden' name='date' id='date' value='$date' />

                                        <br>

                                      </div>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Inspection Notice : </label> $id
                                        <input type='hidden' name='inspection_notice' id='inspection_notice' value='$id' />

                                        <br>

                                      </div>

                                    </div>

                                    <div class='row container-fluid'>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Compliance Date : </label> $compliance_date
                                        <input type='hidden' name='compliance_date' id='compliance_date' value='".$compliance_date."' />

                                        <br>

                                      </div>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Viewed From : </label> $location
                                        <input type='hidden' name='viewed_from' id='viewed_from' value='$location' />

                                        <br>

                                      </div>

                                    </div>

                                    <div class='row container-fluid'>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Item : </label> $item
                                        <input type='hidden' name='item' id='item' value='".$item."' />

                                        <br>

                                      </div>

                                      <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>

                                        <label>Observation : </label> $description
                                        <input type='hidden' name='observation' id='observation' value='".$description."' />

                                        <br>

                                      </div>

                                    </div>

                                    <div class='row container-fluid'>

                                      <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                        <label>Followup Questions / Comments : </label>
                                        <textarea class='form-control' name='notice_summary' placeholder='Max 500 characters' id='notice_summary' required maxlength='500'></textarea>

                                        <br>

                                      </div>

                                    </div>

                                    <div class='row text-center'>

                                      <br>
                                        
                                      <button type='submit' name='submit' id='submit' value='3' class='btn btn-success btn-xs'>Send</button>

                                      <button type='button' class='btn btn-warning btn-xs' data-dismiss='modal'>Cancel</button>

                                    </div>

                                  </form>

                                </div>

                              </div>
                              
                            </div>

                          </div>

                          ";

                          if($status != 'Closed By Vendor' && $status != 'Request Closed By Member' && $status != 'Closed' && $status != 'Closed by CIS' && $status != 'Resolved')
                            echo "<tr><td><a data-toggle='modal' data-target='#sendInspectionReply_$id'>".$inspection_date."</a></td><td><a data-toggle='modal' data-target='#sendInspectionReply_$id'>".$status."</a></td><td><a data-toggle='modal' data-target='#sendInspectionReply_$id'>".$location."</a></td><td><a data-toggle='modal' data-target='#sendInspectionReply_$id'>".$description."</a></td><td><a data-toggle='modal' data-target='#sendInspectionReply_$id'>".$violation_category."</a></td><td><a data-toggle='modal' data-target='#sendInspectionReply_$id'>".$document."</a></td></tr>";
                          else
                            echo "<tr><td>".$inspection_date."</td><td>".$status."</td><td>".$location."</td><td>".$description."</td><td>".$violation_category."</td><td>".$document."</td></tr>";
                          
                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Inspection Date</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Document</th>

                      </tr>

                    </tfoot>

                  </table>

                </div>

              </div>

            </section>

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