<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$pagename = "Brand Management";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>";
echo "<title>".$pagename."</title>"; 
echo "<body>";
include("headfile.html"); 
include("detectlogin.php");
echo "<h4>".$pagename."</h4>";

// Get user ID from session
$user_id = $_SESSION['id'];

// Check if we're editing a brand
$editMode = false;
$brandId = "";
$brandCode = "";
$brandName = "";
$brandStatus = "Active";

if (isset($_GET['id'])) {
    $editMode = true;
    $brandId = $_GET['id'];
    
    // Fetch brand details and verify it belongs to the current user
    $SQL = "SELECT * FROM master_brand WHERE id = '".$brandId."' AND user_id = '".$user_id."'";
    $exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));
    
    if (mysqli_num_rows($exeSQL) == 0) {
        // Brand not found or doesn't belong to user
        echo "<script>alert('Brand not found!'); window.location.href='brand.php';</script>";
        exit();
    }
    
    $brand = mysqli_fetch_array($exeSQL);
    
    if ($brand) {
        $brandCode = $brand['code'];
        $brandName = $brand['name'];
        $brandStatus = $brand['status'];
    }
}

// Display form for adding/editing brand
echo "<div class='formStyle'>";
echo "<form method='post' action='".($editMode ? "brand_update.php" : "brand_add.php")."'>";
if ($editMode) {
    echo "<input type='hidden' name='brand_id' value='".$brandId."'>";
}
echo "<div class='element'><label for='code'>Code:</label>";
echo "<input type='text' name='code' id='code' value='".$brandCode."' required></div>";

echo "<div class='element'><label for='name'>Name:</label>";
echo "<input type='text' name='name' id='name' value='".$brandName."' required></div>";

if ($editMode) {
    echo "<div class='element'><label for='status'>Status:</label>";
    echo "<select name='status' id='status'>";
    echo "<option value='Active' ".($brandStatus == "Active" ? "selected" : "").">Active</option>";
    echo "<option value='Inactive' ".($brandStatus == "Inactive" ? "selected" : "").">Inactive</option>";
    echo "</select></div>";
}

echo "<div class='element'>";
echo "<input type='submit' value='".($editMode ? "Update" : "Add")." Brand' id='submitbtn'>";
echo "<a href='brand.php' class='btn'>Cancel</a>";
echo "</div>";
echo "</form>";
echo "</div>";

// Pagination settings
$itemsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Get total number of brands belonging to the user
$countSQL = "SELECT COUNT(*) as total FROM master_brand WHERE user_id = '".$user_id."'";
$countResult = mysqli_query($conn, $countSQL);
$count = mysqli_fetch_assoc($countResult);
$totalBrands = $count['total'];
$totalPages = ceil($totalBrands / $itemsPerPage);

// Fetch brands with pagination, filtered by user_id
$SQL = "SELECT * FROM master_brand WHERE user_id = '".$user_id."' ORDER BY id DESC LIMIT $offset, $itemsPerPage";
$exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));

// Display brands
echo "<div class='container'>";
echo "<h5>Brands</h5>";

if (mysqli_num_rows($exeSQL) > 0) {
    echo "<table id='indextable'>";
    echo "<tr>";
    echo "<th>Code</th>";
    echo "<th>Name</th>";
    echo "<th>Status</th>";
    echo "<th>Created At</th>";
    echo "<th>Updated At</th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    
    while ($brand = mysqli_fetch_array($exeSQL)) {
        echo "<tr>";
        echo "<td>".$brand['code']."</td>";
        echo "<td>".$brand['name']."</td>";
        echo "<td>".$brand['status']."</td>";
        echo "<td>".$brand['created_at']."</td>";
        echo "<td>".$brand['updated_at']."</td>";
        echo "<td>";
        echo "<a href='brand.php?id=".$brand['id']."' class='btn'>Edit</a> ";
        echo "<a href='brand_delete.php?id=".$brand['id']."' class='btn' onclick='return confirm(\"Are you sure you want to delete this brand?\")'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Pagination links
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a id='number' href='brand.php?page=$i' ".($page == $i ? "class='active'" : "").">$i</a> ";
    }
    echo "</div>";
} else {
    echo "<p>No brands found. Add your first brand using the form above.</p>";
}

echo "<a href='dashboard.php' class='btn'>Back to Dashboard</a>";
echo "</div>";

include("footfile.html"); // include footer layout
echo "</body>";
?>