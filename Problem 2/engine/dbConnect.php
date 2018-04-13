<?php

class DbConnect {
	private $host = 'localhost';
	private $schm = 'scientific_games';
	private $user = 'usrSpinApp';
	private $pass = 'einsZweiDreiVier';

	public $conn;

	public function getConn() {
		$this->conn = null;

		try {
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->schm, $this->user, $this->pass);
			$this->conn->exec("set names utf8");
		} catch(PDOException $e){
			echo "Could no connect.  Error: " . $e->getMessage();
		}

		return $this->conn;

	}

}

?>


