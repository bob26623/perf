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

	
	$GID=$_GET["GRP"];
	
	include('script.php');
	echo "<div class=\"wrapper col5\">"; 
	echo "<div id=\"container\">";
	$strSQL = "SELECT cname,gname FROM servergroup WHERE  gid=$GID";
	$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
	$objResult = mysqli_fetch_array($objQuery);
	$CUS=$objResult["cname"];
	$GRP=$objResult["gname"];
	$FIRST="";

////// generate monthly report
	echo "<font size=+2>[ <a href=\"JavaScript:doCallAjax('server','trend.php?ACT=SHOW&CUS=$CUS&GRP=$GRP&MON='+selfile.value,'NONE');\">";
        echo "<font color=orange>$GRP</font>";
        echo "</a> ] Monthly report </font>&nbsp;&nbsp;";

	echo "<select id=selfile name=selfile style=width:100px onChange=\"JavaScript:ChkFile('$CUS','$GRP',this.value);\">";
	//echo "<select id=selfile name=selfile style=width:100px >";
        for($i=1;$i<=12;$i++){
          $Dmonth=date("M",mktime(0, 0, 0, date("m")-$i,1,date("Y")));
          $Dyear=date("Y",mktime(0, 0, 0, date("m")-$i,1,date("Y")));
	  if ($FIRST == "" ) { $FIRST="$Dmonth.$Dyear";}
          echo "<option value=$Dmonth.$Dyear>$Dmonth.$Dyear</option>";
        }
	echo "</select>";
	echo "&nbsp;&nbsp;<button id=btngen name=btngen OnClick=\"window.location='createPDF.php?GID=$GID&CUS=$CUS&GRP=$GRP&FILE='+selfile.value;\">Generate</button>&nbsp;&nbsp;";

/*
	echo "<button id=btnget name=btnget ";

	if (!file_exists("customer/pdf/$CUS.$GRP.$FIRST.pdf")) {
                echo "disabled='disabled'";
           } 

	echo " OnClick=\"window.location='./customer/pdf/$CUS.$GRP.'+ selfile.value +'.pdf';\">Get</button>";
*/
	if (file_exists("customer/pdf/$CUS.$GRP.$FIRST.pdf")) {
		echo "<a href=customer/pdf/$CUS.$GRP.$FIRST.pdf id=mypdf target=_blank > >> Download << </a>";
	}else{
		echo "<a href=# id=mypdf target=_blank style=display:none> >> Download << </a>";
	}
//echo "<script type=\"text/javascript\">ChkFile('".$CUS."','".$GRP."',selfile.value);</script>"; 
	echo "<br/><br/>";

//// CPU
	echo "<H1>CPU usage in last 12 months";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "<img src=images/red.png align=bottom alt=critical /><font size=-1>=80%</font>";
	echo "<img src=images/yellow.png align=bottom alt=warning /><font size=-1>=70%</font></H1>";
	echo "<table cellpadding=0 cellspacing=0>";
	echo "<thead><tr><th>Server Name</th>";
	$j=1;
	for($i=12;$i>0;$i--){
	  $Hmonth[$j]=date("M",mktime(0, 0, 0, date("m")-$i,1,date("Y")));
	  echo "<th>$Hmonth[$j]</th>";
	  $j++;
	}
	$lastmonth=date("n",mktime(0, 0, 0, date("m")-1,1,date("Y")));
   	$thisyear=date("Y");
	echo "<th>Avg</th>";
	echo "</tr></thead>";
	echo "<tbody>";
	$strSQL = "SELECT server,serial FROM serverdetail WHERE  gid=$GID ORDER BY server";
        $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
        while ($objResult = mysqli_fetch_array($objQuery))
          {
          	     echo "<tr>";
                echo "<td>";
		echo "<a href=\"JavaScript:doCallAjax('server','serverdetail.php?SERIAL=".$objResult["serial"]."','NONE');\">";
		echo $objResult["server"]."</a></td>";

		$AVG=getAVG("CPU",$Hmonth,$objResult["serial"],$lastmonth,$thisyear);
		showTable("CPU",$Hmonth,$objResult["serial"],$AVG,$CUS,$objResult["server"]);

		//echo "<td>$AVG</td>";
                echo "</tr>";
          } // while loop serverdetail
	echo "</tbody></table>";


//// Memory
	echo "<H1>Memory (GB) usage in last 12 months";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	echo "<img src=images/red.png align=bottom alt=critical /><font size=-1>=90%</font>";
	echo "<img src=images/yellow.png align=bottom alt=warning /><font size=-1>=70%</font></H1>";
        echo "<table cellpadding=0 cellspacing=0>";
        echo "<thead><tr><th>Server Name</th>";
        $j=1;
        for($i=12;$i>0;$i--){
          echo "<th>$Hmonth[$j]</th>";
          $j++;
        }
        echo "<th>Avg</th>";
        echo "</tr></thead>";
        echo "<tbody>";
        $strSQL = "SELECT server,serial FROM serverdetail WHERE gid=$GID ORDER BY server";
        $mobjQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
        while ($mobjResult = mysqli_fetch_array($mobjQuery))
          {
                     echo "<tr>";
                echo "<td>";
                echo "<a href=\"JavaScript:doCallAjax('server','serverdetail.php?SERIAL=".$mobjResult["serial"]."','NONE');\">";
                echo $mobjResult["server"]."</a></td>";

                $AVG=getAVG("MEM",$Hmonth,$mobjResult["serial"],$lastmonth,$thisyear);
                showTable("MEM",$Hmonth,$mobjResult["serial"],$AVG,$CUS,$mobjResult["server"]);

                //echo "<td>$AVG</td>";
                echo "</tr>";
          } // while loop serverdetail
        echo "</tbody></table>";
	echo "<div class=\"clear\"></div></div></div>";
	echo "<span id='server' />";

?>
