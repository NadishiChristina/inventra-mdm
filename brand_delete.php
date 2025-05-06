<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Get brand ID from URL
$brand_id = mysqli_real_escape_string($conn, $_GET['id']);

// Check if brand is used in any items
$checkSQL = "SELECT COUNT(*) as count FROM master_item WHERE brand_id = '$brand_id'";
$checkResult = mysqli_query($conn, $checkSQL);
$checkData = mysqli_fetch_assoc($checkResult);

if ($checkData['count'] > 0) {
    // If brand is in use, cannot delete
    echo "<script>alert('This brand cannot be deleted because it is used by some items.'); window.location.href='brand.php';</script>";
    exit();
}

// Delete brand
$SQL = "DELETE FROM master_brand WHERE id = '$brand_id'";

if (mysqli_query($conn, $SQL)) {
    // Success, redirect to brand page
    echo "<script>alert('Brand deleted successfully!'); window.location.href='brand.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='brand.php';</script>";
}
?>