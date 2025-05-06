<?php
session_start();
include("db.php");
$pagename="Your Login Results"; 
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; 
echo "<title>".$pagename."</title>"; 
echo "<body>";
include ("headfile.html"); //include header layout file
echo "<h4>".$pagename."</h4>"; //display name of the page on the web page

//capture the 2 values entered by the user in the login form and assign to local variables
$email = $_POST['l_email'];
$password = $_POST['l_password'];

if (empty($email) or empty($password)) //if either the $email or the $password is empty
{
 echo "<p><b>Login failed!</b>"; //display login error
 echo "<br>login form incomplete";
 echo "<br>Make sure you provide all the required details</p>";
 echo "<br><p id='redirect'> Go back to <a href=login.php>login</a></p>";
}
else
{
 $SQL = "SELECT * FROM users WHERE email = '".$email."'"; //retrieve record if email matches
 $exeSQL = mysqli_query($conn, $SQL) or die (mysqli_error($conn)); //execute SQL query
 $nbrecs = mysqli_num_rows($exeSQL); //retrieve the number of records
 if ($nbrecs ==0) //if nb of records is 0 i.e. if no records were located for which email matches entered email
 {
 echo "<p><b>Login failed!</b>"; //display login error
 echo "<br>Email not recognised</p>";
 echo "<br><p id='redirect'> Go back to <a href=login.php>login</a></p>";
 }
 else
 {
 $arrayuser = mysqli_fetch_array($exeSQL); 

 if (!password_verify($password, $arrayuser['password'])) {
    echo "<p><b>Login failed!</b>";
    echo "<br>Password not valid</p>";
    echo "<br><p id='redirect'> Go back to <a href=login.php>login</a></p>";
} else {
    echo "<p><b>Login success</b></p>";
    $_SESSION['id'] = $arrayuser['id']; 
    $_SESSION['name'] = $arrayuser['name']; 
    echo "<p>Welcome, ". $_SESSION['name']."</p>";
    echo "<p id='redirect'><a href='dashboard.php'>Proceed to Dashboard</a></p>";
}

}
}

include("footfile.html"); //include footer layout
echo "</body>";
?>