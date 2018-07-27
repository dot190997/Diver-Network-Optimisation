<?php

function calculate($origin, $destin)
{	
	$api="AIzaSyDX0QWXi0WRC8F4HKmmJGoBe4wKz5PJO9M";
	$details = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=". $origin . "&destinations=" . $destin . "&mode=driving&sensor=false";


    $json = file_get_contents($details);

    $details = json_decode($json, TRUE);

    $kms=$details['rows'][0]['elements'][0]['distance']['text'];
    $time=$details['rows'][0]['elements'][0]['duration']['value'];

    $kms = (float)$kms;
    $time = (float)$time;
    $time=$time/60;

    $a = array($kms, $time);
    return $a;
}

?>