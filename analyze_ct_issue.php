<?php
/**
 * Analyze the CT Actual "24,3" issue
 */

echo "<h2>Analyzing CT Actual = '24,3'</h2>";

// CSV value: "24,3"
// With comma as decimal separator: 24.3 seconds

echo "<h3>If CT Actual = 24.3 seconds (current interpretation):</h3>";
$qty_ok = 4750;
$cavity = 2;
$ct = 24.3;
$wh = 8;

$cycles = $qty_ok / $cavity;
$prod_time = $cycles * ($ct / 3600);

echo "<ul>";
echo "<li>OK: $qty_ok pieces</li>";
echo "<li>Cavity: $cavity</li>";
echo "<li>Cycles: " . number_format($cycles) . "</li>";
echo "<li>CT Actual: $ct seconds</li>";
echo "<li>Production Time: " . number_format($prod_time, 2) . " hours</li>";
echo "<li>Working Hours: $wh hours</li>";
echo "</ul>";

echo "<p style='color: red;'><strong>PROBLEM:</strong> $prod_time hours > $wh hours (IMPOSSIBLE!)</p>";

echo "<hr>";

echo "<h3>Possible Explanation 1: CT Actual is '24' not '24,3'</h3>";
echo "<p>Maybe the comma is a thousand separator and CT should be 24 seconds?</p>";
$ct2 = 24;
$prod_time2 = ($qty_ok / $cavity) * ($ct2 / 3600);
echo "<ul>";
echo "<li>CT Actual: $ct2 seconds</li>";
echo "<li>Production Time: " . number_format($prod_time2, 2) . " hours</li>";
echo "<li>Fits in $wh hours? " . ($prod_time2 <= $wh ? '✅ YES' : '❌ NO') . "</li>";
echo "</ul>";

echo "<hr>";

echo "<h3>Possible Explanation 2: CT Actual should be divided by 100</h3>";
echo "<p>Maybe '24,3' means 0.243 seconds?</p>";
$ct3 = 24.3 / 100;
$prod_time3 = ($qty_ok / $cavity) * ($ct3 / 3600);
echo "<ul>";
echo "<li>CT Actual: $ct3 seconds</li>";
echo "<li>Production Time: " . number_format($prod_time3, 2) . " hours</li>";
echo "<li>Fits in $wh hours? " . ($prod_time3 <= $wh ? '✅ YES' : '❌ NO') . "</li>";
echo "</ul>";

echo "<hr>";

echo "<h3>Possible Explanation 3: OK quantity is wrong in CSV</h3>";
echo "<p>Maybe OK should be 475, not 4750?</p>";
$qty_ok2 = 475;
$prod_time4 = ($qty_ok2 / $cavity) * ($ct / 3600);
echo "<ul>";
echo "<li>OK: $qty_ok2 pieces</li>";
echo "<li>Production Time: " . number_format($prod_time4, 2) . " hours</li>";
echo "<li>Fits in $wh hours? " . ($prod_time4 <= $wh ? '✅ YES' : '❌ NO') . "</li>";
echo "</ul>";

echo "<hr>";

echo "<h3>Let's check the Target Qty for clues:</h3>";
echo "<p>CSV shows Target Qty = '4.741'</p>";
echo "<p>If Target Qty also has thousand separator dots:</p>";
echo "<ul>";
echo "<li>Target Qty = 4.741 → 4,741 pieces</li>";
echo "<li>OK = 4.750 → 4,750 pieces</li>";
echo "<li>Target (4,741) ≈ Actual (4,750) ✅ This makes sense!</li>";
echo "</ul>";

echo "<hr>";

echo "<h3 style='color: red;'>CONCLUSION:</h3>";
echo "<p><strong>The real issue is the CT Actual value!</strong></p>";
echo "<p>With CT = 24.3 seconds and cavity = 2, you get 16.03 hours.</p>";
echo "<p>But you only have 8 hours to work!</p>";
echo "<p><strong>Either:</strong></p>";
echo "<ol>";
echo "<li>CT Actual is wrong (should be much lower, like 12 seconds)</li>";
echo "<li>OK quantity is wrong (should be much lower)</li>";
echo "<li>There's something else wrong with the data</li>";
echo "</ol>";

// Calculate what CT should be
$required_ct = ($wh * 3600) / ($qty_ok / $cavity);
echo "<p><strong>To produce 4,750 pieces in 8 hours with cavity=2:</strong></p>";
echo "<ul>";
echo "<li>Required cycles: " . number_format($qty_ok / $cavity) . "</li>";
echo "<li>Seconds available: $wh hours × 3600 = " . ($wh * 3600) . " seconds</li>";
echo "<li>Required CT: " . number_format($required_ct, 2) . " seconds/piece</li>";
echo "</ul>";
echo "<p><strong>But CSV shows CT Actual = 24.3 seconds!</strong></p>";
echo "<p style='color: red;'>There's a data discrepancy in the CSV file itself!</p>";
?>
