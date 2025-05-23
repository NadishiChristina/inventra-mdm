<?php
session_start();

include('db.php');
mysqli_report(MYSQLI_REPORT_OFF);

$pagename="Sign-Up Result"; 
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; 
echo "<title>".$pagename."</title>";
echo "<body>";
include ("headfile.html");
echo "<h4>".$pagename."</h4>";

//Capture the inputs entered in signup form and assign to new local variables
$name=trim($_POST['r_name']);
$email=trim($_POST['r_email']);
$password1=trim($_POST['r_password1']);
$password2=trim($_POST['r_password2']);

//A regular expression variable for email validation
$reg = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";

//check if any of the variables that captured the posted values are empty
if (empty($name) or empty($email)or empty($password1)or empty($password2))
{
echo "<p><b>Sign-up failed!</b></p>";
echo "<br><p>Your signup form is incomplete and all fields are mandatory";
echo "<br>Make sure you provide all the required details</p>";
echo "<br><p id='redirect'>Go back to <a href=signup.php>sign up</a></p>";
echo "<br><br><br><br>";
}
elseif ($password1<>$password2) //if the 2 entered passwords do not match
{
echo "<p><b>Sign-up failed!</b></p>";
echo "<br><p>The 2 passwords are not matching";
echo "<br>Make sure you enter them correctly</p>";
echo "<br><p id='redirect'>Go back to <a href=signup.php>sign up</a></p>";
echo "<br><br><br><br>";
}
elseif (!preg_match($reg, $email)) //if the email does not match the regular expression
{
echo "<p><b>Sign-up failed!</b></p>";
echo "<br><p>Email not valid";
echo "<br>Make sure you enter a correct email address</p>";
echo "<br><p id='redirect'>Go back to <a href=signup.php>sign up</a></p>";
echo "<br><br><br><br>";
}
else
{
// Hash the password before storing it
$hashed_password = password_hash($password1, PASSWORD_DEFAULT);

$SQL="insert into users
(name, email, password)
values 
('".$name."', '".$email."', '".$hashed_password."')";
//if SQL execution is correct
if (mysqli_query($conn, $SQL))
{
//display sign up success
echo "<p><b>Sign-up successful!</b></p>";
echo "<br><p id='redirect'>To continue, please <a href=login.php>login</a></p>";
}
 //if the SQL execution is erroneous, there is a problem
else 
{
 //display sign up failure
echo "<p><b>Sign-up failed!</b></p>";
  
//error code for unique constraint violation - user has entered an email which already exists
if (mysqli_errno($conn)==1062)
{
echo "<br><p>Email already in use";
echo "<br>You may be already registered or try another email address</p>";
}
//error code for inserting invalid characters
if (mysqli_errno($conn)==1064)
{
echo "<br><p>Invalid characters entered in the form";
$invalidchars="apostrophes like [ ' ] and backslashes like [ \ ]";
echo "<br>Make sure you avoid the following characters: ".$invalidchars. "</p>";
}
echo "<br><p id='redirect'>Go back to <a href=signup.php>sign up</a></p>";
echo "<br><br><br><br>";
}
}

include("footfile.html"); //include footer layout
echo "</body>";
?>