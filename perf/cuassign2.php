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
  $i=0;
if ($_POST["user"] != "") {
  foreach($_POST["user"] as $key => $user)
   { $myuser[$i]=$user; 
     $i+=1;	
   }
   $myuser[$i]=root;
  $alluser=implode("^",$myuser);
  $strSQL = "UPDATE customer SET account='$alluser' WHERE customer='".$_POST["CUSTOMER"]."'"; 
}else {  // clear all user in customer account
  $strSQL = "UPDATE customer SET account='' WHERE customer='".$_POST["CUSTOMER"]."'";
}
$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
if ($objQuery) {
  ob_end_clean(); //clear buffer 
  header("Location: cuassign.php?RESULT='yes'"); 
}else{
  ob_end_clean(); //clear buffer 
  header("Location: cuassign.php?RESULT='no'"); 
}

   mysqli_close($objConnect);
?>
