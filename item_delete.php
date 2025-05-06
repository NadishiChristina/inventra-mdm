<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Get item ID from URL
$item_id = mysqli_real_escape_string($conn, $_GET['id']);

$SQL = "SELECT attachment FROM master_item WHERE id = '$item_id'";
$result = mysqli_query($conn, $SQL);
$item = mysqli_fetch_assoc($result);
$attachment = $item['attachment'];

// Delete item
$SQL = "DELETE FROM master_item WHERE id = '$item_id'";

if (mysqli_query($conn, $SQL)) {
    if(!empty($attachment)) {
        $file_path = "uploads/" . $attachment;
        if(file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Success, redirect to dashboard
    echo "<script>alert('Item deleted successfully!'); window.location.href='dashboard.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='dashboard.php';</script>";
}
?>