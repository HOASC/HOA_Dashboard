<?php
$data = file_get_contents('php://input');
include 'includes/dbconn.php';
$hoaidquery = "SELECT * FROM community_library_documents WHERE community_id=2";
$query = pg_query($hoaidquery);
$libraryDocuments = array();
while ($row = pg_fetch_assoc($query)) {
	array_push($libraryDocuments, $row['document_name']);
}
echo json_encode($libraryDocuments);
?>