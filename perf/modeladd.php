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
<body OnLoad="javaScript:checktxt();">
<?php
if ($_GET["RESULT"]) {
   switch ($_GET["RESULT"]) {
    case 4:
        echo "<h2><font color=green>".$_GET["INFO"]." add success</font></h2>";
        break;
    case 1:
        echo "<h2><font color=red>Add failed</font></h2>";
        break;
    case 2:
        echo "<h2><font color=red>".$_GET["INFO"]." is exist </font></h2>";
        break;
    case 3:
        echo "<h2><font color=red>".$_GET["INFO"]." is invalid </font></h2>";
        break;
   }
	echo "<hr>";
  }
  if($_POST["hdnCmd"] == "Update")
          {
            $strSQL = "UPDATE model SET ";
            $strSQL .="model = '".trim($_POST["txtEditmodel"])."' ";
            $strSQL .="WHERE modelid = '".$_POST["hdnEditmodelid"]."' ";
            $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
            if(!$objQuery)
                {
                echo "Error Update [".mysqli_error()."]";
                }else{  // rename image
		  rename("./images/model/".$_POST["hdnEditmodel"],"./images/model/".trim($_POST["txtEditmodel"]).".jpg");
		}
          }
        if($_GET["Action"] == "Del")
          {
            $strSQL = "DELETE FROM model ";
            $strSQL .="WHERE modelid = '".$_GET["ModelID"]."' ";
            $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
            if(!$objQuery)
                {
                echo "Error Delete [".mysqli_error()."]";
                }
	    // delete image
	echo "<h2><font color=green> File".$_GET["MyFile"]." has been deleted </font></h2>";
	    unlink("./images/model/".$_GET["MyFile"]);
          }
?>
<h1> Add Model </h1>
<form name="frmmodel" method="post" enctype="multipart/form-data" action="modeladd1.php" >
<table>
<tr>
<td> Model </td>
  <td colspan="2"> <input name="txt2" type="text" id="txt2" > 
  </td>
</tr>
<tr>
<td> Upload picture </td>
<td colspan="2">
    <input name="fileUpload" type="file" accept="*.jpg">
</td>
<tr>
<td colspan="3" align="left">
    <input type="submit" name="Submit" value="Submit"> (jpeg only)
</td>
</tr>
</table>
</form>
<hr>
<div align=center><u><b>Model Table</b></u></div>
<?php
	$strSQL = "SELECT * FROM model ORDER BY model";
        $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
?>
	<form name="frmMain" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
<input type="hidden" name="hdnCmd" value="">
<table width=60% align=center border=0 cellspacing=0 cellpadding=0 bgcolor=black><tr><td>
<table border=0 width=100% cellspacing=1 cellpadding=1>
  <tr bgcolor=LimeGreen>
    <th width="50" align=center>Imgage</th>
    <th width="100" align=center>Model</th>
    <th width="30" align=center>Edit</th>
    <th width="30" align=center>Delete</th>
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
	$myfile=$objResult["model"].".jpg"; 

   if($objResult["modelid"] == $_GET["ModelID"] and $_GET["Action"] == "Edit")
                {
?>
  <td><div align="center">
                <input type="hidden" name="hdnEditmodelid" size="5" value="<?=$objResult["modelid"];?>">
                <input type="hidden" name="hdnEditmodel" size="5" value="<?=$myfile;?>">

        </div></td>
    <td><input type="text" name="txtEditmodel" size="50" value="<?=$objResult["model"];?>"></td>
    <td align="right"><div align="center">
<button name="btnAdd" type="button" id="btnUpdate" OnClick="frmMain.hdnCmd.value='Update';frmMain.submit();">
<img src="./images/save.bmp" alt="save" title="save" width="20" height="20" align="absmiddle" >
</button></div>
</td><td><div align="center">
<button name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
<img src="./images/cancel.jpg" alt="cancel" title="cancel" width="20" height="20" align="absmiddle">
</button>
    </div></td>
  </tr>

<?php }else{
	
 ?>
    <td align=center>
 <img src="./images/model/<?=$myfile;?>" alt="<?=$objResult["model"];?>" title="<?=$objResult["model"];?>" width="40">
    </td>
    <td align="center"><?=$objResult["model"];?></td>
    <td align="center">
<a href="<?=$_SERVER["PHP_SELF"];?>?Action=Edit&ModelID=<?=$objResult["modelid"];?>">
<img src="./images/edit.bmp" alt="edit" title="edit" width="20" height="20" align="absmiddle" >
</a></td>
        <td align="center"><a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?Action=Del&ModelID=<?=$objResult["modelid"];?>&MyFile=<?=$myfile;?>';}">
<img src="./images/del.jpg" alt="delete" title="delete" width="20" height="20" align="absmiddle" >
</a></td>
  </tr>
<?php 
        } // if 

} //which 

?>
</table>
</td></tr></table>

</body>
</html>
