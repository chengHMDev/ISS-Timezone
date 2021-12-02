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
		if($location["country_code"] == "??"){
			$counter = 1;
			$predict_time = (int)$result["timestamp"];
			$prediction = False;
			//kill the loop if prediction can't retrive a value in 3 tried to prevent application crash
			//this area to be improved.
			while($prediction == False){
				$predict_time = $predict_time + 60;
				if($counter >= 3){
					break;
				}else{
					$predictedResult = $this -> predictLocations($predict_time);
					if($predictedResult[0][2] != "??"){
						$prediction = True;
						$location["timezone_id"] = $predictedResult[0][1];
						$location["country_code"] = $predictedResult[0][2];
					}
				}
				$counter = $counter + 1;
			}
			$predictedResult = $this -> predictLocations((int)$result["timestamp"]);
		}
		array_push($locationResult,[$result["timestamp"],$location["timezone_id"],$location["country_code"]]);
		
	  }
	  return $locationResult;
  }
  
  //future version needs a saprate fuction for getLocation for single instance.
  
  //this is the prediction func
  function predictLocations($timestamp){
	  $URL = $this->baseURL.$this->satID."/positions?timestamps=".$timestamp."&units=miles";
	  $json = file_get_contents($URL);
	  $resultSet = json_decode($json, true);
	  
	  unset($predictedResult);
	  $predictedResult = [];
	  
	  foreach ($resultSet as $result){
		$lat = $result["latitude"];
		$lon = $result["longitude"];
		$locURL = "https://api.wheretheiss.at/v1/coordinates/". $lat . "," . $lon;
		$json = file_get_contents($locURL);
		$location = json_decode($json, true);
		array_push($predictedResult,[$result["timestamp"],$location["timezone_id"],$location["country_code"]]);
	  }
	  return $predictedResult;
  }
  
}

?>