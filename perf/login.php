<?php
	$MysqlUser="lad";
	$MysqlPass="lad";

	session_start();

	$strUsername = trim($_POST["tUsername"]);
	$strPassword = trim($_POST["tPassword"]);
	
	//*** Check Username ***//
	if(trim($strUsername) == "")
	{
		echo "<font color=white size=4>&nbsp&nbsp** Plase input [Username]</font>";
		exit();
	}
	
	//*** Check Password ***//
	if(trim($strPassword) == "")
	{
		echo "<font color=white size=4>&nbsp&nbsp** Plase input [Password]</font>";
		exit();
	}
	
//$objConnect = mysqli_init();
//mysqli_real_connect($objConnect,"luna.g-able.ga",$MysqlUser,$MysqlPass,"perf",3036,NULL,MYSQLI_CLIENT_SSL) or die("Error Connect to Database");
	//$objConnect = mysqli_connect("localhost",$MysqlUser,$MysqlPass,"perf",NULL,"/cloudsql/smiling-gasket-170108:us-central1:csypart") or die("Error Connect to Database");
	$objConnect = mysqli_connect("localhost",$MysqlUser,$MysqlPass,"perf",NULL,"/var/run/mysqld/mysqld.sock") or die("Error Connect to Database");
	//$objDB = mysqli_select_db("perf");
	mysqli_query($objConnect,"SET NAMES UTF8");


	//*** Check Username & Password ***//

	$strSQL = "SELECT * FROM usertb WHERE userid = '".$strUsername."' and passwd = sha1('".$strPassword."') ";
	$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
	$objResult = mysqli_fetch_array($objQuery);
	if($objResult)
	{
		echo "MOVE2MAIN";

		//*** Session ***//
		$_SESSION["Username"] = $strUsername;
		$_SESSION["fname"] = $objResult["fname"];
		$_SESSION["MUSER"] = $MysqlUser;
		$_SESSION["MPASS"] = $MysqlPass;
		$_SESSION["PRIV"] = $objResult["priv"];
		session_write_close();
	}
	else
	{
		echo "<font color=white size=4>&nbsp&nbsp ** Invalid username or password</font>";
	}

	mysqli_close($objConnect);
?>
