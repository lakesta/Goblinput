<?php

  	class Dom {
  		# Create private variables for everything possible
		private $_dom; # Reference to DOM in XML
		private $_pubDate; # Publication Date of GCS
		private $_matchDate; # Date of the Match
		private $_generator; # Goblinput Generator of GCS file
		private $_match; # Match node
		private $_ver; # goblinput Version
		private $_currentHalf; # current half gcs item
		private $_currentRound; # current round gcs item
		
		# Constructor - takes some arguments, defaults some, and leaves a lot out
		public function __construct() {

			# Instantiation Variables
			$this->_pubDate = date("n/j/y");
			$this->_matchDate = date("n/j/y");
			$this->_generator = "Goblinput";
			
			# Create DOM for XML Output
			$this->_dom = new DomDocument('1.0'); 
			
			# Create GCS for XML Output
			$this->_ver = $this->addElement($this->_dom, 'gcs');
			$this->_ver->setAttribute("version", "2.0");
			
			# Add match root node to XML Output
			$this->_match = $this->addElement($this->_ver, 'match');
		}

		# Save XML
		public function save($userid){
			# Set the formatOutput attribute of domDocument to True to make the XML nicely styled
			$this->_dom->formatOutput = true;
		
			# Save XML 
			$this->_dom->save("gcs/$userid".'.gcs');
		}

		# XML function short hand
		public function addElement($root, $element){
			return $root->appendChild($this->_dom->createElement($element));
		}

		# XML function short hand
		public function createTextNode($root, $node){
			return $root->appendChild($this->_dom->createTextNode($node));
		}

		# XML function short hand combo
		public function addElementTextNode($root, $element, $node) {
			$dom_append = $this->addElement($root, $element);
			return $this->createTextNode($dom_append, $node);
		}

		# Add matchInfo to the DOM for XML document.
		public function addMatchInfo($team1, $team1tag, $team2, $team2tag, $overtime, $otRounds, $maxRounds, $eventType, $eventName, $map, $notes) {
			$this->addElementTextNode($this->_match, 'team1', $team1);
			$this->addElementTextNode($this->_match, 'team1tag', $team1tag);
			$this->addElementTextNode($this->_match, 'team2', $team2);
			$this->addElementTextNode($this->_match, 'team2tag', $team2tag);
			$this->addElementTextNode($this->_match, 'pubDate', $this->_pubDate);
			$this->addElementTextNode($this->_match, 'matchDate', $this->_matchDate);
			$this->addElementTextNode($this->_match, 'generator', $this->_generator);
			$this->addElementTextNode($this->_match, 'overtime', $overtime);
			$this->addElementTextNode($this->_match, 'eventType', $eventType);
			$this->addElementTextNode($this->_match, 'eventName', $eventName);
			$this->addElementTextNode($this->_match, 'map', $map);
			$this->addElementTextNode($this->_match, 'notes', $notes);
		}

		# Add player to dom with alias, real name, country, team (ct/t), team name, player id, steam id
		public function addPlayer($alias, $name, $country, $team, $teamName, $id, $steamID) {
			$player = $this->addElement($this->_match, 'player');
			$this->addElementTextNode($player, 'alias', $alias);
			$this->addElementTextNode($player, 'name', $name);
			$this->addElementTextNode($player, 'country', $country);
			$this->addElementTextNode($player, 'team', $team);
			$this->addElementTextNode($player, 'teamName', $teamName);
			$this->addElementTextNode($player, 'id', $id);
			$this->addElementTextNode($player, 'steamID', $steamID);
		}

		public function addHalf($half) {
			$this->_currentHalf = $this->addElement($this->_match, 'half');
			$this->_currentHalf->setAttribute("id", $half);
		}

		# Add round to dom
		public function addRound($rounds) {
			$this->_currentRound = $this->addElement($this->_currentHalf, 'round');
			$this->_currentRound->setAttribute("id", $rounds);
		}

		# Add attacks to dom with attacker, receiver, damage dealt, and gun
		public function addAttack($initiator, $receiver, $damage, $gun) {
			$attack = $this->addElement($this->_currentRound, 'attack');
			$this->addElementTextNode($attack, 'initiator', $initiator);
			$this->addElementTextNode($attack, 'receiver', $receiver);
			$this->addElementTextNode($attack, 'damage', $damage);
			$this->addElementTextNode($attack, 'gun', $gun);
		}

		# Add kills to dom with killer, receiver, and gun
		public function addKill($initiator, $receiver, $gun) {
			$kill = $this->addElement($this->_currentRound, 'kill');
			$this->addElementTextNode($kill, 'initiator', $initiator);
			$this->addElementTextNode($kill, 'receiver', $receiver);
			$this->addElementTextNode($kill, 'gun', $gun);
		}

		# Add suicides to dom with suicider
		public function addSuicide($initiator) {
	 		$suicide = $this->addElement($this->_currentRound, 'suicide');
			$this->addElementTextNode($suicide, 'initiator', $initiator);
		}

		# Add bomb plants to dom with planter
		public function addPlant($initiator) {
			$plant = $this->addElement($this->_currentRound, 'bombPlant');
			$this->addElementTextNode($plant, 'initiator', $initiator);
		}

		# Add defuses to dom with defuser
		public function addDefuse($initiator) {
			$defuse = $this->addElement($this->_currentRound, 'bombDefuse');
			$this->addElementTextNode($defuse, 'initiator', $initiator);
		}

		# Add Round Wins to dom
		public function addWin($winType, $winTeam) {

			# Create new win within round
			$win = $this->addElement($this->_currentRound, 'win');
					
			# Set win id to the win Type
			# [1 = CT frag, 2 = CT defuse, 3 = CT timer, 4 = T frag, 5 = T bomb]
			$win->setAttribute("id", $winType);
			
			# Set win team id
			$this->createTextNode($win, $winTeam);

		}		

  	}

?>