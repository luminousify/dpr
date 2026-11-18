<?php
/**
 * Advanced Diagnostic Script to Compare DPR Exports
 * 
 * This script helps identify why Excel exports from DPR page and Export page
 * produce different file sizes and different data.
 */

// Database configuration
require_once 'application/config/database.php';

// Connect to database
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], 
                      $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h1>🔍 DPR Export Diagnostic Tool (Advanced)</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .section { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .warning { background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; }
    .success { background: #d4edda; padding: 10px; border-left: 4px solid #28a745; }
    .error { background: #f8d7da; padding: 10px; border-left: 4px solid #dc3545; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #007bff; color: white; }
    .info { background: #d1ecf1; padding: 10px; border-left: 4px solid #17a2b8; }
    pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
    .sql-highlight { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; }
</style>";

// Get current session info from CodeIgniter
session_start();
$ci_session = isset($_SESSION) ? $_SESSION : [];

echo "<div class='section'>";
echo "<h2>1. 🔐 Current Session Info</h2>";
if (!empty($ci_session)) {
    echo "<table>";
    echo "<tr><th>Session Key</th><th>Value</th></tr>";
    foreach (['nama_actor', 'posisi', 'divisi', 'user_name'] as $key) {
        if (isset($ci_session[$key])) {
            $value = $ci_session[$key];
            $is_kanit = ($key == 'posisi' && $value == 'kanit');
            echo "<tr " . ($is_kanit ? "style='background: #fff3cd;'" : "") . ">";
            echo "<td><strong>$key</strong></td>";
            echo "<td>" . htmlspecialchars($value) . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    
    if (isset($ci_session['posisi']) && $ci_session['posisi'] == 'kanit') {
        echo "<p class='warning'><strong>⚠️ IMPORTANT:</strong> You are in 'kanit' position!</p>";
        echo "<p>Exports will be filtered by: <strong>WHERE q.kanit = '" . $ci_session['nama_actor'] . "'</strong></p>";
        echo "<p>This means you will ONLY see records assigned to you, not all records.</p>";
    }
} else {
    echo "<p class='error'>❌ No active session found. Please login first.</p>";
}
echo "</div>";

// Test 2: Check if using the view (v_production_op) or table
echo "<div class='section'>";
echo "<h2>2. 📊 Data Source Analysis</h2>";

echo "<p>The query uses <strong>v_production_op</strong> (a database view) with complex JOINs. Let's check what it contains:</p>";

$view_check_query = "SELECT COUNT(*) as total FROM v_production_op WHERE tanggal BETWEEN '2026-01-01' AND '2026-01-31'";
$view_result = $mysqli->query($view_check_query);
$view_count = $view_result ? $view_result->fetch_assoc()['total'] : 0;

$table_check_query = "SELECT COUNT(*) as total FROM t_production_op WHERE tanggal BETWEEN '2026-01-01' AND '2026-01-31'";
$table_result = $mysqli->query($table_check_query);
$table_count = $table_result ? $table_result->fetch_assoc()['total'] : 0;

echo "<table>";
echo "<tr><th>Data Source</th><th>Record Count</th></tr>";
echo "<tr><td>v_production_op (view)</td><td>" . number_format($view_count) . " rows</td></tr>";
echo "<tr><td>t_production_op (table)</td><td>" . number_format($table_count) . " rows</td></tr>";
echo "</table>";

if ($view_count != $table_count) {
    echo "<p class='warning'><strong>Difference:</strong> " . number_format(abs($view_count - $table_count)) . " rows</p>";
    echo "<p>The view might have additional filters or transformations.</p>";
}
echo "</div>";

// Test 3: Detailed comparison with the actual DPR query
echo "<div class='section'>";
echo "<h2>3. 📝 Actual Query Execution Test</h2>";

$test_dari = '2026-01-01';
$test_sampai = '2026-01-31';
$test_shift = 'All';
$test_nama = isset($ci_session['nama_actor']) ? $ci_session['nama_actor'] : '';
$test_posisi = isset($ci_session['posisi']) ? $ci_session['posisi'] : '';

echo "<div class='info'>";
echo "<strong>Test Parameters:</strong><br>";
echo "📅 Date Range: $test_dari to $test_sampai<br>";
echo "🔄 Shift: $test_shift<br>";
echo "👤 User: $test_nama<br>";
echo "🏢 Position: $test_posisi<br>";
echo "</div>";

// Simplified query test (mimicking the actual function without complex JOINs)
$simple_query = "
    SELECT 
        COUNT(*) as total_records,
        COUNT(DISTINCT kanit) as unique_kanits,
        MIN(id) as min_id,
        MAX(id) as max_id
    FROM v_production_op
    WHERE tanggal BETWEEN '$test_dari' AND '$test_sampai'
";

if ($test_posisi == 'kanit' && !empty($test_nama)) {
    $simple_query .= " AND kanit = '$test_nama'";
    echo "<p class='warning'><strong>Filter Applied:</strong> AND kanit = '$test_nama'</p>";
}

$result = $mysqli->query($simple_query);
if ($result) {
    $row = $result->fetch_assoc();
    echo "<h4>Query Results:</h4>";
    echo "<table>";
    echo "<tr><th>Metric</th><th>Value</th></tr>";
    echo "<tr><td>Total Records</td><td><strong>" . number_format($row['total_records']) . "</strong></td></tr>";
    echo "<tr><td>Unique Kanits</td><td>" . $row['unique_kanits'] . "</td></tr>";
    echo "<tr><td>Min ID</td><td>" . number_format($row['min_id']) . "</td></tr>";
    echo "<tr><td>Max ID</td><td>" . number_format($row['max_id']) . "</td></tr>";
    echo "</table>";
    
    // Estimate file size
    $estimated_size_kb = round($row['total_records'] * 0.5, 2);
    echo "<p class='info'><strong>Estimated Excel file size:</strong> ~$estimated_size_kb KB</p>";
}
echo "</div>";

// Test 4: Sample records to verify
echo "<div class='section'>";
echo "<h2>4. 🔍 Sample Records Verification</h2>";

$sample_query = "
    SELECT 
        id,
        tanggal,
        YEAR(tanggal) as year_db,
        MONTH(tanggal) as month_db,
        mesin,
        shift,
        kanit,
        nama_product,
        qty_ok,
        qty_ng
    FROM v_production_op
    WHERE tanggal BETWEEN '$test_dari' AND '$test_sampai'
";

if ($test_posisi == 'kanit' && !empty($test_nama)) {
    $sample_query .= " AND kanit = '$test_nama'";
}

$sample_query .= " ORDER BY id LIMIT 5";

$result = $mysqli->query($sample_query);
if ($result && $result->num_rows > 0) {
    echo "<h4>First 5 records from your query:</h4>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Tanggal</th><th>Year</th><th>Month</th><th>Mesin</th><th>Shift</th><th>Kanit</th><th>Product</th><th>OK</th><th>NG</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $year_issue = ($row['year_db'] != 2026) ? "class='error'" : "";
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['tanggal'] . "</td>";
        echo "<td $year_issue><strong>" . $row['year_db'] . "</strong></td>";
        echo "<td>" . $row['month_db'] . "</td>";
        echo "<td>" . htmlspecialchars($row['mesin']) . "</td>";
        echo "<td>" . $row['shift'] . "</td>";
        echo "<td>" . htmlspecialchars($row['kanit']) . "</td>";
        echo "<td>" . htmlspecialchars(substr($row['nama_product'], 0, 30)) . "...</td>";
        echo "<td>" . $row['qty_ok'] . "</td>";
        echo "<td>" . $row['qty_ng'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    if ($row['year_db'] != 2026) {
        echo "<p class='error'><strong>⚠️ CRITICAL ISSUE:</strong> Year in database is " . $row['year_db'] . " instead of 2026!</p>";
        echo "<p>This explains the Year=8 issue in your Excel files.</p>";
    }
} else {
    echo "<p class='error'>❌ No records found with the current filter.</p>";
}
echo "</div>";

// Test 5: Possible reasons for file size difference
echo "<div class='section'>";
echo "<h2>5. 💡 Why File Sizes Are Different</h2>";

echo "<div class='success'>";
echo "<h3>Most Likely Causes:</h3>";
echo "<ol>";
echo "<li><strong>Different row counts:</strong> Even small differences in row count can cause file size differences</li>";
echo "<li><strong>Cell content:</strong> More text/data in cells = larger file size</li>";
echo "<li><strong>Column auto-sizing:</strong> Different column widths can affect file size</li>";
echo "<li><strong>Hidden characters:</strong> Special characters or formatting can increase file size</li>";
echo "</ol>";
echo "</div>";

if ($test_posisi == 'kanit') {
    echo "<div class='warning'>";
    echo "<h3>🔍 Kanit Filter Impact:</h3>";
    echo "<p>Since you're in 'kanit' position, your export is filtered by your name.</p>";
    echo "<p><strong>Possible scenario:</strong></p>";
    echo "<ul>";
    echo "<li>DPR page export: Uses different session or date range</li>";
    echo "<li>Export page export: Uses current session</li>";
    echo "<li>Result: Different data, different file sizes</li>";
    echo "</ul>";
    echo "</div>";
}

// Test 6: Actionable recommendations
echo "</div><div class='section'>";
echo "<h2>6. ✅ How to Get Identical Data</h2>";

echo "<h3>Step-by-Step Verification:</h3>";
echo "<ol>";
echo "<li><strong>Clear browser cache and logout</strong></li>";
echo "<li><strong>Login again</strong> as the same user</li>";
echo "<li><strong>Open browser console</strong> (F12) and go to Network tab</li>";
echo "<li><strong>Export from DPR page</strong> and check the request parameters in network tab</li>";
echo "<li><strong>Export from Export page</strong> and compare the request parameters</li>";
echo "<li><strong>Look for differences in:</strong></li>";
echo "<ul>";
echo "<li>Date range (tanggal_dari, tanggal_sampai)</li>";
echo "<li>Shift parameter</li>";
echo "<li>Session cookie values</li>";
echo "</ul>";
echo "</ol>";

echo "<h3>Or, use this diagnostic:</h3>";
echo "<p><strong>1.</strong> Run this diagnostic script</p>";
echo "<p><strong>2.</strong> Note the exact number of records shown above</p>";
echo "<p><strong>3.</strong> Export from both pages</p>";
echo "<p><strong>4.</strong> Check row count in each Excel file (last row number)</p>";
echo "<p><strong>5.</strong> If row counts match but files are different, the issue is in formatting/styling</p>";
echo "<p><strong>6.</strong> If row counts differ, the issue is in the query/session</p>";

echo "</div>";

$mysqli->close();
?>

<hr>
<div class='section'>
    <h2>🚀 Quick Test</h2>
    <p>Copy this SQL and run it directly in your database to see the exact data:</p>
    <div class='sql-highlight'>
        <pre>
-- Replace 'YOUR_USERNAME' with your actual username
SELECT COUNT(*) FROM v_production_op 
WHERE tanggal BETWEEN '2026-01-01' AND '2026-01-31'
" . ($test_posisi == 'kanit' && !empty($test_nama) ? "AND kanit = '$test_nama'" : "") . "
        </pre>
    </div>
</div>
