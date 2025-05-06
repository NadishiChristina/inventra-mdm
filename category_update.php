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
$category_id = mysqli_real_escape_string($conn, trim($_POST['category_id']));
$code = mysqli_real_escape_string($conn, trim($_POST['code']));
$name = mysqli_real_escape_string($conn, trim($_POST['name']));
$status = mysqli_real_escape_string($conn, trim($_POST['status']));
$updated_at = date('Y-m-d H:i:s');

// Verify the category belongs to the current user
$verifySQL = "SELECT * FROM master_category WHERE id = '$category_id' AND user_id = '$user_id'";
$verifyResult = mysqli_query($conn, $verifySQL);

if (mysqli_num_rows($verifyResult) == 0) {
    // Category not found or doesn't belong to user
    echo "<script>alert('Category not found or you don\\'t have permission to update it.'); window.location.href='category.php';</script>";
    exit();
}

// Check if code or name already exists for other categories of this user
$checkSQL = "SELECT * FROM master_category WHERE (code = '$code' OR name = '$name') AND id != '$category_id' AND user_id = '$user_id'";
$checkResult = mysqli_query($conn, $checkSQL);

if (mysqli_num_rows($checkResult) > 0) {
    // Category with same code or name already exists
    echo "<script>alert('Another category with this code or name already exists!'); window.location.href='category.php?id=$category_id';</script>";
    exit();
}

// Update category
$SQL = "UPDATE master_category 
        SET code = '$code', name = '$name', status = '$status', updated_at = '$updated_at' 
        WHERE id = '$category_id' AND user_id = '$user_id'";

if (mysqli_query($conn, $SQL)) {
    // Success, redirect to category page
    echo "<script>alert('Category updated successfully!'); window.location.href='category.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='category.php?id=$category_id';</script>";
}
?>