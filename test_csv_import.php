<?php
/**
 * Test CSV Import Script
 * This script tests the CSV import functionality before running the actual import
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = '192.168.1.202';
$user = 'endang';
$pass = 'endangthea0';
$dbname = 'dpr_injection_new';

// CSV file path
$csv_file = 'C:\Users\Pratama\Downloads\dpr tgl 2 - Sheet1.csv';

echo "<h2>DPR CSV Import Test</h2>";
echo "<hr>";

// Test 1: Check if CSV file exists
echo "<h3>Test 1: CSV File Access</h3>";
if (file_exists($csv_file)) {
    echo "<p style='color: green;'>✓ CSV file found: " . basename($csv_file) . "</p>";
    echo "<p>File size: " . filesize($csv_file) . " bytes</p>";
} else {
    echo "<p style='color: red;'>✗ CSV file not found: $csv_file</p>";
    exit;
}

// Test 2: Parse CSV headers
echo "<h3>Test 2: CSV Headers</h3>";
if (($handle = fopen($csv_file, "r")) !== FALSE) {
    $headers = fgetcsv($handle, 0, ",");
    echo "<p>Total columns: " . count($headers) . "</p>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>#</th><th>Column Name</th></tr>";
    foreach ($headers as $index => $header) {
        echo "<tr><td>" . ($index + 1) . "</td><td>" . htmlspecialchars($header) . "</td></tr>";
    }
    echo "</table>";
    fclose($handle);
}

// Test 3: Parse first 5 data rows
echo "<h3>Test 3: First 5 Data Rows</h3>";
if (($handle = fopen($csv_file, "r")) !== FALSE) {
    fgetcsv($handle, 0, ","); // Skip headers

    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>No.</th><th>Tanggal</th><th>Mesin</th><th>Shift</th><th>Product ID</th><th>OK</th><th>NG</th><th>Operator</th></tr>";

    $row_count = 0;
    while (($rowData = fgetcsv($handle, 0, ",")) !== FALSE && $row_count < 5) {
        $row = array_combine($headers, $rowData);
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['No.']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Tanggal']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Mesin']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Shift']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Product ID']) . "</td>";
        echo "<td>" . htmlspecialchars($row['OK']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Total Defect']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Operator']) . "</td>";
        echo "</tr>";
        $row_count++;
    }
    echo "</table>";
    fclose($handle);
}

// Test 4: Database connection
echo "<h3>Test 4: Database Connection</h3>";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    echo "<p>Database: $dbname</p>";
}

// Test 5: Check required tables
echo "<h3>Test 5: Required Tables</h3>";
$required_tables = [
    't_production_op',
    't_production_op_dl',
    't_production_op_cutting_tools_usage',
    'select_bom',
    'cutting_tools',
    't_operator'
];

foreach ($required_tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Table '$table' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Table '$table' NOT FOUND</p>";
    }
}

// Test 6: Sample data validation
echo "<h3>Test 6: Sample Data Validation</h3>";
if (($handle = fopen($csv_file, "r")) !== FALSE) {
    fgetcsv($handle, 0, ","); // Skip headers
    $rowData = fgetcsv($handle, 0, ",");
    $row = array_combine($headers, $rowData);

    // Test decimal conversion
    $test_decimals = ['W H', 'O T', 'CT Std', 'CT Actual'];
    echo "<h4>Decimal Conversion Test:</h4>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Original</th><th>Converted</th></tr>";
    foreach ($test_decimals as $field) {
        $original = isset($row[$field]) ? $row[$field] : '';
        $converted = str_replace(',', '.', $original);
        $converted = floatval($converted);
        echo "<tr><td>$field</td><td>" . htmlspecialchars($original) . "</td><td>" . $converted . "</td></tr>";
    }
    echo "</table>";

    // Test Product ID lookup
    echo "<h4>Product ID Lookup Test:</h4>";
    $product_id = trim($row['Product ID']);
    $product_id_clean = str_replace('.', '', $product_id); // Remove dots for lookup

    $query = "SELECT id_bom, kp_pr, np_pr FROM select_bom WHERE kp_pr = '$product_id_clean' LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $bom = $result->fetch_assoc();
        echo "<p style='color: green;'>✓ Product ID found: " . htmlspecialchars($product_id) . " (cleaned: $product_id_clean)</p>";
        echo "<p>BOM ID: " . $bom['id_bom'] . "</p>";
        echo "<p>Product Name: " . htmlspecialchars($bom['np_pr']) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ Product ID NOT FOUND: " . htmlspecialchars($product_id) . " (cleaned: $product_id_clean)</p>";
    }

    fclose($handle);
}

// Test 7: Check for NG and LT columns
echo "<h3>Test 7: NG and LT Column Detection</h3>";
$ng_count = 0;
$lt_count = 0;

foreach ($headers as $header) {
    if (strpos($header, 'NG-') === 0) {
        $ng_count++;
    }
    if (strpos($header, 'LT-') === 0) {
        $lt_count++;
    }
}

echo "<p>Total NG columns: $ng_count</p>";
echo "<p>Total LT columns: $lt_count</p>";

// Test 8: Estimate import time
echo "<h3>Test 8: Import Time Estimation</h3>";
$total_rows = 0;
if (($handle = fopen($csv_file, "r")) !== FALSE) {
    fgetcsv($handle, 0, ","); // Skip headers
    while (fgetcsv($handle, 0, ",")) {
        $total_rows++;
    }
    fclose($handle);
}

$estimated_time_seconds = $total_rows * 0.5; // Estimate 0.5 seconds per row
$estimated_time_minutes = round($estimated_time_seconds / 60, 2);

echo "<p>Total rows to import: <strong>$total_rows</strong></p>";
echo "<p>Estimated time: <strong>$estimated_time_minutes minutes</strong></p>";

echo "<hr>";
echo "<h3>Test Summary</h3>";
echo "<p>All tests completed. If all tests show green checkmarks (✓), your CSV is ready for import.</p>";
echo "<p><a href='" . base_url('c_import_csv/index') . "' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Proceed to Import</a></p>";

$conn->close();
?>
