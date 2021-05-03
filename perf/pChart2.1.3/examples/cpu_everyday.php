<?php
  // Standard inclusions   
  include("../class/pData.class.php");
  include("../class/pDraw.class.php");
  include("../class/pImage.class.php");

  // Dataset definition 
  $DataSet = new pData;
  $DataSet->importFromCSV("./realcpu.txt",array("Delimiter"=>"\t","GotHeader"=>TRUE));
  $DataSet->setAxisName(0,"CPU performance");
  
  /* Bind a data serie to the X axis */
  $DataSet->setAbscissa("time");
  //print_r($DataSet->getData());


  // Initialise the graph
  $Test = new pImage(1100,450,$DataSet);

  /* Draw the background */ 
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107); 
 $Test->drawFilledRectangle(0,0,1200,600,$Settings); 

 /* Overlay with a gradient */ 
 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $Test->drawGradientArea(0,0,1100,450,DIRECTION_VERTICAL,$Settings); 
 $Test->drawGradientArea(0,0,1100,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80)); 


  /* Add a border to the picture */ 
 $Test->drawRectangle(0,0,1100,450,array("R"=>0,"G"=>0,"B"=>0)); 
  
 /* Write the chart title */  
 $Test->setFontProperties(array("FontName"=>"../fonts/Forgotte.ttf","FontSize"=>10,"R"=>255,"G"=>255,"B"=>255)); 
 $Test->drawText(80,12,"CPU utilize",array("FontSize"=>15,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE)); 

 /* Set the default font */ 
 $Test->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0)); 

  /* Define the chart area */ 
 $Test->setGraphArea(60,50,1000,400); 

 /* Draw the scale */ 
 $AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
 $ScaleSettings  = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries,"Floating"=>TRUE,"CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"DrawArrows"=>TRUE,"ArrowSize"=>6,"XMargin"=>0,"YMargin"=>0,"LabelRotation"=>45,"LabelSkip"=>6);
 $Test->drawScale($ScaleSettings);

/* Turn on Antialiasing */ 
 $Test->Antialias = TRUE; 

 /* Write the chart legend */ 
 $Test->drawLegend(1020,50,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL,"FontR"=>255,"FontG"=>255,"FontB"=>255)); 

 /* Enable shadow computing */ 
 $Test->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10)); 

 $Test->drawLineChart(); 
 $Test->drawThresholdArea(70,90,array("R"=>255,"G"=>255,"B"=>55,"Alpha"=>20,"Border"=>TRUE,"BorderTicks"=>2));
 $Test->drawThresholdArea(90,100,array("R"=>255,"G"=>20,"B"=>20,"Alpha"=>20,"Border"=>TRUE,"BorderTicks"=>2));

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
	 if ($mypeak >= 70 ) 
	  {
	  $mykey=array_search($mypeak,$DataSet->getValues($Value));
 	  $Test->writeLabel($Value,$mykey,$LabelSettings);
	 //printf("max = %s,%s series = %s key = %s \n",$DataSet->getMax($Value),$DataSet->getValueAt($Value,$mykey),$Value,$mykey);
	  }
       }
     }
  }

///////////////////////////////////////

 /* Render the picture (choose the best way) */ 
 $Test->autoOutput("mygraph.png"); 

 ?>

