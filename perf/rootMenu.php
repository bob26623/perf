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
<html>
<head>
</head>
<body>
<table>
<tr><td><a href="cuassign.php" target="rootRIGHT">Customer</a></td></tr>
<tr><td><a href="edituser.php" target="rootRIGHT">User</a></td></tr>
<tr><td><a href="modeladd.php" target="rootRIGHT">Model</a></td></tr>
</table>
</body>
</html>
