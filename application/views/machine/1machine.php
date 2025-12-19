<title>DPR | Daftar Op. Mesin</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/select.dataTables.min.css" rel="stylesheet">

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
    
    /* Batch delete controls styling */
    .batch-controls {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .row-checkbox {
        transform: scale(1.2);
    }
    
    .select-all-checkbox {
        transform: scale(1.2);
    }
    
    .dataTables_wrapper .dataTables_scroll {
        clear: both;
    }
    
    /* Highlight selected rows */
    .selected-row {
        background-color: #e3f2fd !important;
    }
} 
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="row">
        <div class="col">
            <h2>Action</h2>
            <?= form_open('c_machine/addNew', array('method' => 'post')); ?>  
            <div class="row">
                <div class="col-sm-6">
                            <select name="line" id="line_select" class="form-control" required=""  >
                                <?php
                                    $lines = (isset($lines) && is_array($lines)) ? $lines : [];
                                    $defaultLine = !empty($lines) ? (string) $lines[0] : '';
                                ?>
                                <?php if (empty($lines)) { ?>
                                    <option value="" selected disabled>-Choose-</option>
                                <?php } else { ?>
                                    <?php foreach ($lines as $ln) { $ln = (string) $ln; ?>
                                        <option value="<?= htmlspecialchars($ln); ?>" <?= ($ln === $defaultLine) ? 'selected' : ''; ?>><?= htmlspecialchars($ln); ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                <div class="col-sm-6">
                                <input type="submit" name="add" class="btn btn-success" value="Add New">
                        </div>
    </div>
    <?= form_close(); ?>
        </div>
        
        <?= form_open('c_machine/copy'); ?>  
        <div class="col-sm-40">
            <div class="ibox" style="margin-bottom: 0;">
                <div class="ibox-title" style="padding-bottom: 10px;">
                    <h5 style="margin-bottom: 0;">Copy Transaksi</h5>
                </div>
                <div class="ibox-content" style="padding-top: 12px;">
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-12">
                            <small class="text-muted">
                                Pilih <b>data sumber</b> yang akan dicopy (tanggal, kanit, line). Setelah klik <b>Copy Transaksi</b>, Anda akan masuk ke halaman copy untuk review & simpan.
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 mb-2">
                            <label><b>Tanggal sumber</b></label>
                            <input type="date" name="tanggal" class="form-control" value="<?= $dari; ?>" required>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <label><b>Kanit sumber</b></label>
                            <select name="group" class="form-control" id="kanit" required>
                                <option value="" selected disabled>-Choose Kanit-</option>
                                <?php foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";}?>
                            </select>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <label><b>Line sumber</b></label>
                            <select name="line" class="form-control" required>
                                <?php if (empty($lines)) { ?>
                                    <option value="" selected disabled>-Choose-</option>
                                <?php } else { ?>
                                    <?php foreach ($lines as $ln) { $ln = (string) $ln; ?>
                                        <option value="<?= htmlspecialchars($ln); ?>" <?= ($ln === $defaultLine) ? 'selected' : ''; ?>><?= htmlspecialchars($ln); ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <label>&nbsp;</label>
                            <button type="submit" name="copy" class="btn btn-primary btn-block" style="white-space: nowrap;">
                                <i class="fa fa-copy"></i> Copy Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
    <br/>
    <div class="row">
        <!-- Dual approach: POST form + GET fallback -->
        <?= form_open('c_machine/index', array('method' => 'post', 'id' => 'dateFilterForm')); ?>  
        <div class="col-sm-60" style="margin-left:100px;">
            <h2>Filter</h2>
            <div class="row">
                        <div class="col-sm-3 mb-2">
                            <label><b>Tanggal Dari</b></label>
                            <input type="date" name="tanggal_dari" class="form-control" value="<?= isset($dari) ? htmlspecialchars($dari) : (isset($_SESSION['machine_filter']['dari']) ? htmlspecialchars($_SESSION['machine_filter']['dari']) : date('Y-m-d')); ?>"> 
                        </div>
                        <div class="col-sm-3 mb-2">
                            <label><b>Tanggal Sampai</b></label>
                            <input type="date" name="tanggal_sampai" class="form-control" value="<?= isset($sampai) ? htmlspecialchars($sampai) : (isset($_SESSION['machine_filter']['sampai']) ? htmlspecialchars($_SESSION['machine_filter']['sampai']) : date('Y-m-d')); ?>"> 
                        </div>
                        <div class="col-sm-3 mb-2">
                            <label><b>Line</b></label>
                            <?php 
                            // Use session data as fallback for line selection
                            $current_line = isset($line) ? $line : (isset($_SESSION['machine_filter']['line']) ? $_SESSION['machine_filter']['line'] : 'All');
                            ?>
                            <select name="line_new"  class="form-control" required=""  >
                                <option value="All" <?= ($current_line == 'All' || $current_line === '') ? 'selected' : ''; ?>>All</option>
                                <?php if (!empty($lines)) { ?>
                                    <?php foreach ($lines as $ln) { $ln = (string) $ln; ?>
                                        <option value="<?= htmlspecialchars($ln); ?>" <?= ($current_line === $ln) ? 'selected' : ''; ?>><?= htmlspecialchars($ln); ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <label>&nbsp;</label>
                            <button type="submit" name="show" class="btn btn-warning btn-block" style="white-space: nowrap;">
                                <i class="fa fa-search"></i> Show
                            </button>
                        </div>
                    </div>
        </div>
        <?= form_close(); ?>
    </div>

    <!-- JavaScript fallback - if POST fails, use GET -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('dateFilterForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Store form data in session storage as backup
                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });
                sessionStorage.setItem('dateFilterBackup', JSON.stringify(data));
                
                // Also create GET version as backup
                const dari = data.tanggal_dari || '';
                const sampai = data.tanggal_sampai || '';
                const line = data.line_new || 'All';
                
                if (dari && sampai) {
                    const getURL = '<?= base_url() ?>c_machine/index?tanggal_dari=' + encodeURIComponent(dari) + '&tanggal_sampai=' + encodeURIComponent(sampai) + '&line_new=' + encodeURIComponent(line) + '&show=1';
                    sessionStorage.setItem('dateFilterGetURL', getURL);
                }
            });
        }
        
        // If there's GET data in URL, restore it to form
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('tanggal_dari')) {
            const dariInput = document.querySelector('input[name="tanggal_dari"]');
            const sampaiInput = document.querySelector('input[name="tanggal_sampai"]');
            const lineInput = document.querySelector('select[name="line_new"]');
            
            if (dariInput && urlParams.get('tanggal_dari')) {
                dariInput.value = urlParams.get('tanggal_dari');
            }
            if (sampaiInput && urlParams.get('tanggal_sampai')) {
                sampaiInput.value = urlParams.get('tanggal_sampai');
            }
            if (lineInput && urlParams.get('line_new')) {
                lineInput.value = urlParams.get('line_new');
            }
        }
    });
    </script>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5 class="mr-3">Machine Use</h5>|<a href="<?php echo base_url('c_machine/add_family_mold') ?>" class="btn btn-sm btn-info ml-3">Family Mold</a>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <div class="tabs-container">
                        <ul class="nav nav-tabs">
                                <li><a class="nav-link active" data-toggle="tab" href="#tab-1">Shift 1</a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-2">Shift 2</a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-3">Shift 3</a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-4">Rekap</a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-5">Total MP</a></li>
                                
                            </ul>
                        <div class="tab-content">
                            <div role="tabpanel" id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <?php $this->load->view('machine/shift1'); ?>
                                </div>
                            </div>
                            <div role="tabpanel" id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <?php $this->load->view('machine/shift2'); ?>
                                </div>
                            </div>
                            <div role="tabpanel" id="tab-3" class="tab-pane">
                                <div class="panel-body">
                                    <?php $this->load->view('machine/shift3'); ?>
                                </div>
                            </div>
                            <div role="tabpanel" id="tab-4" class="tab-pane">
                                <div class="panel-body">
                                    <?php $this->load->view('machine/rekap'); ?>
                                </div>
                            </div>
                            <div role="tabpanel" id="tab-5" class="tab-pane">
                                <div class="panel-body">
                                    <?php $this->load->view('machine/total_mp'); ?>
                                </div>
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
    <script src="<?= base_url(); ?>assets/scripts/dataTables.select.min.js"></script>
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

// Custom PDF export handler with optimized resolution
(function() {
    // Wait for both Highcharts and jsPDF to be ready
    var checkReady = setInterval(function() {
        if (typeof Highcharts !== 'undefined' && (window.jsPDF || (window.jspdf && window.jspdf.jsPDF))) {
            clearInterval(checkReady);
            
            // Get jsPDF reference
            var jsPDFConstructor = window.jsPDF || window.jspdf.jsPDF;
            
            // Create custom PDF export function with optimized resolution
            window.customPDFExport = function(chart) {
                try {
                    // Check for quota exceeded errors
                    if (navigator.storage && navigator.storage.estimate) {
                        navigator.storage.estimate().then(function(estimate) {
                            var usagePercentage = (estimate.usage / estimate.quota) * 100;
                            if (usagePercentage > 80) {
                                console.warn('Storage quota nearly exceeded: ' + usagePercentage.toFixed(2) + '%');
                                alert('Storage quota nearly exceeded. Please clear browser cache.');
                                return;
                            }
                        }).catch(function(error) {
                            console.warn('Unable to check storage quota:', error);
                        });
                    }
                    
                    // Use optimized resolution for better performance
                    var renderWidth = 800;  // Optimized width (reduced from 1200)
                    var renderHeight = 600;  // Optimized height (reduced from 750)
                    
                    // Get the SVG of the chart at optimized resolution
                    var svg = chart.getSVG({
                        chart: {
                            width: renderWidth,
                            height: renderHeight
                        }
                    });
                    
                    // Create an optimized canvas
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
            
            console.log('Custom PDF export handler installed (Optimized Resolution)');
        }
    }, 100);
})();
</script>
    <figure class="highcharts-figure">

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
                  type: j <= 3 ? 'column' : 'line',
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
            text: 'Laporan Machine Use'
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
                  this.y +' '+ this.x.toLowerCase();
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

    <script>
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
                    scrollY:        true,
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 1,
                        right: 2,
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
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

        $(document).ready(function() {
            $('.delete').click(function() {
            return confirm("Are you sure you want to delete?");
            });

            function convertToMachno(number) {
    // Add leading zero if the number is less than 10
    var machno = (number < 10) ? "MC0" + number : "MC" + number;

    return machno;
}


            $('.runBtn').click(function() {
    var machine_number = convertToMachno($(this).data('machno'));
    console.log(machine_number);
    
    // Function to get plan for machine today
    function getPlanforMachineToday(machno, callback) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/c_machine/getPlanforMachineToday/planformachine/machno/",
            dataType: "JSON",
            data: "machno=" + machno,
            success: function(data) {
                callback(data);
            },
            error: function(xhr, status, error) {
                console.error('Error getting plan data:', error);
                callback(null);
            }
        });
    }

    // Create WebSocket with proper error handling and cleanup
    var ws = null;
    try {
        ws = new WebSocket("ws://192.168.50.127/ws");
        
        ws.onopen = function() {
            console.log("WebSocket connection established");
            // Get plan data and send it through WebSocket
            getPlanforMachineToday(machine_number, function(planData) {
                if (planData) {
                    console.log(planData);
                    // Construct the message using planData
                    var message = {
                        "machno": planData.machno,
                        "shift": planData.shift,
                        "partno": planData.partno,
                        "bomid": planData.bomid,
                        "partname": planData.partname,
                        "cav": planData.cav,
                        "cytmc": planData.cytmc,
                        "plan": planData.plan,
                        "totalprod": 1000,
                        "totalnc": 10,
                        "user": "Joko",
                        "opname": planData.opname,
                        "lot": "LotNo"
                    };

                    // Send the message
                    ws.send("2s" + JSON.stringify(message));
                    alert("Message is sent...");
                } else {
                    alert("Failed to get plan data");
                }
            });
        };

        ws.onmessage = function(evt) {
            var received_msg = evt.data;
            console.log("Message received: " + received_msg);
            alert("Message is received : " + received_msg);
        };

        ws.onerror = function(error) {
            console.error("WebSocket error:", error);
            alert("WebSocket connection error");
        };

        ws.onclose = function() {
            console.log("WebSocket connection closed");
            // WebSocket is closed.
            alert("Connection is closed...");
        };
    } catch (error) {
        console.error("Failed to create WebSocket:", error);
        alert("Failed to establish WebSocket connection");
    }
    
    // Cleanup WebSocket on page unload to prevent memory leaks
    $(window).on('beforeunload', function() {
        if (ws && ws.readyState === WebSocket.OPEN) {
            ws.close();
        }
    });
});
});

// Global error handler for quota exceeded issues
window.addEventListener('error', function(e) {
    if (e.message && e.message.includes('quota')) {
        console.error('Quota exceeded error detected:', e.message);
        alert('Storage quota exceeded. Please clear your browser cache and try again.');
        e.preventDefault();
    }
});

// Batch Delete Functionality
console.log("Initializing batch delete functionality...");
$(document).ready(function() {
    console.log("Document ready - setting up batch delete functionality");
    
    // Check if jQuery is properly loaded
    if (typeof $ === 'undefined') {
        console.error("jQuery not loaded!");
        return;
    }
    
    // Function to update selected count and show/hide controls
    function updateBatchControls(shiftClass, controlId, countId, selectAllCheckboxId) {
        var checkboxes = $('.' + shiftClass + ':checked');
        var count = checkboxes.length;
        var controls = $('#' + controlId);
        var countSpan = $('#' + countId);
        var selectAllCheckbox = $('#' + selectAllCheckboxId);
        
        if (count > 0) {
            controls.show();
            countSpan.html('<strong>' + count + '</strong> rows selected');
        } else {
            controls.hide();
        }
        
        // Update select all checkbox state
        var totalCheckboxes = $('.' + shiftClass);
        selectAllCheckbox.prop('checked', totalCheckboxes.length > 0 && totalCheckboxes.length === count);
    }
    
    // Function to handle checkbox changes for all shifts
    function setupBatchDeleteHandlers(shiftClass, controlId, countId, selectAllCheckboxId, selectAllBtnId, deselectAllBtnId, deleteBtnId) {
        console.log("Setting up batch delete handlers for:", shiftClass);
        
        try {
            // Row checkbox changes
            $(document).on('change', '.' + shiftClass, function() {
                console.log("Checkbox changed for class:", shiftClass);
                updateBatchControls(shiftClass, controlId, countId, selectAllCheckboxId);
            });
        
        // Select all checkbox in header
        $(document).on('change', '#' + selectAllCheckboxId, function() {
            var checked = $(this).prop('checked');
            $('.' + shiftClass).prop('checked', checked);
            updateBatchControls(shiftClass, controlId, countId, selectAllCheckboxId);
        });
        
        // Select all button
        $(document).on('click', '#' + selectAllBtnId, function() {
            $('.' + shiftClass).prop('checked', true);
            updateBatchControls(shiftClass, controlId, countId, selectAllCheckboxId);
        });
        
        // Deselect all button
        $(document).on('click', '#' + deselectAllBtnId, function() {
            $('.' + shiftClass).prop('checked', false);
            updateBatchControls(shiftClass, controlId, countId, selectAllCheckboxId);
        });
        
        // Delete selected button
        $(document).on('click', '#' + deleteBtnId, function() {
            var checkboxes = $('.' + shiftClass + ':checked');
            var ids = [];
            
            checkboxes.each(function() {
                ids.push($(this).val());
            });
            
            if (ids.length === 0) {
                alert('No rows selected for deletion.');
                return;
            }
            
            if (confirm('Are you sure you want to delete ' + ids.length + ' selected record(s)?')) {
                $.ajax({
                    url: '<?= base_url("c_machine/batch_delete_machine_use"); ?>',
                    type: 'POST',
                    data: {
                        ids: ids
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Remove deleted rows from table
                            checkboxes.closest('tr').remove();
                            // Update controls
                            updateBatchControls(shiftClass, controlId, countId, selectAllCheckboxId);
                            // Refresh the page after a short delay to update totals
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error occurred while deleting records. Please try again.');
                        console.error('AJAX Error:', error);
                    }
                });
            }
        });
        } catch (error) {
            console.error("Error in setupBatchDeleteHandlers for", shiftClass, ":", error);
        }
    }
    
    try {
        // Setup handlers for all three shifts
        console.log("Setting up handlers for all three shifts");
        
        setupBatchDeleteHandlers(
            'shift1-checkbox', 
            'batch-controls-shift1', 
            'selected-count-shift1', 
            'select-all-checkbox-shift1',
            'select-all-shift1',
            'deselect-all-shift1',
            'delete-selected-shift1'
        );
        
        setupBatchDeleteHandlers(
            'shift2-checkbox', 
            'batch-controls-shift2', 
            'selected-count-shift2', 
            'select-all-checkbox-shift2',
            'select-all-shift2',
            'deselect-all-shift2',
            'delete-selected-shift2'
        );
        
        setupBatchDeleteHandlers(
            'shift3-checkbox', 
            'batch-controls-shift3', 
            'selected-count-shift3', 
            'select-all-checkbox-shift3',
            'select-all-shift3',
            'deselect-all-shift3',
            'delete-selected-shift3'
        );
        
        console.log("Batch delete functionality setup completed successfully");
    } catch (error) {
        console.error("Error setting up batch delete functionality:", error);
    }
});


    </script>




