<?php
class ISS{

  private $name;
  private $satID;
  private $baseURL;
  
  function __construct(){
	$this->baseURL = "https://api.wheretheiss.at/v1/satellites/";
	$json = file_get_contents($this->baseURL);
	$result = json_decode($json, true);
	$this->name = $result[0]["name"];
	$this->satID = $result[0]["id"];
	echo $this->name;
	echo $this->satID;
  }
  function set_name($name) {
    $this->name = $name;
  }
  function get_name() {
    return $this->name;
  }
  function set_satID($satID) {
    $this->satID = $satID;
  }
  function get_satID() {
    return $this->satID;
  }
  
  //this fuction get the location of the ISS data.
  function getLocations($timestampArr){
	  $timestamps = "";
	  $counter = 0;
	  foreach ($timestampArr as $timestamp){
		  if($counter == 0){
			$timestamps = $timestamps.$timestamp;
		  }else{
			$timestamps = $timestamps.",".$timestamp;
		  }
		  $counter++;
	  }
	  $URL = $this->baseURL.$this->satID."/positions?timestamps=".$timestamps."&units=miles";
	  $json = file_get_contents($URL);
	  $resultSet = json_decode($json, true);
	  
	  unset($locationResult);
	  $locationResult = [];
	  
	  foreach ($resultSet as $result){
		$lat = $result["latitude"];
		$lon = $result["longitude"];
		$locURL = "https://api.wheretheiss.at/v1/coordinates/". $lat . "," . $lon;
		$json = file_get_contents($locURL);
		$location = json_decode($json, true);
		array_push($locationResult,[$result["timestamp"],$location["timezone_id"],$location["country_code"]]);
		
	  }
	  return $locationResult;
  }
  
  
}

?>