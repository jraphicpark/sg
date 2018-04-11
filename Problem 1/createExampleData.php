<?php

// create example date for persons living and dying between 1900 and 2000.


// First names
$firstNames = array('Julee','Regan','Dalton','Christiane','Kendal','Della','Pedro','Marg','Anderson','Betty','Kip','Lorretta','Fe','Henrietta','Ellis','Seth','Brittani','Santa','Ahmad','Jeffrey','Zack','Neely','Clorinda','Rosaline','Josphine','Jaye','Alysa','Norine','Santos','Nanci','Philomena','Van','Yer','Brendon','Forest','Ha','Noriko','Sunday','America','Luciana','Aiko','Zoila','Wanetta','Chiquita','Noble','Maragret','Ying','Malia','Isabella','Rosario');

// Last names
$lastNames = array('Siemens','Burstein','Honeycutt','Daye','Anselmo','Chiesa','Zink','Ginter','Moberg','Hwang','Lambrecht','Keim','Lobaugh','Oxford','Behm','Ridgell','Bridges','Linger','Shisler','Purgason','Kennerly','Mebane','Condict','Ronald','Burdo','Nickson','Mclendon','Larrabee','Minix','Noyes','Wetzel','Leday','Sanderson','Barbee','Folts','Hawks','Tolbert','Ingles','Mcdaniels','Fronk','Sieck','Garten','Ashcroft','Almaraz','Saidi','Hong','Keane','Schultheis','Rochelle','Dipasquale');

$i = 1;

while ($i <= 1000) {
	// create random birth years, a meta age, and an end year calculated from the bdate + age.
	$bYear = rand(1900, 2000);
	$age = rand(1,50) + rand(0,20) + rand(0,10) + rand(0,5);
	$eYear = $bYear + $age;

	// all date values must be between 1900 and 2000.
	// bYear already has a floor of 1900 and ceiling of 2000 
	// but eYear may have exceeded 2000 after adding the age meta
	if ($eYear < 2000) {

		// get random first and last names
		$record[$i]['first'] = $firstNames[array_rand($firstNames, 1)];
		$record[$i]['last']  = $lastNames[array_rand($lastNames, 1)];

		// create birth date and end date.
		// for simplicity, nobody gets to be born or die on the 29th, 30th, or 31st.
		$record[$i]['bdate'] = $bYear . '-' . sprintf('%02d', rand(1,12)) . '-' . sprintf('%02d', rand(1,28));
		$record[$i]['edate'] = $eYear . '-' . sprintf('%02d', rand(1,12)) . '-' . sprintf('%02d', rand(1,28));

		$i++;

	}
}

$filename = 'example_data.json';
$json = json_encode($record);
file_put_contents($filename, $json);

echo '<pre>' . $filename . ' has been created and filled with the data below:<br />';
print_r($record);

?>