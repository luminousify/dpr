<?php
/**
 * Update t_product table with mp_standar values from CSV
 * CSV: C:\Users\Pratama\Downloads\mp_std - Sheet1.csv
 */

// Database configuration
$host = '192.168.1.202';
$user = 'endang';
$pass = 'endangthea0';
$db   = 'dpr_injection_new';

// CSV file path
$csvFile = 'C:\Users\Pratama\Downloads\mp_std - Sheet1.csv';

echo "=== Update t_product with mp_standar values ===\n\n";

try {
    // Connect to database
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "✓ Connected to database\n";
    
    // Check if CSV file exists
    if (!file_exists($csvFile)) {
        throw new Exception("CSV file not found: $csvFile");
    }
    
    echo "✓ CSV file found\n";
    
    // Create temporary table
    $createTemp = "CREATE TEMPORARY TABLE IF NOT EXISTS temp_mp_standar (
        kode_product VARCHAR(255),
        mp_standar DECIMAL(10,2)
    )";
    
    if (!$conn->query($createTemp)) {
        throw new Exception("Failed to create temp table: " . $conn->error);
    }
    
    echo "✓ Temporary table created\n";
    
    // Read and parse CSV file
    $handle = fopen($csvFile, 'r');
    if ($handle === FALSE) {
        throw new Exception("Cannot open CSV file");
    }
    
    // Read header
    $header = fgetcsv($handle);
    echo "✓ CSV header: " . implode(', ', $header) . "\n";
    
    // Prepare insert statement
    $stmt = $conn->prepare("INSERT INTO temp_mp_standar (kode_product, mp_standar) VALUES (?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $count = 0;
    $conn->begin_transaction();
    
    // Read data rows
    while (($row = fgetcsv($handle)) !== FALSE) {
        if (count($row) >= 2) {
            $kodeProduct = trim($row[0]);
            $mpStandarRaw = trim($row[1]);
            
            // Convert comma to dot for decimal
            $mpStandar = str_replace(',', '.', $mpStandarRaw);
            
            $stmt->bind_param('sd', $kodeProduct, $mpStandar);
            
            if (!$stmt->execute()) {
                echo "Warning: Failed to insert $kodeProduct: " . $stmt->error . "\n";
            } else {
                $count++;
            }
        }
    }
    
    fclose($handle);
    $conn->commit();
    
    echo "✓ Imported $count records into temporary table\n\n";
    
    // Update t_product table
    echo "Updating t_product table...\n";
    $updateSql = "UPDATE t_product t
                  INNER JOIN temp_mp_standar tmp ON t.kode_product = tmp.kode_product
                  SET t.mp_standar = tmp.mp_standar";
    
    if (!$conn->query($updateSql)) {
        throw new Exception("Update failed: " . $conn->error);
    }
    
    $updatedRows = $conn->affected_rows;
    echo "✓ Updated $updatedRows records in t_product\n\n";
    
    // Show sample of updated records
    echo "Sample of updated records:\n";
    $result = $conn->query("SELECT kode_product, mp_standar FROM t_product WHERE mp_standar IS NOT NULL ORDER BY kode_product LIMIT 10");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo sprintf("  - %s: %.2f\n", $row['kode_product'], $row['mp_standar']);
        }
    }
    
    echo "\n";
    
    // Check for products in CSV that don't exist in t_product
    $checkMissing = "SELECT tmp.kode_product 
                     FROM temp_mp_standar tmp
                     LEFT JOIN t_product t ON tmp.kode_product = t.kode_product
                     WHERE t.kode_product IS NULL
                     LIMIT 20";
    
    $missing = $conn->query($checkMissing);
    if ($missing && $missing->num_rows > 0) {
        echo "⚠ Products in CSV not found in t_product (first 20):\n";
        while ($row = $missing->fetch_assoc()) {
            echo "  - " . $row['kode_product'] . "\n";
        }
    } else {
        echo "✓ All products in CSV exist in t_product\n";
    }
    
    echo "\n";
    
    // Statistics
    $totalProducts = $conn->query("SELECT COUNT(*) as cnt FROM t_product WHERE mp_standar IS NOT NULL")->fetch_assoc()['cnt'];
    echo "Total products with mp_standar: $totalProducts\n";
    
    // Cleanup
    $conn->query("DROP TEMPORARY TABLE IF EXISTS temp_mp_standar");
    echo "✓ Temporary table dropped\n";
    
    $stmt->close();
    $conn->close();
    
    echo "\n=== Update completed successfully ===\n";
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    if (isset($conn)) {
        $conn->rollback();
        $conn->query("DROP TEMPORARY TABLE IF EXISTS temp_mp_standar");
        $conn->close();
    }
    exit(1);
}
?>
