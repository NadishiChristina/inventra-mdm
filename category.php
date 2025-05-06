<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['id'];

$pagename = "Category Management";
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; 
echo "<title>".$pagename."</title>";
echo "<body>";
include("headfile.html");
include("detectlogin.php");
echo "<h4>".$pagename."</h4>";

// Check if we're editing a category
$editMode = false;
$categoryId = "";
$categoryCode = "";
$categoryName = "";
$categoryStatus = "Active";

if (isset($_GET['id'])) {
    $editMode = true;
    $categoryId = $_GET['id'];
    
    // Fetch category details and verify it belongs to the current user
    $SQL = "SELECT * FROM master_category WHERE id = '".$categoryId."' AND user_id = '".$user_id."'";
    $exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));
    
    if (mysqli_num_rows($exeSQL) == 0) {
        // Category not found or doesn't belong to user
        echo "<script>alert('Category not found!'); window.location.href='category.php';</script>";
        exit();
    }
    
    $category = mysqli_fetch_array($exeSQL);
    
    if ($category) {
        $categoryCode = $category['code'];
        $categoryName = $category['name'];
        $categoryStatus = $category['status'];
    }
}

echo "<div class='formStyle'>";
echo "<form method='post' action='".($editMode ? "category_update.php" : "category_add.php")."'>";
if ($editMode) {
    echo "<input type='hidden' name='category_id' value='".$categoryId."'>";
}
echo "<div class='element'><label for='code'>Category Code</label>";
echo "<input type='text' name='code' id='code' value='".$categoryCode."' required></div>";

echo "<div class='element'><label for='name'>Category Name</label>";
echo "<input type='text' name='name' id='name' value='".$categoryName."' required></div>";

if ($editMode) {
    echo "<div class='element'><label for='status'>Status</label>";
    echo "<select name='status' id='status'>";
    echo "<option value='Active' ".($categoryStatus == "Active" ? "selected" : "").">Active</option>";
    echo "<option value='Inactive' ".($categoryStatus == "Inactive" ? "selected" : "").">Inactive</option>";
    echo "</select></div>";
}

echo "<div class='element'>";
echo "<input type='submit' value='".($editMode ? "Update" : "Add")." Category' id='submitbtn'>";
echo "<a href='category.php' class='btn'>Cancel</a>";
echo "</div>";
echo "</form>";
echo "</div>";

// Pagination settings
$itemsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Get total number of categories belonging to the user
$countSQL = "SELECT COUNT(*) as total FROM master_category WHERE user_id = '".$user_id."'";
$countResult = mysqli_query($conn, $countSQL);
$count = mysqli_fetch_assoc($countResult);
$totalCategories = $count['total'];
$totalPages = ceil($totalCategories / $itemsPerPage);

// Fetch categories with pagination, filtered by user_id
$SQL = "SELECT * FROM master_category WHERE user_id = '".$user_id."' ORDER BY id DESC LIMIT $offset, $itemsPerPage";
$exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));

// Display categories
echo "<div class='container'>";
echo "<h5>Categories</h5>";

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
    
    while ($category = mysqli_fetch_array($exeSQL)) {
        echo "<tr>";
        echo "<td>".$category['code']."</td>";
        echo "<td>".$category['name']."</td>";
        echo "<td>".$category['status']."</td>";
        echo "<td>".$category['created_at']."</td>";
        echo "<td>".$category['updated_at']."</td>";
        echo "<td>";
        echo "<a href='category.php?id=".$category['id']."' class='btn'>Edit</a> ";
        echo "<a href='category_delete.php?id=".$category['id']."' class='btn' onclick='return confirm(\"Are you sure you want to delete this category?\")'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Pagination links
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a id='number' href='category.php?page=$i' ".($page == $i ? "class='active'" : "").">$i</a> ";
    }
    echo "</div>";
} else {
    echo "<p>No categories found. Add your first category using the form above.</p>";
}

echo "<a href='dashboard.php' class='btn'>Back to Dashboard</a>";
echo "</div>";

include("footfile.html"); // include footer layout
echo "</body>";
?>