<html>
<head>
<title>Goblinventions&trade; Presents: Goblincast&trade;</title>
<link href="goblincast.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="goblincast">
	
	<div class="goblinventions"><img src="images/image_02.jpg" width="1"/><img src="images/edit.jpg"  width="278"/><img src="images/image_05.jpg"  width="358"/><img src="images/image_07.jpg" width="30"/><img src="images/ginput.gif" alt="ginput"  width="25"/><img src="images/image_07.jpg" width="232"/><img src="images/image_02.jpg"  width="1"/></div>
	</div>


	<div class="linkHeader"><img src="images/image_08.jpg" /><img src="images/image_11.jpg" /><img src="images/image_12.jpg" /><img src="images/image_13.jpg" /><img src="images/image_12.jpg" /><img src="images/image_15.jpg" /><img src="images/image_12.jpg" /><img src="images/image_17.jpg" /><img src="images/image_12.jpg" /><img src="images/image_19.jpg" /><img src="images/image_08.jpg" /></div>
	
	
	<div class="contentBG">
		<div class="contentInnerBG">
			<div class="giBG" id="giBG">
				
				<div id="goblincastlogo">
					<img src="images/goblincast.gif"/>
				</div>
				
				<?php

					require_once('goblincast.php');
					$gcastID = $_GET["id"];
					
					if (is_null($gcastID))
						echo "Please supply a Goblin Cast ID # for the .GCS log you would like read.";
					else
						goblincast($gcastID);
				
				?>
			</div>

			
			
			<div class="disclaimer">
				<img src="images/disclaimer.jpg"alt="" />
			</div>
		</div>
	</div>
</div>
</body>
</html>
