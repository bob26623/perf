<?php
  // Standard inclusions   
  include("../class/pData.class.php");
  include("../class/pDraw.class.php");
  include("../class/pImage.class.php");

  // Dataset definition 
  $DataSet = new pData;
  $DataSet->importFromCSV("./CO2.csv",array("Delimiter"=>"\t","GotHeader"=>TRUE,"SkipColumns"=>array(5,6,7,8,9,10,11,12,13)));
  //$DataSet->importFromCSV("./CO2.csv",array("Delimiter"=>"\t","GotHeader"=>TRUE));
  $DataSet->setAxisName(0,"CO2 concentrations");
  
  /* Bind a data serie to the X axis */
  $DataSet->setSerieDescription("Labels","Year");
  $DataSet->setAbscissa("Year");

  // Initialise the graph
  $Test = new pImage(700,230,$DataSet);

  /* Draw the background */ 
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
 $Test->drawFilledRectangle(0,0,700,230,$Settings); 

 /* Overlay with a gradient */ 
 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $Test->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings); 
 $Test->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80)); 


  /* Add a border to the picture */ 
 $Test->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0)); 
  
 /* Write the chart title */  
 $Test->setFontProperties(array("FontName"=>"../fonts/Forgotte.ttf","FontSize"=>8,"R"=>255,"G"=>255,"B"=>255)); 
 $Test->drawText(10,16,"CO2 concentrations at Mauna Loa",array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMLEFT)); 

 /* Set the default font */ 
 $Test->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>6,"R"=>0,"G"=>0,"B"=>0)); 

  /* Define the chart area */ 
 $Test->setGraphArea(60,40,650,200); 

 /* Draw the scale */ 
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE); 
 $Test->drawScale($scaleSettings); 


/* Turn on Antialiasing */ 
 $Test->Antialias = TRUE; 

 /* Enable shadow computing */ 
 $Test->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10)); 

 /* Draw the line chart */ 
 $Test->drawLineChart(); 
 $Test->drawPlotChart(array("DisplayValues"=>FALSE,"PlotSize"=>0.2,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)); 

 /* Write the chart legend */ 
 $Test->drawLegend(590,9,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>255,"FontG"=>255,"FontB"=>255)); 

 /* Render the picture (choose the best way) */ 
 $Test->autoOutput("mygraph.png"); 


 ?>

