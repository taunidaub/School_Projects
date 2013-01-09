// JavaScript Document

function showResult(str)
{
	if (str.length==0)
	{
		document.getElementById("livesearch").innerHTML="";
		document.getElementById("livesearch").style.border="0px";
		return;
	}
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
			document.getElementById("livesearch").style.border="1px solid #A5ACB2";
		}
	}
	xmlhttp.open("GET","../livesearch.php?q="+str,true);
	xmlhttp.send();
}

//To include_once a page, invoke ajaxinclude("afile.htm") in the BODY of page
//Included file MUST be from the same domain as the page displaying it.

var rootdomain="http://"+window.location.hostname

function ajaxinclude(url) {
	var page_request = false
	if (window.XMLHttpRequest) // if Mozilla, Safari etc
		page_request = new XMLHttpRequest()
	else if (window.ActiveXObject){ // if IE
		try {
			page_request = new ActiveXObject("Msxml2.XMLHTTP")
		} 
		catch (e){
			try{
				page_request = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (e){}
		}
	}
	else
		return false
		
	page_request.open('GET', url, false) //get page synchronously 
	page_request.send(null)
	writecontent(page_request)
}
	
function writecontent(page_request){
	if (window.location.href.indexOf("http")==-1 || page_request.status==200)
		document.write(page_request.responseText)
}
