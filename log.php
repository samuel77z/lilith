<?php 
if(array_key_exists('get',$_REQUEST))
{
	while(!@rename('d/log','d/olog'))
	{
		sleep(1);
	}
	$str=file_get_contents('d/olog');
	echo $str;
	return;
}
if(!file_exists('d/olog'))
 file_put_contents('d/olog','starting up');
?>
<html>
<head>
<style>
body {
 margin:1px;
 font: smaller;
}
ul {
 margin:0;
}
fieldset {
padding:0px;
border: 1px green solid;
}
fieldset legend {
	font-size: 0.5em;
 color: red;
 padding:2px;
 margin: 1px;
}
li {
 font-size:0.8em;
}

li pre {
 margin: 1px;
 color: blue;
}

</style>
<script type="text/javascript">
function loadLog() {
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			var fs=document.createElement("fieldset");
			var legend=document.createElement("legend");
			var d = new Date();
			legend.innerHTML=d.toLocaleTimeString();
			fs.appendChild(legend);
			var ul=document.createElement("ul");
			ul.innerHTML=xmlhttp.responseText;
			fs.appendChild(ul);
			document.body.appendChild(fs);
			fs.scrollIntoView({block: "end", behavior: "smooth"});
			loadLog();
		}
	}
	xmlhttp.open("GET","log.php?get",true);
	xmlhttp.send();
}

function myFunction(event) {
	var tn,sn;
	sn=document.body;
	for(tn=event.target;!sn.isSameNode(tn);tn=tn.parentNode)
	{
	while(tn.previousSibling)
	 tn.parentNode.removeChild(tn.previousSibling);
	}
}
</script>
</head>
<body onload="loadLog()" onclick="myFunction(event)">
<div>Nothing yet</div>
</body>
</html>
