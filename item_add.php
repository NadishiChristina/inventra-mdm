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

$code = mysqli_real_escape_string($conn, trim($_POST['code']));
$name = mysqli_real_escape_string($conn, trim($_POST['name']));
$brand_id = mysqli_real_escape_string($conn, trim($_POST['brand_id']));
$category_id = mysqli_real_escape_string($conn, trim($_POST['category_id']));
$status = 'Active'; // Default for new items
$created_at = date('Y-m-d H:i:s');
$updated_at = date('Y-m-d H:i:s');

// Verify that brand and category belong to current user
$checkBrandSQL = "SELECT * FROM master_brand WHERE id = '$brand_id' AND user_id = '$user_id'";
$checkBrandResult = mysqli_query($conn, $checkBrandSQL);

$checkCategorySQL = "SELECT * FROM master_category WHERE id = '$category_id' AND user_id = '$user_id'";
$checkCategoryResult = mysqli_query($conn, $checkCategorySQL);

if (mysqli_num_rows($checkBrandResult) == 0 || mysqli_num_rows($checkCategoryResult) == 0) {
    echo "<script>alert('Invalid brand or category selection.'); window.location.href='item.php';</script>";
    exit();
}

// Handle file upload
$attachment = "";
if(isset($_FILES['attachment']) && $_FILES['attachment']['size'] > 0) {
    $target_dir = "uploads/";
    
    // Create upload directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if file is an actual image
    $check = getimagesize($_FILES["attachment"]["tmp_name"]);
    if($check === false) {
        echo "<script>alert('File is not an image.'); window.location.href='item.php';</script>";
        exit();
    }
    
    // Check file size (max 2MB)
    if ($_FILES["attachment"]["size"] > 2000000) {
        echo "<script>alert('Sorry, your file is too large. Max size is 2MB.'); window.location.href='item.php';</script>";
        exit();
    }
    
    // Allow certain file formats
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
    if(!in_array(strtolower($file_extension), $allowed_extensions)) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.'); window.location.href='item.php';</script>";
        exit();
    }
    
    // Upload file
    if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
        $attachment = $new_filename;
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.'); window.location.href='item.php';</script>";
        exit();
    }
}

// Check if code already exists for current user
$checkSQL = "SELECT * FROM master_item WHERE code = '$code' AND user_id = '$user_id'";
$checkResult = mysqli_query($conn, $checkSQL);

if (mysqli_num_rows($checkResult) > 0) {
    // Error if item with same code already exists
    echo "<script>alert('Item with this code already exists!'); window.location.href='item.php';</script>";
    exit();
}

// Insert new item with user_id
$SQL = "INSERT INTO master_item (brand_id, category_id, code, name, attachment, status, user_id, created_at, updated_at) 
        VALUES ('$brand_id', '$category_id', '$code', '$name', '$attachment', '$status', '$user_id', '$created_at', '$updated_at')";

if (mysqli_query($conn, $SQL)) {
    // Success
    echo "<script>alert('Item added successfully!'); window.location.href='item.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='item.php';</script>";
}
?>