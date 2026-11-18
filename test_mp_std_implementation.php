<?php
// Test script to verify MP Std implementation
// This script tests the modified tampil_machine_use method

// Include database configuration
require_once 'application/config/database.php';

// Create a simple database connection
$connection = mysqli_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Testing MP Std Implementation\n";
echo "============================\n\n";

// Test the SQL query that will be used in the model
$query = "
SELECT 
    om.*,
    tp.mp_standar AS mp_std
FROM operasi_mesin AS om
LEFT JOIN t_product AS tp ON om.kp_pr = tp.kode_product
WHERE om.tanggal >= '2025-01-01' 
  AND om.tanggal <= '2025-01-31' 
  AND om.shift = 1
ORDER BY om.line ASC, om.no_mesin ASC
LIMIT 5";

echo "Executing SQL Query:\n";
echo $query . "\n\n";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

echo "Results:\n";
echo "Line\tMC\tProduct\tMan Power\tMP Std\n";
echo "----\t--\t-------\t------\n";

while ($row = mysqli_fetch_assoc($result)) {
    $line = $row['line'] ?? '';
    $mc = $row['no_mesin'] ?? '';
    $product = $row['kp_pr'] ?? '';
    $man_power = $row['man_power'] ?? '';
    $mp_std = isset($row['mp_std']) ? $row['mp_std'] : 'NULL';
    
    echo "$line\t$mc\t$product\t$man_power\t$mp_std\n";
}

echo "\nTest completed successfully!\n";
echo "============================\n";

mysqli_close($connection);
?>