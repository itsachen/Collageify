//Not really ajax but who cares
function getThumb()
{

var name= document.getElementById("thumb").value;

var xmlhttp;
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
	    var id= xmlhttp.responseText;
		var src= "http://collagify.com/collagify/imgdata/collages/" + id + "/base_thumb.jpg";
		document.getElementById("thumbpic").innerHTML="<img src=\"" + src + "\" />";
		
		$(document).ready(function(){
			$('#thumbpic').fadeIn('slow', function() {
			});
			showAlbums2();
		});
		
	    }
	  }
	
	xmlhttp.open("GET","ajax.php?id=thumb&name=" + name,true);
	xmlhttp.send();	
}

function showAlbums1(){
	var userid= document.getElementById("useridlol").innerHTML;
	
	var xmlhttp;
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
	    document.getElementById("albumselection1").innerHTML=xmlhttp.responseText;
	
		$(document).ready(function(){
			$('#albumselection1').fadeIn('slow', function() {
			});
		});
	    }
	  }
	xmlhttp.open("GET","ajax.php?id=getalbum&uid=" + userid,true);
	xmlhttp.send();
}

function showAlbums2(){
	var userid= document.getElementById("useridlol").innerHTML;
	
	var xmlhttp;
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
	    document.getElementById("albumselection2").innerHTML=xmlhttp.responseText;
	
		$(document).ready(function(){
			$('#albumselection2').fadeIn('slow', function() {
			});
		});
	    }
	  }
	xmlhttp.open("GET","ajax.php?id=getalbum&uid=" + userid,true);
	xmlhttp.send();
}

function showAlbums3(){
	var userid= document.getElementById("useridlol").innerHTML;
	
	var xmlhttp;
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
	    document.getElementById("albumselection3").innerHTML=xmlhttp.responseText;
	
		$(document).ready(function(){
			$('#albumselection3').fadeIn('slow', function() {
			});
		});
	    }
	  }
	xmlhttp.open("GET","ajax.php?id=getalbum&uid=" + userid,true);
	xmlhttp.send();
}

//Adding a new collage
function newCollage()
{
	var userid= document.getElementById("useridlol").innerHTML;
	var name= document.getElementById("newname").value;
	
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	var url= "db.php?name="+ name+"&uid="+userid;
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
	showAlbums1();
}

function uploadAlbum(aid,uid,cid){
	var xmlhttp;
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
		var id= xmlhttp.responseText;
		window.location="../scripts/test.php"
	    }
	  }
	xmlhttp.open("GET","ajax.php?id=uploadalbum&aid=" + aid + "&uid=" + uid + "&cid=" + cid,true);
	xmlhttp.send();
}

function getContributed(){
	var userid= document.getElementById("useridlol").innerHTML;
	
	var xmlhttp;
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
	    document.getElementById("contributed").innerHTML=xmlhttp.responseText;
	
		$(document).ready(function(){
			$('#contributed').fadeIn('slow', function() {
			});
		});
	    }
	  }
	xmlhttp.open("GET","ajax.php?id=getcontrib&uid=" + userid,true);
	xmlhttp.send();
}

//File upload stuff
function ajaxFileUpload(upload_field){
	document.getElementById('loading').innerHTML = '<div><img src="images/loading.gif" border="0" /></div>';
}