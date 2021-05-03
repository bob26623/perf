<?php
	ob_start();  // output bufering capture start 
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
<html>
<head>
 <meta http-equiv=Content-Type content="text/html; charset=utf-8"> 
</head>
<body>
<?php
	if($_POST["txt2"] == "") {
		ob_end_clean(); //clear buffer
		header("Location: modeladd.php?RESULT=3&INFO=Model");
	}elseif(trim($_FILES["fileUpload"]["tmp_name"]) == "" ){
		ob_end_clean(); //clear buffer
		header("Location: modeladd.php?RESULT=3&INFO=Picture");
	}else{
	  $strSQL = "SELECT modelid FROM model WHERE model ='".trim($_POST["txt2"])."'";
	}
	$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
   	$objResult = mysqli_fetch_array($objQuery);	
	if($objResult)
     	{
        	ob_end_clean(); //clear buffer
        	header("Location: modeladd.php?RESULT=2&INFO=".$_POST["txt2"]);
     	}else{
//		$images = $_FILES["fileUpload"]["tmp_name"];
//		$width=200; //*** Fix Width & Heigh (Autu caculate) ***//
//		$size=GetimageSize($images);
//		$height=round($width*$size[1]/$size[0]);
//		$images_orig = ImageCreateFromJPEG($images);
//		$photoX = ImagesX($images_orig);
//		$photoY = ImagesY($images_orig);
//		$images_fin = ImageCreateTrueColor($width, $height);
//		ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
//		ImageJPEG($images_fin,"./images/model/".$_POST["txt2"].".jpg");
//		ImageDestroy($images_orig);
//		ImageDestroy($images_fin);

		
		$images = ImageCreateFromJPEG($_FILES["fileUpload"]["tmp_name"]);
		ImageJPEG($images,"./images/model/".$_POST["txt2"].".jpg");
	$strSQL = "INSERT INTO model ";
   	$strSQL .="(model) ";
   	$strSQL .="VALUES ";
   	$strSQL .="('".trim($_POST["txt2"])."') ";
   	$objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
   	if($objQuery){
          ob_end_clean(); //clear buffer
          header("Location: modeladd.php?RESULT=4&INFO=".$_POST["txt2"]);
     	}else{
          ob_end_clean(); //clear buffer
          header("Location: modeladd.php?RESULT=1");
     	}

	} // if check exist
?>



</body>
</html>
