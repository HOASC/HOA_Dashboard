<!DOCTYPE html>
<html>
  <head>
    
    <?php

      	ini_set("session.save_path","/var/www/html/session/");

        session_start();

      	include 'includes/dbconn.php';

      	if(@!$_SESSION['hoa_username'])
      		header("Location: logout.php");

      	$user_id=$_SESSION['hoa_user_id'];

      	$result = pg_query("SELECT * FROM backend_team WHERE user_id=$user_id");
    		$num_row = pg_num_rows($result);

    		if($num_row == 0)
    			header("Location: https://hoaboardtime.com/logout.php");

    ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <title>Back End</title>
    
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
          
          		<span class="logo-mini">HA</span>
          
          		<span class="logo-lg">HOA Alchemy</span>

        	</a>
        
        	<nav class="navbar navbar-static-top">
          
          		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            		
            		<span class="sr-only">Toggle navigation</span>

          		</a>

	          	<div class="navbar-custom-menu">
	            
	            	<ul class="nav navbar-nav">

		          		<li class="dropdown user user-menu">
	              
		            		<a href="https://hoaboardtime.com/logout.php"><i class='fa fa-sign-out'></i> Log Out</a>

		          		</li>

	            	</ul>

	          	</div>

        	</nav>

      	</header>
      
      	<aside class="main-sidebar">
        
        	<section class="sidebar">
          
          		<ul class="sidebar-menu">

            		<li class="active treeview">
              
              			<a href="https://hoaboardtime.com/backendBalance.php">
                
                			<i class="fa fa-dashboard"></i> <span>Customer Balance</span>

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

          <h1><strong>Customer Balance</strong></h1>

        </section>

        <section class="content">

          <div class="row">

            <center>
              
              <form method='POST' action='https://hoaboardtime.com/backendBalance.php'>
                  
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
                        
                        $result = pg_query("SELECT cc.hoa_id, cc.home_id FROM current_charges cc GROUP BY cc.home_id, cc.hoa_id HAVING (sum(cc.amount)-(SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND home_id=cc.home_id AND hoa_id=cc.hoa_id))>".$value." ORDER BY cc.home_id"); 

                      }
                      else if ($having == 2) 
                      {
                        
                        $result = pg_query("SELECT cc.hoa_id, cc.home_id FROM current_charges cc GROUP BY cc.home_id, cc.hoa_id HAVING (sum(cc.amount)-(SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND home_id=cc.home_id AND hoa_id=cc.hoa_id))=".$value." ORDER BY cc.home_id"); 

                      }
                      else
                      {
                        
                        $result = pg_query("SELECT cc.hoa_id, cc.home_id FROM current_charges cc GROUP BY cc.home_id, cc.hoa_id HAVING (sum(cc.amount)-(SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND home_id=cc.home_id AND hoa_id=cc.hoa_id))<".$value." ORDER BY cc.home_id"); 

                      }

                      echo "<br><center>Total Number of records fetched : ".pg_num_rows($result)."</center><br>";

                      echo "<table class='table table-striped table-bordered' id='example1' width=100%>";

                      echo "<thead><th></th><th>Community</th><th>Name</th><th>House</th><th>Email</th><th>Phone</th><th>Total Charges</th><th>Total Payments</th><th>Total Balance</th><th></th></thead><tbody>";

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

                        $row1 = pg_fetch_assoc(pg_query("SELECT community_code FROM community_info WHERE community_id=$community_id"));
                        $community = $row1['community_code'];

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

                        echo "<tr><td>";

                        $result1 = pg_query("SELECT * FROM reminders WHERE home_id=$home_id AND hoa_id=$hoa_id");
                        $row1 = pg_num_rows($result1);

                        if($row1)
                        {
                          
                          $row1 = pg_fetch_assoc($result1);

                          $o_date = $row1['open_date'];
                          $d_date = $row1['due_date'];

                          if(date('Y-m-d')<=$d_date)
                            echo "<a title='Reminder Already Set'><i class='fa fa-bell text-green'></i></a>";
                          else
                            echo "<a href='setReminders2.php?id=$hoa_id' title='Set Reminder'><i class='fa fa-bell'></i></a>";

                        }
                        else
                          echo "<a href='setReminders2.php?id=$hoa_id' title='Set Reminder'><i class='fa fa-bell'></i></a>";

                        echo "</td><td>$community</td><td>$name<br>($hoa_id)</td><td>$address<br>($home_id)</td><td>$email</td><td>$phone</td><td>$ $charges</td><td>$ $payments</td><td>$ $balance</td><td><form method='POST' action='print_invoice.php'><a target='_blank' href='backendPrintCustomerInvoice.php?home_id=$home_id&hoa_id=$hoa_id&name=$name&community_id=$community_id'><i class='fa fa-print'></i></a></td></tr>";


                      }

                      echo"</tbody><tfoot><th></th><th>Community</th><th>Name</th><th>House</th><th>Email</th><th>Phone</th><th>Total Charges</th><th>Total Payments</th><th>Total Balance</th><th></th></tfoot><table>";

                    }
                    else
                    {

                      $result = pg_query("SELECT cc.hoa_id, cc.home_id FROM current_charges cc GROUP BY cc.home_id, cc.hoa_id HAVING (sum(cc.amount)-(SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND home_id=cc.home_id AND hoa_id=cc.hoa_id))>0.00 ORDER BY cc.home_id");

                      echo "<br><center>Total Number of records fetched : ".pg_num_rows($result)."</center><br>";

                      echo "<table class='table table-striped table-bordered' id='example1' width=100%>";

                      echo "<thead><th></th><th>Community</th><th>Name</th><th>House</th><th>Email</th><th>Phone</th><th>Total Charges</th><th>Total Payments</th><th>Total Balance</th><th></th></thead><tbody>";

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

                        $row1 = pg_fetch_assoc(pg_query("SELECT community_code FROM community_info WHERE community_id=$community_id"));
                        $community = $row1['community_code'];

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

                        echo "<tr><td>";

                        $result1 = pg_query("SELECT * FROM reminders WHERE home_id=$home_id AND hoa_id=$hoa_id");
                        $row1 = pg_num_rows($result1);

                        if($row1)
                        {
                          
                          $row1 = pg_fetch_assoc($result1);

                          $o_date = $row1['open_date'];
                          $d_date = $row1['due_date'];

                          if(date('Y-m-d')<=$d_date)
                            echo "<a title='Reminder Already Set'><i class='fa fa-bell text-green'></i></a>";
                          else
                            echo "<a href='setReminders2.php?id=$hoa_id' title='Set Reminder'><i class='fa fa-bell'></i></a>";

                        }
                        else
                          echo "<a href='setReminders2.php?id=$hoa_id' title='Set Reminder'><i class='fa fa-bell'></i></a>";

                        echo "</td><td>$community</td><td>$name<br>($hoa_id)</td><td>$address<br>($home_id)</td><td>$email</td><td>$phone</td><td>$ $charges</td><td>$ $payments</td><td>$ $balance</td><td><form method='POST' action='print_invoice.php'><a target='_blank' href='backendPrintCustomerInvoice.php?home_id=$home_id&hoa_id=$hoa_id&name=$name&community_id=$community_id'><i class='fa fa-print'></i></a></td></tr>";


                      }

                      echo"</tbody><tfoot><th></th><th>Community</th><th>Name</th><th>House</th><th>Email</th><th>Phone</th><th>Total Charges</th><th>Total Payments</th><th>Total Balance</th><th></th></tfoot><table>";

                    }
                  
                  ?>

                </div>

              </div>

            </section>

          </div>

        </section>

      </div>

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
        $("#example1").DataTable({ "pageLength": 50, "order": [[1, "asc"]] });
      });
    </script>

  </body>

</html>