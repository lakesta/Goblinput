<?php

  	class Player {
		
		# Create private variables for everything possible
		private $_alias; # Alias stored with the team tag, ie.) 3D|Moto
		private $_name; # For future use to store a players name, ie.) Dave Moto
		private $_teamName; # Team Name, ie.) 3D
		private $_country; # Country of origin, ie.) US
		private $_id; # unique id number for calculation purposes (1-10)
		private $_steamID; # SteamID
		private $_team; # Which team they are on in this game, relates to Team's TeamID (1 or 2)
		private $_frags; # Frags (kills)
		private $_deaths; # Deaths
		
		private $_oFrags; # Offensive Frags (Terrorist)
		private $_dFrags; # Defensive Frags (Counter-Terrorist)
		private $_assists; # Assists (When someone has injured a player > 50hp and someone ELSE on their team gets the kill)
		private $_errors; # Errors (Suicide or team frag)
		private $_entryFrags; # Entry frags (1st frag of the round)
		private $_entryDeaths; # Entry deaths (1st death of the round)
		private $_damage; # Damage
		private $_plants; # Array, stores roundID's with plant
		private $_defuses; # Array, stores roundID's with defuse
		private $_fragRounds; # Array, stores roundID's with frag

		# Stores Frags, Deaths, Assists, Errors, Entry Frags, Entry Deaths, Damage, Damage/Weapon, Frags/Weapon, fragRounds
		private $_offensive; # Store all data related to Terrorist side
		private $_defensive; # Store all data related to Counter-Terrorist side
		
		# Constructor - takes some arguments, defaults some, and leaves a lot out
		public function __construct($alias, $name, $id, $steamID, $team, $frags=0, $deaths=0, $teamName="", $country="us") {
			$this->_alias = $alias;
			$this->_name = $name;
			$this->_id = $id;
			$this->_steamID = $steamID;
			$this->_team = $team;
			$this->_frags = $frags;
			$this->_deaths = $deaths;
			$this->_teamName = $teamName;
			$this->_country = $country;
			$this->_offensive = array(
									'frags'=>0,
									'deaths'=>0,
									'assists'=>0,
									'errors'=>0,
									'entryFrags'=>0,
									'entryDeaths'=>0,
									'damage'=>0,
									'damage_weapon'=>array(
										'knife' => 0,
										'glock18' => 0,
										'usp' => 0,
										'deagle' => 0,
										'mp5navy' => 0,
										'galil' => 0,
										'famas' => 0,
										'ak47' => 0,
										'm4a1' => 0,
										'awp' => 0
									),
									'frag_weapon'=>array(
										'knife' => 0,
										'glock18' => 0,
										'usp' => 0,
										'deagle' => 0,
										'mp5navy' => 0,
										'galil' => 0,
										'famas' => 0,
										'ak47' => 0,
										'm4a1' => 0,
										'awp' => 0
									),
									'frag_rounds'=>array(),
								);
			$this->_defensive = array(
									'frags'=>0,
									'deaths'=>0,
									'assists'=>0,
									'errors'=>0,
									'entryFrags'=>0,
									'entryDeaths'=>0,
									'damage'=>0,
									'damage_weapon'=>array(
										'knife' => 0,
										'glock18' => 0,
										'usp' => 0,
										'deagle' => 0,
										'mp5navy' => 0,
										'galil' => 0,
										'famas' => 0,
										'ak47' => 0,
										'm4a1' => 0,
										'awp' => 0
									),
									'frag_weapon'=>array(
										'knife' => 0,
										'glock18' => 0,
										'usp' => 0,
										'deagle' => 0,
										'mp5navy' => 0,
										'galil' => 0,
										'famas' => 0,
										'ak47' => 0,
										'm4a1' => 0,
										'awp' => 0
									),
									'frag_rounds'=>array(),
								);
		}

		function addOffensive($stat) {
			foreach ($stat as $key => $value) {
				switch ($key) {
					case 'frag' :
						$this->_offensive['frags']++;
					break ;
					case 'death' :
						$this->_offensive['deaths']++;
					break ;
					case 'assist' :
						$this->_offensive['assists']++;
					break ;
					case 'error' :
						$this->_offensive['errors']++;
					break ;
					case 'entryFrag' :
						$this->_offensive['entryFrags']++;
					break ;
					case 'entryDeath' :
						$this->_offensive['entryDeaths']++;
					break ;
					case 'damage' :
						$this->_offensive['damage'] += $value;
					break ;
					case 'damage_weapon' :
						$this->_offensive['damage_weapon'][(string)$value[0]] += $value[1];
					break ;
					case 'frag_weapon' :
						$this->_offensive['frag_weapon'][(string)$value]++;
					break ;
					case 'frag_round' :
						$this->_offensive['frag_rounds'][] = $value;
					break ;
				}
			}
		}

		function addDefensive($stat) {
			foreach ($stat as $key => $value) {
				switch ($key) {
					case 'frag' :
						$this->_defensive['frags']++;
					break ;
					case 'death' :
						$this->_defensive['deaths']++;
					break ;
					case 'assist' :
						$this->_defensive['assists']++;
					break ;
					case 'error' :
						$this->_defensive['errors']++;
					break ;
					case 'entryFrag' :
						$this->_defensive['entryFrags']++;
					break ;
					case 'entryDeath' :
						$this->_defensive['entryDeaths']++;
					break ;
					case 'damage' :
						$this->_defensive['damage'] += $value;
					break ;
					case 'damage_weapon' :
						$this->_defensive['damage_weapon'][(string)$value[0]] += $value[1];
					break ;
					case 'frag_weapon' :
						$this->_defensive['frag_weapon'][(string)$value]++;
					break ;
					case 'frag_round' :
						$this->_defensive['frag_rounds'][] = $value;
					break ;
				}
			}
		}
		
		# Auto Setters
		function addFrag(){
			$this->_frags++;
		}
		
		function addDeath(){
			$this->_deaths++;
		}
		
		function addDFrag(){
			$this->_dFrags++;
		}
		
		function addOFrag(){
			$this->_oFrags++;
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
		
		function addPlant($round){
			$this->_plants[] = $round;
		}
		
		function addDefuse($round){
			$this->_defuses[] = $round;
		}

		function addFragRound($round){
			$this->_fragRounds[] = $round;
		}
		
		function loseFrag(){
			$this->_frags--;
		}




		
		# Setters
		function setFrags($frags){
			$this->_frags = $frags;
		}
		
		function setDeaths($deaths){
			$this->_deaths = $deaths;
		}
		
		
		
		
		
		# Getters
		function getOFrags(){
			return $this->_oFrags;
		}
		
		function getDFrags(){
			return $this->_dFrags;
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

		function getAlias(){
			return $this->_alias;
		}
		
		function getName(){
			return $this->_name;
		}
		
		function getId(){
			return $this->_id;
		}
		
		function getSteamID(){
			return $this->_steamID;
		}
		
		function getTeamName(){
			return $this->_teamName;
		}
		
		function getCountry(){
			return $this->_country;
		}
		
		function getTeam(){
			return $this->_team;
		}
		
		function getFrags(){
			return $this->_frags;
		}
		
		function getDeaths(){
			return $this->_deaths;
		}
		
		function getPlants(){
			if (is_null($this->_plants))
				return array();
			else
				return $this->_plants;
		}
		
		function getDefuses(){
			if (is_null($this->_defuses))
				return array();
			else
				return $this->_defuses;
		}

		function getOffensive(){
			return $this->_offensive;
		}

		function getDefensive(){
			return $this->_defensive;
		}
	}
	
?>