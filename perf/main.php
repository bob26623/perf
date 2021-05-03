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
?>
<HTML>
<HEAD>
<TITLE>Performance Reporting Tool</TITLE>
</HEAD>

<frameset rows="85,*" FRAMEBORDER=NO FRAMESPACING=0 BORDER=0>
  <frame src="MenuFrame.php" name="menufrm" noresize scrolling="no" />
  <frame src="upload.php?CUS=NO" name="showfrm"/>

</frameset>

</HTML>

