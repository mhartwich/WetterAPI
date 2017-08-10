<?php
class liveData {
    public static function getData($city, $country) {    
		if(isset($city) && isset($country)) {
		    $url = 'http://api.openweathermap.org/data/2.5/weather?q='.$city.','.$country.'&lang=de&units=metric&appid=08b6af0b5ba5b988a3e262b04b71e7b4';
		    
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers = array());
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		    $json = curl_exec($curl);
		    curl_close($curl);
		    
		    return $json;
		}
    }
}