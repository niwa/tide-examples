<?php

/*   Super simple script to fetch the tide data from NIWA's API (https://developer.niwa.co.nz/docs/tide-api)
 *   
 *   It cycles through the tide values and prints out a basic description of the next two tides. One high, one low.
 *
 *   If you have a valid php environment all you need to do is put your apikey into the script and run
 *
 *   php nexttides.php
 *	 
 */


 $curl = curl_init();

	$lat = -37.406;
	$long =	175.947;
	$apikey = ""; // Register for an API key at https://developer.niwa.co.nz
	
	if ($apikey == "") {
		echo "Register for an API key at https://developer.niwa.co.nz\n";
		die;
	}

    curl_setopt($curl, CURLOPT_URL, "https://api.niwa.co.nz/tides/data?lat=".$lat."&long=".$long."&datum=MSL&numberOfDays=2&apikey=".$apikey);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

	$tides = json_decode($result);
	
	$firstTideIndex = 0;
	$now = new DateTime();
	
	for ($firstTideIndex = 0; $firstTideIndex < count($tides->values); $firstTideIndex++) {
		$tideTime = new DateTime($tides->values[$firstTideIndex]->time);
		if ($tideTime > $now) {
			break;
		}
	}
	
	$nzTimezone = new DateTimeZone('Pacific/Auckland');
	
	$tide1 = $tides->values[$firstTideIndex];
	$tide1TimeNZ = new DateTime($tide1->time);
	$tide1TimeNZ->setTimeZone($nzTimezone);
	
	
	if ($tide1->value > 0) {
		echo "Next high tide is ";
	} else {
		echo "Next low tide is ";
	}

    echo $tide1->value . "m at " . $tide1TimeNZ->format('F jS g:ia') ."\n";
	
	
	$tide2 = $tides->values[$firstTideIndex+1];
	$tide2TimeNZ = new DateTime($tide2->time);
	$tide2TimeNZ->setTimeZone($nzTimezone);
	
	
	if ($tide2->value > 0) {
		echo "Next high tide is ";
	} else {
		echo "Next low tide is ";
	}

    echo $tide2->value . "m at " . $tide2TimeNZ->format('F jS g:ia') ."\n";
    

