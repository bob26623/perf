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
        $this->Cell(0,6,"Server I/O Performance",0,1,'C',true);
        $this->SetFillColor(255,204,0);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,6,"$hostname",0,1,'C',true);
        $this->Ln(4);
        $this->Image($pathfile.".png",20,50);
     }
}
} // class

$srcfile=$_GET["PATH"]."/".$_GET["SRV"].".".$_GET["MON"].".".$_GET["YEAR"];
$pdf=new PDF('L','mm','A4');
$pdf->SetAuthor('screen');
$pdf->AddFont('Tahoma','','tahoma.php');
$pdf->AddFont('Tahoma','b','tahomab.php');
$pdf->AddPage();
$pdf->SetFont('Arial','',14);
$pdf->SetFillColor(153,0,102);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(0,6,"I/O Utilization on ".$_GET["SRV"],0,1,'C',true);
$pdf->SetFillColor(255,204,0);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',25);
$pdf->SetX(120);
$pdf->Text(120,105,"I/O Utilization Report");
$pdf->Text(120,135,$_GET["MON"]." ".$_GET["YEAR"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<head>
<title>Performance Reporting Tool</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<link rel="stylesheet" href="styles/tables.css" type="text/css" />

<body>
        <div class="wrapper col3">
	<div align=center>
<?php
        if ( $_GET["TYP"] == "CTRL" ) {
          $FILENAME=$_GET["PATH"]."/".$_GET["SRV"].".".$_GET["MON"].".".$_GET["YEAR"].".";
	  $pdf->ShowImage(0,$_GET["SRV"],$srcfile.".asvc.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$srcfile.".wsvc.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$srcfile.".aread.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$srcfile.".awrite.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$srcfile.".pread.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$srcfile.".pwrite.out");
	  $pdf->Output("$srcfile.iostat.pdf","F");
	  echo "<a href=$srcfile.iostat.pdf><img src=images/pdf.png heigh=50 width=40 /></a>";
        }else{
          $FILENAME=$_GET["PATH"]."/".$_GET["SRV"].".".$_GET["MON"].".".$_GET["YEAR"].".".$_GET["CTRL"]."_";
	  $pdf->ShowImage(0,$_GET["SRV"],$FILENAME."asvc.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$FILENAME."pasvc.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$FILENAME."wsvc.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$FILENAME."pwsvc.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$FILENAME."read.out");
	  $pdf->ShowImage(0,$_GET["SRV"],$FILENAME."write.out");
	  $pdf->Output("$FILENAME.iostat.pdf","F");
	  echo "<a href=$FILENAME.iostat.pdf><img src=images/pdf.png heigh=50 width=40 /></a>";
        } // show controller

	echo "<font size=5 color=white> I/O ".$_GET["CTRL"]." ".$_GET["SRV"]." on ".$_GET["MON"]." ".$_GET["YEAR"]."</font></div>";
        echo "<div id=\"gallery\">";
        echo "<ul>";
        if ( $_GET["TYP"] == "CTRL" ) {
?>
         <li class="placeholder" style="background-image:url(<?=$FILENAME?>asvc.out.png);">Image Holder</li>
         <li><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>wsvc.out.png" alt="">
             <span><img src="<?=$FILENAME?>wsvc.out.png" width="950" height="370" alt="wait service time"></span></a></li>
         <li><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>aread.out.png" alt="">
             <span><img src="<?=$FILENAME?>aread.out.png" width="950" height="370" alt="average read"></span></a></li>
         <li><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>awrite.out.png" alt="">
             <span><img src="<?=$FILENAME?>awrite.out.png" width="950" height="370" alt="average write"></span></a></li>
         <li><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>pread.out.png" alt="">
             <span><img src="<?=$FILENAME?>pread.out.png" width="950" height="370" alt="peak read"></span></a></li>
         <li class="last" ><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>pwrite.out.png" alt="">
             <span><img src="<?=$FILENAME?>pwrite.out.png" width="950" height="370" alt="peak write"></span></a></li>
        
<?php
        }else{
?>
        <li class="placeholder" style="background-image:url(<?=$FILENAME?>asvc.out.png);">Image Holder</li>
         <li><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>wsvc.out.png" alt="">
             <span><img src="<?=$FILENAME?>wsvc.out.png" width="950" height="370" alt="wait service time"></span></a></li>
         <li><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>pasvc.out.png" alt="">
             <span><img src="<?=$FILENAME?>pasvc.out.png" width="950" height="370" alt="pead service time "></span></a></li>
         <li class="swap" ><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>pwsvc.out.png" alt="">
             <span><img src="<?=$FILENAME?>pwsvc.out.png" width="950" height="370" alt="peak wait service time"></span></a></li>
	 <li class="swap" ><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>read.out.png" alt="">
             <span><img src="<?=$FILENAME?>read.out.png" width="950" height="370" alt="read data troughput"></span></a></li>
         <li class="last" ><a class="swap" href="javascript:void(0)"><img src="<?=$FILENAME?>write.out.png" alt="">
             <span><img src="<?=$FILENAME?>write.out.png" width="950" height="370" alt="write data throughput"></span></a></li>

<?php
        }

?>
        </ul>
        </div>

        <div id="container">

        <font color=yellow>Source Data</font> : 
        <a href=<?=$FILENAME?>asvc.out>Avg_Svc</a> 
        <a href=<?=$FILENAME?>wsvc.out>Wait_Svc</a> 
<?php
        if ( $_GET["TYP"] == "CTRL" ) {
?>
          <a href=<?=$FILENAME?>aread.out>Avg_Read</a> 
          <a href=<?=$FILENAME?>pread.out>Peak_Read</a> 
          <a href=<?=$FILENAME?>awrite.out>Avg_Write</a> 
          <a href=<?=$FILENAME?>pwrite.out>Peak_Write</a> 
<?php      }else{ ?>
          <a href=<?=$FILENAME?>pasvc.out>Peak_Avg_Svc</a> 
          <a href=<?=$FILENAME?>pwsvc.out>Peak_Wait_Svc</a> 
	  <a href=<?=$FILENAME?>read.out>Read_Data</a>
 	  <a href=<?=$FILENAME?>write.out>Write_Data</a> 

<?php
        }
?>
        </div>
        </div>
</body>
</html>
