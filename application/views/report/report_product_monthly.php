<title>DPR | Report Product Monthly by Customer</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<style>
#tblSummary {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    font-size: 12px;
}
#tblSummary th, #tblSummary td {
    padding: 6px 10px;
    border: 1px solid #cfd8dc;
    overflow: hidden;
    text-overflow: ellipsis;
}
#tblSummary thead th {
    background: #1ab394;
    color: #fff;
    font-weight: 600;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 1;
}
#tblSummary tbody tr:hover { background-color: #f0f8f5; }
#tblSummary .col-no     { width: 40px; text-align: center; }
#tblSummary .col-code   { width: 15%; }
#tblSummary .col-name   { width: 25%; }
#tblSummary .col-cust   { width: 18%; }
#tblSummary .col-ok     { width: 10%; text-align: right; }
#tblSummary .col-ng     { width: 10%; text-align: right; }
#tblSummary .col-pct    { width: 7%;  text-align: center; }
#tblSummary .col-toggle { width: 30px; text-align: center; }
#tblSummary tfoot td {
    background: #e8f5e9;
    font-weight: 700;
    text-align: center;
}
#tblSummary tfoot .col-ok,
#tblSummary tfoot .col-ng { text-align: right; }

.ng-detail-row td { padding: 0 !important; border: none !important; }
.ng-detail-table {
    width: 70%;
    margin: 6px auto 6px auto;
    border-collapse: collapse;
    font-size: 11px;
}
.ng-detail-table th, .ng-detail-table td {
    padding: 4px 10px;
    border: 1px solid #cfd8dc;
}
.ng-detail-table thead th {
    background: #d5f5e3;
    font-weight: 600;
    text-align: center;
}
.ng-detail-table .ng-col-no   { width: 40px; text-align: center; }
.ng-detail-table .ng-col-type { width: 40%; }
.ng-detail-table .ng-col-qty  { width: 20%; text-align: right; }
.ng-detail-table .ng-col-pct  { width: 20%; text-align: center; }

.toggle-icon { cursor: pointer; font-size: 15px; }
.toggle-icon:hover { opacity: 0.7; }
.export-btn { margin-left: 8px; }
.ng-bar { height: 6px; border-radius: 3px; display: inline-block; vertical-align: middle; margin-left: 4px; background: #e74c3c; }

/* Print-only header */
.print-header { display: none; }

@media print {
    @page { margin: 10mm; size: landscape; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }

    body { font-size: 11px; background: #fff !important; }
    #side-menu, .navbar, .navbar-static-top, .navbar-default,
    #page-wrapper > .row.border-bottom, .ibox-title, .ibox-tools,
    footer, script, .no-print, .toggle-icon { display: none !important; }

    #wrapper { margin-left: 0 !important; }
    .sidebar-collapse { display: none !important; }
    .navbar-default { display: none !important; }
    #page-wrapper { margin-left: 0 !important; min-height: auto !important; }
    .wrapper { padding: 0 !important; }
    .ibox { border: none !important; box-shadow: none !important; margin-bottom: 0 !important; }
    .ibox-content { padding: 0 !important; }

    .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #333;
    }
    .print-header h2 { margin: 0 0 4px 0; font-size: 16px; }
    .print-header h4 { margin: 0 0 4px 0; font-size: 13px; font-weight: normal; }
    .print-header p { margin: 2px 0; font-size: 11px; color: #555; }

    #tblSummary { table-layout: auto; font-size: 10px; }
    #tblSummary th, #tblSummary td { white-space: nowrap; border: 1px solid #999; padding: 4px 6px; }
    #tblSummary thead th { background: #1ab394 !important; color: #fff !important; }
    #tblSummary .col-toggle { display: none !important; }
    #tblSummary tfoot td { background: #e8f5e9 !important; }
    #tblSummary tbody tr { page-break-inside: avoid; }

    .ng-detail-row { display: table-row !important; }
    .ng-detail-row td { padding: 0 !important; border: none !important; }
    .ng-detail-table { width: 90%; margin: 4px 20px; font-size: 10px; }
    .ng-detail-table th, .ng-detail-table td { border: 1px solid #999; padding: 3px 6px; }
    .ng-detail-table thead th { background: #d5f5e3 !important; }
    .ng-detail-row tr { page-break-inside: avoid; }
    .ng-bar { display: none !important; }

    .col-no { width: 30px !important; }
}
</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Report Product Monthly by Customer</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Print Header (only visible when printing) -->
                    <div class="print-header">
                        <h2>PT. Padma Soode Indonesia</h2>
                        <h4>Report Product Monthly by Customer</h4>
                        <p>
                            <strong>Period:</strong>
                            <?php
                            $month_names = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June',
                                              '07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
                            echo (isset($month_names[$bulan]) ? $month_names[$bulan] : $bulan) . ' ' . $tahun;
                            ?>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <strong>Customer:</strong>
                            <?= $selected_customer ? htmlspecialchars($selected_customer) : 'All Customers'; ?>
                        </p>
                        <p><em>Printed: <?= date('d-M-Y H:i'); ?></em></p>
                    </div>

                    <!-- Filter Section -->
                    <div class="no-print">
                    <?= form_open('c_report/report_product_monthly'); ?>
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-4">
                            <label><b>Pilih Bulan</b></label>
                            <select name="tahun" class="form-control">
                                <?php
                                $tahuns = date('Y') - 2;
                                $now = date('Y');
                                for ($i = $tahuns; $i <= $now; $i++) {
                                    $monts = array("01","02","03","04","05","06","07","08","09","10","11","12");
                                    foreach ($monts as $value) {
                                        $sel = ($value == $bulan && $i == $tahun) ? 'selected="selected"' : '';
                                        echo '<option value="'.$i.'-'.$value.'" '.$sel.'>'.$i.'-'.$value.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label><b>Pilih Customer</b></label>
                            <select name="customer" class="form-control">
                                <option value="all" <?= ($selected_customer == 'all' || $selected_customer == '') ? 'selected' : '' ?>>Semua Customer</option>
                                <?php foreach ($customer_list->result_array() as $cust): ?>
                                    <?php $sel = ($selected_customer == $cust['customer']) ? 'selected' : ''; ?>
                                    <option value="<?= htmlspecialchars($cust['customer']) ?>" <?= $sel ?>><?= htmlspecialchars($cust['customer']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label><br>
                            <button type="submit" name="show" class="btn btn-primary" value="Show">Show</button>
                        </div>
                    </div>
                    <?= form_close(); ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row no-print" style="margin-bottom: 15px;">
                        <div class="col-md-12">
                            <?php $export_url = base_url('c_report/report_product_monthly_export').'?tahun='.$tahun.'&bulan='.$bulan.'&customer='.urlencode($selected_customer); ?>
                            <button class="btn btn-success" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                            <a href="<?= $export_url ?>" class="btn btn-info export-btn"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                        </div>
                    </div>

                    <!-- Summary Table -->
                    <?php if ($summary->num_rows() > 0): ?>
                    <table class="table" id="tblSummary">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th class="col-code">Product Code</th>
                                <th class="col-name">Product Name</th>
                                <th class="col-cust">Customer</th>
                                <th class="col-ok">Total OK</th>
                                <th class="col-ng">Total NG</th>
                                <th class="col-pct">NG %</th>
                                <th class="col-toggle no-print"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $grand_ok = 0;
                            $grand_ng = 0;
                            foreach ($summary->result_array() as $item):
                                $total_ok = (int) $item['total_ok'];
                                $total_ng = (int) $item['total_ng'];
                                $total_all = $total_ok + $total_ng;
                                $ng_pct = $total_all > 0 ? round(($total_ng / $total_all) * 100, 2) : 0;
                                $grand_ok += $total_ok;
                                $grand_ng += $total_ng;
                                $row_id = 'ng-' . $no;
                            ?>
                            <tr>
                                <td class="col-no"><?= $no; ?></td>
                                <td class="col-code" title="<?= htmlspecialchars($item['kode_product']); ?>"><?= htmlspecialchars($item['kode_product']); ?></td>
                                <td class="col-name" title="<?= htmlspecialchars($item['nama_product']); ?>"><?= htmlspecialchars($item['nama_product']); ?></td>
                                <td class="col-cust" title="<?= htmlspecialchars($item['customer']); ?>"><?= htmlspecialchars($item['customer']); ?></td>
                                <td class="col-ok"><?= number_format($total_ok); ?></td>
                                <td class="col-ng" style="<?= $total_ng > 0 ? 'color:#e74c3c;font-weight:bold;' : '' ?>">
                                    <?= number_format($total_ng); ?>
                                    <?php if ($total_ng > 0 && $total_all > 0): ?>
                                    <span class="ng-bar" style="width:<?= min($ng_pct * 2, 100); ?>px; background:#e74c3c;"></span>
                                    <?php endif; ?>
                                </td>
                                <td class="col-pct"><?= $ng_pct; ?>%</td>
                                <td class="col-toggle no-print">
                                    <span class="toggle-icon" onclick="toggleNgDetail('<?= $row_id; ?>', this)" title="Toggle NG Detail">
                                        <i class="fa fa-plus-square"></i>
                                    </span>
                                </td>
                            </tr>
                            <!-- Hidden NG Detail Row -->
                            <tr class="ng-detail-row" id="<?= $row_id; ?>" style="display: none;">
                                <td colspan="8">
                                    <?php
                                    $kp = $item['kode_product'];
                                    $ng_items = [];
                                    foreach ($ng_detail->result_array() as $ng_row) {
                                        if ($ng_row['kode_product'] === $kp) {
                                            $ng_items[] = $ng_row;
                                        }
                                    }
                                    ?>
                                    <?php if (count($ng_items) > 0): ?>
                                    <table class="ng-detail-table">
                                        <thead>
                                            <tr>
                                                <th class="ng-col-no">No</th>
                                                <th class="ng-col-type">NG Type</th>
                                                <th class="ng-col-qty">Qty</th>
                                                <th class="ng-col-pct">% of Total NG</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ng_no = 1;
                                            foreach ($ng_items as $ng):
                                                $ng_pct_type = $total_ng > 0 ? round(($ng['qty_ng'] / $total_ng) * 100, 2) : 0;
                                            ?>
                                            <tr>
                                                <td class="ng-col-no"><?= $ng_no++; ?></td>
                                                <td class="ng-col-type"><?= htmlspecialchars($ng['ng_type']); ?></td>
                                                <td class="ng-col-qty"><?= number_format($ng['qty_ng']); ?></td>
                                                <td class="ng-col-pct"><?= $ng_pct_type; ?>%</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                    <span style="color: #888; font-style: italic; padding-left: 20px;">Tidak ada data NG detail</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $no++; endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-center">GRAND TOTAL</td>
                                <td class="col-ok"><?= number_format($grand_ok); ?></td>
                                <td class="col-ng"><?= number_format($grand_ng); ?></td>
                                <td class="col-pct"><?php
                                    $grand_all = $grand_ok + $grand_ng;
                                    echo $grand_all > 0 ? round(($grand_ng / $grand_all) * 100, 2) . '%' : '0%';
                                ?></td>
                                <td class="no-print"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #888; font-style: italic;">
                        Tidak ada data untuk periode ini.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleNgDetail(rowId, el) {
    var row = document.getElementById(rowId);
    var icon = el.querySelector('i');
    if (row.style.display === 'none') {
        row.style.display = 'table-row';
        icon.className = 'fa fa-minus-square';
    } else {
        row.style.display = 'none';
        icon.className = 'fa fa-plus-square';
    }
}

function printReport() {
    var rows = document.querySelectorAll('.ng-detail-row');
    var hiddenRows = [];
    rows.forEach(function(row) {
        if (row.style.display === 'none') {
            hiddenRows.push(row.id);
            row.style.display = 'table-row';
        }
    });
    window.onafterprint = function() {
        hiddenRows.forEach(function(id) {
            var r = document.getElementById(id);
            if (r) r.style.display = 'none';
        });
        window.onafterprint = null;
    };
    window.print();
}
</script>
