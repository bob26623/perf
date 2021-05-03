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

if ($_POST["PARA"] != "" ){
include("db.php");
//$objConnect = mysqli_init();
//mysqli_real_connect($objConnect,"luna.g-able.ga",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",3036,NULL,MYSQLI_CLIENT_SSL)) or die("Error Connect to Database");
//$objConnect = mysqli_connect("localhost",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",NULL,"/cloudsql/smiling-gasket-170108:us-central1:csypart") or die("Error Connect to Database");
        //$objDB = mysqli_select_db("perf");
        mysqli_query($objConnect,"SET NAMES UTF8");

        $strSQL = "SELECT * ";
        $strSQL.= "FROM usertb WHERE userid != 'root' ORDER BY userid";
        $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");

        echo "<form name='frmassign' method='post' action='cuassign2.php'>";

	echo "<table border=0 cellspacing=0 cellpadding=0 bgcolor=black><tr><td>";
	echo "<table border=0 width=100% cellspacing=1 cellpadding=1>";
        echo "<tr bgcolor=LimeGreen>";
        echo "<th width=50 align=center>Assign</th>";
        echo "<th width=100 align=center>Userid</th>";
        echo "<th width=100 align=center>First name</th>";
        echo "<th width=100 align=center>Last name</th>";
        echo "<th width=100 align=center>Privilege</th>";
        echo "</tr>";
        $i=1;
        while($objResult = mysqli_fetch_array($objQuery))
          {
               if (($i%2) == 0) {
                 echo "<tr bgcolor=lavender>";
               }else{
                 echo "<tr bgcolor=LavenderBlush>";
               }
               $i+=1;
		echo "<td align=center><input type='checkbox' name='user[]' value=".$objResult["userid"];
		$strSQL = "SELECT customer FROM customer WHERE customer = '".$_POST["PARA"]."'";
		$strSQL.= " and account like '%".$objResult["userid"]."%'";
        	$objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
		$objResult1 = mysqli_fetch_array($objQuery1);
		if ($objResult1) { // found
               		echo " checked ";
		}
		echo "></td>";
               echo "<td align=center>". $objResult["userid"]."</td>";
               echo "<td>". $objResult["fname"]."</td>";
               echo "<td>". $objResult["lname"]."</td>";
               echo "<td align=center>". $objResult["priv"]."</td></tr>";
          }
	    echo "<tr><td colspan=4 align=center>";
	    echo "<input name='CUSTOMER' type='hidden' value=".$_POST["PARA"].">";
	    echo "<input name='btnsubmit' type='submit' value='Update Account'>";
	    echo "</td></tr>";
            echo "</table>"; //table project
	    echo "</td></tr></table>";
	 mysqli_close($objConnect);
}
?>
