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
<html>
<head>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<body>
<?php
	if($_POST["hdnCmd"] == "Add")
	  {
	    $strSQL = "INSERT INTO usertb ";
	    $strSQL .="(userid,fname,lname,passwd,priv) ";
	    $strSQL .="VALUES ";
	    $strSQL .="('".$_POST["txtAdduserid"]."','".trim($_POST["txtAddfname"])."' ";
	    $strSQL .=",'".trim($_POST["txtAddlname"])."' ";
	    $strSQL .=",sha1('".trim($_POST["txtAdduserid"])."'),'".$_POST["rdopriv"]."') ";
	    $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
	    if(!$objQuery)
		{
		echo "Error Save [".mysqli_error()."]";
		}
	  }
	if($_POST["hdnCmd"] == "Update")
	  {
	    $strSQL = "UPDATE usertb SET ";
	    $strSQL .="userid = '".trim($_POST["txtEdituserid"])."' ";
	    $strSQL .=",fname = '".trim($_POST["txtEditfname"])."' ";
	    $strSQL .=",lname = '".trim($_POST["txtEditlname"])."' ";
	    $strSQL .=",priv = '".$_POST["rdoepriv"]."' ";
	    $strSQL .="WHERE userid = '".$_POST["hdnEdituserid"]."' ";
	    $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
	    if(!$objQuery)
		{
		echo "Error Update [".mysqli_error()."]";
		}
	  }
	if($_GET["Action"] == "Del")
	  {
	    $strSQL = "DELETE FROM usertb ";
	    $strSQL .="WHERE userid = '".$_GET["UserID"]."' ";
	    $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
	    if(!$objQuery)
		{
		echo "Error Delete [".mysqli_error()."]";
		}
	  }
	$strSQL = "SELECT * FROM usertb WHERE userid != 'root' ORDER BY userid";
	$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");

?>
<form name="frmMain" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
<input type="hidden" name="hdnCmd" value="">
<table border=0  cellspacing=0 cellpadding=0 bgcolor=black align=center><tr><td>
<table border=0  cellspacing=1 cellpadding=1>
  <tr bgcolor=LimeGreen>
    <th  align=center>User ID</th>
    <th  align=center>First Name</th>
    <th  align=center>Last Name</th>
    <th  align=center>Privilege</th>
    <th  align=center>Edit</th>
    <th  align=center>Delete</th>
    <th  align=center>Passwd</th>
  </tr>
<?php
	$i=1;
	while($objResult = mysqli_fetch_array($objQuery))
	  {
        if (($i%2) == 0) {
            echo "<tr bgcolor=lavender>";
        }else{
            echo "<tr bgcolor=LavenderBlush>";
        }
        $i+=1;
	    if($objResult["userid"] == $_GET["UserID"] and $_GET["Action"] == "Edit")
		{
?>
  <td><div align="center">
		<input type="text" name="txtEdituserid" size="10" value="<?=$objResult["userid"];?>">
		<input type="hidden" name="hdnEdituserid" size="5" value="<?=$objResult["userid"];?>">
	</div></td>
    <td><input type="text" name="txtEditfname" size="50" value="<?=$objResult["fname"];?>"></td>
    <td><input type="text" name="txtEditlname" size="50" value="<?=$objResult["lname"];?>"></td>
    <td><input type="radio" name="rdoepriv" value="staff"
	<?php if ($objResult["priv"] == "staff") { echo " checked ";} ?>
	>Staff
        <input type="radio" name="rdoepriv" value="customer" 
	<?php if ($objResult["priv"] == "customer") { echo " checked ";} ?>
	>Customer</td>
    <td colspan="3" align="right"><div align="center">
<button name="btnAdd" type="button" id="btnUpdate" OnClick="frmMain.hdnCmd.value='Update';frmMain.submit();">
<img src="./images/save.bmp" alt="save" title="save" width="20" height="20" align="absmiddle" >
</button>&nbsp;&nbsp;
<button name="btnCancel" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
<img src="./images/cancel.jpg" alt="cancel" title="cancel" width="20" height="20" align="absmiddle">
</button>
    </div></td>
  </tr>

<?php }else{ ?>
    <td><div align="center"><?=$objResult["userid"];?></div></td>
    <td><?=$objResult["fname"];?></td>
    <td><?=$objResult["lname"];?></td>
    <td align="center" ><?=$objResult["priv"];?></td>
    <td align="center">
<a href="<?=$_SERVER["PHP_SELF"];?>?Action=Edit&UserID=<?=$objResult["userid"];?>">
<img src="./images/edit.bmp" alt="edit" title="edit" width="20" height="20" align="absmiddle" >
</a></td>
	<td align="center"><a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?Action=Del&UserID=<?=$objResult["userid"];?>';}">
<img src="./images/del.jpg" alt="delete" title="delete" width="20" height="20" align="absmiddle" >
</a></td>
    <td align="center">
<a href=profile.php?UserID=<?=$objResult["userid"];?>>
<img src="./images/password.bmp" alt="change password" title="change password" width="20" height="20" align="absmiddle" >
</a></td>
  </tr>
<?php
	} // if 

} //which 

?>
  <tr bgcolor=white>
    <td><div align="center"><input type="text" name="txtAdduserid" size="10"></div></td>
    <td><input type="text" name="txtAddfname" size="50"></td>
    <td><input type="text" name="txtAddlname" size="50"></td>
    <td><input type="radio" name="rdopriv" value="staff">Staff
	<input type="radio" name="rdopriv" value="customer" checked>Customer</td>
    <td colspan="3" align="right"><div align="center">
      <input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frmMain.hdnCmd.value='Add';frmMain.submit();">
    </div></td>
  </tr>
</table>
</td></tr></table>
</form>
<font size=-1><b>*Default user password is same as userid </b></font>

<?php
mysqli_close($objConnect);
?>
</body>
</html>
