<?php
/**
 * Data Reconciliation Script
 * Compare data between mp_std_temp table and t_product table
 * Using MySQL executable: C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe
 */

// Database configuration
$host = '192.168.1.202';
$username = 'endang';
$password = 'endangthea0';
$database = 'dpr_injection_new';

// MySQL executable path
$mysql_exe = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe';

// Output file for reconciliation report
$report_file = 'reconciliation_report.txt';

echo "=== Data Reconciliation: mp_std_temp vs t_product ===\n\n";

// Step 1: Check if mp_std_temp table exists
echo "Step 1: Checking mp_std_temp table structure...\n";
$check_table_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "DESCRIBE mp_std_temp;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$output = shell_exec($check_table_cmd . ' 2>&1');
if (strpos($output, 'ERROR') !== false) {
    echo "Error: mp_std_temp table not found or inaccessible\n";
    echo $output . "\n";
    exit(1);
} else {
    echo "✓ mp_std_temp table found\n";
    echo "Structure:\n" . $output . "\n";
}

// Step 2: Check t_product table structure (specifically mp_standar column)
echo "Step 2: Checking t_product table structure...\n";
$check_product_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SHOW COLUMNS FROM t_product LIKE \'mp_standar\';" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$output = shell_exec($check_product_cmd . ' 2>&1');
if (strpos($output, 'mp_standar') !== false) {
    echo "✓ mp_standar column found in t_product table\n";
    echo "Column info:\n" . $output . "\n";
} else {
    echo "Warning: mp_standar column not found in t_product table\n";
}

// Step 3: Get data from mp_std_temp table
echo "Step 3: Retrieving data from mp_std_temp table...\n";
$get_temp_data_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SELECT * FROM mp_std_temp ORDER BY product_code;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$temp_data = shell_exec($get_temp_data_cmd . ' 2>&1');
$temp_rows = explode("\n", trim($temp_data));
$temp_count = count($temp_rows) - 1; // Subtract header row
echo "✓ Found $temp_count records in mp_std_temp table\n";

// Step 4: Get data from t_product table
echo "Step 4: Retrieving data from t_product table...\n";
$get_product_data_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SELECT kode_product, nama_product, mp_standar FROM t_product WHERE kode_product IN (SELECT product_code FROM mp_std_temp) ORDER BY kode_product;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$product_data = shell_exec($get_product_data_cmd . ' 2>&1');
$product_rows = explode("\n", trim($product_data));
$product_count = count($product_rows) - 1; // Subtract header row
echo "✓ Found $product_count matching records in t_product table\n";

// Step 5: Compare data and generate detailed report
echo "Step 5: Performing detailed comparison...\n";

// Create temporary SQL file for complex queries
$temp_sql_file = 'temp_comparison.sql';
$sql_content = "
SELECT
    t.kode_product AS temp_code,
    t.mp_standar AS temp_mp_std,
    p.kode_product AS product_code,
    p.nama_product AS product_name,
    p.mp_standar AS product_mp_standar,
    CASE
        WHEN p.kode_product IS NULL THEN 'Missing in t_product'
        WHEN p.mp_standar IS NULL THEN 'mp_standar is NULL in t_product'
        WHEN CAST(t.mp_standar AS DECIMAL(3,1)) != p.mp_standar THEN CONCAT('Values differ: temp=', t.mp_standar, ', product=', p.mp_standar)
        ELSE 'Values match'
    END AS comparison_result
FROM mp_std_temp t
LEFT JOIN t_product p ON t.kode_product = p.kode_product
ORDER BY t.kode_product;
";

file_put_contents($temp_sql_file, $sql_content);

$comparison_cmd = sprintf(
    '"%s" -h%s -u%s -p%s %s < %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database,
    $temp_sql_file
);

$comparison_result = shell_exec($comparison_cmd . ' 2>&1');

// Step 6: Generate summary statistics
echo "Step 6: Generating summary statistics...\n";

// Create temporary SQL file for summary
$summary_sql_file = 'temp_summary.sql';
$summary_sql_content = "
SELECT
    COUNT(*) as total_temp_records,
    COUNT(p.kode_product) as matched_products,
    COUNT(*) - COUNT(p.kode_product) as missing_in_product,
    COUNT(CASE WHEN p.mp_standar IS NOT NULL THEN 1 END) as products_with_mp_standar,
    COUNT(CASE WHEN p.mp_standar IS NULL THEN 1 END) as products_with_null_mp_standar,
    COUNT(CASE WHEN CAST(t.mp_standar AS DECIMAL(3,1)) = p.mp_standar THEN 1 END) as matching_values,
    COUNT(CASE WHEN CAST(t.mp_standar AS DECIMAL(3,1)) != p.mp_standar THEN 1 END) as different_values
FROM mp_std_temp t
LEFT JOIN t_product p ON t.kode_product = p.kode_product;
";

file_put_contents($summary_sql_file, $summary_sql_content);

$summary_cmd = sprintf(
    '"%s" -h%s -u%s -p%s %s < %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database,
    $summary_sql_file
);

$summary_result = shell_exec($summary_cmd . ' 2>&1');

// Clean up temporary SQL files
unlink($temp_sql_file);
unlink($summary_sql_file);

// Step 7: Write detailed report to file
$report_content = "=== DATA RECONCILIATION REPORT ===\n";
$report_content .= "Generated: " . date('Y-m-d H:i:s') . "\n";
$report_content .= "Comparing: mp_std_temp vs t_product\n\n";

$report_content .= "=== SUMMARY STATISTICS ===\n";
$report_content .= $summary_result . "\n\n";

$report_content .= "=== DETAILED COMPARISON ===\n";
$report_content .= $comparison_result . "\n\n";

$report_content .= "=== RECOMMENDATIONS ===\n";
$report_content .= "1. For records with 'Missing in t_product': Check if these products should be added to t_product\n";
$report_content .= "2. For records with 'mp_standar is NULL': Update t_product to set mp_standar values\n";
$report_content .= "3. For records with 'Values differ': Review which value is correct and update accordingly\n";
$report_content .= "4. For records with 'Values match': No action needed\n\n";

file_put_contents($report_file, $report_content);

echo "✓ Detailed comparison completed\n";
echo "✓ Report saved to: $report_file\n\n";

// Display summary
echo "=== RECONCILIATION SUMMARY ===\n";
echo $summary_result . "\n";

// Display first 10 comparison results
echo "=== SAMPLE COMPARISON RESULTS (First 10) ===\n";
$comparison_lines = explode("\n", trim($comparison_result));
for ($i = 0; $i < min(10, count($comparison_lines)); $i++) {
    echo $comparison_lines[$i] . "\n";
}

if (count($comparison_lines) > 10) {
    echo "... (showing first 10 of " . count($comparison_lines) . " records)\n";
    echo "See $report_file for complete details\n";
}

echo "\n=== RECONCILIATION COMPLETED ===\n";
echo "Full report available in: $report_file\n";
?>