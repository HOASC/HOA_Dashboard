<?php

include 'includes/dbconn.php';

$query = "SELECT * FROM INSPECTION_CATEGORY";
$queryResult  = pg_query($query);

$inspectionCategoryArray = array();

while ($row = pg_fetch_assoc($queryResult)) {
	$inspectionCategoryArray[$row['id']] = $row['name'];
}

$someJSON = file_get_contents('php://input');
$parsedJSON = json_decode($someJSON);
$id = $parsedJSON[0]->id;
$query  = "SELECT * FROM INSPECTION_NOTICES WHERE ID=".$id;
$queryResult = pg_query($query);

while ($row = pg_fetch_assoc($queryResult)) {
	$description = $row['description'];
	$communityID = $row['community_id'];
	$hoaID = $row['hoa_id'];
	$homeID = $row['home_id'];
	$locationID  = $row['location_id'];
	$inspectionCategoryID = $row['inspection_category_id'];
	$inspectionSubCategoryID = $row['inspection_sub_category_id'];
	$inspectionStatusID = $row['inspection_status_id'];
	$inspectionItem = $row['item'];
}
echo json_encode( array("id" => $id,"community_id"=>$communityID,"description"=>$description,"hoa_id"=>$hoaID,"home_id"=>$homeID,"location_id"=>$locationID,"inspection_category_id"=>$inspectionCategoryID,"inspection_sub_category_id"=>$inspectionSubCategoryID,"inspection_status_id"=>$inspectionStatusID,"item"=>$inspectionItem));
?>