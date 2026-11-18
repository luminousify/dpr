<?php
/**
 * Test convert_decimal function directly
 */

function convert_decimal($value)
{
    if (empty($value)) {
        return 0;
    }

    echo "Input: " . var_export($value, true) . " (type: " . gettype($value) . ")<br>";

    // If contains comma, it's Indonesian format with decimal
    if (strpos($value, ',') !== false) {
        $value = str_replace('.', '', $value);  // "1.440,5" -> "1440,5"
        $value = str_replace(',', '.', $value); // "1440,5" -> "1440.5"
        echo "After comma replacement: $value<br>";
    } elseif (strpos($value, '.') !== false) {
        // Has dots but no comma - could be Indonesian thousand separator or standard decimal
        // Check if it matches Indonesian thousand separator pattern
        // Pattern: Number ends with 3 digits after last dot (thousand separator)
        $parts = explode('.', $value);
        $lastPart = end($parts);

        echo "Parts: " . var_export($parts, true) . "<br>";

        // If last part has exactly 3 digits and there are multiple parts,
        // it's likely Indonesian thousand separator format
        if (count($parts) > 1 && strlen($lastPart) == 3) {
            // Check if all parts (except maybe first) have 3 digits
            $isIndonesianFormat = true;
            foreach ($parts as $i => $part) {
                if ($i > 0 && strlen($part) != 3) {
                    $isIndonesianFormat = false;
                    break;
                }
            }

            if ($isIndonesianFormat) {
                // Remove all dots (thousand separators)
                $value = str_replace('.', '', $value); // "1.440" -> "1440"
                echo "Indonesian thousand format detected, removed dots: $value<br>";
            }
            // Else: Standard decimal format, keep as is (e.g., "1440.5")
        }
        // Else: Standard decimal format or doesn't match pattern
    }

    // Convert to float
    $value = floatval($value);

    echo "Final floatval result: $value<br>";
    echo "<hr>";

    return $value;
}

echo "<h2>Testing convert_decimal() function</h2>";
echo "<hr>";

// Test cases from CSV
echo "<h3>Test Case 1: W H = '7,7' (Mesin 29, Shift 3)</h3>";
$result1 = convert_decimal("7,7");
echo "<strong>Result: $result1</strong>";
echo "<hr>";

echo "<h3>Test Case 2: W H = '7,3' (Mesin 29, Shift 2)</h3>";
$result2 = convert_decimal("7,3");
echo "<strong>Result: $result2</strong>";
echo "<hr>";

echo "<h3>Test Case 3: W H = '0,9'</h3>";
$result3 = convert_decimal("0,9");
echo "<strong>Result: $result3</strong>";
echo "<hr>";

echo "<h3>Test Case 4: W H = '4,3'</h3>";
$result4 = convert_decimal("4,3");
echo "<strong>Result: $result4</strong>";
echo "<hr>";

echo "<h3>Test Case 5: W H = '7,5'</h3>";
$result5 = convert_decimal("7,5");
echo "<strong>Result: $result5</strong>";
echo "<hr>";

echo "<h3>Test Case 6: W H = '7,6'</h3>";
$result6 = convert_decimal("7,6");
echo "<strong>Result: $result6</strong>";
echo "<hr>";

echo "<h2>Summary</h2>";
echo "<table border='1'>";
echo "<tr><th>Input</th><th>Output</th></tr>";
echo "<tr><td>'7,7'</td><td>$result1</td></tr>";
echo "<tr><td>'7,3'</td><td>$result2</td></tr>";
echo "<tr><td>'0,9'</td><td>$result3</td></tr>";
echo "<tr><td>'4,3'</td><td>$result4</td></tr>";
echo "<tr><td>'7,5'</td><td>$result5</td></tr>";
echo "<tr><td>'7,6'</td><td>$result6</td></tr>";
echo "</table>";
?>
