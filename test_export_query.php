<?php
/**
 * Simple test to check export query behavior
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$db = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'dpr_cku'
];

$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h1>Export Query Test</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .info { background: #d1ecf1; padding: 10px; border-left: 4px solid #17a2b8; margin: 10px 0; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #007bff; color: white; }
    pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
</style>";

// Test date range
$test_dari = '2026-01-01';
$test_sampai = '2026-01-31';
$test_shift = 'All';

echo "<div class='info'>";
echo "<strong>Test Parameters:</strong><br>";
echo "📅 Date Range: $test_dari to $test_sampai<br>";
echo "🔄 Shift: $test_shift<br>";
echo "</div>";

// Test 1: Count without kanit filter
echo "<h2>Test 1: All Records (No Kanit Filter)</h2>";
$query1 = "
    SELECT COUNT(*) as total
    FROM v_production_op
    WHERE tanggal BETWEEN '$test_dari' AND '$test_sampai'
";

$result1 = $mysqli->query($query1);
if ($result1) {
    $row = $result1->fetch_assoc();
    echo "<p><strong>Total records:</strong> " . number_format($row['total']) . "</p>";
}

// Test 2: Get sample records to see kanit values
echo "<h2>Test 2: Sample Records with Kanit Names</h2>";
$query2 = "
    SELECT 
        id,
        tanggal,
        mesin,
        shift,
        kanit,
        nama_product,
        qty_ok,
        qty_ng
    FROM v_production_op
    WHERE tanggal BETWEEN '$test_dari' AND '$test_sampai'
    ORDER BY id
    LIMIT 10
";

$result2 = $mysqli->query($query2);
if ($result2 && $result2->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Tanggal</th><th>Mesin</th><th>Shift</th><th>Kanit</th><th>Product</th><th>OK</th><th>NG</th></tr>";
    while ($row = $result2->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['tanggal'] . "</td>";
        echo "<td>" . htmlspecialchars($row['mesin']) . "</td>";
        echo "<td>" . $row['shift'] . "</td>";
        echo "<td><strong>" . htmlspecialchars($row['kanit']) . "</strong></td>";
        echo "<td>" . htmlspecialchars(substr($row['nama_product'], 0, 30)) . "</td>";
        echo "<td>" . $row['qty_ok'] . "</td>";
        echo "<td>" . $row['qty_ng'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Test 3: Count per kanit
echo "<h2>Test 3: Records Count by Kanit</h2>";
$query3 = "
    SELECT 
        kanit,
        COUNT(*) as total_records,
        MIN(tanggal) as first_date,
        MAX(tanggal) as last_date
    FROM v_production_op
    WHERE tanggal BETWEEN '$test_dari' AND '$test_sampai'
    GROUP BY kanit
    ORDER BY total_records DESC
";

$result3 = $mysqli->query($query3);
if ($result3 && $result3->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Kanit</th><th>Record Count</th><th>First Date</th><th>Last Date</th></tr>";
    while ($row = $result3->fetch_assoc()) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($row['kanit']) . "</strong></td>";
        echo "<td>" . number_format($row['total_records']) . "</td>";
        echo "<td>" . $row['first_date'] . "</td>";
        echo "<td>" . $row['last_date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Test 4: Check if session affects query
echo "<h2>Test 4: Current Session Info</h2>";
session_start();
if (isset($_SESSION['nama_actor'])) {
    echo "<p><strong>Current user:</strong> " . htmlspecialchars($_SESSION['nama_actor']) . "</p>";
    echo "<p><strong>Position:</strong> " . htmlspecialchars($_SESSION['posisi']) . "</p>";
    
    // Test with session filter
    $test_nama = $_SESSION['nama_actor'];
    $query4 = "
        SELECT COUNT(*) as total
        FROM v_production_op
        WHERE tanggal BETWEEN '$test_dari' AND '$test_sampai'
        AND kanit = '$test_nama'
    ";
    
    $result4 = $mysqli->query($query4);
    if ($result4) {
        $row = $result4->fetch_assoc();
        echo "<p><strong>Records for current kanit:</strong> " . number_format($row['total']) . "</p>";
    }
} else {
    echo "<p><em>Not logged in or session not active</em></p>";
}

echo "<div class='info'>";
echo "<h3>💡 Key Findings:</h3>";
echo "<ul>";
echo "<li>If you see multiple kanits in Test 3, the data is distributed across different kanits</li>";
echo "<li>If you're in 'kanit' position, your exports will only show YOUR records</li>";
echo "<li>To get ALL data, you need to be logged in as a user who is NOT in 'kanit' position</li>";
echo "<li>Common non-kanit positions: 'kasi', 'manager', 'admin', etc.</li>";
echo "</ul>";
echo "</div>";

$mysqli->close();
?>
