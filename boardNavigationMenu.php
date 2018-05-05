<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

  include 'includes/globalvar.php';

?>

<aside class="main-sidebar">
        
  <section class="sidebar">
          
    <ul class="sidebar-menu">
            
      <?php 

        echo "<li class='header text-center'>$communtiy_logo</li>"; 

      ?>
            
      <li class="header text-center"> Dashboards </li>

      <li class="treeview">
              
        <a href='boardDashboard.php'>
                
          <i class="fa fa-dashboard"></i> <span>Board</span>

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
              
        <a>
                
          <i class="fa fa-envelope"></i> <span>Communications</span>

        </a>

      </li>

      <li class="treeview">
              
        <a href="vendorDashboard.php">

          <i class='fa fa-wrench'></i> <span>Vendors</span>

        </a>

      </li>

      <li class="header text-center"> Other Links </li>

      <li class="treeview">
              
        <a href="#">

          <i class="fa fa-users"></i> <span>Board</span>

          <span class="pull-right-container">
                        
            <i class="fa fa-angle-left pull-right"></i>

          </span>

        </a>

        <ul class="treeview-menu">

          <li>

            <a href="boardCharges.php">

              <span>Charges</span>

            </a>

          </li>
                
          <li>

            <a href='setReminder.php'>

              <span>Create Reminder</span>

            </a>

          </li>

          <li>

            <a href='boardProcessPayment.php'>

              <span>Process Payments</span>
                  
            </a>

          </li>

          <li>

            <a href="viewReminders.php">

              <span>View Reminders</span>

            </a>

          </li>

        </ul>

      </li>

      <li class="treeview">
              
        <a href="#">

          <i class="fa fa-institution"></i> <span>Community</span>

          <span class="pull-right-container">
                        
            <i class="fa fa-angle-left pull-right"></i>

          </span>

        </a>

        <ul class="treeview-menu">
                
          <li>

            <a href="communityDisclosures.php">

              <span>Disclosures</span>

            </a>

          </li>


          <li>

            <a href="communityMeetingMinutes.php">

              <span>Minutes</span>

            </a>

          </li>

          <li>

            <a href="communityMailingList.php">

              <span>Mailing List</span>

            </a>

          </li>

          <li>
                  
            <a href="previousMonthPayments.php">

              <span>Previous Months Payments</span>

            </a>

          </li>

          <li class="treeview">
                  
            <a ><!-- href="https://hoaboardtime.com/boardSurveyDetails.php" -->

              <span>Survey Details</span>

            </a>

          </li>

        </ul>

      </li>

      <li class="treeview">
              
        <a href="#">

          <i class="fa fa-street-view"></i> <span>Users</span>

          <span class="pull-right-container">
                        
            <i class="fa fa-angle-left pull-right"></i>

          </span>

        </a>

        <ul class="treeview-menu">
                
          <li>

            <a href="customerBalance.php">

              <span>Balance</span>

            </a>

          </li>

          <li>

            <a href="boardHOAHomeInfo.php">

              <span>HOA &amp; Home Info</span>

            </a>

          </li>

          <li>

            <a href="userDashboard.php">

              <span>User Dashbord</span>

            </a>

          </li>

        </ul>

      </li>

    </ul>

  </section>

</aside>