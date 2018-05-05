<!DOCTYPE html>
<html>
	<head>
		<title>File Upload</title>
	</head>
	<body>
		<form action="uploadFile.php" method="POST" enctype="multipart/form-data">
			<label>File : </label>
			<input type="file" name='upload' id='upload' required />

			<input type="submit" name="submit" />
		</form>
	</body>
</html>