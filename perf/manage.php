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
</head>
<body id="top">

<form name="frmMain" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
<input type="hidden" name="hdnCmd" value="">

<div class="wrapper col3">
  <div id="breadcrumb">
<?php
   	if($_POST["hdnCmd"] == "GUpdate")
          {
	    $_GET["CUS"]=$_POST["hdnAddgroup"];
	    $_GET["GRP"]=$_POST["hdnOldgroup"];
	    $_GET["PICK"]=CUS;
            if($_POST["txtEditgroup"]=="" )
                {
                  echo "<font color=red size=+2>Update failed : Group name is empty</font><br/>";

                }else{
		  //// check duplicate group name
               $strSQL = "SELECT * FROM servergroup WHERE gname='".$_POST["txtEditgroup"]."' and cname='".$_POST["hdnAddgroup"]."' and gid != '".$_POST["hdnEditgroup"]."' ";
                  $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
                  $objResult = mysqli_fetch_array($objQuery);
                  if (!$objResult){
		  $strSQL = "UPDATE servergroup SET ";
                  $strSQL .="gname = '".$_POST["txtEditgroup"]."' ";
                  $strSQL .="WHERE gid = '".$_POST["hdnEditgroup"]."' ";
		  $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
		  if($objQuery1){
		    // check existing dir and move
		    if (file_exists("customer/".$_GET["CUS"]."/".$_POST["txtEditgroup"])){
			//echo "mv customer/".$_GET["CUS"]."/".$_POST["txtEditgroup"]." customer/".$_GET["CUS"]."/".$_POST["txtEditgroup"].".`date +%d%b%y`";
			shell_exec("mv customer/".$_GET["CUS"]."/".$_POST["txtEditgroup"]." customer/".$_GET["CUS"]."/".$_POST["txtEditgroup"].".`date +%d%b%y`");
		    }
		    //echo "mv customer/".$_GET["CUS"]."/".$_GET["GRP"]." customer/".$_GET["CUS"]."/".$_POST["txtEditgroup"];
		    shell_exec("mv customer/".$_GET["CUS"]."/".$_GET["GRP"]." customer/".$_GET["CUS"]."/".$_POST["txtEditgroup"]);
		  }
		  }else{
		  echo "<font color=red size=+2>Update failed : Duplicate group name</font><br/>";
                  }

                } // check blank servername , serial , model
          }elseif($_POST["hdnCmd"] == "GADD"){
	     $_GET["CUS"]=$_POST["hdnAddgroup"];
	     $_GET["PICK"]=CUS;
	     if($_POST["txtAddgroup"]=="" )
                {
                  echo "<font color=red size=+2>Add failed : Group name is empty</font><br/>";

                }else{
			//// check duplicate group name
		$strSQL = "SELECT * FROM servergroup WHERE gname='".trim($_POST["txtAddgroup"])."' and cname='".$_POST["hdnAddgroup"]."'";
   		  $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
   		  $objResult = mysqli_fetch_array($objQuery);
		  if (!$objResult){
		
if(!file_exists("customer/".$_GET["CUS"]."/".$_POST["txtAddgroup"])) { mkdir("customer/".$_GET["CUS"]."/".$_POST["txtAddgroup"]); }
		  $strSQL = "INSERT INTO servergroup ";
		  $strSQL .="(gname,cname) VALUES ";
		  $strSQL .="('".trim($_POST["txtAddgroup"])."','".$_POST["hdnAddgroup"]."')";
		  $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
		  }else{
		  echo "<font color=red size=+2>Add failed : Duplicate group name</font><br/>";
		  }
		  
		}
        }elseif($_POST["hdnCmd"] == "SUpdate"){
	    $_GET["CUS"]=$_POST["hdnAddgroup"];
	    $_GET["GID"]=$_POST["hdnAddserver"];
	    $_GET["GRP"]=$_POST["hdnGrpName"];
	    $_GET["PICK"]=GRP;
	  //echo "server update ".$_GET["CUS"]." ".$_GET["GRP"]." ".$_GET["PICK"]."serial =".$_POST["txtserial"]." host =".$_POST["txthostname"]." old serial =".$_POST["hdnEditSerial"]."<br/>";
	if($_POST["txtserial"]=="" || $_POST["txthostname"]=="" )
                {
                  echo "<font color=red size=+2>Update failed : Hostname or Serial must not empty</font><br/>";

                }else{
                  //// check duplicate serial 
		$DUP=F;
		if ($_POST["txtserial"] != $_POST["hdnEditSerial"]){
                  $strSQL = "SELECT * FROM serverdetail WHERE serial='".$_POST["txtserial"]."'" ;
                  $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
                  $objResult = mysqli_fetch_array($objQuery);
                  if ($objResult){ $DUP=T;}
		}
		if ($_POST["txthostname"] != $_POST["hdnEditHOST"]){
                  $strSQL = "SELECT * FROM serverdetail WHERE server='".$_POST["txthostname"]."' and gid=".$_GET["GID"] ;
                  $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
                  $objResult = mysqli_fetch_array($objQuery);
                  if ($objResult){ $DUP=T;}
                }
		  if ($DUP == "F"){
                  $strSQL = "UPDATE serverdetail SET ";
                  $strSQL .="server = '".trim($_POST["txthostname"])."',serial='".$_POST["txtserial"]."',";
                  $strSQL .="model = '".$_POST["selmodel"]."',remark='".$_POST["txtremark"]."',";
                  $strSQL .="cpu = '".$_POST["txtcpu"]."',memory='".$_POST["txtmemory"]."',";
                  $strSQL .="os = '".$_POST["txtos"]."',application='".$_POST["txtapplication"]."',";
                  $strSQL .="software = '".$_POST["txtsoftware"]."',location='".$_POST["txtlocation"]."' ";
                  $strSQL .="WHERE serial = '".$_POST["hdnEditSerial"]."' ";
                  $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
		  if($objQuery1)
                   {
		     if (file_exists("customer/".$_GET["CUS"]."/".trim($_GET["GRP"])."/".trim($_POST["txthostname"]))){
			shell_exec("mv customer/".$_GET["CUS"]."/".trim($_GET["GRP"])."/".trim($_POST["txthostname"])." customer/".$_GET["CUS"]."/".trim($_GET["GRP"])."/".trim($_POST["txthostname"]).".`date +%d%b%y`"); 
		     }
                     shell_exec("mv customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_POST["hdnEditHOST"]." customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_POST["txthostname"]);
                   }
                  }else{
                  echo "<font color=red size=+2>Update failed : Duplicate serial or hostname</font><br/>";
                  }

                } // check blank servername , serial , model
	}elseif($_POST["hdnCmd"] == "SADD"){
	    $_GET["CUS"]=$_POST["hdnAddgroup"];
	    $_GET["GID"]=$_POST["hdnAddserver"];
	    $_GET["GRP"]=$_POST["hdnGrpName"];
	    $_GET["PICK"]="GRP";


	    if($_POST["txthostname"]=="" || $_POST["txtserial"]=="" )
                {
                  echo "<font color=red size=+2>Add failed : hostname  or serial is empty</font><br/>";

                }else{
                        //// check duplicate serial
                $strSQL = "SELECT * FROM serverdetail WHERE serial='".$_POST["txtserial"]."' or server='".$_POST["txthostname"]."'";
                  $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
                  $objResult = mysqli_fetch_array($objQuery);
                  if (!$objResult){
                
                  $strSQL = "INSERT INTO serverdetail ";
                  $strSQL .="(gid,server,model,serial,location,cpu,memory,os,application,software,remark) VALUES ";
                  $strSQL .="('".$_POST["hdnAddserver"]."','".trim($_POST["txthostname"])."','".$_POST["selmodel"]."',";
                  $strSQL .="'".trim($_POST["txtserial"])."','".$_POST["txtlocation"]."','".$_POST["txtcpu"]."',";
                  $strSQL .="'".trim($_POST["txtmemory"])."','".$_POST["txtos"]."','".$_POST["txtapplication"]."',";
                  $strSQL .="'".$_POST["txtsoftware"]."','".$_POST["txtremark"]."')";
                  $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
                  }else{
                  echo "<font color=red size=+2>Add failed : Duplicate Hostname or Serial</font><br/>";
                  }
                  
                }
	}elseif($_POST["hdnCmd"] == "MOVE"){
	    $_GET["CUS"]=$_POST["hdnAddgroup"];
	    $_GET["GID"]=$_POST["hdnAddserver"];
	    $_GET["GRP"]=$_POST["hdnGrpName"];
	    $_GET["PICK"]="GRP";
	    if ($_POST["selgrp"] == 0){
		echo "<font color=red size=+2>Move failed : Please select group</font><br/>";
	    }else{
	    if(isset($_POST["srv"])){
            foreach($_POST["srv"] as $key => $myserial)
              {
		$strSQL = "UPDATE serverdetail SET ";
                $strSQL .="gid = '".$_POST["selgrp"]."' ";
                $strSQL .="WHERE serial = '$myserial' ";
                $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
	
	//////// move contents in server
		$strSQL = "SELECT gname FROM servergroup WHERE gid ='".$_POST["selgrp"]."' ";
                $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
                $objResult1 = mysqli_fetch_array($objQuery1);

                $strSQL = "SELECT server FROM serverdetail WHERE serial ='$myserial' ";
                $objQuery2 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
                $objResult2 = mysqli_fetch_array($objQuery2);

                if($objResult1 && $objResult2)
                {
                  shell_exec("mv customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$objResult2["server"]." customer/".$_GET["CUS"]."/".$objResult1["gname"]."/".$objResult2["server"]);
                }


              } //foreach
            } // check srv[]
	    }// if check selgrp
	  
	}

        // Delete serverdetail and server
        if($_GET["Action"] == "GDel")
          {
//  check existing server in this group before delete
//               echo "delete group ".$_GET["GID"]." on ".$_GET["CUS"]."<br/>";
$strSQL = "SELECT * FROM servergroup,serverdetail WHERE servergroup.gid=serverdetail.gid and servergroup.gid='".$_GET["GID"]."' ";
		$objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
		$objResult1 = mysqli_fetch_array($objQuery1);
		if (!$objResult1){
		  $strSQL = "DELETE FROM servergroup ";
                  $strSQL .="WHERE gid = '".$_GET["GID"]."' ";
                  $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
		  if($objQuery1){
		    //echo "rm -rf customer/".$_GET["CUS"]."/".$_GET["GRP"]; 
		    shell_exec("rm -rf customer/".$_GET["CUS"]."/".$_GET["GRP"]); 
 		  }
		}else{
		  echo "<font color=red size=+2>Delete failed : There are existing server in this group</font><br/>";	
		}
          }elseif ($_GET["Action"] == "SDel"){
		$strSQL = "DELETE FROM serverdetail ";
                $strSQL .="WHERE serial = '".$_GET["SERIAL"]."' ";
                $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
		if($objQuery1){
                    //echo "rm -rf customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_GET["SRV"];
                    shell_exec("rm -rf customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_GET["SRV"]);
                  }
	
	  }
	echo "<ul>";

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
          //echo "<li><a href=manage.php?PICK=CUS&CUS=".$cusResult["customer"].">";
          echo "<li><a href=manage.php?PICK=CUS&CUS=".$cusResult["customer"].">&nbsp;".$cusResult["customer"]."&nbsp;</a></li>";
        }
          $CUC+=1;
        //echo "&nbsp;".$cusResult["customer"]."&nbsp;</a></li>";

   	}//while  
	echo "</ul></div></div>";

	// pass no value to ajax
	echo "<input type='hidden' name='NONE' id='NONE' value='NONE'>";

	echo "<div class=\"wrapper col5\">";
  	echo "<div id=\"container\">";
  	echo "<div id=\"content\">";

////////////////// show group ///////////////// 
	if ( $_GET["PICK"] == "CUS"){

	echo "<h1>[ Customer <font color=orange>$CUS</font> ] Group list</h1> ";


  	$strSQL = "SELECT * FROM servergroup WHERE cname='$CUS' ORDER BY gname";
  	$gQuery=mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
  	while ($gResult = mysqli_fetch_array($gQuery))
    	{
	
	if($gResult["gid"] == $_GET["GID"] and $_GET["Action"] == "Edit")
         {
	   echo "<input type=hidden name=hdnEditgroup size=5 value=".$gResult["gid"].">";
	   echo "<input type=text name=txtEditgroup size=10 value=".$gResult["gname"].">";
	   echo "<input type=hidden name=hdnOldgroup size=10 value=".$gResult["gname"].">";
	   echo "<button name=btnUpdate type=button id=btnUpdate OnClick=\"frmMain.hdnCmd.value='GUpdate';frmMain.submit();\">";
	   echo "<img src=./images/save.bmp alt=save title=save  align=absmiddle width=16 heigh=16>";
	   echo "</button>";
	   echo "<button name=btnCancel type=button id=btnCancel value=Cancel OnClick=\"window.location='manage.php?PICK=CUS&CUS=$CUS';\">";
	   echo "<img src=./images/cancel.jpg alt=cancel title=cancel align=absmiddle width=16 heigh=16>";
	   echo "</button><br/>";
	 }else{
	   echo "<button name=btnEdit type=button id=btnEdit OnClick=\"window.location='manage.php?Action=Edit&GID=".$gResult["gid"]."&PICK=CUS&CUS=$CUS';\">";
	   echo "<img src=./images/edit.bmp alt=edit title=edit  align=absmiddle width=16 heigh=16>";
   	   echo "</button>";
	   echo "<button name=btnDel type=button id=btnDel OnClick=\"JavaScript:if(confirm('Delete ".$gResult["gname"]." group ?')==true){window.location='".$_SERVER["PHP_SELF"]."?Action=GDel&GID=".$gResult["gid"]."&PICK=CUS&CUS=$CUS&GRP=".$gResult["gname"]."';}\">";
           echo "<img src=./images/del.jpg alt=delete title=delete  align=absmiddle width=16 heigh=16>";
   	   echo "</button>";
		
	   echo "<a href=manage.php?PICK=GRP&CUS=$CUS&GID=".$gResult["gid"]."&GRP=".$gResult["gname"].">";
       	   echo "&nbsp;".$gResult["gname"]."&nbsp;</a><br/> ";
	 }

    	}
	echo "<input type=hidden name=hdnAddgroup size=5 value=$CUS>";
	echo "<input type=text name=txtAddgroup id=txtAddgroup size=20 value=>&nbsp";

	echo "<button name=btnAdd type=button id=btnAdd OnClick=\"frmMain.hdnCmd.value='GADD';frmMain.submit();\">Add</button>";
	echo "<br/> <font size+2 color=red>**Not support \"white space\",\"spacebar\",\"tab\",\"special character\"</font>";
	}elseif ( $_GET["PICK"] == "GRP"){

////////////////////////// list server

	echo "<h1><a href=manage.php?PICK=CUS&CUS=$CUS><< back&nbsp;</a>[ Group <font color=orange>".$_GET["GRP"]."</font> ] Server list </h1> ";

        $strSQL = "SELECT * FROM serverdetail WHERE gid=".$_GET["GID"]." ORDER BY server";
        $gQuery=mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
        while ($gResult = mysqli_fetch_array($gQuery))
        {
	  if($gResult["serial"] == $_GET["SERIAL"] and $_GET["Action"] == "Edit")
         {
	   $hostname=$gResult["server"];
	   $serial=$gResult["serial"];
	   $model=$gResult["model"];
	   $location=$gResult["location"];
	   $cpu=$gResult["cpu"];
	   $memory=$gResult["memory"];
	   $os=$gResult["os"];
	   $software=$gResult["software"];
	   $application=$gResult["application"];
	   $remark=$gResult["remark"];
         }else{
           echo "<button name=btnEdit type=button id=btnEdit OnClick=\"window.location='manage.php?PICK=GRP&CUS=$CUS&GID=".$gResult["gid"]."&Action=Edit&GRP=".$_GET["GRP"]."&SERIAL=".$gResult["serial"]."';\">";

           echo "<img src=./images/edit.bmp alt=edit title=edit  align=absmiddle width=16 heigh=16>";
           echo "</button>";
           echo "<button name=btnDel type=button id=btnDel OnClick=\"JavaScript:if(confirm('Delete server ".$gResult["server"]." ?')==true){window.location='".$_SERVER["PHP_SELF"]."?PICK=GRP&Action=SDel&CUS=$CUS&SERIAL=".$gResult["serial"]."&GID=".$gResult["gid"]."&GRP=".$_GET["GRP"]."&SRV=".$gResult["server"]."';}\">";
           echo "<img src=./images/del.jpg alt=delete title=delete  align=absmiddle width=16 heigh=16>";
           echo "</button>";
           echo "&nbsp;<a href=manage.php?PICK=GRP&CUS=$CUS&GID=".$gResult["gid"]."&Action=Edit&GRP=".$_GET["GRP"]."&SERIAL=".$gResult["serial"].">".$gResult["server"]."</a>&nbsp;";
	   echo "<input type='checkbox' name='srv[]' value=".$gResult["serial"].">";
	   echo "<br/> ";

         } 
	} // while grp list server
	echo "<hr/>";
	echo "Move selected server to group &nbsp;&nbsp; ";
	echo "<select id=selgrp name=selgrp style=width:120px >";
	echo "<option value=0 >Select group</option>";
	$strSQL = "SELECT * FROM servergroup WHERE gid != '".$_GET["GID"]."' and cname = '$CUS' ORDER BY gname ";
        $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
        while($objResult = mysqli_fetch_array($objQuery))
           {  
		echo "<option value='".$objResult["gid"]."'>".$objResult["gname"]."</option>";
           }  
	echo "</select></td>";
	echo "&nbsp;&nbsp;<button name=btnMove type=button id=btnMove OnClick=\"frmMain.hdnCmd.value='MOVE';frmMain.submit();\">Move</button>";
	echo "<br/><hr/><br/>";
	echo "<input type=hidden name=hdnAddgroup size=5 value=$CUS>";
        echo "<input type=hidden name=hdnAddserver size=5 value=".$_GET["GID"].">";
        echo "<input type=hidden name=hdnGrpName size=5 value=".$_GET["GRP"].">";
	echo "<br/>";
        echo "<table cellpadding=0 cellspacing=0>";
        echo "<tbody>";

	if ($_GET["Action"] != "Edit"){
      echo "<tr><td>Hostname<font size=-1 color=red>*</font> : <input type=text name=txthostname id=txthostname size=20></td>";
        echo "<td>Serial<font size=-1 color=red>*</font> : <input type=text name=txtserial id=txtserial size=20></td></tr>";
        echo "<tr><td>CPU<font size=-1 color=red>**</font> : <input type=text name=txtcpu id=txtcpu size=20 value=> <font color=yellow>pls specific if x86</font></td>";
        echo "<td>Memory<font size=-1 color=red>**</font> : <input type=text name=txtmemory id=txtmemory size=16 value=>&nbspGB</td></tr>";
        echo "<tr><td>Model : <select id=selmodel name=selmodel style=width:160px >";
	echo "<option value=N/A >Select model</option>";
        $strSQL = "SELECT * FROM model ORDER BY model";
        $Query=mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
        while ($Result = mysqli_fetch_array($Query))
        {
          echo "<option value='".$Result["model"]."'>".$Result["model"]."</option>";      
        }
        echo "</select></td>";
        echo "<td>Remark : <input type=text name=txtremark id=txtremark size=20 value=></td></tr>";
        echo "<tr><td>OS : <input type=text name=txtos id=txtos size=20 value=></td>";
        echo "<td>Application : <input type=text name=txtapplication id=txtapplication size=20 value=></td></tr>";
        echo "<tr><td>Software : <input type=text name=txtsoftware id=txtsoftware size=20 value=></td>";
        echo "<td>Location : <input type=text name=txtlocation id=txtlocation size=20 value=></td></tr>";
        echo "<tr><td colspan=2 align=right><button name=btnAdd type=button id=btnAdd OnClick=\"frmMain.hdnCmd.value='SADD';frmMain.submit();\">Add</button>&nbsp;&nbsp;<font size=-1 color=red>* MUST , ** require</font></td></tr>";

        }elseif ($_GET["Action"] == "Edit"){

        echo "<input type=hidden name=hdnEditSerial size=5 value='$serial'>";
        echo "<input type=hidden name=hdnEditHOST size=5 value='$hostname'>";
        echo "<tr><td>Hostname : <input type=text name=txthostname id=txthostname size=20 value='$hostname'></td>";
        echo "<td>Serial : <input type=text name=txtserial id=txtserial size=20 value='$serial'></td></tr>";
        echo "<tr><td>Model : <select id=selmodel name=selmodel style=width:160px >";
        $strSQL = "SELECT * FROM model ORDER BY model";
        $Query=mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
        while ($Result = mysqli_fetch_array($Query))
        {
          echo "<option value='".$Result["model"]."'";
	  if ($model == $Result["model"]) { echo " selected ";}
	  echo ">".$Result["model"]."</option>";      
        }
        echo "</select></td>";
        echo "<td>Remark : <input type=text name=txtremark id=txtremark size=20 value='$remark'></td></tr>";
        echo "<tr><td>CPU : <input type=text name=txtcpu id=txtcpu size=20 value='$cpu'> <font color=yellow>pls specific if x86</font></td>";
        echo "<td>Memory : <input type=text name=txtmemory id=txtmemory size=16 value='$memory'>&nbspGB</td></tr>";
        echo "<tr><td>OS : <input type=text name=txtos id=txtos size=20 value='$os'></td>";
        echo "<td>Application : <input type=text name=txtapplication id=txtapplication size=20 value='$application'></td></tr>";
        echo "<tr><td>Software : <input type=text name=txtsoftware id=txtsoftware size=20 value='$software'></td>";
        echo "<td>Location : <input type=text name=txtlocation id=txtlocation size=20 value='$location'></td></tr>";
        echo "<tr><td colspan=2 align=center>";
	echo "<button name=btnUpdate type=button id=btnUpdate OnClick=\"frmMain.hdnCmd.value='SUpdate';frmMain.submit();\">";
        echo "<img src=./images/save.bmp alt=save title=save  align=absmiddle width=16 heigh=16>";
        echo "</button>";
        echo "<button name=btnCancel type=button id=btnCancel value=Cancel OnClick=\"window.location='manage.php?PICK=GRP&CUS=$CUS&GID=".$_GET["GID"]."&GRP=".$_GET["GRP"]."';\">";
        echo "<img src=./images/cancel.jpg alt=cancel title=cancel align=absmiddle width=16 heigh=16>";
        echo "</button></td></tr>";

	}

        echo "</tbody>";
        echo "</table>";

	} // if pick = grp
/////////////// show group /////////////////
	echo "</form>";
  	echo "</div></div></div>";

?>

<?php mysqli_close($objConnect); ?>
</body>
</html>
