<?php
session_start();
include("../db.php");
$pagename="Your Login Results";
echo "<link rel=stylesheet type=text/css href=../mystylesheet.css>"; //Call in stylesheet
echo "<title>".$pagename."</title>";
echo "<body>";
include ("../headerfile.html");
echo "<h4>".$pagename."</h4>"; 

//capture the 2 values entered by the user in the form using the $_POST superglobal variable
//assign these 2 values to 2 local variables
$email = $_POST['l_email'];
$password = $_POST['l_password'];
;

if (empty($email) or empty($password)) //if either the $email or the $password is empty
{
 echo "<p><b>Login failed!</b>"; //display login error
 echo "<br>login form incomplete";
 echo "<br>Make sure you provide all the required details</p>";
 echo "<br><p> Go back to <a href=login.php>login</a></p>";
}
else
{
 $SQL = "SELECT * FROM users WHERE email = '".$email."'"; //retrieve record if email matches
 $exeSQL = mysqli_query($conn, $SQL) or die (mysqli_error($conn)); 
 $nbrecs = mysqli_num_rows($exeSQL); 
 if ($nbrecs ==0) //if nb of records is 0 - no records were located for entered email
 {
 echo "<p><b>Login failed!</b>"; //display login error
 echo "<br>Email not recognised</p>";
 echo "<br><p> Go back to <a href=login.php>login</a></p>";

 }
 else
 {
 $arrayuser = mysqli_fetch_array($exeSQL); 

 if ($arrayuser['password'] <> $password) //if the pwd in the array does not match the pwd entered in the form
 {
 echo "<p><b>Login failed!</b>"; //display login error
 echo "<br>Password not valid</p>";
 echo "<br><p> Go back to <a href=login.php>login</a></p>";
 }
 else
 {
 echo "<p><b>Login success</b></p>"; //display login success
 $_SESSION['id'] = $arrayuser['id']; 
 $_SESSION['name'] = $arrayuser['name']; 
 echo "<p>Welcome, ". $_SESSION['name']."</p>"; //display welcome greeting
 }
}
}

include("../footerfile.html"); 
echo "</body>";
?>