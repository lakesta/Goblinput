<?php

// Save the data from the GET function

	$team1 = $_GET["team1"];
	$team2 = $_GET["team2"];
	$team1tag = $_GET["team1tag"];
	$team2tag = $_GET["team2tag"];
	$map = $_GET["map"];
	$maxroundz = $_GET["maxroundz"];
	$notes = $_GET["notes"];
	$eventt = $_GET["eventt"];
	$ename = $_GET["ename"];
	$ot = $_GET["ot"];
	$otmaxroundz = $_GET["otmaxroundz"];
	$totalhalves = $_GET["totalhalves"];
	$userid = $_GET["userid"];


// RUN the Parser.

	require_once ('goblinput.php');
	goblinput($team1, $team1tag, $team2, $team2tag, $map, $maxroundz, $notes, $eventt, $ename, $ot, $otmaxroundz, $totalhalves, $userid);

// Output the link to the new .GCS File

	echo "<a href=/goblinput/gcs/$userid" . ".gcs>Click here to view your .GCS file, Right click -> Save As to save</a><br/><br/>";
	
	echo "<a href=gcast.php?id=" .$userid . ">Click here to view your Goblincast&trade; game log.</a><br/><br/>";
	
	echo "<a href=gsense.php?id=" .$userid . ">Click here to view your Goblinsense&trade; team score board.</a><br/><br/>";
	
	echo "<a href=gpsense.php?id=" .$userid . ">Click here to view your Goblinsense&trade; player statistics.</a><br/><br/>";

// Ask the user which data to be displayed in statistics in form



// Submit to Step 4 (Statistics Display using .GCS file + HTML Code)
?>