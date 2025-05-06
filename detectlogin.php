<?php
if (isset($_SESSION['id'])){
    //to display the username of logged-in user across pages
    echo "<p style='float: right'><i><b>Account: ".$_SESSION['name']."</b></i></p>";
}
?>