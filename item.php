<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$pagename = "Item Management"; // Create and populate a variable called $pagename
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; // Call in stylesheet
echo "<title>".$pagename."</title>"; // display name of the page as window title
echo "<body>";
include("headfile.html"); // include header layout file
include("detectlogin.php");
echo "<h4>".$pagename."</h4>"; // display name of the page on the web page

// Check if we're editing an item
$editMode = false;
$itemId = "";
$itemCode = "";
$itemName = "";
$itemBrandId = "";
$itemCategoryId = "";
$itemStatus = "Active";
$itemAttachment = "";

if (isset($_GET['id'])) {
    $editMode = true;
    $itemId = $_GET['id'];
    
    // Fetch item details
    $SQL = "SELECT * FROM master_item WHERE id = '".$itemId."'";
    $exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));
    $item = mysqli_fetch_array($exeSQL);
    
    if ($item) {
        $itemCode = $item['code'];
        $itemName = $item['name'];
        $itemBrandId = $item['brand_id'];
        $itemCategoryId = $item['category_id'];
        $itemStatus = $item['status'];
        $itemAttachment = $item['attachment'];
    }
}

// Get all active brands
$brandsSQL = "SELECT * FROM master_brand WHERE status = 'Active' ORDER BY name";
$brandsResult = mysqli_query($conn, $brandsSQL) or die(mysqli_error($conn));

// Get all active categories
$categoriesSQL = "SELECT * FROM master_category WHERE status = 'Active' ORDER BY name";
$categoriesResult = mysqli_query($conn, $categoriesSQL) or die(mysqli_error($conn));

// Display form for adding/editing item
echo "<div class='formStyle'>";
echo "<form method='post' action='".($editMode ? "item_update.php" : "item_add.php")."' enctype='multipart/form-data'>";
if ($editMode) {
    echo "<input type='hidden' name='item_id' value='".$itemId."'>";
}
echo "<div class='element'><label for='code'>Code:</label>";
echo "<input type='text' name='code' id='code' value='".$itemCode."' required></div>";

echo "<div class='element'><label for='name'>Name:</label>";
echo "<input type='text' name='name' id='name' value='".$itemName."' required></div>";

echo "<div class='element'><label for='brand_id'>Brand:</label>";
echo "<select name='brand_id' id='brand_id' required>";
echo "<option value=''>Select Brand</option>";
while ($brand = mysqli_fetch_array($brandsResult)) {
    $selected = ($brand['id'] == $itemBrandId) ? "selected" : "";
    echo "<option value='".$brand['id']."' ".$selected.">".$brand['name']."</option>";
}
echo "</select></div>";

echo "<div class='element'><label for='category_id'>Category:</label>";
echo "<select name='category_id' id='category_id' required>";
echo "<option value=''>Select Category</option>";
while ($category = mysqli_fetch_array($categoriesResult)) {
    $selected = ($category['id'] == $itemCategoryId) ? "selected" : "";
    echo "<option value='".$category['id']."' ".$selected.">".$category['name']."</option>";
}
echo "</select></div>";

if ($editMode) {
    echo "<div class='element'><label for='status'>Status:</label>";
    echo "<select name='status' id='status'>";
    echo "<option value='Active' ".($itemStatus == "Active" ? "selected" : "").">Active</option>";
    echo "<option value='Inactive' ".($itemStatus == "Inactive" ? "selected" : "").">Inactive</option>";
    echo "</select></div>";
}

echo "<div class='element'><label for='attachment'>Image:</label>";
echo "<input type='file' name='attachment' id='attachment'>";
if ($editMode && !empty($itemAttachment)) {
    echo "<br><small>Current image: ".$itemAttachment."</small>";
    echo "<input type='hidden' name='current_attachment' value='".$itemAttachment."'>";
}
echo "</div>";

echo "<div class='element'>";
echo "<input type='submit' value='".($editMode ? "Update" : "Add")." Item' id='submitbtn'>";
echo "<a href='dashboard.php' class='btn'>Cancel</a>";
echo "</div>";
echo "</form>";
echo "</div>";

// Pagination settings
$itemsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Get total number of items
$countSQL = "SELECT COUNT(*) as total FROM master_item";
$countResult = mysqli_query($conn, $countSQL);
$count = mysqli_fetch_assoc($countResult);
$totalItems = $count['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch items with brand and category names
$SQL = "SELECT i.*, b.name as brand_name, c.name as category_name 
        FROM master_item i
        LEFT JOIN master_brand b ON i.brand_id = b.id
        LEFT JOIN master_category c ON i.category_id = c.id
        ORDER BY i.id DESC LIMIT $offset, $itemsPerPage";
$exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));

// Display items
echo "<div class='container'>";
echo "<h5>Items</h5>";

if (mysqli_num_rows($exeSQL) > 0) {
    echo "<table id='indextable'>";
    echo "<tr>";
    echo "<th>Code</th>";
    echo "<th>Name</th>";
    echo "<th>Brand</th>";
    echo "<th>Category</th>";
    echo "<th>Status</th>";
    echo "<th>Image</th>";
    echo "<th>Created At</th>";
    echo "<th>Updated At</th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    
    while ($item = mysqli_fetch_array($exeSQL)) {
        echo "<tr>";
        echo "<td>".$item['code']."</td>";
        echo "<td>".$item['name']."</td>";
        echo "<td>".$item['brand_name']."</td>";
        echo "<td>".$item['category_name']."</td>";
        echo "<td>".$item['status']."</td>";
        echo "<td>";
        if (!empty($item['attachment'])) {
            echo "<img src='uploads/".$item['attachment']."' width='50' height='50'>";
        } else {
            echo "No image";
        }
        echo "</td>";
        echo "<td>".$item['created_at']."</td>";
        echo "<td>".$item['updated_at']."</td>";
        echo "<td>";
        echo "<a href='item.php?id=".$item['id']."' class='btn'>Edit</a> ";
        echo "<a href='item_delete.php?id=".$item['id']."' class='btn' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Pagination links
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a id='number' href='item.php?page=$i' ".($page == $i ? "class='active'" : "").">$i</a> ";
    }
    echo "</div>";
} else {
    echo "<p>No items found. Add your first item using the form above.</p>";
}

echo "<a href='dashboard.php' class='btn'>Back to Dashboard</a>";
echo "</div>";

include("footfile.html"); // include head layout
echo "</body>";
?>