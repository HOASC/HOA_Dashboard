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
    <link rel="stylesheet" href="plugins/select2/select2.min.css">

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

          $ryear = $_GET['year'];

        ?>
        
        <section class="content-header">

          <h1><strong>Community Assets</strong><small> - <?php echo $ryear; ?></small></h1>

          <ol class="breadcrumb">
            
            <li><a href='reservesDashboard.php'><i class="fa fa-support"></i> Reserves Dashboard</a></li>
            <li>Community Assets</li>
          
          </ol>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-lg-12 col-xl-12 col-md-12 col-xs-12 col-sm-12">

              <div class="row container-fluid" style="background-color: white;">

                <div class="box-body ">
                  
                  <div class="box box-solid">

                    <div class="box-body">

                      <div class="box-group" id="accordion">

                        <?php

                          $res = pg_query("SELECT asset_category_id, count(*), sum(ideal_balance) AS ib, sum(current_balance) AS cb FROM community_assets WHERE community_id=$community_id AND year=$ryear GROUP BY asset_category_id");

                          while($row = pg_fetch_assoc($res))
                          {

                            $category_id = $row['asset_category_id'];
                            $count = $row['count'];
                            $ib = $row['ib'];
                            $cb = $row['cb'];

                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM asset_category WHERE id=$category_id"));
                            $asset_category = $row1['name'];

                            echo "

                              <div class='panel'>

                                <div class='box-header'>

                                  <h4>
                                    
                                    <a data-toggle='collapse' data-parent='#accordion' href='#collapse_$category_id'>

                                      <div class='row container-fluid'>

                                        <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'><strong>$asset_category</strong> - $count</div>
                                        <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'><strong>Ideal Balance</strong> - $$ib</div>
                                        <div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'><strong>Current Balance</strong> - $$cb</div>

                                      </div>

                                    </a>

                                  </h4>

                                </div>

                                <div id='collapse_$category_id' class='panel-collapse collapse'>

                                  <div class='box-body table-responsive'>";

                                    $res1 = pg_query("SELECT * FROM community_assets WHERE community_id=$community_id AND asset_category_id=$category_id");

                                    echo "<table class='table table-striped table-bordered'>

                                      <thead>

                                        <th>Sub Category</th>
                                        <th>Usable Life</th>
                                        <th>Remaining Usable Life</th>
                                        <th>Minimum Unit Cost</th>
                                        <th>Average Unit Cost</th>
                                        <th>Maximum Unit Cost</th>
                                        <th>Asset Placement Date</th>
                                        <th>Ideal Balance</th>
                                        <th>Current Balance</th>
                                        <th>Monthly Contribution</th>
                                        <th>Quantity</th>
                                        <th>Repair Type</th>

                                      </thead>

                                      <tbody>";

                                        while ($row = pg_fetch_assoc($res1)) 
                                        {

                                          $asset_sub_category = $row['asset_subcategory_id'];
                                          $asset_component = $row['asset_component_id'];
                                          $ul = $row['ul'];
                                          $rul = $row['rul'];
                                          $avg_unit_cost = $row['avg_unit_cost'];
                                          $asset_placement_date = $row['asset_placement_date'];
                                          $ideal_balance = $row['ideal_balance'];
                                          $current_balance = $row['current_balance'];
                                          $monthly_contributions = $row['monthly_contributions'];
                                          $quantity = $row['quantity'];
                                          $community_repair_type = $row['community_repair_type_id'];
                                          $community_uom = $row['community_uom_id'];
                                          $min_unit_cost = $row1['min_unit_cost'];
                                          $max_unit_cost = $row1['max_unit_cost'];

                                          if($min_unit_cost != "")
                                            $min_unit_cost = "$ ".$min_unit_cost;

                                          if($max_unit_cost != "")
                                            $max_unit_cost = "$ ".$max_unit_cost;

                                          if($asset_sub_category != "")
                                          {

                                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM asset_subcategory WHERE id=$asset_sub_category"));
                                            $asset_sub_category = $row1['name'];
                                            
                                          }

                                          if($community_repair_type != "")
                                          {

                                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM community_repair_type WHERE id=$community_repair_type"));
                                            $community_repair_type = $row1['name'];
                                            
                                          }

                                          if($community_uom != "")
                                          {

                                            $row1 = pg_fetch_assoc(pg_query("SELECT * FROM community_uom WHERE id=$community_uom"));
                                            $community_uom = $row1['name'];
                                            
                                          }

                                          if($asset_placement_date != "")
                                            $asset_placement_date = date('m-d-Y', strtotime($asset_placement_date));

                                          echo "<tr><td>$asset_sub_category</td><td>$ul</td><td>$rul</td><td>$min_unit_cost</td><td>$ $avg_unit_cost</td><td>$max_unit_cost</td><td>$asset_placement_date</td><td>$ $ideal_balance</td><td>$ $current_balance</td><td>$ $monthly_contributions</td><td>$quantity $community_uom</td><td>$community_repair_type</td></tr>";

                                        }

                                      echo "</tbody>

                                    </table>

                                  </div>

                                </div>

                              </div>

                            ";

                          }

                        ?>

                      </div>

                    </div>

                  </div>

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
    <script src="plugins/select2/select2.full.min.js"></script>

    <script>
      $(function () {
        $("#example1").DataTable({ "pageLength": 50 });
      });
    </script>

  </body>

</html>