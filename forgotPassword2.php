<!DOCTYPE html>
<html>
  <head>
    
    <?php

      ini_set("session.save_path","/var/www/html/session/");

      session_start();

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
        
        <a href="https://hoaboardtime.com/" class="logo">
          
<<<<<<< HEAD
          <span class="logo-mini"><?php echo $m_community; ?></span>
=======
          <span class="logo-mini"></span>
>>>>>>> 950b373b8ada46ad28d804ae2ca3e98a171b29d3
          
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
                  
                  <form class="container-fluid" method="POST" action="https://hoaboardtime.com/login.php">
                    
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

            <form class="row" method="post" action="https://hoaboardtime.com/forgotPassword.php"><!-- action="https://hoaboardtime.com/forgotPassword.php" -->
                                            
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
              
              <a href="https://hoaboardtime.com/">
                
                <i class="fa fa-home"></i> <span>Home</span>

              </a>

            </li>

            <li class="treeview">
              
              <a href="https://hoaboardtime.com#getInvolved">
                
                <i class="fa fa-comment"></i> <span>Get Involved</span>

              </a>

            </li>
             
            <li class="treeview">

              <a href='https://hoaboardtime.com#pay'>

                <i class='fa fa-dollar'></i> <span>Make a payment</span>
              
              </a>

            </li>
            
            <li class="treeview">
              
              <a href='https://hoaboardtime.com#pool'>

                <i class='fa fa-pencil-square-o'></i> <span>Pool Signin</span>

              </a>

            </li>
            
            <li class="treeview">

              <a href='https://hoaboardtime.com#resale'>

                <i class='fa fa-sitemap'></i> <span>Resale Docs</span>

              </a>

            </li>
            
            <li class="treeview">

              <a href='https://hoaboardtime.com#contact'>
              
                <i class='fa fa-phone'></i> <span>Contact</span>

              </a>

            </li>

          </ul>

        </section>

      </aside>

      <div class="content-wrapper">
        
        <section class="content-header">

          <h1><strong>Reset Password</strong></h1>

        </section>

        <section class="content">
          
          <div class='row container-fluid'>

            <div class="row container-fluid" style="background-color: white;">

              <br>

                <?php

                  $reset_email = $_REQUEST['forgot_password_email'];

                  $result = pg_query("SELECT * FROM usr WHERE email='$reset_email'");

                  if(pg_num_rows($result) == 0)
                  {

                    echo "<center><h3>Some error occured.<br><br>Please try again.</h3><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/'},1000);</script></center>";

                  }
                  else
                  {

                    $row = pg_fetch_assoc($result);

                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $passcode = $row['forgot_password_code'];
                    $otp_entered = $_REQUEST['otp_entered'];

                    if($otp_entered == $passcode)
                    {
                      echo "Hello ".$first_name." ".$last_name.",<br><br>Please reset your password to login into your account.<br><br><br>";

                      echo "
                      <form action='https://hoaboardtime.com/resetPassword.php' method='POST'>

                        <div class='col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6'>
                          
                          <div class='row'>

                            <input class='form-control' type='password' name='new_password' id='new_password' required placeholder='Enter New Password'>

                          </div>

                          <div class='row'>

                            <br>

                            <input class='form-control' type='password' name='confirm_password' id='confirm_password' required placeholder='Re-Type New Password'>

                            <input type='hidden' name='forgot_password_email' id='forgot_password_email' value='".$reset_email."'>
                            <input type='hidden' name='otp_entered' id='otp_entered' value='".$otp_entered."'>

                          </div>

                        </div>

                        <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3 text-left'>

                          <br><br><br>

                          <button type='submit' class='btn btn-xs btn-info'>Reset Password</button>

                        </div>

                      </form>";
                    }
                    else
                      echo "<br><br><center><h3>Entered OTP is invalid. Please verify the OTP and try again.</h3></center><br><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/forgotPassword.php?forgot_password_email=".$reset_email."'},1000);</script>";

                  }

                ?>

              <br><br><br><br><br>

            </div>

          </div>

        </section>

      </div>

      <footer class="main-footer">

        <div class="pull-right hidden-xs"></div>
        
        <strong>Copyright &copy; <?php echo date('Y'); ?>.</strong> All rights
        reserved.

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