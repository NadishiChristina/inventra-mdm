<?php
session_start();

include ("../headerfile.html"); 

$pagename="logout"; 
echo "<link rel=stylesheet type=text/css href=../mystylesheet.css>"; 
echo "<title>".$pagename."</title>"; 

echo "<h4>".$pagename."</h4>"; 
echo "<p> Thank you, ".$_SESSION['fname']." ".$_SESSION['sname']."</p>";

unset($_SESSION);
session_destroy();
echo "<br><p>You are now logged out</p>";

include("../footerfile.html"); 
echo "</body>";
?>