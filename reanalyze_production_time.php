<?php
/**
 * Re-analyze production time - CT is in seconds
 */

echo "<h2>Production Time Analysis - CT in Seconds</h2>";
echo "<hr>";

// If CT is 27 SECONDS per piece
// And you produced 8250 pieces
// You need: 8250 × 27 = 222,750 seconds = 61.875 hours

echo "<h3>The Math:</h3>";
echo "<ul>";
echo "<li><strong>OK:</strong> 8250 pieces</li>";
echo "<li><strong>CT Actual:</strong> 27 seconds/piece</li>";
echo "<li><strong>Time needed:</strong> 8250 × 27 = 222,750 seconds</li>";
echo "<li><strong>In hours:</strong> 222,750 ÷ 3600 = 61.875 hours</li>";
echo "</ul>";

echo "<h3>But you only have:</h3>";
echo "<ul>";
echo "<li><strong>W H (Working Hours):</strong> 7.7 hours</li>";
echo "</ul>";

echo "<h3 style='color: red;'>CONCLUSION: Something is WRONG!</h3>";

echo "<p><strong>Either:</strong></p>";
echo "<ol>";
echo "<li>The OK quantity is wrong (should be 825, not 8250)</li>";
echo "<li>The CT Actual is wrong (not 27 seconds)</li>";
echo "<li>The W H is wrong (not 7.7 hours)</li>";
echo "<li>There are multiple machines/cavities (cavity > 1)</li>";
echo "<li>The data is from multiple shifts/days combined</li>";
echo "</ol>";

echo "<hr>";
echo "<h3>Let's check the CSV more carefully for Mesin 29, Shift 3:</h3>";

echo "<p><strong>CSV Row:</strong></p>";
echo "<pre>";
echo "3,2026-01-02,29,3,B,8,\"7,7\",0,\"0,3\",180.133.800,\"SPUR GEAR,21.6,PUMP\",26010229C,0,8.250,,8.533,0,31,27,27,28";
echo "</pre>";

echo "<p><strong>Analyzing each field:</strong></p>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Value</th><th>Meaning</th></tr>";
echo "<tr><td>Mesin</td><td>29</td><td>Machine number 29</td></tr>";
echo "<tr><td>Shift</td><td>3</td><td>Shift 3</td></tr>";
echo "<tr><td>W H</td><td>7,7</td><td>7.7 working hours</td></tr>";
echo "<tr><td>O T</td><td>0</td><td>0 overtime hours</td></tr>";
echo "<tr><td>Total LT</td><td>0,3</td><td>0.3 loss time hours</td></tr>";
echo "<tr><td>OK</td><td>8.250</td><td>8,250 pieces produced</td></tr>";
echo "<tr><td>Target Qty</td><td>8.533</td><td>Target 8,533 pieces</td></tr>";
echo "<tr><td>CT Std</td><td>31</td><td>Standard 31 seconds/piece</td></tr>";
echo "<tr><td>CT Actual</td><td>27</td><td>Actual 27 seconds/piece</td></tr>";
echo "</table>";

echo "<hr>";
echo "<h3 style='color: red;'>THE REAL QUESTION:</h3>";
echo "<p><strong>How can you produce 8,250 pieces in 7.7 hours?</strong></p>";

echo "<p><strong>Math check:</strong></p>";
echo "<ul>";
echo "<li>Total time available: 7.7 hours = 27,720 seconds</li>";
echo "<li>If CT = 27 seconds/piece: 27,720 ÷ 27 = <strong>1,027 pieces max</strong></li>";
echo "<li><strong>But you produced 8,250 pieces!</strong></li>";
echo "</ul>";

echo "<p><strong>This means:</strong></p>";
echo "<ul>";
echo "<li><strong>Actual CT:</strong> 27,720 ÷ 8,250 = <strong>3.36 seconds/piece</strong></li>";
echo "<li><strong>NOT 27 seconds/piece as shown in CSV!</strong></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>POSSIBLE EXPLANATIONS:</h3>";

echo "<h4>1. Wrong OK value in CSV (most likely)</h4>";
echo "<p>Maybe OK should be <strong>825</strong> not <strong>8.250</strong>?</p>";
echo "<ul>";
echo "<li>825 pieces in 7.7 hours</li>";
echo "<li>825 × 27 = 22,275 seconds = 6.19 hours</li>";
echo "<li>This fits within 7.7 hours! ✅</li>";
echo "<li><strong>Target Qty 8.533</strong> suggests target is in thousands format</li>";
echo "</ul>";

echo "<h4>2. Cavity > 1</h4>";
echo "<p>If cavity = 8 (8 pieces per cycle):</p>";
echo "<ul>";
echo "<li>8250 ÷ 8 = 1,031 cycles</li>";
echo "<li>1,031 × 27 = 27,837 seconds = 7.73 hours</li>";
echo "<li>This fits! ✅</li>";
echo "</ul>";

echo "<h4>3. Multiple machines</h4>";
echo "<p>If multiple machines running same product:</p>";
echo "<ul>";
echo "<li>Maybe Mesin 29 represents a group of machines?</li>";
echo "</ul>";

echo "<h4>4. Wrong CT Actual value</h4>";
echo "<p>Maybe CT Actual should be 3.36 seconds, not 27?</p>";
echo "<ul>";
echo "<li>This would match the actual production rate</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>CHECK: What is the cavity value in the database/select_bom?</h3>";
echo "<p><strong>Question:</strong> What is the cavity for product ID 180.133.800 (SPUR GEAR,21.6,PUMP)?</p>";
echo "<p><strong>We need to check the select_bom table to see the cavity value!</strong></p>";

echo "<hr>";
echo "<h3 style='color: blue;'>RECOMMENDATION:</h3>";
echo "<p><strong>Before fixing anything, we need to know:</strong></p>";
echo "<ol>";
echo "<li>What is the actual cavity value for this product in select_bom?</li>";
echo "<li>Is OK = 8250 correct or should it be 825?</li>";
echo "<li>Is the production_time calculation supposed to use the cavity from select_bom?</li>";
echo "<li>Why is cavity hardcoded to 1 in the import code?</li>";
echo "</ol>";

echo "<p><strong>The code currently has:</strong></p>";
echo "<pre>";
echo "\$cavity = 1; // Default, can be enhanced to lookup from select_bom";
echo "</pre>";
echo "<p><strong>This might be the bug! It should lookup the actual cavity from the database!</strong></p>";
?>
