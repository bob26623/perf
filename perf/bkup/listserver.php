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

<body id="top">

<form name="frmMain1" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
<input type="hidden" name="hdnCmd" value="">

<div class="wrapper col3">
  <div id="breadcrumb">
<?
   	if($_POST["hdnCmd"] == "Update")
          {
	    $_GET["CUS"]=$_POST["hdnAddgroup"];
            if($_POST["txtEditgroup"]=="" )
                {
                  echo "<font color=red size=+2>Update failed : Group name is empty</font><br/>";

                }else{
		  //// check duplicate group name
                $strSQL = "SELECT * FROM servergroup WHERE gname='".$_POST["txtEditgroup"]."' and cname='".$_POST["hdnAddgroup"]."'";
                  $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
                  $objResult = mysql_fetch_array($objQuery);
                  if (!$objResult){
                  echo "update groupname<br/>";
                  echo "EDIT ".$_POST["txtEditgroup"]."(".$_POST["hdnEditgroup"].") on ".$_POST["hdnAddgroup"]."<br/>";
		  $strSQL = "UPDATE servergroup SET ";
                  $strSQL .="gname = '".$_POST["txtEditgroup"]."' ";
                  $strSQL .="WHERE gid = '".$_POST["hdnEditgroup"]."' ";
		  $objQuery1 = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		  }else{
		  echo "<font color=red size=+2>Update failed : Duplicate group name</font><br/>";
                  }

                } // check blank servername , serial , model
          }elseif($_POST["hdnCmd"] == "ADD"){
	     $_GET["GID"]=$_POST["hdnAddserver"];
	     if($_POST["txthostname"]=="" || $_POST["txtserial"]=="" )
                {
                  echo "<font color=red size=+2>Add failed : hostname  or serial is empty</font><br/>";

                }else{
			//// check duplicate serial
		$strSQL = "SELECT * FROM serverdetail WHERE serial='".$_POST["txtserial"]."'";
   		  $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
   		  $objResult = mysql_fetch_array($objQuery);
		  if (!$objResult){
		
                  echo "ADD ".$_POST["txtserver"]." on ".$_POST["hdnAddserver"]."<br/>";
		  $strSQL = "INSERT INTO serverdetail ";
		  $strSQL .="(gid,server,model,serial,location,cpu,memory,os,application,software,remark) VALUES ";
		  $strSQL .="('".$_POST["txtAddserver"]."','".$_POST["txthostname"]."','".$_POST["selmodel"]."',";
		  $strSQL .="'".$_POST["txtserial"]."','".$_POST["txtlocation"]."','".$_POST["txtcpu"]."',";
		  $strSQL .="'".$_POST["txtmemory"]."','".$_POST["txtos"]."','".$_POST["txtapplication"]."',";
		  $strSQL .="'".$_POST["txtsoftware"]."','".$_POST["txtremark"]."')";
		  $objQuery1 = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		  }else{
		  echo "<font color=red size=+2>Add failed : Duplicate serial</font><br/>";
		  }
		  
		}
        }

        // Delete serverdetail and server
        if($_GET["Action"] == "Del")
          {
                echo "delete server ".$_GET["SERIAL"]." on ".$_GET["GID"]."<br/>";
		$strSQL = "DELETE FROM servedetail ";
                $strSQL .="WHERE serial = '".$_GET["SERIAL"]."' ";
                $objQuery1 = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
          }

///////////////////////////////////////////

	//echo "<div class=\"wrapper col5\">";
  	//echo "<div id=\"container\">";
  	//echo "<div id=\"content\">";
	echo "<h1>[ Group ".$_GET["GRP"]." ] Server list </h1> ";


  	$strSQL = "SELECT * FROM serverdetail WHERE gid=".$_GET["GID"]." ORDER BY server";
  	$gQuery=mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
  	while ($gResult = mysql_fetch_array($gQuery))
    	{
	
	if($gResult["serial"] == $_GET["SERIAL"] and $_GET["Action"] == "Edit")
         {
	   echo "<input type=hidden name=hdnEditgroup size=5 value=".$gResult["gid"].">";
	   echo "<input type=text name=txtEditgroup size=10 value=".$gResult["gname"].">";
	   echo "<button name=btnUpdate type=button id=btnUpdate OnClick=\"frmMain1.hdnCmd.value='Update';frmMain1.submit();\">";
	   echo "<img src=./images/save.bmp alt=save title=save  align=absmiddle width=16 heigh=16>";
	   echo "</button>";
	   echo "<button name=btnCancel type=button id=btnCancel value=Cancel OnClick=\"window.location='listserver.php?PICK=CUS&CUS=$CUS';\">";
	   echo "<img src=./images/cancel.jpg alt=cancel title=cancel align=absmiddle width=16 heigh=16>";
	   echo "</button><br/>";


	 }else{
	   echo "<button name=btnEdit type=button id=btnEdit OnClick=\"JavaScript:doCallAjax('server','listserver.php?GID=".$gResult["gid"]."&Action=Edit&GRP=".$_GET["GRP"]."&SERIAL=".$gResult["serial"]."','NONE');\">";

	   echo "<img src=./images/edit.bmp alt=edit title=edit  align=absmiddle width=16 heigh=16>";
   	   echo "</button>";
	   echo "<button name=btnDel type=button id=btnDel OnClick=\"JavaScript:if(confirm('Delete server ".$gResult["server"]." ?')==true){window.location='".$_SERVER["PHP_SELF"]."?Action=Del&SERIAL=".$gResult["serial"]."&GID=".$gResult["gid"]."';}\">";
           echo "<img src=./images/del.jpg alt=delete title=delete  align=absmiddle width=16 heigh=16>";
   	   echo "</button>";
		
	   //echo "<a href=\"JavaScript:doCallAjax('server','listserver.php?GID=".$gResult["gid"]."','NONE');\">";
       	   //echo "&nbsp;".$gResult["gname"]."&nbsp;</a><br/> ";
       	   echo "&nbsp;".$gResult["server"]."&nbsp;<br/> ";

	 }

    	}
	if ($_GET["Action"] != "Edit"){
	echo "<input type=hidden name=hdnAddserver size=5 value=".$_GET["GID"].">";
	echo "<table cellpadding=0 cellspacing=0>";
        echo "<tbody>";
      echo "<tr><td>Hostname<font size=-1 color=red>*</font> : <input type=text name=txthostname id=txthostname size=20></td>";
        echo "<td>Serial<font size=-1 color=red>*</font> : <input type=text name=txtserial id=txtserial size=20></td></tr>";
	echo "<tr><td>Model : <select id=selmodel name=selmodel style=width:120px >";
	$strSQL = "SELECT * FROM model ORDER BY model";
        $Query=mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
        while ($Result = mysql_fetch_array($Query))
        {
	  echo "<option value=".$Result["model"].">".$Result["model"]."</option>";	
	}
	echo "</select></td>";
        echo "<td>Remark : <input type=text name=txtremark id=txtremark size=20 value=></td></tr>";
        echo "<tr><td>CPU : <input type=text name=txtcpu id=txtcpu size=20 value=></td>";
        echo "<td>Memory : <input type=text name=txtmemory id=txtmemory size=20 value=></td></tr>";
        echo "<tr><td>OS : <input type=text name=txtos id=txtos size=20 value=></td>";
        echo "<td>Application : <input type=text name=txtappliation id=txtapplication size=20 value=></td></tr>";
        echo "<tr><td>Software : <input type=text name=txtsoftware id=txtsoftware size=20 value=></td>";
        echo "<td>Location : <input type=text name=txtlocation id=txtlocation size=20 value=></td></tr>";
	echo "<tr><td colspan=2 align=center><button name=btnAdd type=button id=btnAdd OnClick=\"frmMain1.hdnCmd.value='ADD';frmMain1.submit();\">Add</button></td></tr>";
        echo "</tbody>";
        echo "</table>";
	}
/*
	echo "<input type=hidden name=hdnAddgroup size=5 value=$CUS>";
	echo "<button name=btnAdd type=button id=btnAdd OnClick=\"frmMain1.hdnCmd.value='ADD';frmMain1.submit();\">Add</button>";
	echo "<input type=text name=txtAddgroup id=txtAddgroup size=10 value=>";
*/
	echo "</form>";
  	//echo "</div></div></div>";
?>
	<span id="server" />

<? mysql_close($objConnect); ?>
</body>
</html>
