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
$code = mysqli_real_escape_string($conn, trim($_POST['code']));
$name = mysqli_real_escape_string($conn, trim($_POST['name']));
$status = 'Active'; // Default for new categories
$created_at = date('Y-m-d H:i:s');
$updated_at = date('Y-m-d H:i:s');

// Check if code or name already exists for this user
$checkSQL = "SELECT * FROM master_category WHERE (code = '$code' OR name = '$name') AND user_id = '$user_id'";
$checkResult = mysqli_query($conn, $checkSQL);

if (mysqli_num_rows($checkResult) > 0) {
    // Category with same code or name already exists
    echo "<script>alert('Category with this code or name already exists!'); window.location.href='category.php';</script>";
    exit();
}

// Insert new category with user_id
$SQL = "INSERT INTO master_category (user_id, code, name, status, created_at, updated_at) 
        VALUES ('$user_id', '$code', '$name', '$status', '$created_at', '$updated_at')";

if (mysqli_query($conn, $SQL)) {
    // Success, redirect to category page
    echo "<script>alert('Category added successfully!'); window.location.href='category.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='category.php';</script>";
}
?>