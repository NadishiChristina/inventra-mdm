<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID for role-based access
$user_id = $_SESSION['id'];

$pagename = "Item Management"; 
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; 
echo "<title>".$pagename."</title>"; 
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
    
    // Fetch item details - ensure item belongs to current user
    $SQL = "SELECT * FROM master_item WHERE id = '".$itemId."' AND user_id = '".$user_id."'";
    $exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));
    
    // If no matching item found (wrong ID or not owned by user), redirect
    if(mysqli_num_rows($exeSQL) == 0) {
        echo "<script>alert('Item not found or you don\'t have permission to edit it.'); window.location.href='dashboard.php';</script>";
        exit();
    }
    
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

// Get all active brands that belong to the current user
$brandsSQL = "SELECT * FROM master_brand WHERE status = 'Active' AND user_id = '".$user_id."' ORDER BY name";
$brandsResult = mysqli_query($conn, $brandsSQL) or die(mysqli_error($conn));

// Get all active categories that belong to the current user
$categoriesSQL = "SELECT * FROM master_category WHERE status = 'Active' AND user_id = '".$user_id."' ORDER BY name";
$categoriesResult = mysqli_query($conn, $categoriesSQL) or die(mysqli_error($conn));

// Display form for adding/editing item
echo "<div class='formStyle'>";
echo "<form method='post' action='".($editMode ? "item_update.php" : "item_add.php")."' enctype='multipart/form-data'>";
if ($editMode) {
    echo "<input type='hidden' name='item_id' value='".$itemId."'>";
}
echo "<div class='element'><label for='code'>Code:</label>";
echo "<input type='text' name='code' id='code' class='form-input' value='".$itemCode."' required></div>";

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

echo "<div class='container search-export-wrapper' style='margin-bottom: 20px;'>";

// SEARCH & FILTER FORM
echo "<form method='get' action='item.php' class='search-filter-form' style='display: flex; gap: 10px; flex-wrap: wrap; align-items: center;'>";

echo "<input type='text' name='search' placeholder='Search by code or name' value='".(isset($_GET['search']) ? htmlspecialchars($_GET['search']) : "")."' style='padding: 8px; width: 200px;'>";

echo "<select name='status_filter' style='padding: 8px;'>";
echo "<option value=''>All Statuses</option>";
echo "<option value='Active' ".(isset($_GET['status_filter']) && $_GET['status_filter'] == 'Active' ? 'selected' : '').">Active</option>";
echo "<option value='Inactive' ".(isset($_GET['status_filter']) && $_GET['status_filter'] == 'Inactive' ? 'selected' : '').">Inactive</option>";
echo "</select>";

echo "<div style='display: flex; gap: 1px;'>";
echo "<input type='submit' value='Search' class='btn' style='padding: 8px 16px;'>";
echo "<a href='item.php' class='btn' style='padding: 8px 16px; background-color: #f3f4f6; color: #333; text-decoration: none;'>Reset</a>";
echo "</div>";

echo "</form>";

// EXPORT FORM
echo "<form method='post' action='export_items.php' class='export-form' style='display: flex; gap: 10px; align-items: center; margin-top: 15px;'>";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    echo "<input type='hidden' name='search' value='".htmlspecialchars($_GET['search'])."'>";
}
if (isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
    echo "<input type='hidden' name='status_filter' value='".htmlspecialchars($_GET['status_filter'])."'>";
}

echo "<label for='export_format'>Export as:</label>";
echo "<select name='export_format' id='export_format' style='padding: 8px;'>";
echo "<option value='csv'>CSV</option>";
echo "<option value='excel'>Excel</option>";
echo "<option value='pdf'>PDF</option>";
echo "</select>";

echo "<input type='submit' value='Export' class='btn' style='padding: 8px 16px;'>";

echo "</form>";
echo "</div>"; 


// Pagination settings
$itemsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Add user_id filter to base WHERE clause for role-based access
$whereClause = "i.user_id = '$user_id'";

// Apply search filter
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $whereClause .= " AND (i.code LIKE '%$search%' OR i.name LIKE '%$search%')";
}

// Apply status filter
if (isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
    $statusFilter = mysqli_real_escape_string($conn, $_GET['status_filter']);
    $whereClause .= " AND i.status = '$statusFilter'";
}

// Get total number of items with applied filters
$countSQL = "SELECT COUNT(*) as total FROM master_item i WHERE $whereClause";
$countResult = mysqli_query($conn, $countSQL);
$count = mysqli_fetch_assoc($countResult);
$totalItems = $count['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch items with brand and category names and apply filters
$SQL = "SELECT i.*, b.name as brand_name, c.name as category_name 
        FROM master_item i
        LEFT JOIN master_brand b ON i.brand_id = b.id
        LEFT JOIN master_category c ON i.category_id = c.id
        WHERE $whereClause
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
    
    // Pagination links with search parameters
    echo "<div class='pagination'>";
    $paginationParams = "";
    if (isset($_GET['search'])) {
        $paginationParams .= "&search=" . urlencode($_GET['search']);
    }
    if (isset($_GET['status_filter'])) {
        $paginationParams .= "&status_filter=" . urlencode($_GET['status_filter']);
    }
    
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a id='number' href='item.php?page=$i$paginationParams' ".($page == $i ? "class='active'" : "").">$i</a> ";
    }
    echo "</div>";
} else {
    echo "<p>No items found. Add your first item using the form above or try a different search.</p>";
}

echo "<a href='dashboard.php' class='btn'>Back to Dashboard</a>";
echo "</div>";

include("footfile.html"); // include footer layout
echo "</body>";
?>