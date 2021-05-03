<?php
        session_start();
        if($_SESSION["Username"] == "")
        {
                header("location:logout.php");
                exit();
        }

        // set timeout period in seconds
        $inactive = 1800;

        // check to see if $_SESSION['timeout'] is set
        if(isset($_SESSION['timeout']) ) {
                $session_life = time() - $_SESSION['timeout'];
                if($session_life > $inactive)
                        { session_destroy(); header("Location: logout.php"); }
        }
        $_SESSION['timeout'] = time();


include("db.php");
//$objConnect = mysqli_init();
//mysqli_real_connect($objConnect,"luna.g-able.ga",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",3036,NULL,MYSQLI_CLIENT_SSL) or die("Error Connect to Database");
   //$objConnect = mysqli_connect("localhost",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",NULL,"/cloudsql/smiling-gasket-170108:us-central1:csypart") or die("Error Connect to Database");
   //$objDB = mysqli_select_db("perf");
   mysqli_query($objConnect,"SET NAMES UTF8");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<head>
<title>Performance Reporting Tool</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<link rel="stylesheet" href="styles/tables.css" type="text/css" />
<script type="text/javascript" src="scripts/ajax.js"></script>
<script type="text/javascript">
        function ChkFile(cus,grp,file){

	myfile=cus+"."+grp+"."+file+".pdf";
        //alert("You pressed OK! ./customer/pdf/"+myfile);

	//////document.getElementById('btnget').disabled = true;
	document.getElementById('mypdf').style.display='none';
	//document.getElementById('mypdf').href='#';
	<?php
          $objOpen = opendir("./customer/pdf");
           //while (($ufile = readdir($objOpen)) !== false)
           while ($ufile = readdir($objOpen))
           {    
        ?>
           listfile="<?=$ufile;?>";
           if (listfile == myfile) { 
		/////document.getElementById('btnget').disabled = false; 
		document.getElementById('mypdf').href ="./customer/pdf/"+cus+"."+grp+"."+file+".pdf"; 
		document.getElementById('mypdf').style.display = ''; 
	   }
 
        <?php  } ?>
        }
function fncComment(path,fname)
   {    
        //alert('path = '+path+' file = '+fname);
        var div = document.getElementById (fname);
        var txt1 = document.getElementById ("txt"+fname);
        //var sub1 = document.getElementById ("mysubmit");
        var table1 = document.getElementById ("mytable");
        var file1 = document.getElementById ("myfile");
        
        if(fname!="utilize"){
          document.getElementById ("utilize").style.display="none";
          document.getElementById ("txtutilize").style.display="none";
        }
        if(fname!="aversar"){
          document.getElementById ("aversar").style.display="none";
          document.getElementById ("txtaversar").style.display="none";
        }
        if(fname!="realcpu"){
          document.getElementById ("realcpu").style.display="none";
          document.getElementById ("txtrealcpu").style.display="none";
        }
        if(fname!="realmem"){
          document.getElementById ("realmem").style.display="none";
          document.getElementById ("txtrealmem").style.display="none";
        }
        if(fname!="runq"){
          document.getElementById ("runq").style.display="none";
          document.getElementById ("txtrunq").style.display="none";
        }
        if(fname!="scan"){
          document.getElementById ("scan").style.display="none";
          document.getElementById ("txtscan").style.display="none";
        }

        if(div.style.display=="none") {
          div.style.display="block";
          txt1.style.display="block";
         // sub1.style.display="block";
          table1.style.display="block";
          file1.value=fname;
        } else {
          div.style.display="none";
          txt1.style.display="none";
          //sub1.style.display="none";
          table1.style.display="none";
        }
   }
</script>
</head>
<body id="top">
<div class="wrapper col3">
  <div id="breadcrumb">
    <ul>
<?php
	$strSQL = "SELECT customer FROM customer WHERE account like '%".$_SESSION["Username"]."%' ORDER BY customer"; 
 	$cusQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 
 	$CUC=1; // customer counter
 	$CUS=$_GET["CUS"];
	echo "<li class=\"first\">Customer : &nbsp;</li> ";
 	while ($cusResult = mysqli_fetch_array($cusQuery))
   	{
        if ( $CUS == "NO" ) { 
          echo "<li class='first'>";
          $CUS=$cusResult["customer"];
	  echo "&nbsp;<font size=+2 color=orange>".$cusResult["customer"]."</font>&nbsp;</li>";
        } elseif ( $CUS == $cusResult["customer"] ) { 
          if ($CUC != "1") {echo "<li>&#166;</li>";}
          //echo "<li class='first'>"; 
	  echo "<li class='first'>&nbsp;<font size=+2 color=orange>".$cusResult["customer"]."</font>&nbsp;</a></li>";
        } else {
          //echo "<li>&#166;</li>";
          if ($CUC != "1") {echo "<li>&#166;</li>";}
          //echo "<li><a href=report.php?PICK=CUS&CUS=".$cusResult["customer"].">";
          echo "<li><a href=report.php?PICK=CUS&CUS=".$cusResult["customer"].">&nbsp;".$cusResult["customer"]."&nbsp;</a></li>";
        }
          $CUC+=1;
        //echo "&nbsp;".$cusResult["customer"]."&nbsp;</a></li>";

   	}//while  
	echo "</ul></div></div>";

	// pass no value to ajax
	echo "<input type='hidden' name='NONE' id='NONE' value='NONE'>";

	echo "<div class=\"wrapper col3\">";
  	echo "<div id=\"breadcrumb\">";
  	echo "<ul>";
	echo "<li class=\"first\">Group &nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;</li> ";
  	$strSQL = "SELECT * FROM servergroup WHERE cname='$CUS' ORDER BY gname";
  	$gQuery=mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
  	while ($gResult = mysqli_fetch_array($gQuery))
    	{
        //echo "<li><a href=report.php?PICK=GRP&CUS=$CUS&GRP=".$gResult["gname"].">";
	echo "<li><a href=\"JavaScript:doCallAjax('history','gethistory.php?GRP=".$gResult["gid"]."','NONE');\">";

        echo "&nbsp;".$gResult["gname"]."&nbsp;</a> ";
	echo "</li>";

    	}
        echo "</ul>";
  	echo "</div></div>";
?>
	<span id="history" />

<?php mysqli_close($objConnect); ?>
</body>
</html>
