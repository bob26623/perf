<?php
function io2g($PATH,$FILE,$HOST,$MONTH,$YEAR,$HEADX,$HEADY)
{
  // Standard inclusions   
  $WIDTH=950;
  $HEIGH=370;
if(file_exists("$PATH/$YEAR/$MONTH/iostat/$FILE")){
  $DataSet = new pData;
  $DataSet->importFromCSV("$PATH/$YEAR/$MONTH/iostat/$FILE",array("Delimiter"=>"\t","GotHeader"=>TRUE));
  $DataSet->setAxisName(0,"$HEADY");
  $DataSet->setAbscissa("DATE");
  $Test = new pImage($WIDTH,$HEIGH,$DataSet);
  $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
  $Test->drawFilledRectangle(0,0,$WIDTH,$HEIGH,$Settings); 
  $Test->drawGradientArea(0,0,$WIDTH,$HEIGH,DIRECTION_VERTICAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100));
  $Test->drawRectangle(0,0,$WIDTH-1,$HEIGH-1,array("R"=>100,"G"=>100,"B"=>100));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>11));
  $Test->drawText($WIDTH/2,35,"$HEADX at $MONTH-$YEAR on $HOST",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0)); 
  $Test->setGraphArea(60,50,$WIDTH-50,$HEIGH-50); 
  $ScaleSettings  = array("Mode"=>SCALE_MODE_START0,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"GridR"=>180,"GridG"=>180,"GridB"=>180,"LabelRotation"=>45);
  $Test->drawScale($ScaleSettings);
  $Test->Antialias = TRUE; 
  $Test->drawLegend($WIDTH-100,50,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL)); 
  $Test->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
  $Test->drawSplineChart(); 

  //$Test->Render("$PATH/$HOST.$MONTH.$YEAR.$FILE.png"); 
  $Test->Render("$PATH/$YEAR/$MONTH/iostat/$FILE.png"); 
}else{ // check utlize.out
  echo "<font color=red>no $FILE </font>";
}
}
function getHistory($type,$month,$serial,$AVG,$CUS,$HOST,$chkmon,$chkyear)
{
   $lastmonth=date("n",strtotime("1 $chkmon $chkyear"));
   $lastyear=$chkyear-1;
   $thisyear=$chkyear;
   $PATH=ChkPath($CUS,$HOST);
   $RET=array();
//   if (intval($lastmonth) == 12 ){
//          $limit=0;
//        }else{
//          $limit=intval($lastmonth);
//   }

   $strSQL = "SELECT * FROM history WHERE type='$type' and serial='$serial' and year='$lastyear'";
   $objQuery1 = mysqli_query($GLOBALS["objConnect"],$strSQL) or die ("Error Query [".$strSQL."]"); 
   if($objResult1 = mysqli_fetch_array($objQuery1))
      {
     //   for($i=1;$i<=12-$limit;$i++){
        for($i=1;$i<=12-intval($lastmonth);$i++){
	  if ($objResult1[$month[$i]] != "" ) {
	    array_push($RET,$objResult1[$month[$i]]);
	  }else{
	    array_push($RET,"N/A");
	  }
        }
      }else{
        for($i=1;$i<=12-intval($lastmonth);$i++){
	  array_push($RET,"N/A");
        }
      } // if loop cpu history last year
      $strSQL = "SELECT * FROM history WHERE type='$type' and serial='$serial' and year='$thisyear'";
      $objQuery1 = mysqli_query($GLOBALS["objConnect"],$strSQL) or die ("Error Query [".$strSQL."]"); 
      if ($objResult1 = mysqli_fetch_array($objQuery1))
      {
	for($i=13-intval($lastmonth);$i<=12;$i++){
	  if ($objResult1[$month[$i]] != "" ) {
	    array_push($RET,$objResult1[$month[$i]]);
	  }else{
            array_push($RET,"N/A");
          }
        }
      }else{
        for($i=1;$i<=intval($lastmonth);$i++){
	  array_push($RET,"N/A");
        }
      } // if loop cpu history this year

        if ($type=="MEM" && $AVG != 0){
	  array_push($RET,"$AVG/$PATH[2]");
        }else{
	  array_push($RET,$AVG);
        }
return $RET;
	
}
function showTable($type,$month,$serial,$AVG,$CUS,$HOST)
{
   $lastmonth=date("n",mktime(0, 0, 0, date("m")-1,1,date("Y")));
   $lastyear=date("Y",mktime(0, 0, 0, date("m"),1,date("Y")-1));
   $thisyear=date("Y");
   $PATH=ChkPath($CUS,$HOST);

   if ($type == "CPU"){
	$critical=80;
	$warning=60;
   }else{ // Memory check 80%,60% of physicall mem
	$critical=round($PATH[2]*0.8,0);
	$warning=round($PATH[2]*0.6,0);
   }
   if (intval($lastmonth) == 12 ){
          $limit=0;
        }else{
          $limit=intval($lastmonth);
   }
   $strSQL = "SELECT * FROM history WHERE type='$type' and serial='$serial' and year='$lastyear'";
   $objQuery1 = mysqli_query($GLOBALS["objConnect"],$strSQL) or die ("Error Query [".$strSQL."]"); 
   if($objResult1 = mysqli_fetch_array($objQuery1))
      {
        //for($i=1;$i<=12-intval($lastmonth);$i++){
        for($i=1;$i<=12-$limit;$i++){
	  $chk=$objResult1[$month[$i]]-$AVG;
	  echo "<td>";

  	  echo "<a href=\"JavaScript:doCallAjax('server','getserver.php?PATH=$PATH[0]&YEAR=$lastyear&MON=$month[$i]&SRV=$HOST','NONE');\">";
          echo $objResult1[$month[$i]]."</a>";
	  if (($chk >=30) || ($objResult1[$month[$i]] >= $critical)){
            echo "<img src=images/red.png align=top alt='utilize >= $critical or >= average 30' />";
          }elseif (($chk>=15) || ($objResult1[$month[$i]] >= $warning)){
            echo "<img src=images/yellow.png align=top alt='utilize >= $warning or >= average 15' />";
	  }

	  echo "</td>";
        }
      }else{
        for($i=1;$i<=12-$limit;$i++){
          echo "<td></td>";
        }
      } // if loop cpu history last year
      $strSQL = "SELECT * FROM history WHERE type='$type' and serial='$serial' and year='$thisyear'";
      $objQuery1 = mysqli_query($GLOBALS["objConnect"],$strSQL) or die ("Error Query [".$strSQL."]"); 
      if ($objResult1 = mysqli_fetch_array($objQuery1))
      {
        for($i=13-intval($lastmonth);$i<=12;$i++){
	  $chk=$objResult1[$month[$i]]-$AVG;
	  echo "<td>";
	  echo "<a href=\"JavaScript:doCallAjax('server','getserver.php?PATH=$PATH[0]&YEAR=$thisyear&MON=$month[$i]&SRV=$HOST','NONE');\">";
          echo $objResult1[$month[$i]]."</a>";
	  if (($chk >=30) || ($objResult1[$month[$i]] >= $critical)){
            echo "<img src=images/red.png align=top  alt='utilize >= $critical or >= average 30' />";
          }elseif (($chk>=15) || ($objResult1[$month[$i]] >= $warning)){
            echo "<img src=images/yellow.png align=top alt='utilize >= $warning or >= average 15' />";
          }
	  echo "</td>";
	  
          //echo "<td>".$objResult1[$month[$i]]."</td>";
        }
      }else{
	if (intval($lastmonth) != 12){
        for($i=1;$i<=intval($lastmonth);$i++){
          echo "<td></td>";
        }
	}
      } // if loop cpu history this year 

	if ($type=="MEM" && $AVG != 0){
	  echo "<td>$AVG/$PATH[2]</td>";	
	}else{
	  echo "<td>$AVG</td>";	
	}
    
}
function getAVG($type,$month,$serial,$lastmonth,$thisyear)
{
   $AVG=0;
   $countAVG=0;
   $lastyear=$thisyear-1;

   if (intval($lastmonth) == 12 ){
          $limit=0;
        }else{
          $limit=intval($lastmonth);
   }
   $strSQL = "SELECT * FROM history WHERE type='$type' and serial='$serial' and year='$lastyear'";
   $objQuery1 = mysqli_query($GLOBALS["objConnect"],$strSQL) or die ("Error Query [".$strSQL."]"); 
   if($objResult1 = mysqli_fetch_array($objQuery1))
      {
      for($i=1;$i<=12-$limit;$i++){
          if ($objResult1[$month[$i]] != "" && $objResult1[$month[$i]] != 0 ){
             $AVG+=$objResult1[$month[$i]];
             $countAVG+=1;
          }
      }
   } 
   $strSQL = "SELECT * FROM history WHERE type='$type' and serial='$serial' and year='$thisyear'";
   $objQuery1 = mysqli_query($GLOBALS["objConnect"],$strSQL) or die ("Error Query [".$strSQL."]"); 
   if ($objResult1 = mysqli_fetch_array($objQuery1))
      {
      for($i=13-intval($lastmonth);$i<=12;$i++){
          if ($objResult1[$month[$i]] != "" && $objResult1[$month[$i]] != 0 ){
               $AVG+=$objResult1[$month[$i]];
               $countAVG+=1;
          }   
      }
   }// if loop cpu history this year
   if ($countAVG != 0){
       return round($AVG/$countAVG,0);
   }else{
       return 0;
   }
}
function ChkPath($CUS,$HOST)
{
   
   //$GLOBALS["objConnect"] = mysqli_connect("localhost",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",NULL,"/cloudsql/smiling-gasket-170108:us-central1:csypart") or die("Error Connect to Database");
   //$objDB = mysqli_select_db("perf");
   //mysqli_query($GLOBALS["objConnect"],"SET NAMES UTF8");
   $strSQL="SELECT gname,cpu,memory,serial FROM servergroup,serverdetail WHERE cname='$CUS' and server='$HOST' and servergroup.gid=serverdetail.gid";
   $Query=mysqli_query($GLOBALS["objConnect"],$strSQL) or die ("Error Query [".$strSQL."]");
   $Result = mysqli_fetch_array($Query);
   if ($Result){
	if(!file_exists("customer/$CUS/")) { mkdir("customer/$CUS/"); }
	if(!file_exists("customer/$CUS/".$Result["gname"])) { mkdir("customer/$CUS/".$Result["gname"]); }
	if(!file_exists("customer/$CUS/".$Result["gname"]."/$HOST")) { mkdir("customer/$CUS/".$Result["gname"]."/$HOST"); }
	if(!file_exists("customer/$CUS/".$Result["gname"]."/$HOST/tmp")) { mkdir("customer/$CUS/".$Result["gname"]."/$HOST/tmp"); }
	
	$RET[0]="customer/$CUS/".$Result["gname"]."/$HOST";
	$RET[1]=$Result["cpu"];
	$RET[2]=$Result["memory"];
	$RET[3]=$Result["serial"];
	$RET[4]=$Result["gname"];
	return $RET;
	//return "customer/$CUS/".$Result["gname"]."/$HOST";
   }else{
	$RET[0]=0;
        $RET[1]=0;
	return $RET;
   }

}
function CallScriptCPU($PATH,$HOST,$MONTH,$YEAR,$SCRIPT,$MEM)
{
	
	switch($SCRIPT){
        case "realmem" :
          $DIR="sar-r";
          break;
        case "scan" :
          $DIR="sar-g";
          break;
        case "runq" :
          $DIR="sar-q";
          break;
	default :
          $DIR="sar-u";
	  break;
        }
        //echo "  - run script : $SCRIPT.sh : ";
        echo " $SCRIPT ";
@ob_flush();
        //$start= date("G:i:s");
        //echo $start;
        //$arrS = explode(":",$start);
        copy("scripts/$SCRIPT","$PATH/tmp/$DIR/$SCRIPT");
        copy("scripts/time1.txt","$PATH/tmp/$DIR/time1.txt");
        copy("scripts/time2.txt","$PATH/tmp/$DIR/time2.txt");
        copy("scripts/time3.txt","$PATH/tmp/$DIR/time3.txt");
        copy("scripts/time4.txt","$PATH/tmp/$DIR/time4.txt");
        copy("scripts/time5.txt","$PATH/tmp/$DIR/time5.txt");
        copy("scripts/time10.txt","$PATH/tmp/$DIR/time10.txt");
        copy("scripts/time15.txt","$PATH/tmp/$DIR/time15.txt");
        copy("scripts/time20.txt","$PATH/tmp/$DIR/time20.txt");
        copy("scripts/time30.txt","$PATH/tmp/$DIR/time30.txt");
	chmod("$PATH/tmp/$DIR/$SCRIPT",0750);
        echo shell_exec("cd ".getcwd()."/$PATH/tmp/$DIR;./$SCRIPT $MEM");
        //$finish= date("G:i:s");
        //echo " - $finish";
        //$arrF = explode(":",$finish);
        //echo " spend time = ".date("s",mktime(0,$arrF[1]-$arrS[1],$arrF[2]-$arrS[2],0,0,0))." seconds<br/>";
        //echo  date("i.s",mktime(0,$arrF[1]-$arrS[1],$arrF[2]-$arrS[2],0,0,0))." minutes<br/>";
        copy("$PATH/tmp/$DIR/$SCRIPT.out","$PATH/$YEAR/$MONTH/$HOST.$MONTH.$YEAR.$SCRIPT.out");

}
function GenGraph($PATH,$HOST,$MONTH,$YEAR,$CPU,$MEM,$SKIP)
{

  // Standard inclusions   
  //include("pChart2.1.3/class/pData.class.php");
  //include("pChart2.1.3/class/pDraw.class.php");
  //include("pChart2.1.3/class/pImage.class.php");
  $WIDTH=950;
  $HEIGH=370;


////////////////////////// utilize 

if(file_exists("$PATH/$HOST.$MONTH.$YEAR.utilize.out")){
  echo "utilize ,";
@ob_flush();
  // Dataset definition 
  $DataSet = new pData;
  $DataSet->importFromCSV("$PATH/$HOST.$MONTH.$YEAR.utilize.out",array("Delimiter"=>"\t","GotHeader"=>TRUE,"SkipColumns"=>array(4,6)));
  $DataSet->setAxisName(0,"CPU performance");
  
  /* Bind a data serie to the X axis */
  $DataSet->setAbscissa("DATE");
  //print_r($DataSet->getData());

  /* Normalize the data series to 100% */ 

  // Initialise the graph
  $Test = new pImage($WIDTH,$HEIGH,$DataSet);

  /* Draw the background */ 
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
 $Test->drawFilledRectangle(0,0,$WIDTH,$HEIGH,$Settings); 

 /* Overlay with a gradient */ 
 $Test->drawGradientArea(0,0,$WIDTH,$HEIGH,DIRECTION_VERTICAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100));


  /* Add a border to the picture */ 
 $Test->drawRectangle(0,0,$WIDTH-1,$HEIGH-1,array("R"=>100,"G"=>100,"B"=>100));

  
 /* Write the chart title */  
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>11));
 $Test->drawText($WIDTH/2,35,"CPU utilize by date at $MONTH-$YEAR on $HOST CPU $CPU Memory $MEM GB",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));


 /* Set the default font */ 
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0)); 

  /* Define the chart area */ 
 $Test->setGraphArea(60,50,$WIDTH-50,$HEIGH-50); 

 /* Draw the scale */ 
 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
 $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"GridR"=>180,"GridG"=>180,"GridB"=>180);
 $Test->drawScale($ScaleSettings);




/* Turn on Antialiasing */ 
 $Test->Antialias = TRUE; 

 /* Write the chart legend */ 
 $Test->drawLegend($WIDTH-100,50,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL)); 

 /* Enable shadow computing */ 
 $Test->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw the area chart */ 
  $DataSet->setSerieDrawable("%peak",FALSE); 

  $Test->drawStackedAreaChart(array("DrawPlot"=>FALSE,"DrawLine"=>TRUE,"LineSurrounding"=>-20,));

  $DataSet->setSerieDrawable("%usr",FALSE); 
  $DataSet->setSerieDrawable("%sys",FALSE); 
  $DataSet->setSerieDrawable("%wio",FALSE); 
  $DataSet->setSerieDrawable("%peak",TRUE); 
  //$DataSet->setSerieDrawable("%aver",TRUE); 

 //$Test->drawLineChart(); 
 $Test->drawSplineChart(); 

  /* Write some text */ 
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>4));
 $TextSettings = array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>10);
 $SUMMARY="Peak=".$DataSet->getMax("%peak")."\nAvg=".round($DataSet->getSerieAverage("%usr")+$DataSet->getSerieAverage("%sys")+$DataSet->getSerieAverage("%wio"),2);
 $Test->drawText(70,100,$SUMMARY,$TextSettings);


 /* Render the picture (choose the best way) */ 
 $Test->Render("$PATH/$HOST.$MONTH.$YEAR.utilize.png"); 

}else{ // check utlize.out
  echo "<font color=red>no utlize ,</font>";
@ob_flush();
}

if(file_exists("$PATH/$HOST.$MONTH.$YEAR.aversar.out")){
echo "aversar ,";
@ob_flush();
//////////////////////  aversar

  // Dataset definition 
  $DataSet = new pData;
  $DataSet->importFromCSV("$PATH/$HOST.$MONTH.$YEAR.aversar.out",array("Delimiter"=>"\t","GotHeader"=>TRUE,"SkipColumns"=>array(4,6)));
  $DataSet->setAxisName(0,"CPU performance");
  
  /* Bind a data serie to the X axis */
  $DataSet->setAbscissa("time");


  // Initialise the graph
  $Test = new pImage($WIDTH,$HEIGH,$DataSet);

  /* Draw the background */ 
 //$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
 //$Test->drawFilledRectangle(0,0,$WIDTH,$HEIGH,$Settings); 

 /* Overlay with a gradient */ 
 $Test->drawGradientArea(0,0,$WIDTH,$HEIGH,DIRECTION_VERTICAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100));


  /* Add a border to the picture */ 
 $Test->drawRectangle(0,0,$WIDTH-1,$HEIGH-1,array("R"=>100,"G"=>100,"B"=>100));
  
 /* Write the chart title */  
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>11));
 $Test->drawText($WIDTH/2,35,"CPU utilize by time at $MONTH-$YEAR on $HOST CPU $CPU Memory $MEM GB",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));

 /* Set the default font */ 
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0)); 

  /* Define the chart area */ 
 $Test->setGraphArea(60,50,$WIDTH-50,$HEIGH-50); 

 /* Draw the scale */ 
 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
 $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>$SKIP,"GridR"=>180,"GridG"=>180,"GridB"=>180);
 $Test->drawScale($ScaleSettings);

/* Turn on Antialiasing */ 
 $Test->Antialias = TRUE; 

 /* Write the chart legend */ 
 $Test->drawLegend($WIDTH-100,50,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL)); 

 /* Enable shadow computing */ 
 $Test->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw the area chart */ 
  $DataSet->setSerieDrawable("%peak",FALSE); 

  $Test->drawStackedAreaChart(array("DrawPlot"=>FALSE,"DrawLine"=>TRUE,"LineSurrounding"=>-20,));

  $DataSet->setSerieDrawable("%usr",FALSE); 
  $DataSet->setSerieDrawable("%sys",FALSE); 
  $DataSet->setSerieDrawable("%wio",FALSE); 
  $DataSet->setSerieDrawable("%peak",TRUE); 

 $Test->drawSplineChart(); 

  /* Write some text */ 
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>4));
 $TextSettings = array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>10);
 $SUMMARY="Peak=".$DataSet->getMax("%peak")."\nAvg=".round($DataSet->getSerieAverage("%usr")+$DataSet->getSerieAverage("%sys")+$DataSet->getSerieAverage("%wio"),2);
 $Test->drawText(70,100,$SUMMARY,$TextSettings);


 /* Render the picture (choose the best way) */ 
 $Test->Render("$PATH/$HOST.$MONTH.$YEAR.aversar.png"); 
}else{ // check utlize.out
  echo "<font color=red>no aversar ,</font>";
@ob_flush();
}
 
 // call realcpu realmem runq scanrate
if(file_exists("$PATH/$HOST.$MONTH.$YEAR.realcpu.out")){
  echo "realcpu ,";
@ob_flush();
  $RET[0]=round(real("$PATH","$HOST","$MONTH","$YEAR","realcpu",$WIDTH,$HEIGH,$CPU,$MEM,$SKIP),0);
}else{
  echo "<font color=red>no realcpu ,</font>";
@ob_flush();
  $RET[0]="N/A";
}
if(file_exists("$PATH/$HOST.$MONTH.$YEAR.realmem.out")){
  echo "realmem ,";
@ob_flush();
  $RET[1]=round(real("$PATH","$HOST","$MONTH","$YEAR","realmem",$WIDTH,$HEIGH,$CPU,$MEM,$SKIP),0);
}else{
  echo "<font color=red>no realmem ,</font>";
@ob_flush();
  $RET[1]="N/A";
	
}
if(file_exists("$PATH/$HOST.$MONTH.$YEAR.scan.out")){
  echo "scan ,";
@ob_flush();
  real("$PATH","$HOST","$MONTH","$YEAR","scan",$WIDTH,$HEIGH,$CPU,$MEM,$SKIP);
}else{
  echo "<font color=red>no scan ,</font>";
@ob_flush();
}
if(file_exists("$PATH/$HOST.$MONTH.$YEAR.runq.out")){
  echo "runq : ";
@ob_flush();
  real("$PATH","$HOST","$MONTH","$YEAR","runq",$WIDTH,$HEIGH,$CPU,$MEM,$SKIP);
}else{
  echo "<font color=red>no runq : </font>";
@ob_flush();
}

 return $RET;

}

function real($PATH,$HOST,$MONTH,$YEAR,$SCRIPT,$WIDTH,$HEIGH,$CPU,$MEM,$SKIP)
{

/////////////////////////// real cpu

  // Dataset definition 
  $DataSet = new pData;
  $DataSet->importFromCSV("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.out",array("Delimiter"=>"\t","GotHeader"=>TRUE));

  $DataSet->setAbscissa("time");
  $Test = new pImage($WIDTH,$HEIGH,$DataSet);
  $Test->drawFilledRectangle(60,40,$WIDTH,$HEIGH,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
  $Test->drawGradientArea(0,0,$WIDTH,$HEIGH,DIRECTION_VERTICAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100));
  $Test->drawRectangle(0,0,$WIDTH-1,$HEIGH-1,array("R"=>100,"G"=>100,"B"=>100));


  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>11));

  switch($SCRIPT){
        case "realcpu" :
	  $limit1=60;
	  $limit2=80;
	  $limit3=100;
          $DataSet->setAxisName(0,"CPU performance");
  	  $Test->drawText($WIDTH/2,35,"CPU utilize everyday at $MONTH-$YEAR on $HOST CPU $CPU Memory $MEM GB",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));
  	  $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
  	  $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>$SKIP,"GridR"=>180,"GridG"=>180,"GridB"=>180);
          break;
        case "realmem" :
	  $limit1=round($MEM*0.6,0);
	  $limit2=round($MEM*0.8,0);
	  $limit3=$MEM;
          $DataSet->setAxisName(0,"Memory usage");
  	  $Test->drawText($WIDTH/2,35,"Memory utilize everyday at $MONTH-$YEAR on $HOST CPU $CPU Memory $MEM GB",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));
  	  $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>$MEM));
  	  $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>$SKIP,"GridR"=>180,"GridG"=>180,"GridB"=>180);
          break;
        case "scan" :
          $DataSet->setAxisName(0,"Memory Scan Rate");
  	  $Test->drawText($WIDTH/2,35,"Memory Scan rate everyday at $MONTH-$YEAR on $HOST CPU $CPU Memory $MEM GB",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));
  	  $ScaleSettings  = array("Mode"=>SCALE_MODE_START0,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>$SKIP,"GridR"=>180,"GridG"=>180,"GridB"=>180);
          break;
        case "runq" :
          $DataSet->setAxisName(0,"Number of Queue on CPU");
  	  $Test->drawText($WIDTH/2,35,"Run Queue everyday at $MONTH-$YEAR on $HOST CPU $CPU Memory $MEM GB",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));
  	  $ScaleSettings  = array("Mode"=>SCALE_MODE_START0,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>$SKIP,"GridR"=>180,"GridG"=>180,"GridB"=>180);
          break;
        }
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0)); 
  $Test->setGraphArea(60,50,$WIDTH-50,$HEIGH-50); 


  $Test->drawScale($ScaleSettings);


  $Test->Antialias = TRUE; 
  $Test->drawLegend($WIDTH-50,10,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL,"FontSize"=>4)); 

  $Test->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
  $Test->drawLineChart(); 
$memavg=0;
if ($SCRIPT == "realcpu" or  $SCRIPT == "realmem")
{
  $Test->drawThresholdArea($limit1,$limit2,array("R"=>255,"G"=>255,"B"=>55,"Alpha"=>20,"Border"=>TRUE,"BorderTicks"=>2));
  $Test->drawThresholdArea($limit2,$limit3,array("R"=>255,"G"=>20,"B"=>20,"Alpha"=>20,"Border"=>TRUE,"BorderTicks"=>2));

/////////////////////// search for max position

  $LabelSettings = array("TitleMode"=>LABEL_TITLE_BACKGROUND,"DrawSerieColor"=>TRUE,"TitleR"=>255,"TitleG"=>255,"TitleB"=>255);
  $Data = $DataSet->getData();
  foreach($Data["Series"] as $SerieName => $Serie)
  {
    $Lines = preg_split("/\n/",$Serie["Description"]);
    foreach($Lines as $Key => $Value)
      {
       if ($Value != $DataSet->Data["Abscissa"]){
          $mypeak=$DataSet->getMax($Value);
	  $myavg[]=$DataSet->getSerieAverage($Value);
         if ($mypeak >= $limit2 ) 
          {
          $mykey=array_search($mypeak,$DataSet->getValues($Value));
          $Test->writeLabel($Value,$mykey,$LabelSettings);
         //printf("max = %s,%s series = %s key = %s \n",$DataSet->getMax($Value),$DataSet->getValueAt($Value,$mykey),$Value,$mykey);
          }
       }
     }
  }

///////////////////////////////////////
	// check cpu and mem average
 	$memavg=array_sum($myavg)/count($myavg);
}// check  realcpu realmem

 /* Render the picture (choose the best way) */ 
  $Test->Render("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.png"); 
return $memavg;

}
function cpudaily($PATH,$HOST,$MONTH,$YEAR,$SCRIPT,$WIDTH,$HEIGH,$CPU,$MEM)
{
  $SKIP=6;
  $DataSet = new pData;
  $DataSet->importFromCSV("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.out",array("Delimiter"=>"\t","GotHeader"=>TRUE));
  $DataSet->setAxisName(0,"CPU performance");
  $DataSet->setAbscissa("time");
  $Test = new pImage($WIDTH,$HEIGH,$DataSet);
  $Test->drawGradientArea(0,0,$WIDTH,$HEIGH,DIRECTION_VERTICAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100));
  $Test->drawRectangle(0,0,$WIDTH-1,$HEIGH-1,array("R"=>100,"G"=>100,"B"=>100));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>11));
  $Test->drawText($WIDTH/2,35,"CPU utilize by time at $MONTH-$YEAR on $HOST CPU $CPU Memory $MEM GB",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0));
  $Test->setGraphArea(60,50,$WIDTH-50,$HEIGH-50);
  $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
  $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>$SKIP,"GridR"=>180,"GridG"=>180,"GridB"=>180);
  $Test->drawScale($ScaleSettings);
  $Test->Antialias = TRUE; 
  $Test->drawLegend($WIDTH-100,50,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));
  $Test->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
  $DataSet->setSerieDrawable("%idle",FALSE); 
  $Test->drawStackedAreaChart(array("DrawPlot"=>FALSE,"DrawLine"=>TRUE,"LineSurrounding"=>-20,));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>4));
  $TextSettings = array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>10);
  $SUMMARY="Peak=".(100-$DataSet->getMin("%idle"))."\nAvg=".round($DataSet->getSerieAverage("%usr")+$DataSet->getSerieAverage("%sys")+$DataSet->getSerieAverage("%wio"),2);
  $Test->drawText(70,100,$SUMMARY,$TextSettings);

  $Test->Render("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.png");
}
function memdaily($PATH,$HOST,$MONTH,$YEAR,$SCRIPT,$WIDTH,$HEIGH,$CPU,$MEM)
{
  $SKIP=6;
  $DataSet = new pData;
  $DataSet->importFromCSV("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.out",array("Delimiter"=>"\t","GotHeader"=>TRUE));
  $DataSet->setAxisName(0,"Memory Usage");
  $DataSet->setAbscissa("time");
  $Test = new pImage($WIDTH,$HEIGH,$DataSet);
  $Test->drawGradientArea(0,0,$WIDTH,$HEIGH,DIRECTION_VERTICAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100));
  $Test->drawRectangle(0,0,$WIDTH-1,$HEIGH-1,array("R"=>100,"G"=>100,"B"=>100));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>11));
  $Test->drawText($WIDTH/2,35,"Memory Usage by time at $MONTH-$YEAR on $HOST CPU $CPU Memory $MEM GB",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0));
  $Test->setGraphArea(60,50,$WIDTH-50,$HEIGH-50);
  $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>$MEM));
  $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>$SKIP,"GridR"=>180,"GridG"=>180,"GridB"=>180);
  $Test->drawScale($ScaleSettings);
  $Test->Antialias = TRUE; 
  $Test->drawLegend($WIDTH-100,50,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));
  $Test->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
  $Test->drawlineChart();
  //$Test->drawStackedAreaChart(array("DrawPlot"=>FALSE,"DrawLine"=>TRUE,"LineSurrounding"=>-20,));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>4));
  $TextSettings = array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>10);
  $SUMMARY="Peak=".$DataSet->getMax("MemUsage")."\nAvg=".round($DataSet->getSerieAverage("MemUsage"),2);
  $Test->drawText(70,100,$SUMMARY,$TextSettings);

  $Test->Render("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.png");
}
function iodaily($PATH,$HOST,$MONTH,$YEAR,$SCRIPT,$WIDTH,$HEIGH,$CPU,$MEM)
{
  if(file_exists("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.out")){
  $SKIP=6;
  $DataSet = new pData;
  $DataSet->importFromCSV("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.out",array("Delimiter"=>"\t","GotHeader"=>TRUE));
  if ($SCRIPT == "asvc" || $SCRIPT == "wsvc"){
    $DataSet->setAxisName(0,"time millisecond");
  }else{
    $DataSet->setAxisName(0,"Megabyte");
  }
  $DataSet->setAbscissa("time");
  $Test = new pImage($WIDTH,$HEIGH,$DataSet);
  $Test->drawGradientArea(0,0,$WIDTH,$HEIGH,DIRECTION_VERTICAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100));
  $Test->drawRectangle(0,0,$WIDTH-1,$HEIGH-1,array("R"=>100,"G"=>100,"B"=>100));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>11));
  $Test->drawText($WIDTH/2,35,"I/O utilize $SCRIPT by time at $MONTH-$YEAR on $HOST",array("FontSize"=>20,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));
  $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0));
  $Test->setGraphArea(60,50,$WIDTH-50,$HEIGH-50);
  $ScaleSettings  = array("Mode"=>SCALE_MODE_START0,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"GridR"=>180,"GridG"=>180,"GridB"=>180,"LabelRotation"=>45,"LabelSkip"=>$SKIP);
  $Test->drawScale($ScaleSettings);
  $Test->Antialias = TRUE;
  $Test->drawLegend($WIDTH-100,50,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));
  $Test->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
  $Test->drawSplineChart();

  $Test->Render("$PATH/$HOST.$MONTH.$YEAR.$SCRIPT.png");
}else{ // check file.out
  echo "<font color=red>no $SCRIPT </font>";
}
}

 ?>
