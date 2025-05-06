<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$category_id = mysqli_real_escape_string($conn, trim($_POST['category_id']));
$code = mysqli_real_escape_string($conn, trim($_POST['code']));
$name = mysqli_real_escape_string($conn, trim($_POST['name']));
$status = mysqli_real_escape_string($conn, trim($_POST['status']));
$updated_at = date('Y-m-d H:i:s');

// Check if code or name already exists for other categories
$checkSQL = "SELECT * FROM master_category WHERE (code = '$code' OR name = '$name') AND id != '$category_id'";
$checkResult = mysqli_query($conn, $checkSQL);

if (mysqli_num_rows($checkResult) > 0) {
    // Category with same code or name already exists
    echo "<script>alert('Another category with this code or name already exists!'); window.location.href='category.php?id=$category_id';</script>";
    exit();
}

// Update category
$SQL = "UPDATE master_category 
        SET code = '$code', name = '$name', status = '$status', updated_at = '$updated_at' 
        WHERE id = '$category_id'";

if (mysqli_query($conn, $SQL)) {
    // Success, redirect to category page
    echo "<script>alert('Category updated successfully!'); window.location.href='category.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='category.php?id=$category_id';</script>";
}
?>