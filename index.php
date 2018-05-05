<!DOCTYPE html>
<html style="font-family: Avenir;">
  <head>
    
    <?php

      ini_set("session.save_path","/var/www/html/session/");

      session_start();

      include 'includes/dbconn.php';
      include 'includes/globalvar.php';

      $community_id = 2;

      if(@$_SESSION['hoa_username'])
        header("Location: logout.php");

      $year = date("Y");
      $month = date("m");
      $end_date = date("t");

      $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND community_id=$community_id AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'"));

      $amount_recieved = $row['sum'];

      $row = pg_fetch_assoc(pg_query("SELECT count(hoa_id) FROM hoaid WHERE community_id=$community_id"));

      $total_customers = $row['count'];

      $row = pg_fetch_assoc(pg_query("SELECT amount FROM assessment_amounts WHERE community_id=$community_id"));

      $assessment_amount = $row['amount'];

      $total_amount = ( $total_customers * $assessment_amount );
      $amount_percentage = (( $amount_recieved / $total_amount ) * 100 );

      $paid_customers = pg_num_rows(pg_query("SELECT DISTINCT home_id FROM current_payments WHERE payment_status_id=1 AND community_id=$community_id AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'"));

      $paid_percentage = (( $paid_customers / $total_customers) * 100 );

      $result = pg_query("SELECT sum(amount), count(*) FROM current_charges WHERE assessment_rule_type_id=1 AND community_id=".$community_id." GROUP BY home_id");
      $row = pg_fetch_assoc($result);

      $charges = $row['sum'];
      $count = $row['count'];

      $del_acc = 0;
      $del = 3;

      $del_amount = $assessment_amount * $del;

      $result = pg_query("SELECT home_id, sum(amount) FROM current_charges WHERE assessment_rule_type_id=1 AND community_id=$community_id GROUP BY home_id ORDER BY home_id");

      while($row = pg_fetch_assoc($result))
      {

        $home_id = $row['home_id'];
        $assessment_charges = $row['sum'];

        $query2 = "SELECT hoa_id FROM hoaid WHERE home_id=".$home_id;
        $result2 = pg_query($query2);
        $row2 = pg_fetch_assoc($result2);
        $hoa_id = $row2['hoa_id'];

        $query2 = "SELECT sum(amount) FROM current_charges WHERE hoa_id=".$hoa_id;
        $result2 = pg_query($query2);
        $row2 = pg_fetch_assoc($result2);
        $charges = $row2['sum'];

        $query2 = "SELECT sum(amount) FROM current_payments WHERE payment_status_id=1 AND hoa_id=".$hoa_id;
        $result2 = pg_query($query2);
        $row2 = pg_fetch_assoc($result2);
        $payments = $row2['sum'];

        $balance = $charges - $payments;

        if($del_amount <= ($assessment_charges - $payments) && $balance >= $del_amount)
          $del_acc++;

      }

      $query = "SELECT count(*) FROM member_info WHERE community_id=".$community_id;

      $result = pg_query($query);
      $row = pg_fetch_assoc($result);
      $res_dir = $row['count'];

      date_default_timezone_set('America/Los_Angeles'); 
      
      $campaigns = 0;
      $ch = curl_init('https://us12.api.mailchimp.com/3.0/reports/');
      
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: apikey af5b50b9f714f9c2cb81b91281b84218-us12'));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      
      $result = curl_exec($ch);
      $json_decode = json_decode($result,TRUE);
      
      $open_rate_percentage = 0.0;

      foreach ($json_decode['reports'] as $key ) {
        $opens = $key['opens'];
        $openrate = sprintf("%.2f",floatval($opens['open_rate']*100.0));
              
        $campaigns++;

        $open_rate_percentage += $openrate;
      }

      $open_rate_percentage /= $campaigns;

      $query = "SELECT * FROM community_legal_table WHERE community_legal_id=51";

      $result = pg_query($query);
      $row = pg_fetch_assoc($result);
      $legal_address = $row['legal_info'];

      $ach = 0;
      $billpay = 0;
      $check = 0;

      $result = pg_query("SELECT * FROM current_payments WHERE community_id=2 AND payment_status_id=1 AND process_date>='$year-$month-1' AND process_date<='$year-$month-$end_date'");
      $total_payments = pg_num_rows($result);

      while($row = pg_fetch_assoc($result))
      {

        $payment_type_id = $row['payment_type_id'];

        if($payment_type_id == 1)
          $ach += 1;
        else if($payment_type_id == 2)
          $billpay += 1;
        else if($payment_type_id == 3)
          $check += 1;

      }

      $ach = ($ach / $total_payments) * 100;
      $billpay = ($billpay / $total_payments) * 100;
      $check = ($check / $total_payments) * 100;

      $result = pg_query("SELECT * FROM current_charges WHERE community_id=$community_id AND assessment_rule_type_id=9 AND assessment_date>='$year-01-01' AND assessment_date<='$year-12-31'");
      $board_write_off = pg_num_rows($result);

      $result = pg_query("SELECT * FROM current_charges WHERE community_id=$community_id AND assessment_rule_type_id=3 AND assessment_date>='$year-$month-01' AND assessment_date<='$year-$month-$end_date'");
      $late_fee = pg_num_rows($result);

      $minutes = pg_num_rows(pg_query("SELECT * FROM document_management WHERE url LIKE '/".$community_name."_HOA/Documents/Minutes/".$community_name."_Minutes_".$year."_%'"));

      $dm  = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0);
      
      $result = pg_query("SELECT * FROM document_management WHERE community_id=$community_id");

      while($row = pg_fetch_assoc($result))
      {
                      
        $document_category_id = $row['document_category_id'];

        if($document_category_id == "")
        {

          $dm[0]++;

        }
        else
        {

          $dm[$document_category_id]++;

        }

      }

      $violations = pg_num_rows(pg_query("SELECT * FROM inspection_notices WHERE community_id=$community_id AND inspection_date>='$year-01-01' AND inspection_date<='$year-12-31'"));

      $inspections_resolved = 0;

      $res = pg_query("SELECT * FROM inspection_notices WHERE community_id=$community_id");
      while ($r = pg_fetch_assoc($res)) 
      {
        $status = $r['inspection_status_id'];

        if($status != 2 && $status != 6 && $status != 9 && $status != 14 && $status != 13)
          $inspection_resolved++;
      }

      $result = pg_fetch_assoc(pg_query("SELECT sum(avg_unit_cost) FROM community_assets WHERE community_id=$community_id"));
      $assets = $result['sum'];

      $tenants = pg_num_rows(pg_query("SELECT * FROM home_mailing_address WHERE community_id=$community_id")); 

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

    <script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(e){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var e=this.createElement("script");n&&(this.domain=n),e.id="js-iframe-async",e.src="https://assets.zendesk.com/embeddable_framework/main.js",this.t=+new Date,this.zendeskHost="stoneridgesquare.zendesk.com",this.zEQueue=a,this.body.appendChild(e)},o.write('<body onload="document._l();">'),o.close()}();
/*]]>*/</script>

  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

      <header class="main-header">
        
        <a href="index.php" class="logo">
          
          <span class="logo-mini"></span>
          
          <span class="logo-lg">Stoneridge Square Association at Pleasantonnnn</span>

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
                  
                  <form class="container-fluid" method="POST" action="login.php">
                    
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

                        <a data-toggle="modal" data-target="#forgotPassword">Forgot Password?</a><!--  -->

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

            <form class="row" method="post" action="forgotPassword.php"><!-- action="forgotPassword.php" -->
                                            
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
            
            <li class="active treeview" class='page-scroll'>
              
              <a>
                
                <i class="fa fa-home"></i> <span>Home</span>

              </a>

            </li>

            <li class="treeview">
              
              <a href="#getInvolved" class='page-scroll'>
                
                <i class="fa fa-comment"></i> <span>Get Involvved</span>

              </a>

            </li>

            <li class="treeview">
              
              <a target='_blank' href='http://stoneridgesquare.us12.list-manage.com/subscribe?u=12a11bf64aa26b44b5b667427&id=09692e90bd'>
               
                <i class='fa fa-envelope'></i> <span>Mailing List</span>

              </a>

            </li>
             
            <li class="treeview">

              <a href='#pay' class='page-scroll'>

                <i class='fa fa-dollar'></i> <span>Make a payment</span>
              
              </a>

            </li>
            
            <li class="treeview" class='page-scroll'>
              
              <a href='#pool'>

                <i class='fa fa-pencil-square-o'></i> <span>Pool Signin</span>

              </a>

            </li>
            
            <li class="treeview">

              <a href='#resale' class='page-scroll'>

                <i class='fa fa-sitemap'></i> <span>Resale Docs</span>

              </a>

            </li>
            
            <li class="treeview">

              <a href='#contact' class='page-scroll'>
              
                <i class='fa fa-phone'></i> <span>Contact</span>

              </a>

            </li>

            <!--li class="treeview">

              <a href="#">
                
                <i class="fa fa-pie-chart"></i>
                
                <span>Charts</span>
                
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>

              </a>

              <ul class="treeview-menu">
                
                <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>

              </ul>

            </li>
            
            <li class="treeview">
              
              <a href="#">

                <i class="fa fa-folder"></i> <span>Examples</span>

                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>

              </a>

              <ul class="treeview-menu">
                
                <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
                <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
                <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
                <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
                <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
                <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
                <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
                <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
                <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>

              </ul>

            </li-->

          </ul>

        </section>

      </aside>

      <div class="content-wrapper">
        
        <section class="content-header">

          <h1><small>Welcome to Stoneridge Square Association</small></h1>

        </section>

        <section class="content">
          
          <div class="row">

            <section class="col-lg-12">
              
              <div class="row">
            
                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-aqua">
                    
                    <div class="inner">
                      
                      <h3><?php echo $res_dir; ?></h3>

                      <p>Resident Directory</p>

                    </div>

                    <div class="icon">
                      
                      <!--i style="font-size: 50px;" class="fa fa-users"></i-->
                      <img src='delinquent_accounts.png' width=50 height=50 alt='Tenants'>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-aqua">
                      
                    <div class="inner">
                        
                      <h3><?php echo round(((($total_customers-$tenants)/$total_customers)*100), 2); ?><i style='font-size: 12pt;'>%</i></h3>

                      <p>Owners</p>

                    </div>

                    <div class="icon">

                      <!--i style="font-size: 50px;" class="fa fa-users"></i-->
                      <img src='delinquent_accounts.png' width=50 height=50 alt='Tenants'>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-aqua">
                      
                    <div class="inner">
                        
                      <h3><?php echo round((($tenants/$total_customers)*100), 2); ?><i style='font-size: 12pt;'>%</i></h3>

                      <p>Tenants</p>

                    </div>

                    <div class="icon">

                      <!--i style="font-size: 50px;" class="fa fa-users"></i-->
                      <img src='delinquent_accounts.png' width=50 height=50 alt='Tenants'>

                    </div>

                  </div>

                </div>
                
                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-aqua">
                    
                    <div class="inner">
                      
                      <h3><?php $days90 = date('Y-m-d', strtotime("-90 days")); echo pg_num_rows(pg_query("SELECT * FROM hoaid WHERE community_id=$community_id AND valid_from>='".$days90."' AND valid_from<='".date('Y-m-d')."'")); ?></h3>

                      <p>Newly moved in</p>

                    </div>

                    <div class="icon">

                      <i style="font-size: 55px;" class="ion ion-person-add"></i>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-aqua">
                      
                    <div class="inner">
                        
                      <h3><?php $emails = pg_num_rows(pg_query("SELECT * FROM hoaid WHERE email!='' AND community_id=$community_id")); echo $emails; ?></h3>

                      <p>Homes with email</p>

                    </div>

                    <div class="icon">

                      <i style="font-size: 50px;" class="fa fa-envelope"></i>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-green">
                    
                    <div class="inner">
                      
                      <h3><?php echo $minutes; ?></h3>

                      <p>Meeting Minutes(<?php echo $year; ?>)</p>

                    </div>

                    <div class="icon">
                      
                      <i style="font-size: 55px;" class="fa fa-book"></i>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-green">
                    
                    <div class="inner">
                      
                      <h3><?php echo $board_write_off; ?></h3>

                      <p>Board Write Off(<?php echo $year; ?>)</p>

                    </div>

                    <div class="icon">

                      <i style="font-size: 55px;" class="fa fa-check-square-o"></i>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-green">
                    
                    <div class="inner">
                      
                      <h3><?php echo $late_fee; ?></h3>

                      <p>Late Fee(<?php echo date("F").", ".$year; ?>)</p>

                    </div>

                    <div class="icon">

                      <i style="font-size: 55px;" class="fa fa-clock-o"></i>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-red">
                    
                    <div class="inner">
                      
                      <h3><?php echo $violations-$inspection_resolved; ?></h3>

                      <p>Inspection Notices(Resolved)</p>

                    </div>

                    <div class="icon">

                      <i style="font-size: 55px;" class="fa fa-exclamation"></i>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-red">
                    
                    <div class="inner">
                      
                      <h3><?php echo $inspection_resolved; ?></h3>

                      <p>Inspection Notices(Pending)</p>

                    </div>

                    <div class="icon">

                      <i style="font-size: 55px;" class="fa fa-exclamation"></i>

                    </div>

                  </div>

                </div>
                
                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-red">
                    
                    <div class="inner">
                      
                      <h3><?php echo $del_acc; ?></h3>

                      <p>Delinquent Accounts</p>

                    </div>

                    <div class="icon">

                      <i style="font-size: 55px;" class="fa fa-legal"></i>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Click to view details'>
                  
                  <a href='mailchimpCampaigns.php'>

                    <div class="small-box bg-teal">
                    
                      <div class="inner">
                        
                        <h3><?php echo $campaigns; ?></h3>

                        <p>Newsletter Communication</p>

                      </div>

                      <div class="icon">

                        <i style="font-size: 50px;" class="fa fa-at"></i>

                      </div>

                    </div>

                  </a>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Login to view details'>
                  
                  <div class="small-box bg-teal">
                    
                    <div class="inner">
                      
                      <h3><?php echo round($open_rate_percentage,1)."<i style='font-size: 12pt;'>%</i>"; ?></h3>

                      <p>Newsletter Open Rate</p>

                    </div>

                    <div class="icon">

                      <i style="font-size: 50px;" class="fa fa-folder-open"></i>

                    </div>

                  </div>

                </div>

                <div class='col-xl-3 col-lg-3 col-md-4 col-xs-6' title='Login to view details'>
                        
                  <div class='small-box bg-teal'>
                          
                    <div class='inner'>
                            
                      <h3><?php $rows = pg_num_rows(pg_query("SELECT * FROM document_management WHERE community_id=$community_id")); echo $rows; ?></h3>
                      <p>Community Documents</p>

                    </div>

                    <div class='icon'>

                      <i style='font-size: 50px;' class='fa fa-file'></i>

                    </div>

                  </div>

                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-6" title='Click to view details'>
                  
                  <a href='communityAssets.php'>

                    <div class="small-box bg-teal">
                    
                      <div class="inner">
                        
                        <h3>$<?php $assets = round(($assets/1000), 0); echo $assets; ?><i style='font-size: 12pt;'>K</i></h3>

                        <p>Community Assets</p>

                      </div>

                      <div class="icon">

                        <i style="font-size: 50px;" class="fa fa-at"></i>

                      </div>

                    </div>

                  </a>

                </div>

              </div>

            </section>
            
            <section class="col-lg-8">

              <!-- Get Involved -->
              <div id="getInvolved" class="box box-info">
                
                <div class="box-header">
                  
                  <i class="fa fa-comments-o"></i>

                  <h3 class="box-title">Get Involved</h3>
                  
                  <div class="pull-right box-tools">
                    
                    <button type="button" class="btn btn-info btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>

                  </div>
                  
                </div>

                <div class="box-body container-fluid" style='text-align: justify;'>
                  
                  <br>

                  Here is the 2016 Strategy for our Home Owners Association, so we are well connected as a community and keep our HOA dues low. In order to accomplish these goals over the next several quarters we need volunteers and member support from the entire community.

                  <br>
              
                  <ul>
                    <li>CCR Updates – Declaration of <a href='http://stoneridgesquare.org/wp-content/uploads/2016/02/Stoneridge_square_CCR.pdf' target='_blank'>CCR</a> describes the rights and obligations of the membership of the association &amp; the association to the membership. Most communities update them after the builder has left and ours are 15 years old and we have hired Hughes Gill Cochrin to update ours. Changes to CCR will start in a few weeks now and expected to complete by end of the year or early 2017.</li>
                      
                    <li>Survey Monkey – Respond to our quarterly survey here.</li>
                      
                    <li>Updated Website – We are in the process of re-designing our website to achieve the following goals, allow login so we members can view past board meeting minutes, insurance info. Service Requests from now on will be visible to every member for common issues.</li>
                      
                    <li>Email Opt In List – Yes we saved $32.29 / year for Printed statements <a target='_blank' href='http://stoneridgesquare.us12.list-manage.com/subscribe?u=12a11bf64aa26b44b5b667427&id=09692e90bd'>Sign Up to Receive Annual Disclosures &amp; Meeting notices via email here</a> we have 142 emails for 143 residents so far.</li>
                      
                    <li>ACH – Its green, secure and 97 out of your neighbors pay. If you don’t share your Bank Routing # and Bank Account # with your friends and family why are your sharing with your HOA by paying via Bill Pay / Check?</li>
                      
                    <li>Spring and Summer Party & Fall Party – Join our summer party next Sunday by our swimming pool at 12:30 PM for Pizza, BBQ & Soft Drinks on Sept 18th.</li>

                    <li>News Letters – <a href='https://stoneridgesquare.org/disclosures/' target='_blank'>our past updates are posed here</a>.</li>

                    <li>Welcome New Neighbors – Create a list of Vital tel nos. and services for new neighbors and send them an email and invite them to signup to our Website and future Mobile App.</li>

                    <li>Metrics – Create a list of metrics on how the Board of Directors should operate and provide valuable information to prospective buyers.</li>
                  </ul>

                </div>

                <div class="box-footer clearfix">
                  
                  Here is the <a href='https://www.dropbox.com/s/f8b0ie27loi057t/2017_Annual_Budget.pdf?dl=0' target='_blank'>2017 Approved Budget</a>

                </div>

              </div>

              <!-- Legal Info -->
              <div id="pool" class="box box-info">
                
                <div class="box-header">
                  
                  <i class="fa fa-legal"></i>

                  <h3 class="box-title">Community Info</h3>
                  
                  <div class="pull-right box-tools">
                    
                    <button type="button" class="btn btn-info btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>

                  </div>
                  
                </div>

                <div class="box-body table-responsive container-fluid">
                  
                  <table class="table table-striped table-bordered">
                    
                    <thead>
                      
                      <th>Type</th>
                      <th>Information</th>

                    </thead>

                    <tbody>
                      
                    <?php

                      $result = pg_query("SELECT * FROM community_legal_table WHERE community_id=2");

                      while ($row = pg_fetch_assoc($result)) {
                        $type = $row['item_type'];
                        $legal_info = $row['legal_info'];

                        echo "<tr><td>$type</td><td>$legal_info</td></tr>";
                      }

                    ?>

                    </tbody>

                  </table>

                </div>

              </div>

            </section>
            
            <section class="col-lg-4">

              <!-- Payment Information -->
              <div class="box box-solid bg-teal-gradient">
                <div class="box-header">
                  <i class="fa fa-info"></i>

                  <h3 class="box-title">Payment Info - <?php echo date('F').", ".date("Y"); ?></h3>

                  <div class="box-tools pull-right">

                    <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>

                  </div>

                </div>
                
                <div class="box-body border-radius-none">
                  
                  <div class="info-box bg-aqua">
                    
                    <span class="info-box-icon" style="font-size: 18pt;"><?php echo round($amount_percentage, 1)."%"; ?></span>

                    <div class="info-box-content">
                      
                      <div class="progress">

                        <div class="progress-bar" style="width: <?php echo round($amount_percentage, 1); ?>%"></div>

                      </div>
                      
                      <span class="progress-description"><br>Payments Received</span>

                    </div>

                  </div>

                  <div class="info-box bg-aqua">
                    
                    <span class="info-box-icon" style="font-size: 18pt;"><?php echo (round($paid_percentage, 1))."%"; ?></span>

                    <div class="info-box-content">

                      <div class="progress">

                        <div class="progress-bar" style="width: <?php echo round($paid_percentage, 1); ?>%"></div>

                      </div>
                      
                      <span class="progress-description"><br>Number of customers paid</span>

                    </div>

                  </div>

                  <div class="info-box bg-aqua container-fluid">
                    
                    <br><div class="row"><div class='col-xl-4 col-lg-4 col-md-4 col-xs-4 col-sm-4'>ACH:<?php echo round($ach,1); ?>%</div> <div class='col-xl-4 col-lg-4 col-md-4 col-xs-4 col-sm-4'>BillPay:<?php echo round($billpay, 1); ?>%</div> <div class='col-xl-4 col-lg-4 col-md-4 col-xs-4 col-sm-4'>Check:<?php echo round($check, 1); ?>%</div></div>
                      
                    <center><span class="progress-description"><br>Payments Processed</span></center>

                  </div>

                </div>

              </div>

              <!-- Make payment -->
              <div id="pay" class="box box-info">
                
                <div class="box-header">
                  
                  <i class="fa fa-dollar"></i>

                  <h3 class="box-title">Make A Payment</h3>
                  
                  <div class="pull-right box-tools">
                    
                    <button type="button" class="btn btn-info btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>

                  </div>
                  
                </div>

                <div class="box-body container-fluid" style='text-align: justify;'>
                  
                  Click the Pay Now button below to enter your payment details with the Payment gateway directly to make a One time Payment.

                </div>

                <div class="box-footer clearfix">
                  
                  <center>

                    <a class='btn btn-info btn-sm' href='paymentPage1.php' target='_blank'>Pay Now</a>

                  </center>

                </div>

              </div>

              <!-- Pool Signin -->
              <div id="pool" class="box box-info">
                
                <div class="box-header">
                  
                  <i class="fa fa-pencil-square-o"></i>

                  <h3 class="box-title">Pool Signin</h3>
                  
                  <div class="pull-right box-tools">
                    
                    <button type="button" class="btn btn-info btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>

                  </div>
                  
                </div>

                <div class="box-body container-fluid" style='text-align: justify;'>
                  
                  Click the Sign Now button to sign the Stoneridge Square Association Swimming Pool rules acknowledgement and key fob registration form.

                </div>

                <div class="box-footer clearfix">
                  
                  <center>

                    <a class='btn btn-info btn-sm' href='https://secure.na1.echosign.com/public/esignWidget?wid=CBFCIBAA3AAABLblqZhAt2FV611bZ0ufERNZzJ2mVo33iDdvuobFchD-30n8lj2fsppHb6ZN9IAQXBVKKsmk*' target='_blank'>Sign Now</a>

                  </center>

                </div>

              </div>

              <!-- Resale -->
              <div id="resale" class="box box-info">
                
                <div class="box-header">
                  
                  <i class="fa fa-sitemap"></i>

                  <h3 class="box-title">Resale &amp; Docs</h3>
                  
                  <div class="pull-right box-tools">
                    
                    <button type="button" class="btn btn-info btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>

                  </div>
                  
                </div>

                <div class="box-body container-fluid" style='text-align: justify;'>
                  
                  <ul>
              
                    <li>
                      
                      <a href='https://www.dropbox.com/s/k4q6okz2nqe5szs/SRSQ_2017_Draft_Reserve_Study_2017.pdf?dl=0' target='_blank'>Draft Reserve Study</a>

                    </li>

                    <li>

                      <a href='https://www.dropbox.com/s/jpw5mn87lzjf9zr/SRSQ_CC%26Rs_First_Draft_12-22-16.pdf?dl=0' target='_blank'>Proposed CCR</a>

                    </li>

                    <li>
                    
                      <a href='https://www.dropbox.com/s/oippjvpuazmi67t/SRSQ_Bylaws_First_Draft_2-12-22-16.pdf?dl=0' target='_blank'>Upcoming Governing Docs</a>

                    </li>

                    <li>

                      <a href='https://www.dropbox.com/s/t2lm7rn0cot4acy/2017_Annual_Budget.pdf?dl=0' target='_blank'>2017 Annual Budget</a>

                    </li>

                    <li>
                    
                      <a href='https://www.dropbox.com/s/o7hrs8lanf4us0k/SRSQ_Voting%20%26%20Election%20Rules%2011-21-16.pdf?dl=0' target='_blank'>Current Election and Voting Rules</a>

                    </li>

                    <li>
                    
                      <a href='https://www.dropbox.com/s/zs9er1e7ai78lbu/Articles%20of%20Inc.pdf?dl=0' target='_blank'>Articles of Incorporation</a>

                    </li>

                    <li>
                    
                      <a href='https://www.dropbox.com/s/v5z15b028l26dlg/SRSQ_Enforcement%20Policy.pdf?dl=0' target='_blank'>Enforcement Policy</a>

                    </li>

                    <li>
                    
                      <a href='https://www.dropbox.com/s/0u0uyq762vnnvr1/SRSQ_Farmers_5300_Disclosure.pdf?dl=0' target='_blank'>Insurance</a>

                    </li>

                  </ul>

                </div>

              </div>

              <!-- Contact -->
              <div id='contact' class="nav-tabs-custom">
                
                <ul class="nav nav-tabs text-success pull-right">
                  
                  <li class="text-success pull-left header"><i class="text-success fa fa-phone"></i> Contact</li>

                </ul>

                <div class='container-fluid text-center'>
                  
                  <br><br><br>

                  <address>
                    <h4><?php echo $legal_address; ?></h4>
                    <br>
                    Website : <a href="https://www.stoneridgesquare.org/" target="_blank">www.stoneridgesquare.org</a>
                  </address>

                  <br><br><br>

                </div>

              </div>

            </section>

          </div>

        </section>

      </div>

      <footer class="main-footer">

        <div class="pull-right hidden-xs"></div>
        
        <center><strong>Copyright &copy; <?php echo date('Y'); ?> - </strong> All rights reserved</center>

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
