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

if($_POST["txtcustname"] == "" or $_POST["txtdet"] == "" or $_POST["txtaddress"] == "" or $_FILES["fileUpload"]["tmp_name"] == "" ) 
{
   echo "Custormer or detail or address or logo are empty";
}else{ 
include("db.php");
//$objConnect = mysqli_init();
//mysqli_real_connect($objConnect,"luna.g-able.ga",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",3036,NULL,MYSQLI_CLIENT_SSL) or die("Error Connect to Database");
//$objConnect = mysqli_connect("localhost",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",NULL,"/cloudsql/smiling-gasket-170108:us-central1:csypart") or die("Error Connect to Database");
   //$objDB = mysqli_select_db("perf");
   mysqli_query($objConnect,"SET NAMES UTF8");
 
   $strSQL = "SELECT customer FROM customer WHERE customer ='".$_POST["txtcustname"]."'";
   $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
   $objResult = mysqli_fetch_array($objQuery);

if (!$objResult){
	$images = ImageCreateFromJPEG($_FILES["fileUpload"]["tmp_name"]);
        ImageJPEG($images,"./images/customer/".$_POST["txtcustname"].".jpg");
   
   $strSQL = "INSERT INTO customer ";
   $strSQL .="(customer,detail,address,account) ";
   $strSQL .="VALUES ";
   $strSQL .="('".$_POST["txtcustname"]."','".$_POST["txtdet"]."','".$_POST["txtaddress"]."','root') ";
   $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");

   if($objQuery)
     {
	ob_end_clean(); //clear buffer
        header("Location: cuassign.php?RESULT=3&INFO=".$_POST["txtcustname"]);
     }
   else
     {
	ob_end_clean(); //clear buffer
        header("Location: cuassign.php?RESULT=4");
     }
} else{
   ob_end_clean(); //clear buffer
   header("Location: cuassign.php?RESULT=5&INFO=".$_POST["txtcustname"]);
}
   mysqli_close($objConnect);
}
?>
