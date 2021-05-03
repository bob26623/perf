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

$objConnect = mysql_connect("localhost",$_SESSION["MUSER"],$_SESSION["MPASS"]) or die("Error Connect to Database");
   $objDB = mysql_select_db("perf");
   mysql_query("SET NAMES UTF8");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="styles/layout.css" type="text/css" />

<script language = "JavaScript">
    function ListServer(SelectValue)
      {
         frmUpload.selsrv.length = 0
         //var myOption = new Option('SERVER','')
         //frmUpload.selsrv.options[frmUpload.selsrv.length]= myOption
         //frmUpload.txtcontract.value="";
         //frmUpload.hdnprojimp.value="";

      <?
        $strSQL = "SELECT * FROM serverdetail ORDER by server";
        $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
        $intRows = 0;
        while($objResult = mysql_fetch_array($objQuery))
          {
            $intRows++;
      ?>
        x = <?=$intRows;?>;
        mySubList = new Array();
        strGroup = "<?=$objResult["gid"];?>";
        strValue = "<?=$objResult["serial"];?>";
        strItem = "<?=$objResult["server"];?>";
        mySubList[x,0] = strItem;
        mySubList[x,1] = strGroup;
        mySubList[x,2] = strValue;
        if (mySubList[x,1] == SelectValue) {
          var myOption = new Option(mySubList[x,0], mySubList[x,2])
          frmUpload.selsrv.options[frmUpload.selsrv.length]= myOption
        }
      <?  } ?>
}


</script>
</head>
<body id="top">
<div class="wrapper col3">
  <div id="breadcrumb">
    <ul>
<?
 $strSQL = "SELECT customer FROM customer WHERE account like '%".$_SESSION["Username"]."%' ORDER BY customer"; 
 $cusQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]"); 
 $CUC=1; // customer counter
 $CUS=$_GET["CUS"];
 while ($cusResult = mysql_fetch_array($cusQuery))
   {
	if ( $CUS == "NO" ) { 
	  echo "<li class='first'>";
	  $CUS=$cusResult["customer"];
	} elseif ( $CUS == $cusResult["customer"] ) { 
	  if ($CUC != "1") {echo "<li>&#166;</li>";}
	  echo "<li class='first'>"; 
	} else {
	  //echo "<li>&#166;</li>";
	  if ($CUC != "1") {echo "<li>&#166;</li>";}
	  echo "<li><a href=upload.php?CUS=".$cusResult["customer"].">";
	}
	  $CUC+=1;
	echo "&nbsp;".$cusResult["customer"]."&nbsp;</a></li>";

   }
?>
    </ul>
  </div>
</div>
	<form id="frmUpload" action="upload1.php" method="post" enctype="multipart/form-data" >
<div class="wrapper col6">
  <div id="footer">
    <div id="contactform">
      <h2>Select Group</h2>
	<select size=10 style="width: 200px" id="selgrp" name="selgrp" onChange="JavaScript:ListServer(this.value);">
<?
  $strSQL = "SELECT * FROM servergroup WHERE cname='$CUS' ORDER BY gname";
  $gQuery=mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
  while ($gResult = mysql_fetch_array($gQuery))
    {
	echo "<option value=".$gResult["gid"].">".$gResult["gname"]."</option>";
    }
?>
	</select>
    </div>
    <!-- End Contact Form -->
    <div id="compdetails">
      <div id="officialdetails">
        <h2>Select Server</h2>
	  <select size=10 style="width: 200px" id="selsrv" name="selsrv" >
	  </select>


      </div>
      <div id="contactdetails">
        <h2>Select File</h2>
	<span id="upmsg">Please select file</span>
	<br class="clear" />
	<input id="fileupload" name="fileupload" type="file">&nbsp;
	<input id="btnUpload" type="submit" value="upload">
	<input type="hidden" name="CUS" value='<?=$CUS?>' >
	</form>

        <br class="clear" />
	  <ul>
          <ol>- Monthly format : hostname.month.year.tar.gz </ol>
          <ol>- ie. myserver.Jan.12.tar.gz </ol>
          <ol>- Content of upload files consist of "sar-x" directory</ol>
          <br/>
          <ol>- Daily format : hostname.date.sar-x </ol>
          <ol>- ie. myserver.1Jan12.sar-u </ol>
          <br/>
          <ol>- Calculated Format : hostname.month.year.txt </ol>
          <ol>- ie. myserver.Jan.12.txt </ol>
          </ul>

        <br class="clear" />
        <br class="clear" />
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
<br class="clear" />
<br class="clear" />
</div>
<div class="wrapper col7">
  <div id="copyright">
    <ul>
      <li><a href="#">Online Privacy Policy</a></li>
      <li><a href="#">Terms of Use</a></li>
      <li><a href="#">Permissions &amp; Trademarks</a></li>
      <li><a href="#">Product License Agreements</a></li>
      <li class="last">Template by <a target="_blank" href="http://www.os-templates.com/" title="Open Source Templates">OS Templates</a></li>
    </ul>
  </div>
</div>
</body>
</html>
