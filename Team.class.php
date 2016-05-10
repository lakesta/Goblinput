<?php

	require_once('Player.class.php');
	
	class Team {
		
		# Create all private vars that could possibly be needed
		private $_players; # Array, stores player's Player objects
		private $_name; # Team name
		private $_teamID; # Team ID (1 or 2)
		private $_score; # Score throughout the game
		private $_tag; # Tag - used to remove tag from player alias for print out
		
		private $_roundsWon; # Array, stores all round ID's for rounds won
		private $_roundsLost; # Array, stores all round ID's for rounds lost
		private $_oFrags; # Offensive side frags
		private $_dFrags; # Defensive side frags
		private $_frags; # Frags (kills)
		private $_deaths; # Deaths 
		private $_assists; # Assists (When someone has injured a player > 50hp and someone ELSE on their team gets the kill)
		private $_errors; # Errors (Suicide or team frag)
		private $_entryFrags; # Entry frags (1st frag of the round)
		private $_entryDeaths; # Entry deaths (1st death of the round)
		private $_damage; # Damage
		private $_plants; # Array, stores roundID's with plant
		private $_plantWins; # Array, stores roundID's that a bomb plant won the round
		private $_defuseWins; # Array, stores roundID's that a defuse won the round
		private $_fragRounds; # Array, stores roundID's with frag
		
		# Constructor, takes 4 arguments and defaults the score
		public function __construct($name, $tag, $teamID, $score=0){
			$this->_name = $name;
			$this->_teamID = $teamID;
			$this->_tag = $tag;
			$this->_score = $score;
		}
		
		
		# Public setters
		function addPlayer(Player $player) {
			$this->_players[] = $player;
		}
		
		function setTag($tag){
			$this->_tag = $tag;
		}
		
		function setScore($score){
			$this->_score = $score;
		}
		
		
		
		# Auto increasing setters
		function addScore(){
			$this->_score++;
		}
		
		function addRoundsWon($round) {
			$this->_roundsWon[] = $round;
		}
		
		function addRoundsLost($round) {
			$this->_roundsLost[] = $round;
		}
		
		function addDFrag(){
			$this->_dFrags++;
		}
		
		function addOFrag(){
			$this->_oFrags++;
		}
		
		function addFrag(){
			$this->_frags++;
		}
		
		function addDeath(){
			$this->_deaths++;
		}
		
		function addAssist(){
			$this->_assist++;
		}
		
		function addError(){
			$this->_errors++;
		}
		
		function addEntryFrag(){
			$this->_entryFrags++;
		}
		
		function addEntryDeath(){
			$this->_entryDeaths++;
		}
	
		function addDamage($damage){
			$this->_damage += $damage;
		}

		function addFragRound($round){
			$this->_fragRounds[] = $round;
		}
		
		function addPlant($round){
			$this->_plants[] = $round;
		}
				
		function addPlantWin($round){
			$this->_plantWins[] = $round;
		}
		
		function addDefuseWin($round){
			$this->_defuseWins[] = $round;
		}
		
		
		
		
		# Public getters
		function getName(){
			return $this->_name;
		}
		
		function getTeamID(){
			return $this->_teamID;
		}
				
		function getPlayers(){
			return $this->_players;
		}
		
		function getTag(){
			return $this->_tag;
		}
				
		function getScore(){
			return $this->_score;
		}
		
		function getRoundsWon() {
			if (is_null($this->_roundsWon))
				return array();
			else
				return $this->_roundsWon;
		}
		
		function getRoundsLost() {
			if (is_null($this->_roundsLost))
				return array();
			else
				return $this->_roundsLost;
		}
		
		function getOFrags(){
			return $this->_oFrags;
		}
		
		function getDFrags(){
			return $this->_dFrags;
		}
		
		function getFrags(){
			return $this->_frags;
		}
		
		function getDeaths(){
			return $this->_deaths;
		}
		
		function getAssists(){
			return $this->_assist;
		}
		
		function getErrors(){
			return $this->_errors;
		}
		
		function getEntryFrags(){
			return $this->_entryFrags;
		}
		
		function getEntryDeaths(){
			return $this->_entryDeaths;
		}
		
		function getDamage(){
			return $this->_damage;
		}

		function getFragRounds(){
			if (is_null($this->_fragRounds))
				return array();
			else
				return $this->_fragRounds;
		}
		
		function getPlants(){
			if (is_null($this->_plants))
				return array();
			else
				return $this->_plants;
		}
		
		function getPlantWins(){
			if (is_null($this->_plantWins))
				return array();
			else
				return $this->_plantWins;
		}
		
		function getDefuseWins(){
			if (is_null($this->_defuseWins))
				return array();
			else
				return $this->_defuseWins;
		}

	}

?>