<?php
/**
 * Detail Calculation Page for a single production record
 * Shows step-by-step calculation for debugging mismatches
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

// Get production ID from URL
$id_production = isset($_GET['id']) ? $_GET['id'] : '';
$return_url = isset($_GET['return']) ? $_GET['return'] : 'calculate_gross_nett.php';

if (empty($id_production)) {
    die("Error: No production ID provided. <a href='$return_url'>Go back</a>");
}

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

// Get production record
$sql = "SELECT po.*
        FROM t_production_op po
        WHERE po.id_production = '$id_production'";

$result = $conn->query($sql);

if (!$result) {
    die("Database error: " . $conn->error . " <a href='$return_url'>Go back</a>");
}

if ($result->num_rows == 0) {
    die("Error: Production record not found with ID: $id_production <a href='$return_url'>Go back</a>");
}

$row = $result->fetch_assoc();

// Try to get product info separately
$product_name = '';
$product_code = '';
if (!empty($row['id_product'])) {
    $product_sql = "SELECT nama_product, kode_product FROM t_product WHERE id_product = '" . $row['id_product'] . "'";
    $product_result = $conn->query($product_sql);
    if ($product_result && $product_result->num_rows > 0) {
        $product_row = $product_result->fetch_assoc();
        $product_name = $product_row['nama_product'];
        $product_code = $product_row['kode_product'];
    }
}

// Extract values
$qty_ok = floatval($row['qty_ok']);
$defect = floatval($row['qty_ng']);
$LT = floatval($row['qty_lt']);
$cavity = floatval($row['cavity']) ?: 1;
$cavity2 = floatval($row['cavity2']) ?: $cavity;
$ct_aktual = floatval($row['ct_mc_aktual']);
$nwt = floatval($row['nwt_mp']);
$ot = floatval($row['ot_mp']);

$db_nett = floatval($row['nett_prod']);
$db_gross = floatval($row['gross_prod']);
$db_prod_time = floatval($row['production_time']);

// Calculate step by step
$kalkulasi = $qty_ok + $defect;
$hasil_time = ($cavity > 0 && $ct_aktual > 0) ? ($kalkulasi / $cavity) * ($ct_aktual / 3600) : 0;
$production_time = round($hasil_time, 1);
$WorkHour = ($production_time + $LT) - $ot;

// Gross calculation
$grossProduction = 0;
$calc_gross = 0;
if (($WorkHour + $ot) > 0 && $qty_ok > 0 && $cavity2 > 0) {
    $grossProduction = 3600 / (($qty_ok / $cavity2) / ($WorkHour + $ot));
    $calc_gross = customRound($grossProduction);
}

// Nett calculation
$NProd = 0;
$calc_nett = 0;
if (($WorkHour + $ot - $LT) > 0 && $qty_ok > 0 && $cavity2 > 0) {
    $NProd = 3600 / (($qty_ok / $cavity2) / ($WorkHour + $ot - $LT));
    $calc_nett = customRound($NProd);
}

// Determine issues
$issues = [];
if ($gross_diff >= 1) {
    $issues[] = "Gross mismatch: DB=$db_gross vs Calc=$calc_gross (diff: " . round($calc_gross - $db_gross, 0) . ")";
}
if ($nett_diff >= 1) {
    $issues[] = "Nett mismatch: DB=$db_nett vs Calc=$calc_nett (diff: " . round($calc_nett - $db_nett, 0) . ")";
}
if ($qty_ok == 0) {
    $issues[] = "Qty OK is zero - cannot calculate productivity";
}
if ($ct_aktual == 0) {
    $issues[] = "CT Aktual is zero - cannot calculate production time";
}
if ($cavity == 0) {
    $issues[] = "Cavity is zero - division by zero prevented";
}

$gross_diff = abs($calc_gross - $db_gross);
$nett_diff = abs($calc_nett - $db_nett);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Calculation Detail - <?php echo $id_production; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; font-size: 14px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #2196F3; padding-bottom: 10px; }
        h2 { color: #2196F3; margin-top: 30px; }
        h3 { color: #666; margin-top: 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #2196F3; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        table { border-collapse: collapse; width: 100%; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2196F3; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .match { color: green; font-weight: bold; }
        .mismatch { color: red; font-weight: bold; background-color: #ffebee; }
        .highlight { background-color: #fff3cd; }
        .info-box { background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #2196F3; }
        .warning-box { background-color: #fff3e0; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #ff9800; }
        .error-box { background-color: #ffebee; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #f44336; }
        .success-box { background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4CAF50; }
        .calc-step { background-color: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 4px; font-family: monospace; }
        .formula { color: #1565C0; font-weight: bold; }
        .result { color: #2E7D32; font-weight: bold; font-size: 16px; }
        .value { color: #D84315; font-weight: bold; }
        .comparison-table { margin-top: 20px; }
        .comparison-table th { background-color: #4CAF50; }
        .comparison-table td.highlight { background-color: #fff3cd; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <a href="<?php echo $return_url; ?>" class="back-link">&larr; Back to list</a>
        
        <h1>Calculation Detail</h1>
        
        <div class="info-box">
            <strong>ID Production:</strong> <?php echo $id_production; ?><br>
            <strong>Tanggal:</strong> <?php echo $row['tanggal']; ?><br>
            <strong>Product:</strong> <?php echo ($product_code ? $product_code . ' - ' . $product_name : 'N/A'); ?><br>
            <strong>Operator:</strong> <?php echo isset($row['operator']) ? $row['operator'] : 'N/A'; ?>
        </div>
        
        <?php if (count($issues) > 0): ?>
        <div class="error-box">
            <strong>Issues Found:</strong>
            <ul>
                <?php foreach ($issues as $issue): ?>
                    <li><?php echo $issue; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <h2>1. Input Values from Database</h2>
        <table>
            <tr><th>Parameter</th><th>Value</th><th>Notes</th></tr>
            <tr><td>Qty OK</td><td class="value"><?php echo $qty_ok; ?></td><td>Good quantity produced</td></tr>
            <tr><td>Defect (NG)</td><td class="value"><?php echo $defect; ?></td><td>Defect quantity</td></tr>
            <tr><td>Loss Time (LT)</td><td class="value"><?php echo $LT; ?> hours</td><td>Downtime hours</td></tr>
            <tr><td>Cavity (std)</td><td class="value"><?php echo $cavity; ?></td><td>Standard cavity count</td></tr>
            <tr><td>Cavity (actual)</td><td class="value"><?php echo $cavity2; ?></td><td>Actual cavity count (cavity2)</td></tr>
            <tr><td>CT Aktual</td><td class="value"><?php echo $ct_aktual; ?> sec</td><td>Cycle time actual</td></tr>
            <tr><td>NWT</td><td class="value"><?php echo $nwt; ?> hours</td><td>Net working time</td></tr>
            <tr><td>OT</td><td class="value"><?php echo $ot; ?> hours</td><td>Overtime</td></tr>
        </table>
        
        <h2>2. Stored Database Values</h2>
        <table>
            <tr><th>Field</th><th>DB Value</th></tr>
            <tr><td>Production Time</td><td><?php echo $db_prod_time; ?> hours</td></tr>
            <tr><td>Gross Production</td><td class="value"><?php echo $db_gross; ?></td></tr>
            <tr><td>Nett Production</td><td class="value"><?php echo $db_nett; ?></td></tr>
        </table>
        
        <h2>3. Step-by-Step Calculation</h2>
        
        <h3>Step 1: Calculate Kalkulasi (Total Output)</h3>
        <div class="calc-step">
            <span class="formula">Kalkulasi = Qty OK + Defect</span><br>
            Kalkulasi = <?php echo $qty_ok; ?> + <?php echo $defect; ?><br>
            <span class="result">Kalkulasi = <?php echo $kalkulasi; ?></span>
        </div>
        
        <h3>Step 2: Calculate Production Time</h3>
        <div class="calc-step">
            <span class="formula">Production Time = (Kalkulasi / Cavity) × (CT Aktual / 3600)</span><br>
            Production Time = (<?php echo $kalkulasi; ?> / <?php echo $cavity; ?>) × (<?php echo $ct_aktual; ?> / 3600)<br>
            Production Time = <?php echo round($kalkulasi / $cavity, 4); ?> × <?php echo round($ct_aktual / 3600, 6); ?><br>
            Production Time = <?php echo round($hasil_time, 6); ?> hours<br>
            <span class="result">Production Time ≈ <?php echo $production_time; ?> hours (rounded to 1 decimal)</span>
        </div>
        
        <h3>Step 3: Calculate WorkHour</h3>
        <div class="calc-step">
            <span class="formula">WorkHour = Production Time + LT - OT</span><br>
            WorkHour = <?php echo $production_time; ?> + <?php echo $LT; ?> - <?php echo $ot; ?><br>
            <span class="result">WorkHour = <?php echo round($WorkHour, 4); ?> hours</span>
        </div>
        
        <h3>Step 4: Calculate GROSS Production</h3>
        <div class="calc-step">
            <span class="formula">Gross = 3600 / ((Qty OK / Cavity2) / (WorkHour + OT))</span><br>
            <br>
            <strong>Breakdown:</strong><br>
            - Qty OK / Cavity2 = <?php echo $qty_ok; ?> / <?php echo $cavity2; ?> = <?php echo round($qty_ok / $cavity2, 4); ?><br>
            - WorkHour + OT = <?php echo round($WorkHour, 4); ?> + <?php echo $ot; ?> = <?php echo round($WorkHour + $ot, 4); ?><br>
            - (Qty OK / Cavity2) / (WorkHour + OT) = <?php echo round($qty_ok / $cavity2, 4); ?> / <?php echo round($WorkHour + $ot, 4); ?> = <?php echo round(($qty_ok / $cavity2) / ($WorkHour + $ot), 6); ?><br>
            <br>
            <?php if (($WorkHour + $ot) > 0 && $qty_ok > 0 && $cavity2 > 0): ?>
            Gross = 3600 / <?php echo round(($qty_ok / $cavity2) / ($WorkHour + $ot), 6); ?><br>
            Gross = <?php echo round($grossProduction, 4); ?><br>
            <br>
            <span class="result">Gross (after customRound) = <?php echo $calc_gross; ?></span>
            <?php else: ?>
            <span class="mismatch">Cannot calculate - division by zero or invalid values</span>
            <?php endif; ?>
        </div>
        
        <h3>Step 5: Calculate NETT Production</h3>
        <div class="calc-step">
            <span class="formula">Nett = 3600 / ((Qty OK / Cavity2) / (WorkHour + OT - LT))</span><br>
            <br>
            <strong>Breakdown:</strong><br>
            - Qty OK / Cavity2 = <?php echo $qty_ok; ?> / <?php echo $cavity2; ?> = <?php echo round($qty_ok / $cavity2, 4); ?><br>
            - WorkHour + OT - LT = <?php echo round($WorkHour, 4); ?> + <?php echo $ot; ?> - <?php echo $LT; ?> = <?php echo round($WorkHour + $ot - $LT, 4); ?><br>
            - (Qty OK / Cavity2) / (WorkHour + OT - LT) = <?php echo round($qty_ok / $cavity2, 4); ?> / <?php echo round($WorkHour + $ot - $LT, 4); ?> = <?php echo round(($qty_ok / $cavity2) / ($WorkHour + $ot - $LT), 6); ?><br>
            <br>
            <?php if (($WorkHour + $ot - $LT) > 0 && $qty_ok > 0 && $cavity2 > 0): ?>
            Nett = 3600 / <?php echo round(($qty_ok / $cavity2) / ($WorkHour + $ot - $LT), 6); ?><br>
            Nett = <?php echo round($NProd, 4); ?><br>
            <br>
            <span class="result">Nett (after customRound) = <?php echo $calc_nett; ?></span>
            <?php else: ?>
            <span class="mismatch">Cannot calculate - division by zero or invalid values</span>
            <?php endif; ?>
        </div>
        
        <h3>Custom Rounding Logic</h3>
        <div class="warning-box">
            <span class="formula">customRound(value):</span><br>
            - If decimal part <= 0.5 → floor (round down)<br>
            - If decimal part > 0.5 → ceil (round up)<br>
            <br>
            Example: 123.4 → 123, 123.6 → 124
        </div>
        
        <h2>4. Final Comparison</h2>
        <table class="comparison-table">
            <tr>
                <th>Metric</th>
                <th>Calculated</th>
                <th>Database</th>
                <th>Difference</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>Production Time</td>
                <td><?php echo $production_time; ?></td>
                <td><?php echo $db_prod_time; ?></td>
                <td><?php echo round($production_time - $db_prod_time, 2); ?></td>
                <td><?php echo (abs($production_time - $db_prod_time) < 0.1) ? '<span class="match">OK</span>' : '<span class="mismatch">DIFF</span>'; ?></td>
            </tr>
            <tr class="<?php echo ($gross_diff >= 1) ? 'highlight' : ''; ?>">
                <td><strong>GROSS</strong></td>
                <td class="value"><?php echo $calc_gross; ?></td>
                <td class="value"><?php echo $db_gross; ?></td>
                <td class="<?php echo ($gross_diff >= 1) ? 'mismatch' : 'match'; ?>"><?php echo round($calc_gross - $db_gross, 0); ?></td>
                <td><?php echo ($gross_diff < 1) ? '<span class="match">MATCH</span>' : '<span class="mismatch">MISMATCH</span>'; ?></td>
            </tr>
            <tr class="<?php echo ($nett_diff >= 1) ? 'highlight' : ''; ?>">
                <td><strong>NETT</strong></td>
                <td class="value"><?php echo $calc_nett; ?></td>
                <td class="value"><?php echo $db_nett; ?></td>
                <td class="<?php echo ($nett_diff >= 1) ? 'mismatch' : 'match'; ?>"><?php echo round($calc_nett - $db_nett, 0); ?></td>
                <td><?php echo ($nett_diff < 1) ? '<span class="match">MATCH</span>' : '<span class="mismatch">MISMATCH</span>'; ?></td>
            </tr>
        </table>
        
        <?php if ($gross_diff >= 1 || $nett_diff >= 1): ?>
        <div class="error-box">
            <h3>Possible Causes of Mismatch:</h3>
            <ol>
                <li><strong>Different formula used:</strong> The stored values might have been calculated using a different formula than the one in edit_dpr.php</li>
                <li><strong>Manual override:</strong> Values might have been manually entered/edited</li>
                <li><strong>Rounding differences:</strong> Different rounding method was applied</li>
                <li><strong>Data changed after calculation:</strong> Input values (cavity, CT, LT) might have been updated but gross/nett not recalculated</li>
                <li><strong>Zero values handling:</strong> Different handling of zero/null values</li>
                <li><strong>Cavity vs Cavity2 confusion:</strong> Formula might use different cavity value</li>
            </ol>
            
            <h3>Recommendations:</h3>
            <ul>
                <li>Verify which formula version was used when the record was created</li>
                <li>Check if there's a recalculation script that should be run</li>
                <li>Consider adding a "last calculated" timestamp to track changes</li>
            </ul>
        </div>
        <?php else: ?>
        <div class="success-box">
            <h3>All calculations match!</h3>
            <p>The calculated values match the database values. No issues found.</p>
        </div>
        <?php endif; ?>
        
        <h2>5. Formula Reference (from edit_dpr.php)</h2>
        <div class="info-box">
            <pre>
Production Time = (Qty OK + Defect) / Cavity × (CT Aktual / 3600) hours

WorkHour = Production Time + Loss Time - Overtime

<b>GROSS Formula:</b>
grossProduction = 3600 / ((QtyOK / Cavity2) / (WorkHour + Overtime))
Simplified: Gross = 3600 × Cavity2 × (WorkHour + OT) / QtyOK

<b>NETT Formula:</b>
NProd = 3600 / ((QtyOK / Cavity2) / (WorkHour + Overtime - LossTime))
Simplified: Nett = 3600 × Cavity2 × (WorkHour + OT - LossTime) / QtyOK

<b>Custom Rounding:</b>
If decimal <= 0.5 → floor (round down)
If decimal > 0.5 → ceil (round up)
            </pre>
        </div>
        
        <p style="margin-top: 30px; color: #666;">
            <a href="<?php echo $return_url; ?>">&larr; Back to list</a>
        </p>
    </div>
</body>
</html>
<?php $conn->close(); ?>
