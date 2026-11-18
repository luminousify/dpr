<?php
/**
 * Verify the mp_standar update from mp_std_temp to t_product
 * Simple verification script with basic SQL queries
 */

// Database configuration
$host = '192.168.1.202';
$username = 'endang';
$password = 'endangthea0';
$database = 'dpr_injection_new';

// MySQL executable path
$mysql_exe = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe';

echo "=== Verifying mp_standar Update Results ===\n\n";

// Step 1: Basic statistics
echo "Step 1: Basic statistics...\n";
$stats_sql = "SELECT COUNT(*) as total_products FROM t_product;";
$stats_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $stats_sql, $database);
$total_result = shell_exec($stats_cmd . ' 2>&1');
echo "Total products in t_product: " . trim($total_result) . "\n";

$non_null_sql = "SELECT COUNT(*) as products_with_mp_standar FROM t_product WHERE mp_standar IS NOT NULL;";
$non_null_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $non_null_sql, $database);
$non_null_result = shell_exec($non_null_cmd . ' 2>&1');
echo "Products with mp_standar values: " . trim($non_null_result) . "\n";

$null_sql = "SELECT COUNT(*) as products_with_null_mp_standar FROM t_product WHERE mp_standar IS NULL;";
$null_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $null_sql, $database);
$null_result = shell_exec($null_cmd . ' 2>&1');
echo "Products with NULL mp_standar: " . trim($null_result) . "\n";

// Step 2: Distribution of mp_standar values
echo "\nStep 2: Distribution of mp_standar values...\n";
$distribution_sql = "SELECT mp_standar, COUNT(*) as count FROM t_product WHERE mp_standar IS NOT NULL GROUP BY mp_standar ORDER BY mp_standar;";
$distribution_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $distribution_sql, $database);
$distribution_result = shell_exec($distribution_cmd . ' 2>&1');
echo "Distribution:\n" . $distribution_result . "\n";

// Step 3: Sample of updated records
echo "\nStep 3: Sample of updated records...\n";
$sample_sql = "SELECT kode_product, nama_product, mp_standar FROM t_product WHERE mp_standar IS NOT NULL ORDER BY kode_product LIMIT 10;";
$sample_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $sample_sql, $database);
$sample_result = shell_exec($sample_cmd . ' 2>&1');
echo "Sample records with mp_standar values:\n" . $sample_result . "\n";

// Step 4: Check temp table
echo "\nStep 4: mp_std_temp table statistics...\n";
$temp_stats_sql = "SELECT COUNT(*) as total_temp_records FROM mp_std_temp;";
$temp_stats_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $temp_stats_sql, $database);
$temp_total_result = shell_exec($temp_stats_cmd . ' 2>&1');
echo "Total records in mp_std_temp: " . trim($temp_total_result) . "\n";

$temp_dist_sql = "SELECT mp_standar, COUNT(*) as count FROM mp_std_temp GROUP BY mp_standar ORDER BY mp_standar;";
$temp_dist_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $temp_dist_sql, $database);
$temp_dist_result = shell_exec($temp_dist_cmd . ' 2>&1');
echo "mp_std_temp distribution:\n" . $temp_dist_result . "\n";

// Step 5: Check for any mismatches
echo "\nStep 5: Checking for value mismatches...\n";
$mismatch_sql = "SELECT COUNT(*) as mismatch_count FROM t_product t INNER JOIN mp_std_temp temp ON t.kode_product = temp.kode_product WHERE t.mp_standar != CAST(temp.mp_standar AS DECIMAL(3,1));";
$mismatch_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $mismatch_sql, $database);
$mismatch_result = shell_exec($mismatch_cmd . ' 2>&1');
echo "Records with mismatched values: " . trim($mismatch_result) . "\n";

// Step 6: Check for products in temp that don't exist in product
echo "\nStep 6: Checking for missing products...\n";
$missing_sql = "SELECT COUNT(*) as missing_count FROM mp_std_temp temp LEFT JOIN t_product t ON temp.kode_product = t.kode_product WHERE t.kode_product IS NULL;";
$missing_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $missing_sql, $database);
$missing_result = shell_exec($missing_cmd . ' 2>&1');
echo "Products in mp_std_temp missing from t_product: " . trim($missing_result) . "\n";

echo "\n=== Verification Complete ===\n";
echo "Summary:\n";
echo "- Total products: " . trim($total_result) . "\n";
echo "- Products with mp_standar: " . trim($non_null_result) . "\n";
echo "- Products without mp_standar: " . trim($null_result) . "\n";
echo "- Records in mp_std_temp: " . trim($temp_total_result) . "\n";
echo "- Mismatched values: " . trim($mismatch_result) . "\n";
echo "- Missing products: " . trim($missing_result) . "\n";

$success_rate = ((int)trim($non_null_result) / (int)trim($total_result)) * 100;
echo "- Success rate: " . number_format($success_rate, 2) . "%\n";

if (trim($mismatch_result) == 0 && trim($missing_result) == 0) {
    echo "\n✓ Update appears to be successful!\n";
} else {
    echo "\n⚠ Some issues detected. Review the details above.\n";
}
?>