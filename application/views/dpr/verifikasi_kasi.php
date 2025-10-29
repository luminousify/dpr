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
                        <h5>Verifikasi Kasie</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                    <?= form_open('c_dpr/verifikasi_dpr_by_kasi'); ?>  
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-4"> <b>Tanggal Dari (mm/dd/yyyy)</b> 
                                    <input type="date" name="tanggal_dari" class="form-control" value="<?= $dari; ?>"> </div>
                                    <div class="col-sm-4"> <b>Tanggal Sampai (mm/dd/yyyy)</b>
                                        <input type="date" name="tanggal_sampai" class="form-control" value="<?= $sampai; ?>"> </div>
                                    <div class="col"> <b>Shift</b>
                                        <select name="shift" class="form-control">
                                            <option value="All">All</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select></div>
                                    <div class="col"> <br/><input type="submit" name="show" class="btn btn-primary" value="Show"></div>

                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Data yang muncul adalah data <strong>SELURUH SHIFT HARI INI</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                        </div>
                    </div>
                    <?= form_close(); ?>
                    <br>
                    <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th> 
                                <th><b>Tanggal</b></th> 
                                <th><b>Kanit</b></th> 
                                <th><b>Mesin</b></th> 
                                <th><b>CT Std (Sec)</b></th>  
                                <th><b>Gross (Sec)</b></th>
                                <th><b>Product ID</b></th> 
                                <th><b>Product Name</b></th> 
                                <th><b>Proses</b></th>
                                <th><b>Shift</b></th> 
                                <th><b>Qty OK</b></th> 
                                <th><b>Qty NG</b></th> 
                                <th><b>NWT (Jam)</b></th> 
                                <th><b>CDT (Jam)</b></th> 
                                <th><b>LT (Jam)</b></th> 
                                <th class="align-middle text-center"><b>Production Time (Jam)</b></th>  
                                <th><b>Total Prod</b></th>
                                <th><b>Runner (KG)</b></th> 
                                <th><b>Loss Purge( KG)</b></th>
                                <th><b>Lot Production</b></th>   
                                <th><b>OT (Jam)</b></th>
                                <th><b>CT Act (Sec)</b></th> 
                                <th><b>Nett (Sec)</b></th> 
                                <th><b>ket.</b></th> 
                                <th><b>Operator</b></th> 
                                <th><b>PIC</b></th> 
                                <th><b>Customer</b></th> 
                            </tr>                        
                        </thead>
                        <tbody>
                            <?php 
                            $user       = $this->session->userdata('posisi');
                            $nama       = $this->session->userdata('nama_actor');
                            $no = 0 ; foreach($verif_kasi->result_array() as $data) 
                            {  $no++;
                                $cdt = ($data['nwt_mp'] - ($data['production_time'] + $data['qty_lt']));
                                $cdt_new = round($cdt,1);
                               $hasil = $data['cek_kanit'];
                               if ($hasil == null) {
                                   $background = '#FFB6C1';
                               } else {
                                   $background = '#9dff9d';
                               }
                               
                                echo '<tr >';
                                echo '<td style="background-color:'.$background.'">'.$no.'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['tanggal'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['kanit'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['mesin'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.number_format($data['CTStd2'],2).'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['gross_prod'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['kp_pr'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['np_pr'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['proses'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['shift'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.number_format($data['qty_ok']).'</td>'; 
                                echo '<td style="background-color:'.$background.'">'.number_format($data['qty_ng']).'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['nwt_mp'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$cdt_new.'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['qty_lt'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['production_time'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.number_format($data['qty_ok'] + $data['qty_ng'] ).'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['runner'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['loss_purge'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['lot_global'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['ot_mp'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['ct_mc_aktual'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['nett_prod'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['keterangan'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['operator'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['pic'].'</td>';
                                echo '<td style="background-color:'.$background.'">'.$data['customer'].'</td>';
                                echo '</tr>';
                            }
                        ?> 
                        </tbody>
                    </table>
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
                        left: 2,
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

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




    </script>




