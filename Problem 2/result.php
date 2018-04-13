<?php

// define headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ( isset($_GET['pid']) && $_GET['pid'] !== '' ) {
	$player_id = $_GET['pid'];

	include_once 'engine/dbConnect.php';
	include_once 'engine/whirledPeas.php';

	// get connection
	$database = new DbConnect();
	$conn = $database->getConn();
	 
	// create WhirledPeas obj
	$objWhirledPeas = new WhirledPeas($conn);
	 
	// get all players
	$result = $objWhirledPeas->getPlayerStats($player_id);

	// check if records are returned
	if($result->rowCount() > 0) {
		// records returned; process and build JSON
		while( $row = $result->fetch() ) { 
			$rtn = array(
				'player_id' 	=> $row['player_id'],
				'name' 			=> $row['name'],
				'credits' 		=> $row['credits'],
				'spin_count'	=> $row['spin_count'],
				'avg_return'	=> $row['avg_return']
			);
		}

		// send JSON response
		echo json_encode($rtn);

	} else {
		// no records returned.  Send JSON response that says as much.
		echo json_encode( array("message" => "No records found.") );
	}
} else {
	// no player_id was given
	echo json_encode( array("message" => "Requires player_id.") );
}



?>


