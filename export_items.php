<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Check if export format is specified
if (!isset($_POST['export_format'])) {
    header("Location: item.php");
    exit();
}

$exportFormat = $_POST['export_format'];

$whereClause = "1=1"; // Always true condition to start

// Apply search filter
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $whereClause .= " AND (i.code LIKE '%$search%' OR i.name LIKE '%$search%')";
}

// Apply status filter
if (isset($_POST['status_filter']) && !empty($_POST['status_filter'])) {
    $statusFilter = mysqli_real_escape_string($conn, $_POST['status_filter']);
    $whereClause .= " AND i.status = '$statusFilter'";
}

// Fetch all items with brand and category names that match the filter
$SQL = "SELECT i.id, i.code, i.name, b.name as brand_name, c.name as category_name, i.status, i.created_at, i.updated_at 
        FROM master_item i
        LEFT JOIN master_brand b ON i.brand_id = b.id
        LEFT JOIN master_category c ON i.category_id = c.id
        WHERE $whereClause
        ORDER BY i.id DESC";
$exeSQL = mysqli_query($conn, $SQL) or die(mysqli_error($conn));

// Create array of data
$items = [];
$items[] = ['ID', 'Code', 'Name', 'Brand', 'Category', 'Status', 'Created At', 'Updated At']; // Headers

while ($item = mysqli_fetch_array($exeSQL)) {
    $items[] = [
        $item['id'],
        $item['code'],
        $item['name'],
        $item['brand_name'],
        $item['category_name'],
        $item['status'],
        $item['created_at'],
        $item['updated_at']
    ];
}

// Generate output based on requested format
switch ($exportFormat) {
    case 'csv':
        exportCSV($items);
        break;
    case 'excel':
        exportExcel($items);
        break;
    case 'pdf':
        exportPDF($items);
        break;
    default:
        header("Location: item.php");
        exit();
}

// Function to export as CSV
function exportCSV($items) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="items_export_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    foreach ($items as $item) {
        fputcsv($output, $item);
    }
    
    fclose($output);
    exit();
}

// Function to export as Excel
function exportExcel($items) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="items_export_' . date('Y-m-d') . '.xls"');
    
    echo '<table border="1">';
    foreach ($items as $item) {
        echo '<tr>';
        foreach ($item as $cell) {
            echo '<td>' . htmlspecialchars($cell) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    exit();
}

// Function to export as PDF
function exportPDF($items) {        
    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="items_export_' . date('Y-m-d') . '.html"');
    
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Items Export</title>
        <style>
            body { font-family: Arial, sans-serif; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            h1 { text-align: center; }
        </style>
    </head>
    <body>
        <h1>Items Export - ' . date('Y-m-d') . '</h1>
        <table>';
    
    $firstRow = true;
    foreach ($items as $item) {
        if ($firstRow) {
            echo '<tr>';
            foreach ($item as $header) {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
            echo '</tr>';
            $firstRow = false;
        } else {
            echo '<tr>';
            foreach ($item as $cell) {
                echo '<td>' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }
    }
    
    echo '</table>
        <p>Note: Save this page as PDF using your browser\'s print function.</p>
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    </body>
    </html>';
    exit();
}
?>