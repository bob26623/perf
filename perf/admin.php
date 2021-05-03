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

    <FRAMESET cols="100,*" onResize="if (navigator.family == 'nn4') window.location.reload()"> 
      <FRAME src="rootMenu.php" name="rootLEFT"> 
      <FRAME src="cuassign.php" name="rootRIGHT" > 
    </FRAMESET> 

