<?php
include "ISS.php";
function getLocation($timestamps){
	$ISSInstance = new ISS();
	$locations = $ISSInstance->getLocations($timestamps);
	return $locations;
}
//generate 13 point timestamp 
function generateTimestamp($timestamp){
	unset($timestamps);
	$timestamps = [];
	$timestamp = $timestamp - 3600;
	for ($x = 0; $x < 13; $x++) {
		//600 means every 10 minutes
		$timestamp = $timestamp + 600;
		array_push($timestamps,$timestamp);
	}
	return $timestamps;
}


$query = $_POST['datetime'];
$timestamp = new DateTime($query,new DateTimeZone('Asia/Kuala_Lumpur'));
$timestampArr = generateTimestamp($timestamp->getTimestamp());
$locations = getLocation($timestampArr);
print_r($locations);

?>