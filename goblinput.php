<?php

	#########################
	#      Goblinput™       #
	#                       # 
	#      Created by:      #
	#  Jake "Lakesta" Lake  #
	#                       #
	#  Goblinventions 2008  #
    #                       #
	#     Release Date:     #
	#       10/12/08        #
	#########################
	
	
############################################################################################################

	# 1. Initializ match data (create dom, add teams and match info)
	# 2. Loop through log and grab out first 10 players, add to GCS
	# 3. Loop through log and create actions in GCS (attacks, kills, plants, defuses, etc.)

############################################################################################################
	
	
######## BEGIN INITIALIZATION #########
function goblinput($team1, $team1tag, $team2, $team2tag, $map, $maxRounds, $notes, $eventType, $ename, $overtime, $otRounds, $totalhalves, $userid) {
		
	# Include Functions
	include_once ('Dom.class.php');
	
	$dom = new Dom();

	# Add instantiation variables to XML document	
	$dom->addMatchInfo($team1, $team1tag, $team2, $team2tag, $overtime, $otRounds, $maxRounds, $eventType, $ename, $map, $notes);
	
	# Create Players Array to keep track of # of Players
	$Players = array();
	
	# Keep track of total rounds for XML document
	$rounds = 1;

######## END INITIALIZATION #########
	
	
############################################################################################################	
	
	# Run this code for each half uploaded
	for($z=1; $z<=$totalhalves; $z++) {

		$dom->addHalf($z);
	
	######## BEGIN DATA PARSE FOR PLAYERS #########
	
		# Open first log file into memory and count its length
		$ourFileName = "logs/Goblinput_" . $userid . "-" . $z;

		$the_file = @file( $ourFileName );
		$the_file_rows = @count( $the_file );
	
		# Create count variable for entries
		$x = 0;
		
		# Only add players on the first log file call
		if ($z == 1) {
			
			# Pull the 10 Players out of the log and put them into the XML file
			while ((count($Players) < 10) && ($x <= $the_file_rows)) {
				
				# Split each line into 5 seperate elements of an array
				$values = explode(" ", $the_file[$x], 5); 
				
				# Split each line into 2 different elements based on where "STEAM_0:" lies at
				$CAKEWALK = explode("STEAM_0:", $the_file[$x]); # 
				
				# Increase entry count
				$x++;
				
				# Split CAKEWALK's second element into two based on where ">" lies at
				$side = explode(">", $CAKEWALK[1]);
				
				# Set sideLetter = The first letter in side's 2nd element (sideLetter corresponds to CT or T)
				$sideLetter = $side[1];
				$sideLetter = $sideLetter{1};
				
				# Set stringy = the useful data from the LOG file line split
				$stringy = $values[4];
				
				# Set first = the first letter in stringy
				$first = $stringy{0};
				
				# If first is = " then a Player Action has been found where we can pull a STEAMID from
				if ($first=='"') {
					
					# Split the useful data, stringy, into a two element array based on where "<STEAM_0:" lies at
					$steam = explode("<STEAM_0:", $stringy);
					
					# Set alias = the first element in Steam, the player's Name + serverID #
					$alias = $steam[0];
					
					# Set alias2 = alias without the serverID # (the ACTUAL alias used ingame)
					$len1 = strrchr($alias, "<");
					$len2 = strlen($len1);
					$alias2 = substr($alias, 1, -$len2);
					
					# Split the steamID into two elements based on where the first ":" lies at (currently entire STEAM_0: included)
					$split = explode(":", $steam[1]);
					
					# Finally split the STEAMID down to just the #'s we want by splitting the second part of split at the ">" position
					$final = explode(">", $split[1]);
					
					# $final[0] is now the STEAMID #'s, we need to make sure it is NOT empty so we'll store it in a new variable
					$steamCheck = $final[0];
					
					# Check to see if the SteamID already exists in the array of Players AND check to make sure the SteamID is NOT empty
					if (!in_array($final[0],$Players) && $steamCheck{0} != "") {
						
						# If not already in Players Array and not empty, add it to the Array at current position
						$Players[count($Players)] = $final[0];
						
						# Check the current side (T or CT) of the given player, if T, put player on Team1, else Team2
						if ($sideLetter ==  "T") {
							$playerTeam = 1;
						}
						else
							$playerTeam = 2;
							
						
						# Add the player to XML document
						$dom->addPlayer(htmlspecialchars($alias2), "", "us", $playerTeam, ${'team' . $playerTeam}, count($Players), $final[0]);
					} 
				}
			}
		}
	######## END DATA PARSE FOR PLAYERS #########	
		
	
############################################################################################################


	######## BEGIN DATA PARSE STATISTICS #########
	
		# DATA IS ALREADY OPEN FROM ABOVE!
				
		for ($x = 0; $x <= $the_file_rows; $x++) {
		
			# Split each line into 5 seperate elements of an array
			$values = explode(" ", $the_file[$x], 5); 
			
			# Set stringy = the useful data from the LOG file line split
			$stringy = $values[4];
			
			# Set first = the first letter in stringy
			$first = $stringy{0};
			
			
			# If first is = " then a Player Action has been found where we can pull a STEAMID from
			if ($first=='"') {
				
				# Split the useful data, stringy, into a two element array based on where "<STEAM_0:" lies at
				$steam = explode("<STEAM_0:", $stringy);
				
				# Set alias = the first element in Steam, the player's Name + serverID #
				$alias = $steam[0];
				
				# Set alias2 = alias without the serverID # (the ACTUAL alias used ingame)
				$alias2 = substr($alias, 1, -4);
				
				# Split the steamID into two elements based on where the first ":" lies at (currently entire STEAM_0: included)
				$split = explode(":", $steam[1]);
				
				# Finally split the STEAMID down to just the #'s we want by splitting the second part of split at the ">" position
				$final = explode(">", $split[1]);
				
				# Using part 2 of the useful data, split it again to find the ACTION performed in the line
				$action = explode('"',$steam[1]);
				$action[1] = trim($action[1]);
				
				switch ($action[1]) {
					
					# Create <attack> in XML document
					case "attacked":
						
						# Find the receiver
						$split2 = explode(":",$steam[2]);
						$final2 = explode(">", $split2[1]);
						$attackSteam2 = $final2[0];
						
						# Find the weapon used in the attack
						$ts2 = explode('"', $split2[1]);
						$attackWeaponUsed = $ts2[2];
						
						# Find the initiator
						$attackSteam1 = $final[0];
						
						# Find damage dealt
						$damageDealt = $ts2[4];
						
						# Add the attack to the XML document
						$dom->addAttack($attackSteam1, $attackSteam2, $damageDealt, $attackWeaponUsed);
						break;
						
					# Create <kill> in XML document	
					case "killed":
						
						# Find the receiver
						$split2 = explode(":",$steam[2]);
						$final2 = explode(">", $split2[1]);
						$attackSteam2 = $final2[0];
						
						# Find the weapon used in the attack
						$ts2 = explode('"', $split2[1]);
						$attackWeaponUsed = $ts2[2];
						
						# Find the initiator
						$attackSteam1 = $final[0];
						
						# Add the kill to the XML document
						$dom->addKill($attackSteam1, $attackSteam2, $attackWeaponUsed);
						break;
						
					# Check triggers to see which needs to be added to XML document	
					case "triggered":
						
						# Check trigger call
						$triggerID = $final[0];
						
						switch ($action[2]){
							
							# If Spawned with bomb, begin a new Round
							case "Spawned_With_The_Bomb": 
								
								# Create new round within Match
								$dom->addRound($rounds);
								
								# Increase round count
								$rounds++;
								break;
								
							case "Planted_The_Bomb": 
								
								# Find the initiator
								$attackSteam1 = $final[0];
								
								# Add the Plant to the XML document
								$dom->addPlant($attackSteam1);
								break;
								
							case "Defused_The_Bomb":
								# Find the initiator
								$attackSteam1 = $final[0];
								
								# Add the Plant to the XML document
								$dom->addDefuse($attackSteam1);
								break;
						}			
						break;
					
					
					case "committed suicide with":
						
						# Find the initiator
						$attackSteam1 = $final[0];
						
						# Add the suicide to the XML document
						$dom->addSuicide($attackSteam1);
						break;
				}
			}
			
			
			# Check how the round was completed (won)
			# T stands for Team and refers to round completion
			if ($first == "T") {
				
				# 6th character will either be C for Counter-Terrorist or T for Terrorist
				$second = $stringy{6}; 
				
				# If C for Counter-Terrorist...
				if ($second == "C") {
					
					# The 21st character will tell the way the round was won
					$third = $stringy{21};
					
					# CT's win by getting 5 kills
					if ($third == "C"){
						$dom->addWin(1, 'team2');
					}
					
					# CT's win by bomb defuse
					else if ($third == "B"){					
						$dom->addWin(2, 'team2');
					}
					
					# CT's win by default, time ran out
					else {					
						$dom->addWin(3, 'team2');
					}
				}
				
				# Else if T for Terrorist...
				else if ($second == "T"){
				
					# The 29th character will tell how the round was won
					$third = $stringy{29};
					
					# T's win by getting 5 kills
					if ($third == "e") {			
						$dom->addWin(4, 'team1');
					}
					
					# T's win by bomb explosion
					else {
						$dom->addWin(5, 'team1');
					}
				}
			}
		}
	
	
	######## END DATA PARSE STATISTICS #########
	}
	
############################################################################################################
		
		
	######## BEGIN XML GENERATION #########	
	
		$dom->save($userid);
		
	######## END XML GENERATION #########		
	}
	
############################################################################################################
	
?>