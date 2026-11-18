<?php
$months = array("jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");
$month_names = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

// Ensure year is set
$display_year = isset($year) ? $year : date('Y');
?>

<div class="card rounded">
    <?php if(isset($productivity) && is_array($productivity) && count($productivity) > 0): ?>
        <div class="card-header">
            <h5>Annual Productivity Data for Year: <strong><?= $display_year ?></strong></h5>
        </div>
        <div class="table-responsive">
            <table id="annual-productivity-table" class="table table-striped table-bordered table-hover dataTables-example2" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="2">Product Code</th>
                        <th rowspan="2">Product Name</th>
                        <?php foreach ($month_names as $month): ?>
                        <th colspan="3"><?= $month ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php foreach ($months as $month): ?>
                        <th>CYT Quo</th>
                        <th>Avg Net</th>
                        <th>Avg Gross</th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productivity as $row): ?>
                    <tr>
                        <td><?= isset($row['kode_product']) ? htmlspecialchars($row['kode_product']) : '' ?></td>
                        <td><?= isset($row['nama_product']) ? htmlspecialchars($row['nama_product']) : '' ?></td>
                        <?php foreach ($months as $month): ?>
                        <td><?= isset($row[$month.'_cyt_quo']) && $row[$month.'_cyt_quo'] !== null ? number_format($row[$month.'_cyt_quo'], 0) : '0' ?></td>
                        <td><?= isset($row[$month.'_avg_nett_prod']) && $row[$month.'_avg_nett_prod'] !== null ? number_format($row[$month.'_avg_nett_prod'], 0) : '0' ?></td>
                        <td><?= isset($row[$month.'_avg_gross_prod']) && $row[$month.'_avg_gross_prod'] !== null ? number_format($row[$month.'_avg_gross_prod'], 0) : '0' ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <?php foreach ($months as $month): ?>
                        <th>CYT Quo</th>
                        <th>Avg Net</th>
                        <th>Avg Gross</th>
                        <?php endforeach; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <div class="card-body">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">No Data Found</h4>
                <p>No productivity data found for year <strong><?= $display_year ?></strong>.</p>
                <hr>
                <p class="mb-0">Please check:</p>
                <ul>
                    <li>Data exists in the database for the selected year</li>
                    <li>The date format in v_production_op table is correct</li>
                    <li>Products are properly linked in t_product table</li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Add debugging -->
<script>
console.log("Annual productivity table HTML loaded");
console.log("Year: <?= $display_year ?>");
console.log("Productivity data count: <?= isset($productivity) && is_array($productivity) ? count($productivity) : 0 ?>");
<?php if(isset($productivity) && is_array($productivity) && count($productivity) > 0): ?>
console.log("Sample data keys:", <?= json_encode(array_keys($productivity[0])) ?>);
<?php endif; ?>
</script>
