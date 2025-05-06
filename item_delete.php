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

// Get item ID from URL
$item_id = mysqli_real_escape_string($conn, $_GET['id']);

// Verify the item belongs to the current user
$SQL = "SELECT attachment, user_id FROM master_item WHERE id = '$item_id'";
$result = mysqli_query($conn, $SQL);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Item not found.'); window.location.href='dashboard.php';</script>";
    exit();
}

$item = mysqli_fetch_assoc($result);

// Check if the item belongs to the current user
if ($item['user_id'] != $user_id) {
    echo "<script>alert('You do not have permission to delete this item.'); window.location.href='dashboard.php';</script>";
    exit();
}

$attachment = $item['attachment'];

// Delete item with user_id check
$SQL = "DELETE FROM master_item WHERE id = '$item_id' AND user_id = '$user_id'";

if (mysqli_query($conn, $SQL)) {
    if(!empty($attachment)) {
        $file_path = "uploads/" . $attachment;
        if(file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Success, redirect to dashboard
    echo "<script>alert('Item deleted successfully!'); window.location.href='item.php';</script>";
} else {
    // Error
    echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='dashboard.php';</script>";
}
?>