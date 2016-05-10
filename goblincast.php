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
	function goblincast($userid) {
		
		######## BEGIN DATA PARSE FOR PLAYERS #########
	
		# Open gcs file into memory using simpleXML library
		$gcs = simplexml_load_file('gcs/' . $userid . '.gcs');
		
		# Create variable to store whether teams are switching sides (T & CT) or not
		# Switches occur at halftime and during over times
		$switch = FALSE;
		
		# Default for overtime count
		$numOfOT = 1;
		
		# For color backgrounds
		$white = TRUE;

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
			
			$utility = new Utility($team1, $team2);

			$half = 0;
			foreach ($gcs->match->half as $halves) {
				$half++;
				$white = TRUE;
				$switch = FALSE;
				if ($half % 2 == 0 && $half > 1) { 
					if ($half < 3) {
?>
						<div class="halftime">
							<table width="663"><tr><td class="halftime">HALFTIME</td></tr></table>
						</div>
<?php
					} else {
?>
						<div class="halftime">
							<table width="663"><tr><td class="halftime">OVERTIME #<?php echo ($half-2)/2; ?> HALFTIME</td></tr></table>
						</div>
<?php						
					}

					$switch = TRUE;
				}

				if ($half % 2 == 1 && $half > 1) {
?>
				<div class="halftime">
					<table width="663"><tr><td class="halftime">OVERTIME #<?php echo ($half-1)/2; ?></td></tr></table>
				</div>
<?php					
				}
				
				foreach ($halves->round as $round) {
?>	
					<div class="round">
						<table width="663"><tr><td>Round <?php echo $round[id]; ?></td></tr></table>
					</div>
<?php				
					foreach ($round->kill as $kill) { 
										
						$killer = $utility->playerSearch($kill->initiator);
						$receiver = $utility->playerSearch($kill->receiver);
						
						if ($players[$killer]->getTeam() == $players[$receiver]->getTeam()) {
							$players[$receiver]->addDeath();

							if ($white) {
								echo '<div class="white">';
								echo '<table width="663"><tr><td width="30">';
							} else {
								echo '<div class="grey">';
								echo '<table width="663"><tr><td width="30">'; 
							}

							if ($players[$killer]->getTeam() == 1 && !$switch) 
								echo "<img class='image' src='images/t.gif'/></td><td>";
							else if ($players[$killer]->getTeam() == 1 && $switch)
								echo "<img class='image' src='images/ct.gif'/></td><td>";
							else if ($players[$killer]->getTeam() == 2 && !$switch)
								echo "<img class='image' src='images/ct.gif'/></td><td>";
							else if ($players[$killer]->getTeam() == 2 && $switch)
								echo "<img class='image' src='images/t.gif'/></td><td>";

							echo $players[$killer]->getAlias() . " [" . $players[$killer]->getFrags() . "/" . $players[$killer]->getDeaths() . "] kills teammate " . $players[$receiver]->getAlias() . " [" . $players[$receiver]->getFrags() . "/" . $players[$receiver]->getDeaths() . "] with a " . $kill->gun . "</td></tr></table></div>";
						} else {
							$players[$killer]->addFrag();
							$players[$receiver]->addDeath();
							
							if ($white) {
								echo '<div class="white">';
								echo '<table width="663"><tr><td width="30">';
							} else {
								echo '<div class="grey">';
								echo '<table width="663"><tr><td width="30">'; 
							}

							if ($players[$killer]->getTeam() == 1 && !$switch) 
								echo "<img class='image' src='images/t.gif'/></td><td>";
							else if ($players[$killer]->getTeam() == 1 && $switch)
								echo "<img class='image' src='images/ct.gif'/></td><td>";
							else if ($players[$killer]->getTeam() == 2 && !$switch)
								echo "<img class='image' src='images/ct.gif'/></td><td>";
							else if ($players[$killer]->getTeam() == 2 && $switch)
								echo "<img class='image' src='images/t.gif'/></td><td>";
								
							echo $players[$killer]->getAlias() . " [" . $players[$killer]->getFrags() . "/" . $players[$killer]->getDeaths() . "] frags " . $players[$receiver]->getAlias() . " [" . $players[$receiver]->getFrags() . "/" . $players[$receiver]->getDeaths() . "] with a " . $kill->gun . "</td></tr></table></div>";
						}
						if ($white)
							$white = FALSE;
						else
							$white = TRUE;
					} # </kills>
				
				
					foreach ($round->bombPlant as $plant) { 
										
						$planter = $utility->playerSearch($plant->initiator);
						
						if ($white) {
								echo '<div class="white">';
								echo '<table width="663"><tr><td width="30">';
						} else {
								echo '<div class="grey">';
								echo '<table width="663"><tr><td width="30">'; 
						}
							
						if ($players[$planter]->getTeam() == 1 && !$switch) 
							echo "<img class='image' src='images/t.gif'/></td><td>";
						else if ($players[$planter]->getTeam() == 2 && $switch)
							echo "<img class='image' src='images/t.gif'/></td><td>";
						echo $players[$planter]->getAlias() . " [" . $players[$planter]->getFrags() . "/" . $players[$planter]->getDeaths() . "] plants the bomb.</td></tr></table></div>";
						if ($white)
							$white = FALSE;
						else
							$white = TRUE;
					} # </plants>
				
					foreach ($round->bombDefuse as $defuse) { 
										
						$defuser = $utility->playerSearch($defuse->initiator);
						
						if ($white) {
								echo '<div class="white">';
								echo '<table width="663"><tr><td width="30">';
						} else {
								echo '<div class="grey">';
								echo '<table width="663"><tr><td width="30">'; 
						}
						
						if ($players[$defuser]->getTeam() == 1 && $switch)
							echo "<img class='image' src='images/ct.gif'/></td><td>";
						else if ($players[$defuser]->getTeam() == 2 && !$switch)
							echo "<img class='image' src='images/ct.gif'/></td><td>";

						echo $players[$defuser]->getAlias() . " [" . $players[$defuser]->getFrags() . "/" . $players[$defuser]->getDeaths() . "] defuses the bomb.</td></tr></table></div>";
						if ($white)
							$white = FALSE;
						else
							$white = TRUE;
					} # </defuses>
				
					foreach ($round->suicide as $suicide) { 
										
						$suicider = $utility->playerSearch($suicide->initiator);
						
						if ($white) {
								echo '<div class="white">';
								echo '<table width="663"><tr><td width="30">';
						} else {
								echo '<div class="grey">';
								echo '<table width="663"><tr><td width="30">'; 
						}
						
						if ($players[$suicider]->getTeam() == 1 && !$switch) 
							echo "<img class='image' src='images/t.gif'/></td><td>";
						else if ($players[$suicider]->getTeam() == 1 && $switch)
							echo "<img class='image' src='images/ct.gif'/></td><td>";
						else if ($players[$suicider]->getTeam() == 2 && !$switch)
							echo "<img class='image' src='images/ct.gif'/></td><td>";
						else if ($players[$suicider]->getTeam() == 2 && $switch)
							echo "<img class='image' src='images/t.gif'/></td><td>";
						
						$players[$suicider]->loseFrag();
						$players[$suicider]->addDeath();
						echo $players[$suicider]->getAlias() . " [" . $players[$suicider]->getFrags() . "/" . $players[$suicider]->getDeaths() . "] commits suicide.</td></tr></table></div>";
						
						if ($white)
							$white = FALSE;
						else
							$white = TRUE;	
					} # </suicides>
	
		  
					# Set win id to the win Type
					# [1 = CT frag, 2 = CT defuse, 3 = CT timer, 4 = T frag, 5 = T bomb]
					foreach ($round->win as $win) {
						switch ($win[id]) {
							case 1: 
								if (!$switch)
									$team2->addScore();
								else
									$team1->addScore();
								echo '<div class="ct">';
								echo '<table width="663" height="26"><tr><td width="30"></td><td style="color:#FFF;">';
								echo "Counter Terrorists win by elimination.";
								echo '</td><td width="300" align="right" style="color:#FFF;">';
								echo $team1->getName() . ":" . $team1->getScore() ." " . $team2->getName() . ":" . $team2->getScore();
								echo '</td><td width="30"></td></tr></table></div>';
								break;
							case 2:
								if (!$switch)
									$team2->addScore();
								else
									$team1->addScore();
								echo '<div class="ct">';
								echo '<table width="663" height="26"><tr><td width="30"></td><td style="color:#FFF;">';
								echo "Counter Terrorists win by bomb defusal.";
								echo '</td><td width="260" align="right" style="color:#FFF;">';
								echo $team1->getName() . ":" . $team1->getScore() ." " . $team2->getName() . ":" . $team2->getScore();
								echo '</td><td width="30"></td></tr></table></div>';
								break;
							case 3:
								if (!$switch)
									$team2->addScore();
								else
									$team1->addScore();
								echo '<div class="ct">';
								echo '<table width="663" height="26"><tr><td width="30"></td><td style="color:#FFF;">';
								echo "Counter Terrorists win by default.";
								echo '</td><td width="300" align="right" style="color:#FFF;">';
								echo $team1->getName() . ":" . $team1->getScore() ." " . $team2->getName() . ":" . $team2->getScore();
								echo '</td><td width="30"></td></tr></table></div>';
								break;
							case 4:
								if (!$switch)
									$team1->addScore();
								else
									$team2->addScore();
								echo '<div class="t">';
								echo '<table width="663" height="26"><tr><td width="30"></td><td style="color:#FFF;">';
								echo "Terrorists win by elimination.";
								echo '</td><td width="300" align="right" style="color:#FFF;">';
								echo $team1->getName() . ":" . $team1->getScore() ." " . $team2->getName() . ":" . $team2->getScore();
								echo '</td><td width="30"></td></tr></table></div>';
								break;
							case 5:
								if (!$switch)
									$team1->addScore();
								else
									$team2->addScore();
								echo '<div class="t">';
								echo '<table width="663" height="26"><tr><td width="30"></td><td style="color:#FFF;">';
								echo "Terrorists win by bomb explosion.";
								echo '</td><td width="300" align="right" style="color:#FFF;">';
								echo $team1->getName() . ":" . $team1->getScore() ." " . $team2->getName() . ":" . $team2->getScore();
								echo '</td><td width="30"></td></tr></table></div>';
								break;
						} # </switch>			
					} # </win>
				} # </round>			
			} # </half>
		} # </match>
	} # </goblincast>
?>