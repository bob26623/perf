<?php
  // Standard inclusions   
  include("pChart2.1.3/class/pData.class.php");
  include("pChart2.1.3/class/pDraw.class.php");
  include("pChart2.1.3/class/pImage.class.php");

  // Dataset definition 
  $DataSet = new pData;
  $DataSet->importFromCSV("./myperf.out",array("Delimiter"=>"\t","GotHeader"=>TRUE,"SkipColumns"=>array(4,6)));
  $DataSet->setAxisName(0,"CPU performance");
  
  /* Bind a data serie to the X axis */
  $DataSet->setAbscissa("time");
  //print_r($DataSet->getData());


  // Initialise the graph
  $Test = new pImage(800,350,$DataSet);

  /* Draw the background */ 
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
 $Test->drawFilledRectangle(0,0,800,300,$Settings); 

 /* Overlay with a gradient */ 
 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $Test->drawGradientArea(0,0,800,300,DIRECTION_VERTICAL,$Settings); 
 $Test->drawGradientArea(0,0,800,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80)); 


  /* Add a border to the picture */ 
 $Test->drawRectangle(0,0,800,300,array("R"=>0,"G"=>0,"B"=>0)); 
  
 /* Write the chart title */  
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>10,"R"=>255,"G"=>255,"B"=>255)); 
 $Test->drawText(80,12,"CPU utilize by time",array("FontSize"=>15,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE)); 

 /* Set the default font */ 
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0)); 

  /* Define the chart area */ 
 $Test->setGraphArea(60,50,750,250); 

 /* Draw the scale */ 
 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
 $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>6);
 $Test->drawScale($ScaleSettings);

/* Turn on Antialiasing */ 
 $Test->Antialias = TRUE; 

 /* Write the chart legend */ 
 $Test->drawLegend(580,9,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>255,"FontG"=>255,"FontB"=>255)); 

 /* Enable shadow computing */ 
 $Test->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10)); 

 /* Draw the area chart */ 
  $DataSet->setSerieDrawable("%peak",FALSE); 
//  $DataSet->setSerieDrawable("%aver",FALSE); 

  $Test->drawStackedAreaChart(array("DrawPlot"=>FALSE,"DrawLine"=>TRUE,"LineSurrounding"=>-20,));

  $DataSet->setSerieDrawable("%usr",FALSE); 
  $DataSet->setSerieDrawable("%sys",FALSE); 
  $DataSet->setSerieDrawable("%wio",FALSE); 
  $DataSet->setSerieDrawable("%peak",TRUE); 
//  $DataSet->setSerieDrawable("%aver",TRUE); 

 $Test->drawLineChart(); 
 //$Test->drawPlotChart(array("DisplayValues"=>FALSE,"PlotSize"=>0,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)); 

  /* Write some text */ 
 $Test->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/verdana.ttf","FontSize"=>4));
 $TextSettings = array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>10);
 $SUMMARY="Peak=".$DataSet->getMax("%peak")."\nAvg=".round($DataSet->getSerieAverage("%peak"),2);
// $Test->drawText(70,100,"Peak=$EAK") Avg=$DataSet->getSerieAverage("AVG")",$TextSettings);
 $Test->drawText(70,100,$SUMMARY,$TextSettings);


 /* Render the picture (choose the best way) */ 
 $Test->autoOutput("mygraph.png"); 


 ?>

