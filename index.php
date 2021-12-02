<!DOCTYPE html>
<html>
<head>
	<title>Where is the ISS</title>
</head>
<body>

	<h1>Where is the ISS</h1>
	<div>
		<form id="form" method="post" action="Webservices.php">
			Date and time (Malaysia Time):
			<input type="datetime-local" name="datetime" value="<?php echo date('Y-m-d'); ?>" />
			<br/>
			<input type="submit" name="search" class="button" value="search">
		</form>
	</div>
</body>
</html>