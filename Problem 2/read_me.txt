Problem 2 READ ME

Author: Robert Sims - Senior Software Developer Candidate

Script for creating MySQL data base can be found in /mysql.

Core classes are in /engine.
	- dbConnect.php is a basic class to easy database connection

	- whirledPeas.php is the work horse for the demonstration. 
		Methods included are:
		- public getAllPlayers()
		- public verifyPassword()
		- public getCredits()
		- public setCredits()
		- public addCredits()
		- public subtractCredits()
		- public addSpins()
		- public insertNewSpin()
		- public isPlayer()
		- public getPlayerStats()
		- public playerStatsJSON()
		- private getPlayerHash()

/index.php is an extremely basic, self-referencing game demonstration that excercises the methods found in class WhirledPeas.

/result.php outputs JSON formatted file based on Player ID, passed as $_GET['pid'].


