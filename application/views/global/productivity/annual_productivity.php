<?php
$months = array("jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");
$month_names = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
?>

<div class="card rounded">
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
                <?php if(isset($productivity) && is_array($productivity)): ?>
                    <?php foreach ($productivity as $row): ?>
                    <tr>
                        <td><?= isset($row['kode_product']) ? $row['kode_product'] : '' ?></td>
                        <td><?= isset($row['nama_product']) ? $row['nama_product'] : '' ?></td>
                        <?php foreach ($months as $month): ?>
                        <td><?= isset($row[$month.'_cyt_quo']) ? number_format($row[$month.'_cyt_quo'], 0) : '0' ?></td>
                        <td><?= isset($row[$month.'_avg_nett_prod']) ? number_format($row[$month.'_avg_nett_prod'], 0) : '0' ?></td>
                        <td><?= isset($row[$month.'_avg_gross_prod']) ? number_format($row[$month.'_avg_gross_prod'], 0) : '0' ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
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
</div>

<!-- Add debugging -->
<script>
console.log("Annual productivity table HTML loaded");
</script>
