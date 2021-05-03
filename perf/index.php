<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
if(isset($_SESSION['Username']) ) {
  $_SESSION['Username']="";
}
?>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<title>Welcome to Performance Reporting Tool</title>

<link href="login-box.css" rel="stylesheet" type="text/css" />
</head>
<script language="JavaScript">
           var HttPRequest = false;

           function doCallAjax() {
                  HttPRequest = false;
                  if (window.XMLHttpRequest) { // Mozilla, Safari,...
                         HttPRequest = new XMLHttpRequest();
                         if (HttPRequest.overrideMimeType) {
                                HttPRequest.overrideMimeType('text/html');
                         }
                  } else if (window.ActiveXObject) { // IE
                         try {
                                HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
                         } catch (e) {
                                try {
                                   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
                                } catch (e) {}
                         }
                  } 
                  
                  if (!HttPRequest) {
                         alert('Cannot create XMLHTTP instance');
                         return false;
                  }
        
                  var url = 'login.php';
                  var pmeters = "tUsername=" + encodeURI( document.getElementById("txtUsername").value) +
                                                "&tPassword=" + encodeURI( document.getElementById("txtPassword").value );

                        HttPRequest.open('POST',url,true);

                        HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        HttPRequest.setRequestHeader("Content-length", pmeters.length);
                        HttPRequest.setRequestHeader("Connection", "close");
                        HttPRequest.send(pmeters);
                        
                        
                        HttPRequest.onreadystatechange = function()
                        {

                                if(HttPRequest.readyState == 3)  // Loading Request
                                {
                                        document.getElementById("mySpan").innerHTML = "Now is Loading...";
                                }

                                if(HttPRequest.readyState == 4) // Return Request
                                {
                                        if(HttPRequest.responseText == 'MOVE2MAIN')
                                        {
                                                window.location = 'main.php';
                                        }
                                        else
                                        {
                                                document.getElementById("mySpan").innerHTML = HttPRequest.responseText;
                                        }
                                }
                                
                        }

           }
document.onkeydown = function() {
		if (event.keyCode == 13) {
		   doCallAjax();
		}
	}


</script>

<body onload="JavaScript:txtUsername.focus();">


<div style="padding: 100px 0 0 250px;">


<div id="login-box">

<H2>Login<span id="mySpan"></span></H2>
<!--Performance Reporting Tool-->
<br />
<h3>Try our brand new website <a href="https://admapp.g-able.ga" style="color:white">HERE</a></h3>
<h4>Go to <a href="t.php" style="color:white">File Manager</a></h4>
<div id="login-box-name" style="margin-top:20px;">Username:</div>
<div id="login-box-field" style="margin-top:20px;">
	<input id="txtUsername" name="txtUsername" class="form-login" title="Username" value="" size="30" maxlength="20" />
</div>
<div id="login-box-name">Password:</div>
<div id="login-box-field">
	<input id="txtPassword" name="txtPassword" type="password" class="form-login" title="Password" value="" size="30" maxlength="20" />
</div>
<br />
<!--span class="login-box-options"><input type="checkbox" name="1" value="1"> Remember Me <a href="#" style="margin-left:30px;">Forgot password?</a></span-->
<br />
<br />
<br />
<a href="JavaScript:doCallAjax();"><img src="images/login-btn.png" width="103" height="42" style="margin-left:90px;" /></a>

</div>

</div>

</body>
</html>
