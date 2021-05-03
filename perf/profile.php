<?php
	ob_start();  // output bufering capture start 
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
<head profile="http://gmpg.org/xfn/11">
<title>Change profile</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
</head>
<body id="top">
<div class="wrapper col3">
</div>
<div class="wrapper col5">
  <div id="container">
    <div id="content">
      <h2>Profile</h2>
<form name="frmprofile" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
<?php
   if ($_POST["txtnewpass"] != $_POST["txtrepass"]) {
	echo "<font color=red size=+1>Password didn't match, try again</font>";
   }else{
	if ($_POST["txtnewpass"] == ""){ $_POST["txtnewpass"] = $_POST["txtoldpass"];}
        if($_POST["hdnCmd"] == "USER"){
	  $strSQL ="SELECT * FROM usertb WHERE userid = '".$_SESSION["Username"]."' and passwd = sha1('".$_POST["txtoldpass"]."') ";
          $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
          $objResult = mysqli_fetch_array($objQuery);
          if (!$objResult){
             echo "<font color=red size=+1>Password is wrong</font>";
          }else{
             $strSQL = "UPDATE usertb SET passwd=sha1('".$_POST["txtnewpass"]."'), ";
	     $strSQL .= "fname='".$_POST["txtfname"]."',lname='".$_POST["txtlname"]."' ";
	     $strSQL .= "WHERE userid='".$_SESSION["Username"]."'";
             $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
             if ($objQuery){
                echo "<font color=green size=+1>Password has been changed</font>";
             }else{
                echo "<font color=red size=+1>Password cann't change</font>";
             }

          }
	}elseif ( isset($_POST["hdnCmd"])) {
	  $strSQL = "UPDATE usertb SET passwd=sha1('".$_POST["txtnewpass"]."'), ";
	  $strSQL .= "fname='".$_POST["txtfname"]."',lname='".$_POST["txtlname"]."' ";
	  $strSQL .= "WHERE userid='".$_POST["hdnCmd"]."'";
           $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
           if ($objQuery){
		ob_end_clean(); //clear buffer
                header("Location: edituser.php");
           }
	}
    }// check new password match

	if( isset($_GET["UserID"]))
  	{
    	  $strSQL = "SELECT * FROM usertb WHERE userid='".$_GET["UserID"]."'";
    	  echo "<input type='hidden' name='hdnCmd' value='".$_GET["UserID"]."'>";
  	}else{
    	  $strSQL = "SELECT * FROM usertb WHERE userid='".$_SESSION["Username"]."'";
    	  echo "<input type='hidden' name='hdnCmd' value='USER'>";
  	}
   	$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
   	$objResult = mysqli_fetch_array($objQuery); 
?>
      <table summary="Summary Here" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="light">
            <td>User id</td>
            <td><?=$objResult["userid"]?></td>
          </tr>
          <tr class="dark">
            <td>First name</td>
            <td><input type='text' name='txtfname' id='txtfname' size='20' maxlength='20' value='<?=$objResult["fname"]?>'></td>
          </tr>
          <tr class="light">
            <td>Last name</td>
            <td><input type='text' name='txtlname' id='txtlname' size='20' maxlength='20' value='<?=$objResult["lname"]?>'></td>
          </tr>
          <tr class="dark">
            <td>Change password</td>
            <td><input type='password' name='txtnewpass' id='txtnewpass' size='20' maxlength='20'></td>
          </tr>
          <tr class="light">
            <td>Type password again</td>
            <td><input type='password' name='txtrepass' id='txtrepass' size='20' maxlength='20'></td>
          </tr>
<?php
if( !isset($_GET["UserID"]))
{
?>
          <tr class="dark">
            <td colspan=2>Note : <font color=red>To change information ,please type password below</font></td>
          </tr>
          <tr class="light">
            <td>Password</td>
            <td><input type='password' name='txtoldpass' id='txtoldpass' size='20' maxlength='20'></td>
          </tr>
<?php } ?>
          <tr class="dark">
            <td colspan=2><button name="btnChange" type="button" id="btnChange" OnClick="frmprofile.submit();">Submit changes !!</button></td>
          </tr>
        </tbody>
      </table>
	</form>
    </div>
  </div>
</div>
</body>
</html>
