<?php
	$PATH=$_GET["PATH"]."/".$_GET["YEAR"]."/".$_GET["MON"]."/".$_GET["SRV"].".".$_GET["MON"].".".$_GET["YEAR"];
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

        
        $GID=$_GET["GRP"];
        
        include('script.php');
        echo "<div class=\"wrapper col5\">"; 
        echo "<div id=\"container\">";
        $strSQL = "SELECT * FROM serverdetail WHERE  serial='".$_GET["SERIAL"]."'";
        $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
        $objResult = mysqli_fetch_array($objQuery);
?>
        <hr/>
<div class="wrapper col5">
  <div id="container">
    <div id="content">
      <h1><?=$objResult["server"]?>&nbsp&nbsp(<?=$objResult["serial"]?>) </h1>

      
<?php
	if ($objResult["model"] == "N/A" ) {
	echo "<img class='imgl' src=images/model/na.jpg alt='N/A'/>";
	}else{
?>
      <img class="imgl" src="images/model/<?=$objResult["model"]?>.jpg" alt="<?=$objResult["model"]?>"  />
<?php	} ?>  
      <strong>CPU :</strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?=$objResult["cpu"]?><br/>
      <strong>Memory :</strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?=$objResult["memory"]?><br/>
      <strong>OS :</strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?=$objResult["os"]?><br/>
      <strong>Application :</strong>&nbsp&nbsp&nbsp&nbsp<?=$objResult["application"]?><br/>
      <strong>Software :</strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?=$objResult["software"]?><br/>
      <strong>Location :</strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?=$objResult["location"]?><br/>
      <strong>Remark :</strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?=$objResult["remark"]?><br/>
 	</table>	
	<div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
