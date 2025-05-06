<?php
$host = 'localhost';
$db = 'mdm_db';
$user = 'root';
$pass = '';
$conn = mysqli_connect($host, $user, $pass, $db, 3307);

//if the DB connection fails, display an error message and exit
if (!$conn){
die('Could not connect: ' . mysqli_error($conn));
}
?>
