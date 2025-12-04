 <title>DPR | Report Efficiency Machine</title>
 <?php $this->load->view('layout/sidebar'); ?>

 <link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

 <link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

 <div class="wrapper wrapper-content animated fadeInRight">
     <div class="row">
         <div class="col-lg-12">
             <div class="ibox ">
                 <div class="ibox-title">
                     <h5>Efficiency Machine</h5>
                     <div class="ibox-tools">
                         <a class="collapse-link">
                             <i class="fa fa-chevron-up"></i>
                         </a>
                     </div>
                 </div>
                 <div class="ibox-content">
                     <?= form_open('c_report/efesiencyMesin/'); ?>
                     <div class="card rounded mb-4">
                         <div class="card-header">
                             <h2>Filter Data</h2>
                         </div>
                         <div class="card-body">
                             <div class="row" style="margin-left:2px;">
                                 <div class="col-sm-3"> <b>Pilih Tahun - Bulan</b>
                                     <select name="tahun" value="<?php echo $tahun ?>" class="form-control">
                                         <?php
                                            $tahuns = date('Y') - 1;
                                            $now = date('Y');
                                            for ($i = $tahuns; $i <= $now; $i++) {
                                                $monts = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                                                foreach ($monts as $value) { ?>
                                                 <option value="<?php echo $i . '-' . $value ?>" <?php if ($value == $bulan && $i == $tahun) {
                                                                                                        echo 'selected="selected"';
                                                                                                    } ?>><?php echo $i . '-' . $value; ?></option>;<?php
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
                         <div class="row mt-3">
                             <div class="col-12">
                                 <a href="<?= base_url(); ?>c_report/export_machine_efficiency_excel?year=<?= $tahun; ?>" 
                                    class="btn btn-success" 
                                    title="Export Machine Efficiency Data to Excel">
                                    <i class="fa fa-file-excel-o"></i> Export to Excel
                                 </a>
                             </div>
                         </div>
                     </div>
                     <?= form_close(); ?>
                     <div class="tabs-container">
                         <ul class="nav nav-tabs">
                             <li><a class="nav-link active" data-toggle="tab" href="#tab-1">Machine Use By Machine By Month</a></li>
                             <li><a class="nav-link" data-toggle="tab" href="#tab-2">Total Capacity Machine By Month</a></li>
                             <li><a class="nav-link" data-toggle="tab" href="#tab-3">Effeciency Machine Rekap</a></li>
                         </ul>
                         <div class="tab-content">
                             <div role="tabpanel" id="tab-1" class="tab-pane active">
                                 <div class="panel-body">
                                     <?php $this->load->view('global/efesiency/efesiencyMonth'); ?>
                                 </div>
                             </div>
                             <div role="tabpanel" id="tab-2" class="tab-pane">
                                 <div class="panel-body">
                                     <?php $this->load->view('global/efesiency/efesiencyYear'); ?>
                                 </div>
                             </div>
                             <div role="tabpanel" id="tab-3" class="tab-pane">
                                 <div class="panel-body">
                                     <?php $this->load->view('global/efesiency/efesiencyRekap'); ?>
                                 </div>
                             </div>

                         </div>




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
                                 type: j <= 1 ? 'column' : 'line',
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
                         text: 'Machine Use By Month (<?= $bulan; ?> - <?= $tahun; ?>) (Hours)'
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
                             text: 'Hours'
                         }
                     },
                     tooltip: {
                         formatter: function() {
                             return '<b>' + this.series.name + '</b><br/>' +
                                 this.y + ' ' + this.x.toLowerCase();
                         }
                     },
                     colors: ['#4ec4ce', '#f7941d', '#cc113c', 'black'],
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
                     },
                     exporting: {
                         fallbackToExportServer: false,
                         sourceWidth: 800,
                         sourceHeight: 400,
                         scale: 2,
                         chartOptions: {
                             plotOptions: {
                                 series: {
                                     dataLabels: {
                                         enabled: true
                                     }
                                 }
                             }
                         },
                         buttons: {
                             contextButton: {
                                 menuItems: ['viewFullscreen', 'separator', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                             }
                         },
                         pdfHandler: function() {
                             const { jsPDF } = window.jspdf;
                             if (!jsPDF) {
                                 console.error('jsPDF not available');
                                 return false;
                             }
                             
                             try {
                                 const chart = this;
                                 const svg = chart.getSVG({ 
                                     sourceWidth: 800, 
                                     sourceHeight: 400 
                                 });
                                 
                                 const canvas = document.createElement('canvas');
                                 const ctx = canvas.getContext('2d');
                                 canvas.width = 800;
                                 canvas.height = 400;
                                 
                                 const img = new Image();
                                 img.onload = function() {
                                     ctx.drawImage(img, 0, 0);
                                     
                                     const pdf = new jsPDF('l', 'pt', [800, 400]);
                                     const imgData = canvas.toDataURL('image/png');
                                     pdf.addImage(imgData, 'PNG', 0, 0, 800, 400);
                                     pdf.save(chart.title.textStr + '.pdf');
                                 };
                                 
                                 const blob = new Blob([svg], { type: 'image/svg+xml' });
                                 img.src = URL.createObjectURL(blob);
                                 
                             } catch (error) {
                                 console.error('Error in PDF export:', error);
                                 return false;
                             }
                         }
                     }
                 };


             Highcharts.visualize(table, options);
         });
     </script>

     <script>
         $(document).ready(function() {
             $('.dataTables-example tfoot th').each(function() {
                 $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
             });
             //alert(iniValue);
             $('.dataTables-example').DataTable({
                 pageLength: 10,
                 responsive: false,
                 // scrollY:        "300px",
                 scrollX: true,
                 scrollCollapse: true,
                 paging: true,
                 fixedColumns: {
                     left: 2,
                     right: 2,
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

         $(document).ready(function() {
             $('.dataTables-example2 tfoot th').each(function() {
                 $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
             });
             //alert(iniValue);
             $('.dataTables-example2').DataTable({
                 pageLength: 10,
                 responsive: false,
                 // scrollY:        "300px",
                 scrollX: true,
                 scrollCollapse: true,
                 paging: true,
                 fixedColumns: {
                     left: 1,
                     right: 2,
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

         $(document).ready(function() {
             $('.dataTables-example3 tfoot th').each(function() {
                 $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
             });
             //alert(iniValue);
             $('.dataTables-example3').DataTable({
                 pageLength: 10,
                 responsive: false,
                 // scrollY:        "300px",
                 scrollX: true,
                 scrollCollapse: true,
                 paging: true,
                 fixedColumns: {
                     left: 1,
                     right: 2,
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


         Highcharts.chart('container3', {
             data: {
                 table: 'datatable3'
             },
             chart: {
                 type: 'column'
             },
             title: {
                 text: 'Efficiency Machine  (Hours)'
             },
             yAxis: {
                 allowDecimals: false,
                 title: {
                     text: 'Total'
                 }
             },
             tooltip: {
                 formatter: function() {
                     return '<b>' + this.series.name + '</b><br/>' +
                         this.point.y + ' ' + this.point.name.toLowerCase();
                 }
             },
             plotOptions: {
                 column: {
                     dataLabels: {
                         enabled: true
                     },
                     enableMouseTracking: false
                 }
             },
             exporting: {
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
                 pdfHandler: function() {
                     const { jsPDF } = window.jspdf;
                     if (!jsPDF) {
                         console.error('jsPDF not available');
                         return false;
                     }
                     
                     try {
                         const chart = this;
                         const svg = chart.getSVG({ 
                             sourceWidth: 800, 
                             sourceHeight: 400 
                         });
                         
                         const canvas = document.createElement('canvas');
                         const ctx = canvas.getContext('2d');
                         canvas.width = 800;
                         canvas.height = 400;
                         
                         const img = new Image();
                         img.onload = function() {
                             ctx.drawImage(img, 0, 0);
                             
                             const pdf = new jsPDF('l', 'pt', [800, 400]);
                             const imgData = canvas.toDataURL('image/png');
                             pdf.addImage(imgData, 'PNG', 0, 0, 800, 400);
                             pdf.save(chart.title.textStr + '.pdf');
                         };
                         
                         const blob = new Blob([svg], { type: 'image/svg+xml' });
                         img.src = URL.createObjectURL(blob);
                         
                     } catch (error) {
                         console.error('Error in PDF export:', error);
                         return false;
                     }
                 }
             }
         });

         Highcharts.chart('container4', {
             data: {
                 table: 'datatable4'
             },
             chart: {
                 type: 'column'
             },
             title: {
                 text: 'Efficiency Machine (%)'
             },
             yAxis: {
                 allowDecimals: false,
                 title: {
                     text: '%'
                 }
             },
             tooltip: {
                 formatter: function() {
                     return '<b>' + this.series.name + '</b><br/>' +
                         this.point.y + ' ' + this.point.name.toLowerCase();
                 }
             },
             plotOptions: {
                 column: {
                     dataLabels: {
                         enabled: true
                     },
                     enableMouseTracking: false
                 }
             },
             exporting: {
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
                 pdfHandler: function() {
                     const { jsPDF } = window.jspdf;
                     if (!jsPDF) {
                         console.error('jsPDF not available');
                         return false;
                     }
                     
                     try {
                         const chart = this;
                         const svg = chart.getSVG({ 
                             sourceWidth: 800, 
                             sourceHeight: 400 
                         });
                         
                         const canvas = document.createElement('canvas');
                         const ctx = canvas.getContext('2d');
                         canvas.width = 800;
                         canvas.height = 400;
                         
                         const img = new Image();
                         img.onload = function() {
                             ctx.drawImage(img, 0, 0);
                             
                             const pdf = new jsPDF('l', 'pt', [800, 400]);
                             const imgData = canvas.toDataURL('image/png');
                             pdf.addImage(imgData, 'PNG', 0, 0, 800, 400);
                             pdf.save(chart.title.textStr + '.pdf');
                         };
                         
                         const blob = new Blob([svg], { type: 'image/svg+xml' });
                         img.src = URL.createObjectURL(blob);
                         
                     } catch (error) {
                         console.error('Error in PDF export:', error);
                         return false;
                     }
                 }
             }
         });
     </script>