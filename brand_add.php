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
$status = 'Active'; // Default for new brands
$created_at = date('Y-m-d H:i:s');
$updated_at = date('Y-m-d H:i:s');

// Check if code or name already exists for this user
$checkSQL = "SELECT * FROM master_brand WHERE (code = '$code' OR name = '$name') AND user_id = '$user_id'";
$checkResult = mysqli_query($conn, $checkSQL);

if (mysqli_num_rows($checkResult) > 0) {
    // Brand with same code or name already exists
    echo "<script>alert('Brand with this code or name already exists!'); window.location.href='brand.php';</script>";
    exit();
}

// Insert new brand with user_id
$SQL = "INSERT INTO master_brand (user_id, code, name, status, created_at, updated_at) 
        VALUES ('$user_id', '$code', '$name', '$status', '$created_at', '$updated_at')";

if (mysqli_query($conn, $SQL)) {
    // Success, redirect to brand page
    echo "<script>alert('Brand added successfully!'); window.location.href='brand.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='brand.php';</script>";
}
?>