<?php

# Grab includes - Player Class and Team Class
require_once('Player.class.php');
require_once('Team.class.php');
	
class Utility {

	private $_team1Players;
	private $_team2Players;
	private $_players;

	# Constructor - takes some arguments, defaults some, and leaves a lot out
	public function __construct($team1, $team2) {
		# Retrieve players on team1 and store as Array
		$this->_team1Players = $team1->getPlayers();
		$this->_team2Players = $team2->getPlayers();
		$this->_players = array();
		foreach ($this->_team1Players as $player) {
			$this->_players[(int)$player->getId()] = (int)$player->getSteamID();
		}
		foreach ($this->_team2Players as $player) {
			$this->_players[(int)$player->getId()] = (int)$player->getSteamID();
		}
	}
	
	################################################
	# Function: playerSearch
	#	Purpose-> to find player based on steamID
	#
	# Parameters:
	#	Player -> SteamID of player being queried
	################################################	
	
	public function playerSearch($player){
		$x = array_search((int)$player, $this->_players);
		return (int)$x;
	}
	
	
	
	################################################
	# Function: getScore
	#	Purpose   -> find out the score of the match
	#
	# Parameters:
	#	GCS       -> DOM item from simple XML call
	# 	Team1     -> Team object for first team
	#	Team2     -> Team object for second team
	################################################
		
	public static function getScore($gcs, &$team1, &$team2){
		
		# Setup some default variables
		# numOfOT keeps track of which overtime it currently is
		# switch keeps track of what half it is and when to switch scoring from T to CT
		$numOfOT = 1;
		$switch = FALSE;
		$half = 0;
		
		# for each half in the match...
		foreach ($gcs->match->half as $halves) {
			$half++;
			if ($half % 2 == 0 && $half > 1) { 
				$switch = TRUE;
			}

			# for each round in the match
			foreach ($halves->round as $round) {		

				# Set win id to the win Type
				# [1 = CT frag, 2 = CT defuse, 3 = CT timer, 4 = T frag, 5 = T bomb]
				foreach ($round->win as $win) {
					switch ($win[id]) {
						case 1: 
							if (!$switch)
								$team2->addScore();
							else
								$team1->addScore();
							break;
						case 2:
							if (!$switch)
								$team2->addScore();
							else
								$team1->addScore();
							break;
						case 3:
							if (!$switch)
								$team2->addScore();
							else
								$team1->addScore();
							break;
						case 4:
							if (!$switch)
								$team1->addScore();
							else
								$team2->addScore();
							break;
						case 5:
							if (!$switch)
								$team1->addScore();
							else
								$team2->addScore();
							break;
					}
				}
			}
		}
	}
	
	# Function to count how many rounds had 2Frags, 3Frags, 4Frags, or 5Frags
	public static function countFrags($array, $int) {
		$count = 0;
		for ($x=0; $x <= max($array); $x++){
			if (count(array_intersect($array, array($x))) == $int)
				$count++;
		}
		return $count;
	}
	
	# Function to calculate the "Score" for a team
	public static function getTeamScore($team) {
		#   * i.    Frags +2
		#	* ii.   Assists +1
		#	* iii.  Errors -1
		#	* iv.   First Frag +0.5
		#	* v.    First Death -0.5
		#	* vi.   Bomb Plants +0.5
		#	* vii.  Successful Bombs +0.5
		#	* viii. Bomb Defuses +1
		
		$frags = (integer)$team->getFrags();
		$assists = (integer)$team->getAssists();
		$errors = (integer)$team->getErrors();
		$firstfrags = (integer)$team->getEntryFrags();
		$firstdeaths = (integer)$team->getEntryDeaths();
		$plants = count($team->getPlants());
		$plantWins = count($team->getPlantWins());
		$defuseWins = count($team->getDefuseWins());
		
		$score = ($frags * 2) + ($assists) - ($errors) + ($firstfrags * .5) - ($firstdeaths * .5) + ($plants * .5) + ($plantWins * .5) + ($defuseWins * .5);
		
		return $score;
	}
	
	# Function to calculate the "Score" for a player
	public static function getPlayerScore($player, $team) {
		#   * i.    Frags +2
		#	* ii.   Assists +1
		#	* iii.  Errors -1
		#	* iv.   First Frag +0.5
		#	* v.    First Death -0.5
		#	* vi.   Bomb Plants +0.5
		#	* vii.  Successful Bombs +0.5
		#	* viii. Bomb Defuses +1
		
		$frags = (integer)$player->getFrags();
		$assists = (integer)$player->getAssists();
		$errors = (integer)$player->getErrors();
		$firstfrags = (integer)$player->getEntryFrags();
		$firstdeaths = (integer)$player->getEntryDeaths();
		$plants = count($player->getPlants());
		$plantWins = count(array_intersect($player->getPlants(),$team->getPlantWins()));
		$defuseWins = count(array_intersect($player->getDefuses(),$team->getDefuseWins()));
		
		$score = ($frags * 2) + ($assists) - ($errors) + ($firstfrags * .5) - ($firstdeaths * .5) + ($plants * .5) + ($plantWins * .5) + ($defuseWins * .5);
		
		return $score;
	}

	# Allow inserting values into array at a specific position since our keys are numbers not strings
	public static function insert_in_array_pos($array, $pos, $value) {
		$result = array_merge(array_slice($array, 0 , $pos), array($value), array_slice($array,  $pos));
		return $result;
	}
}
		
?>