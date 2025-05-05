<?php
$pagename="template"; 
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; //Call in stylesheet
echo "<title>".$pagename."</title>"; //display name of the page as window title
echo "<body>";
include ("headerfile.html"); //include header layout 
echo "<h4>".$pagename."</h4>"; //display name of the page on the web page
//display random text
echo "<p> Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum";
include("footerfile.html"); //include footer layout
echo "</body>";
?>