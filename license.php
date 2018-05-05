<?php	
	
	include 'includes/dbconn.php';

	$query = "SELECT legal_name FROM community_info WHERE community_id = 2";
	$result = pg_query($query);	
	$num_row = pg_num_rows($result);
	
	echo "Community Name is ".$num_row;
?>

<html lang='en'>


<head>
</head>
<body>

<form method='POST' action='license_check.php'>

										<div class='form-group'>

											<input class='form-control form-control-lg' name='license_plate' id='license_plate' type='text' placeholder='License' required>

										</div>

										<div class='form-group'>

											<button class='btn btn-block btn-lg btn-round btn-success' type='submit'><i class='fa fa-sign-in'></i> Check License</button>

										</div>

									</form>
</body>								