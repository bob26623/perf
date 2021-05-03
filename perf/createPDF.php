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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<head>
<title>Performance Reporting Tool</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
</head>
<body  id="top">
<?php

include('script.php');
require('./fpdf17/fpdf.php');

class PDF extends FPDF
{
function Header()
{
        //global $title;

	//Logo
        $this->Image('./images/g-able.gif',10,8);
        $this->Image('./images/customer/'.$_GET["CUS"].'.jpg',250,8);

        //Arial bold 15
        $this->SetFont('Arial','B',15);
        $this->Ln(18);
}

function Footer()
{
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Text color in gray
        $this->SetTextColor(128);
        //Page number
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
function ShowImage($link,$hostname,$pathfile)
{
	$this->AddPage();
	$this->SetLink($link);
        //Arial 12
        $this->SetFont('Arial','',14);
        //Background color
        $this->SetFillColor(153,0,102);
        //Title
        $this->SetTextColor(255,255,255);
        $this->Cell(0,6,"Server Performance Statistic",0,1,'C',true);

        $this->SetFillColor(255,204,0);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,6,"$hostname",0,1,'C',true);

        //Line break
        $this->Ln(4);

	if (file_exists($pathfile.".png")) {
	$this->Image($pathfile.".png",20,50);
	}else{
	$this->SetFont('Arial','B',25);
	//$this->Text(130,90,"There is no data. Please upload sar file to anaylyze");

	$w=$this->GetStringWidth("There is no data. Please upload sar file to anaylyze")+6;
        $this->SetX((297-$w)/2);
        $this->Text(((297-$w)/2),105,"There is no data. Please upload sar file to anaylyze");
	}
	if (file_exists($pathfile.".cmt")) {
         $objFopen = fopen($pathfile.".cmt", 'r');
           if ($objFopen) {
                while (!feof($objFopen)) {
                        $file.= fgets($objFopen, 4096);
                }
                fclose($objFopen);
           }
	$file=iconv('utf-8', 'tis-620',$file);
	$this->SetFont('Tahoma','',14);
         $this->Text(45,169,$file);
        }
	//return to page2
	   $this->Ln(120);
	   $back=$this->AddLink();
	   $this->SetFont('Arial','U',14);
	   $this->Cell(20,6,"Index",'',0,'C',false,$back);
	   $this->SetFont('');
	   
	$this->SetLink($back,0,2);

}
function FancyTable($header,$data,$type)
{
	global $link;

	$this->Ln(5);
	$this->Write(10,$type);
	$this->Ln(10);
	//Colors, line width and bold font
	$this->SetFillColor(255,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(128,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B');
	//Header
	$w=array(30,19,19,19,19,19,19,19,19,19,19,19,19,19);
	//$w=array(30,20,20,20,20,20,20,20,20,20,20,20,20,20);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
	$this->Ln();
	//Color and font restoration
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetFont('');
	//Data
	//$fill=false;
	$count=0;
	$critical=80;
        $warning=60;
	foreach($data as $row)
	{
	   if ($type == "CPU") {$link[$count]=$this->AddLink();}
	   $this->SetFont('','U');
	   $this->Cell($w[0],6,$row[0],'LR',0,'C',$fill,$link[$count]);
	   $this->SetFont('');
	   for($i=1;$i<=12;$i++)
		{
		if ($row[$i] == "N/A" || $type =="Memory" ){
		  $chk=0;
		}else{
		  $chk=$row[$i] - $row[13];
		} // if check N/A
		  if ( $chk >=30 || ($row[13] >= $critical && $type=="CPU") ){
			$this->SetFillColor(255,51,0); 
			$fill=true;
		  }elseif ( $chk >=15 || ($row[13] >= $warning && $type=="CPU")) {
			$this->SetFillColor(255,255,0); 
			$fill=true;
		  }elseif ( $chk <= -15 && $type=="CPU" ){
			$this->SetFillColor(0,255,0); 
			$fill=true;
		  }
		  $this->Cell($w[$i],6,$row[$i],'LR',0,'C',true);
	  if (($count%2)==0){
		$this->SetFillColor(255,255,255);
	  }else{
	  	$this->SetFillColor(224,235,255);
	  }
		} // for

	  $this->Cell($w[13],6,$row[13],'LR',0,'C',true);
		$this->Ln();
		$fill=!$fill;
		$count++;
	}
	$this->Cell(array_sum($w),0,'','T');
	//return $link;

	if ($type=="CPU") {
	if(file_exists("customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_GET["FILE"].".cmt")){
           $objFopen = fopen("customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_GET["FILE"].".cmt", 'r');
           if ($objFopen) {
                while (!feof($objFopen)) {
                        $file.= fgets($objFopen, 4096);
                }
                fclose($objFopen);
           }

           $file=iconv('utf-8', 'tis-620',$file);
           $this->SetFont('Tahoma','',14);
           $this->Text(10,170,"Note : ".$file);
        }
	//$this->SetFillColor(255,51,0); 
	//$this->Rect(20,170,20,6,'DF');
	//$this->Text(45,174,"Critical : utilize more than 80% or more than average 30%");
	//$this->SetFillColor(255,255,0);
	//$this->Rect(20,178,20,6,'DF');
	//$this->Text(45,182,"Warning : utilize more than 75% or more than average 15%");
	//$this->SetFillColor(0,255,0);
	//$this->Rect(20,186,20,6,'DF');
	//$this->Text(45,190,"Notice : utilize less than average 15%");

	$this->SetFillColor(255,51,0);
        $this->Rect(20,186,10,6,'DF');
        $this->Text(35,190,"Critical : > 85% or > average 30%");
        $this->SetFillColor(255,255,0);
        $this->Rect(115,186,10,6,'DF');
        $this->Text(130,190,"Warning : > 75% or > average 15%");
        $this->SetFillColor(0,255,0);
        $this->Rect(210,186,10,6,'DF');
        $this->Text(225,190,"Notice : < average 15%");

	}
}

}

$GID=$_GET["GID"];
$CUS=$_GET["CUS"];
$GRP=$_GET["GRP"];
$TIME = explode(".",$_GET["FILE"]);

echo "<div class=\"wrapper col3\"></div>";

echo "<div class=\"wrapper col5\">"; 
echo "<div id=\"container\">";

$pdf=new PDF('L','mm','A4');
$pdf->SetAuthor('screen');
// add font thai
$pdf->AddFont('Tahoma','','tahoma.php');
$pdf->AddFont('Tahoma','b','tahomab.php');

	/// first page
        $pdf->AddPage();
        $pdf->SetFont('Arial','',14);
        //Background color
        $pdf->SetFillColor(153,0,102);
        //Title
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(0,6,"System Utilization",0,1,'C',true);

        $pdf->SetFillColor(255,204,0);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,6,"$GRP",0,1,'C',true);

        $pdf->SetFont('Arial','',25);
        $w=$pdf->GetStringWidth("System Utilization Report")+6;
        $pdf->SetX((297-$w)/2);
        $pdf->Text(((297-$w)/2),105,"System Utilization Report");
        $pdf->Text(((297-$w)/2),135,"$TIME[0] $TIME[1]");
        //$pdf->Ln(4);

	// index page
	$pdf->AddPage();
        $pdf->SetFont('Arial','',14);
        //Background color
        $pdf->SetFillColor(153,0,102);
        //Title
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(0,6,"System Utilization Index",0,1,'C',true);

        $pdf->SetFillColor(255,204,0);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,6,"$GRP",0,1,'C',true);

        // table
	$header= array();
	array_push($header,'Server');
	$j=1;
	$mytime=strtotime("1 $TIME[0] $TIME[1]");
	for($i=11;$i>=0;$i--){
	  $Hmonth[$j]=date("M",strtotime("-$i month",$mytime));
          array_push($header,$Hmonth[$j]);
	  $j++;
        }
	echo "<br/>";
        array_push($header,'Avg');
	
//$header=array('Server','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Avg'); 

$resultData = array(); 
$strSQL = "SELECT server,serial FROM serverdetail WHERE  gid=$GID ORDER BY server";
$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
while ($objResult = mysqli_fetch_array($objQuery))
{
  $result = array(); 
  array_push($result,$objResult["server"]);
  $AVG=getAVG("CPU",$Hmonth,$objResult["serial"],date("n",$mytime),$TIME[1]);

  $tmp=getHistory("CPU",$Hmonth,$objResult["serial"],$AVG,$CUS,$objResult["server"],$TIME[0],$TIME[1]);
  foreach($tmp as $d)
  {
   array_push($result,$d);
  }
  array_push($resultData,$result); 
}
$pdf->FancyTable($header,$resultData,"CPU");
$pdf->Ln(4);

if (count($resultData) >= 6) {
                // index page
        $pdf->AddPage();
        $pdf->SetFont('Arial','',14);
        //Background color
        $pdf->SetFillColor(153,0,102);
        //Title
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(0,6,"System Utilization Index",0,1,'C',true);

        $pdf->SetFillColor(255,204,0);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,6,"$GRP",0,1,'C',true);
}

$resultData = array();
$strSQL = "SELECT server,serial FROM serverdetail WHERE  gid=$GID ORDER BY server";
$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
while ($objResult = mysqli_fetch_array($objQuery))
{
  $result = array();
  array_push($result,$objResult["server"]);
  $AVG=getAVG("MEM",$Hmonth,$objResult["serial"],date("n",$mytime),$TIME[1]);

  $tmp=getHistory("MEM",$Hmonth,$objResult["serial"],$AVG,$CUS,$objResult["server"],$TIME[0],$TIME[1]);
  foreach($tmp as $d)
  {
   array_push($result,$d);
  }
  array_push($resultData,$result);
}
$pdf->FancyTable($header,$resultData,"Memory");
$pdf->Ln(4);

$scount=0;
$strSQL = "SELECT server,serial FROM serverdetail WHERE  gid=$GID ORDER BY server";
$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
while ($objResult = mysqli_fetch_array($objQuery))
{
  $host=$objResult["server"];
  $PATHF="./customer/$CUS/$GRP/$host/$TIME[1]/$TIME[0]/$host.$TIME[0].$TIME[1].";
  $pdf->ShowImage($link[$scount],$host,$PATHF.'utilize');
  $pdf->ShowImage(0,$host,$PATHF.'aversar');
  $pdf->ShowImage(0,$host,$PATHF.'realcpu');
  $pdf->ShowImage(0,$host,$PATHF.'realmem');
  if (file_exists("{$PATHF}runq.png")) {
  $pdf->ShowImage(0,$host,$PATHF.'runq');
  }
  if (file_exists("{$PATHF}scan.png")) {
  $pdf->ShowImage(0,$host,$PATHF.'scan');
  }
  $scount++;
}

if (!file_exists("./customer/pdf")){
  mkdir("./customer/pdf"); 
}
$pdf->Output("./customer/pdf/$CUS.$GRP.$TIME[0].$TIME[1].pdf","F");
echo "<br/><h1>Month report group $GRP on $TIME[0] $TIME[1] </h1>";
echo "<br/><a href=./customer/pdf/$CUS.$GRP.$TIME[0].$TIME[1].pdf target=_blank> >> Download << </a>";
echo "</div></div>";
echo "<div class=clear></div>";
?>
</body>
</html>
