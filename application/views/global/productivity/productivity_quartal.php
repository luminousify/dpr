 <title>DPR | Report Productivity</title>
<?php $this->load->view('layout/sidebar'); ?>

<!-- We need to ensure jQuery is loaded before any plugins -->
<script>
    // Check if jQuery is already loaded
    if (typeof jQuery === 'undefined') {
        document.write('<script src="<?= base_url(); ?>template/js/jquery-3.1.1.min.js"><\/script>');
    }
</script>

<!-- DataTables CSS -->
<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

<!-- Highcharts -->
<script src="<?= base_url(); ?>template/js/grafik/highcharts.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/data.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/exporting.js"></script>
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
                            
                            // Add HIGH RES image to PDF at smaller display size
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

<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: auto;
        height: auto;
        margin: 0 auto;
    }
    tr { background-color: white; }
    
    /* Annual productivity table styles */
    .dataTables-productivity th {
        background-color: #f5f5f6;
        text-align: center;
        vertical-align: middle !important;
    }
    .dataTables-productivity td {
        text-align: right;
    }
    .dataTables-productivity td:first-child,
    .dataTables-productivity td:nth-child(2) {
        text-align: left;
    }
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
                  <?= form_open('c_report/annual_productivity'); ?>  
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-3"> <b>Pilih Tahun</b> 
                                    <select name="year" class="form-control">
                                      <?php
                                        $current_year = date('Y');
                                        $start_year = $current_year - 1;
                                        $end_year = $current_year + 1;
                                        for($i = $start_year; $i <= $end_year; $i++) {
                                          $selected = ($i == $year) ? 'selected="selected"' : '';
                                          echo "<option value=\"$i\" $selected>$i</option>";
                                        }                          
                                      ?>
                                    </select>
                                </div>
                                <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show"></div>
                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>tahun ini saja</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
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
                                    <label for="export_year_quartal" class="font-weight-bold">Pilih Tahun:</label>
                                    <select id="export_year_quartal" name="year" class="form-control">
                                        <?php 
                                        $currentYear = date('Y');
                                        for($y = $currentYear - 3; $y <= $currentYear + 1; $y++): ?>
                                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>&nbsp;</label>
                                    <button type="button" id="export_custom_excel_quartal" class="btn btn-success btn-block" onclick="exportCustomExcel()">
                                        <i class="fa fa-file-excel-o"></i> Export Excel
                                    </button>
                                </div>
                                <div class="col-sm-3">
                                    <label>&nbsp;</label>
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle"></i> 
                                        Data format: YY | Product ID | Product Name | Cycle Time Standard | Cycle Time Quote | Tool | 01 | 02 | ... | 12 
                                        (kolom 01-12 menampilkan rata-rata CT aktual per bulan)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                                <li><a class="nav-link active" data-toggle="tab" href="#tab-1">Productivity Chart</a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-2">Annual Productivity</a></li>
                            </ul>
                        <div class="tab-content">
                            <div role="tabpanel" id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <?php $this->load->view('global/productivity/productivity_q1'); ?>
                                </div>
                            </div>
                            <div role="tabpanel" id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <?php $this->load->view('global/productivity/annual_productivity'); ?>
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

<!-- Debug jQuery version -->
<script>
    console.log("jQuery version: " + $.fn.jquery);
    console.log("DataTable exists: " + (typeof $.fn.DataTable === 'function'));
</script>

<!-- DataTables scripts -->
<script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>

<!-- Annual productivity table initialization -->
<script>
    // Wait for document fully loaded - this is critical
    $(window).on('load', function() {
        console.log("Window fully loaded");
        console.log("Table element exists:", $('#annual-productivity-table').length > 0);
        
        // Check if table already has DataTable initialized
        var existingTable = $('#annual-productivity-table').hasClass('dataTable');
        console.log("Table already initialized:", existingTable);
        
        if (!existingTable) {
            try {
                // Use exact format from working example
                var table = $('#annual-productivity-table').DataTable({
                    pageLength: 10,
                    responsive: false,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: true,
                    fixedColumns: { left: 2 },
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [
                        {extend: 'copy'},
                        {extend: 'csv'},
                        {extend: 'excel', title: 'Annual Productivity'},
                        {extend: 'pdf', title: 'Annual Productivity'},
                        {extend: 'print',
                         customize: function (win){
                                $(win.document.body).addClass('white-bg');
                                $(win.document.body).css('font-size', '10px');
                                $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                        }}
                    ]
                });
                console.log("Annual productivity table initialized successfully");
            } catch (e) {
                console.error("Error initializing table:", e);
            }
        }
    });
</script>

<!-- OTHER UNRELATED DATATABLES INITIALIZATIONS WILL BE BELOW -->

<script>
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend: 'copy'},
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
                    }}
                ]
            });
        });

        $(document).ready(function(){
            $('.dataTables-example3 tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example3').DataTable({
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

// Custom Excel Export Function
function exportCustomExcel() {
    var selectedYear = $('#export_year_quartal').val();
    
    // Show loading state
    var $button = $('#export_custom_excel_quartal');
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
    }, 5000); // Increased timeout for Excel generation
}



    </script>


    <script type="text/javascript">
        Highcharts.visualize = function(table, options) {
   // the categories
   options.xAxis.categories = [];
   $('tbody th', table).each( function(i) {
      options.xAxis.categories.push(this.innerHTML);
   });
   
   // the data series
   options.series = [];
   $('tr', table).each( function(i) {
      var tr = this;
      $('th, td', tr).each( function(j) {
         if (j > 0) { // skip first column
            if (i == 0) { // get the name and init the series
               options.series[j - 1] = { 
                  name: this.innerHTML,
                  type: j <= 2 ? 'column' : 'line',
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
   
$(document).ready(function() {         
   var table = document.getElementById('datatable1');
   if (!table) return; // Exit if table doesn't exist
   
   // Get current month (not previous month)
   var currentDate = new Date();
   var currentMonthIndex = currentDate.getMonth() + 1; // JavaScript months are 0-based, convert to 1-based for comparison
   
   var options = {
         chart: {
            renderTo: 'container2',
            defaultSeriesType: 'column'
         },
         title: {
            text: 'Production Productivity <?= $tahun; ?> '
         },
         xAxis: {
                    //gridLineWidth: 1,
                    labels: {
                      style: {
                        color: '#000',
                        font: '14px Trebuchet MS, Verdana, sans-serif'
                      }
                    }},
         yAxis: {
            title: {
               text: 'Productivity %'
            },
            min: 0,
            max: 100,
            tickInterval: 10
         },
         tooltip: {
            formatter: function() {
               return '<b>'+ this.series.name +'</b><br/>'+
                  this.y +'% '+ this.x.toLowerCase();
            }
         },
         colors: [ '#4ec4ce', '#f7941d', '#cc113c',  'black'],
         plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return this.y + '%';
                    }
                },
                enableMouseTracking: false
            },
            line: {
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return this.y + '%';
                    }
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
            chartOptions: {
                chart: {
                    width: 800,
                    height: 400
                }
            }
         }
   };
   
   // Modified visualization that limits data to previous month
   // Reference for month names
   var monthNames = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
   
   // Create a custom function to filter by month
   var customVisualize = function(table, options, maxMonthIndex) {
      // The categories (x-axis labels)
      options.xAxis.categories = [];
      var includedMonthIndices = [];
      
      // First gather all categories and identify which ones to include
      $('tbody th', table).each(function(i) {
         var monthText = this.innerHTML;
         var monthNumber = 0;
         
         // Find which month number this is
         for (var m = 1; m <= 12; m++) {
            if (monthText.indexOf(monthNames[m]) !== -1) {
               monthNumber = m;
               break;
            }
         }
         
         // Only include months up to maxMonthIndex
         if (monthNumber <= maxMonthIndex) {
            options.xAxis.categories.push(monthText);
            includedMonthIndices.push(i); // Store the index of included months
         }
      });
      
      // Initialize the data series
      options.series = [];
      var firstRow = true;
      var seriesCount = 0;
      
      // Process the data row by row
      $('tr', table).each(function(i) {
         if (firstRow) {
            // First row contains series names
            firstRow = false;
            $('th, td', this).each(function(j) {
               if (j > 0) { // Skip first column (month names)
                  options.series[j-1] = {
                     name: this.innerHTML,
                     type: j <= 2 ? 'column' : 'line',
                     data: []
                  };
                  seriesCount = j;
               }
            });
         } else {
            // Only add data if this month's index is in our includedMonthIndices list
            if (includedMonthIndices.includes(i-1)) {
               var tr = this;
               $('th, td', tr).each(function(j) {
                  if (j > 0 && j <= seriesCount) { // Add data to appropriate series
                     options.series[j-1].data.push(parseFloat(this.innerHTML));
                  }
               });
            }
         }
      });
      
      // Create the chart
      var chart = new Highcharts.Chart(options);
   };
   
   // Use our custom visualization that limits data to current month
   customVisualize(table, options, currentMonthIndex);
});

$(document).ready(function() {         
   var table = document.getElementById('datatable3');
   if (!table) return; // Exit if table doesn't exist
   var options = {
         chart: {
            renderTo: 'container3',
            defaultSeriesType: 'column'
         },
         title: {
            text: 'Production Productivity <?= $tahun; ?> (Kuartal 2)'
         },
         xAxis: {
                    //gridLineWidth: 1,
                    labels: {
                      style: {
                        color: '#000',
                        font: '14px Trebuchet MS, Verdana, sans-serif'
                      }
                    }},
         yAxis: {
            title: {
               text: 'Productivity %'
            }
         },
         tooltip: {
            formatter: function() {
               return '<b>'+ this.series.name +'</b><br/>'+
                  this.y +'% '+ this.x.toLowerCase();
            }
         },
         colors: [ '#4ec4ce', '#f7941d', '#cc113c',  'black'],
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
    }
      };
   
                     
   Highcharts.visualize(table, options);
});

$(document).ready(function() {         
   var table = document.getElementById('datatable4');
   if (!table) return; // Exit if table doesn't exist
   var options = {
         chart: {
            renderTo: 'container4',
            defaultSeriesType: 'column'
         },
         title: {
            text: 'Production Productivity <?= $tahun; ?> (Kuartal 3)'
         },
         xAxis: {
                    //gridLineWidth: 1,
                    labels: {
                      style: {
                        color: '#000',
                        font: '14px Trebuchet MS, Verdana, sans-serif'
                      }
                    }},
         yAxis: {
            title: {
               text: 'Productivity %'
            }
         },
         tooltip: {
            formatter: function() {
               return '<b>'+ this.series.name +'</b><br/>'+
                  this.y +'% '+ this.x.toLowerCase();
            }
         },
         colors: [ '#4ec4ce', '#f7941d', '#cc113c',  'black'],
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
    }
      };
   
                     
   Highcharts.visualize(table, options);
});




    </script>
