<?php
session_start();
$pagename = "Login"; 
echo "<link rel='stylesheet' type='text/css' href='mystylesheet.css'>"; 
echo "<title>$pagename</title>"; 
echo "<body>";
include("headfile.html");
echo "<h4>$pagename</h4>";
?>

<div class='form-wrapper'>
    <form action='login_process.php' method='post' class='login-form'>
        <div class="form-element">
            <label for="l_email">Email</label>
            <input type="text" name="l_email" id="l_email" placeholder="Enter your email">
        </div>
        <div class="form-element">
            <label for="l_password">Password</label>
            <input type="password" name="l_password" id="l_password" placeholder="Enter your password">
        </div>
        <div class="form-actions">
            <button type="submit" id="submitbtn">Login</button>
            <button type="reset" class="btn">Clear</button>
        </div>
    </form>
</div>

<?php
include("footfile.html");
echo "</body>";
?>
