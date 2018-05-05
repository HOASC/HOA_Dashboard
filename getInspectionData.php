<?php

include 'includes/dbconn.php';

if ( true){
    $query = "SELECT   * FROM LOCATIONS_IN_COMMUNITY";
    $queryRes = pg_query($query);
    $locationsArray = array();
    while ($row = pg_fetch_assoc($queryRes)) {
        $locationsArray[$row['location_id']] = $row['location'];
    }
    $categoryArray  = array();

    $query = "SELECT * FROM INSPECTION_CATEGORY";
    $queryRes  = pg_query($query);

    while ($row = pg_fetch_assoc($queryRes)) {
        $categoryArray[$row['id']] = $row['name'];
    }

    $inspectionStatusArray = array();
    $query = "SELECT * FROM INSPECTION_STATUS";
    $queryRes  = pg_query($query);
    while ($row = pg_fetch_assoc($queryRes)) {
        $inspectionStatusArray[$row['id']] = $row['inspection_status'];
    }
    $inspectionSubCategoryArray  = array();
    $query = "SELECT * FROM INSPECTION_SUB_CATEGORY";
    $queryResult = pg_query($query);
    while ($row = pg_fetch_assoc($queryResult)) {
        $inspectionSubCategoryArray[$row['id']] = $row['name'];
    }

    if( $_GET['id']){
        $query =  "SELECT * FROM INSPECTION_NOTICES WHERE INSPECTION_STATUS_ID=".$_GET['id'];
        $queryResult = pg_query($query);
        $sendData = array();
        while ($row = pg_fetch_assoc($queryResult)) {
            $data = array();
            $data['id']  = $row['id'];
            $data['inspection_date'] = $row['inspection_date'];
            $data['description'] = $row['description'];
            $data['community_id'] = $row['community_id'];
            $data['home_id'] = $row['home_id'];
            $data['location_id'] = $locationsArray[$row['location_id']];
            $data['inspection_category_id'] = $categoryArray[$row['inspection_category_id']];
            $data['inspection_sub_category_id'] = $inspectionSubCategoryArray[$row['inspection_sub_category_id']];
            $data['hoa_id'] = $row['hoa_id'];
            $data['inspection_notice_type_id'] = $row['inspection_notice_type_id'];
            $data['inspection_status'] = $inspectionStatusArray[$row['inspection_status_id']];
            array_push($sendData, $data);
        }
    }
    else {
        $query =  "SELECT * FROM INSPECTION_NOTICES WHERE INSPECTION_STATUS_ID != 2 AND INSPECTION_STATUS_ID != 6 AND INSPECTION_STATUS_ID != 9 AND INSPECTION_STATUS_ID != 13 AND INSPECTION_STATUS_ID != 14";
        $queryResult = pg_query($query);
        $sendData = array();
        while ($row = pg_fetch_assoc($queryResult)) {
            $data = array();
            $data['id']  = $row['id'];
            $data['inspection_date'] = $row['inspection_date'];
            $data['description'] = $row['description'];
            $data['community_id'] = $row['community_id'];
            $data['home_id'] = $row['home_id'];
            $data['location_id'] = $locationsArray[$row['location_id']];
            $data['inspection_category_id'] = $categoryArray[$row['inspection_category_id']];
            $data['inspection_sub_category_id'] = $inspectionSubCategoryArray[$row['inspection_sub_category_id']];
            $data['hoa_id'] = $row['hoa_id'];
            $data['inspection_notice_type_id'] = $row['inspection_notice_type_id'];
            $data['inspection_status'] = $inspectionStatusArray[$row['inspection_status_id']];
            array_push($sendData, $data);
        }
    }
}
echo json_encode($sendData);
?>