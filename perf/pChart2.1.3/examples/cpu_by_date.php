<?php
  // Standard inclusions   
  include("../class/pData.class.php");
  include("../class/pDraw.class.php");
  include("../class/pImage.class.php");

  // Dataset definition 
  $DataSet = new pData;
  //$DataSet->importFromCSV("./myperf.out",array("Delimiter"=>"\t","GotHeader"=>TRUE,"SkipColumns"=>array(5,6,7,8,9,10,11,12,13)));
  $DataSet->importFromCSV("./myperf.out",array("Delimiter"=>"\t","GotHeader"=>TRUE,"SkipColumns"=>array(4,6)));
  $DataSet->setAxisName(0,"CPU performance");
  
  /* Bind a data serie to the X axis */
  $DataSet->setAbscissa("DATE");
  //print_r($DataSet->getData());

  /* Normalize the data series to 100% */ 
 //$DataSet->normalize(100,"%"); 

  // Initialise the graph
  $Test = new pImage(950,370,$DataSet);

  /* Draw the background */ 
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
 $Test->drawFilledRectangle(0,0,950,320,$Settings); 

 /* Overlay with a gradient */ 
 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $Test->drawGradientArea(0,0,950,320,DIRECTION_VERTICAL,$Settings); 
 $Test->drawGradientArea(0,0,950,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80)); 


  /* Add a border to the picture */ 
 $Test->drawRectangle(0,0,950,320,array("R"=>0,"G"=>0,"B"=>0)); 
  
 /* Write the chart title */  
 $Test->setFontProperties(array("FontName"=>"../fonts/Forgotte.ttf","FontSize"=>10,"R"=>255,"G"=>255,"B"=>255)); 
 $Test->drawText(80,12,"CPU utilize by date",array("FontSize"=>15,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE)); 

 /* Set the default font */ 
 $Test->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0)); 

  /* Define the chart area */ 
 $Test->setGraphArea(60,50,900,270); 

 /* Draw the scale */ 
 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
 $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45);
 $Test->drawScale($ScaleSettings);


 //$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE); 
 //$Test->drawScale($scaleSettings); 


/* Turn on Antialiasing */ 
 $Test->Antialias = TRUE; 

 /* Write the chart legend */ 
 $Test->drawLegend(770,9,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>255,"FontG"=>255,"FontB"=>255)); 

 /* Enable shadow computing */ 
 $Test->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10)); 

 /* Draw the area chart */ 
  $DataSet->setSerieDrawable("%peak",FALSE); 
  //$DataSet->setSerieDrawable("%aver",FALSE); 

  $Test->drawStackedAreaChart(array("DrawPlot"=>FALSE,"DrawLine"=>TRUE,"LineSurrounding"=>-20,));

  $DataSet->setSerieDrawable("%usr",FALSE); 
  $DataSet->setSerieDrawable("%sys",FALSE); 
  $DataSet->setSerieDrawable("%wio",FALSE); 
  $DataSet->setSerieDrawable("%peak",TRUE); 
  //$DataSet->setSerieDrawable("%aver",TRUE); 

 $Test->drawLineChart(); 
 //$Test->drawPlotChart(array("DisplayValues"=>FALSE,"PlotSize"=>0.2,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)); 

  /* Write some text */ 
 $Test->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>4));
 $TextSettings = array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>10);
 $SUMMARY="Peak=".$DataSet->getMax("%peak")."\nAvg=".round($DataSet->getSerieAverage("%peak"),2);
// $Test->drawText(70,100,"Peak=$EAK") Avg=$DataSet->getSerieAverage("AVG")",$TextSettings);
 $Test->drawText(70,100,$SUMMARY,$TextSettings);


 /* Render the picture (choose the best way) */ 
 $Test->autoOutput("mygraph.png"); 


 ?>

