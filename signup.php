<?php
$pagename = "Sign Up"; 
echo "<link rel='stylesheet' type='text/css' href='mystylesheet.css'>"; 
echo "<title>$pagename</title>"; 
echo "<body>";
include("headfile.html");
echo "<h4>$pagename</h4>"; 
?>

<div class='form-wrapper'>
    <form method='post' action='signup_process.php' class='signup-form'>
        <div class="form-element">
            <label for="r_name">*Name</label>
            <input type="text" name="r_name" id="r_name" placeholder="Enter your name">
        </div>
        <div class="form-element">
            <label for="r_email">*Email Address</label>
            <input type="email" name="r_email" id="r_email" placeholder="Enter your email">
        </div>
        <div class="form-element">
            <label for="r_password1">*Password</label>
            <input type="password" name="r_password1" id="r_password1" placeholder="Enter password" maxlength="10">
        </div>
        <div class="form-element">
            <label for="r_password2">*Confirm Password</label>
            <input type="password" name="r_password2" id="r_password2" placeholder="Re-enter password" maxlength="10">
        </div>
        <div class="form-actions">
            <button type="submit" id="submitbtn">Sign Up</button>
            <button type="reset" class="btn">Clear</button>
        </div>
    </form>
</div>

<?php
include("footfile.html");
echo "</body>";
?>
