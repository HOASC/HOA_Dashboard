<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <?php

      include 'includes/dbconn.php';
      include 'includes/api_keys.php';

      $community_id = 2;

      if(@$_SESSION['hoa_username'])
        header("Location: logout.php");

      $year = date("Y");
      $month = date("m");
      $end_date = date("t");

    ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <title><?php echo $m_cnote; ?></title>
    
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
        
        <a href="index.php" class="logo">
          
          <span class="logo-mini"><?php echo $m_community; ?></span>
          
          <span class="logo-lg"><?php echo $m_cnote; ?></span>

        </a>
        
        <nav class="navbar navbar-static-top">
          
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            
            <ul class="nav navbar-nav">
              
              <li class="dropdown user user-menu">
                
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  
                  <i class="fa fa-sign-in"></i> Log In

                </a>

                <ul class="dropdown-menu">
                  
                  <form class="container-fluid" method="POST" action="login.php">
                    
                    <li class="user-body">

                      <br>

                      <label for='login_email'>Email</label>
                      <input class='form-control' type='email' name='login_email' id='login_email' placeholder='example@email.com' required>

                      <br>

                      <label for='login_password'>Password</label>
                      <input class='form-control' type='password' name='login_password' id='login_password' placeholder='********' required>

                      <br>

                    </li>
                    
                    <li class="user-footer">

                      <div class="pull-left">

                        <a data-toggle="modal" data-target="#forgotPassword">Forgot Password?</a>

                        <br><br>

                      </div>

                      <div class="pull-right">

                        <button type="submit" class="btn btn-success btn-flat">Log In</button>

                        <br><br>

                      </div>

                    </li>

                  </form>

                </ul>

              </li>

            </ul>

          </div>

        </nav>

      </header>

      <div class="modal fade hmodal-success" id="forgotPassword" role="dialog"  aria-hidden="true">
                                
        <div class="modal-dialog">
                                    
          <div class="modal-content">
                                        
            <div class="color-line"></div>
                        
            <div class="modal-header">
                                                
              <h4 class="modal-title"><strong>Forgot Password?</strong></h4>

            </div>

            <form class="row" method="post" action="forgotPassword.php"><!-- action="forgotPassword.php" -->
                                            
              <div class="modal-body">
                                                
                <div class="row container-fluid">
                                
                  <div class='col-xl-offset-3 col-lg-offset-3 col-md-offset-2 col-sm-offset-1 col-xs-offset-1 col-xl-6 col-lg-6 col-md-8 col-sm-10 col-xs-10'>

                    <label>Enter your email</label>
                    <input type='email' class="form-control" placeholder='example@email.com' name='forgot_password_email' id='forgot_password_email' size='50' required>

                  </div>

                </div>

                <br>

                <center>

                  <button type="submit" name='submit' id='submit' class="btn btn-success btn-xs">Reset Password</button>
                  <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal">Cancel</button>

                </center>

              </div>

            </form>

          </div>
                  
        </div>

      </div>
      
      <aside class="main-sidebar">
        
        <section class="sidebar">
          
          <ul class="sidebar-menu">
            
            <li class="treeview">
              
              <a href="index.php">
                
                <i class="fa fa-home"></i> <span>Home</span>

              </a>

            </li>

            <li class="treeview">
              
              <a href="index.php#getInvolved">
                
                <i class="fa fa-comment"></i> <span>Get Involved</span>

              </a>

            </li>
             
            <li class="treeview">

              <a href='index.php#pay'>

                <i class='fa fa-dollar'></i> <span>Make a payment</span>
              
              </a>

            </li>
            
            <li class="treeview">
              
              <a href='index.php#pool'>

                <i class='fa fa-pencil-square-o'></i> <span>Pool Signin</span>

              </a>

            </li>
            
            <li class="treeview">

              <a href='index.php#resale'>

                <i class='fa fa-sitemap'></i> <span>Resale Docs</span>

              </a>

            </li>
            
            <li class="treeview">

              <a href='index.php#contact'>
              
                <i class='fa fa-phone'></i> <span>Contact</span>

              </a>

            </li>

          </ul>

        </section>

      </aside>

      <div class="content-wrapper">
        
        <section class="content-header">

          <h1><strong>Community Assets</strong><small> - Stoneridge Square Association</small></h1>

        </section>

        <section class="content">
          
          <div class="row container-fluid table-responsive" style="background-color: white;">

            <br><br>

            <table id='example1' class="table table-bordered">

              <thead>

                <th>Category</th>
                <th>Sub Category</th>
                <th>Component</th>
                <th>UL</th>
                <th>RUL</th>
                <th>Minimum Unit Cost</th>
                <th>Average Unit Cost</th>
                <th>Maximum Unit Cost</th>
                <th>Asset Placement Date</th>
                <th>Ideal Balance</th>
                <th>Current Balance</th>
                <th>Monthly Contribution</th>
                <th>Quantity</th>
                <th>Repair Type</th>
                <th>Units Of Measure</th>
                
              </thead>

              <tbody>

                <?php

                  $result = pg_query("SELECT * FROM community_assets WHERE community_id=$community_id");

                  while ($row = pg_fetch_assoc($result)) 
                  {

                    $asset_category = $row['asset_category_id'];
                    $asset_sub_category = $row['asset_subcategory_id'];
                    $asset_component = $row['asset_component_id'];
                    $ul = $row['ul'];
                    $rul = $row['rul'];
                    $avg_unit_cost = $row['avg_unit_cost'];
                    $asset_placement_date = $row['asset_placement_date'];
                    $ideal_balance = $row['ideal_balance'];
                    $current_balance = $row['current_balance'];
                    $monthly_contributions = $row['monthly_contributions'];
                    $quantity = $row['quantity'];
                    $community_repair_type = $row['community_repair_type_id'];
                    $community_uom = $row['community_uom_id'];
                    $min_unit_cost = $row1['min_unit_cost'];
                    $max_unit_cost = $row1['max_unit_cost'];

                    if($min_unit_cost != "")
                      $min_unit_cost = "$ ".$min_unit_cost;

                    if($max_unit_cost != "")
                      $max_unit_cost = "$ ".$max_unit_cost;

                    if($asset_category != "")
                    {

                      $row1 = pg_fetch_assoc(pg_query("SELECT * FROM asset_category WHERE id=$asset_category"));
                      $asset_category = $row1['name'];
                      
                    }

                    if($asset_sub_category != "")
                    {

                      $row1 = pg_fetch_assoc(pg_query("SELECT * FROM asset_subcategory WHERE id=$asset_sub_category"));
                      $asset_sub_category = $row1['name'];
                      
                    }

                    if($community_repair_type != "")
                    {

                      $row1 = pg_fetch_assoc(pg_query("SELECT * FROM community_repair_type WHERE id=$community_repair_type"));
                      $community_repair_type = $row1['name'];
                      
                    }

                    if($community_uom != "")
                    {

                      $row1 = pg_fetch_assoc(pg_query("SELECT * FROM community_uom WHERE id=$community_uom"));
                      $community_uom = $row1['name'];
                      
                    }

                    if($asset_placement_date != "")
                      $asset_placement_date = date('m-d-Y', strtotime($asset_placement_date));

                    echo "<tr><td>$asset_category</td><td>$asset_sub_category</td><td>$asset_component</td><td>$ul</td><td>$rul</td><td>$min_unit_cost</td><td>$ $avg_unit_cost</td><td>$max_unit_cost</td><td>$asset_placement_date</td><td>$ $ideal_balance</td><td>$ $current_balance</td><td>$ $monthly_contributions</td><td>$quantity</td><td>$community_repair_type</td><td>$community_uom</td></tr>";

                  }

                ?>

              </tbody>
              
            </table>

            <br><br>

          </div>

        </section>

      </div>

      <footer class="main-footer">

        <div class="pull-right hidden-xs"></div>
        
        <strong>Copyright &copy; <?php echo date('Y'); ?> <a target='_blank' href="https://www.stoneridgesquare.org">Stoneridge Square Association</a>.</strong> All rights
        reserved.

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