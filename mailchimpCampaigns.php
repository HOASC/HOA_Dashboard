<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <?php

      include 'includes/dbconn.php';

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
    
    <title>Stoneridge Square Association</title>
    
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
          
          <span class="logo-mini"></span>
          
          <span class="logo-lg">Stoneridge Square Association</span>

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
              
              <a target='_blank' href='http://stoneridgesquare.us12.list-manage.com/subscribe?u=12a11bf64aa26b44b5b667427&id=09692e90bd'>
               
                <i class='fa fa-envelope'></i> <span>Mailing List</span>

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

          <h1><strong>Newsletter Communication</strong><small> - Stoneridge Square Association</small></h1>

        </section>

        <section class="content">
          
          <?php

            $ch = curl_init('https://us12.api.mailchimp.com/3.0/reports/');
          
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: apikey af5b50b9f714f9c2cb81b91281b84218-us12'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            
            $result = curl_exec($ch);
            $json_decode = json_decode($result,TRUE);
            
            foreach ($json_decode['reports'] as $key ) {
              
              $opens = $key['opens'];
              $clicks = $key['clicks'];
              $openrate = sprintf("%.2f",floatval($opens['open_rate']*100.0));
              $clickrate = sprintf("%.2f",floatval($clicks['click_rate']*100.0) );
              $date = date("U",strtotime($key['send_time']));
              $date = date('Y-m-d H:i:s', $date);
              $url = "#";
              
              echo '<a class="list-group-item">
                <h4 class="list-group-item-heading" style="color: #3FC2D9;font-size: 25px;">'.$key['campaign_title'].'</h4>
                <br>
                <p class="list-group-item-text" style="font-size: 19px;"><b>Sent On  </b>'.date('m-d-y', strtotime($date)).'&nbsp;&nbsp;&nbsp;<b>Emails Sent</b> '.$key['emails_sent'].'&nbsp;&nbsp;&nbsp;<b>Open Rate </b>'.$openrate.'%&nbsp;&nbsp;&nbsp;<b>Click Rate</b> '.$clickrate.'%</p>
              </a>';

            }

          ?>

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