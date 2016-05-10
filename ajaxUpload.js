// JavaScript Document
function CDownloadUrl(method, url, func) {
   var httpObj;
   var browser = navigator.appName;
   if(browser.indexOf("Microsoft") > -1)
      httpObj = new ActiveXObject("Microsoft.XMLHTTP");
   else
      httpObj = new XMLHttpRequest();
 
   httpObj.open(method, url, true);
   httpObj.onreadystatechange = function() {
      if(httpObj.readyState == 4){
         if (httpObj.status == 200) {
            var contenttype = httpObj.getResponseHeader('Content-Type');
            if (contenttype.indexOf('xml')>-1) {
               func(httpObj.responseXML);
            } else {
               func(httpObj.responseText);
            }
         } else {
            func('Error: '+httpObj.status);
         }
      }
   };
   httpObj.send(null);
}

function jsUpload(upload_field, x){    
	// this is just an example of checking file extensions    
	// if you do not need extension checking, remove     
	// everything down to line    
	// upload_field.form.submit();   
	var re_text = /\.txt|\.xml|\.log/i;    
	var filename = upload_field.value;    
	/* Checking file type */    
	if (filename.search(re_text) == -1)    
	{        
		alert("File does not have text(txt, xml, log) extension");        
		upload_field.form.reset();        
		return false;    
	}    
	upload_field.form.submit();
 	setTimeout("getProgress("+x+")", 100); 
	//document.getElementById('upload_status').value = "uploading file...";    
	upload_field.disabled = true;    
	return true;
}