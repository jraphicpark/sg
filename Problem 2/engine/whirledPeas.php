<?php

// Your code should validate the following request data: 
//          hash, coins won, coins bet, player ID

// jraphicpark: awkwardAardvark
// wowsims: colludingCockatoo


class WhirledPeas {

	private $conn;

	public function __construct($db){
		$this->conn = $db;
	}

	public function getAllPlayers() {
		// gets all player's non-private information
		$query 	= 'select player_id, name, credits from players;';
		$stmt 	= $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	public function verifyPassword($_id, $_pass) {
		// verify password
		$rtn = FALSE;

		if ($this->isPlayer($_id)) {
			$hash = $this->getPlayerHash($_id);

			if (password_verify($_pass, $hash)) {
			    $rtn = TRUE;
			}

		}

		return $rtn;
	}

	public function getCredits($_id) {
		// get current credit value

		$rtn = FALSE;

		if ($this->isPlayer($_id)) {
			$query = 'select credits from players where player_id = ' . $_id . ';';

			$stmt = $this->conn->prepare($query);
			$stmt->execute();

			$row = $stmt->fetch();

			$rtn = $row['credits'];
		}

		return $rtn;		
	}


	public function setCredits($_id, $_int) {
		// replace the current credit value

		$rtn = FALSE;

		if ($this->isPlayer($_id)) {
			if ( is_int($_int) ) {
				$query = 'update players set credits = :VALUE where player_id = ' . $_id . ';';
				$stmt = $this->conn->prepare($query);

				$stmt->bindParam(":VALUE", $_int);

				if ( $stmt->execute() ) {
					$rtn = TRUE;
				}
			}
		}

		return $rtn;
		
	}

	public function addCredits($_id, $_int) {
		// add credits to current credit value

		$rtn = FALSE;

		if ($this->isPlayer($_id)) {
			if ( is_int($_int) ) {
				$query = 'update players set credits = (credits + :VALUE) where player_id = ' . $_id . ';';
				$stmt = $this->conn->prepare($query);

				$stmt->bindParam(":VALUE", $_int);

				if ( $stmt->execute() ) {
					$rtn = TRUE;
				}
			}
		}
		return $rtn;
		
	}

	public function subtractCredits($_id, $_int) {
		// subtract credits to current credit value

		$rtn = FALSE;
		if ($this->isPlayer($_id)) {
			if ( is_int($_int) ) {
				$query = 'update players set credits = (credits - :VALUE) where player_id = ' . $_id . ';';
				$stmt = $this->conn->prepare($query);

				$stmt->bindParam(":VALUE", $_int);

				if ( $stmt->execute() ) {
					$rtn = TRUE;
				}
			}
		}

		return $rtn;
		
	}

	public function addSpins($_id, $_int) {
		// add credits to current credit value

		$rtn = FALSE;

		if ($this->isPlayer($_id)) {
			if ( is_int($_int) ) {
				$query = 'update players set spins = (spins + :VALUE) where player_id = ' . $_id . ';';
				$stmt = $this->conn->prepare($query);

				$stmt->bindParam(":VALUE", $_int);

				if ( $stmt->execute() ) {
					$rtn = TRUE;
				}
			}
		}

		return $rtn;
		
	}


	public function insertNewSpin($_ary) {
		// insert new spin record
		$rtn = FALSE;

		if ($this->isPlayer($_ary['player_id'])) {

			$query = 'INSERT INTO spins (
				player_ID, prediction, outcome, wager, payout
			) VALUES (
				:PLAYER_ID, :PREDICTION, :OUTCOME, :WAGER, :PAYOUT
			);';

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(":PLAYER_ID", 	$_ary['player_id']);
			$stmt->bindParam(":PREDICTION", $_ary['prediction']);
			$stmt->bindParam(":OUTCOME", 	$_ary['outcome']);
			$stmt->bindParam(":WAGER", 		$_ary['wager']);
			$stmt->bindParam(":PAYOUT", 	$_ary['payout']);

			if ( $stmt->execute() ) {
				$rtn = TRUE;
			}
		}

		return $rtn;
	}

	public function isPlayer($_id) {
		// Checks if player_id is found in players table

		$rtn = FALSE;

		$query 	=  'select player_id from players where player_id = ' . $_id . ';';

		$stmt 	= $this->conn->prepare($query);
		$stmt->execute();

		
		if($stmt->rowCount() == 1) {
			$rtn = TRUE;
		}

		return $rtn;
	}

	public function getPlayerStats($_id) {
		// Gets player stats:  player_id, name, credits, spin_count, avg_return
		
		$rtn = FALSE;

		if ($this->isPlayer($_id)) {

			$query 	=  'select distinct
							p.player_id, 
							p.name, 
							p.credits, 
							count(s.spin_id) as spin_count,
							avg(s.payout) as avg_return

						from 
							players p,
							spins s

						where
							p.player_id = ' . $_id . '
							and s.player_id = p.player_id;';

			$stmt 	= $this->conn->prepare($query);
			$stmt->execute();
			$rtn = $stmt;
		}

		return $rtn;
	}

	public function playerStatsJSON($_id) {
		// returns JSON of getPlayerStates()

		$result = $this->getPlayerStats($_id);
		 
		// check if records are returned
		if($result->rowCount() > 0) {
			// records returned; process and build JSON
			while( $row = $result->fetch() ) { 
				$aryPlayer = array(
					'player_id' 	=> $row['player_id'],
					'name' 			=> $row['name'],
					'credits' 		=> $row['credits'],
					'spin_count' 	=> $row['spin_count'],
					'avg_return' 	=> $row['avg_return'],
				);
			}

			// prepare JSON response
			$rtn = json_encode($aryPlayer);

		} else {
			// no records returned.  Prepare JSON response that says as much.
			$rtn = json_encode( array("message" => "No players found.") );
		}

		return json_decode($rtn);
	}

	private function getPlayerHash($_id) {
		// PRIVATE!  Get player hash value (labeled salt)
		$rtn = FALSE;

		if ($this->isPlayer($_id)) {

			$query 	=  'select salt from players where player_id = ' . $_id . ';';

			$stmt 	= $this->conn->prepare($query);
			$stmt->execute();

			$row = $stmt->fetch();

			$rtn = $row['salt'];
		}

		return $rtn;
	}

}




?>



