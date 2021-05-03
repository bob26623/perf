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
ob_start();  // output bufering capture start
//get unique id
$up_id = uniqid();

include("db.php");
//$objConnect = mysqli_init();
//mysqli_real_connect($objConnect,"luna.g-able.ga",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",3036,NULL,MYSQLI_CLIENT_SSL) or die("Error Connect to Database");
//$objConnect = mysqli_connect("localhost",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",NULL,"/cloudsql/smiling-gasket-170108:us-central1:csypart") or die("Error Connect to Database");
   //$objDB = mysqli_select_db("perf");
   mysqli_query($objConnect,"SET NAMES UTF8");
?>
<?php

//process the forms and upload the files
if ($_POST) {

//specify folder for file upload
//if(!file_exists("customer/".$_POST["CUS"])) { mkdir("customer/".$_POST["CUS"]); } // add when create customer !! 
//if(!file_exists("customer/".$_POST["CUS"]."/tmp")) { mkdir("customer/".$_POST["CUS"]."/tmp"); }
$folder = "customer/".$_POST["CUS"]."/tmp/";


  $redirect = "upload.php?result=1;CUS=".$_POST["CUS"];
//upload the file
//        echo "count=".count($_FILES["file"]["name"])."<br/>";;
for($i=0;$i<count($_FILES["file"]["name"]);$i++)
{
if($_FILES["file"]["name"][$i] != "")
{
      move_uploaded_file($_FILES["file"]["tmp_name"][$i], "$folder" . $_FILES["file"]["name"][$i]);
//        echo $_FILES["file"]["tmp_name"][$i]."<br/>";;
//        echo $_FILES["file"]["name"][$i]."<br/>";;
if ($_FILES["file"]["size"][$i] == 0 ){
  $redirect = "upload.php?result=2;CUS=".$_POST["CUS"];
}
}
}

//do whatever else needs to be done (insert information into database, etc...)

//redirect user
ob_end_clean(); //clear buffer
//header('Location: '.$redirect); die;
}
//

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<link href="styles/style_progress.css" rel="stylesheet" type="text/css" />
<script src="scripts/jquery.js" type="text/javascript"></script>
<!--display bar only if file is chosen-->
<script>

$(document).ready(function() {
//

//show the progress bar only if a file field was clicked
        var show_bar = 0;
    $('input[type="file"]').click(function(){
                show_bar = 1;
    });

//show iframe on form submit
    $("#form1").submit(function(){

                if (show_bar === 1) {
                        $('#upload_frame').show();
                        function set () {
                                $('#upload_frame').attr('src','upload_frame.php?up_id=<?php echo $up_id; ?>');
                        }
                        setTimeout(set);
                }
    });
//

});
</script>

</head>
<body id="top">
<div class="wrapper col3">
  <div id="breadcrumb">
    <ul>
<?php
 $strSQL = "SELECT customer FROM customer WHERE account like '%".$_SESSION["Username"]."%' ORDER BY customer"; 
 $cusQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]"); 
 $CUC=1; // customer counter
 $CUS=$_GET["CUS"];
 while ($cusResult = mysqli_fetch_array($cusQuery))
   {
	if ( $CUS == "NO" ) { 
	  echo "<li class='first'>";
	  $CUS=$cusResult["customer"];
	  echo "&nbsp;<font size=+2 color=orange>".$cusResult["customer"]."</font>&nbsp;</li>";
	} elseif ( $CUS == $cusResult["customer"] ) { 
	  if ($CUC != "1") {echo "<li>&#166;</li>";}
	  //echo "<li class='first'>"; 
	  echo "<li class='first'>&nbsp;<font size=+2 color=orange>".$cusResult["customer"]."</font>&nbsp;</a></li>"; 
	} else {
	  //echo "<li>&#166;</li>";
	  if ($CUC != "1") {echo "<li>&#166;</li>";}
	  //echo "<li><a href=upload.php?CUS=".$cusResult["customer"].">";
	  echo "<li><a href=upload.php?CUS=".$cusResult["customer"].">&nbsp;".$cusResult["customer"]."&nbsp;</a></li>";
	}
	  $CUC+=1;
	//echo "&nbsp;".$cusResult["customer"]."&nbsp;</a></li>";

   }
if(!file_exists("customer/$CUS")) { mkdir("customer/$CUS"); }  
if(!file_exists("customer/$CUS/tmp")) { mkdir("customer/$CUS/tmp"); }
?>
    </ul>
  </div>
</div>
<div class="wrapper col6">
  <div id="footer">
    <div id="contactform">
      <h2>Select File</h2>

  <?php if ($_GET["result"] == "1") {
    echo "<span class='notice'>Your file has been uploaded.</span>";
   }elseif ($_GET["result"] == "2") {
    echo "<span class='notice'>Upload failed.</span>";
   } ?>
  <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    Choose a file to upload <br/><br/>
<!--APC hidden field-->
    <input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?php echo $up_id; ?>"/>
    <input type="hidden" name="CUS" value='<?=$CUS?>' >
<!---->

    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>
    <input name="file[]" type="file" id="file[]" size="30"/><br/>

<!--Include the iframe-->
    <iframe id="upload_frame" name="upload_frame" frameborder="0" border="0" src="" scrolling="no" scrollbar="no" > </iframe>
<!---->
	<br/>
    <input name="Submit" type="submit" id="submit" value="Upload" />
  </form>


    </div>
    <!-- End Contact Form -->
    <div id="compdetails">
      <div id="officialdetails">
        <h2>Uploaded Files </h2>
<?php
	 $i=0;
	 $objOpen = opendir("customer/$CUS/tmp");
	   while (($ufile = readdir($objOpen)) !== false)
		{
		if ( $ufile != "." && $ufile != ".." ) {
		  $i++;
		  echo "$i. " . $ufile . "<br />";
		  }
		}

?>

      </div>
      <div id="contactdetails">
        <h2>Generate graph</h2>
	

	  <ul>
          <ol>- Content of upload files consist of "sar-[u,r,q,g],<font color=red>iostat</font>" directory</ol>
          <br/>
          <ol>- Monthly format : hostname.month.year.tar.gz </ol>
          <ol>- ie.<font color=yellow> myserver.Jan.2012.tar.gz </font></ol>
          <br/>
          <ol>- Monthly format : hostname.period.year.tar.gz </ol>
          <ol>- ie.<font color=yellow> myserver.1-10Jan.2012.tar.gz</font> </ol>
          <br/>
          <ol>- Daily format : hostname.date.sar-u or sar-r <font color=red>only</font> </ol>
          <ol>- ie.<font color=yellow> myserver.1Jan.2012.sar-u </font></ol>
          <ol>- ie.<font color=yellow> myserver.1Jan.2012.sar-r </font></ol>
          <br/>
          <ol>- Calculated Format : hostname.month.year.out </ol>
          <ol>- ie.<font color=yellow> myserver.Jan.2012.out</font> </ol>
          </ul>

	<br/><br/>
          <button name="btnprocess" type="button" id="btnprocess" 
	<?php if ($i == "0" ){
		echo "disabled='disabled'";
	   } 
		echo $i;
	?>
	  OnClick="window.location='process.php?CUS=<?=$CUS;?>';" >Process</button>

        <br class="clear" />
        <br class="clear" />
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
<br class="clear" />
<br class="clear" />
</div>
<div class="wrapper col7">
  <div id="copyright">
    <ul>
      <li><a href="#">Online Privacy Policy</a></li>
      <li><a href="#">Terms of Use</a></li>
      <li><a href="#">Permissions &amp; Trademarks</a></li>
      <li><a href="#">Product License Agreements</a></li>
      <li class="last">Template by <a target="_blank" href="http://www.os-templates.com/" title="Open Source Templates">OS Templates</a></li>
    </ul>
  </div>
</div>
</body>
</html>
