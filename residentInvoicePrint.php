<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

  $community_id = $_SESSION['hoa_community_id'];

  include 'includes/dbconn.php';
  
  $year = date("Y");
  $month = date("m");
  $end_date = date("t");

  $home_id = $_REQUEST['home_id'];#$_SESSION['hoa_home_id'];
  $hoa_id = $_REQUEST['hoa_id'];#$_SESSION['hoa_hoa_id'];

  $row = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE hoa_id=$hoa_id"));

  $cus_name = $row['firstname'];
  $cus_name .= " ";
  $cus_name .= $row['lastname'];

  $row = pg_fetch_assoc(pg_query("SELECT * FROM community_info WHERE community_id=$community_id"));
  
  $city = $row['payment_city'];
  $c_name = $row['legal_name'];
  $pobox = $row['remit_payment_address'];
  $state = $row['payment_addr_state'];
  $zip = $row['payment_addr_zip'];
  $tax_id = $row['taxid'];
  $c_email = $row['email'];
  $c_website = $row['community_website_url'];

  $row = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$state"));
  $state = $row['state_code'];

  $row = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$city"));
  $city = $row['city_name'];

  $row = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$zip"));
  $zip = $row['zip_code'];

  $row = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE home_id=$home_id"));

  $living_status = $row['living_status'];
  $property = $row['address1'];

  if($living_status == 't')
  {
    $cus_addr = $row['address1'];
    $cus_state = $row['state_id'];
    $cus_city = $row['city_id'];
    $cus_zip = $row['zip_id'];
  }
  else
  {
            
    $result = pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id");

    $row = pg_fetch_assoc($result);

    $cus_addr = $row['address1'];
    $cus_state = $row['state_id'];
    $cus_city = $row['city_id'];
    $cus_zip = $row['zip_id'];
          
  }

  $row = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$cus_state"));
  $cus_state = $row['state_code'];

  $row = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$cus_city"));
  $cus_city = $row['city_name'];

  $row = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$cus_zip"));
  $cus_zip = $row['zip_code'];

  $row = pg_fetch_assoc(pg_query("SELECT amount FROM assessment_amounts WHERE community_id=$community_id"));
  $assessment_amount = $row['amount'];

  $result = pg_query("SELECT * FROM current_charges WHERE home_id=".$home_id." ORDER BY assessment_date DESC LIMIT 1");

  $row = pg_fetch_assoc($result);
  $adate = $row['assessment_date'];

  

  if(date("m", strtotime($adate)) == date("m"))
    $month = date("m"); 
  else if(date("m", strtotime($adate)) < date("m")) 
    $month = date("m")-1; 
  else if(date("m", strtotime($adate)) > date("m")) 
    $month = date("m")+1; 

  $ddate = $month."-15-".date("y");

  $adate = date("m-d-y", strtotime($adate));

  $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id"));
  $total_charges = $row['sum'];

  $row = pg_fetch_assoc(pg_query("SELECT sum(amount) FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND payment_status_id=1"));
  $total_payments = $row['sum'];

  $total = $total_charges - $total_payments;


  require("fpdf/fpdf.php");


  header ( "Content-Type: application/vnd.x-pdf" );
  header ( "Content-disposition: attachment; filename=Testing_PDF-_".date('m-d-Y H:i:s').".pdf" );
  header ( "Content-Type: application/force-download" );
  header ( "Content-Transfer-Encoding: binary" );
  header ( "Pragma: no-cache" );
  header ( "Expires: 0" );


  $pdf = new FPDF();
  $pdf->AddPage();


  $pdf->SetFont("Arial", "", 12);


  $pdf->Cell(100, 6, "From :", 0, 1, L);


  $pdf->SetFont("Arial", "B", 12);
  $pdf->Cell(100, 6, $c_name, 0, 0, L);
  $pdf->SetFont("Arial", "", 12);
  $pdf->Cell(85, 6, "HOA Account No. : ".$hoa_id, 0, 1, R);


  $pdf->Cell(100, 6, $pobox, 0, 0, L);
  $pdf->Cell(85, 6, "Invoice Date : ".$adate, 0, 1, R);


  $pdf->Cell(100, 6, $city.", ".$state." ".$zip, 0, 0, L);
  $pdf->Cell(85, 6, "Due Date : ".$ddate, 0, 1, R);


  $pdf->Cell(189, 6, " ", 0, 1);
  $pdf->Cell(189, 6, " ", 0, 1);
  $pdf->Cell(189, 6, " ", 0, 1);


  $pdf->Cell(130, 6, "To :", 0, 0, L);
  $pdf->Cell(100, 6, "Property Address :", 0, 1, L);


  $pdf->SetFont("Arial", "B", 12);
  $pdf->Cell(130, 6, $cus_name, 0, 0, L);
  $pdf->Cell(100, 6, $property, 0, 1, L);
  $pdf->SetFont("Arial", "", 12);


  $pdf->Cell(100, 6, $cus_addr, 0, 1, L);
  $pdf->Cell(100, 6, $cus_city.", ".$cus_state." ".$cus_zip, 0, 1, L);


  $pdf->Cell(189, 6, " ", 0, 1);


    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(20, 6, "Month", 0, 0);
    $pdf->Cell(30, 6, "Document ID", 0, 0);
    $pdf->Cell(80, 6, "Description", 0, 0);
    $pdf->Cell(20, 6, "Charge", 0, 0);
    $pdf->Cell(20, 6, "Payment", 0, 0);
    $pdf->Cell(40, 6, "Total", 0, 1);
    $pdf->SetFont("Arial", "", 10);


    for($m = 1; $m <= 12; $m++)
    {

        $last_date = date("Y-m-t", strtotime("$year-$m-1"));
                          
        $charges_results = pg_query("SELECT * FROM current_charges WHERE home_id=$home_id AND hoa_id=$hoa_id AND assessment_date>='$year-$m-1' AND assessment_date<='$last_date' ORDER BY assessment_date");

        $payments_results = pg_query("SELECT * FROM current_payments WHERE home_id=$home_id AND hoa_id=$hoa_id AND process_date>='$year-$m-1' AND process_date<='$last_date' ORDER BY process_date");

        $pdf->SetFillColor(247,248,249);
      $pdf->SetTextColor(0);

      $month_charge = 0.0;

        while($charges_row = pg_fetch_assoc($charges_results))
        {

            $month_charge += $charges_row['amount'];
            $tdate = $charges_row['assessment_date'];
            $desc = $charges_row['assessment_rule_type_id'];

            $r = pg_fetch_assoc(pg_query("SELECT * FROM assessment_rule_type WHERE assessment_rule_type_id=$desc"));
            $desc = $r['name'];

            $pdf->Cell(20, 5, date('F', strtotime($tdate)), 0, 0, 'L');
            $pdf->Cell(30, 5, $charges_row['id']."-".$charges_row['assessment_rule_type_id'], 0, 0, 'L');
            $pdf->Cell(80, 5, date('m-d-y', strtotime($tdate))." | ".$desc, 0, 0, 'L');
            $pdf->Cell(20, 5, "$ ".$charges_row['amount'], 0, 0, 'L');
            $pdf->Cell(20, 5, " ", 0, 0, 'L');
            $pdf->Cell(40, 5, "$ ".$month_charge, 0, 1, 'L');

        }     
      
      $month_payment = 0.0;
        $fill = true;

        while($payments_row = pg_fetch_assoc($payments_results))
        {

            $month_payment += $payments_row['amount'];
            $tdate = $payments_row['process_date'];

            $pdf->Cell(20, 5, date('F', strtotime($tdate)), 0, 0, 'L');
            $pdf->Cell(30, 5, $payments_row['id']."-".$payments_row['payment_type_id'], 0, 0, 'L');
            $pdf->Cell(80, 5, date('m-d-y', strtotime($tdate))." | Payment Received # ".$payments_row['document_num'], 0, 0, 'L');
            $pdf->Cell(20, 5, " ", 0, 0, 'L');
            $pdf->Cell(20, 5, "$ ".$payments_row['amount'], 0, 0, 'L');
            $pdf->Cell(40, 5, "$ ".$month_payment, 0, 1, 'L');
        }

    }

    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(20, 6, "", 0, 0);
    $pdf->Cell(30, 6, "", 0, 0);
    $pdf->Cell(80, 6, "Total", 0, 0);
    $pdf->Cell(20, 6, "$ ".$total_charges, 0, 0);
    $pdf->Cell(20, 6, "$ ".$total_payments, 0, 0);
    $pdf->Cell(40, 6, "$ ".$total, 0, 1);
    $pdf->SetFont("Arial", "", 10);


    $pdf->Cell(189, 6, " ", 0, 1);


    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(100, 6, "Note", 0, 1, L);
    $pdf->SetFont("Arial", "", 10);


    $pdf->Cell(100, 6, "BillPay Address : ", 0, 1, L);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(100, 6, $c_name, 0, 1, L);
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(100, 6, $pobox, 0, 1, L);
    $pdf->Cell(100, 6, $city.", ".$state." ".$zip, 0, 1, L);
    $pdf->Cell(100, 6, "EIN : ".$tax_id, 0, 1, L);


    $pdf->Cell(189, 6, " ", 0, 1);
    

    $pdf->Cell(100, 6, "Send an email to ".$c_email." for HOA related queries", 0, 1, L);
    $pdf->Cell(100, 6, "All updates will be posted at ".$c_website, 0, 1, L);


    $pdf->output();


?>