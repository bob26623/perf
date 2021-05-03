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

require('./fpdf17/fpdf.php');
class PDF extends FPDF
{
function Header()
{
        $this->Image('./images/g-able.gif',10,8);
        //$this->Image('./images/customer/'.$_GET["CUS"].'.jpg',250,8);
        $this->SetFont('Arial','B',15);
        $this->Ln(18);
}

function Footer()
{
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
function ShowImage($link,$hostname,$pathfile)
{
     if (file_exists($pathfile.".png")) {
        $this->AddPage();
        $this->SetLink($link);
        $this->SetFont('Arial','',14);
        $this->SetFillColor(153,0,102);
        $this->SetTextColor(255,255,255);
        $this->Cell(0,6,"Performance report",0,1,'C',true);
        $this->SetFillColor(255,204,0);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,6,"$hostname",0,1,'C',true);
        $this->Ln(4);
        $this->Image($pathfile.".png",20,50);
     }
}
} // class

?>

<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<body id="top">
<div class="wrapper col3"></div>
<div class="wrapper col5">
<div id="container">
<?php
	include('script.php');
	include("pChart2.1.3/class/pData.class.php");
  	include("pChart2.1.3/class/pDraw.class.php");
  	include("pChart2.1.3/class/pImage.class.php");

	#### Fatal error: Maximum execution time of xxx seconds exceeded
	#### set 10 minutes
	ini_set('max_execution_time',600);  

	ob_implicit_flush(true); // to flush output
	// list file 
	$CUS=$_GET["CUS"];
	echo "=== Start process ===<br/><hr/>";
	$objOpen = opendir("customer/$CUS/tmp");
           while (($ufile = readdir($objOpen)) !== false)
                {
                if ( $ufile != "." && $ufile != ".." ) {
                  $i++;
                  echo "$i. <font color=yellow>" . $ufile . "</font><br />";

	$file=trim($ufile);
		$fname=explode(".",$file);
		$HOST = $fname[0];
		$MONTH = $fname[1];
		$YEAR = $fname[2];
		$ext = $fname[3];
		$tarfile = "$fname[0].$fname[1].$fname[2].$fname[3]";
		$PATH=ChkPath($CUS,$HOST);
		if ($PATH[0] == "0") {
			echo "  - <font color=red>server name \"$HOST\" didn't exist in customer $CUS or Group. Please contact admin</font><br/>";
			unlink("customer/$CUS/tmp/$ufile");
			continue;
		}else{
		// assign pagesize for cpu arch
                if (preg_match("/x86/",$PATH[1]) || preg_match("/X86/",$PATH[1])) {
                  $PAGESIZE=262144;
                }else{
                  $PAGESIZE=131072;
                }
		// clear temp file 
		shell_exec("/bin/rm -rf $PATH[0]/tmp/*");
		if(!file_exists("$PATH[0]/$YEAR")) { mkdir("$PATH[0]/$YEAR"); }
                if(!file_exists("$PATH[0]/$YEAR/$MONTH")) { mkdir("$PATH[0]/$YEAR/$MONTH"); }
		}

	$CHK = shell_exec("/usr/bin/file customer/$CUS/tmp/$file | cut -f2 -d: |cut -f1 -d,");
//echo "$CHK<br/>";
        //$FPATH=getcwd();
        switch (trim($CHK)){
	case "gzip compressed data" :

		if ( $tarfile != "$HOST.$MONTH.$YEAR.tar" or strlen($YEAR) != 4 ){
			echo "  - <font color=red>Incorrect format hostname.month.year.tar.gz : $ufile </font><br/>";
			echo "  - <font color=red>Example : server1.Jan.2012.tar.gz </font><br/>";
			unlink("customer/$CUS/tmp/$ufile");
			continue;
		}

		if (copy("customer/$CUS/tmp/$ufile","$PATH[0]/tmp/$ufile")) {
		  unlink("customer/$CUS/tmp/$ufile"); 
		}

		echo shell_exec("/bin/gunzip $PATH[0]/tmp/$file");
                echo shell_exec("cd $PATH[0]/tmp; /bin/tar xf $tarfile");

		echo "  - run script : ";
@ob_flush();

        	//$start= date("G:i:s");
		//$arrS = explode(":",$start);
		$start=round(microtime(true),4);


		//check sar-u dir
		if(!file_exists("$PATH[0]/tmp/sar-u"))
		  {
		    echo " \"<font color=red>no sar-u</font>\" ";
@ob_flush();
		  }else{
		    //if(!file_exists("$PATH[0]/$YEAR")) { mkdir("$PATH[0]/$YEAR"); }
                    //if(!file_exists("$PATH[0]/$YEAR/$MONTH")) { mkdir("$PATH[0]/$YEAR/$MONTH"); }
                    CallScriptCPU($PATH[0],$HOST,$MONTH,$YEAR,"utilize","");
                    CallScriptCPU($PATH[0],$HOST,$MONTH,$YEAR,"aversar","");
                    CallScriptCPU($PATH[0],$HOST,$MONTH,$YEAR,"realcpu","");
		  }
		//check sar-r dir
		if(!file_exists("$PATH[0]/tmp/sar-r"))
		  {
		    echo " \"<font color=red>no sar-r</font>\" ";
@ob_flush();
		  }else{
                    CallScriptCPU($PATH[0],$HOST,$MONTH,$YEAR,"realmem",$PATH[2]." ".$PAGESIZE);
		  }
		//check sar-g dir
		if(!file_exists("$PATH[0]/tmp/sar-g"))
		  {
		    echo " \"<font color=red>no sar-g</font>\" ";
@ob_flush();
		  }else{
                    CallScriptCPU($PATH[0],$HOST,$MONTH,$YEAR,"scan","");
		  }
		//check sar-q dir
		if(!file_exists("$PATH[0]/tmp/sar-q"))
		  {
		    echo " \"<font color=red>no sar-q</font>\" ";
@ob_flush();
		  }else{
                    CallScriptCPU($PATH[0],$HOST,$MONTH,$YEAR,"runq","");
		  }
		//check iostat dir
                if(!file_exists("$PATH[0]/tmp/iostat"))
                  {
                    echo " \"<font color=red>no iostat</font>\" ";
                  }else{
                    echo " iostat ";
@ob_flush();
		    if(!file_exists("$PATH[0]/$YEAR/$MONTH/iostat")) { mkdir("$PATH[0]/$YEAR/$MONTH/iostat"); }
                    copy("scripts/iostat.pl","$PATH[0]/tmp/iostat/iostat.pl");
                    chmod("$PATH[0]/tmp/iostat/iostat.pl",0750);
                    echo shell_exec("cd ".getcwd()."/$PATH[0]/tmp/iostat;./iostat.pl");
                    $objScan = scandir("$PATH[0]/tmp/iostat/"); 
                    foreach ($objScan as $value) { 
                        if (preg_match("/.out$/",$value)) {
                        copy("$PATH[0]/tmp/iostat/$value","$PATH[0]/$YEAR/$MONTH/iostat/$HOST.$MONTH.$YEAR.$value");
                        }
                    }

                  }

		$finish=round(microtime(true),4);
		echo  ": ".substr(($finish-$start),0,5)." seconds<br/>";
@ob_flush();


                // ---- generate graph
                $start= date("G:i:s");
                echo "  - Generate Graph : ";
@ob_flush();

                $his=GenGraph("$PATH[0]/$YEAR/$MONTH","$HOST","$MONTH","$YEAR",$PATH[1],$PATH[2],6);
		shell_exec("/bin/rm -rf $PATH[0]/tmp/*");

		// iostat
		if(!file_exists("$PATH[0]/$YEAR/$MONTH/iostat"))
                  {
                    echo " \"<font color=red>[no iostat]</font>\" ";
                  }else{
                echo " [iostat] :";
@ob_flush();
                io2g("$PATH[0]","$HOST.$MONTH.$YEAR.asvc.out","$HOST","$MONTH","$YEAR","Average Service Time","Time (Millisecond)");
echo ".";@ob_flush();
                io2g("$PATH[0]","$HOST.$MONTH.$YEAR.wsvc.out","$HOST","$MONTH","$YEAR","Wait Service Time","Time (Millisecond)");
echo ".";@ob_flush();
                io2g("$PATH[0]","$HOST.$MONTH.$YEAR.aread.out","$HOST","$MONTH","$YEAR","Average read","Data (MB)");
echo ".";@ob_flush();
                io2g("$PATH[0]","$HOST.$MONTH.$YEAR.pread.out","$HOST","$MONTH","$YEAR","Peak read","Data (MB)");
echo ".";@ob_flush();
                io2g("$PATH[0]","$HOST.$MONTH.$YEAR.awrite.out","$HOST","$MONTH","$YEAR","Average write","Data (MB)");
echo ".";@ob_flush();
                io2g("$PATH[0]","$HOST.$MONTH.$YEAR.pwrite.out","$HOST","$MONTH","$YEAR","Peak write","Data (MB)");
echo ".";@ob_flush();


                $objScan = scandir("$PATH[0]/$YEAR/$MONTH/iostat/");
                foreach ($objScan as $value) {
                   if (preg_match("/._asvc\.out$/",$value)) {
                     io2g("$PATH[0]",$value,$HOST,$MONTH,$YEAR,"Average Service Time","Time (Millisecond)");
echo ".";@ob_flush();
                   }elseif (preg_match("/._pasvc\.out$/",$value)) {
                     io2g("$PATH[0]",$value,$HOST,$MONTH,$YEAR,"Peak Service Time","Time (Millisecond)");
echo ".";@ob_flush();
                   }elseif (preg_match("/._wsvc\.out$/",$value)) {
                     io2g("$PATH[0]",$value,$HOST,$MONTH,$YEAR,"Wait Service Time","Time (Millisecond)");
echo ".";@ob_flush();
                   }elseif (preg_match("/._pwsvc\.out$/",$value)) {
                     io2g("$PATH[0]",$value,$HOST,$MONTH,$YEAR,"Peak Wait Time","Time (Millisecond)");
echo ".";@ob_flush();
                   }elseif (preg_match("/._read\.out$/",$value)) {
		     io2g("$PATH[0]",$value,$HOST,$MONTH,$YEAR,"Read Data throughput","Megabytes");
echo ".";@ob_flush();
		   }elseif (preg_match("/._write\.out$/",$value)) {
                     io2g("$PATH[0]",$value,$HOST,$MONTH,$YEAR,"Write Data throughput","Megabytes");
echo ".";@ob_flush();
		   }
                }

		} // check iostat for gen graph

                $finish= date("G:i:s");
                $arrS = explode(":",$start);
                $arrF = explode(":",$finish);
                echo date("i.s",mktime(0,$arrF[1]-$arrS[1],$arrF[2]-$arrS[2],0,0,0))." minutes<br/>";
@ob_flush();
		
		if ($MONTH == "Jan" || $MONTH == "Feb" || $MONTH == "Mar"|| $MONTH == "Apr"|| $MONTH == "May"|| $MONTH == "Jun"|| $MONTH == "Jul"|| $MONTH == "Aug"|| $MONTH == "Sep"|| $MONTH == "Oct"|| $MONTH == "Nov"|| $MONTH == "Dec"){

		// update history cpu and mem to sql
		$strSQL = "SELECT hid FROM history WHERE  serial='$PATH[3]' and year=$YEAR";
		$objQuery = mysqli_query($objConnect,$strSQL);
        	$objResult = mysqli_fetch_array($objQuery);      
        	if($objResult)
		{ // update
		  	$strSQL = "UPDATE history SET `$MONTH`='$his[0]' WHERE serial='$PATH[3]' and year=$YEAR and `type`='CPU'";
			$objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 
		  	$strSQL = "UPDATE history SET `$MONTH`='$his[1]' WHERE serial='$PATH[3]' and year=$YEAR and `type`='MEM'";
			$objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 

		}else{ // insert


		  $strSQL = "INSERT INTO history (serial,year,`type`,`$MONTH`) VALUE ('$PATH[3]',$YEAR,'CPU','$his[0]')";
        	  $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 
		  $strSQL = "INSERT INTO history (serial,year,`type`,`$MONTH`) VALUE ('$PATH[3]',$YEAR,'MEM','$his[1]')";
        	  $objQuery2 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 
		}
		}// no update
	
		
		// show image
		if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.png alt='cpu utilize' width=150 height=100/>";
                echo "</a>&nbsp";
		}
		if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.png alt='cpu average' width=150 height=100/>";
                echo "</a>&nbsp";
		}
		if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.png alt='real cpu' width=150 height=100/>";
                echo "</a>&nbsp";
		}
		if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realmem.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realmem.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realmem.png alt='memory utilize' width=150 height=100/>";
                echo "</a>&nbsp";
		}
		if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.scan.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.scan.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.scan.png alt='Scan rate' width=150 height=100/>";
                echo "</a>&nbsp";
		}
		if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.runq.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.runq.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.runq.png alt='run queue' width=150 height=100/>";
                echo "</a><br/>";
		}
		if (strlen($MONTH) != 3 ){
                $srcfile=$PATH[0]."/".$YEAR."/".$MONTH."/".$HOST.".".$MONTH.".".$YEAR;
                $pdf=new PDF('L','mm','A4');
                $pdf->SetAuthor('screen');
                $pdf->AddFont('Tahoma','','tahoma.php');
                $pdf->AddFont('Tahoma','b','tahomab.php');
                $pdf->AddPage();
                $pdf->SetFont('Arial','',14);
                $pdf->SetFillColor(153,0,102);
                $pdf->SetTextColor(255,255,255);
                $pdf->Cell(0,6,"Performance on ".$HOST,0,1,'C',true);
                $pdf->SetFillColor(255,204,0);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont('Arial','',25);
                $pdf->SetX(120);
                $pdf->Text(120,105,"Utilization Report");
                $pdf->Text(120,135,$MONTH." ".$YEAR);
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.png")){
                $pdf->ShowImage(0,$HOST,$srcfile.".utilize");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.png")){
                $pdf->ShowImage(0,$HOST,$srcfile.".aversar");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.png")){
                $pdf->ShowImage(0,$HOST,$srcfile.".realcpu");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realmem.png")){
                $pdf->ShowImage(0,$HOST,$srcfile.".realmem");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.scan.png")){
                $pdf->ShowImage(0,$HOST,$srcfile.".scan");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.runq.png")){
                $pdf->ShowImage(0,$HOST,$srcfile.".runq");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/iostat/$HOST.$MONTH.$YEAR.asvc.out.png")){
                $pdf->ShowImage(0,$HOST,$PATH[0]."/".$YEAR."/".$MONTH."/iostat/".$HOST.".".$MONTH.".".$YEAR.".asvc.out");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/iostat/$HOST.$MONTH.$YEAR.wsvc.out.png")){
                $pdf->ShowImage(0,$HOST,$PATH[0]."/".$YEAR."/".$MONTH."/iostat/".$HOST.".".$MONTH.".".$YEAR.".wsvc.out");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/iostat/$HOST.$MONTH.$YEAR.aread.out.png")){
                $pdf->ShowImage(0,$HOST,$PATH[0]."/".$YEAR."/".$MONTH."/iostat/".$HOST.".".$MONTH.".".$YEAR.".aread.out");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/iostat/$HOST.$MONTH.$YEAR.pread.out.png")){
                $pdf->ShowImage(0,$HOST,$PATH[0]."/".$YEAR."/".$MONTH."/iostat/".$HOST.".".$MONTH.".".$YEAR.".pread.out");
                }
		if (file_exists("$PATH[0]/$YEAR/$MONTH/iostat/$HOST.$MONTH.$YEAR.awrite.out.png")){
                $pdf->ShowImage(0,$HOST,$PATH[0]."/".$YEAR."/".$MONTH."/iostat/".$HOST.".".$MONTH.".".$YEAR.".awrite.out");
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/iostat/$HOST.$MONTH.$YEAR.pwrite.out.png")){
                $pdf->ShowImage(0,$HOST,$PATH[0]."/".$YEAR."/".$MONTH."/iostat/".$HOST.".".$MONTH.".".$YEAR.".pwrite.out");
                }
                $pdf->Output("$srcfile.pdf","F");
                echo "<a href=$srcfile.pdf target=_blank><img src=images/pdf.png heigh=50 width=40 /></a>";
                }
                echo "<hr/>";
@ob_flush();

                break;
        case "ASCII text" :
		$temp=explode(".",$ufile);
		if(sizeof($temp)<4 ){
		   echo "<font color=red>$ufile</font> : Invalid file format (server.DateMonth.Year.sar-? or .out)<br/>";
                  unlink("customer/$CUS/tmp/$ufile");
		}else{
		if (copy("customer/$CUS/tmp/$ufile","$PATH[0]/tmp/$ufile")) {
                  unlink("customer/$CUS/tmp/$ufile");
                }
		if ($ext == "sar-u" ){
	 	  copy("scripts/convertc.pl","$PATH[0]/tmp/convertc.pl");
        	  chmod("$PATH[0]/tmp/convertc.pl",0750);
        	  shell_exec("cd $PATH[0]/tmp/;./convertc.pl $ufile");
		  copy("$PATH[0]/tmp/cpu.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.cpu.out");
		  cpudaily("$PATH[0]/$YEAR/$MONTH",$HOST,$MONTH,$YEAR,cpu,950,370,$PATH[1],$PATH[2]); 

		  if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.cpu.png")){
                	echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.cpu.png target=_blank>";
                	echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.cpu.png alt='CPU daily' width=150 height=100/>";
                	echo "</a><br/>";
                  }
                  echo "<hr/>";
		  
		}elseif ($ext == "sar-r"){
		  copy("scripts/convertm.pl","$PATH[0]/tmp/convertm.pl");
                  chmod("$PATH[0]/tmp/convertm.pl",0750);
                  shell_exec("cd $PATH[0]/tmp/;./convertm.pl $ufile $PATH[2]");
                  copy("$PATH[0]/tmp/mem.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.mem.out");
                  memdaily("$PATH[0]/$YEAR/$MONTH",$HOST,$MONTH,$YEAR,mem,950,370,$PATH[1],$PATH[2]);

                  if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.mem.png")){
                        echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.mem.png target=_blank>";
                        echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.mem.png alt='Memory daily' width=150 height=100/>";
                        echo "</a><br/>";
                  }
                  echo "<hr/>";

		}elseif ($ext == "iostat"){
                  copy("scripts/converti.pl","$PATH[0]/tmp/converti.pl");
                  chmod("$PATH[0]/tmp/converti.pl",0750);
                  shell_exec("cd $PATH[0]/tmp/;./converti.pl $ufile");

                  copy("$PATH[0]/tmp/wsvc.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.wsvc.out");
                  copy("$PATH[0]/tmp/asvc.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.asvc.out");
                  copy("$PATH[0]/tmp/read.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.read.out");
                  copy("$PATH[0]/tmp/write.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.write.out");
                  iodaily("$PATH[0]/$YEAR/$MONTH",$HOST,$MONTH,$YEAR,wsvc,950,370,$PATH[1],$PATH[2]);
                  iodaily("$PATH[0]/$YEAR/$MONTH",$HOST,$MONTH,$YEAR,asvc,950,370,$PATH[1],$PATH[2]);
                  iodaily("$PATH[0]/$YEAR/$MONTH",$HOST,$MONTH,$YEAR,read,950,370,$PATH[1],$PATH[2]);
                  iodaily("$PATH[0]/$YEAR/$MONTH",$HOST,$MONTH,$YEAR,write,950,370,$PATH[1],$PATH[2]);

                  if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.wsvc.png")){
                        echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.wsvc.png target=_blank>";
                        echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.wsvc.png alt='Memory daily' width=150 height=100/>";
                        echo "</a>";
                  }
                  if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.asvc.png")){
                        echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.asvc.png target=_blank>";
                        echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.asvc.png alt='Memory daily' width=150 height=100/>";
                        echo "</a>";
                  }
                  if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.read.png")){
                        echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.read.png target=_blank>";
                        echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.read.png alt='Memory daily' width=150 height=100/>";
                        echo "</a>";
                  }
                  if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.write.png")){
                        echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.write.png target=_blank>";
                        echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.write.png alt='Memory daily' width=150 height=100/>";
                        echo "</a>";
                  }

                  echo "<hr/>";
		  
		  

		}elseif ($ext == "out"){
		  echo "  - run script : ";
@ob_flush();
		  $start=round(microtime(true),4);

		  copy("scripts/convertf.pl","$PATH[0]/tmp/convertf.pl");
                  chmod("$PATH[0]/tmp/convertf.pl",0750);
                  shell_exec("cd $PATH[0]/tmp/;./convertf.pl $ufile $PATH[2]");
		  if (file_exists("$PATH[0]/tmp/utilize.out")){
                    copy("$PATH[0]/tmp/utilize.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.out");
                    echo " utilize ";
                  }
                  if (file_exists("$PATH[0]/tmp/aversar.out")){
                    copy("$PATH[0]/tmp/aversar.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.out");
                    echo " aversar ";
                  }
                  if (file_exists("$PATH[0]/tmp/realcpu.out")){
                  copy("$PATH[0]/tmp/realcpu.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.out");
                    echo " realcpu ";
                  }
		  if (file_exists("$PATH[0]/tmp/realmem.out")){
                    copy("$PATH[0]/tmp/realmem.out","$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realmem.out");
                    echo " realmem ";
		  }
		  
		  $finish=round(microtime(true),4);
                  echo  ": ".substr(($finish-$start),0,5)." seconds<br/>";
@ob_flush();
		  
		  // ---- generate graph
                  $start= date("G:i:s");
                  echo "  - Generate Graph : ";
@ob_flush();
		  if (filesize("$PATH[0]/tmp/$ufile") >= 100000){ // file size 100KB
                    $his=GenGraph("$PATH[0]/$YEAR/$MONTH","$HOST","$MONTH","$YEAR",$PATH[1],$PATH[2],30);
                  }else{
                    $his=GenGraph("$PATH[0]/$YEAR/$MONTH","$HOST","$MONTH","$YEAR",$PATH[1],$PATH[2],6);
                  }
                  shell_exec("/bin/rm -rf $PATH[0]/tmp/*");
		  $finish= date("G:i:s");
                  $arrS = explode(":",$start);
                  $arrF = explode(":",$finish);
                  echo date("i.s",mktime(0,$arrF[1]-$arrS[1],$arrF[2]-$arrS[2],0,0,0))." minutes<br/>";	
@ob_flush();
		  if ($MONTH == "Jan" || $MONTH == "Feb" || $MONTH == "Mar"|| $MONTH == "Apr"|| $MONTH == "May"|| $MONTH == "Jun"|| $MONTH == "Jul"|| $MONTH == "Aug"|| $MONTH == "Sep"|| $MONTH == "Oct"|| $MONTH == "Nov"|| $MONTH == "Dec"){


                // update history cpu and mem to sql
                $strSQL = "SELECT hid FROM history WHERE  serial='$PATH[3]' and year=$YEAR";
                $objQuery = mysqli_query($objConnect,$strSQL);
                $objResult = mysqli_fetch_array($objQuery);      
                if($objResult)
                { // update
                        $strSQL = "UPDATE history SET `$MONTH`='$his[0]' WHERE serial='$PATH[3]' and year=$YEAR and `type`='CPU'";
                        $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 
                        $strSQL = "UPDATE history SET `$MONTH`='$his[1]' WHERE serial='$PATH[3]' and year=$YEAR and `type`='MEM'";
                        $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 

                }else{ // insert


                  $strSQL = "INSERT INTO history (serial,year,`type`,`$MONTH`) VALUE ('$PATH[3]',$YEAR,'CPU','$his[0]')";
                  $objQuery1 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 
                  $strSQL = "INSERT INTO history (serial,year,`type`,`$MONTH`) VALUE ('$PATH[3]',$YEAR,'MEM','$his[1]')";
                  $objQuery2 = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 
                }
                }// no update
        
                
                // show image
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.utilize.png alt='cpu utilize' width=150 height=100/>";
                echo "</a>&nbsp";
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.aversar.png alt='cpu average' width=150 height=100/>";
                echo "</a>&nbsp";
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realcpu.png alt='real cpu' width=150 height=100/>";
                echo "</a>&nbsp";
                }
                if (file_exists("$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realmem.png")){
                echo "&nbsp;<a href=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realmem.png target=_blank>";
                echo "<img src=$PATH[0]/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.realmem.png alt='memory utilize' width=150 height=100/>";
                echo "</a>&nbsp";
                }

                  echo "<hr/>";
		}

		} // check format file

                break;
        default :
                echo "<font color=red>unknown format $CHK</font><br/>";
@ob_flush();
		unlink("customer/$CUS/tmp/$file");
                break;
        }

                  } // check dir != . ..
                } // while loop


 	mysqli_close($objConnect); 

	echo "<br/>=== End process ===<br/><hr/>";

?>
</div></div>
