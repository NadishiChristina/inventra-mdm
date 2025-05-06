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
$brand_id = mysqli_real_escape_string($conn, trim($_POST['brand_id']));
$code = mysqli_real_escape_string($conn, trim($_POST['code']));
$name = mysqli_real_escape_string($conn, trim($_POST['name']));
$status = mysqli_real_escape_string($conn, trim($_POST['status']));
$updated_at = date('Y-m-d H:i:s');

// Verify the brand belongs to the current user
$verifySQL = "SELECT * FROM master_brand WHERE id = '$brand_id' AND user_id = '$user_id'";
$verifyResult = mysqli_query($conn, $verifySQL);

if (mysqli_num_rows($verifyResult) == 0) {
    // Brand not found or doesn't belong to user
    echo "<script>alert('Brand not found or you don\\'t have permission to update it.'); window.location.href='brand.php';</script>";
    exit();
}

// Check if code or name already exists for other brands of this user
$checkSQL = "SELECT * FROM master_brand WHERE (code = '$code' OR name = '$name') AND id != '$brand_id' AND user_id = '$user_id'";
$checkResult = mysqli_query($conn, $checkSQL);

if (mysqli_num_rows($checkResult) > 0) {
    // If brand with same code or name already exists
    echo "<script>alert('Another brand with this code or name already exists!'); window.location.href='brand.php?id=$brand_id';</script>";
    exit();
}

// Update brand
$SQL = "UPDATE master_brand 
        SET code = '$code', name = '$name', status = '$status', updated_at = '$updated_at' 
        WHERE id = '$brand_id' AND user_id = '$user_id'";

if (mysqli_query($conn, $SQL)) {
    // Success, redirect to brand page
    echo "<script>alert('Brand updated successfully!'); window.location.href='brand.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='brand.php?id=$brand_id';</script>";
}
?>