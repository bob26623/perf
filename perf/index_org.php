<?php
if($_GET["switch"]=="on") {
apache_setenv("switch","on",1);
echo "<center><h2>ON</h2></center>";
}
elseif($_GET["switch"]=="off") {
apache_setenv("switch","off",1);
echo "<center><h2>OFF</h2></center>";
} else {

echo "<h1>".apache_getenv("switch",1)."</h1>";
//if(apache_getenv("switch")=="on") {
//include("index_org.html");
//} else {
//include("index_close.html");
//}

}
?>
