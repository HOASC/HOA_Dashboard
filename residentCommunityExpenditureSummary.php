<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <?php

      	include 'includes/dbconn.php';

      	if(@!$_SESSION['hoa_username'])
      		header("Location: logout.php");

      	$community_id = $_SESSION['hoa_community_id'];

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
  	              
  		            		<a href='https://hoaboardtime.com/boardDashboard.php'>Board Dashboard</a>

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

					                  	<a href="https://hoaboardtime.com/logout.php" class="btn btn-warning">Log Out</a>

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

        ?>
        
        <section class="content-header">

          <h1><strong>YTD Expenditures</strong></h1>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">

                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>Date</th>
                        <th>Type</th>
                        <th>Payee</th>
                        <th>Category</th>
                        <th>Total</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php
                        
                        if($community_id == 2)
                        {  

                          $ch = curl_init('https://quickbooks.api.intuit.com/v3/company/123145844183384/query?minorversion=8');
                          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                          curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Intuit-qbov3-postman-collection1','Accept:application/json','Content-Type:application/text','Authorization:OAuth oauth_consumer_key="qyprdRAm244oPXhP3miXslnVdpDfWF",oauth_token="qyprdwVPs6UkPK3Xrpe9XMGvlGdJa6EUg0s65QPt2Cgsr14v",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1506452058",oauth_nonce="cEzWCgQy0l5",oauth_version="1.0",oauth_signature="KXtBMOAC0UjBuczxlE7tPlDyPN0%3D"'));
                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                          curl_setopt($ch, CURLOPT_POSTFIELDS, "select * from purchase startposition 1 maxresults 1000");
        
                          $result = curl_exec($ch);
                          $jsonDecode  = json_decode($result,TRUE);
                          $queryResponse = $jsonDecode['QueryResponse'];
                        
                          foreach ($queryResponse['Purchase'] as $purchase) {
                          
                            echo '<tr><td>';
                            print_r(date("m-d-Y",strtotime($purchase['TxnDate'])));
                            echo '</td><td>Expenditure</td><td>';
                            echo $purchase['EntityRef']['name'];
                            echo '</td><td>';
                            
                            $count = 0;
                            
                            foreach ($purchase['Line'] as $line) {
                              
                              $count = $count + 1;
                              $category = $line['AccountBasedExpenseLineDetail']['AccountRef']['name'];

                            }
                            
                            if ( $count == 1) {
                              
                              echo $category;
                              $count = 0;

                            }
                            else {
                              
                              echo "-Split-";
                              $count = 0;

                            }

                            echo '</td><td>';
                            print_r("$".number_format($purchase['TotalAmt'],2));
                            echo '</td></tr>';

                          }
                          
                        }

                      ?>
                    
                    </tbody>

                    <tfoot>

                      <tr>

                        <th>Date</th>
                        <th>Type</th>
                        <th>Payee</th>
                        <th>Category</th>
                        <th>Total</th>

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
        $("#example1").DataTable({ "pageLength": 50, "order": [[ 0, "desc" ]] });
      });
    </script>

  </body>

</html>