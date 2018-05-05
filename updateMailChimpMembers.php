<?php
	
	date_default_timezone_set('America/Los_Angeles');

	include 'includes/dbconn.php';
	
	$query = "SELECT LIST_ID FROM COMMUNITY_CAMPAIGNS_LISTS WHERE COMMUNITY_ID = 2";
	$queryResult = pg_query($query);
	while ($row = pg_fetch_assoc($queryResult)) {
	$ch = curl_init('https://us12.api.mailchimp.com/3.0/lists/'.$row['list_id'].'/members/');

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: apikey af5b50b9f714f9c2cb81b91281b84218-us12'));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch);
	$result = json_decode($result);
	foreach ($result->members as $member) {
	$query  = "INSERT INTO community_campaigns_lists_members (list_id,member_id,email_address,status,avg_open_rate,avg_click_rate,member_rating,last_changed,updated_on,updated_by) VALUES('".$row['list_id']."','".$member->id."','".$member->email_address."','".$member->status."',".round($member->stats->avg_open_rate,2).",".round($member->stats->avg_click_rate).",".$member->member_rating.",'".$member->last_changed."','".date('Y-m-d H:i:s')."',401) ON CONFLICT(member_id) DO UPDATE SET updated_on='".date('Y-m-d H:i:s')."',updated_by=401";
	if ( !pg_query($query) ){
		print_r($query);
		print_r(nl2br("\n\n"));
	}
	
	}

	}
	
?>

		