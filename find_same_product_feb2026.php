<?php
/**
 * Find February 2026 records with same product as 2601020555231147
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

// First, get the original record to find its id_bom
$original_id = '2601020555231147';

$sql_original = "SELECT id_production, tanggal, id_bom FROM t_production_op WHERE id_production = '$original_id'";
$result_original = $conn->query($sql_original);

if ($result_original->num_rows == 0) {
    die("Original record not found: $original_id");
}

$original = $result_original->fetch_assoc();
$id_bom = $original['id_bom'];

// Custom round function - defined once before the loop
function customRoundFeb($value) {
    $remainder = $value % 1;
    if ($remainder <= 0.5) {
        return floor($value);
    } else {
        return ceil($value);
    }
}

echo "<h2>Original Record</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Tanggal</th><th>id_bom</th></tr>";
echo "<tr><td>{$original['id_production']}</td><td>{$original['tanggal']}</td><td>{$id_bom}</td></tr>";
echo "</table>";

// Now find all February 2026 records with the same id_bom
$sql_february = "SELECT 
                    po.id_production,
                    po.tanggal,
                    po.shift,
                    po.operator,
                    po.id_bom,
                    po.qty_ok,
                    po.qty_ng,
                    po.qty_lt,
                    po.nett_prod,
                    po.gross_prod,
                    po.production_time,
                    po.ct_mc_aktual,
                    po.cavity,
                    po.cavity2
                FROM t_production_op po
                WHERE po.id_bom = '$id_bom'
                  AND po.tanggal BETWEEN '2026-02-01' AND '2026-02-28'
                ORDER BY po.tanggal, po.shift, po.id_production";

$result_february = $conn->query($sql_february);

echo "<h2>February 2026 Records with Same id_bom: $id_bom</h2>";
echo "<p>Total records: " . $result_february->num_rows . "</p>";

if ($result_february->num_rows > 0) {
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
    echo "<tr style='background-color:#2196F3;color:white;'>
            <th>No</th>
            <th>ID Production</th>
            <th>Tanggal</th>
            <th>Shift</th>
            <th>Operator</th>
            <th>Qty OK</th>
            <th>NG</th>
            <th>LT</th>
            <th>CT</th>
            <th>Cavity</th>
            <th>Prod Time</th>
            <th>DB Gross</th>
            <th>DB Nett</th>
          </tr>";
    
    $no = 1;
    while ($row = $result_february->fetch_assoc()) {
        // Calculate values using the formula
        $qty_ok = floatval($row['qty_ok']);
        $defect = floatval($row['qty_ng']);
        $LT = floatval($row['qty_lt']);
        $cavity = floatval($row['cavity']) ?: 1;
        $cavity2 = floatval($row['cavity2']) ?: $cavity;
        $ct_aktual = floatval($row['ct_mc_aktual']);
        
        $kalkulasi = $qty_ok + $defect;
        $hasil_time = ($cavity > 0 && $ct_aktual > 0) ? ($kalkulasi / $cavity) * ($ct_aktual / 3600) : 0;
        $production_time = round($hasil_time, 1);
        $WorkHour = ($production_time + $LT);
        
        // Gross
        $calc_gross = 0;
        if ($WorkHour > 0 && $qty_ok > 0 && $cavity2 > 0) {
            $grossProduction = 3600 / (($qty_ok / $cavity2) / $WorkHour);
            $calc_gross = customRoundFeb($grossProduction);
        }
        
        // Nett
        $calc_nett = 0;
        if (($WorkHour - $LT) > 0 && $qty_ok > 0 && $cavity2 > 0) {
            $NProd = 3600 / (($qty_ok / $cavity2) / ($WorkHour - $LT));
            $calc_nett = customRoundFeb($NProd);
        }
        
        $db_gross = floatval($row['gross_prod']);
        $db_nett = floatval($row['nett_prod']);
        
        $gross_match = (abs($calc_gross - $db_gross) < 1) ? "OK" : "DIFF: " . ($calc_gross - $db_gross);
        $nett_match = (abs($calc_nett - $db_nett) < 1) ? "OK" : "DIFF: " . ($calc_nett - $db_nett);
        
        $row_style = ($gross_match != "OK" || $nett_match != "OK") ? "background-color:#fff3cd;" : "";
        
        echo "<tr style='$row_style'>";
        echo "<td>" . $no++ . "</td>";
        echo "<td><a href='calculate_gross_nett_detail.php?id=" . urlencode($row['id_production']) . "' target='_blank' style='color:#2196F3;'>" . $row['id_production'] . "</a></td>";
        echo "<td>" . $row['tanggal'] . "</td>";
        echo "<td>" . $row['shift'] . "</td>";
        echo "<td>" . $row['operator'] . "</td>";
        echo "<td>" . $row['qty_ok'] . "</td>";
        echo "<td>" . $row['qty_ng'] . "</td>";
        echo "<td>" . $row['qty_lt'] . "</td>";
        echo "<td>" . $row['ct_mc_aktual'] . "</td>";
        echo "<td>" . $cavity . "/" . $cavity2 . "</td>";
        echo "<td>" . $production_time . "</td>";
        echo "<td>" . $db_gross . " <span style='color:" . ($gross_match == "OK" ? "green" : "red") . ";font-size:10px;'>($gross_match)</span></td>";
        echo "<td>" . $db_nett . " <span style='color:" . ($nett_match == "OK" ? "green" : "red") . ";font-size:10px;'>($nett_match)</span></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No records found in February 2026 with the same id_bom</p>";
}

$conn->close();
?>
