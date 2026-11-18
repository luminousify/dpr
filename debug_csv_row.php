<?php
/**
 * Debug CSV parsing for the specific row
 */

$csv_file = 'C:\Users\Pratama\Downloads\dpr tgl 2 - Sheet1.csv';

echo "<h2>Debugging CSV Row for Mesin 29, Shift 3</h2>";
echo "<hr>";

if (($handle = fopen($csv_file, "r")) !== FALSE) {
    // Get headers
    $headers = fgetcsv($handle, 0, ",");

    echo "<h3>Headers (first 20 columns):</h3>";
    echo "<pre>";
    print_r(array_slice($headers, 0, 20));
    echo "</pre>";

    // Find column index for 'W H'
    $wh_index = array_search('W H', $headers);
    echo "<p><strong>'W H' column index:</strong> $wh_index</p>";

    // Read data rows
    $row_number = 0;
    while (($rowData = fgetcsv($handle, 0, ",")) !== FALSE) {
        $row_number++;

        $row = array_combine($headers, $rowData);

        // Look for Mesin 29, Shift 3
        if (isset($row['Mesin']) && isset($row['Shift']) &&
            $row['Mesin'] == '29' && $row['Shift'] == '3') {

            echo "<h3>Found Mesin 29, Shift 3 (Row $row_number)</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Column</th><th>Key</th><th>Raw Value</th><th>Type</th></tr>";

            foreach ($headers as $i => $header) {
                if ($i < 20) { // Show first 20 columns
                    $value = isset($row[$header]) ? $row[$header] : '[NOT SET]';
                    $type = gettype($value);
                    $display = strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;

                    // Highlight W H column
                    if ($header == 'W H') {
                        echo "<tr style='background: yellow;'><td>$i</td><td><strong>$header</strong></td><td><strong>'" . htmlspecialchars($display) . "'</strong></td><td>$type</td></tr>";
                    } else {
                        echo "<tr><td>$i</td><td>$header</td><td>'" . htmlspecialchars($display) . "'</td><td>$type</td></tr>";
                    }
                }
            }
            echo "</table>";

            // Test if W H is empty
            echo "<h3>Empty Check:</h3>";
            echo "<pre>";
            echo "isset(\$row['W H']): " . (isset($row['W H']) ? 'true' : 'false') . "\n";
            echo "empty(\$row['W H']): " . (empty($row['W H']) ? 'true' : 'false') . "\n";
            echo "!empty(\$row['W H']): " . (!empty($row['W H']) ? 'true' : 'false') . "\n";
            echo "\$row['W H'] value: ";
            var_export($row['W H']);
            echo "\n";
            echo "</pre>";

            // Test the actual condition from code
            echo "<h3>Code Logic Test:</h3>";
            $nwt_mp = !empty($row['W H']) ? floatval($row['W H']) : 8;
            echo "<pre>";
            echo "Condition: !empty(\$row['W H']) ? floatval(\$row['W H']) : 8\n";
            echo "Result: \$nwt_mp = $nwt_mp\n";
            echo "</pre>";

            break; // Stop after finding the row
        }
    }

    fclose($handle);
}
?>
