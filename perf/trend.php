<?php
        if ($_GET["ACT"] == "ADD" ){
          $objFopen = fopen("customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_GET["MON"].".cmt", 'w');
          $data=iconv('TIS-620', 'UTF-8',$_GET["CMT"]);
          fwrite($objFopen,$data);
          if($objFopen) {
           echo "Add completed.";
          }else{
           echo "Add failed";
          }
          fclose($objFopen);



        }
?>
<hr/>
<div class="wrapper col5">
  <div id="container">
    <div id="content">
      <h1><font color=orange><?=$_GET["GRP"]?></font> trend on <font color=orange><?=$_GET["MON"]?></font></h1>

<table style=border:0px; id=mytable>
<tr><td style=border:0px>
        <input type=text name=txttrend id=txttrend size=120 maxlength=120
<?php
         if (file_exists("customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_GET["MON"].".cmt")){
           $objFopen = fopen("customer/".$_GET["CUS"]."/".$_GET["GRP"]."/".$_GET["MON"].".cmt", 'r');
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
</td><td style=border:0px>
<button name="btnadd" type="button" id="btnadd" OnClick="JavaScript:doCallAjax('server','trend.php?ACT=ADD&CUS=<?=$_GET["CUS"]?>&GRP=<?=$_GET["GRP"]?>&MON=<?=$_GET["MON"]?>&CMT='+txttrend.value,'NONE');">

<?php
        if ($file != "") {
          echo "UPDATE";
        }else{
          echo "ADD";
        }
?>

</button>
</td></tr></table>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
