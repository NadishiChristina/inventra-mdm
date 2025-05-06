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

// Get the export format
$export_format = isset($_POST['export_format']) ? $_POST['export_format'] : 'csv';

// Initialize where clause with user_id for role-based access
$whereClause = "i.user_id = '$user_id'";

// Apply search filter if provided
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $whereClause .= " AND (i.code LIKE '%$search%' OR i.name LIKE '%$search%')";
}

// Apply status filter if provided
if (isset($_POST['status_filter']) && !empty($_POST['status_filter'])) {
    $statusFilter = mysqli_real_escape_string($conn, $_POST['status_filter']);
    $whereClause .= " AND i.status = '$statusFilter'";
}

// Fetch items with brand and category names
$SQL = "SELECT i.code, i.name, b.name as brand_name, c.name as category_name, 
        i.status, i.created_at, i.updated_at
        FROM master_item i
        LEFT JOIN master_brand b ON i.brand_id = b.id
        LEFT JOIN master_category c ON i.category_id = c.id
        WHERE $whereClause
        ORDER BY i.id DESC";
        
$result = mysqli_query($conn, $SQL) or die(mysqli_error($conn));

// Create filename
$filename = 'items_export_' . date('Y-m-d') . '.' . $export_format;

// Process data according to export format
switch ($export_format) {
    case 'csv':
        // Set the appropriate headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array('Code', 'Name', 'Brand', 'Category', 'Status', 'Created At', 'Updated At'));
        
        // Add data rows
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
        
        // Close the output stream
        fclose($output);
        break;
        
    case 'excel':
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo "Code\tName\tBrand\tCategory\tStatus\tCreated At\tUpdated At\n";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['code'] . "\t" . $row['name'] . "\t" . $row['brand_name'] . "\t" . 
                 $row['category_name'] . "\t" . $row['status'] . "\t" . 
                 $row['created_at'] . "\t" . $row['updated_at'] . "\n";
        }
        break;
        
    case 'pdf':
        // For PDF, you need to use a library like TCPDF or FPDF
        echo "PDF export functionality requires a PDF generation library to be installed.";
        echo "<br><a href='item.php'>Go back to Items</a>";
        break;
        
    default:
        // Redirect back if invalid format
        header('Location: item.php');
        exit();
}

exit();
?>