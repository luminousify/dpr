<?php
/**
 * Simple test to verify the 'usage' keyword fix
 * This script tests the specific queries that were causing issues
 */

echo "=== Testing 'usage' Keyword Fix ===\n\n";

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

// Test 2: Test the fixed queries
echo "\nTest 2: Testing fixed queries with escaped 'usage'\n";

$queries = [
    "getDataProduct" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, `usage`, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`id_product` = 1",
    "tampil_product_bySearch" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, `usage`, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`divisi` = 'INJ' AND (q.`kode_product` LIKE '%test%' OR q.`nama_product` LIKE '%test%')",
    "tampil_product_default" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, `usage`, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`cost` = '1' LIMIT 5",
    "tampil_product_inventory" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, `usage`, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar from t_product as p order by p.kode_product ASC",
    "getProduk_inventory" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, `usage`, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS p WHERE p.id_product = 1"
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

// Test 3: Test the old queries (should fail)
echo "\nTest 3: Testing old queries (should fail with 'usage' keyword)\n";

$oldQueries = [
    "getDataProduct_old" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, usage, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`id_product` = 1",
    "tampil_product_bySearch_old" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, usage, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`divisi` = 'INJ' AND (q.`kode_product` LIKE '%test%' OR q.`nama_product` LIKE '%test%')",
    "tampil_product_default_old" => "SELECT id_product, kode_product, nama_product, MomID, MomProduct, kode_proses, nama_proses, usage, cavity, sp, type, cyt_mc, cyt_quo, customer, AccID, AccInv, divisi, cost, discontinue, mp_standar FROM t_product AS q WHERE q.`cost` = '1' LIMIT 5"
];

foreach ($oldQueries as $name => $sql) {
    echo "Testing $name query...\n";
    try {
        $result = $conn->query("EXPLAIN " . $sql);
        if ($result) {
            echo "✓ $name query syntax is valid (unexpected!)\n";
        } else {
            echo "✗ $name query syntax error: " . $conn->error . "\n";
        }
    } catch (Exception $e) {
        echo "✗ $name query failed: " . $e->getMessage() . "\n";
    }
}

$conn->close();

echo "\n=== Test Completed ===\n";
echo "If the new queries pass and old queries fail, the fix is working correctly.\n";
?>