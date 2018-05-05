<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-102881886-2"></script>
    
    <script>
      
      var dimensionValue = '<?php echo $_SESSION['hoa_hoa_id'] ?>';
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'UA-102881886-2', {
          'custom_map': {'dimension1': 'hoaid'}
        
        // Sends an event that passes 'age' as a parameter.
        gtag('event', 'hoaid_dimension', {'hoaid': dimensionValue});
      });
      
    </script>

    <?php

        if(@!$_SESSION['hoa_username'])
          header("Location: logout.php");

        $community_id = $_SESSION['hoa_community_id'];
        $hoa_id = $_SESSION['hoa_hoa_id'];
        $home_id = $_SESSION['hoa_home_id'];
        $user_id = $_SESSION['hoa_user_id'];

        include 'includes/dbconn.php';

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

        <?php include 'residentHeader.php'; ?>

        <?php include 'residentNavigationMenu.php'; ?>

        <?php  include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">
        
        <section class="content-header">

          <h1><strong>Resident Directory</strong><small> - <?php echo $_SESSION['hoa_community_name']; ?></small></h1>

        </section>

        <section class="content">

          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">
                
                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <!--th>Name</th-->
                        <th>Property Address</th>
                        <th>Mailing Address</th>
                        <th>Email</th>
                        <th>Phone</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php 

                        $result = pg_query("SELECT * FROM member_info WHERE community_id=".$community_id);
                                        
                        while ($row = pg_fetch_assoc($result)) 
                        {
                          
                          $name = $row['combined_owner'];
                          $property = $row['house_number'];
                          $property .= " ";
                          $property .= $row['address'];

                          $result2 = pg_query("SELECT * FROM account_info WHERE member_id=".$row['member_id']);
                          $row2 = pg_fetch_assoc($result2);

                          if($row2['cell_visibility'] == 'NO')
                            $cell = 'NOT PERMITTED';
                          else
                            $cell = $row2['cell'];

                          if($row2['email_visibility'] == 'NO')
                            $email = 'NOT PERMITTED';
                          else
                            $email = $row2['email1'];

                          if($row2['mailing_address_visibility'] == 'NO')
                            $mailing_address = 'NOT PERMITTED';
                          else
                          {
                            $hoa_id = $row2['bank_pmt'];

                            $result2 = pg_query("SELECT * FROM homeid WHERE home_id=(SELECT home_id FROM hoaid WHERE hoa_id=$hoa_id)");
                            $row2 = pg_fetch_assoc($result2);

                            $home_id = $row2['home_id'];

                            if($row2['living_status'] == 't')
                              $mailing_address = $row2['address1'];
                            else
                            {
                              $result2 = pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id");
                              $row2 = pg_fetch_assoc($result2);

                              $mailing_address = $row2['address1'];
                            }
                          }

                          $row100 = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE address1='$property'"));
                          $living_status = $row100['living_status'];

                          if($living_status == 't')
                            echo "<tr><td>".$property."</td><td>".$mailing_address."</td><td>".$email."</td><td>".$cell."</td></tr>";
                          else
                            echo "<tr><td class='text-danger'>".$property."</td><td class='text-danger'>".$mailing_address."</td><td class='text-danger'>".$email."</td><td class='text-danger'>".$cell."</td></tr>";

                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <!--th>Name</th-->
                        <th>Property Address</th>
                        <th>Mailing Address</th>
                        <th>Email</th>
                        <th>Phone</th>

                      </tr>

                    </tfoot>

                  </table>

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