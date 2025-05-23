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

// Get category ID from URL
$category_id = mysqli_real_escape_string($conn, $_GET['id']);

// Verify the category belongs to the current user
$verifySQL = "SELECT * FROM master_category WHERE id = '$category_id' AND user_id = '$user_id'";
$verifyResult = mysqli_query($conn, $verifySQL);

if (mysqli_num_rows($verifyResult) == 0) {
    // Category not found or doesn't belong to user
    echo "<script>alert('Category not found or you don\\'t have permission to delete it.'); window.location.href='category.php';</script>";
    exit();
}

// Check if category is used in any items by this user
$checkSQL = "SELECT COUNT(*) as count FROM master_item WHERE category_id = '$category_id' AND user_id = '$user_id'";
$checkResult = mysqli_query($conn, $checkSQL);
$checkData = mysqli_fetch_assoc($checkResult);

if ($checkData['count'] > 0) {
    // Category is in use, cannot delete
    echo "<script>alert('This category cannot be deleted because it is used by some items.'); window.location.href='category.php';</script>";
    exit();
}

// Delete category
$SQL = "DELETE FROM master_category WHERE id = '$category_id' AND user_id = '$user_id'";

if (mysqli_query($conn, $SQL)) {
    // Success, redirect to category page
    echo "<script>alert('Category deleted successfully!'); window.location.href='category.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='category.php';</script>";
}
?>