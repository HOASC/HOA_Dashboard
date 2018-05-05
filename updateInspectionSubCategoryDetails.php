<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/dbconn.php';

$query = "SELECT * FROM INSPECTION_CATEGORY";
$queryResult  = pg_query($query);
$inspectionCategoryArray = array();
while ($row = pg_fetch_assoc($queryResult)) {
	$inspectionCategoryArray[$row['name']] = $row['id'];
}
$someJSON = file_get_contents('php://input');
$parsedJSON = json_decode($someJSON);
$id = $parsedJSON[0]->id;
$name = $parsedJSON[0]->name;
$inspectionSubCategoryId = $inspectionCategoryArray[$parsedJSON[0]->inspection_category];
$rule = $parsedJSON[0]->rule;
$rule_description = $parsedJSON[0]->rule_description;
$explanation  = $parsedJSON[0]->explanation;
$subject = $parsedJSON[0]->subject;
$communityLegalDocsID = $parsedJSON[0]->community_legal_docs_id;
$section  = $parsedJSON[0]->section;
$query = "UPDATE INSPECTION_SUB_CATEGORY SET ";
if ( $name ){
	$query  = $query."name='".$name."'";
}
else {
	$query  = $query."name= NULL";
}
if ( $inspectionSubCategoryId ){
	$query = $query.",inspection_category_id=".$inspectionSubCategoryId."";
}
else {
	$query = $query.",inspection_category_id= NULL";
}
if ( !($rule == 'null' ) ){
	$query = $query.", rule='".$rule."'";
}
else {
	$query = $query.", rule= NULL";
}
if ( !($rule_description == 'null') ){
	$query = $query.", rule_description='".$rule_description."'";
}
else {
	$query = $query.", rule_description= NULL";
}
if ( !($explanation == 'null') ){
	$query = $query.", explanation='".$explanation."'";
}
else {
		$query = $query.", explanation= NULL";
}
if ( !($subject == 'null') ) {
	$query = $query.", subject='".$subject."' ";
}
else {
	$query = $query.", subject= NULL";
}
if ( !($communityLegalDocsID=='null') ){
	$query = $query.",community_legal_docs_id=".$communityLegalDocsID;
}
else {
	$query = $query.",community_legal_docs_id= NULL";
}
if ( !($section == 'null')  ){
	$query = $query.",section='".$section."'";
}
else {
	$query = $query.",section= NULL";
}
$query = $query." WHERE ID=".$id;
// echo $query;
if (pg_query($query)) {
	echo "Updated Successfully";
}

?>