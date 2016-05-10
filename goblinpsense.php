<?php


	#########################
	#      Goblincast™      #
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
	
	# Grab class files for Team and Player
	require_once ('Team.class.php');
	require_once ('Player.class.php');
	require_once ('Utility.class.php');
	
	######## BEGIN INITIALIZATION #########
	function goblinpsense($userid) {
		
		######## BEGIN DATA PARSE FOR PLAYERS #########
	
		# Open gcs file into memory using simpleXML library
		$gcs = simplexml_load_file('gcs/' . $userid . '.gcs');
		
		# Create variable to store whether teams are switching sides (T & CT) or not
		# Switches occur at halftime and during over times
		$switch = FALSE;

		# Players Array
		$players = array();
		
		# Pull the match data from the .GCS document
		foreach ($gcs->match as $match) {
			
			# Team Names
			$teamOne = $match->team1;
			$teamTwo = $match->team2;
			$teamOnetag = $match->team1tag;
			$teamTwotag = $match->team2tag;
			
			# Using a Team Class, so create two new instances using Team Names
			$team1 = new Team($teamOne, $teamOnetag, 1);
			$team2 = new Team($teamTwo, $teamTwotag, 2);
			
			# Grab other match data from .GCS document
			$pubDate = $match->pubDate;
			$matchDate = $match->matchDate;
			$generator = $match->generator;
			$overtime = $match->overtime;
			$eventtype = $match->eventType;
			$eventname = $match->eventName;
			$map = $match->map;
			$notes = $match->notes;
			Utility::getScore($gcs, $team1, $team2);
			
				
			# Print out Match Info
?>
				<table width="673">
					<tr>
						<td width="95" valign="top">
							<table width="95" class="brown" align="right" valign="top">
								<tr align="right">
									<td align="right" class="brown">Matchup:</td></tr>
								<tr align="right">
									<td align="right" class="brown">Score:</td></tr>
								<tr align="right">
									<td align="right" class="brown">Date:</td></tr>
								<tr align="right">
									<td align="right" class="brown">Event:</td></tr>
								<tr align="right">
									<td align="right" class="brown">Map:</td></tr>
								<tr align="right">
									<td align="right" class="brown">Notes:</td>
								</tr>
							</table>
						</td>
						<td width="172" valign="top">
							<table width="172" class="black" valign="top">
								<tr>
									<td><?php echo $team1->getName() . " vs " . $team2->getName();?></td></tr>
								<tr>
									<td><?php echo $team1->getScore() . " - " . $team2->getScore();?></td></tr>
								<tr>
									<td><?php echo $matchDate; ?></td></tr>
								<tr>
									<td><?php echo $eventtype; if ($eventtype != "Scrim") echo " / " . $eventname;?></td></tr>
								<tr>
									<td><?php echo $map; ?></td></tr>
								<tr>
									<td><?php if ($notes != "") echo $notes; else echo "N/A";?></td>
								</tr>
							</table>
						</td>
						<td width="203" valign="top">
							<table width="203" valign="top">
								<tr>
									<td class="brown"><?php echo $team1->getName() . " Five";?></td></tr>
<?php					
					
			$team1->setScore(0);
			$team2->setScore(0);
			
			# Get Player data from the .GCS document
			foreach($match->player as $player) {
				
				# Store data
				$alias = $player->alias;
				$name = $player->name;
				$country = $player->country; 
				$teamName = $player->teamName;
				$id = $player->id; 
				$steamID = $player->steamID;
				$team = $player->team;
				
				# Check player's team to get them properly assigned
				if ($player->team == 1) {

					# Assign the player variable based on player ID
					# Create new Player instance using data just collected
					//Utility::insert_in_array_pos($players, $id, new Player($alias, $name, $id, $steamID, $team, 0, 0, $teamName, $country));
					$players[(int)$id] = new Player($alias, $name, $id, $steamID, $team, 0, 0, $teamName, $country);

					# Add the newly created Player instance to the proper team
					$team1->addPlayer($players[(int)$id]); 
					
				} else {
					
					# Assign the player variable based on player ID
					# Create new Player instance using data just collected
					//Utility::insert_in_array_pos($players, $id, new Player($alias, $name, $id, $steamID, $team, 0, 0, $teamName, $country));
					$players[(int)$id] = new Player($alias, $name, $id, $steamID, $team, 0, 0, $teamName, $country);
					
					# Add the newly created Player instance to the proper team
					$team2->addPlayer($players[(int)$id]);
				}
			}
			
			
			# Retrieve players on team1 and store as Array
			$team1Players = $team1->getPlayers();
			$team1Tag = $team1->getTag();
			$team1Tag = htmlspecialchars($team1Tag);
			
			
			# For each player on Team 1, print out info
			foreach ($team1Players as $team1Player) {
				
				# Retrieve info from Player class
				$alias = $team1Player->getAlias();
				$name = $team1Player->getName();
				$country = $team1Player->getCountry();
				$teamName = $team1Player->getTeamName();
				$id = $team1Player->getId();
				$steamID = $team1Player->getSteamID();
				

				$alias2 = explode($team1Tag, $alias);
				if ($alias2[0] != "")
					$alias3 = $alias2[0];
				else
					$alias3 = $alias2[1];

				
				# Print info
?>
								<tr>
									<td><?php echo $alias3; ?></td></tr>
<?php
			}
?>
							</table>
						</td>
						<td width="203" valign="top">
							<table width="203" class="brown" valign="top">
								<tr>
									<td class="brown"><?php echo $team2->getName() . " Five";?></td></tr>
<?php	
			
			# Retrieve players on team2 and store as Array
			$team2Players = $team2->getPlayers();
			$team2Tag = $team2->getTag();
			$team2Tag = htmlspecialchars($team2Tag);
			
			
			# For each player on Team 2, print out info
			foreach ($team2Players as $team2Player) {
				
				# Retrieve info from Player class
				$alias = $team2Player->getAlias();
				$name = $team2Player->getName();
				$country = $team2Player->getCountry();
				$teamName = $team2Player->getTeamName();
				$id = $team2Player->getId();
				$steamID = $team2Player->getSteamID();
				
				$alias2 = explode($team2Tag, $alias);
				if ($alias2[0] != "")
					$alias3 = $alias2[0];
				else
					$alias3 = $alias2[1];
				
				# Print info
?>
								<tr>
									<td><?php echo $alias3; ?></td></tr>
<?php
			}
?>
							</table>
						</td>
					</tr>
				</table>
				<img src="images/line.jpg"/>			
<?php			
			
			# Begin actual Goblinpsense data gathering.
			$utility = new Utility($team1, $team2);
			$half = 0;
			$damages = array();
			
			foreach ($gcs->match->half as $halves) {
				$half++;
				$switch = FALSE;
				
				if ($half % 2 == 0 && $half > 1) { 
					$switch = TRUE;
				}

				# Check each round for data
				foreach ($halves->round as $round) {
					
					# Create damage arrays for each player 
					# Had to be done in order for assists to be calculated
					foreach ($team1Players as $team1Player) {
					
						# Retrieve info from Player class
						$id = (int)$team1Player->getId();
						$damages[$id] = array();
						
					}
					foreach ($team2Players as $team2Player) {
					
						# Retrieve info from Player class
						$id = (int)$team2Player->getId();
						$damges[$id] = array();
						
					}
					
					# Set round first frag counter
					$firstFrag = TRUE;				
					
					foreach ($round->bombPlant as $plant) { 
						
						$planter = $utility->playerSearch($plant->initiator);
						
						$players[$planter]->addPlant($round['id']);
						
						if ($players[$planter]->getTeam() == 1) {
							$team1->addPlant($round['id']);
						} else if ($players[$planter]->getTeam() == 2){
							$team2->addPlant($round['id']);	
						}
					}
					
					foreach ($round->bombDefuse as $defuse) { 
							
						$defuser = $utility->playerSearch($defuse->initiator);			
						
						$players[$defuser]->addDefuse($round['id']);
						
						if ($players[$defuser]->getTeam() == 1) {
							$team1->addDefuseWin($round['id']);
						}						
						else if ($players[$defuser]->getTeam() == 2) {
							$team2->addDefuseWin($round['id']);					
						}
							
					}
					
					
					foreach ($round->attack as $attack) {
						
						# Example of GCS for an attack
						#
						# <attack>
						#	<initiator>259178</initiator>
						#	<receiver>307653</receiver>
						#	<damage>8</damage>
						#	<gun>grenade</gun>
						# </attack>
						
						
						# Store variables
						$initiator = $utility->playerSearch($attack->initiator);
						$receiver = $utility->playerSearch($attack->receiver);
						$damage = (int)$attack->damage;
						$gun = $attack->gun;
						
						# FRIENDLY FIRE (ATTACK NOT COUNTED)
						if ($players[$initiator]->getTeam() == $players[$receiver]->getTeam()) {
							break;
						
						# ACTUAL DAMAGE (DAMAGE)
						} else {
						
							# If player is from Team 1
							if ($players[$initiator]->getTeam() == 1) {
							
								# Add the damage to Team 1 total damage
								$team1->addDamage($damage);
								
								# Add the damage to the players total damage
								$players[$initiator]->addDamage($damage);

								# offensive
								if (!$switch) {
									$players[$initiator]->addOffensive(array('damage'=>$damage));
									$players[$initiator]->addOffensive(array('damage_weapon'=>array($gun,$damage)));
								} 
								# defensive
								else {
									$players[$initiator]->addDefensive(array('damage'=>$damage));
									$players[$initiator]->addDefensive(array('damage_weapon'=>array($gun,$damage)));
								}
								
								$x = array_search($receiver, $damages[$initiator]);
								
								# Assuming it is now found or does not exist...
								
								# If they exist, update their damage
								if ($x) {
									$damages[$initiator][$x] += $damage;
								# If they don't exist, create them and store their damage
								} else {
									$damages[$initiator][$receiver] = $damage;
								}
								
							} else if ($players[$initiator]->getTeam() == 2) {
								
								# Add the damage to Team 2 total damage
								$team2->addDamage($damage);
								
								# Add the damage to the players total damage
								$players[$initiator]->addDamage($damage);
								
								# offensive
								if ($switch) {
									$players[$initiator]->addOffensive(array('damage'=>$damage));
									$players[$initiator]->addOffensive(array('damage_weapon'=>array($gun,$damage)));
								} 
								# defensive
								else {
									$players[$initiator]->addDefensive(array('damage'=>$damage));
									$players[$initiator]->addDefensive(array('damage_weapon'=>array($gun,$damage)));
								}
								
								$x = array_search($receiver, $damages[$initiator]);
								
								# If they exist, update their damage
								if ($x) {
									$damages[$initiator][$x] += $damage;
								# If they don't exist, create them and store their damage
								} else {
									$damages[$initiator][$receiver] = $damage;
								}
							}
						}
					}
					
					
					foreach ($round->suicide as $suicide) { 
										
						$suicider = $utility->playerSearch($suicide->initiator);
						
						$players[$suicider]->addDeath();
						$players[$suicider]->addError();
						
						if ($players[$suicider]->getTeam() == 1)  {
							$team1->addDeath();
							$team1->addError();

							# offensive
							if (!$switch) {
								$players[$initiator]->addOffensive(array('death'=>1));
								$players[$initiator]->addOffensive(array('error'=>1));
							} 
							# defensive
							else {
								$players[$initiator]->addDefensive(array('death'=>1));
								$players[$initiator]->addDefensive(array('error'=>1));
							}

							# Check if assist should be calculated
							foreach ($team2Players as $team2Player) {
								$id = (int)$team2Player->getId();
								if (isset($damages[$id][$suicider]) && 
								   ($damages[$id][$suicider] > 50)) {
									$team2->addAssist();
									$team2Player->addAssist();
									if (!$switch) {
										$team2Player->addDefensive(array('assist'=>1));
									} else {
										$team2Player->addOffensive(array('assist'=>1));
									}
								}									
							}	
						}	
						else if ($players[$suicider]->getTeam() == 2){
							$team2->addDeath();
							$team2->addError();

							# offensive
							if ($switch) {
								$players[$initiator]->addOffensive(array('death'=>1));
								$players[$initiator]->addOffensive(array('error'=>1));
							} 
							# defensive
							else {
								$players[$initiator]->addDefensive(array('death'=>1));
								$players[$initiator]->addDefensive(array('error'=>1));
							}

							# Check if assist should be calculated
							foreach ($team1Players as $team1Player) {
								$id = (int)$team1Player->getId();
								if (isset($damages[$id][$suicider]) && 
								   ($damages[$id][$suicider] > 50)) {
									$team1->addAssist();
									$team1Player->addAssist();
									if ($switch) {
										$team1Player->addDefensive(array('assist'=>1));
									} else {
										$team1Player->addOffensive(array('assist'=>1));
									}
								}									
							}	
						}
					}
					
					# For each kill, record the stats
					foreach ($round->kill as $kill) { 

						$roundID = (int)$round['id'];

						# Get the Killer ID #				
						$killer = $utility->playerSearch($kill->initiator);
						
						# Get the Receiver ID #
						$receiver = $utility->playerSearch($kill->receiver);
						
						# FRIENDLY KILL (ERRORS)
						# If killer's team and receiver's team are the same, it is an error.
						if ($players[$killer]->getTeam() == $players[$receiver]->getTeam()) {

							# Team 1
							if ($players[$killer]->getTeam() == 1) {
								$players[$receiver]->addDeath();
								$players[$killer]->addError();
								$team1->addDeath();
								$team1->addError();

								# offensive
								if (!$switch) {
									$players[$receiver]->addOffensive(array('death'=>1));
									$players[$killer]->addOffensive(array('error'=>1));
								} 
								# defensive
								else {
									$players[$receiver]->addDefensive(array('death'=>1));
									$players[$killer]->addDefensive(array('error'=>1));
								}

								# Check if assist should be calculated
								$receiver = $receiver;
								$killer = $killer;
								foreach ($team2Players as $team2Player) {
									$id = $team2Player->getId();
									$id = (int)$id;
									if (isset($damages[$id][$receiver]) && 
									   ($damages[$id][$receiver] > 50)) {
										$team2Player->addAssist();
										$team2->addAssist();
										if (!$switch) {
											$team2Player->addDefensive(array('assist'=>1));
										} else {
											$team2Player->addOffensive(array('assist'=>1));
										}
									}									
								}							
							} 
							# Team 2
							else if ($players[$killer]->getTeam() == 2) {
								$players[$receiver]->addDeath();
								$players[$killer]->addError();
								$team2->addDeath();
								$team2->addError();

								# offensive
								if ($switch) {
									$players[$receiver]->addOffensive(array('death'=>1));
									$players[$killer]->addOffensive(array('error'=>1));
								} 
								# defensive
								else {
									$players[$receiver]->addDefensive(array('death'=>1));
									$players[$killer]->addDefensive(array('error'=>1));
								}

								# Check if assist should be calculated
								$receiver = (integer)$receiver;
								$killer = (integer)$killer;
								foreach ($team1Players as $team1Player) {
									$id = $team1Player->getId();
									$id = (integer)$id;
									if (isset($damages[$id][$receiver]) && 
									   ($damages[$id][$receiver] > 50)) {
										$team1Player->addAssist();
										$team1->addAssist();
										if ($switch) {
											$team1Player->addDefensive(array('assist'=>1));
										} else {
											$team1Player->addOffensive(array('assist'=>1));
										}
									}									
								}	
							}
						
						# REAL KILLS (FRAGS)		
						} else {
							
							# Add Frag & Death
							$players[$killer]->addFrag();
							$players[$receiver]->addDeath();
							
							# Player on team 1 and on Offense
							if ($players[$killer]->getTeam() == 1 && !$switch) {
								$team1->addOFrag();
								$team1->addFrag();
								$team1->addFragRound($roundID);
								$team2->addDeath();
								
								$players[$killer]->addOFrag();
								$players[$killer]->addFragRound($roundID);
								$players[$killer]->addOffensive(array('frag_round'=>$roundID));
								$players[$killer]->addOffensive(array('frag'=>1));
								$players[$killer]->addOffensive(array('frag_weapon'=>$kill->gun));

								$players[$receiver]->addDefensive(array('death'=>1));
								
								# Check if first frag
								if ($firstFrag) {
									$team1->addEntryFrag();
									$team2->addEntryDeath();
									$players[$killer]->addEntryFrag();
									$players[$receiver]->addEntryDeath();
									$players[$killer]->addOffensive(array('entryFrag'=>1));
									$players[$receiver]->addDefensive(array('entryDeath'=>1));
									$firstFrag = FALSE;
								}
								
								# Check if assist should be calculated
								foreach ($team1Players as $team1Player) {
									$id = (int)$team1Player->getId();
									if (($id != $killer) && 
									   (isset($damages[$id][$receiver])) && 
									   ($damages[$id][$receiver] > 50)) {
										$team1Player->addAssist();
										$team1Player->addOffensive(array('assist'=>1));
										$team1->addAssist();
									}									
								}							
								
							} 
							
							# Player on team 1 and on Defense
							else if ($players[$killer]->getTeam() == 1 && $switch) {
								$team1->addDFrag();
								$team1->addFrag();
								$team1->addFragRound($roundID);
								$team2->addDeath();
								
								$players[$killer]->addDFrag();
								$players[$killer]->addFragRound($roundID);
								$players[$killer]->addDefensive(array('frag_round'=>$roundID));
								$players[$killer]->addDefensive(array('frag'=>1));
								$players[$killer]->addDefensive(array('frag_weapon'=>$kill->gun));

								$players[$receiver]->addOffensive(array('death'=>1));
								
								# Check if first frag
								if ($firstFrag) {
									$team1->addEntryFrag();
									$team2->addEntryDeath();
									$players[$killer]->addEntryFrag();
									$players[$receiver]->addEntryDeath();
									$players[$killer]->addDefensive(array('entryFrag'=>1));
									$players[$receiver]->addOffensive(array('entryDeath'=>1));
									$firstFrag = FALSE;
								}
								
								# Check if assist should be calculated
								$receiver = (integer)$receiver;
								$killer = (integer)$killer;
								foreach ($team1Players as $team1Player) {
									$id = $team1Player->getId();
									$id = (integer)$id;
									if ($id != $killer) {
										if (isset($damages[$id][$receiver])) {
									   		if ($damages[$id][$receiver] > 50) {
												$team1Player->addAssist();
												$team1Player->addDefensive(array('assist'=>1));
												$team1->addAssist();
											}
										}
									}									
								}	
							} 
							
							# Player is on team 2 and on Defense
							else if ($players[$killer]->getTeam() == 2 && !$switch) {
								$team2->addDFrag();
								$team2->addFrag();
								$team2->addFragRound($roundID);
								$team1->addDeath();
								
								$players[$killer]->addDFrag();
								$players[$killer]->addFragRound($roundID);
								$players[$killer]->addDefensive(array('frag_round'=>$roundID));
								$players[$killer]->addDefensive(array('frag'=>1));
								$players[$killer]->addDefensive(array('frag_weapon'=>$kill->gun));

								$players[$receiver]->addOffensive(array('death'=>1));
								
								# Check if first frag
								if ($firstFrag) {
									$team2->addEntryFrag();
									$team1->addEntryDeath();
									$players[$killer]->addEntryFrag();
									$players[$receiver]->addEntryDeath();
									$players[$killer]->addDefensive(array('entryFrag'=>1));
									$players[$receiver]->addOffensive(array('entryDeath'=>1));
									$firstFrag = FALSE;
								}
								
								# Check if assist should be calculated
								$receiver = (integer)$receiver;
								$killer = (integer)$killer;
								foreach ($team2Players as $team2Player) {
									$id = $team2Player->getId();
									$id = (integer)$id;
									if ($id != $killer) {
										if (isset($damages[$id][$receiver])) {
									   		if ($damages[$id][$receiver] > 50) {
												$team2Player->addAssist();
												$team2Player->addDefensive(array('assist'=>1));
												$team2->addAssist();
											}
										}
									}									
								}
							} 
							
							# Player is on team 2 and on Offense
							else if ($players[$killer]->getTeam() == 2 && $switch) {
								$team2->addOFrag();
								$team2->addFrag();
								$team2->addFragRound($roundID);
								$team1->addDeath();
								
								$players[$killer]->addOFrag();
								$players[$killer]->addFragRound($roundID);
								$players[$killer]->addOffensive(array('frag_round'=>$roundID));
								$players[$killer]->addOffensive(array('frag'=>1));
								$players[$killer]->addOffensive(array('frag_weapon'=>$kill->gun));

								$players[$receiver]->addDefensive(array('death'=>1));
								
								# Check if first frag
								if ($firstFrag) {
									$team2->addEntryFrag();
									$team1->addEntryDeath();
									$players[$killer]->addEntryFrag();
									$players[$receiver]->addEntryDeath();
									$players[$killer]->addOffensive(array('entryFrag'=>1));
									$players[$receiver]->addDefensive(array('entryDeath'=>1));
									$firstFrag = FALSE;
								}
								
								# Check if assist should be calculated
								$receiver = (integer)$receiver;
								$killer = (integer)$killer;
								foreach ($team2Players as $team2Player) {
									$id = $team2Player->getId();
									$id = (integer)$id;
									if (($id != $killer) && 
									   (isset($damages[$id][$receiver])) && 
									   ($damages[$id][$receiver] > 50)) {
										$team2Player->addAssist();
										$team2Player->addOffensive(array('assist'=>1));
										$team2->addAssist();
									}									
								}
							}
						}
					}
		
			  
					# Set win id to the win Type
					# [1 = CT frag, 2 = CT defuse, 3 = CT timer, 4 = T frag, 5 = T bomb]
					foreach ($round->win as $win) {
						$roundID = $round['id'];
						switch ($win['id']) {
							# CT win by elimination
							case 1: 
								if (!$switch) {
									$team2->addRoundsWon($roundID);
									$team1->addRoundsLost($roundID);
								} else {
									$team1->addRoundsWon($roundID);
									$team2->addRoundsLost($roundID);
								}
								break;
							# CT win by bomb defuse
							case 2:
								if (!$switch) {
									$team2->addRoundsWon($roundID);
									$team2->addDefuseWin($roundID);
									$team1->addRoundsLost($roundID);
								} else {
									$team1->addRoundsWon($roundID);
									$team1->addDefuseWin($roundID);
									$team2->addRoundsLost($roundID);
								}
								break;
							# CT win by default timer
							case 3:
								if (!$switch) {
									$team2->addRoundsWon($roundID);
									$team1->addRoundsLost($roundID);
								} else {
									$team1->addRoundsWon($roundID);
									$team2->addRoundsLost($roundID);
								}
								break;
							# T win by elimination
							case 4:
								if (!$switch) {
									$team1->addRoundsWon($roundID);
									$team2->addRoundsLost($roundID);
								} else {
									$team2->addRoundsWon($roundID);
									$team1->addRoundsLost($roundID);
								}
								break;
							# T win by bomb explosion	
							case 5:
								if (!$switch) {
									$team1->addRoundsWon($roundID);
									$team1->addPlantWin($roundID);
									$team2->addRoundsLost($roundID);
								} else {
									$team2->addRoundsWon($roundID);
									$team2->addPlantWin($roundID);
									$team1->addRoundsLost($roundID);
								}
								break;
						}			
					}				
				}
			}
		}
		
		# Output all of the stats
		echo "<div class=content>";
?>
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>						
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td class="round2">Stat \ Name</td>
							  </tr>
							  <tr>
								<td class="whiteb">Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Deaths</td>
							  </tr>
							  <tr>
								<td class="whiteb">Assists</td>
							  </tr>
							  <tr>
								<td class="greyb">Errors</td>
							  </tr>
							  <tr>
								<td class="whiteb">Offensive Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Defensive Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Entry Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Entry Deaths</td>
							  </tr>
							  <tr>
								<td class="whiteb">2 Frag Rounds</td>
							  </tr>
							  <tr>
								<td class="greyb">3 Frag Rounds</td>
							  </tr>
							  <tr>
								<td class="whiteb">4 Frag Rounds</td>
							  </tr>
							  <tr>
								<td class="greyb">Aces</td>
							  </tr>
							  <tr>
								<td class="whiteb">Damage per Round</td>
							  </tr>
							  <tr>
								<td class="greyb">Off. Entry Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Off. Entry Deaths</td>
							  </tr>
							  <tr>
								<td class="greyb">Def. Entry Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Def. Entry Deaths</td>
							  </tr>
							  <tr>
								<td class="greyb">Offensive Assists</td>
							  </tr>
							  <tr>
								<td class="whiteb">Defensive Assists</td>
							  </tr>
							  <tr>
								<td class="greyb">Knife Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">USP Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Glock Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Deagle Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">MP5 Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Galil Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Famas Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">AK Damage</td>
							  </tr>
							  <tr>
								<td class="greyb">AK Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Colt Damage</td>
							  </tr>
							  <tr>
								<td class="greyb">Colt Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">AWP Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Rounds w/ Frag</td>
							  </tr>
							  <tr>
								<td class="whiteb">% Rounds w/ Frag</td>
							  </tr>
							  <tr>
								<td class="greyb">Frags per Round</td>
							  </tr>
							  <tr>
								<td class="whiteb">% Team Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Frags - Deaths</td>
							  </tr>
							  <tr>
								<td class="whiteb">Frags in Win</td>
							  </tr>
							  <tr>
								<td class="greyb">% Frags in Win</td>
							  </tr>
							  <tr>
								<td class="whiteb">Frags in Loss</td>
							  </tr>
							  <tr>
								<td class="greyb">% Frags in Loss</td>
							  </tr>
							  <tr>
								<td class="whiteb">Score</td>
							  </tr>
							  <tr>
								<td class="greyb">% Team Score</td>
							  </tr>
							</table>
						</td>
<?php
		foreach ($team1Players as $team1Player) {
			$o = $team1Player->getOffensive();
			$d = $team1Player->getDefensive();
?>
	
						<td width="3" class="dot"></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td class="round">
								<?php 
								# Remove the tag from the player's alias when printed here
								$alias2 = explode($team1Tag, $team1Player->getAlias());
								if ($alias2[0] != "")
									$alias3 = $alias2[0];
								else
									$alias3 = $alias2[1];
								echo $alias3; 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea">
								<?php 
								if (is_null($team1Player->getFrags()))
									echo 0;
								else
									echo $team1Player->getFrags();
								?>
								</td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php 
								if (is_null($team1Player->getDeaths()))
									echo 0;
								else
								echo $team1Player->getDeaths(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea">
								<?php
								if (is_null($team1Player->getAssists()))
									echo 0;
								else 
								echo $team1Player->getAssists(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php 
								if (is_null($team1Player->getErrors()))
									echo 0;
								else
								echo $team1Player->getErrors(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea">
								<?php 
								if (is_null($team1Player->getOFrags()))
									echo 0;
								else
								echo $team1Player->getOFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php 
								if (is_null($team1Player->getDFrags()))
									echo 0;
								else
								echo $team1Player->getDFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea">
								<?php 
								if (is_null($team1Player->getEntryFrags()))
									echo 0;
								else
								echo $team1Player->getEntryFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php
								if (is_null($team1Player->getEntryDeaths()))
									echo 0;
								else 
								echo $team1Player->getEntryDeaths(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo Utility::countFrags($team1Player->getFragRounds(), 2); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo Utility::countFrags($team1Player->getFragRounds(), 3); ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo Utility::countFrags($team1Player->getFragRounds(), 4); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo Utility::countFrags($team1Player->getFragRounds(), 5); ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo (round($team1Player->getDamage() / $round['id'], 0)); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['entryFrags']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['entryDeaths']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $d['entryFrags']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $d['entryDeaths']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['assists']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $d['assists']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['knife'] + $d['frag_weapon']['knife']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['frag_weapon']['usp'] + $d['frag_weapon']['usp']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['glock18'] + $d['frag_weapon']['glock18']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['frag_weapon']['deagle'] + $d['frag_weapon']['deagle']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['mp5navy'] + $d['frag_weapon']['mp5navy']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['frag_weapon']['galil'] + $d['frag_weapon']['galil']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['famas'] + $d['frag_weapon']['famas']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['damage_weapon']['ak47'] + $d['damage_weapon']['ak47']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['ak47'] + $d['frag_weapon']['ak47']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['damage_weapon']['m4a1'] + $d['damage_weapon']['m4a1']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['m4a1'] + $d['frag_weapon']['m4a1']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['frag_weapon']['awp'] + $d['frag_weapon']['awp']; ?></td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php
									$count1 = 0;
										$count1 += Utility::countFrags($team1Player->getFragRounds(), 1);
										$count1 += Utility::countFrags($team1Player->getFragRounds(), 2);
										$count1 += Utility::countFrags($team1Player->getFragRounds(), 3);
										$count1 += Utility::countFrags($team1Player->getFragRounds(), 4);
										$count1 += Utility::countFrags($team1Player->getFragRounds(), 5);
									echo $count1;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo round($count1 / $round['id'], 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo round($team1Player->getFrags() / $round['id'], 2); ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo round($team1Player->getFrags() / $team1->getFrags(), 2)*100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php 
								$pm = $team1Player->getFrags() - $team1Player->getDeaths();
								if ($pm > 0)
									$pm = "+" . $pm;
								echo $pm; 
								?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo count(array_intersect($team1Player->getFragRounds(), $team1->getRoundsWon())); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo round(count(array_intersect($team1Player->getFragRounds(), $team1->getRoundsWon()))/$team1Player->getFrags(), 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo count(array_intersect($team1Player->getFragRounds(), $team1->getRoundsLost())); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo round(count(array_intersect($team1Player->getFragRounds(), $team1->getRoundsLost()))/$team1Player->getFrags(), 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo Utility::getPlayerScore($team1Player, $team1); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo round(Utility::getPlayerScore($team1Player, $team1) / Utility::getTeamScore($team1), 2)* 100 . "%"; ?></td>
							  </tr>
							</table>
						</td>
		
<?php
		}
?>
					<td width="3" class="dot"></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td class="round"><?php echo $team1->getName(); ?></td>
							  </tr>
							  <tr>
								<td class="white">
								<?php 
								if (is_null($team1->getFrags()))
									echo 0;
								else
									echo $team1->getFrags();
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php 
								if (is_null($team1->getDeaths()))
									echo 0;
								else
								echo $team1->getDeaths(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php
								if (is_null($team1->getAssists()))
									echo 0;
								else 
								echo $team1->getAssists(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php 
								if (is_null($team1->getErrors()))
									echo 0;
								else
								echo $team1->getErrors(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php 
								if (is_null($team1->getOFrags()))
									echo 0;
								else
								echo $team1->getOFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php 
								if (is_null($team1->getDFrags()))
									echo 0;
								else
								echo $team1->getDFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php 
								if (is_null($team1->getEntryFrags()))
									echo 0;
								else
								echo $team1->getEntryFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php
								if (is_null($team1->getEntryDeaths()))
									echo 0;
								else 
								echo $team1->getEntryDeaths(); 
								?>
								</td>
							  <tr>
								<td class="white">
								<?php
									$count2 = 0;
									foreach ($team1Players as $team1Player) {
										$count2 += Utility::countFrags($team1Player->getFragRounds(), 2);
									}
									echo $count2;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php
									$count3 = 0;
									foreach ($team1Players as $team1Player) {
										$count3 += Utility::countFrags($team1Player->getFragRounds(), 3);
									}
									echo $count3;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php
									$count4 = 0;
									foreach ($team1Players as $team1Player) {
										$count4 += Utility::countFrags($team1Player->getFragRounds(), 4);
									}
									echo $count4;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php
									$count5 = 0;
									foreach ($team1Players as $team1Player) {
										$count5 += Utility::countFrags($team1Player->getFragRounds(), 5);
									}
									echo $count5;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white"><?php echo (round($team1->getDamage() / $round['id'], 0)); ?></td>
							  </tr>
							  								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['entryFrags'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['entryDeaths'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $d['entryFrags'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $d['entryDeaths'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['assists'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $d['assists'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['knife'];
											$x += $d['frag_weapon']['knife'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['usp'];
											$x += $d['frag_weapon']['usp'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['glock18'];
											$x += $d['frag_weapon']['glock18'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['deagle'];
											$x += $d['frag_weapon']['deagle'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['mp5navy'];
											$x += $d['frag_weapon']['mp5navy'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['galil'];
											$x += $d['frag_weapon']['galil'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['famas'];
											$x += $d['frag_weapon']['famas'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['damage_weapon']['ak47'];
											$x += $d['damage_weapon']['ak47'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['ak47'];
											$x += $d['frag_weapon']['ak47'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['damage_weapon']['m4a1'];
											$x += $d['damage_weapon']['m4a1'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['m4a1'];
											$x += $d['frag_weapon']['m4a1'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team1Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['awp'];
											$x += $d['frag_weapon']['awp'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php
									$count1 = 0;
										$count1 += Utility::countFrags($team1->getFragRounds(), 1);
										$count1 += Utility::countFrags($team1->getFragRounds(), 2);
										$count1 += Utility::countFrags($team1->getFragRounds(), 3);
										$count1 += Utility::countFrags($team1->getFragRounds(), 4);
										$count1 += Utility::countFrags($team1->getFragRounds(), 5);
									echo $count1;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white"><?php echo round($count1 / $round['id'], 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="grey"><?php echo round($team1->getFrags() / $round['id'], 2); ?></td>
							  </tr>
							  <tr>
								<td class="white">100%</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php 
								$pm = $team1->getFrags() - $team1->getDeaths();
								if ($pm > 0)
									$pm = "+" . $pm;
								echo $pm; 
								?></td>
							  </tr>
							  <tr>
								<td class="white"><?php echo count(array_intersect($team1->getFragRounds(), $team1->getRoundsWon())); ?></td>
							  </tr>
							  <tr>
								<td class="grey"><?php echo round(count(array_intersect($team1->getFragRounds(), $team1->getRoundsWon()))/$team1->getFrags(), 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="white"><?php echo count(array_intersect($team1->getFragRounds(), $team1->getRoundsLost())); ?></td>
							  </tr>
							  <tr>
								<td class="grey"><?php echo round(count(array_intersect($team1->getFragRounds(), $team1->getRoundsLost()))/$team1->getFrags(), 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="white"><?php echo Utility::getTeamScore($team1); ?></td>
							  </tr>
							  <tr>
								<td class="grey">100%</td>
							  </tr>
							</table>
						</td>
					</tr>
				</table>
<?php
		echo "</div>";
		echo "<div class='content'>";

?>
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>						
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td class="round2">Stat \ Name</td>
							  </tr>
							  <tr>
								<td class="whiteb">Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Deaths</td>
							  </tr>
							  <tr>
								<td class="whiteb">Assists</td>
							  </tr>
							  <tr>
								<td class="greyb">Errors</td>
							  </tr>
							  <tr>
								<td class="whiteb">Offensive Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Defensive Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Entry Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Entry Deaths</td>
							  </tr>
							  <tr>
								<td class="whiteb">2 Frag Rounds</td>
							  </tr>
							  <tr>
								<td class="greyb">3 Frag Rounds</td>
							  </tr>
							  <tr>
								<td class="whiteb">4 Frag Rounds</td>
							  </tr>
							  <tr>
								<td class="greyb">Aces</td>
							  </tr>
							  <tr>
								<td class="whiteb">Damage per Round</td>
							  </tr>
							  <tr>
								<td class="greyb">Off. Entry Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Off. Entry Deaths</td>
							  </tr>
							  <tr>
								<td class="greyb">Def. Entry Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Def. Entry Deaths</td>
							  </tr>
							  <tr>
								<td class="greyb">Offensive Assists</td>
							  </tr>
							  <tr>
								<td class="whiteb">Defensive Assists</td>
							  </tr>
							  <tr>
								<td class="greyb">Knife Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">USP Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Glock Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Deagle Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">MP5 Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Galil Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Famas Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">AK Damage</td>
							  </tr>
							  <tr>
								<td class="greyb">AK Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">Colt Damage</td>
							  </tr>
							  <tr>
								<td class="greyb">Colt Frags</td>
							  </tr>
							  <tr>
								<td class="whiteb">AWP Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Rounds w/ Frag</td>
							  </tr>
							  <tr>
								<td class="whiteb">% Rounds w/ Frag</td>
							  </tr>
							  <tr>
								<td class="greyb">Frags per Round</td>
							  </tr>
							  <tr>
								<td class="whiteb">% Team Frags</td>
							  </tr>
							  <tr>
								<td class="greyb">Frags - Deaths</td>
							  </tr>
							  <tr>
								<td class="whiteb">Frags in Win</td>
							  </tr>
							  <tr>
								<td class="greyb">% Frags in Win</td>
							  </tr>
							  <tr>
								<td class="whiteb">Frags in Loss</td>
							  </tr>
							  <tr>
								<td class="greyb">% Frags in Loss</td>
							  </tr>
							  <tr>
								<td class="whiteb">Score</td>
							  </tr>
							  <tr>
								<td class="greyb">% Team Score</td>
							  </tr>
							</table>
						</td>
<?php
		foreach ($team2Players as $team2Player) {
			$o = $team2Player->getOffensive();
			$d = $team2Player->getDefensive();
?>
	
						<td width="3" class="dot"></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td class="round">
								<?php 
								# Remove the tag from the player's alias when printed here
								$alias2 = explode($team2Tag, $team2Player->getAlias());
								if ($alias2[0] != "")
									$alias3 = $alias2[0];
								else
									$alias3 = $alias2[1];
								echo $alias3; 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea">
								<?php 
								if (is_null($team2Player->getFrags()))
									echo 0;
								else
									echo $team2Player->getFrags();
								?>
								</td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php 
								if (is_null($team2Player->getDeaths()))
									echo 0;
								else
								echo $team2Player->getDeaths(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea">
								<?php
								if (is_null($team2Player->getAssists()))
									echo 0;
								else 
								echo $team2Player->getAssists(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php 
								if (is_null($team2Player->getErrors()))
									echo 0;
								else
								echo $team2Player->getErrors(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea">
								<?php 
								if (is_null($team2Player->getOFrags()))
									echo 0;
								else
								echo $team2Player->getOFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php 
								if (is_null($team2Player->getDFrags()))
									echo 0;
								else
								echo $team2Player->getDFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea">
								<?php 
								if (is_null($team2Player->getEntryFrags()))
									echo 0;
								else
								echo $team2Player->getEntryFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php
								if (is_null($team2Player->getEntryDeaths()))
									echo 0;
								else 
								echo $team2Player->getEntryDeaths(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo Utility::countFrags($team2Player->getFragRounds(), 2); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo Utility::countFrags($team2Player->getFragRounds(), 3); ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo Utility::countFrags($team2Player->getFragRounds(), 4); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo Utility::countFrags($team2Player->getFragRounds(), 5); ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo (round($team2Player->getDamage() / $round['id'], 0)); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['entryFrags']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['entryDeaths']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $d['entryFrags']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $d['entryDeaths']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['assists']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $d['assists']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['knife'] + $d['frag_weapon']['knife']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['frag_weapon']['usp'] + $d['frag_weapon']['usp']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['glock18'] + $d['frag_weapon']['glock18']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['frag_weapon']['deagle'] + $d['frag_weapon']['deagle']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['mp5navy'] + $d['frag_weapon']['mp5navy']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['frag_weapon']['galil'] + $d['frag_weapon']['galil']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['famas'] + $d['frag_weapon']['famas']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['damage_weapon']['ak47'] + $d['damage_weapon']['ak47']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['ak47'] + $d['frag_weapon']['ak47']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['damage_weapon']['m4a1'] + $d['damage_weapon']['m4a1']; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo $o['frag_weapon']['m4a1'] + $d['frag_weapon']['m4a1']; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo $o['frag_weapon']['awp'] + $d['frag_weapon']['awp']; ?></td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php
									$count1 = 0;
										$count1 += Utility::countFrags($team2Player->getFragRounds(), 1);
										$count1 += Utility::countFrags($team2Player->getFragRounds(), 2);
										$count1 += Utility::countFrags($team2Player->getFragRounds(), 3);
										$count1 += Utility::countFrags($team2Player->getFragRounds(), 4);
										$count1 += Utility::countFrags($team2Player->getFragRounds(), 5);
									echo $count1;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo round($count1 / $round['id'], 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo round($team2Player->getFrags() / $round['id'], 2); ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo round($team2Player->getFrags() / $team2->getFrags(), 2)*100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="greya">
								<?php 
								$pm = $team2Player->getFrags() - $team2Player->getDeaths();
								if ($pm > 0)
									$pm = "+" . $pm;
								echo $pm; 
								?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo count(array_intersect($team2Player->getFragRounds(), $team2->getRoundsWon())); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo round(count(array_intersect($team2Player->getFragRounds(), $team2->getRoundsWon()))/$team2Player->getFrags(), 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo count(array_intersect($team2Player->getFragRounds(), $team2->getRoundsLost())); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo round(count(array_intersect($team2Player->getFragRounds(), $team2->getRoundsLost()))/$team2Player->getFrags(), 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="whitea"><?php echo Utility::getPlayerScore($team2Player, $team2); ?></td>
							  </tr>
							  <tr>
								<td class="greya"><?php echo round(Utility::getPlayerScore($team2Player, $team2) / Utility::getTeamScore($team2), 2)* 100 . "%"; ?></td>
							  </tr>
							</table>
						</td>
		
<?php
		}
?>
					<td width="3" class="dot"></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td class="round"><?php echo $team2->getName(); ?></td>
							  </tr>
							  <tr>
								<td class="white">
								<?php 
								if (is_null($team2->getFrags()))
									echo 0;
								else
									echo $team2->getFrags();
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php 
								if (is_null($team2->getDeaths()))
									echo 0;
								else
								echo $team2->getDeaths(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php
								if (is_null($team2->getAssists()))
									echo 0;
								else 
								echo $team2->getAssists(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php 
								if (is_null($team2->getErrors()))
									echo 0;
								else
								echo $team2->getErrors(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php 
								if (is_null($team2->getOFrags()))
									echo 0;
								else
								echo $team2->getOFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php 
								if (is_null($team2->getDFrags()))
									echo 0;
								else
								echo $team2->getDFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php 
								if (is_null($team2->getEntryFrags()))
									echo 0;
								else
								echo $team2->getEntryFrags(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php
								if (is_null($team2->getEntryDeaths()))
									echo 0;
								else 
								echo $team2->getEntryDeaths(); 
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php
									$count2 = 0;
									foreach ($team2Players as $team2Player) {
										$count2 += Utility::countFrags($team2Player->getFragRounds(), 2);
									}
									echo $count2;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php
									$count3 = 0;
									foreach ($team2Players as $team2Player) {
										$count3 += Utility::countFrags($team2Player->getFragRounds(), 3);
									}
									echo $count3;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
								<?php
									$count4 = 0;
									foreach ($team2Players as $team2Player) {
										$count4 += Utility::countFrags($team2Player->getFragRounds(), 4);
									}
									echo $count4;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php
									$count5 = 0;
									foreach ($team2Players as $team2Player) {
										$count5 += Utility::countFrags($team2Player->getFragRounds(), 5);
									}
									echo $count5;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white"><?php echo (round($team2->getDamage() / $round['id'], 0)); ?></td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['entryFrags'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['entryDeaths'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $d['entryFrags'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $d['entryDeaths'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['assists'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $d['assists'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['knife'];
											$x += $d['frag_weapon']['knife'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['usp'];
											$x += $d['frag_weapon']['usp'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['glock18'];
											$x += $d['frag_weapon']['glock18'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['deagle'];
											$x += $d['frag_weapon']['deagle'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['mp5navy'];
											$x += $d['frag_weapon']['mp5navy'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['galil'];
											$x += $d['frag_weapon']['galil'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['famas'];
											$x += $d['frag_weapon']['famas'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['damage_weapon']['ak47'];
											$x += $d['damage_weapon']['ak47'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['ak47'];
											$x += $d['frag_weapon']['ak47'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['damage_weapon']['m4a1'];
											$x += $d['damage_weapon']['m4a1'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['m4a1'];
											$x += $d['frag_weapon']['m4a1'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="white">
									<?php 
										$x = 0;
										foreach ($team2Players as $y) {
											$o = $y->getOffensive();
											$d = $y->getDefensive();
											$x += $o['frag_weapon']['awp'];
											$x += $d['frag_weapon']['awp'];
										}
										echo $x; 
									?>
								</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php
									$count1 = 0;
										$count1 += Utility::countFrags($team2->getFragRounds(), 1);
										$count1 += Utility::countFrags($team2->getFragRounds(), 2);
										$count1 += Utility::countFrags($team2->getFragRounds(), 3);
										$count1 += Utility::countFrags($team2->getFragRounds(), 4);
										$count1 += Utility::countFrags($team2->getFragRounds(), 5);
									echo $count1;
								?>
								</td>
							  </tr>
							  <tr>
								<td class="white"><?php echo round($count1 / $round['id'], 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="grey"><?php echo round($team2->getFrags() / $round['id'], 2); ?></td>
							  </tr>
							  <tr>
								<td class="white">100%</td>
							  </tr>
							  <tr>
								<td class="grey">
								<?php 
								$pm = $team2->getFrags() - $team2->getDeaths();
								if ($pm > 0)
									$pm = "+" . $pm;
								echo $pm; 
								?></td>
							  </tr>
							  <tr>
								<td class="white"><?php echo count(array_intersect($team2->getFragRounds(), $team2->getRoundsWon())); ?></td>
							  </tr>
							  <tr>
								<td class="grey"><?php echo round(count(array_intersect($team2->getFragRounds(), $team2->getRoundsWon()))/$team2->getFrags(), 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="white"><?php echo count(array_intersect($team2->getFragRounds(), $team2->getRoundsLost())); ?></td>
							  </tr>
							  <tr>
								<td class="grey"><?php echo round(count(array_intersect($team2->getFragRounds(), $team2->getRoundsLost()))/$team2->getFrags(), 2) * 100 . "%"; ?></td>
							  </tr>
							  <tr>
								<td class="white"><?php echo Utility::getTeamScore($team2); ?></td>
							  </tr>
							  <tr>
								<td class="grey">100%</td>
							  </tr>
							</table>
						</td>
					</tr>
				</table>
				</div>
<?php
	}
?>