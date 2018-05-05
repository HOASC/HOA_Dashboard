<?php
include 'includes/dbconn.php';
if ( true ){
    $query  = "SELECT HOME_ID,COMMUNITY_ID FROM HOAID WHERE HOA_ID = ".$_GET['id'];
    $orderNumber = $_GET['orderID'];
    print_r($query);
    print_r(nl2br("\n"));
    $queryResult = pg_query($query);
    $row = pg_fetch_assoc($queryResult);
    $homeID = $row['home_id'];
    $communityID = $row['community_id'];

    if (  isset($_GET['type'])  ){

        if ( $_GET['type'] == 0 ){
            $query = "INSERT INTO COMMUNITY_STATEMENTS_MAILED(\"home_id\",\"hoa_id\",\"date_sent\",\"community_id\",\"notification_type\",\"order_id\",\"invoice_id\",\"updated_on\",\"updated_by\",\"sent_file_tech_id\") VALUES(".$homeID.",".$_GET['id'].",'".date('Y-m-d')."',".$communityID.",4,".$orderNumber.",".$orderNumber.",'".date('Y-m-d')."',401,'".$_GET['file_id']."')";
            pg_query($query);
            exit(0);
        }
    }
    else {
    $query = "INSERT INTO COMMUNITY_STATEMENTS_MAILED(\"home_id\",\"hoa_id\",\"date_sent\",\"community_id\",\"statement_type_id\",\"notification_type\",\"order_id\",\"invoice_id\",\"updated_on\",\"updated_by\") VALUES(".$homeID.",".$_GET['id'].",'".date('Y-m-d')."',".$communityID.",2,4,".$orderNumber.",".$orderNumber.",'".date('Y-m-d')."',401)";
    pg_query($query);
    }
}
?>