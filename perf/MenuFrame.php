<?php
        session_start();
        if($_SESSION["Username"] == "")
        {
                header("location:logout.php");
                exit();
        }

        // set timeout period in seconds
        $inactive = 600;

        // check to see if $_SESSION['timeout'] is set
        if(isset($_SESSION['timeout']) ) {
                $session_life = time() - $_SESSION['timeout'];
                if($session_life > $inactive)
                        { session_destroy(); header("Location: logout.php"); }
        }
        $_SESSION['timeout'] = time();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<title>PRT</title>
<head>  
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<script language="javascript"> 
function fncAlert() 
{ 
if (confirm('Do you want to logout')==true)
  { return true;
  }
else
  { return false;
  }
} 
</script> 

<body >
<div class="wrapper col1">
</div>
<div class="wrapper col2">
  <div id="header">
    <div id="logo">
      <h1>G-ABLE</h1>
      <p>To be the trusted business partner</p>
    </div>
    <ul id="topnav">
      <li class="last">
	<a href="logout.php" target="_top" onclick="JavaScript:return fncAlert();">log out</a>
      </li>
	<?php
   	if($_SESSION["Username"] == "root"){
           echo "<li><A href='admin.php' target='showfrm'>Admin Task</A></li>";
   	}
	?>
      <li><a href="profile.php" target="showfrm">Profile</a></li>
<?php
	if($_SESSION["PRIV"] == "staff"){
      	   echo "<li><a href='manage.php?PICK=CUS&CUS=NO' target='showfrm'>Manage</a></li>";
	}
?>
      <li><a href="report.php?PICK=CUS&CUS=NO" target="showfrm">Report</a></li>
      <li><a href="upload.php?CUS=NO" target="showfrm">Upload</a></li>
    </ul>
    <br class="clear" />
  </div>
</div>


</body>
</html>
