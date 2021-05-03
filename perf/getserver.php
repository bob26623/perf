<?php
	$PATH=$_GET["PATH"]."/".$_GET["YEAR"]."/".$_GET["MON"]."/".$_GET["SRV"].".".$_GET["MON"].".".$_GET["YEAR"];
	$PATH2=$_GET["PATH"]."/".$_GET["YEAR"]."/".$_GET["MON"];
        $FILE=$_GET["SRV"].".".$_GET["MON"].".".$_GET["YEAR"];
	if(!file_exists($_GET["PATH"]."/".$_GET["YEAR"])) { mkdir($_GET["PATH"]."/".$_GET["YEAR"]); }
	
	if(file_exists($_GET["PATH"]."/".$_GET["YEAR"]."/".$_GET["MON"])){
	
	// add comment 
        if ($_GET["MYFILE"]){
          $tmp=explode(":",$_GET["CMT"]);
          switch($_GET["MYFILE"]){
          case "utilize" : $data=$tmp[0];
                break;
          case "aversar" : $data=$tmp[1];
                break;
          case "realcpu" : $data=$tmp[2];
                break;
          case "realmem" : $data=$tmp[3];
                break;
          case "runq" : $data=$tmp[4];
                break;
          case "scan" : $data=$tmp[5];
                break;
          }
          $objFopen = fopen($PATH.".".$_GET["MYFILE"].".cmt", 'w');
	  $data=iconv('TIS-620', 'UTF-8',$data); 
          fwrite($objFopen,$data);
          if($objFopen) {
           echo "Comment completed."; 
          }else{
           echo "Comment failed"; 
          } 
          fclose($objFopen); 
        }
?>
        <hr/>
        <div class="wrapper col3">
        <div id="gallery">
         <ul>
         <li ondblclick="javascript:fncComment('<?=$PATH?>','utilize');" class="placeholder" style="background-image:url(<?=$PATH?>.utilize.png);">Image Holder</li>
	
 
<form name="frmComment" method="post" action="check.php">
<table style=border:0px;display:none id=mytable>
<tr><td style=border:0px>
         <div id=utilize style=display:none >Add Comment for utilize </div>
         <div id=aversar style=display:none >Add Comment for average sar </div>
         <div id=realcpu style=display:none >Add Comment for real cpu </div>
         <div id=realmem style=display:none >Add Comment for real memory </div>
         <div id=runq style=display:none >Add Comment for run queue </div>
         <div id=scan style=display:none >Add Comment for scan rate  </div>
</td><td style=border:0px>

         <input type=text name=txtutilize id=txtutilize style=display:none size=100 
<?php
         if (file_exists($PATH.".utilize.cmt")){
           $objFopen = fopen($PATH.".utilize.cmt", 'r');
           if ($objFopen) {
                while (!feof($objFopen)) {
                        $file = fgets($objFopen, 4096);
			echo "value='$file' >";
                }
                fclose($objFopen);
           }
        }else{ 
          echo "value='' >";
        }
?>
         <input type=text name=txtaversar id=txtaversar style=display:none size=100 
<?php
         if (file_exists($PATH.".aversar.cmt")){
           $objFopen = fopen($PATH.".aversar.cmt", 'r');
           if ($objFopen) {
                while (!feof($objFopen)) {
                        $file = fgets($objFopen, 4096);
			echo "value='$file' >";
                }
                fclose($objFopen);
           }
        }else{
          echo "value='' >";
        }
?>
	<input type=text name=txtrealcpu id=txtrealcpu style=display:none size=100 
<?php
         if (file_exists($PATH.".realcpu.cmt")){
           $objFopen = fopen($PATH.".realcpu.cmt", 'r');
           if ($objFopen) {
                while (!feof($objFopen)) {
                        $file = fgets($objFopen, 4096);
			echo "value='$file' >";
                }
                fclose($objFopen);
           }
        }else{
          echo "value='' >";
        }
?>
         <input type=text name=txtrealmem id=txtrealmem style=display:none size=100  
<?php
         if (file_exists($PATH.".realmem.cmt")){
           $objFopen = fopen($PATH.".realmem.cmt", 'r');
           if ($objFopen) {
                while (!feof($objFopen)) {
                        $file = fgets($objFopen, 4096);
			echo "value='$file' >";
                }
                fclose($objFopen);
           }
        }else{
          echo "value='' >";
        }
?>
         <input type=text name=txtrunq id=txtrunq style=display:none size=100  
<?php      
         if (file_exists($PATH.".runq.cmt")){
           $objFopen = fopen($PATH.".runq.cmt", 'r');
           if ($objFopen) {
                while (!feof($objFopen)) {
                        $file = fgets($objFopen, 4096);
			echo "value='$file' >";
                }
                fclose($objFopen);
           }
        }else{
          echo "value='' >";
        }
?>
         <input type=text name=txtscan id=txtscan style=display:none size=100 
<?php      
         if (file_exists($PATH.".scan.cmt")){
           $objFopen = fopen($PATH.".scan.cmt", 'r');
           if ($objFopen) {
                while (!feof($objFopen)) {
                        $file = fgets($objFopen, 4096);
			echo "value='$file' >";
                }
                fclose($objFopen);
           }
        }else{
          echo "value='' >";
        }
?>

         <input type=hidden name=mypath value=<?=$PATH?>>
         <input type=hidden name=myfile id=myfile value=''>
</td><td style=border:0px>
<button name="btnprocess" type="button" id="btnprocess" OnClick="JavaScript:doCallAjax('server','getserver.php?PATH=<?=$_GET["PATH"]?>&YEAR=<?=$_GET["YEAR"]?>&MON=<?=$_GET["MON"]?>&SRV=<?=$_GET["SRV"]?>&MYFILE='+myfile.value+'&CMT='+txtutilize.value+':'+txtaversar.value+':'+txtrealcpu.value+':'+txtrealmem.value+':'+txtrunq.value+':'+txtscan.value,'NONE');">
        Comment</button>
</td></tr></table>
         </form>
<?php
	 if (file_exists("$PATH.aversar.png")){
         echo "<li ondblclick=\"javascript:fncComment('$PATH','aversar');\"><a class=\"swap\" href=\"javascript:void(0)\"><img src=\"$PATH.aversar.png\" alt=\"\" />";
	 echo "<span><img src=\"$PATH.aversar.png\" width=\"950\" height=\"370\" alt=\"average sar\" /></span></a></li>";
	 }

	 if (file_exists("$PATH.realcpu.png")){
         echo "<li ondblclick=\"javascript:fncComment('$PATH','realcpu');\"><a class=\"swap\" href=\"javascript:void(0)\"><img src=\"$PATH.realcpu.png\" alt=\"\" />";
	 echo "<span><img src=\"$PATH.realcpu.png\" width=\"950\" height=\"370\" alt=\"real cpu\" /></span></a></li>";
	}	
	if (file_exists("$PATH.realmem.png")){
         echo "<li ondblclick=\"javascript:fncComment('$PATH','realmem');\"><a class=\"swap\" href=\"javascript:void(0)\"><img src=\"$PATH.realmem.png\" alt=\"\" />";
	 echo "<span><img src=\"$PATH.realmem.png\" width=\"950\" height=\"370\" alt=\"real memory\" /></span></a></li>";
	}

	if (file_exists("$PATH.runq.png")){
         echo "<li ondblclick=\"javascript:fncComment('$PATH','runq');\"><a class=\"swap\" href=\"javascript:void(0)\"><img src=\"$PATH.runq.png\" alt=\"\" />";
	 echo "<span><img src=\"$PATH.runq.png\" width=\"950\" height=\"370\" alt=\"run queue\" /></span></a></li>";
	}

	if (file_exists("$PATH.scan.png")){
         echo "<li ondblclick=\"javascript:fncComment('$PATH','scan');\" class=\"last\"><a class=\"swap\" href=\"javascript:void(0)\"><img src=\"$PATH.scan.png\" alt=\"\" />";
	 echo "<span><img src=\"$PATH.scan.png\" width=\"950\" height=\"370\" alt=\"scan rate\" /></span></a></li>";
	}


        echo "</ul>";
        echo "</div>";
	echo "<div id=\"container\">";
if (file_exists("$PATH2/iostat/")){
        echo "<font color=yellow>iostat per Ctrl</font> : ";
if(file_exists("$PATH2/iostat/$FILE.asvc.out.png") || file_exists("$PATH2/iostat/$FILE.wsvc.out.png") || file_exists("$PATH2/iostat/$FILE.aread.out.png") || file_exists("$PATH2/iostat/$FILE.awrite.out.png") || file_exists("$PATH2/iostat/$FILE.pread.out.png") ||file_exists("$PATH2/iostat/$FILE.pwrite.out.png")){
        echo "<a href=showio.php?TYP=CTRL&PATH=$PATH2/iostat&SRV=".$_GET["SRV"]."&MON=".$_GET["MON"]."&YEAR=".$_GET["YEAR"]." target=_blank>(All) </a>";
	}
        if(file_exists("$PATH2/iostat/$FILE.asvc.out.png")){
          echo "<a href=$PATH2/iostat/$FILE.asvc.out.png target=_blank>Avg_Svc</a> ";
        }
        if(file_exists("$PATH2/iostat/$FILE.wsvc.out.png")){
          echo "<a href=$PATH2/iostat/$FILE.wsvc.out.png target=_blank>Wait_Svc</a> ";
        }
        if(file_exists("$PATH2/iostat/$FILE.aread.out.png")){
          echo "<a href=$PATH2/iostat/$FILE.aread.out.png target=_blank>Avg_Read</a> ";
        }
        if(file_exists("$PATH2/iostat/$FILE.awrite.out.png")){
          echo "<a href=$PATH2/iostat/$FILE.awrite.out.png target=_blank>Avg_Write</a> ";
        }
        if(file_exists("$PATH2/iostat/$FILE.pread.out.png")){
          echo "<a href=$PATH2/iostat/$FILE.pread.out.png target=_blank>Peak_Read</a> ";
        }
        if(file_exists("$PATH2/iostat/$FILE.pwrite.out.png")){
          echo "<a href=$PATH2/iostat/$FILE.pwrite.out.png target=_blank>Peak_Write</a> ";
        }
        echo "<br/>";

        echo "<font color=yellow>iostat per LUN</font> : ";
        $objScan = scandir("$PATH2/iostat/");
        foreach ($objScan as $value) {
          if (preg_match("/_.asvc\.out\.png$/",$value)) {
            $filename = explode(".",$value);
            $ctrl = explode("_",$filename[3]);
            echo "<a href=showio.php?TYP=LUN&PATH=$PATH2/iostat&CTRL=$ctrl[0]&SRV=".$_GET["SRV"]."&MON=".$_GET["MON"]."&YEAR=".$_GET["YEAR"]." target=_blank>$ctrl[0]</a> ";
          }
        }

        echo "<br/>";
}
	echo "<font color=yellow>Source Data</font> : ";
	if(file_exists("$PATH.utilize.out")){
	  echo "<a href=$PATH.utilize.out>utilize</a> ";
	}
	if(file_exists("$PATH.aversar.out")){
	  echo "<a href=$PATH.aversar.out>average</a> ";
	}
	if(file_exists("$PATH.realcpu.out")){
	  echo "<a href=$PATH.realcpu.out>real cpu</a> ";
	}
	if(file_exists("$PATH.realmem.out")){
	  echo "<a href=$PATH.realmem.out>real memory</a> ";
	}
	if(file_exists("$PATH.runq.out")){
	  echo "<a href=$PATH.runq.out>run queue</a> ";
	}
	if(file_exists("$PATH.scan.out")){
	  echo "<a href=$PATH.scan.out>scan rate</a> ";
	}
	
	echo "<br/>";

	} // if for show image
	$objOpen = opendir($_GET["PATH"]."/".$_GET["YEAR"]);
        while (($ufile = readdir($objOpen)) !== false)
          {
	 if ($ufile != "." && $ufile != ".." && $ufile != "Jan" && $ufile != "Feb" && $ufile != "Mar"&& $ufile != "Apr"&& $ufile != "May"&& $ufile != "Jun"&& $ufile != "Jul"&& $ufile != "Aug"&& $ufile != "Sep"&& $ufile != "Oct"&& $ufile != "Nov"&& $ufile != "Dec")
	    {
// disable sort by date
//	if (strpos($ufile,$_GET["MON"])){
		$PATH=$_GET["PATH"]."/".$_GET["YEAR"]."/$ufile/".$_GET["SRV"].".$ufile.".$_GET["YEAR"] ;
		echo "<br/><font color=yellow>Graph on $ufile</font> : ";

		if(file_exists("$PATH.pdf")){
                echo "<a href=$PATH.pdf target=_blank><img src=images/pdf.png heigh=20 width=15 /></a> ";       
                }

		if(file_exists("$PATH.utilize.png")){
		echo "<a href=$PATH.utilize.png target=_blank>utilize</a> ";	
                echo "(<a href=$PATH.utilize.out >s</a>)  ";                                                  
		}
		if(file_exists("$PATH.aversar.png")){
		echo "<a href=$PATH.aversar.png target=_blank> average</a> ";	
                echo "(<a href=$PATH.aversar.out >s</a>)  ";  
		}
		if(file_exists("$PATH.realcpu.png")){
		echo "<a href=$PATH.realcpu.png target=_blank> real cpu</a> ";	
                echo "(<a href=$PATH.realcpu.out >s</a>)  "; 
		}
		if(file_exists("$PATH.realmem.png")){
		echo "<a href=$PATH.realmem.png target=_blank> real mem</a> ";	
                echo "(<a href=$PATH.realmem.out >s</a>)  "; 
		}
		if(file_exists("$PATH.runq.png")){
		echo "<a href=$PATH.runq.png target=_blank> run queue</a> ";	
                echo "(<a href=$PATH.runq.out>s</a>)  ";   
		}
		if(file_exists("$PATH.scan.png")){
		echo "<a href=$PATH.scan.png target=_blank> scan rate</a> ";	
                echo "(<a href=$PATH.scan.out>s</a>)"; 
		}
		if(file_exists("$PATH.cpu.png")){
		echo "<a href=$PATH.cpu.png target=_blank> CPU</a> ";	
                echo "(<a href=$PATH.cpu.out>s</a>)"; 
		}
		if(file_exists("$PATH.mem.png")){
		echo "<a href=$PATH.mem.png target=_blank> Memory</a> ";	
                echo "(<a href=$PATH.mem.out>s</a>)"; 
		}
		if(file_exists("$PATH.wsvc.png")){
                echo "<a href=$PATH.wsvc.png target=_blank> wsvc</a> "; 
                echo "(<a href=$PATH.wsvc.out>s</a>)"; 
                }
                if(file_exists("$PATH.asvc.png")){
                echo "<a href=$PATH.asvc.png target=_blank> asvc</a> "; 
                echo "(<a href=$PATH.asvc.out>s</a>)"; 
                }
                if(file_exists("$PATH.read.png")){
                echo "<a href=$PATH.read.png target=_blank> read i/o</a> ";     
                echo "(<a href=$PATH.read.out>s</a>)"; 
                }
                if(file_exists("$PATH.write.png")){
                echo "<a href=$PATH.write.png target=_blank> write i/o</a> ";   
                echo "(<a href=$PATH.write.out>s</a>)"; 
                }
		//echo "<br/>";
//		} // check same month

	    } // if 
		
	  } // while
	closedir($objOpen);
?>
        </div>
        </div>

