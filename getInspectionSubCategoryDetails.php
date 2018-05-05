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
$query  = "SELECT * FROM INSPECTION_SUB_CATEGORY WHERE ID=".$id;
$queryResult = pg_query($query);

while ($row = pg_fetch_assoc($queryResult)) {
	$name = $row['name'];
	$inspectionCategory = $row['inspection_category_id'];
	$rule = $row['rule'];
	$rule_description = $row['rule_description'];
	$explanation  = $row['explanation'];
	$subject = $row['subject'];
	$community_Legal = $row['community_legal_docs_id'];
	$section  = $row['section'];
	$community_ID = $row['community_id'];
}
echo json_encode( array("id" => $id,"name" => $name,"inspection_category"=> $inspectionCategoryArray[$inspectionCategory],"rule" => $rule,"rule_description" => $rule_description,"explanation" => $explanation,"subject"=> $subject,"community_legal_id" => $community_Legal,"section" => $section,"community_id" => $community_ID));

?>