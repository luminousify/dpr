<?php
// Restructure data for the table format
$months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
$structuredData = [];

foreach($data_7table_table->result_array() as $data) {
    $category = $data['losstime_category'];
    $subcategory = $data['nama'];
    $period = $data['period'];
    $month = substr($period, 5, 2);
    $hours = round($data['total_minutes'], 1);
    
    if (!isset($structuredData[$category])) {
        $structuredData[$category] = [];
    }
    
    if (!isset($structuredData[$category][$subcategory])) {
        $structuredData[$category][$subcategory] = array_fill_keys($months, '-');
    }
    
    $structuredData[$category][$subcategory][$month] = $hours;
}
?>

<div class="card rounded">
    <div id="container_7table" class="p-3" style="overflow-x: auto;">
        <table class="table table-striped table-bordered table-hover dataTables-example nowrap" style="width:100%" id="losstime-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <?php foreach($months as $month): ?>
                        <th><?= date('M', mktime(0, 0, 0, $month, 1)) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <?php foreach($months as $month): ?>
                        <th><?= date('M', mktime(0, 0, 0, $month, 1)) ?></th>
                    <?php endforeach; ?>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach($structuredData as $category => $subcategories): ?>
                    <?php foreach($subcategories as $subcategory => $monthData): ?>
                        <tr>
                            <td><?= $category ?></td>
                            <td><?= $subcategory ?></td>
                            <?php foreach($months as $month): ?>
                                <td><?= $monthData[$month] ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
(function ensureDataTableInit(){
    if (window.jQuery && jQuery.fn && jQuery.fn.DataTable) {
        jQuery('#losstime-table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            scrollX: true,
            responsive: true,
            fixedHeader: true
        });
    } else {
        setTimeout(ensureDataTableInit, 100);
    }
})();
</script>