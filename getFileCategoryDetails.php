<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('America/Los_Angeles');

include 'includes/dbconn.php';

$data = file_get_contents('php://input');
$parseJSON = json_decode($data);

if ( $parseJSON[0]->type == "legal" ){
     $query = "SELECT * FROM DOC_MAPPING WHERE COMMUNITY_ID=".$parseJSON[0]->community_id." AND TABLE_NAME='legal_docs_type' AND SUB_CATEGORY='".$parseJSON[0]->sub_category."'";
     $queryResult = pg_query($query);   
     $row = pg_fetch_assoc($queryResult);

     $result = $row['name'].'@'.$row['short_desc'].'@'.date('m/d/Y',strtotime($row['valid_from'])).'@'.date('m/d/Y',strtotime($row['valid_until']));
     echo $result;
     
     if ( !(isset($row['valid_from'])) ){
     $secondQuery = "SELECT * FROM COMMUNITY_LEGAL_DOCS WHERE legal_docs_type_id=(SELECT ID FROM legal_docs_type WHERE name='".$parseJSON[0]->sub_category."' and community_id=".$parseJSON[0]->community_id.") AND COMMUNITY_ID=".$parseJSON[0]->community_id;
     }
     else {
     $secondQuery = "SELECT * FROM COMMUNITY_LEGAL_DOCS WHERE legal_docs_type_id=(SELECT ID FROM legal_docs_type WHERE name='".$parseJSON[0]->sub_category."' and community_id=".$parseJSON[0]->community_id.") AND COMMUNITY_ID=".$parseJSON[0]->community_id." and valid_from='".$row['valid_from']."' and valid_until='".$row['valid_until']."' ORDER BY last_updated_on";
     }
     $secondQueryResult = pg_query($secondQuery);
     while( $row2 = pg_fetch_assoc($secondQueryResult) ){
          $row23 = $row2;
     }
     if ( isset($row23) ){
     if ( $row23['id'] ){
          if ( $row23['document_id'] ){
               echo "@A file found@".$row23['document_id'];
          }
          else {
               echo "Not found";
          }
     }
     else {
          echo "Not found";
     }
     }
     else {
          echo "Not found";
     }


}
else if ( $parseJSON[0]->type == "disclosure" ){
     $query = "SELECT * FROM DOC_MAPPING WHERE COMMUNITY_ID=".$parseJSON[0]->community_id." AND TABLE_NAME='disclosure_type' AND SUB_CATEGORY='".$parseJSON[0]->sub_category."'";
     $queryResult = pg_query($query);   
     $row = pg_fetch_assoc($queryResult);
     $result = $row['name'].'@'.$row['short_desc'].'@'.date('m/d/Y',strtotime($row['valid_from'])).'@'.date('m/d/Y',strtotime($row['valid_until']));
     echo $result;
     if ( !(isset($row['valid_from'])) ){
     $secondQuery = "SELECT * FROM community_disclosures where type_id=(select id from disclosure_type where name = '".$parseJSON[0]->sub_category."' and community_id=".$parseJSON[0]->community_id.") and community_id =".$parseJSON[0]->community_id."' ORDER BY updated_on";
     }
     else {
      // $secondQuery = "SELECT * FROM community_disclosures where type_id=(select id from disclosure_type where name = '".$parseJSON[0]->sub_category."' and community_id=".$parseJSON[0]->community_id.") and community_id =".$parseJSON[0]->community_id." and fiscal_year_start='".$row['valid_from']."' and fiscal_year_end='".$row['valid_until']."' ORDER BY updated_on";
     $secondQuery = "SELECT * FROM community_disclosures where type_id=(select id from disclosure_type where name = '".$parseJSON[0]->sub_category."' and community_id=".$parseJSON[0]->community_id.") and community_id =".$parseJSON[0]->community_id." ORDER BY updated_on";
     }
     $secondQueryResult = pg_query($secondQuery);
     while( $row2  = pg_fetch_assoc($secondQueryResult) ){
          $row23 = $row2;
     }
     if( isset($row23) ){
     if ( $row23['id'] ){
          if ( $row23['document_id'] ){
               echo "@A file found@".$row23['document_id'];
          }
          else {
               echo "document missing";
          }
     }
     else {
          echo "Not found";
     }
     }
     else {
          echo "Not found";
     }
}

?>