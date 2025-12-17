<!-- Page Title -->
<title>DPR | Prod. Detail by Part</title>

<!-- Include Sidebar -->
<?php $this->load->view('layout/sidebar'); ?>

<!-- CSS Dependencies -->
<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

<!-- Main Content Wrapper -->
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Productivity Detail by Part by Machine by Month</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>

                <!-- Content Body -->
                <div class="ibox-content">
                    <!-- Form for filtering data -->
                    <?= form_open('c_report/productivity_by_part_by_machine_by_month'); ?>
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <!-- Year-Month selector -->
                                <div class="col-sm-3">
                                    <b>Pilih Tahun - Bulan</b>
                                    <select name="tahun" value="<?php echo $tahun ?>" class="form-control">
                                        <?php
                                        // Generate year options from last year to current year
                                        $currentYear = date('Y');
                                        $previousYear = date('Y') - 1;

                                        for ($year = $previousYear; $year <= $currentYear; $year++) {
                                            // Generate all months
                                            $months = array(
                                                "01",
                                                "02",
                                                "03",
                                                "04",
                                                "05",
                                                "06",
                                                "07",
                                                "08",
                                                "09",
                                                "10",
                                                "11",
                                                "12"
                                            );
                                            foreach ($months as $month) { ?>
                                                <option value="<?= $year . '-' . $month ?>"
                                                    <?php if ($month == $bulan && $year == $tahun) {
                                                        echo 'selected="selected"';
                                                    } ?>>
                                                    <?= $year . '-' . $month ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <!-- Submit button -->
                                <div class="col">
                                    <input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show">
                                </div>
                            </div>
                        </div>
                        <!-- Note/instruction section -->
                        <div class="row ml-4">
                            <p>
                                <strong>*Catatan :</strong> Data yang muncul hanya data pada
                                <strong>bulan ini saja</strong>, jika ingin melihat data yang lain
                                silahkan gunakan fitur <strong>filter</strong>.
                            </p>
                        </div>
                    </div>
                    <?= form_close(); ?>

                    <!-- Data Table Section -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example5" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Mesin</th>
                                    <th>Target Qty</th>
                                    <th>Qty OK</th>
                                    <th>Gross Prod</th>
                                    <th>Nett Prod</th>
                                    <th>CTStd</th>
                                    <th>CTQuo</th>
                                    <th>Min CTSet</th>
                                    <th>Max CTSet</th>
                                    <th>Shift Hour</th>
                                    <th>WorkHour</th>
                                    <th>Overtime</th>
                                    <th>Overtime Pctg.</th>
                                    <th>Mach Use</th>
                                    <th>CalcDT</th>
                                    <th>Delta DT</th>
                                    <th>Stop Time</th>
                                    <th>Press Insp</th>
                                    <th>Press Defect</th>
                                    <th>% Defect</th>

                                    <!-- Defect Columns -->
                                    <th>BENDING</th>
                                    <th>BERAWAN</th>
                                    <th>BLACKDOT</th>
                                    <th>BROKEN</th>
                                    <th>CRACK</th>
                                    <th>DENT</th>
                                    <th>DIRTY</th>
                                    <th>DISCOLOUR</th>
                                    <th>EJECTOR MARK</th>
                                    <th>FLASH</th>
                                    <th>FLOW GATE</th>
                                    <th>FLOW MARK</th>
                                    <th>FOREIGN MATERIAL</th>
                                    <th>GAS BURN</th>
                                    <th>GAS MARK</th>
                                    <th>GATE BOLONG</th>
                                    <th>GATE LONG</th>
                                    <th>HANGUS</th>
                                    <th>HIKE</th>
                                    <th>OIL</th>
                                    <th>OVERSIZE</th>
                                    <th>PIN PLONG</th>
                                    <th>PIN SERET</th>
                                    <th>SCRATCH</th>
                                    <th>SETTINGAN</th>
                                    <th>SHORT SHOOT</th>
                                    <th>SILVER</th>
                                    <th>SINK MARK</th>
                                    <th>UNDERCUT</th>
                                    <th>UNDERSIZE</th>
                                    <th>VOID</th>
                                    <th>WAVING</th>
                                    <th>WELD LINE</th>
                                    <th>WHITE DOT</th>
                                    <th>WHITE MARK</th>
                                    <th>ADJUST PARAMETER</th>
                                    <th>PRE HEATING MATERIAL</th>
                                    <th>CLEANING HOPPER & BARREL</th>
                                    <th>SET UP MOLD</th>
                                    <th>SET UP PARAMETER MACHINE</th>
                                    <th>IPQC INSPECTION</th>
                                    <th>NO PACKING</th>
                                    <th>NO MATERIAL</th>
                                    <th>MATERIAL PROBLEM</th>
                                    <th>NO OPERATOR</th>
                                    <th>DAILY CHECK LIST</th>
                                    <th>OVERHOULE MOLD</th>
                                    <th>MOLD PROBLEM</th>
                                    <th>TRIAL</th>
                                    <th>MACHINE</th>
                                    <th>HOPPER DRYER</th>
                                    <th>ROBOT</th>
                                    <th>MTC</th>
                                    <th>COOLING TOWER</th>
                                    <th>COMPRESSOR</th>
                                    <th>LISTRIK</th>
                                    <th>QC LOLOS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                foreach ($detail_productivity_by_month->result_array() as $data) {
                                    $no++;
                                    echo '<tr>';
                                    echo '<td>' . $no . '</td>';
                                    echo '<td>' . $data['bulan'] . '</td>';
                                    echo '<td>' . $data['kode_product'] . '</td>';
                                    echo '<td>' . $data['nama_product'] . '</td>';
                                    echo '<td>' . $data['mesin'] . '</td>';
                                    echo '<td>' . number_format($data['target_mc'], 0, ',', '.') . '</td>';
                                    echo '<td>' . number_format($data['qty_ok'], 0, ',', '.') . '</td>';
                                    echo '<td>' . $data['gross_prod'] . '</td>';
                                    echo '<td>' . $data['nett_prod'] . '</td>';
                                    echo '<td>' . $data['CTStd'] . '</td>';
                                    echo '<td>' . $data['CTStd2'] . '</td>';
                                    echo '<td>' . $data['MinSPMSet'] . '</td>';
                                    echo '<td>' . $data['MaxSPMSet'] . '</td>';
                                    echo '<td>' . $data['nwt_mp'] . '</td>'; // Shift Hour
                                    echo '<td>' . $data['production_time'] . '</td>'; // WorkHour
                                    echo '<td>' . $data['ot_mp'] . '</td>'; // Overtime
                                    echo '<td>' . $data['percentage_ot'] . '%</td>'; // Overtime Pctg.
                                    echo '<td>' . number_format($data['mach_use_percentage'], 2, '.', '') . '%</td>';
                                    // Assuming CalcDT and Delta DT are computed or not part of the query; adjust as needed
                                    echo '<td>' . $data['CalcDT'] . '</td>'; // Mach Use
                                    echo '<td>' . $data['DeltaDT'] . '</td>'; // StopTime (assuming qty_ng represents stop time)
                                    echo '<td>' . number_format($data['qty_lt'], 2, '.', '') . '</td>';
                                    echo '<td>' . number_format($data['press_insp'], 0, ',', '.') . '</td>';
                                    echo '<td>' . $data['press_def'] . '</td>'; // 
                                    echo '<td>' . $data['percent_def'] . '%</td>'; // 
                                    // Defect Columns Data
                                    echo '<td>' . $data['bending'] . '</td>';
                                    echo '<td>' . $data['berawan'] . '</td>';
                                    echo '<td>' . $data['blackdot'] . '</td>';
                                    echo '<td>' . $data['broken'] . '</td>';
                                    echo '<td>' . $data['crack'] . '</td>';
                                    echo '<td>' . $data['dent'] . '</td>';
                                    echo '<td>' . $data['dirty'] . '</td>';
                                    echo '<td>' . $data['discolour'] . '</td>';
                                    echo '<td>' . $data['ejector_mark'] . '</td>';
                                    echo '<td>' . $data['flash'] . '</td>';
                                    echo '<td>' . $data['flow_gate'] . '</td>';
                                    echo '<td>' . $data['flow_mark'] . '</td>';
                                    echo '<td>' . $data['fm'] . '</td>';
                                    echo '<td>' . $data['gas_burn'] . '</td>';
                                    echo '<td>' . $data['gas_mark'] . '</td>';
                                    echo '<td>' . $data['gate_bolong'] . '</td>';
                                    echo '<td>' . $data['gate_long'] . '</td>';
                                    echo '<td>' . $data['hangus'] . '</td>';
                                    echo '<td>' . $data['hike'] . '</td>';
                                    echo '<td>' . $data['oil'] . '</td>';
                                    echo '<td>' . $data['oversize'] . '</td>';
                                    echo '<td>' . $data['pin_plong'] . '</td>';
                                    echo '<td>' . $data['pin_seret'] . '</td>';
                                    echo '<td>' . $data['scratch'] . '</td>';
                                    echo '<td>' . $data['settingan'] . '</td>';
                                    echo '<td>' . $data['short_shoot'] . '</td>';
                                    echo '<td>' . $data['silver'] . '</td>';
                                    echo '<td>' . $data['sink_mark'] . '</td>';
                                    echo '<td>' . $data['undercut'] . '</td>';
                                    echo '<td>' . $data['under_size'] . '</td>';
                                    echo '<td>' . $data['void'] . '</td>';
                                    echo '<td>' . $data['waving'] . '</td>';
                                    echo '<td>' . $data['weld_line'] . '</td>';
                                    echo '<td>' . $data['white_dot'] . '</td>';
                                    echo '<td>' . $data['white_mark'] . '</td>';
                                    echo '<td>' . $data['adjust_parameter'] . '</td>';
                                    echo '<td>' . $data['pre_heating_material'] . '</td>';
                                    echo '<td>' . $data['cleaning'] . '</td>';
                                    echo '<td>' . $data['set_up_mold'] . '</td>';
                                    echo '<td>' . $data['set_up_par_machine'] . '</td>';
                                    echo '<td>' . $data['ipqc_inspection'] . '</td>';
                                    echo '<td>' . $data['no_packing'] . '</td>';
                                    echo '<td>' . $data['no_material'] . '</td>';
                                    echo '<td>' . $data['material_problem'] . '</td>';
                                    echo '<td>' . $data['no_operator'] . '</td>';
                                    echo '<td>' . $data['daily_check_list'] . '</td>';
                                    echo '<td>' . $data['overhoule_mold'] . '</td>';
                                    echo '<td>' . $data['mold_problem'] . '</td>';
                                    echo '<td>' . $data['trial'] . '</td>';
                                    echo '<td>' . $data['machine'] . '</td>';
                                    echo '<td>' . $data['hopper_dryer'] . '</td>';
                                    echo '<td>' . $data['robot'] . '</td>';
                                    echo '<td>' . $data['mtc'] . '</td>';
                                    echo '<td>' . $data['cooling_tower'] . '</td>';
                                    echo '<td>' . $data['compressor'] . '</td>';
                                    echo '<td>' . $data['listrik'] . '</td>';
                                    echo '<td>' . $data['qc_lolos'] . '</td>';
                                    echo '</tr>';
                                } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <!-- Adjust the footer as needed or remove if not required -->
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Mesin</th>
                                    <th>Target Qty</th>
                                    <th>Qty OK</th>
                                    <th>Gross Prod</th>
                                    <th>Nett Prod</th>
                                    <th>CTStd</th>
                                    <th>CTQuo</th>
                                    <th>Min CTSet</th>
                                    <th>Max CTSet</th>
                                    <th>Shift Hour</th>
                                    <th>WorkHour</th>
                                    <th>Overtime</th>
                                    <th>Overtime Pctg.</th>
                                    <th>Mach Use</th>
                                    <th>CalcDT</th>
                                    <th>Delta DT</th>
                                    <th>Stop Time</th>
                                    <th>Press Insp</th>
                                    <th>Press Defect</th>
                                    <th>% Defect</th>
                                    <!-- Defect Columns -->
                                    <th>BENDING</th>
                                    <th>BERAWAN</th>
                                    <th>BLACKDOT</th>
                                    <th>BROKEN</th>
                                    <th>CRACK</th>
                                    <th>DENT</th>
                                    <th>DIRTY</th>
                                    <th>DISCOLOUR</th>
                                    <th>EJECTOR MARK</th>
                                    <th>FLASH</th>
                                    <th>FLOW GATE</th>
                                    <th>FLOW MARK</th>
                                    <th>FOREIGN MATERIAL</th>
                                    <th>GAS BURN</th>
                                    <th>GAS MARK</th>
                                    <th>GATE BOLONG</th>
                                    <th>GATE LONG</th>
                                    <th>HANGUS</th>
                                    <th>HIKE</th>
                                    <th>OIL</th>
                                    <th>OVERSIZE</th>
                                    <th>PIN PLONG</th>
                                    <th>PIN SERET</th>
                                    <th>SCRATCH</th>
                                    <th>SETTINGAN</th>
                                    <th>SHORT SHOOT</th>
                                    <th>SILVER</th>
                                    <th>SINK MARK</th>
                                    <th>UNDERCUT</th>
                                    <th>UNDERSIZE</th>
                                    <th>VOID</th>
                                    <th>WAVING</th>
                                    <th>WELD LINE</th>
                                    <th>WHITE DOT</th>
                                    <th>WHITE MARK</th>
                                    <th>ADJUST PARAMETER</th>
                                    <th>PRE HEATING MATERIAL</th>
                                    <th>CLEANING HOPPER & BARREL</th>
                                    <th>SET UP MOLD</th>
                                    <th>SET UP PARAMETER MACHINE</th>
                                    <th>IPQC INSPECTION</th>
                                    <th>NO PACKING</th>
                                    <th>NO MATERIAL</th>
                                    <th>MATERIAL PROBLEM</th>
                                    <th>NO OPERATOR</th>
                                    <th>DAILY CHECK LIST</th>
                                    <th>OVERHOULE MOLD</th>
                                    <th>MOLD PROBLEM</th>
                                    <th>TRIAL</th>
                                    <th>MACHINE</th>
                                    <th>HOPPER DRYER</th>
                                    <th>ROBOT</th>
                                    <th>MTC</th>
                                    <th>COOLING TOWER</th>
                                    <th>COMPRESSOR</th>
                                    <th>LISTRIK</th>
                                    <th>QC LOLOS</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Footer -->
<?php $this->load->view('layout/footer'); ?>

<!-- JavaScript Dependencies -->
<script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>

<!-- Highcharts Dependencies -->
<script src="<?= base_url(); ?>template/js/grafik/highcharts.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/data.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/exporting.js"></script>
<script>
// Ensure jsPDF is available globally for offline-exporting
window.jspdf = window.jspdf || {};
</script>
<script src="<?= base_url(); ?>template/js/grafik/jspdf.umd.min.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/offline-exporting.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/accessibility.js"></script>
<script>
// Ensure jsPDF is globally available
window.jsPDF = window.jspdf?.jsPDF || window.jsPDF;

// Custom PDF export handler with HIGH RESOLUTION
(function() {
    // Wait for both Highcharts and jsPDF to be ready
    var checkReady = setInterval(function() {
        if (typeof Highcharts !== 'undefined' && (window.jsPDF || (window.jspdf && window.jspdf.jsPDF))) {
            clearInterval(checkReady);
            
            // Get jsPDF reference
            var jsPDFConstructor = window.jsPDF || window.jspdf.jsPDF;
            
            // Create custom PDF export function with HIGH RESOLUTION
            window.customPDFExport = function(chart) {
                try {
                    // Render at HIGH resolution for quality (3x larger)
                    var renderWidth = 1200;  // High res width
                    var renderHeight = 750;  // High res height
                    
                    // Get the SVG of the chart at high resolution
                    var svg = chart.getSVG({
                        chart: {
                            width: renderWidth,
                            height: renderHeight
                        }
                    });
                    
                    // Create a HIGH RESOLUTION canvas
                    var canvas = document.createElement('canvas');
                    canvas.width = renderWidth;
                    canvas.height = renderHeight;
                    var ctx = canvas.getContext('2d');
                    
                    // Create an image from the SVG
                    var img = new Image();
                    img.onload = function() {
                        try {
                            // Draw at high resolution
                            ctx.fillStyle = 'white';
                            ctx.fillRect(0, 0, renderWidth, renderHeight);
                            ctx.drawImage(img, 0, 0, renderWidth, renderHeight);
                            
                            // Create PDF with A4 landscape
                            var pdf = new jsPDFConstructor({
                                orientation: 'landscape',
                                unit: 'mm',
                                format: 'a4'
                            });
                            
                            // A4 landscape: 297mm x 210mm
                            // Display at 85% of page width for better paper utilization
                            var displayWidth = 250;  // mm - 85% of page width
                            var displayHeight = 156;  // mm - maintaining aspect ratio
                            
                            // Center on page
                            var x = (297 - displayWidth) / 2;
                            var y = (210 - displayHeight) / 2;
                            
                            // Add HIGH RES image to PDF at larger display size
                            // This gives us sharp quality
                            var imgData = canvas.toDataURL('image/png', 1.0); // Max quality
                            pdf.addImage(imgData, 'PNG', x, y, displayWidth, displayHeight);
                            
                            // Save PDF
                            var filename = (chart.options.title && chart.options.title.text) ? 
                                         chart.options.title.text.replace(/[^a-z0-9]/gi, '_') + '.pdf' : 
                                         'chart.pdf';
                            pdf.save(filename);
                        } catch (e) {
                            console.error('Error creating PDF:', e);
                            alert('Error creating PDF. Please try again.');
                        }
                    };
                    
                    img.onerror = function() {
                        console.error('Error loading chart image');
                        alert('Error loading chart. Please try again.');
                    };
                    
                    // Set image source
                    var svgData = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svg)));
                    img.src = svgData;
                } catch (e) {
                    console.error('Error in customPDFExport:', e);
                    alert('Error exporting PDF. Please try again.');
                }
            };
            
            // Override the exportChart method for PDF - MORE AGGRESSIVE
            var originalExportChart = Highcharts.Chart.prototype.exportChart;
            Highcharts.Chart.prototype.exportChart = function(options) {
                if (options && options.type === 'application/pdf') {
                    // Use our custom PDF export and DO NOT call original
                    window.customPDFExport(this);
                    // Return false to prevent any further processing
                    return false;
                } else {
                    // Use original for other formats
                    return originalExportChart.call(this, options);
                }
            };
            
            // Also override exportChartLocal if it exists (from offline-exporting)
            if (Highcharts.Chart.prototype.exportChartLocal) {
                var originalExportChartLocal = Highcharts.Chart.prototype.exportChartLocal;
                Highcharts.Chart.prototype.exportChartLocal = function(options) {
                    if (options && options.type === 'application/pdf') {
                        // Use our custom PDF export
                        window.customPDFExport(this);
                        return false;
                    } else {
                        return originalExportChartLocal.call(this, options);
                    }
                };
            }
            
            // Override the menu item click handler directly
            if (Highcharts.getOptions().exporting && Highcharts.getOptions().exporting.menuItemDefinitions) {
                Highcharts.getOptions().exporting.menuItemDefinitions.downloadPDF = {
                    textKey: 'downloadPDF',
                    onclick: function() {
                        window.customPDFExport(this);
                        return false;
                    }
                };
            }
            
            console.log('Custom PDF export handler installed (High Resolution)');
        }
    }, 100);
})();
</script>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function() {
        // Initialize DataTable with custom options
        $('.dataTables-example5 tfoot th').each(function() {
            $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
        });

        $('.dataTables-example5').DataTable({
            pageLength: 10,
            responsive: false,
            scrollX: true,
            scrollCollapse: true,
            paging: true,
            fixedColumns: {
                left: 5, // Adjusted to freeze the first 5 columns
                right: 1,
            },
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [{
                    extend: 'copy'
                },
                {
                    extend: 'csv'
                },
                {
                    extend: 'excel',
                    title: 'Data_2025_12_09'
                },
                {
                    extend: 'pdf',
                    title: 'Data_2025_12_09'
                },
                {
                    extend: 'print',
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var that = this;
                    $('input', this.footer()).on('keyup change clear', function() {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
            }
        });
    });
</script>

<!-- Highcharts Figure -->
<figure class="highcharts-figure">
    <!-- Highcharts container -->
</figure>