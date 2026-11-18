<?php
/**
 * Generate CSV file of products without mp_standar values
 */

// Database configuration
$host = '192.168.1.202';
$username = 'endang';
$password = 'endangthea0';
$database = 'dpr_injection_new';

// MySQL executable path
$mysql_exe = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe';

echo "=== Generating CSV of Products Without mp_standar ===\n\n";

// Step 1: Get products without mp_standar
echo "Step 1: Retrieving products without mp_standar...\n";

// Create SQL file for the query
$sql_file = 'get_missing_mp_standar.sql';
$sql_content = "
SELECT 
    kode_product,
    nama_product,
    customer,
    divisi,
    cavity,
    cyt_mc,
    cyt_quo,
    sp,
    type,
    discontinue
FROM t_product 
WHERE mp_standar IS NULL 
ORDER BY kode_product;
";

file_put_contents($sql_file, $sql_content);

$query_cmd = sprintf(
    '"%s" -h%s -u%s -p%s %s < %s',
    $mysql_exe,
    $host,
    $username,
    $password,
    $database,
    $sql_file
);

$result = shell_exec($query_cmd . ' 2>&1');

// Clean up temporary SQL file
unlink($sql_file);

// Step 2: Parse results and create CSV
echo "Step 2: Creating CSV file...\n";

$lines = explode("\n", trim($result));
$data = [];

// Parse header and data
if (count($lines) > 1) {
    $header = str_replace("\t", ",", $lines[0]);
    $data[] = $header;
    
    for ($i = 1; $i < count($lines); $i++) {
        if (!empty(trim($lines[$i]))) {
            // Replace tabs with commas and handle any commas in data
            $line = str_replace("\t", ",", $lines[$i]);
            $data[] = $line;
        }
    }
}

// Step 3: Write to CSV file
$csv_filename = 'products_without_mp_standar_' . date('Y_m_d_H_i_s') . '.csv';
$csv_content = implode("\n", $data);

file_put_contents($csv_filename, $csv_content);

// Step 4: Generate summary statistics
echo "Step 3: Generating summary...\n";

// Get total count
$count_sql = "SELECT COUNT(*) as missing_count FROM t_product WHERE mp_standar IS NULL;";
$count_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $count_sql, $database);
$count_result = shell_exec($count_cmd . ' 2>&1');

// Get distribution by division
$div_sql = "SELECT divisi, COUNT(*) as count FROM t_product WHERE mp_standar IS NULL GROUP BY divisi ORDER BY count DESC;";
$div_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $div_sql, $database);
$div_result = shell_exec($div_cmd . ' 2>&1');

// Get distribution by customer
$customer_sql = "SELECT customer, COUNT(*) as count FROM t_product WHERE mp_standar IS NULL AND customer IS NOT NULL GROUP BY customer ORDER BY count DESC LIMIT 10;";
$customer_cmd = sprintf('"%s" -h%s -u%s -p%s -e "%s" %s', $mysql_exe, $host, $username, $password, $customer_sql, $database);
$customer_result = shell_exec($customer_cmd . ' 2>&1');

// Step 5: Display results
echo "\n=== Results ===\n";
echo "Total products without mp_standar: " . trim($count_result) . "\n";
echo "CSV file created: " . $csv_filename . "\n\n";

echo "Distribution by Division:\n";
echo $div_result . "\n\n";

echo "Top 10 Customers with Missing mp_standar:\n";
echo $customer_result . "\n\n";

echo "First 10 sample records:\n";
for ($i = 1; $i < min(11, count($lines)); $i++) {
    if (!empty(trim($lines[$i]))) {
        echo $lines[$i] . "\n";
    }
}

echo "\n=== CSV File Generated Successfully ===\n";
echo "File: " . $csv_filename . "\n";
echo "Location: C:\\temp\\" . $csv_filename . "\n";
echo "Total records: " . (count($data) - 1) . "\n";
echo "\nThe CSV file contains all products that need mp_standar values assigned.\n";
echo "You can use this file to:\n";
echo "1. Review which products need manpower standards\n";
echo "2. Plan data entry for missing values\n";
echo "3. Import updated values back to the system\n";
?>