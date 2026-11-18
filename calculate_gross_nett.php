<?php
/**
 * Manual Gross and Nett Calculation Script
 * Based on the formula from edit_dpr.php
 * Calculate for all records in January and February 2026
 */

// Database connection
$host = '192.168.1.202';
$user = 'endang';
$pass = 'endangthea0';
$db   = 'dpr_injection_new';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get date range from URL params or default to Jan-Feb 2026
$start_date = isset($_GET['start']) ? $_GET['start'] : '2026-01-01';
$end_date = isset($_GET['end']) ? $_GET['end'] : '2026-02-28';

/**
 * Custom rounding function (from the view)
 */
function customRound($value) {
    $remainder = $value % 1;
    if ($remainder <= 0.5) {
        return floor($value);
    } else {
        return ceil($value);
    }
}

echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; font-size: 12px; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
    th, td { border: 1px solid #ddd; padding: 5px; text-align: left; font-size: 11px; }
    th { background-color: #4CAF50; color: white; position: sticky; top: 0; cursor: pointer; }
    th:hover { background-color: #45a049; }
    th.sort-asc::after { content: ' \\25B2'; font-size: 10px; }
    th.sort-desc::after { content: ' \\25BC'; font-size: 10px; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    .section { margin-bottom: 40px; }
    .formula { background-color: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
    .match { color: green; font-weight: bold; }
    .mismatch { color: red; font-weight: bold; }
    .error { background-color: #ffcccc; }
    h2 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
    .summary-table th { background-color: #2196F3; }
    .summary-table th:hover { background-color: #1976D2; }
    .mismatch-row { background-color: #fff3cd !important; }
    .summary-box { background-color: #e3f2fd; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #2196F3; }
    .summary-box.error { background-color: #ffebee; border-left-color: #f44336; }
    .summary-box.success { background-color: #e8f5e9; border-left-color: #4CAF50; }
    #mainTable th { user-select: none; }
</style>

<script>
function sortTable(tableId, columnIndex, dataType = 'string') {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const headers = table.querySelectorAll('th');
    
    // Determine sort direction
    const currentHeader = headers[columnIndex];
    let direction = 'asc';
    
    if (currentHeader.classList.contains('sort-asc')) {
        direction = 'desc';
        currentHeader.classList.remove('sort-asc');
        currentHeader.classList.add('sort-desc');
    } else if (currentHeader.classList.contains('sort-desc')) {
        direction = 'asc';
        currentHeader.classList.remove('sort-desc');
        currentHeader.classList.add('sort-asc');
    } else {
        // Clear all other sort classes
        headers.forEach(h => {
            h.classList.remove('sort-asc', 'sort-desc');
        });
        currentHeader.classList.add('sort-asc');
    }
    
    // Sort rows
    rows.sort((a, b) => {
        let aVal = a.cells[columnIndex].textContent.trim();
        let bVal = b.cells[columnIndex].textContent.trim();
        
        // Handle different data types
        if (dataType === 'number') {
            // Extract numeric value, handling special cases like 'OK' or diff values
            aVal = parseFloat(aVal.replace(/[^0-9.-]/g, '')) || 0;
            bVal = parseFloat(bVal.replace(/[^0-9.-]/g, '')) || 0;
        } else if (dataType === 'date') {
            aVal = new Date(aVal);
            bVal = new Date(bVal);
        } else {
            aVal = aVal.toLowerCase();
            bVal = bVal.toLowerCase();
        }
        
        if (direction === 'asc') {
            return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
        } else {
            return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
        }
    });
    
    // Re-append sorted rows and update row numbers
    rows.forEach((row, index) => {
        row.cells[0].textContent = index + 1;
        tbody.appendChild(row);
    });
}
</script>";

echo "<h1>Gross and Nett Production Calculation Verification</h1>";
echo "<p><a href='?show_mismatch=1&start=$start_date&end=$end_date' style='background-color:#f44336;color:white;padding:8px 15px;text-decoration:none;border-radius:4px;'>Show Only Mismatches</a></p>";

echo "<div class='formula'>";
echo "<h3>Formula Used (from edit_dpr.php):</h3>";
echo "<pre>";
echo "Production Time = (Qty OK + Defect) / Cavity * (CT Aktual / 3600) hours\n\n";
echo "WorkHour = Production Time + Loss Time - Overtime\n\n";
echo "<b>Gross Formula:</b>\n";
echo "grossProduction = 3600 / ((QtyOK / Cavity2) / (WorkHour + Overtime))\n";
echo "Simplified: Gross = 3600 * Cavity2 * (WorkHour + OT) / QtyOK\n\n";
echo "<b>Nett Formula:</b>\n";
echo "NProd = 3600 / ((QtyOK / Cavity2) / (WorkHour + Overtime - LossTime))\n";
echo "Simplified: Nett = 3600 * Cavity2 * (WorkHour + OT - LossTime) / QtyOK\n\n";
echo "<b>Custom Rounding:</b> If decimal <= 0.5, floor; else ceil\n";
echo "</pre>";
echo "</div>";

// Get all production records in date range
$sql = "SELECT 
            po.id_production,
            po.tanggal,
            po.qty_ok,
            po.qty_ng,
            po.qty_lt,
            po.nwt_mp,
            po.ot_mp,
            po.ct_mc_aktual,
            po.cavity,
            po.cavity2,
            po.production_time,
            po.nett_prod,
            po.gross_prod
        FROM t_production_op po
        WHERE po.tanggal BETWEEN '$start_date' AND '$end_date'
        ORDER BY po.tanggal, po.id_production";

$result = $conn->query($sql);
$total_records = $result->num_rows;

echo "<h2>Period: $start_date to $end_date | Total Records: $total_records</h2>";

// Summary counters
$mismatch_gross_count = 0;
$mismatch_nett_count = 0;
$mismatch_records = [];
$zero_qty_count = 0;
$zero_ct_count = 0;

echo "<table class='summary-table' id='mainTable'>";
echo "<thead>";
echo "<tr>
    <th onclick=\"sortTable('mainTable', 0, 'number')\">No</th>
    <th onclick=\"sortTable('mainTable', 1, 'string')\">ID Production</th>
    <th onclick=\"sortTable('mainTable', 2, 'date')\">Tanggal</th>
    <th onclick=\"sortTable('mainTable', 3, 'number')\">Qty OK</th>
    <th onclick=\"sortTable('mainTable', 4, 'number')\">Defect</th>
    <th onclick=\"sortTable('mainTable', 5, 'number')\">LT (hr)</th>
    <th onclick=\"sortTable('mainTable', 6, 'string')\">Cavity</th>
    <th onclick=\"sortTable('mainTable', 7, 'number')\">CT Aktual</th>
    <th onclick=\"sortTable('mainTable', 8, 'number')\">NWT</th>
    <th onclick=\"sortTable('mainTable', 9, 'number')\">OT</th>
    <th onclick=\"sortTable('mainTable', 10, 'number')\">Prod Time</th>
    <th onclick=\"sortTable('mainTable', 11, 'number')\">WorkHour</th>
    <th onclick=\"sortTable('mainTable', 12, 'number')\">Calc Gross</th>
    <th onclick=\"sortTable('mainTable', 13, 'number')\">DB Gross</th>
    <th onclick=\"sortTable('mainTable', 14, 'number')\">Gross</th>
    <th onclick=\"sortTable('mainTable', 15, 'number')\">Calc Nett</th>
    <th onclick=\"sortTable('mainTable', 16, 'number')\">DB Nett</th>
    <th onclick=\"sortTable('mainTable', 17, 'number')\">Nett</th>
</tr>";
echo "</thead>";
echo "<tbody>";

$no = 1;
while ($row = $result->fetch_assoc()) {
    
    // Extract values
    $qty_ok = floatval($row['qty_ok']);
    $defect = floatval($row['qty_ng']);
    $LT = floatval($row['qty_lt']); // Loss Time in hours
    $cavity = floatval($row['cavity']) ?: 1;
    $cavity2 = floatval($row['cavity2']) ?: $cavity;
    $ct_aktual = floatval($row['ct_mc_aktual']);
    $nwt = floatval($row['nwt_mp']);
    $ot = floatval($row['ot_mp']);
    
    $db_nett = floatval($row['nett_prod']);
    $db_gross = floatval($row['gross_prod']);
    $db_prod_time = floatval($row['production_time']);
    
    // Track zero values
    if ($qty_ok == 0) $zero_qty_count++;
    if ($ct_aktual == 0) $zero_ct_count++;
    
    // Calculate Production Time
    $kalkulasi = $qty_ok + $defect;
    $hasil_time = ($cavity > 0 && $ct_aktual > 0) ? ($kalkulasi / $cavity) * ($ct_aktual / 3600) : 0;
    $production_time = round($hasil_time, 1);
    
    // Calculate WorkHour
    $WorkHour = ($production_time + $LT) - $ot;
    
    // Calculate Gross
    $grossProduction = 0;
    $calc_gross = 0;
    if (($WorkHour + $ot) > 0 && $qty_ok > 0 && $cavity2 > 0) {
        $grossProduction = 3600 / (($qty_ok / $cavity2) / ($WorkHour + $ot));
        $calc_gross = customRound($grossProduction);
    }
    
    // Calculate Nett
    $NProd = 0;
    $calc_nett = 0;
    if (($WorkHour + $ot - $LT) > 0 && $qty_ok > 0 && $cavity2 > 0) {
        $NProd = 3600 / (($qty_ok / $cavity2) / ($WorkHour + $ot - $LT));
        $calc_nett = customRound($NProd);
    }
    
    // Check matches
    $gross_diff = abs($calc_gross - $db_gross);
    $nett_diff = abs($calc_nett - $db_nett);
    
    $gross_match = ($gross_diff < 1) ? 
        "<span class='match'>OK</span>" : 
        "<span class='mismatch'>" . round($calc_gross - $db_gross, 0) . "</span>";
    
    $nett_match = ($nett_diff < 1) ? 
        "<span class='match'>OK</span>" : 
        "<span class='mismatch'>" . round($calc_nett - $db_nett, 0) . "</span>";
    
    // Track mismatches
    $is_mismatch = false;
    if ($gross_diff >= 1) {
        $mismatch_gross_count++;
        $is_mismatch = true;
    }
    if ($nett_diff >= 1) {
        $mismatch_nett_count++;
        $is_mismatch = true;
    }
    if ($is_mismatch) {
        $mismatch_records[] = [
            'id' => $row['id_production'],
            'tanggal' => $row['tanggal'],
            'gross_diff' => round($calc_gross - $db_gross, 0),
            'nett_diff' => round($calc_nett - $db_nett, 0),
            'calc_gross' => $calc_gross,
            'db_gross' => $db_gross,
            'calc_nett' => $calc_nett,
            'db_nett' => $db_nett
        ];
    }
    
    $row_class = $is_mismatch ? "class='mismatch-row'" : "";
    
    echo "<tr $row_class>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . $row['id_production'] . "</td>";
    echo "<td>" . $row['tanggal'] . "</td>";
    echo "<td>" . $qty_ok . "</td>";
    echo "<td>" . $defect . "</td>";
    echo "<td>" . $LT . "</td>";
    echo "<td>" . $cavity . "/" . $cavity2 . "</td>";
    echo "<td>" . $ct_aktual . "</td>";
    echo "<td>" . $nwt . "</td>";
    echo "<td>" . $ot . "</td>";
    echo "<td>" . $production_time . "</td>";
    echo "<td>" . round($WorkHour, 2) . "</td>";
    echo "<td>" . $calc_gross . "</td>";
    echo "<td>" . $db_gross . "</td>";
    echo "<td>" . $gross_match . "</td>";
    echo "<td>" . $calc_nett . "</td>";
    echo "<td>" . $db_nett . "</td>";
    echo "<td>" . $nett_match . "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";

// Summary Section
echo "<div class='section'>";
echo "<h2>Summary</h2>";

$match_rate_gross = $total_records > 0 ? round((($total_records - $mismatch_gross_count) / $total_records) * 100, 2) : 0;
$match_rate_nett = $total_records > 0 ? round((($total_records - $mismatch_nett_count) / $total_records) * 100, 2) : 0;

if ($mismatch_gross_count > 0 || $mismatch_nett_count > 0) {
    echo "<div class='summary-box error'>";
} else {
    echo "<div class='summary-box success'>";
}

echo "<h3>Calculation Results:</h3>";
echo "<table>";
echo "<tr><th>Metric</th><th>Count</th><th>Percentage</th></tr>";
echo "<tr><td><b>Total Records</b></td><td>$total_records</td><td>100%</td></tr>";
echo "<tr><td>Gross Match</td><td>" . ($total_records - $mismatch_gross_count) . "</td><td>$match_rate_gross%</td></tr>";
echo "<tr class='error'><td>Gross Mismatch</td><td>$mismatch_gross_count</td><td>" . (100 - $match_rate_gross) . "%</td></tr>";
echo "<tr><td>Nett Match</td><td>" . ($total_records - $mismatch_nett_count) . "</td><td>$match_rate_nett%</td></tr>";
echo "<tr class='error'><td>Nett Mismatch</td><td>$mismatch_nett_count</td><td>" . (100 - $match_rate_nett) . "%</td></tr>";
echo "<tr><td>Zero Qty OK</td><td>$zero_qty_count</td><td>-</td></tr>";
echo "<tr><td>Zero CT Aktual</td><td>$zero_ct_count</td><td>-</td></tr>";
echo "</table>";
echo "</div>";

// Show mismatched records
if (count($mismatch_records) > 0) {
    echo "<h3>Mismatched Records (first 50):</h3>";
    echo "<p style='color:#666;font-size:11px;'><i>Click on ID Production to see detailed calculation breakdown</i></p>";
    echo "<table>";
    echo "<tr>
        <th>No</th>
        <th>ID Production</th>
        <th>Tanggal</th>
        <th>Calc Gross</th>
        <th>DB Gross</th>
        <th>Gross Diff</th>
        <th>Calc Nett</th>
        <th>DB Nett</th>
        <th>Nett Diff</th>
        <th>Action</th>
    </tr>";
    
    $i = 1;
    foreach (array_slice($mismatch_records, 0, 50) as $rec) {
        echo "<tr>";
        echo "<td>" . $i++ . "</td>";
        echo "<td><a href='calculate_gross_nett_detail.php?id=" . urlencode($rec['id']) . "' target='_blank' style='color:#2196F3;font-weight:bold;'>" . $rec['id'] . "</a></td>";
        echo "<td>" . $rec['tanggal'] . "</td>";
        echo "<td>" . $rec['calc_gross'] . "</td>";
        echo "<td>" . $rec['db_gross'] . "</td>";
        echo "<td class='mismatch'>" . $rec['gross_diff'] . "</td>";
        echo "<td>" . $rec['calc_nett'] . "</td>";
        echo "<td>" . $rec['db_nett'] . "</td>";
        echo "<td class='mismatch'>" . $rec['nett_diff'] . "</td>";
        echo "<td><a href='calculate_gross_nett_detail.php?id=" . urlencode($rec['id']) . "' target='_blank' style='background-color:#2196F3;color:white;padding:3px 8px;text-decoration:none;border-radius:3px;font-size:10px;'>Detail</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    if (count($mismatch_records) > 50) {
        echo "<p><i>Showing first 50 of " . count($mismatch_records) . " mismatched records</i></p>";
    }
}

echo "</div>";

// Monthly breakdown
echo "<div class='section'>";
echo "<h2>Monthly Breakdown</h2>";

$sql_monthly = "SELECT 
                    DATE_FORMAT(po.tanggal, '%Y-%m') as bulan,
                    COUNT(*) as total,
                    SUM(CASE WHEN po.nett_prod = 0 AND po.qty_ok > 0 THEN 1 ELSE 0 END) as zero_nett,
                    SUM(CASE WHEN po.gross_prod = 0 AND po.qty_ok > 0 THEN 1 ELSE 0 END) as zero_gross
                FROM t_production_op po
                WHERE po.tanggal BETWEEN '$start_date' AND '$end_date'
                GROUP BY DATE_FORMAT(po.tanggal, '%Y-%m')
                ORDER BY bulan";

$result_monthly = $conn->query($sql_monthly);

echo "<table>";
echo "<tr><th>Bulan</th><th>Total Records</th><th>Zero Nett</th><th>Zero Gross</th></tr>";
while ($row = $result_monthly->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['bulan'] . "</td>";
    echo "<td>" . $row['total'] . "</td>";
    echo "<td>" . $row['zero_nett'] . "</td>";
    echo "<td>" . $row['zero_gross'] . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

$conn->close();
?>
