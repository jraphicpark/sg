<?php

// Given a list of people with their birth and end years (all between 1900 and 2000), 
// find the year with the most number of people alive.

echo '<pre>';

// grab data
$file = 'example_data.json';
$jsonFile = file_get_contents($file);
$json = json_decode($jsonFile, true);


// compare current year ($i) to individual birth and end date
// add 1 to $yearCount[$i] when year is within range of bdate and edate
$yearCount = array();

for ($i = 1900; $i <= 2000; $i++) {
	foreach ($json as $data) {

		// isolate birth year
		$dateParts = explode('-', $data['bdate']);
		$bYear = $dateParts[0];

		// isolate end year
		$dateParts = explode('-', $data['edate']);
		$eYear = $dateParts[0];

		// is current year ($i) between birth year and end year?
		if ( $i >= $bYear && $i <= $eYear ) {
			
			// add one to that current year ($i)
			if (isset($yearCount[$i])) {
				$yearCount[$i]++;
			} else {
				$yearCount[$i] = 1;
			}
		}
	}
}

// sort year count in descending order
arsort($yearCount);

// check for tie
$bossValue = 0;
$result = array();
foreach ($yearCount as $key => $value) {

	if ($bossValue == 0) {
		$bossYear = $key;
		$bossValue = $value;
	}

	if ($value == $bossValue) {
		$results[$key] = $value;
	}

	if ($value < $bossValue) {
		break;
	}

}

if (count($results) > 1) {
	ksort($results);
	echo 'There was a tie in the number of living people each year.<br />';
	foreach($results as $key => $value) {
		echo '<li>Year ' . $key . ' had ' . $value . ' people living.</li>';
	}
} else {
	echo $bossYear . ' had the most living people with ' . $bossValue;
}

// show all results
echo '<hr>Data sorted by most living within given year.<br>';
print_r($yearCount);

?>




