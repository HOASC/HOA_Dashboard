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
      $user_id = $_SESSION['hoa_user_id'];
      $mode = $_SESSION['hoa_mode'];

      include 'includes/dbconn.php';

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

      <?php if($mode == 1) include "boardHeader.php"; else if($mode == 2) include "residentHeader.php"; ?>
      
      <?php if($mode == 1) include 'boardNavigationMenu.php'; else if($mode == 2) include "residentNavigationMenu.php"; ?>

      <?php include 'zenDeskScript.php'; ?>

      <div class="content-wrapper">

        <?php

          $year = date("Y");
          $month = date("m");
          $end_date = date("t");

          $result = pg_query("SELECT * FROM community_invoices WHERE community_id=$community_id AND reserve_expense='t'");

          $today = date('Y-m-d');

        ?>
        
        <section class="content-header">

          <h1><strong>Disclosures</strong></h1>

          <ol class="breadcrumb">
            
            <?php if($mode == 1) echo "<li><i class='fa fa-institution'></i> Community</li>"; ?>

            <li>Disclosures</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="box">

                <div class="box-body table-responsive">
                  
                  <table id="example1" class="table table-bordered table-striped">
                    
                    <thead>
                      
                      <tr>
                        
                        <th>DisclosureType</th>
                        <th>Actual Date</th>
                        <th>Delivery Type</th>
                        <th>Notes</th>
                        <th>Document</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php

                        $result = pg_query("SELECT CD1.* FROM COMMUNITY_DISCLOSURES CD1 INNER JOIN (SELECT type_id,MAX(UPDATED_ON) AS MAXDATETIME FROM COMMUNITY_DISCLOSURES GROUP BY type_id) GROUPEDCD ON CD1.type_id = GROUPEDCD.type_id AND CD1.UPDATED_ON = GROUPEDCD.MAXDATETIME ORDER BY TYPE_ID");

                        while($row = pg_fetch_assoc($result))
                        {

                          $actual_date = $row['actual_date'];
                          $disclosure_type = $row['type_id'];
                          $delivery_type = $row['delivery_type'];
                          $notes = $row['notes'];
                          $document_id = $row['document_id'];

                          if($delivery_type == 1)
                            $delivery_type = 'Email';

                          $row1 = pg_fetch_assoc(pg_query("SELECT * FROM disclosure_type WHERE id=$disclosure_type"));

                          $civilcode_section = $row1['civilcode_section'];
                          $description = $row1['desc'];
                          $legal_url = $row1['legal_url'];
                          $disclosure_type = $row1['name'];

                          $document = "";

                          if($document_id != "")
                          {
                            $document = "<a href='documentPreview.php?path=$document_id&desc=$description&cid=$community_id' target='_blank'><i class='fa fa-file'></i></a>";
                          }

                          if($civilcode_section != '')
                          {

                            $disclosure_type .= " (";
                            $disclosure_type .= $civilcode_section;
                            $disclosure_type .= ")";

                          }

                          if($legal_url != '')
                            $disclosure_type = "<a target='_blank' href='$legal_url'>$disclosure_type</a>";

                          if($actual_date != '')
                            $actual_date = date('m-d-Y', strtotime($actual_date));

                          echo "<tr><td>$disclosure_type</td><td>$actual_date</td><td>$delivery_type</td><td>$notes</td><td>$document</td></tr>";

                        }

                      ?>
                    
                    </tbody>

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
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/demo.js"></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50, "order": [[0,"desc"]] });
      });
    </script>

  </body>

</html>