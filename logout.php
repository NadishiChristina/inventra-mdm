<?php
session_start();
$pagename="Logout"; 
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; 
echo "<title>".$pagename."</title>"; 
echo "<body>";
include ("headfile.html");
echo "<h4>".$pagename."</h4>"; 

// Check if user is logged in
if(isset($_SESSION['id'])) {
    echo "<p id='thankyou-message'>Thank you, ".$_SESSION['name']."</p>";
    
    // Store name temporarily for goodbye message
    $userName = $_SESSION['name'];
    
    // Clear session variables and destroy session
    session_unset();
    session_destroy();
    
    echo "<p id='logout-message'>You are now logged out</p>";
    echo "<p id='redirect'><a href='login.php'>Login again</a></p>";
} else {
    echo "<p id='logout-message'>You are not logged in</p>";
    echo "<p id='redirect'><a href='login.php'>Login</a></p>";
}

include("footfile.html");
echo "</body>";
?>
