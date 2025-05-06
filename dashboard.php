<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$pagename = "Dashboard"; 
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; 
echo "<title>".$pagename."</title>"; 
echo "<body>";
include("headfile.html"); 
include("detectlogin.php");

echo "<div class='welcome-message'>";
echo "<h4>Welcome, " . $_SESSION['name'] . " 👋</h4>";
echo "</div>";

echo "<h4>".$pagename."</h4>"; // display name of the page on the web page

// Get items with brand and category information
$SQL = "SELECT i.*, b.name as brand_name, c.name as category_name 
        FROM master_item i
        LEFT JOIN master_brand b ON i.brand_id = b.id
        LEFT JOIN master_category c ON i.category_id = c.id
        WHERE i.status = 'Active'
        ORDER BY i.name";
$exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));

// Display items
echo "<div class='container'>";

echo "<div class='dashboard-menu'>";
echo "  <a href='brand.php' class='dashboard-button'>";
echo "    <i class='fas fa-tags'></i><br>Manage Brands";
echo "  </a>";
echo "  <a href='category.php' class='dashboard-button'>";
echo "    <i class='fas fa-layer-group'></i><br>Manage Categories";
echo "  </a>";
echo "  <a href='item.php' class='dashboard-button'>";
echo "    <i class='fas fa-box'></i><br>Add New Item";
echo "  </a>";
echo "</div>";

echo "</div>";

include("footfile.html"); // include footer layout
echo "</body>";
?>