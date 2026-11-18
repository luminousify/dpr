<?php
/**
 * Script to add sample mp_standar values for testing
 * This adds sample manpower standards to some products
 */

// Database configuration
$host = '192.168.1.202';
$username = 'endang';
$password = 'endangthea0';
$database = 'dpr_injection_new';

// Create database connection
$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Connected to database successfully\n";

// Sample data to add (product_code => mp_standar)
$sampleData = array(
    '169424400' => '1.0',    // ROLLER UPPER ASF
    '143908201' => '0.5',    // FLAP PAPER GUIDE
    '152258900' => '1.0',    // ROLLER DRIVEN PF ADF
    'E361002' => '0.5',     // POM Jupital F20-03 Natural
    'PSI-13-004' => '1.0',    // POM Jupital F20-03 Natural
);

// Prepare update statement
$stmt = $mysqli->prepare("UPDATE t_product SET mp_standar = ? WHERE kode_product = ?");

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error . "\n");
}

$updatedCount = 0;

foreach ($sampleData as $productCode => $mpStandar) {
    $stmt->bind_param("ds", $mpStandar, $productCode);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $updatedCount++;
            echo "Updated: $productCode -> $mpStandar\n";
        } else {
            echo "Not found in database: $productCode\n";
        }
    } else {
        echo "Failed to update: $productCode\n";
    }
}

$stmt->close();

echo "\nSample data update summary:\n";
echo "- Products updated: $updatedCount\n";

// Verify the updates
echo "\nVerifying updated records:\n";
$result = $mysqli->query("SELECT kode_product, nama_product, mp_standar FROM t_product WHERE mp_standar IS NOT NULL ORDER BY kode_product LIMIT 10");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['kode_product']}: {$row['nama_product']} -> {$row['mp_standar']}\n";
    }
}

// Close connection
$mysqli->close();

echo "\nSample data addition completed!\n";
?>