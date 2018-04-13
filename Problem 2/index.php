<?php 

include_once 'engine/dbConnect.php';
include_once 'engine/whirledPeas.php';

// get connection
$database = new DbConnect();
$conn = $database->getConn();
 
// create WhirledPeas obj
$objWhirledPeas = new WhirledPeas($conn);

// declare vars 
$msg 		= '';
$prediction = '';
$outcome 	= '';
$modifier 	= '';
$payout 	= '';
$d1			= '';
$d2			= '';

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
	$err = FALSE;

	$player_id = $_POST['player_id'];

	if ( !intval($_POST['prediction']) || ((int)$_POST['prediction'] < 1 || (int)$_POST['prediction'] > 12)  ) {
		$err = TRUE;
		$msg .= '<li>Your predicted dice value must be a number from 2 through 12</li>';
	} else {
		$prediction = (int)$_POST['prediction'];
	}

	$credits = $objWhirledPeas->getCredits($player_id);

	if ( !intval($_POST['wager']) || ((int)$_POST['wager'] < 1 || (int)$_POST['wager'] > $credits) ) {
		$err = TRUE;
		$msg .= '<li>Your wager must be greater than 0 and no more than your available credits.</li>';
	} else {
		$wager = (int)$_POST['wager'];
	}

	if ( !$objWhirledPeas->verifyPassword($player_id, $_POST['assumed_password']) ) {
		$err = TRUE;
		$msg = 'The user account was not verified.';

	}

	if ($err == FALSE) {
		// subtract initial wager
		$objWhirledPeas->subtractCredits($player_id, $wager);

		// roll the dice (spin)
		$d1 = rand(1,6);
		$d2 = rand(1,6);
		$outcome = $d1 + $d2;

		// evaluate outcome
		$modifier = 0;
		if ( $prediction == $outcome ) {

			// winner matrix
			$aryDouble = array(5,6,8,9);
			$aryTriple = array(3,4,10,11);
			$aryQuadruple = array(2,12);

			if ( in_array($prediction, $aryDouble) ) {
				$modifier = 2;
			} elseif ( in_array($prediction, $aryTriple) ) {
				$modifier = 3;
			} elseif ( in_array($prediction, $aryQuadruple) ) {
				$modifier = 4;
			}

			$payout = $wager * $modifier;

			$objWhirledPeas->addCredits($player_id, $payout);

		} else {
			$payout = 0;
		}

		// prep data for spin table update
		$aryData = array(
			'player_id'		=> $player_id,
			'prediction'	=> $prediction,
			'outcome'		=> $outcome,
			'wager'			=> $wager,
			'payout'		=> $payout
		);

		// update spin table
		$objWhirledPeas->insertNewSpin($aryData);
	}


} else {
	$player_id = 1;
	if (isset($_GET['pid']) ) {
		if ( $objWhirledPeas->isPlayer($_GET['pid']) ) {
			$player_id = $_GET['pid'];
		}
	}
}

// some hard coding for demonstration only.  
// not at all practical or secure
switch ($player_id) {
	case 1:
		$password = 'awkwardAardvark';
		break;
	case 2:
		$password = 'colludingCockatoo';
		break;
	default: 
		echo "demo password is empty so the demo can't be run.";
		exit;
}

$player_stats = $objWhirledPeas->playerStatsJSON($player_id);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta charset="utf-8">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="X-UA-Compatible" content="IE=9">
	<title>Robert Sims - Senior Software Developer Candidate</title>

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="css/styles.css?r=<?= rand(10000, 99999); ?>" />
	
</head>
<body>

<div class="content">
	<?php
		if ($msg !== '') {
			echo '<div class="message">' . $msg .  '</div>';
		}
	?>

	<h3>Whirled Peas</h3>
	<p>The dice rolling game where 7 always loses.  Yes, always.</p>
	<p>5, 6, 8, 9 pays double.</p>
	<p>3, 4, 10, 11 pays triple.</p>
	<p>2 and 12 pays quadruple.</p>

	<div class="game_details">
		<div id="name" class="detail_block">
			<div class="label">Player: </div>
			<div class="value"><?= $player_stats->name ?></div>
		</div>

		<div id="score" class="detail_block">
			<div class="label">Credits: </div>
			<div class="value"><?= $player_stats->credits ?></div>
		</div>

		<div id="lifetime_spins" class="detail_block">
			<div class="label">Lifetime Rolls: </div>
			<div class="value"><?= $player_stats->spin_count ?></div>
		</div>

		<div id="avg_returns" class="detail_block">
			<div class="label">Avg. Return: </div>
			<div class="value"><?= $player_stats->avg_return ?></div>
		</div>

	</div>

	<form action="index.php" method="POST">

		<div class="node_row">
			Predict your dice total: <input type="number" name="prediction" value="<?= $prediction ?>"> (2 - 12)
		</div>

		<div class="node_row">
			Bet coins: <input type="number" name="wager" value="<?= $wager ?>">
		</div>

		<input type="hidden" name="player_id" value="<?= $player_id ?>">
		<input type="hidden" name="assumed_password" value="<?= $password ?>">
		
		<input type="submit" value="ROLL!!!">
	</form> 

<div class="roll_results">
	Meta Information<br />
	- prediction: 	<?= $prediction ?><br />
	- die one:		<?= $d1 ?><br />
	- die two:		<?= $d2 ?><br />
	- roll: 		<?= $outcome ?><br />
	- modifier: 	<?= $modifier ?><br />
	- payout: 		<?= $payout ?><br />
</div>


</div>

<div class="content">
	JSON response example.
	<iframe src="result.php?pid=<?= $player_id ?>"></iframe>
</div>

<div class="content">
	Possible users:<br />
	<?php
		$result = $objWhirledPeas->getAllPlayers();
		if($result->rowCount() > 0) {
			// records returned; 
			while( $row = $result->fetch() ) { 
				echo '<li><a href="index.php?pid=' . $row['player_id'] . '">' . $row['name'] . '</a></li>' . PHP_EOL;
			}

		} else {
			echo 'No users found.';
		}

	?>
</div>

</body>
</html>
