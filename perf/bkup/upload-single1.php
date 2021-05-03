<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<body id="top">
<div class="wrapper col6">
<div id="footer">
<?
	ob_implicit_flush(true); // to flush output

if (($_POST["selgrp"] == '' ) || ($_POST["selsrv"] == '' ) | ($_FILES["fileupload"]["name"] == '' ) ) {
	echo "<h2>Please select group or server or file</h2>";
}else{

   $file = $_FILES["fileupload"]["name"];
   $sizefile = $_FILES['fileupload']['size'];

echo "<br/> file=$file size=".round($sizefile/1048576,2)."MB <br/>";

if(!file_exists("customer/".$_POST["CUS"])) { mkdir("customer/".$_POST["CUS"]); }
if(!file_exists("customer/".$_POST["CUS"]."/".$_POST["selgrp"])) { mkdir("customer/".$_POST["CUS"]."/".$_POST["selgrp"]); }
if(!file_exists("customer/".$_POST["CUS"]."/".$_POST["selgrp"]."/".$_POST["selsrv"])) { 
	mkdir("customer/".$_POST["CUS"]."/".$_POST["selgrp"]."/".$_POST["selsrv"]); 
	}
if(!file_exists("customer/".$_POST["CUS"]."/".$_POST["selgrp"]."/".$_POST["selsrv"]."/tmp")) { 
	mkdir("customer/".$_POST["CUS"]."/".$_POST["selgrp"]."/".$_POST["selsrv"]."/tmp"); 
	}
$PATH="customer/".$_POST["CUS"]."/".$_POST["selgrp"]."/".$_POST["selsrv"];

        copy($_FILES["fileupload"]["tmp_name"],"$PATH/tmp/$file");
	$CHK = shell_exec("/usr/bin/file $PATH/tmp/$file | cut -f2 -d:");
	$FPATH=getcwd();
	switch (trim($CHK)){
	case "gzip compressed data - deflate method , original file name" :
	  	$tarfile = shell_exec("cd $PATH/tmp; /usr/bin/ls $file |/usr/bin/cut -f1-4 -d.");
	  	$HOST = trim(shell_exec("cd $PATH/tmp; /usr/bin/ls $file |/usr/bin/cut -f1 -d."));
	  	$MONTH = trim(shell_exec("cd $PATH/tmp; /usr/bin/ls $file |/usr/bin/cut -f2 -d."));
		$YEAR = trim(shell_exec("cd $PATH/tmp; /usr/bin/ls $file |/usr/bin/cut -f3 -d."));
		if(!file_exists("$PATH/$YEAR")) { mkdir("$PATH/$YEAR"); }
		if(!file_exists("$PATH/$YEAR/$MONTH")) { mkdir("$PATH/$YEAR/$MONTH"); }
	  	echo shell_exec("/usr/bin/gunzip $PATH/tmp/$file");
		echo shell_exec("cd $PATH/tmp; /usr/bin/tar xf $tarfile");
// check sar-u directory
		if(!file_exists("$PATH/tmp/sar-u")) {
		  echo "There is no sar-u directory<br/>";
		  exit;
		}

		include('script.php');
		CallScriptCPU($PATH,$HOST,$MONTH,$YEAR,"utilize");
		CallScriptCPU($PATH,$HOST,$MONTH,$YEAR,"aversar");
		CallScriptCPU($PATH,$HOST,$MONTH,$YEAR,"realcpu");

		// ---- generate graph
		$start= date("G:i:s");
		echo "<br/>Generate Graph : $start";
		
		GenGraph("$PATH/$YEAR/$MONTH","$HOST","$MONTH","$YEAR");
		$finish= date("G:i:s");
		$arrS = explode(":",$start);
		$arrF = explode(":",$finish);
		echo " - $finish spend time = ".date("i.s",mktime(0,$arrF[1]-$arrS[1],$arrF[2]-$arrS[2],0,0,0))." minutes<br/>";

		echo "<br/><img src=$PATH/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.png />";
		echo "<br/><img src=$PATH/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.png />";
		echo "<br/><img src=$PATH/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.png />";

		break;
	case "ascii text" :
		echo "text";
		break;
	default :
		echo "unknown format $CHK";
		break;
	}

	exec("sleep 3");
	echo "<hr/>";

	  
//   } // upload file completed
} // check post group , server


?>
</div></div>
