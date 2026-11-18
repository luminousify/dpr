<?php
/**
 * Simple migration script to import mp_standar data from Excel to t_product table
 * Uses basic PHP Excel reading without external libraries
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

// Simple Excel reading using CSV conversion (more reliable)
function readExcelAsCSV($excelFile) {
    // For simple Excel reading, we'll convert to CSV format first
    // This is a workaround for environments without proper Excel libraries
    
    // Try to use COM object for Excel conversion (Windows only)
    if (class_exists('COM')) {
        try {
            $excel = new COM("Excel.Application");
            $excel->Workbooks->Open($excelFile);
            $workbook = $excel->Workbooks(1);
            $worksheet = $workbook->Worksheets(1);
            
            // Save as temporary CSV
            $tempCsv = tempnam(sys_get_temp_dir(), 'excel_import_') . '.csv';
            $worksheet->SaveAs($tempCsv, 6); // 6 = xlCSV
            
            // Read the CSV file
            $data = array();
            if (($handle = fopen($tempCsv, 'r')) !== FALSE) {
                $headers = fgetcsv($handle);
                $row = 1; // Skip header row
                
                while (($data = fgetcsv($handle)) !== FALSE) {
                    if (count($data) >= 2 && !empty($data[0]) && !empty($data[1])) {
                        $productCode = trim($data[0]);
                        $mpStd = trim($data[1]);
                        
                        if (!empty($productCode)) {
                            $data[] = array(
                                'product_code' => $productCode,
                                'mp_std' => $mpStd
                            );
                        }
                    }
                    $row++;
                }
                fclose($handle);
            }
            
            // Clean up
            $excel->Quit();
            unset($excel);
            unlink($tempCsv);
            
            return $data;
        } catch (Exception $e) {
            echo "COM Excel conversion failed: " . $e->getMessage() . "\n";
            return array();
        }
    }
    
    return array();
}

// Read Excel data
$excelData = readExcelAsCSV($excelFile);

if (empty($excelData)) {
    echo "No data found in Excel file\n";
    $mysqli->close();
    exit;
}

echo "Processing " . count($excelData) . " rows from Excel file...\n";

// Prepare update statement
$stmt = $mysqli->prepare("UPDATE t_product SET mp_standar = ? WHERE kode_product = ?");

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error . "\n");
}

$updatedCount = 0;
$notFoundCount = 0;

foreach ($excelData as $row) {
    $productCode = $row['product_code'];
    $mpStd = $row['mp_std'];
    
    // Skip empty rows
    if (empty($productCode)) {
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
                } else {
                    echo "Not found in database: $productCode\n";
                    $notFoundCount++;
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
echo "- Total rows processed: " . count($excelData) . "\n";
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

// Close connection
$mysqli->close();

echo "\nMigration completed!\n";
?>