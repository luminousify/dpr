<?php
/**
 * Migration script to import mp_standar data from Excel to t_product table
 * Excel file: C:\Users\Pratama\AppData\Roaming\Output Messenger\CDJAAA\Received Files\202510\MP Actual.xls
 * Excel columns: product_code, mp_std
 */

// Database configuration
$host = '192.168.1.202';
$username = 'endang';
$password = 'endangthea0';
$database = 'dpr_injection_new';

// Excel file path
$excelFile = 'C:\Users\Pratama\AppData\Roaming\Output Messenger\CDJAAA\Received Files\202510\MP Actual.xls';

// Create database connection
$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Connected to database successfully\n";

// Check if Excel file exists
if (!file_exists($excelFile)) {
    die("Excel file not found: " . $excelFile . "\n");
}

echo "Excel file found: " . basename($excelFile) . "\n";

// Load PHPExcel (using existing library in project)
require_once 'application/libraries/PHPExcelOld/PHPExcel.php';
require_once 'application/libraries/PHPExcelOld/PHPExcel/IOFactory.php';

try {
    // Load Excel file
    $spreadsheet = IOFactory::load($excelFile);
    $worksheet = $spreadsheet->getActiveSheet();
    
    // Get the highest row and column numbers
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    
    echo "Processing $highestRow rows from Excel file...\n";
    
    // Prepare update statement
    $stmt = $mysqli->prepare("UPDATE t_product SET mp_standar = ? WHERE kode_product = ?");
    
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error . "\n");
    }
    
    $updatedCount = 0;
    $notFoundCount = 0;
    
    // Start from row 2 (assuming row 1 is header)
    for ($row = 2; $row <= $highestRow; $row++) {
        // Get product_code and mp_std values
        $productCode = $worksheet->getCell('A' . $row)->getValue(); // Column A: product_code
        $mpStd = $worksheet->getCell('B' . $row)->getValue();     // Column B: mp_std
        
        // Skip empty rows
        if (empty($productCode) || $productCode === null) {
            continue;
        }
        
        // Validate mp_std value (should be 0.5, 1.0, etc.)
        if ($mpStd !== null && $mpStd !== '') {
            // Convert to decimal and validate
            $mpStd = (float)$mpStd;
            
            // Only allow specific values (0.5, 1.0, 1.5, 2.0, etc.)
            if ($mpStd > 0 && $mpStd <= 10) {
                $stmt->bind_param("ds", $mpStd, $productCode);
                
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $updatedCount++;
                        echo "Updated: $productCode -> $mpStd\n";
                    }
                } else {
                    echo "Failed to update: $productCode\n";
                }
            } else {
                echo "Invalid mp_std value for $productCode: $mpStd\n";
            }
        }
    }
    
    $stmt->close();
    
    echo "\nMigration Summary:\n";
    echo "- Total rows processed: " . ($highestRow - 1) . "\n";
    echo "- Products updated: $updatedCount\n";
    echo "- Products not found in database: $notFoundCount\n";
    
    // Verify some updated records
    echo "\nVerifying updated records:\n";
    $result = $mysqli->query("SELECT kode_product, nama_product, mp_standar FROM t_product WHERE mp_standar IS NOT NULL ORDER BY kode_product LIMIT 10");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "- {$row['kode_product']}: {$row['nama_product']} -> {$row['mp_standar']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error processing Excel file: " . $e->getMessage() . "\n";
}

// Close connection
$mysqli->close();

echo "\nMigration completed!\n";
?>