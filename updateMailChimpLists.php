<?php
	
	date_default_timezone_set('America/Los_Angeles');
	
	include 'includes/dbconn.php';
	
	$ch = curl_init('https://us12.api.mailchimp.com/3.0/lists/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: apikey af5b50b9f714f9c2cb81b91281b84218-us12'));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch);
	$result = json_decode($result);
	foreach ($result->lists as $list) {

		if ( $list->stats->campaign_last_sent == "" ){
			$cLastSent = "NULL";

		}

		else {
			$cLastSent = "'".$list->stats->campaign_last_sent."'";
		}


		if ( $list->stats->last_unsub_date == "" ){
			$cLastUnsub = "NULL";
		}

		else {
			$cLastUnsub = "'".$list->stats->last_unsub_date."'";
		}

		$query = "INSERT INTO community_campaigns_lists(list_id,community_id,list_name,date_created,list_rating,member_count,unsubscribe_count,cleaned_count,member_count_since_send,unsubscribe_count_since_send,cleaned_count_since_send,campaign_count,campaign_last_sent,merge_field_count,avg_sub_rate,avg_unsub_rate,target_sub_rate,open_rate,click_rate,last_sub_date,last_unsub_date,update_date,updated_by) VALUES('".$list->id."',2,'".$list->name."','".$list->date_created."',".$list->list_rating.",".$list->stats->member_count.",".$list->stats->unsubscribe_count.",".$list->stats->cleaned_count.",".$list->stats->member_count_since_send.",".$list->stats->unsubscribe_count_since_send.",".$list->stats->cleaned_count_since_send.",".$list->stats->campaign_count.",".$cLastSent.",".$list->stats->merge_field_count.",".round($list->stats->avg_sub_rate,2).",".round($list->stats->avg_unsub_rate,2).",".round($list->stats->target_sub_rate,2).",".round($list->stats->open_rate,2).",".round($list->stats->click_rate,2).",'".$list->stats->last_sub_date."',".$cLastUnsub.",'".date('Y-m-d H:i:s')."',401) ON CONFLICT(list_id) DO UPDATE SET list_rating=".round($list->list_rating,2).",member_count=".$list->stats->member_count.",unsubscribe_count=".$list->stats->unsubscribe_count.",cleaned_count=".$list->stats->cleaned_count.",member_count_since_send=".$list->stats->member_count_since_send.",unsubscribe_count_since_send=".$list->stats->unsubscribe_count_since_send.",cleaned_count_since_send=".$list->stats->cleaned_count_since_send.",campaign_count=".$list->stats->campaign_count.",campaign_last_sent=".$cLastSent.",merge_field_count=".$list->stats->merge_field_count.",avg_sub_rate=".round($list->stats->avg_sub_rate,2).",avg_unsub_rate=".round($list->stats->avg_unsub_rate,2).",target_sub_rate=".round($list->stats->target_sub_rate,2).",open_rate=".round($list->stats->open_rate,2).",click_rate=".round($list->stats->click_rate,2).",last_sub_date='".$list->stats->last_sub_date."',last_unsub_date=".$cLastUnsub.",update_date='".date('Y-m-d H:i:s')."',updated_by=401";
		if ( !pg_query($query)){
			print_r($query);
		print_r(nl2br("\n\n"));
	}
	}
?>

		