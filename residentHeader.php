<?php

	ini_set("session.save_path","/var/www/html/session/");

  	session_start();

  	$community_id = $_SESSION['hoa_community_id'];

  	include 'includes/dbconn.php';

  	$query = "SELECT * FROM board_committee_details WHERE user_id=".$_SESSION['hoa_user_id'];
    $result = pg_query($query);
    $board = pg_num_rows($result);

?>

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