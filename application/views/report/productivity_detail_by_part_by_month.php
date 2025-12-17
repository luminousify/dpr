 <title>DPR | Report</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: auto;
        /*width: 1000px;*/
        height: auto;
        margin: 0 auto;
    }
    tr {background-color: white} 
} 
</style>

<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Productivity</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                  <?= form_open('c_dpr/productivity_detail_by_part_by_month'); ?>  
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-3"> <b>Pilih Tahun - Bulan</b> 
                                    <select name="tahun" value="<?php echo $tahun ?>" class="form-control">
                                        <?php
                                        $tahuns = date('Y')-1;
                                        $now=date('Y');
                                        for($i=$tahuns; $i<=$now; $i++){
                                        $monts = array("01","02","03","04","05","06","07","08","09","10","11","12");
                                           foreach ($monts as $value) { ?>
                                                <option value="<?php echo $i.'-'.$value?>" <?php if($value == $bulan && $i == $tahun) { echo 'selected="selected"';}?>><?php echo $i.'-'.$value; ?></option>;<?php
                                           }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show"></div>
                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>bulan ini saja</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                        </div>
                    </div>
                    <?= form_close(); ?>
                    
                    <!-- Custom Excel Export -->
                    <div class="card rounded mb-4 border-info">
                        <div class="card-header bg-info text-white">
                            <h5><i class="fa fa-download"></i> Export Productivity Data</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-sm-3">
                                    <label for="export_year" class="font-weight-bold">Pilih Tahun:</label>
                                    <select id="export_year" name="year" class="form-control">
                                        <?php 
                                        $currentYear = date('Y');
                                        for($y = $currentYear - 3; $y <= $currentYear + 1; $y++): ?>
                                        <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>&nbsp;</label>
                                    <div class="btn-group d-flex" role="group">
                                        <button type="button" class="btn btn-success flex-fill" id="export_custom_excel">
                                            <i class="fa fa-file-excel-o"></i> Export Excel
                                        </button>
                                        <button type="button" class="btn btn-primary flex-fill" onclick="exportCustomCSV()">
                                            <i class="fa fa-file-csv-o"></i> Export CSV
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="alert alert-success py-2 mb-0">
                                        <i class="fa fa-info-circle"></i>
                                        <strong>Excel Format:</strong> Native Excel file with proper formatting. Fully compatible with Microsoft Excel.
                                    </div>
                                </div>
                            </div>
                                <div class="col-sm-6">
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle"></i> 
                                        Data format: YY | Product ID | Product Name | Cycle Time Standard | Cycle Time Quote | Tool | 01 | 02 | ... | 12 
                                        (kolom 01-12 menampilkan rata-rata CT aktual per bulan)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
             <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                   <thead>
                    <tr>
                    <th>No</th>
                        <th>Mo</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Mac</th>
                        <th>Target-Qty</th>
                        <th>Qty OK</th>
                        <th>Qty NG</th>
                        <th>Max SPM Std</th>
                        <th>Max SPM Std2</th>
                        <th>Min SPM Set</th>
                        <th>Max SPM Set</th>
                        <th>Shift Hour</th>
                        <th>Work Hour</th>
                        <th>Overtime</th>
                        <th>View Detail By Part</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $view_detail = base_url('c_dpr/view_detail_bypart');
                        $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($detail_productivity_bypart_bymonth->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['Mo'],'</td>';
                            echo '<td>'.$data['kode_product'],'</td>';
                            echo '<td>'.$data['nama_product'],'</td>';
                            echo '<td>'.$data['Mac'],'</td>';
                            echo '<td>'.$data['target_mc'],'</td>';
                            echo '<td>'.$data['qty_ok'],'</td>';
                            echo '<td>'.$data['qty_ng'],'</td>';
                            echo '<td>'.$data['max_CTStd'],'</td>';
                            echo '<td>'.$data['max_CTStd2'],'</td>';
                            echo '<td>'.$data['min_CTSet'],'</td>';
                            echo '<td>'.$data['max_CTSet'],'</td>';
                            echo '<td>'.$data['qty_nwt'],'</td>';
                            echo '<td>'.$data['wh'],'</td>';
                            echo '<td>'.$data['qty_ot'],'</td>';
                             echo '<td><center><a href="'.$view_detail.'/'.$data['kode_product'].'/'.$bulan.'"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-search"></i></button></a></td>';
                            echo '</tr>';



                        }  ?>
                    </tbody>
                    <tfoot>
                    <tr>
                    <th>No</th>
                        <th>Mo</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Mac</th>
                        <th>Target-Qty</th>
                        <th>Qty OK</th>
                        <th>Qty NG</th>
                        <th>Max SPM Std</th>
                        <th>Max SPM Std2</th>
                        <th>Min SPM Set</th>
                        <th>Max SPM Set</th>
                        <th>Shift Hour</th>
                        <th>Work Hour</th>
                        <th>Overtime</th>
                        <th>View Detail By Part</th>
                    </tr>
                    </tfoot>
                </table>

    </div>
                </div>
</div>
</div>
</div>
</div>




<?php $this->load->view('layout/footer'); ?>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>
    <!-- Page-Level Scripts -->

    <script src="<?= base_url(); ?>template/js/grafik/highcharts.js"></script>
    <script src="<?= base_url(); ?>template/js/grafik/data.js"></script>
    <script src="<?= base_url(); ?>template/js/grafik/exporting.js"></script>
    <script src="<?= base_url(); ?>template/js/grafik/accessibility.js"></script>
    <script src="<?= base_url(); ?>template/js/grafik/jspdf.umd.min.js"></script>
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
    <figure class="highcharts-figure">



    <script>
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 3,
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Data_2025_12_09'},
                    {extend: 'pdf', title: 'Data_2025_12_09'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
               

            });
        });
        
        // Custom Excel Export
        $('#export_custom_excel').click(function() {
            var selectedYear = $('#export_year').val();
            
            // Show loading state
            var $button = $(this);
            var originalText = $button.html();
            $button.html('<i class="fa fa-spinner fa-spin"></i> Exporting...').prop('disabled', true);
            
            // Create form and submit
            var form = $('<form>', {
                'method': 'POST',
                'action': '<?= base_url('c_report/export_custom_excel') ?>'
            });
            
            var yearInput = $('<input>', {
                'type': 'hidden',
                'name': 'year',
                'value': selectedYear
            });
            
            form.append(yearInput);
            $('body').append(form);
            
            // Submit form
            form.submit();
            
            // Remove form after submission
            setTimeout(function() {
                form.remove();
                // Restore button state (in case download doesn't start)
                $button.html(originalText).prop('disabled', false);
            }, 2000);
        });
}

function exportCustomCSV() {
    var selectedYear = $('#export_year').val();
    
    // Create form and submit for CSV
    var form = $('<form>', {
        'method': 'POST',
        'action': '<?= base_url('c_report/export_custom_csv_direct') ?>'
    });
    
    var yearInput = $('<input>', {
        'type': 'hidden',
        'name': 'year',
        'value': selectedYear
    });
    
    form.append(yearInput);
    $('body').append(form);
    
    // Submit form
    form.submit();
    
    // Remove form after submission
    setTimeout(function() {
        form.remove();
    }, 2000);
}
    </script>
