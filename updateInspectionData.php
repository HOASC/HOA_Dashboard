<?php
$parsedJSON = json_decode(file_get_contents('php://input'));
include 'includes/dbconn.php';
if( true ){
        $query = "UPDATE INSPECTION_NOTICES SET DESCRIPTION='".$parsedJSON[0]->inspection_description."',INSPECTION_CATEGORY_ID=".$parsedJSON[0]->inspection_category_id.",INSPECTION_SUB_CATEGORY_ID=".$parsedJSON[0]->inspection_sub_category_id.",HOME_ID=".$parsedJSON[0]->homeID.",HOA_ID=".$parsedJSON[0]->hoaID.",location_id=".$parsedJSON[0]->location_id.",inspection_status_id=".$parsedJSON[0]->inspection_status_id.",item='".$parsedJSON[0]->item."' WHERE ID=".$parsedJSON[0]->inspection_id;
        if ( pg_query($query) ){
                echo "Updated Successfully";
        }
        else {
            echo "Failed to update";
        }
}
?>