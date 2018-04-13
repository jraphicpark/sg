Problem 1 READ ME

Author: Robert Sims - Senior Software Developer Candidate

findMostLivingByYear.php:
	As the filename describes, finds the year with the most living individuals.  
	Imports data from example_data.json.

createExampleData.php:
	Creates 1000 rows of new user data (firstname, lastname, bdate, edate) and
	overwrites example_data.json if it exists.  Names are randomly drawn from a 
	list of names.  Birth date is randomly constructed using php rand().  An age 
	is determined for meta use, and then age is added to the birth year and 
	constructed with random day and month values to construct the end date.

example_data.json:
	Data file. Necessary for running findMostLiveingByYear.php
