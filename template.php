<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$pagename="template"; //Create and populate a variable called $pagename
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; //Call in stylesheet
echo "<title>".$pagename."</title>"; //display name of the page as window title
echo "<body>";
include("headfile.html"); // include header layout file
include("detectlogin.php"); // display the logged-in username across pages
echo "<h4>".$pagename."</h4>"; // display name of the page on the web page

//content
echo "<p> Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum";

include("footfile.html"); //include footer layout
echo "</body>";
?>