<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once('./inc/db.inc.php');
require_once('./inc/getLiveData.inc.php');

	if(isset($_GET['city'])) {

	$_GET['country'] 	= 'DE';

	/* GET DATA */
		$dataJSON = liveData::getData($_GET['city'], $_GET['country']);
		$data = json_decode($dataJSON);
	/* GET DATA */

	/* INSERT */
		$db = new Db();    
		$result = $db->query("INSERT INTO `weatherData` (
															`ID`, 
															`jsonData`, 
															`city`, 
															`countryCode`, 
															`timestamp`, 
															`weatherMain`, 
															`weatherDescription`, 
															`weatherIcon`, 
															`temp`, 
															`pressure`, 
															`humidity`, 
															`temp_min`, 
															`temp_max`, 
															`visibility`, 
															`windSpeed`, 
															`windDeg`, 
															`sunrise`, 
															`sunset`
														) VALUES (
															NULL, 
															'".$dataJSON."', 
															'".$data->name."', 
															'".$data->sys->country."', 
															CURRENT_TIMESTAMP, 
															'".$data->weather[0]->main."', 
															'".$data->weather[0]->description."', 
															'".$data->weather[0]->icon."', 
															'".$data->main->temp."',  
															'".$data->main->pressure."', 
															'".$data->main->humidity."',
															'".$data->main->temp_min."',
															'".$data->main->temp_max."',
															'".$data->visibility."', 
															'".$data->wind->speed."', 
															'".$data->wind->deg."',
															'".date( 'Y-m-d H:i:s', $data->sys->sunrise)."', 
															'".date( 'Y-m-d H:i:s', $data->sys->sunset)."'
														);");
	/* INSERT */	
	}