 <title>DPR | Report <?php echo $jenis ?></title>
 <?php $this->load->view('layout/sidebar'); ?>

 <link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

 <link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

 <style>
     th,
     td {
         white-space: nowrap;
     }

     div.dataTables_wrapper {
         width: auto;
         /*width: 1000px;*/
         height: auto;
         margin: 0 auto;
     }

     tr {
         background-color: white
     }
 </style>
 <?php $this->load->view('report/menu_report'); ?>
 <?php $valueNya < 5 || $valueNya > 5 ? $angka = 31 : $angka = 12; ?>
 <input type="hidden" name="" id="iniValue" value="<?php echo $valueNya == '' ? 1 : $valueNya; ?>">
 <div class="wrapper wrapper-content animated fadeInRight">
     <div class="row">
         <div class="col-lg-12">
             <div class="ibox ">
                 <div class="ibox-title">
                     <h5><?= $judul_laporan; ?></h5>
                     <div class="ibox-tools">
                         <a class="collapse-link">
                             <i class="fa fa-chevron-up"></i>
                         </a>
                     </div>
                 </div>
                 <div class="ibox-content">
                    <?php if (strpos($jenis, 'qty_ng') !== false): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Date:</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control datepicker" id="ng_report_date" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" id="print_ng_report">
                                        <i class="fa fa-print"></i> Print NG Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                         <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                             <thead>
                                 <tr>
                                     <th><b>No</b></th>
                                     <th><b>Product ID</b></th>
                                     <th><b>Product Name</b></th>
                                     <?php if ($valueNya > 5) {  ?>
                                         <th><b>Customer</b></th>
                                     <?php } ?>
                                     <?php if ($valueNya > 1 && $valueNya < 5) {  ?>
                                         <th><b>Proses</b></th>
                                     <?php } ?>
                                     <?php if ($valueNya > 1 && $valueNya < 5) {  ?>
                                         <th><b>Machine</b></th>
                                     <?php } ?>
                                     <?php if ($valueNya > 2 && $valueNya < 5) {  ?>
                                         <th><b>Shift</b></th>
                                     <?php } ?>
                                     <?php if ($valueNya > 3 && $valueNya < 5) {  ?>
                                         <th><b>Group</b></th>
                                         <th><b>Target</b></th>
                                         <th><b>CT Standar</b></th>
                                         <th><b>CT Aktual</b></th>
                                     <?php } ?>
                                     <th><?= $name; ?></th>
                                     <?php for ($i = 1; $i <= $angka; $i++) { ?><th style="background:#1ab394;color: white;text-align: center;">
                                             <center><?php echo $i; ?></center>
                                         </th><?php } ?>
                                     <?php if ($valueNya > 0 && $valueNya < 6) {  ?>
                                         <th><b>Alias BOM</b></th>
                                         <th><b>ID BOM</b></th>
                                     <?php } ?>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php $total = 0;
                                    $total_ng = 0;
                                    $no = 0;
                                    foreach ($data_production->result_array() as $data) {
                                        $no++;
                                        echo '<tr>';
                                        echo '<td>' . $no;
                                        '</td>';
                                        echo '<td class="str">' . $data['kode_product'], '</td>';
                                        echo '<td>' . $data['nama_product'], '</td>';
                                        if ($valueNya > 5) {
                                            echo '<td>' . $data['customer'], '</td>';
                                        }
                                        if ($valueNya > 1 && $valueNya < 5) {
                                            echo '<td>' . $data['nama_proses'], '</td>';
                                        }
                                        if ($valueNya > 1 && $valueNya < 5) {
                                            echo '<td>' . $data['mesin'], '</td>';
                                        }
                                        if ($valueNya > 2 && $valueNya < 5) {
                                            echo '<td>' . $data['shift'], '</td>';
                                        }
                                        if ($valueNya > 3 && $valueNya < 5) {
                                            echo '<td>' . $data['kanit'], '</td>';
                                            echo '<td>' . $data['target'], '</td>';
                                            echo '<td>' . ($data['cyt_mc'] + $data['cyt_mp']), '</td>';
                                            echo '<td>' . ($data['ct_mc_aktual'] + $data['ct_mp_aktual']), '</td>';
                                        }
                                        echo '<td>' . round($data['total'], 2), '</td>';
                                        for ($i = 1; $i <= $angka; $i++) {
                                            echo '<td>' . round($data['t' . $i . $name], 2) . '</td>';
                                        }
                                        if ($valueNya > 0 && $valueNya < 6) {
                                            echo '<td>' . $data['alias_bom'], '</td>';
                                            echo '<td>' . $data['id_bom'], '</td>';
                                        }
                                        echo '</tr>';
                                    }
                                    ?>
                             </tbody>
                             <tfoot>
                                 <tr>
                                     <th><b>No</b></th>
                                     <th><b>Product ID</b></th>
                                     <th><b>Product Name</b></th>
                                     <?php if ($valueNya > 5) {  ?>
                                         <th><b>Customer</b></th>
                                     <?php } ?>
                                     <?php if ($valueNya > 1 && $valueNya < 6) {  ?>
                                         <th><b>Proses</b></th>
                                     <?php } ?>
                                     <?php if ($valueNya > 1 && $valueNya < 5) {  ?>
                                         <th><b>Machine</b></th>
                                     <?php } ?>
                                     <?php if ($valueNya > 2 && $valueNya < 5) {  ?>
                                         <th><b>Shift</b></th>
                                     <?php } ?>
                                     <?php if ($valueNya > 3 && $valueNya < 5) {  ?>
                                         <th><b>Group</b></th>
                                         <th><b>Target</b></th>
                                         <th><b>CT Standar</b></th>
                                         <th><b>CT Aktual</b></th>
                                     <?php } ?>
                                     <th><?= $name; ?></th>
                                     <?php for ($i = 1; $i <= $angka; $i++) { ?><th style="background:#1ab394;color: white;text-align: center;">
                                             <center><?php echo $i; ?>
                                         </th>
                                         </center><?php } ?>
                                     <?php if ($valueNya > 0 && $valueNya < 6) {  ?>
                                         <th><b>Alias BOM</b></th>
                                         <th><b>ID BOM</b></th>
                                     <?php } ?>
                                 </tr>
                             </tfoot>
                         </table>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <div class="wrapper wrapper-content animated fadeInRight">
         <div class="row">
             <div class="col-lg-12">
                 <div class="ibox ">
                     <div class="ibox-title">
                         <h5>Grafik <?= $judul_laporan; ?></h5>
                         <div class="ibox-tools">
                             <a class="collapse-link">
                                 <i class="fa fa-chevron-up"></i>
                             </a>
                         </div>
                     </div>
                     <div class="ibox-content">
                         <div class="table-responsive">
                             <div id="container2"></div>
                             <table id="datatable1" border="1" style="display:none">
                                 <thead>
                                     <tr>
                                         <th></th>
                                         <th>Quantity</th>
                                         <th>Quantity</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php $no = 0;
                                        foreach ($data_grafik->result_array() as $data) {
                                            $no++; ?>
                                         <tr>
                                             <th><?php echo $data['kode_product'] . '<br/>(' . $data['nama_product'] . ')'; ?></th>
                                             <td><?php echo round($data['total'], 2); ?></td>
                                             <td><?php echo round($data['total'], 2); ?></td>
                                         </tr>
                                     <?php } ?>
                                 </tbody>

                             </table>

                         </div>
                     </div>
                 </div>
             </div>
         </div>

     </div>
 </div>


 <style>
     .str {
         mso-number-format: \@;
     }
 </style>



 <?php $this->load->view('layout/footer'); ?>
 <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
 <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
 <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>
 <!-- Page-Level Scripts -->

 <script src="<?= base_url(); ?>template/js/grafik/highcharts.js"></script>
 <script src="<?= base_url(); ?>template/js/grafik/data.js"></script>
 <script src="<?= base_url(); ?>template/js/grafik/exporting.js"></script>
 <script>
     // Ensure jsPDF is available globally for offline-exporting
     window.jspdf = window.jspdf || {};
 </script>
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


 <figure class="highcharts-figure">

     <script type="text/javascript">
         Highcharts.visualize = function(table, options) {
             // the categories
             options.xAxis.categories = [];
             $('tbody th', table).each(function(i) {
                 options.xAxis.categories.push(this.innerHTML);
             });

             // the data series
             options.series = [];
             $('tr', table).each(function(i) {
                 var tr = this;
                 $('th, td', tr).each(function(j) {
                     if (j > 0) { // skip first column
                         if (i == 0) { // get the name and init the series
                             options.series[j - 1] = {
                                 name: this.innerHTML,
                                 type: j === 1 ? 'column' : 'spline',
                                 data: []
                             };
                         } else { // add values
                             options.series[j - 1].data.push(parseFloat(this.innerHTML));
                         }
                     }
                 });
             });

             var chart = new Highcharts.Chart(options);
         }

         // On document ready, call visualize on the datatable.
         $(document).ready(function() {
             var table = document.getElementById('datatable1'),
                 options = {
                     chart: {
                         renderTo: 'container2',
                         defaultSeriesType: 'column'
                     },
                     title: {
                         text: ' <?= $judul_laporan . ' ' . $tanggal; ?> '
                     },
                     xAxis: {
                         //gridLineWidth: 1,
                         labels: {
                             style: {
                                 color: '#000',
                                 font: '14px Trebuchet MS, Verdana, sans-serif'
                             }
                         }
                     },
                     yAxis: {
                         title: {
                             text: 'Pcs'
                         }
                     },
                     tooltip: {
                         formatter: function() {
                             return '<b>' + this.series.name + '</b><br/>' +
                                 this.y + ' ' + this.x.toLowerCase();
                         }
                     },
                     colors: ['#4ec4ce', '#f7941d', '#cc113c', '#d24087'],
                     plotOptions: {
                         column: {
                             dataLabels: {
                                 enabled: true
                             },
                             enableMouseTracking: false
                         },
                         line: {
                             dataLabels: {
                                 enabled: true
                             },
                             enableMouseTracking: false
                         }
                     }
                 };


             // Add offline export configuration
            options.exporting = {
                enabled: true,
                fallbackToExportServer: false,
                buttons: {
                    contextButton: {
                        menuItems: ['viewFullscreen', 'separator', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                    }
                },
                sourceWidth: 800,
                sourceHeight: 400,
                scale: 2,
                chartOptions: {
                    chart: {
                        width: 800,
                        height: 400
                    }
                }
            };
            
            Highcharts.visualize(table, options);
         });
     </script>




     <script>
         $(document).ready(function() {
             // Initialize datepicker for NG report
             if($('#ng_report_date').length) {
                 $('#ng_report_date').datepicker({
                     todayBtn: "linked",
                     keyboardNavigation: false,
                     forceParse: false,
                     calendarWeeks: true,
                     autoclose: true,
                     format: "yyyy-mm-dd"
                 });
                 
                 // Handle print NG report button click
                 $('#print_ng_report').on('click', function() {
                     var selectedDate = $('#ng_report_date').val();
                     if(selectedDate) {
                         // Create a popup window for printing
                         var printWindow = window.open('<?= site_url("c_dpr/print_ng_report") ?>?date=' + selectedDate, '_blank', 'width=800,height=600');
                         
                         // You can also use this alternative approach to redirect to print page
                         // window.location.href = '<?= site_url("c_dpr/print_ng_report") ?>?date=' + selectedDate;
                         
                         // Focus the print window
                         if(printWindow) {
                             printWindow.focus();
                         }
                     } else {
                         alert('Please select a date first');
                     }
                 });
             }
             
             // Initialize DataTables
             $('.dataTables-example tfoot th').each(function() {
                 $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
             });
             var iniValue = parseInt($('#iniValue').val()) + 3;
             if (iniValue == 9) {
                 iniValue = 0;
             }
             //alert(iniValue);
             $('.dataTables-example').DataTable({
                 pageLength: 10,
                 responsive: false,
                 // scrollY:        "300px",
                 scrollX: true,
                 scrollCollapse: true,
                 paging: true,
                 fixedColumns: {
                     left: iniValue,
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
                         title: 'ExampleFile'
                     },
                     {
                         extend: 'pdf',
                         title: 'ExampleFile'
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
                     // Apply the search
                     this.api().columns().every(function() {
                         var that = this;

                         $('input', this.footer()).on('keyup change clear', function() {
                             if (that.search() !== this.value) {
                                 that
                                     .search(this.value)
                                     .draw();
                             }
                         });
                     });
                 }


             });
         });
     </script>