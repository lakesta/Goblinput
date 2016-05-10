<?php
$id = uniqid("");

$upload_dir = "logs/"; // Directory for file storing                                            
							   // filesystem path
$web_upload_dir = "logs/"; // Directory for file storing                          
						  // Web-Server dir 
						  						  
// FILEFRAME section of the script
if ($_POST['fileframe']) {    
	if (isset($_FILES['file']))  { // file was sent from browser        
		if ($_FILES['file']['error'] == UPLOAD_ERR_OK) { // no error
			$x = $_POST['x'];
			$uid = $_POST['uid'];
			$bits = explode('.',$_FILES['file']['name']);
    		$ext = array_pop($bits);

			// File rename process:	
			$_FILES['file']['name'] = "Goblinput_" . $uid . "-" . $x;
			//Change filename to it's demoID and same extension                
			
			
			$filename = $_FILES['file']['name']; // file name
			move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir.'/'.$filename);            
			$result = 'OK';  
			exit();      
		}        
		elseif ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE) {            
			$result_msg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			exit();     			
		} else {         
			$result_msg = 'Unknown error'; 
			exit();   
		}        
	}        
}
// FILEFRAME section END
						  
?>
<html>
<head>
<title>Goblinventions&trade; Presents: Goblinput&trade;</title>
<link href="goblinput.css" rel="stylesheet" type="text/css" />
<script src="goblinput.js" type="text/javascript"></script>
<script src="back.js" type="text/javascript"></script>
<script type='text/javascript' src='timer.js'></script>
<script type="text/javascript" src="ajaxUpload.js"></script>
<script type="text/javascript">
function getProgress(x){
 CDownloadUrl('get', "getprogress.php?progress_key=<?php echo($id)?>-"+x,
    function(percent) {
      document.getElementById("check"+x).style.height = percent/100*20 +"px";
	  
      if (percent < 100){
        setTimeout("getProgress("+x+")", 100);
      } else {
	  	document.getElementById("check"+x).src = "images/check.gif"
		document.getElementById("check"+x).style.height = "20px";
		document.getElementById("check"+x).style.width = "20px";
	  }
    }
  );
}
</script>
</head>

<body>
<script language="javascript" type="text/javascript">
			if(document.iframesfix) {
				var windowlocator = new PageLocator("window.location.href", "#");
				//document.write(windowlocator.getHash());
				document.write("<iframe id='ajaxnav' name='ajaxnav' src='mock-page.php?step="+windowlocator.getHash()+"' style='display: none; visibility:hidden;'></iframe>"); 
			}
</script>
<div class="goblinput">
	
	<div class="goblinventions"><img src="images/image_02.jpg" width="1"/><img src="images/edit.jpg"  width="278"/><img src="images/image_05.jpg"  width="358"/><img src="images/image_07.jpg" width="30"/><img src="images/ginput.gif" alt="ginput"  width="25"/><img src="images/image_07.jpg" width="232"/><img src="images/image_02.jpg"  width="1"/></div>
	</div>


	<div class="linkHeader"><img src="images/image_08.jpg" /><img src="images/image_11.jpg" /><img src="images/image_12.jpg" /><img src="images/image_13.jpg" /><img src="images/image_12.jpg" /><img src="images/image_15.jpg" /><img src="images/image_12.jpg" /><img src="images/image_17.jpg" /><img src="images/image_12.jpg" /><img src="images/image_19.jpg" /><img src="images/image_08.jpg" /></div>
	
	
	<div class="contentBG">
		<div class="contentInnerBG">
			<div class="content">
				<div class="contentLeft">
					<div class="mainImage">
						<img src="images/mainimage.jpg" />
					</div>
					<span class="headerMain">Overview</span><br/><br/>
					Goblinput™ was created to parse counter-strike .logs into XML files with a defined standard (GCS).
					Using the GCS files, Goblinput™ can generate all types of information about the counter-strike match.  
					From game scores to how rounds were won, player statistics and a new scoring system, Goblinput™ does almost 			                    everything.  However, if there are things that you think could be improved we’d love to hear from you.  
					Just head on over to the comments page and send us a note.  If there are any advanced features you think should be 	                    implemented, be sure to let us know those too!  Also be sure to check out the About page to see                      				                    enhancements we are thinking of adding in the future.
				</div>
				<div class="contentRight">
					<span class="headerMain">Instructions</span><br/>
					<div class="roundcont">
					   <div class="roundtop">
						 <img src="images/ltcorner.gif" width="10" height="10" alt="" class="corner" />
					   </div>
					
						  <p>01 Get your .log files ready</p>
						  <p>---</p><p>
						  You can find your log files in the \cstrike\logs\ folder on your server.  
						  There will be a lot of them so you need to know which match you're looking for.  
						  Matches are normally stored in more than one .log file because each map change creates a new one.  
						  After you find all of the .log files for your match some editing will be necessary to ensure that 
						  pregame rounds are not included in the output. </p>

					</div>

				</div>
			</div><br/>
			<div class="giBG" id="giBG">
				
				
				
				<!-- Part 1 -->
				
				<div id="Step1">	
					
					
					
					<div class="giLeft">	
					
					<fieldset><legend>Step 1</legend></fieldset>
					
													
						<div class="giData">
							<div class="giDataLeft">
								<label for="team1">Team 1</label>
							</div>
							<div class="giDataRight">
								<input class="giForm2" id="team1" type="text" size="23" />
							</div>
						</div>
						
						<div class="giData">
							<div class="giDataLeft">
								<label for="team1tag">Team 1 Tag</label>
							</div>
							<div class="giDataRight">
								<input class="giForm3" id="team1tag" type="text" size="23" />
							</div>
						</div>
						
						<div class="giData">
							<div class="giDataLeft">
								<label for="team2">Team 2</label>
							</div>
							<div class="giDataRight">
								<input class="giForm" id="team2" type="text" size="23" />
							</div>
						</div>
						
						<div class="giData">
							<div class="giDataLeft">
								<label for="team2tag">Team 2 Tag</label>
							</div>
							<div class="giDataRight">
								<input class="giForm" id="team2tag" type="text" size="23" />
							</div>
						</div>
											
						<div class="giData">
							<div class="giDataLeft">
								Overtime
							</div>
							<div class="giDataRight">
								<span onClick="noOvertime()" style="text-decoration:none; color:#000; cursor:pointer"><img id="ot1"  src="images/filledradio.jpg" /> No</span> <span onClick="Overtime()" style="text-decoration:none; color:#000; cursor:pointer"><img  src="images/emptyradio.jpg" id="ot2"/> Yes</span>
							</div>
						</div>
						
						<div class="giDataHidden" id="ots">
							<div class="giDataLeft">
								<label for="ots"># Overtimes</label>
							</div>
							<div class="giDataRight">
								<input class="giForm" id="ottimes" type="text" size="23" />
							</div>
						</div>
						
						<div class="giDataHidden" id="ots2">
							<div class="giDataLeft">
								OT Max Rounds
							</div>
							<div class="giDataRight">
								<span onClick="otFive()" style="text-decoration:none; color:#000; cursor:pointer"><img  id="ot5" src="images/filledradio.jpg" /> 5</span> <span onClick="otThree()" style="text-decoration:none; color:#000; cursor:pointer"><img  id="ot3" src="images/emptyradio.jpg" /> 3</span>
							</div>
						</div>
										
						<div class="giData">
							<div class="giDataLeft">
								Event
							</div>
							<div class="giDataRight">
								<span onClick="Scrim()" style="text-decoration:none; color:#000; cursor:pointer"><img  id="scrim" src="images/filledradio.jpg" /> Scrim</span> <span onClick="Match()" style="text-decoration:none; color:#000; cursor:pointer"><img  id="match" src="images/emptyradio.jpg" /> Match</span> <span onClick="LAN()" style="text-decoration:none; color:#000; cursor:pointer"><img id="lan"  src="images/emptyradio.jpg" /> LAN</span>
							</div>
						</div>
						
						<div id="eventname" class="giDataHidden2">
							<div class="giDataLeft">
								<label for="ename">Event Name</label>
							</div>
							<div class="giDataRight">
								<input class="giForm" id="ename" type="text" size="23" />
							</div>
						</div>
						
			
						<div class="giData">
							<div class="giDataLeft">
								<label for="map">Map</label>
							</div>
							<div class="giDataRight">
								<input class="giForm" id="map" type="text" size="23" />
							</div>
						</div>
						
						<div class="giData">
							<div class="giDataLeft">
								<label for="team2">Max Rounds</label>
							</div>
							<div class="giDataRight">
								<span onClick="mrFifteen()" style="text-decoration:none; color:#000; cursor:pointer"><img id="mr15"  src="images/filledradio.jpg" /> 15</span> <span onClick="mrTwelve()" style="text-decoration:none; color:#000; cursor:pointer"><img id="mr12"  src="images/emptyradio.jpg" /> 12</span>
							</div>
						</div>
						
						<div class="giData">
							<div class="giDataLeft">
								<label for="team2">Notes</label>
							</div>
							<div class="giDataRight">
								<input class="giForm" id="notes" type="text" size="23" />
							</div>
						</div>
					
					
					</div>
					<div class="giRight">
						<input src="images/submit.jpg" type="image" value="submit" onClick="two()" />
					</div>
					
					<input id="ot" type="hidden" value="No" />
					<input id="otmaxroundz" type="hidden" value="5" />
					<input id="eventt" type="hidden" value="Scrim" />
					<input id="maxroundz" type="hidden" value="15" />
					<input id="id" type="hidden" value="<?php echo $id; ?>" />
				
				</div>
				
				
				<!-- End of Part 1 -->
				
				
				
				<!-- Part 2 -->
				
				
				<div id="Step2">
					
				</div>
				
				
				<!-- End of Part 2 -->
				
				
				
				<!-- Part 3 -->
				
				
				<div id="Step3">
					
				</div>
				
				
				<!-- End of Part 3 -->
				

			</div>

			
			
			<div class="disclaimer">
				<img src="images/disclaimer.jpg"alt="" />
			</div>
		</div>
	</div>
</div>
<iframe name="upload_iframe" style="width: 400px; height: 100px; display: none;"></iframe>
</body>
</html>
