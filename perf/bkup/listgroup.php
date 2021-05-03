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
<head>
<title>Performance Reporting Tool</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<link rel="stylesheet" href="styles/table.css" type="text/css" />
<script type="text/javascript" src="scripts/ajax.js"></script>
</head>
<body id="top">

<form name="frmMain" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
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
//                  echo "update groupname<br/>";
 //                 echo "EDIT ".$_POST["txtEditgroup"]."(".$_POST["hdnEditgroup"].") on ".$_POST["hdnAddgroup"]."<br/>";
		  $strSQL = "UPDATE servergroup SET ";
                  $strSQL .="gname = '".$_POST["txtEditgroup"]."' ";
                  $strSQL .="WHERE gid = '".$_POST["hdnEditgroup"]."' ";
		  $objQuery1 = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		  }else{
		  echo "<font color=red size=+2>Update failed : Duplicate group name</font><br/>";
                  }

                } // check blank servername , serial , model
          }elseif($_POST["hdnCmd"] == "ADD"){
	     $_GET["CUS"]=$_POST["hdnAddgroup"];
	     if($_POST["txtAddgroup"]=="" )
                {
                  echo "<font color=red size=+2>Add failed : Group name is empty</font><br/>";

                }else{
			//// check duplicate group name
		$strSQL = "SELECT * FROM servergroup WHERE gname='".$_POST["txtAddgroup"]."' and cname='".$_POST["hdnAddgroup"]."'";
   		  $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
   		  $objResult = mysql_fetch_array($objQuery);
		  if (!$objResult){
		
//                  echo "ADD ".$_POST["txtAddgroup"]." on ".$_POST["hdnAddgroup"]."<br/>";
		  $strSQL = "INSERT INTO servergroup ";
		  $strSQL .="(gname,cname) VALUES ";
		  $strSQL .="('".$_POST["txtAddgroup"]."','".$_POST["hdnAddgroup"]."')";
		  $objQuery1 = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		  }else{
		  echo "<font color=red size=+2>Add failed : Duplicate group name</font><br/>";
		  }
		  
		}
        }

        // Delete serverdetail and server
        if($_GET["Action"] == "Del")
          {
//  check existing server in this group before delete
 //               echo "delete group ".$_GET["GID"]." on ".$_GET["CUS"]."<br/>";
$strSQL = "SELECT * FROM servergroup,serverdetail WHERE servergroup.gid=serverdetail.gid and servergroup.gid='".$_GET["GID"]."' ";
		$objQuery1 = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		if (!$objResult1){
		$strSQL = "DELETE FROM servergroup ";
                $strSQL .="WHERE gid = '".$_GET["GID"]."' ";
                $objQuery1 = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		}else{
		  echo "<font color=red size=+2>Delete failed : There are existing server in this group</font><br/>";	
		}
          }

	echo "<ul>";

	$strSQL = "SELECT customer FROM customer WHERE account like '%".$_SESSION["Username"]."%' ORDER BY customer"; 
 	$cusQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]"); 
 	$CUC=1; // customer counter
 	$CUS=$_GET["CUS"];
	echo "<li class=\"first\">Customer : &nbsp;</li> ";
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
          echo "<li><a href=profile.php?PICK=CUS&CUS=".$cusResult["customer"].">";
        }
          $CUC+=1;
        echo "&nbsp;".$cusResult["customer"]."&nbsp;</a></li>";

   	}//while  
	echo "</ul></div></div>";

	// pass no value to ajax
	echo "<input type='hidden' name='NONE' id='NONE' value='NONE'>";

	echo "<div class=\"wrapper col5\">";
  	echo "<div id=\"container\">";
  	echo "<div id=\"content\">";
	echo "<h1>[ Customer $CUS ] Group list</h1> ";


  	$strSQL = "SELECT * FROM servergroup WHERE cname='$CUS' ORDER BY gname";
  	$gQuery=mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
  	while ($gResult = mysql_fetch_array($gQuery))
    	{
	
	if($gResult["gid"] == $_GET["GID"] and $_GET["Action"] == "Edit")
         {
	   echo "<input type=hidden name=hdnEditgroup size=5 value=".$gResult["gid"].">";
	   echo "<input type=text name=txtEditgroup size=10 value=".$gResult["gname"].">";
	   echo "<button name=btnUpdate type=button id=btnUpdate OnClick=\"frmMain.hdnCmd.value='Update';frmMain.submit();\">";
	   echo "<img src=./images/save.bmp alt=save title=save  align=absmiddle width=16 heigh=16>";
	   echo "</button>";
	   echo "<button name=btnCancel type=button id=btnCancel value=Cancel OnClick=\"window.location='profile.php?PICK=CUS&CUS=$CUS';\">";
	   echo "<img src=./images/cancel.jpg alt=cancel title=cancel align=absmiddle width=16 heigh=16>";
	   echo "</button><br/>";
	 }else{
	   echo "<button name=btnEdit type=button id=btnEdit OnClick=\"window.location='profile.php?Action=Edit&GID=".$gResult["gid"]."&PICK=CUS&CUS=$CUS';\">";
	   echo "<img src=./images/edit.bmp alt=edit title=edit  align=absmiddle width=16 heigh=16>";
   	   echo "</button>";
	   echo "<button name=btnDel type=button id=btnDel OnClick=\"JavaScript:if(confirm('Delete ".$gResult["gname"]." group ?')==true){window.location='".$_SERVER["PHP_SELF"]."?Action=Del&GID=".$gResult["gid"]."&PICK=CUS&CUS=$CUS';}\">";
           echo "<img src=./images/del.jpg alt=delete title=delete  align=absmiddle width=16 heigh=16>";
   	   echo "</button>";
		
	   echo "<a href=\"JavaScript:doCallAjax('server','listserver.php?GID=".$gResult["gid"]."&GRP=".$gResult["gname"]."','NONE');\">";
       	   echo "&nbsp;".$gResult["gname"]."&nbsp;</a><br/> ";
	 }

    	}
	echo "<input type=hidden name=hdnAddgroup size=5 value=$CUS>";
	echo "<button name=btnAdd type=button id=btnAdd OnClick=\"frmMain.hdnCmd.value='ADD';frmMain.submit();\">Add</button>";
	echo "<input type=text name=txtAddgroup id=txtAddgroup size=10 value=>";
	echo "</form>";
  	echo "</div></div></div>";
?>
	<span id="server" />

<? mysql_close($objConnect); ?>
</body>
</html>
