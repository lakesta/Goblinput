<?php
	$team1 = $_GET["team1"];
	$team2 = $_GET["team2"];
	$team1tag = $_GET["team1tag"];
	$team2tag = $_GET["team2tag"];
	$ottimes = $_GET["ottimes"];
	$ename = $_GET["ename"];
	$map = $_GET["map"];
	$notes = $_GET["notes"];
	$ot = $_GET["ot"];
	$otmaxroundz = $_GET["otmaxroundz"];
	$eventt = $_GET["eventt"];
	$maxroundz = $_GET["maxroundz"];
	$id = $_GET["id"];
	
	$totalHalves = 2;
	
	if ($ot == "Yes") {
		$totalHalves += ($ottimes * 2);
	}
	
?>
				<div class="giLeft">	
					<fieldset><legend>Step 2</legend></fieldset>
					
				
<?php
	for ($x = 1; $x <= $totalHalves; $x++) {
?>												
<form action="<?=$PHP_SELF;?>" target="upload_iframe" method="post" enctype="multipart/form-data">					
					<div class="giData<?php echo $x; ?>" style="height: 35px">
						<div class="giDataLeft2" style="height:25px;">
							<label for="half<?php echo $x; ?>">Half #<?php echo $x; ?></label>
						</div>
						<div class="giDataRight2">
<input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?php echo $id . "-" . $x;?>"/>
<input type="hidden" name="fileframe" value="true">
<input name="file" type="file" id="file" style="font-size: 20px;" onChange="jsUpload(this, <?php echo $x; ?>)">
<img id="check<?php echo $x; ?>" src="images/red.jpg" height="0" width="2" />						 
<input type="hidden" id="x" name="x" value="<?php echo $x; ?>">
<input type="hidden" id="uid" name="uid" value="<?php echo $id; ?>">
							
						</div>
					</div>
</form>
<?php } ?>
				
				</div>
				<div class="giRight">
					<input src="images/step3.jpg" type="image" value="submit" onclick="three()" />
				</div>
				<input id="team1" type="hidden" value="<?php echo $team1; ?>" />
				<input id="team2" type="hidden" value="<?php echo $team2; ?>" />
				<input id="team1tag" type="hidden" value="<?php echo $team1tag; ?>" />
				<input id="team2tag" type="hidden" value="<?php echo $team2tag; ?>" />
				<input id="map" type="hidden" value="<?php echo $map; ?>" />
				<input id="maxroundz" type="hidden" value="<?php echo $maxroundz; ?>" />
				<input id="notes" type="hidden" value="<?php echo $notes; ?>" />
				<input id="eventt" type="hidden" value="<?php echo $eventt; ?>" />
				<input id="ename" type="hidden" value="<?php echo $ename; ?>" />
				<input id="ot" type="hidden" value="<?php echo $ot; ?>" />
				<input id="otmaxroundz" type="hidden" value="<?php echo $otmaxroundz; ?>" />
				<input id="totalhalves" type="hidden" value="<?php echo $totalHalves; ?>" />
				<input id="userid" type="hidden" value="<?php echo $id; ?>">
				