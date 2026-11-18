<?php
/**
 * Analyze the production_time calculation for Mesin 29, Shift 3
 */

echo "<h2>Production Time Analysis for Mesin 29, Shift 3</h2>";
echo "<hr>";

// CSV values
$qty_ok = 8250;  // From "8.250"
$qty_ng = 0;
$cavity = 1;
$ct_mc_aktual = 27;  // From "27" (in seconds)

echo "<h3>Current Calculation (WRONG):</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Parameter</th><th>Value</th></tr>";
echo "<tr><td>Quantity OK</td><td>" . number_format($qty_ok) . " pieces</td></tr>";
echo "<tr><td>Quantity NG</td><td>" . number_format($qty_ng) . " pieces</td></tr>";
echo "<tr><td>Total Quantity</td><td>" . number_format($qty_ok + $qty_ng) . " pieces</td></tr>";
echo "<tr><td>Cavity</td><td>$cavity</td></tr>";
echo "<tr><td>CT Actual</td><td>$ct_mc_aktual seconds/piece</td></tr>";
echo "<tr><td>Working Hours (W H)</td><td>7.7 hours</td></tr>";
echo "</table>";

echo "<h4>Formula:</h4>";
echo "<code>production_time = (qty_ok + qty_ng) / cavity * (ct_mc_aktual / 3600)</code><br>";

$production_time = (($qty_ok + $qty_ng) / $cavity) * ($ct_mc_aktual / 3600);
echo "<h4>Calculation:</h4>";
echo "production_time = ($qty_ok + $qty_ng) / $cavity * ($ct_mc_aktual / 3600)<br>";
echo "production_time = $qty_ok * " . ($ct_mc_aktual / 3600) . "<br>";
echo "production_time = $qty_ok * " . number_format($ct_mc_aktual / 3600, 6) . " hours<br>";
echo "production_time = <strong>" . number_format($production_time, 2) . " hours</strong>";

echo "<h3 style='color: red;'>PROBLEM: This is IMPOSSIBLE!</h3>";
echo "<ul>";
echo "<li><strong>Working time available:</strong> 7.7 hours (from W H column)</li>";
echo "<li><strong>Calculated production time:</strong> " . number_format($production_time, 2) . " hours</li>";
echo "<li><strong>Impossible!</strong> You cannot produce for 61.88 hours when you only worked 7.7 hours!</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>Root Cause Analysis:</h3>";

echo "<h4>Possible Issue 1: CT Actual is in WRONG unit</h4>";
echo "<p>If CT Actual = 27 means <strong>27 seconds</strong> (current assumption):</p>";
echo "<ul>";
echo "<li>Time to produce 8250 pieces = 8250 × 27 seconds = 222,750 seconds</li>";
echo "<li>222,750 seconds ÷ 3600 = 61.875 hours ❌ IMPOSSIBLE!</li>";
echo "</ul>";

echo "<p>If CT Actual = 27 means <strong>0.27 seconds</strong>:</p>";
echo "<ul>";
echo "<li>Time to produce 8250 pieces = 8250 × 0.27 seconds = 2,227.5 seconds</li>";
echo "<li>2,227.5 seconds ÷ 3600 = 0.62 hours ✅ Possible!</li>";
echo "</ul>";

echo "<p>If CT Actual = 27 means <strong>27 centiseconds (0.27s)</strong>:</p>";
$ct_fixed = 0.27;
$production_time_fixed = (($qty_ok + $qty_ng) / $cavity) * ($ct_fixed / 3600);
echo "<ul>";
echo "<li>Time to produce 8250 pieces = 8250 × 0.27 = 2,227.5 seconds</li>";
echo "<li>2,227.5 seconds ÷ 3600 = <strong>" . number_format($production_time_fixed, 2) . " hours</strong> ✅</li>";
echo "<li>Machine utilization: " . number_format(($production_time_fixed / 7.7) * 100, 1) . "%</li>";
echo "</ul>";

echo "<h4>Possible Issue 2: OK quantity is WRONG</h4>";
echo "<p>If OK = 8250 is actually supposed to be different:</p>";
echo "<ul>";
echo "<li>Maybe it should be 825 pieces (not 8250)?</li>";
echo "<li>825 × 27 seconds = 22,275 seconds = 6.19 hours ✅ Possible!</li>";
echo "</ul>";

echo "<h4>Possible Issue 3: CT Actual should be divided by 100</h4>";
echo "<p>The CSV value '27' might need to be divided by 100 to get 0.27 seconds:</p>";
$ct_adjusted = 27 / 100;
$production_time_adjusted = (($qty_ok + $qty_ng) / $cavity) * (($ct_adjusted) / 3600);
echo "<ul>";
echo "<li>CT Actual = 27 ÷ 100 = 0.27 seconds/piece</li>";
echo "<li>Production time = 8250 × (0.27 / 3600) = <strong>" . number_format($production_time_adjusted, 2) . " hours</strong> ✅</li>";
echo "<li>This fits within 7.7 hours!</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>Checking Other Rows</h3>";
echo "<p>Let's see what CT Actual values are in the CSV and if they make sense:</p>";

// Sample from CSV
$sample_rows = [
    ['no' => '55', 'ok' => '250', 'ct' => '25', 'wh' => '0,9'],
    ['no' => '81', 'ok' => '1.750', 'ct' => '26,7', 'wh' => '4,3'],
    ['no' => '3', 'ok' => '8.250', 'ct' => '27', 'wh' => '7,7'],
];

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>No.</th><th>OK</th><th>CT Actual</th><th>W H</th><th>Calc Prod Time</th><th>Possible?</th></tr>";

foreach ($sample_rows as $row) {
    $ok = intval(convert_decimal_for_test($row['ok']));
    $ct = floatval(convert_decimal_for_test($row['ct']));
    $wh = floatval(convert_decimal_for_test($row['wh']));

    $prod_time = ($ok / 1) * ($ct / 3600);
    $possible = $prod_time <= $wh ? '✅ Yes' : '❌ NO';

    echo "<tr>";
    echo "<td>{$row['no']}</td>";
    echo "<td>" . number_format($ok) . "</td>";
    echo "<td>$ct</td>";
    echo "<td>$wh</td>";
    echo "<td>" . number_format($prod_time, 2) . " hrs</td>";
    echo "<td><strong>$possible</strong></td>";
    echo "</tr>";
}

echo "</table>";

function convert_decimal_for_test($value) {
    if (strpos($value, ',') !== false) {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
    }
    return floatval($value);
}

echo "<hr>";
echo "<h3 style='color: red;'>CONCLUSION:</h3>";
echo "<p><strong>The CT Actual values in the CSV appear to be in CENTISECONDS (need ÷ 100) or the values are wrong!</strong></p>";
echo "<p>Current code treats '27' as 27 seconds, but it should be 0.27 seconds.</p>";
?>
