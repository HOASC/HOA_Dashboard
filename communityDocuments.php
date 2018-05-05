<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <?php

      if(@!$_SESSION['hoa_username'])
        header("Location: https://hoaboardtime.com/logout.php");

      $community_id = $_SESSION['hoa_community_id'];
      $user_id=$_SESSION['hoa_user_id'];

      include 'includes/dbconn.php';

      if($_SESSION['hoa_mode'] == 2)
        header("Location: https://hoaboardtime.com/residentDashboard.php");

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

      <?php include "boardHeader.php"; ?>
      
      <?php include 'boardNavigationMenu.php'; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

        	$year = date("Y");
        	$month = date("m");
        	$end_date = date("t");

          $result = pg_query("SELECT * FROM current_payments WHERE community_id=$community_id AND payment_status_id=1 AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'");

        ?>
        
        <section class="content-header">

          <h1><strong>Community Documents</strong><small> - <?php echo $_SESSION['hoa_community_name']; ?></small></h1>

          <ol class="breadcrumb">
            
            <li><a href='boardDashboard.php'><i class="fa fa-dashboard"></i> Board Dashboard</a></li>
            <li>Community Documents</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row container-fluid">

            <ul class="timeline">

              <?php

                $result = pg_query("SELECT year_of_upload FROM document_management WHERE community_id=$community_id GROUP BY year_of_upload ORDER BY year_of_upload DESC");

                $i = 0;

                while($row = pg_fetch_assoc($result))
                {

                  $i++;

                  $id = 'example';
                  $id .= $i;
                
                  $year_of_upload = $row['year_of_upload'];
                  
                  echo "<li class='time-label'>
                    
                      <span class='bg-red'>".$year_of_upload."</span>

                    </li> 

                  <li>
            
                    <div class='timeline-item'>

                      <div class='timeline-body container-fluid'>

                        <div class='row container-fluid table-responsive'>

                          <table id='".$id."' class='table table-bordered table-striped'>

                            <thead>

                              <th>Date of Upload</th>
                              <th>Description</th>

                            </thead>

                            <tbody>";
                      
                              $result1 = pg_query("SELECT * FROM document_management WHERE community_id=$community_id AND year_of_upload=$year_of_upload");

                              while($row1 = pg_fetch_assoc($result1))
                              {

                                $desc = $row1['description'];
                                $category = $row1['document_category_id'];
                                $document_url = $row1['url'];
                                $date_of_upload = $row1['uploaded_date'];

                                if($date_of_upload != '')
                                  $date_of_upload = date('m-d-Y', strtotime($date_of_upload));

                                if($category != '')
                                {

                                  $row11 = pg_fetch_assoc(pg_query("SELECT * FROM document_category WHERE document_category_id=$category"));

                                  $category = $row11['document_category_name'];

                                }
                                else
                                  $category = 'Others';
                                if ( $community_id == 2 ){
                                echo "<tr><td><a href='https://hoaboardtime.com/getDocumentPreviewTest.php?path=$document_url&desc=$desc&cid=$community_id&t=-2' target='_blank'>$date_of_upload</a></td><td><a href='https://hoaboardtime.com/getDocumentPreviewTest.php?path=$document_url&desc=$desc&cid=$community_id&t=-2' target='_blank'>$desc</a></td></tr>";
                                }
                                else {
                                  echo "<tr><td><a href='https://hoaboardtime.com/getDocumentPreviewTest.php?path=$document_url&desc=$desc&cid=$community_id' target='_blank'>$date_of_upload</a></td><td><a href='https://hoaboardtime.com/getDocumentPreviewTest.php?path=$document_url&desc=$desc&cid=$community_id' target='_blank'>$desc</a></td></tr>";

                                }

                              }

                            echo "</tbody>

                            <tfoot>

                              <th>Date of Upload</th>
                              <th>Description</th>

                            </tfoot>

                          </table>

                        </div>

                      </div>
                      
                    </div>
                    
                  </li>";

                }

              ?>

            </ul>

          </div>

        </section>

      </div>

      <?php include "footer.php"; ?>

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