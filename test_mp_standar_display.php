<?php
/**
 * Test script to verify MP Standar display implementation
 * This script tests the database queries and field handling
 */

// Include CodeIgniter bootstrap
define('ENVIRONMENT', 'development');
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');

// Load CodeIgniter
require_once BASEPATH . 'core/CodeIgniter.php';

// Load the model
$CI =& get_instance();
$CI->load->model('m_new', 'mm');

echo "=== MP Standar Display Implementation Test ===\n\n";

// Test 1: Check if mp_standar column exists in database
echo "1. Testing database column existence...\n";
$query = $CI->db->query("SHOW COLUMNS FROM t_product LIKE 'mp_standar'");
if ($query->num_rows() > 0) {
    echo "✓ mp_standar column exists in t_product table\n";
    $column_info = $query->row();
    echo "  - Type: " . $column_info->Type . "\n";
    echo "  - Null: " . $column_info->Null . "\n";
    echo "  - Default: " . $column_info->Default . "\n";
} else {
    echo "✗ mp_standar column NOT found in t_product table\n";
}

echo "\n";

// Test 2: Test model methods that include mp_standar
echo "2. Testing model methods...\n";

// Test getDataProduct method
echo "Testing getDataProduct() method:\n";
$product_data = $CI->mm->getDataProduct(1); // Test with product ID 1
if ($product_data && $product_data->num_rows() > 0) {
    $product = $product_data->row();
    if (property_exists($product, 'mp_standar')) {
        echo "✓ mp_standar field found in getDataProduct() result\n";
        echo "  - Value: " . ($product->mp_standar ?? 'NULL') . "\n";
    } else {
        echo "✗ mp_standar field NOT found in getDataProduct() result\n";
    }
} else {
    echo "! No product data found for ID 1 (this is expected if product doesn't exist)\n";
}

echo "\n";

// Test tampil_product_default method
echo "Testing tampil_product_default() method:\n";
$default_products = $CI->mm->tampil_product_default();
if ($default_products && $default_products->num_rows() > 0) {
    $first_product = $default_products->row();
    if (property_exists($first_product, 'mp_standar')) {
        echo "✓ mp_standar field found in tampil_product_default() result\n";
        echo "  - First product mp_standar: " . ($first_product->mp_standar ?? 'NULL') . "\n";
    } else {
        echo "✗ mp_standar field NOT found in tampil_product_default() result\n";
    }
} else {
    echo "! No default products found\n";
}

echo "\n";

// Test 3: Check if sample data exists
echo "3. Testing sample data...\n";
$sample_query = $CI->db->query("SELECT COUNT(*) as count FROM t_product WHERE mp_standar IS NOT NULL AND mp_standar != ''");
$sample_count = $sample_query->row()->count;
echo "Products with mp_standar data: " . $sample_count . "\n";

if ($sample_count > 0) {
    $sample_query = $CI->db->query("SELECT kode_product, nama_product, mp_standar FROM t_product WHERE mp_standar IS NOT NULL AND mp_standar != '' LIMIT 3");
    echo "Sample data:\n";
    foreach ($sample_query->result() as $row) {
        echo "  - " . $row->kode_product . " (" . $row->nama_product . "): " . $row->mp_standar . "\n";
    }
} else {
    echo "! No sample data found. You may need to run add_sample_mp_standar.php\n";
}

echo "\n";

// Test 4: Check file modifications
echo "4. Checking file modifications...\n";

$files_to_check = [
    'application/views/master/master_product.php' => [
        'search' => 'mp_standar',
        'description' => 'Table data display'
    ],
    'application/views/master/master_productAct.php' => [
        'search' => 'mp_standar',
        'description' => 'Add form fields'
    ],
    'application/views/master/edit_master_product.php' => [
        'search' => 'mp_standar',
        'description' => 'Edit form fields'
    ]
];

foreach ($files_to_check as $file => $info) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, $info['search']) !== false) {
            echo "✓ " . $info['description'] . " - mp_standar found\n";
        } else {
            echo "✗ " . $info['description'] . " - mp_standar NOT found\n";
        }
    } else {
        echo "✗ File not found: " . $file . "\n";
    }
}

echo "\n=== Test Complete ===\n";
echo "If all tests pass, the MP Standar display implementation is working correctly.\n";
echo "You can now test the functionality through the web interface.\n";