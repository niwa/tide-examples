<?php

$lat = -37.406;
$long = 175.947;
$apikey = ""; // Register for an API key at https://developer.niwa.co.nz

if ($apikey == "") {
    echo "Register for an API key at https://developer.niwa.co.nz\n";
    die;
}

$startDate = new DateTime('2023-01-01');
$endDate = new DateTime('2023-12-31');

$currentDate = clone $startDate;

while ($currentDate <= $endDate) {
    $year = $currentDate->format('Y');
    $month = $currentDate->format('m');
    $filename = "tides_" . $year . "_" . $month . ".json";
    echo "Downloading " . $currentDate->format('M') . " " . $year . "\n"; 
    // Calculate the number of days in the current month
    $numberOfDays = $currentDate->format('t');
    
    $url = "https://api.niwa.co.nz/tides/data?lat=".$lat."&long=".$long."&datum=MSL&numberOfDays=".$numberOfDays."&apikey=".$apikey;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    file_put_contents($filename, $result);

    $currentDate->add(new DateInterval('P1M'));
}

echo "Done\n";
