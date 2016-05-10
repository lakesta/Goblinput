// JavaScript Document

function noOvertime() { 
document.getElementById('ots').style.visibility = 'hidden'; 
document.getElementById('ots').style.display = 'none';
document.getElementById('ots2').style.visibility = 'hidden'; 
document.getElementById('ots2').style.display = 'none';
document.getElementById('ot2').src = "images/emptyradio.jpg";
document.getElementById('ot1').src = "images/filledradio.jpg";
document.getElementById('ot').value = "No";
} 

function Overtime() { 
document.getElementById('ots').style.visibility = 'visible'; 
document.getElementById('ots').style.display = 'block';
document.getElementById('ots2').style.visibility = 'visible'; 
document.getElementById('ots2').style.display = 'block';
document.getElementById('ot1').src = "images/emptyradio.jpg";
document.getElementById('ot2').src = "images/filledradio.jpg";
document.getElementById('ot').value = "Yes";
} 

function Scrim() { 
document.getElementById('eventname').style.visibility = 'hidden'; 
document.getElementById('eventname').style.display = 'none';

document.getElementById('scrim').src = "images/filledradio.jpg";
document.getElementById('match').src = "images/emptyradio.jpg";
document.getElementById('lan').src = "images/emptyradio.jpg";
document.getElementById('eventt').value = "Scrim";
}

function Match() { 
document.getElementById('eventname').style.visibility = 'visible'; 
document.getElementById('eventname').style.display = 'block';

document.getElementById('scrim').src = "images/emptyradio.jpg";
document.getElementById('match').src = "images/filledradio.jpg";
document.getElementById('lan').src = "images/emptyradio.jpg";
document.getElementById('eventt').value = "Match";
}

function LAN() { 
document.getElementById('eventname').style.visibility = 'visible'; 
document.getElementById('eventname').style.display = 'block';

document.getElementById('scrim').src = "images/emptyradio.jpg";
document.getElementById('match').src = "images/emptyradio.jpg";
document.getElementById('lan').src = "images/filledradio.jpg";
document.getElementById('eventt').value = "LAN";
}

function otFive() {
document.getElementById('ot5').src = "images/filledradio.jpg";
document.getElementById('ot3').src = "images/emptyradio.jpg";
document.getElementById('otmaxroundz').value = "5";
}

function otThree() {
document.getElementById('ot3').src = "images/filledradio.jpg";
document.getElementById('ot5').src = "images/emptyradio.jpg";
document.getElementById('otmaxroundz').value = "3";
}

function mrFifteen() {
document.getElementById('mr15').src = "images/filledradio.jpg";
document.getElementById('mr12').src = "images/emptyradio.jpg";
document.getElementById('maxroundz').value = "15";
}

function mrTwelve() {
document.getElementById('mr12').src = "images/filledradio.jpg";
document.getElementById('mr15').src = "images/emptyradio.jpg";
document.getElementById('maxroundz').value = "12";
}
function one() {
	window.location = "#1";
	if (document.iframesfix)
		document.getElementById('ajaxnav').setAttribute('src', 'mock-page.php?step=1');
	var divCustomerInfo = document.getElementById("Step1");
	var divCustomerInfo2 = document.getElementById("Step2");
	
	
	if (divCustomerInfo) {
		divCustomerInfo.style.visibility = "visible";
		divCustomerInfo.style.display = "block";
		divCustomerInfo2.innerHTML = "";
	}
}
function two() {

	var ename = document.getElementById('ename').value;
	var team1 = document.getElementById("team1").value;
	var team2 = document.getElementById("team2").value;
	var team1tag = document.getElementById("team1tag").value;
	var team2tag = document.getElementById("team2tag").value;
	var ottimes = document.getElementById("ottimes").value;
	var map = document.getElementById("map").value;
	var notes = document.getElementById("notes").value;
	var ot = document.getElementById("ot").value;
	var otmaxroundz = document.getElementById("otmaxroundz").value;
	var eventt = document.getElementById("eventt").value;
	var maxroundz = document.getElementById("maxroundz").value;
	var id = document.getElementById("id").value;
	
	var oXmlHttp = new XMLHttpRequest();
	oXmlHttp.open("GET", "step2.php?team1=" + team1 + "&team2=" + team2 + "&ename=" + ename + "&map=" + map + "&notes=" + notes + "&ot=" + ot + "&otmaxroundz=" + otmaxroundz + "&eventt=" + eventt + "&maxroundz=" + maxroundz + "&id=" + id + "&ottimes=" + ottimes + "&team1tag=" + team1tag + "&team2tag=" + team2tag, true);
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (oXmlHttp.status == 200) {
				displayStepTwo(oXmlHttp.responseText);
			} else {
				displayStepTwo("An error occurred: " + oXmlHttp.statusText); //statusText is not always accurate
			}
		}            
	};
	oXmlHttp.send(null);
}

function displayStepTwo(sText) {
	window.location = "#2";
	if (document.iframesfix)
		document.getElementById('ajaxnav').setAttribute('src', 'mock-page.php?step=2');
	var divCustomerInfo = document.getElementById("Step1");
	var divCustomerInfo2 = document.getElementById("Step2");
	var divCustomerInfo3 = document.getElementById("Step3");
	divCustomerInfo2.style.visibility = "visible";
	divCustomerInfo2.innerHTML = sText;
	divCustomerInfo2.style.display = "block";
	//divCustomerInfo.innerHTML = "";
	divCustomerInfo.style.visibility = "hidden";
	divCustomerInfo.style.display = "none";
	divCustomerInfo3.style.visibility = "hidden";
}

function three() {
				
	var team1 = document.getElementById("team1").value;
	var team2 = document.getElementById("team2").value;
	var team1tag = document.getElementById("team1tag").value;
	var team2tag = document.getElementById("team2tag").value;
	var map = document.getElementById("map").value;
	var maxroundz = document.getElementById("maxroundz").value;
	var notes = document.getElementById("notes").value;
	var eventt = document.getElementById("eventt").value;
	var ename = document.getElementById("ename").value;
	var ot = document.getElementById("ot").value;
	var otmaxroundz = document.getElementById("otmaxroundz").value;
	var totalhalves = document.getElementById("totalhalves").value;
	var userid = document.getElementById("userid").value;
	
	
	var oXmlHttp = new XMLHttpRequest();
	oXmlHttp.open("GET", "step3.php?team1=" + team1 + "&team2=" + team2 + "&map=" + map + "&maxroundz=" + maxroundz + "&notes=" + notes +"&eventt=" + eventt + "&ename=" + ename + "&ot=" + ot + "&otmaxroundz=" + otmaxroundz +  "&totalhalves=" + totalhalves + "&userid=" + userid + "&team1tag=" + team1tag + "&team2tag=" + team2tag, true);
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (oXmlHttp.status == 200) {
				displayStepThree(oXmlHttp.responseText);
			} else {
				displayStepThree("An error occurred: " + oXmlHttp.statusText); //statusText is not always accurate
			}
		}            
	};
	oXmlHttp.send(null);
}

function displayStepThree(sText) {
	window.location = "#3";
	if (document.iframesfix)
		document.getElementById('ajaxnav').setAttribute('src', 'mock-page.php?step=3');
	var divCustomerInfo2 = document.getElementById("Step2");
	var divCustomerInfo3 = document.getElementById("Step3");
	divCustomerInfo3.style.visibility = "visible";
	divCustomerInfo3.innerHTML = sText;
	divCustomerInfo3.style.display = "block";
	//divCustomerInfo.innerHTML = "";
	divCustomerInfo2.style.visibility = "hidden";
	divCustomerInfo2.style.display = "none";
}