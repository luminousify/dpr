<?php
/**
 * Simple SQL syntax verification for mp_standar column changes
 * This script verifies that our SQL queries are syntactically correct
 */

echo "=== Verifying SQL Syntax for mp_standar Changes ===\n\n";

// Test 1: Check if we can connect to database
echo "Test 1: Database connection\n";
try {
    // Try to connect using common database configurations
    $configs = [
        ['host' => 'localhost', 'username' => 'root', 'password' => '', 'database' => 'dpr'],
        ['host' => 'localhost', 'username' => 'root', 'password' => '', 'database' => 'production'],
        ['host' => '127.0.0.1', 'username' => 'root', 'password' => '', 'database' => 'dpr'],
    ];
    
    $conn = null;
    foreach ($configs as $config) {
        try {
            $conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
            if ($conn->connect_error) {
                continue;
            }
            echo "✓ Connected to database: " . $config['database'] . "\n";
            break;
        } catch (Exception $e) {
            continue;
        }
    }
    
    if (!$conn || $conn->connect_error) {
        echo "✗ Could not connect to database\n";
        echo "  Please check your database configuration\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check if mp_standar column exists
echo "\nTest 2: Checking mp_standar column\n";
try {
    $result = $conn->query("SHOW COLUMNS FROM t_product LIKE 'mp_standar'");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "✓ mp_standar column exists\n";
        echo "  - Type: " . $row['Type'] . "\n";
        echo "  - Null: " . $row['Null'] . "\n";
        echo "  - Default: " . $row['Default'] . "\n";
    } else {
        echo "✗ mp_standar column does not exist\n";
        echo "  Please run the migration script first\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking mp_standar column: " . $e->getMessage() . "\n";
}

// Test 3: Test our updated SQL queries
echo "\nTest 3: Testing SQL query syntax\n";

$queries = [
    "getDataProduct" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, usage, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`id_product` = 1",
    "get_kodeProductRelease" => "SELECT id_product, kode_product, nama_product, mp_standar FROM t_product WHERE kode_product LIKE '%test%' OR nama_product LIKE '%test%' GROUP BY kode_product, nama_product, mp_standar",
    "tampil_product_bySearch" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, usage, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`divisi` = 'INJ' AND (q.`kode_product` LIKE '%test%' OR q.`nama_product` LIKE '%test%')",
    "tampil_product_default" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, usage, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`cost` = '1' LIMIT 5",
    "tampil_product" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, usage, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar from t_product as p order by p.kode_product ASC",
    "getProduk" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, usage, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS p WHERE p.id_product = 1",
    "get_products_with_mp_standar" => "SELECT id_product, kode_product, nama_product, mp_standar FROM t_product WHERE mp_standar IS NOT NULL ORDER BY kode_product ASC"
];

foreach ($queries as $name => $sql) {
    echo "Testing $name query...\n";
    try {
        // Use EXPLAIN to test query syntax without executing
        $result = $conn->query("EXPLAIN " . $sql);
        if ($result) {
            echo "✓ $name query syntax is valid\n";
        } else {
            echo "✗ $name query syntax error: " . $conn->error . "\n";
        }
    } catch (Exception $e) {
        echo "✗ $name query failed: " . $e->getMessage() . "\n";
    }
}

// Test 4: Test UPDATE query with mp_standar
echo "\nTest 4: Testing UPDATE query with mp_standar\n";
try {
    // Create a test update query (we won't actually execute it)
    $test_update = "UPDATE t_product SET mp_standar = ? WHERE id_product = ?";
    $stmt = $conn->prepare($test_update);
    if ($stmt) {
        echo "✓ UPDATE query with mp_standar is valid\n";
        $stmt->close();
    } else {
        echo "✗ UPDATE query with mp_standar failed: " . $conn->error . "\n";
    }
} catch (Exception $e) {
    echo "✗ UPDATE query test failed: " . $e->getMessage() . "\n";
}

$conn->close();

echo "\n=== SQL Syntax Verification Completed ===\n";
echo "If all tests passed, the code changes should work correctly.\n";
echo "Make sure to run the migration script to add the mp_standar column if it doesn't exist.\n";
?>