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

# @todo
# Fix overtime stuff
# Addon for charges only game type
	
	
############################################################################################################
	
	# Grab class files for Team and Player
	require_once ('Team.class.php');
	require_once ('Player.class.php');
	require_once ('Utility.class.php');
	
	######## BEGIN INITIALIZATION #########
	function goblinsense($userid) {
		
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
			<div id="centr2">
				<table >
					<tr>
						<td width="95" valign="top">
							<table width="95" class="brown" align="right">
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
							<table width="172" class="black">
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
							<table width="203">
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
				</div>
				<img src="images/line.jpg"/>
				<div class="chart">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td><table width="130" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg"><table cellpadding="0" cellspacing="0" width="130"><tr><td width="23" align="right"></td><td class="blackbg" width="107" >1st Half</td></tr></table></td></tr><tr><td class="whiteb"><table cellpadding="0" cellspacing="0" width="130"><tr><td width="23" align="right"><img src="images/t.gif"/></td><td class="whiteb" width="107" ><?php echo $team1->getName(); ?></td></tr></table></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="greyb"><table width="130" cellpadding="0" cellspacing="0"><tr><td width="23" align="right"><img src="images/ct.gif"/></td><td class="greyb"><?php echo $team2->getName(); ?></td></tr></table></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td height="20">&nbsp;</td></tr></table></td>			
<?php			
			
			$half = 0;
			foreach ($gcs->match->half as $halves) {
				$half++;
				$white = TRUE;
				if ($half % 2 == 0 && $half > 1) { 
					if ($half < 3) {
?>
						<td><table width="48" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg">HALF</td></tr>
							  <tr><td class="white"><?php echo $team1->getScore(); ?></td><tr>
							  <td class="tdline" ><img src="images/3px.gif"/></td></tr></tr><tr><td class="grey"><?php echo $team2->getScore(); ?></td></tr>
							  <tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td height="20">&nbsp;</td></tr>
							  </table>
						</td>
						</tr>
				  </table>
				</div>
				<div class="chart">
					<table  cellpadding="0" cellspacing="0">
						<tr>
							<td><table width="130" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg"><table cellpadding="0" cellspacing="0" width="130"><tr><td width="23" align="right"></td><td class="blackbg">2nd Half</td></tr></table></td></tr><tr><td class="whiteb"><table cellpadding="0" cellspacing="0" width="130"><tr><td width="23" align="right"><img src="images/ct.gif"/></td><td class="whiteb"><?php echo $team1->getName(); ?></td></tr></table></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="greyb"><table width="130" cellpadding="0" cellspacing="0"><tr><td width="23" align="right"><img src="images/t.gif"/></td><td class="greyb"><?php echo $team2->getName(); ?></td></tr></table></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td height="20">&nbsp;</td></tr></table></td>
<?php					

					} else {
?>
						<td><table width="48" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg">HALF</td></tr><tr><td class="white"><?php echo $team1->getScore(); ?></td><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr></tr><tr><td class="grey"><?php echo $team2->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td height="20">&nbsp;</td></tr></table></td>
						</tr>
				  </table>
				</div>
				<div class="chart">
					<table  cellpadding="0" cellspacing="0">
						<tr>
							<td><table width="130" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg"><table cellpadding="0" cellspacing="0" width="130"><tr><td width="23" align="right"></td><td width="107" class="blackbg">
							  <?php 
							  	switch ($numOfOT) {
									case 3:
										$ending = "rd";
										break;
									default: 
										$ending = "th";
								}
							  echo $numOfOT . $ending; 
							  ?> Half
							  
							  </td></tr></table></td></tr><tr><td class="whiteb"><table cellpadding="0" cellspacing="0" width="130"><tr><td width="23" align="right"><img src="images/t.gif"/></td><td class="whiteb"><?php echo $team1->getName(); ?></td></tr></table></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="greyb"><table width="130" cellpadding="0" cellspacing="0"><tr><td width="23" align="right"><img src="images/ct.gif"/></td><td class="greyb"><?php echo $team2->getName(); ?></td></tr></table></td></tr><tr><td class="tdline"><img src="images/3px.gif"/></td></tr><tr><td height="20">&nbsp;</td></tr></table></td>
<?php
						$switch = FALSE;
					}

					$switch = TRUE;
				}

				if ($half % 2 == 1 && $half > 1) {
?>
				<td><table width="48" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg">HALF</td></tr><tr><td class="white"><?php echo $team1->getScore(); ?></td><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr></tr><tr><td class="grey"><?php echo $team2->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td height="20">&nbsp;</td></tr></table></td>
						</tr>
				  </table>
				</div>
				<div class="chart">
					<table  cellpadding="0" cellspacing="0">
						<tr>
							<td><table width="130" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg"><table cellpadding="0" cellspacing="0" width="130"><tr><td width="23" align="right"></td>
							  <td class="blackbg">Half</td>
							  </tr></table></td></tr><tr><td class="whiteb">
							  <table cellpadding="0" cellspacing="0" width="130">
							  	<tr><td width="23" align="right"><img src="images/ct.gif"/></td><td class="whiteb"><?php echo $team1->getName(); ?></td></tr>
							  </table></td></tr>
							  <tr><td class="tdline" ><img src="images/3px.gif"/></td></tr>
							  <tr><td class="greyb"><table width="130" cellpadding="0" cellspacing="0"><tr><td width="23" align="right"><img src="images/t.gif"/></td>
							  <td class="greyb"><?php echo $team2->getName(); ?></td></tr></table></td></tr>
							  <tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td height="20">&nbsp;</td></tr></table></td>
<?php					
					$switch = TRUE;
				}

				foreach ($halves->round as $round) {
?>
											<td><table width="33" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg"><?php echo $round['id']; ?></td></tr>
<?php				
				# Set win id to the win Type
				# [1 = CT frag, 2 = CT defuse, 3 = CT timer, 4 = T frag, 5 = T bomb]
				foreach ($round->win as $win) {
					switch ($win[id]) {
						case 1: 
							if (!$switch) {
								$team2->addScore();
?>
<tr><td class="white">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey"><?php echo $team2->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/1.gif"/></td></tr></table></td>
<?php
							} else {
								$team1->addScore();
?>
<tr><td class="white"><?php echo $team1->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/1.gif"/></td></tr></table></td>
<?php
							}
							break;
						case 2:
							if (!$switch) {
								$team2->addScore();
?>
<tr><td class="white">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey"><?php echo $team2->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/2.gif"/></td></tr></table></td>
<?php
							} else {
								$team1->addScore();
?>
<tr><td class="white"><?php echo $team1->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/2.gif"/></td></tr></table></td>
<?php
							}
							break;
						case 3:
							if (!$switch) {
								$team2->addScore();
?>
<tr><td class="white">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey"><?php echo $team2->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/3.gif"/></td></tr></table></td>
<?php
							} else {
								$team1->addScore();
?>
<tr><td class="white"><?php echo $team1->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/3.gif"/></td></tr></table></td>
<?php
							}
							break;
						case 4:
							if (!$switch) {
								$team1->addScore();
?>
<tr><td class="white"><?php echo $team1->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/4.gif"/></td></tr></table></td>
<?php
							} else {
								$team2->addScore();
?>
<tr><td class="white">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey"><?php echo $team2->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/4.gif"/></td></tr></table></td>
<?php
							}
							break;
						case 5:
							if (!$switch) {
								$team1->addScore();
?>
<tr><td class="white"><?php echo $team1->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/5.gif"/></td></tr></table></td>
<?php
							} else {
								$team2->addScore();
?>
<tr><td class="white">-</td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td class="grey"><?php echo $team2->getScore(); ?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td align="center"><img src="images/5.gif"/></td></tr></table></td>
<?php
							}
							break;
					}			
				}			
			}
		}
?>
							<td><table width="48" cellpadding="0" cellspacing="0">
							  <tr><td class="blackbg">FINAL</td></tr><tr><td class="white"><?php echo $team1->getScore(); ?></td><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr></tr><tr><td class="grey"><?php echo $team2->getScore();?></td></tr><tr><td class="tdline" ><img src="images/3px.gif"/></td></tr><tr><td height="20">&nbsp;</td></tr></table></td>
						</tr>
				  </table>
				</div>
<?php	
		}
	}
?>