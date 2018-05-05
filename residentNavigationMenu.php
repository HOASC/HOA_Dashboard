
<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<aside class="main-sidebar">
        
  <section class="sidebar">
          
    <ul class="sidebar-menu">
            
      <?php 

        echo "<li class='header text-center'><img src=".$communtiy_logo."></li>"; 

      ?>
            
      <li class="header text-center"> Dashboards </li>

      <li class="treeview">
              
        <a href='residentDashboard.php'>
                
          <i class="fa fa-dashboard"></i> <span>Resident</span>

        </a>

      </li>

      <li class="treeview">
              
        <a href='financeDashboard.php'>
                
          <i class="fa fa-dollar"></i> <span>Finance</span>

        </a>

      </li>

      <li class="treeview">
              
        <a href='reservesDashboard.php'>
                
          <i class="fa fa-support"></i> <span>Reserves</span>

        </a>

      </li>

      <li class="treeview">
              
        <a href="vendorDashboard.php">

          <i class='fa fa-wrench'></i> <span>Vendors</span>

        </a>

      </li>

      <li class="header text-center"> Other Links </li>

      

      <li class="treeview">
              
        <a href="communityDisclosures.php">

          <i class='fa fa-file'></i> <span>Disclosures</span>

        </a>

      </li>

      <li class="treeview">
              
        <a href="communityMeetingMinutes.php">

          <i class='fa fa-file'></i> <span>Minutes</span>

        </a>

      </li>

      <li class="treeview">
              
        <a href='residentQuickPay.php'>

          <i class='fa fa-dollar'></i> <span>Quick Pay</span>

        </a>

      </li>

      <li class="treeview">

        <a href='residentRecurringPay.php'>

          <i class='fa fa-repeat'></i> <span>Recurring Pay</span>
              
        </a>

      </li>


    </ul>

  </section>

</aside>