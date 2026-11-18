<?php
/**
 * Update mp_standar values in t_product table from mp_std_temp table
 * This script will safely migrate manpower standard data
 */

// Database configuration
$host = '192.168.1.202';
$username = 'endang';
$password = 'endangthea0';
$database = 'dpr_injection_new';

// MySQL executable path
$mysql_exe = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe';

echo "=== Updating mp_standar values from mp_std_temp to t_product ===\n\n";

// Step 1: Create backup before updating
echo "Step 1: Creating backup of t_product table...\n";
$backup_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "CREATE TABLE t_product_backup_%s LIKE t_product; INSERT INTO t_product_backup_%s SELECT * FROM t_product;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    date('Y_m_d_H_i_s'),
    date('Y_m_d_H_i_s'),
    $database
);

$backup_result = shell_exec($backup_cmd . ' 2>&1');
if (strpos($backup_result, 'ERROR') === false) {
    echo "✓ Backup created successfully\n";
} else {
    echo "✗ Backup failed: " . $backup_result . "\n";
    exit(1);
}

// Step 2: Show current statistics before update
echo "\nStep 2: Current statistics before update...\n";
$before_stats_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SELECT 
    COUNT(*) as total_products,
    COUNT(CASE WHEN mp_standar IS NOT NULL THEN 1 END) as products_with_mp_standar,
    COUNT(CASE WHEN mp_standar IS NULL THEN 1 END) as products_with_null_mp_standar
FROM t_product;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$before_stats = shell_exec($before_stats_cmd . ' 2>&1');
echo "Before update:\n" . $before_stats . "\n";

// Step 3: Preview what will be updated
echo "Step 3: Preview of records that will be updated...\n";
$preview_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SELECT 
    t.kode_product,
    t.nama_product,
    t.mp_standar as current_mp_standar,
    temp.mp_standar as new_mp_standar
FROM t_product t
INNER JOIN mp_std_temp temp ON t.kode_product = temp.kode_product
WHERE t.mp_standar IS NULL OR t.mp_standar != temp.mp_standar
ORDER BY t.kode_product
LIMIT 10;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$preview_result = shell_exec($preview_cmd . ' 2>&1');
echo "Sample records to be updated (first 10):\n" . $preview_result . "\n";

// Step 4: Perform the update
echo "Step 4: Updating mp_standar values...\n";

// Create temporary SQL file for the update
$update_sql_file = 'update_mp_standar.sql';
$update_sql_content = "
-- Update mp_standar values from mp_std_temp to t_product
UPDATE t_product t
INNER JOIN mp_std_temp temp ON t.kode_product = temp.kode_product
SET t.mp_standar = CAST(temp.mp_standar AS DECIMAL(3,1))
WHERE t.mp_standar IS NULL OR t.mp_standar != CAST(temp.mp_standar AS DECIMAL(3,1));
";

file_put_contents($update_sql_file, $update_sql_content);

$update_cmd = sprintf(
    '"%s" -h%s -u%s -p%s %s < %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database,
    $update_sql_file
);

$update_result = shell_exec($update_cmd . ' 2>&1');

if (strpos($update_result, 'ERROR') === false) {
    echo "✓ Update completed successfully\n";
} else {
    echo "✗ Update failed: " . $update_result . "\n";
    // Clean up and exit on error
    unlink($update_sql_file);
    exit(1);
}

// Clean up temporary SQL file
unlink($update_sql_file);

// Step 5: Show statistics after update
echo "\nStep 5: Statistics after update...\n";
$after_stats_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SELECT 
    COUNT(*) as total_products,
    COUNT(CASE WHEN mp_standar IS NOT NULL THEN 1 END) as products_with_mp_standar,
    COUNT(CASE WHEN mp_standar IS NULL THEN 1 END) as products_with_null_mp_standar,
    COUNT(CASE WHEN mp_standar = 0.5 THEN 1 END) as products_with_0_5_mp,
    COUNT(CASE WHEN mp_standar = 1.0 THEN 1 END) as products_with_1_0_mp,
    COUNT(CASE WHEN mp_standar = 1.5 THEN 1 END) as products_with_1_5_mp,
    COUNT(CASE WHEN mp_standar = 2.0 THEN 1 END) as products_with_2_0_mp
FROM t_product;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$after_stats = shell_exec($after_stats_cmd . ' 2>&1');
echo "After update:\n" . $after_stats . "\n";

// Step 6: Show any remaining issues
echo "\nStep 6: Checking for remaining issues...\n";

// Check for products in temp that don't exist in t_product
$missing_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SELECT 
    temp.kode_product,
    temp.mp_standar
FROM mp_std_temp temp
LEFT JOIN t_product t ON temp.kode_product = t.kode_product
WHERE t.kode_product IS NULL
ORDER BY temp.kode_product;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$missing_result = shell_exec($missing_cmd . ' 2>&1');
$missing_lines = explode("\n", trim($missing_result));
if (count($missing_lines) > 1) {
    echo "Products in mp_std_temp that don't exist in t_product (" . (count($missing_lines) - 1) . " records):\n";
    echo $missing_result . "\n";
} else {
    echo "✓ All products from mp_std_temp exist in t_product\n";
}

// Check for any data type issues
$datatype_issues_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SELECT 
    kode_product,
    mp_standar
FROM t_product
WHERE mp_standar IS NOT NULL 
AND (mp_standar < 0 OR mp_standar > 10)
ORDER BY kode_product;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$datatype_issues = shell_exec($datatype_issues_cmd . ' 2>&1');
$issue_lines = explode("\n", trim($datatype_issues));
if (count($issue_lines) > 1) {
    echo "Products with unusual mp_standar values:\n" . $datatype_issues . "\n";
} else {
    echo "✓ All mp_standar values are within expected range (0-10)\n";
}

// Step 7: Verification sample
echo "\nStep 7: Verification sample...\n";
$verify_cmd = sprintf(
    '"%s" -h%s -u%s -p%s -e "SELECT 
    t.kode_product,
    t.nama_product,
    t.mp_standar,
    temp.mp_standar as temp_value
FROM t_product t
INNER JOIN mp_std_temp temp ON t.kode_product = temp.kode_product
WHERE t.mp_standar = CAST(temp.mp_standar AS DECIMAL(3,1))
ORDER BY t.kode_product
LIMIT 10;" %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database
);

$verify_result = shell_exec($verify_cmd . ' 2>&1');
echo "Sample of successfully updated records:\n" . $verify_result . "\n";

echo "\n=== Update Process Completed ===\n";
echo "✓ Backup created: t_product_backup_" . date('Y_m_d_H_i_s') . "\n";
echo "✓ mp_standar values updated from mp_std_temp\n";
echo "✓ Verification completed\n";
echo "\nReview the statistics above to confirm the update was successful.\n";
?>