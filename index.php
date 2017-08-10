<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

if(!isset($_POST['search']) || $_POST['search'] == '') {
	$_POST['city'] 		= 'Berlin';
} else { 
	$_POST['city'] 		= $_POST['search'];
}

$_POST['country'] 	= 'DE';
			    
require_once('./inc/db.inc.php');
require_once('./inc/getLiveData.inc.php');

/* HTML START */
	echo '<!DOCTYPE html>';
	echo '<html>';	
	echo '<head>';	
	echo '<meta charset="UTF-8">';
	echo '<title>Wetter</title>';
	echo '<html lang="de">';
	echo '<link href="https://fonts.googleapis.com/css?family=Fira+Sans:300,400,700" rel="stylesheet">';
	echo '<link rel="stylesheet" type="text/css" href="/css/styles.css">';
	echo '<link rel="stylesheet" type="text/css" href="/css/normalize.css">';
	echo '<script src="/js/jquery-3.2.1.min.js"></script>';
	echo '<script src="/js/parallax.js"></script>';
	echo '<script src="/js/jquery-documentReady.js"></script>';
	echo '</head>';	
	echo '<body>';	
/* HTML START */

/* HEADER */
	echo '<div id="header">';
	echo '<form action="/index.php" method="post" autocomplete="on">';
	echo '<input id="search" name="search" type="text" placeholder="Zum Suchen Enter drücken!"><input id="search_submit" value="Suchen" type="submit">';
	echo '</form>';
	echo '</div>';
/* HEADER */

/* CONTENT */
	/* PARALLAX LIVE SCREEN */

		/* GET LIVE DATA */
		    $dataJSON = liveData::getData($_POST['city'], $_POST['country']);
		    $data = json_decode($dataJSON);
		/* GET LIVE DATA */
		
			//echo '<div class="parallax-window" data-parallax="scroll" data-image-src="/img/'.$data->weather[0]->icon.'.jpg" id="slide-1">';
			echo '<div class="parallax-window" data-parallax="scroll" data-image-src="/img/03n.jpg" id="slide-1">';
			
			    	echo '<div class="content">';
			    	
					if($data->message != 'city not found') {
			    		echo '<h1>Heute: '.date('d.m.Y', time()).' I Ort: '.$_POST['city'].'</h1>';
			    		
			    		echo '<table border="0" width="100%">';
			    		echo '<tr>';
			    		echo '<td width="300" style="background: rgba(255,255,255,0.2);"><img width="300" height="300" src="/img/'.$data->weather[0]->icon.'.svg" class="icon" \></td>';
			    		echo '<td style=" padding: 0 40px; 0 40px">';
			    		echo '<h1><img width="90" height="90" src="/img/max.svg" \>'.number_format($data->main->temp, 2, ',', ' ').'°</h1><br />';
			    		echo $data->weather[0]->description.'<br /><br />';
			    		
			    		echo 'Temperatur: <strong>'.number_format($data->main->temp_min, 0, ',', ' ').'° - '.number_format($data->main->temp_max, 0, ',', ' ').'°</strong><br /><br />';
			    		
			    		echo '<div class="info">';
			    		echo 'Luftdruck: <strong>'.$data->main->pressure.' hPa</strong><br/>';
			    		echo 'Luftfeuchtigkeit: <strong>'.$data->main->humidity.' %</strong><br/><br/>';
			    											
			    		echo 'Sichtweite: <strong>'.number_format($data->visibility, 0, ',', '.').' m</strong><br/><br/>';
			    		echo 'Windgeschwindigkeit: <strong>'.$data->wind->speed.' km/h</strong><br/>';
			    		echo 'Windrichtung: <strong>'.$data->wind->deg.'°</strong><br/><br/>';
			    											
			    		echo 'Sonnenaufgang: <strong>'.date( 'H:i', $data->sys->sunrise).' Uhr</strong><br/>';
			    		echo 'Sonnenuntergang: <strong>'.date( 'H:i', $data->sys->sunset).' Uhr</strong><br/>';
			    		echo '</div>';
			    	
			    		echo '</td>';
			    		echo '</tr>';
			    		echo '</table>';
			    	} else {
				    	echo '<h2>Leider liegen uns keine historischen Wetterdaten f&uuml;r "'.$_POST['city'].'" vor!</h2>';
			    	}
			
			    	echo '</div>';
			    
			echo '</div>';
	/* PARALLAX LIVE SCREEN */
	
	/* PARALLAX HISTORY SCREEN */
		echo '<div class="parallax-window" data-parallax="scroll" id="slide-2">';
		
				echo '<div class="content-1">';
				
					echo '<h1>Historische Wetterdaten für '.$_POST['city'].'</h1>';
		
					/* QUERY DATA HISTORY */
					    $db = new Db();
					    $rows = $db->select("SELECT *, DATE_FORMAT(timestamp, '%d.%m.%Y %H:%I Uhr') AS timestamHuman, DATE_FORMAT(sunrise, '%d.%m.%Y %H:%I Uhr') AS sunriseHuman, DATE_FORMAT(sunset, '%d.%m.%Y %H:%I Uhr') AS sunsetHuman FROM `weatherData` WHERE `city` = '".$_POST['city']."' ORDER BY `timestamp` DESC;");
					/* QUERY DATA HISTORY */
					
					if(count($rows) > 0) {
						foreach($rows AS $key => $value) {
							echo '<div class="historyInfo">';
							echo '<h2>Wetterdaten von '.$_POST['city'].' vom '.$value["timestamHuman"].'</h2>';
						
							echo '<img width="300" height="300" src="/img/'.$value["weatherIcon"].'.svg" style="float: left;" \>';
							
							echo '<h1><img width="90" height="90" src="/img/max.svg" \>'.number_format($value["temp"], 2, ',', ' ').'°</h1><br />';
							echo $value["weatherDescription"].'<br /><br />';
							
							echo 'Temperatur: <strong>'.number_format($value["temp_min"], 0, ',', ' ').'° - '.number_format($value["temp_max"], 0, ',', ' ').'°</strong><br /><br />';
							
							echo '<div class="info">';
							echo 'Luftdruck: <strong>'.$value["pressure"].' hPa</strong><br/>';
							echo 'Luftfeuchtigkeit: <strong>'.$value["humidity"].' %</strong><br/><br/>';
																
							echo 'Sichtweite: <strong>'.number_format($value["visibility"], 0, ',', '.').' m</strong><br/><br/>';
							echo 'Windgeschwindigkeit: <strong>'.$value["windSpeed"].' km/h</strong><br/>';
							echo 'Windrichtung: <strong>'.$value["windDeg"].'°</strong><br/><br/>';
																
							echo 'Sonnenaufgang: <strong>'.$value["sunriseHuman"].' Uhr</strong><br/>';
							echo 'Sonnenuntergang: <strong>'.$value["sunsetHuman"].' Uhr</strong>';
							echo '</div>';
							echo '</div>';
						}
					}  else {
							echo '<div class="historyInfo">';
							echo '<h2>Leider liegen uns keine historischen Wetterdaten f&uuml;r "'.$_POST['city'].'" vor!</h2>';
						
	
							echo '</div>';
							echo '</div>';
						
					}

				echo '</div>';
		
		echo '</div>';
	/* PARALLAX HISTORY SCREEN */
/* CONTENT */

/* HTML END */
	echo '</body>';
	echo '</html>';
/* HTML END */