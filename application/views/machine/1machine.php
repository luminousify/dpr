<title>DPR | Daftar Op. Mesin</title>
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
        <?= form_open('c_machine/index'); ?>  
        <div class="col-sm-60" style="margin-left:100px;">
            <h2>Filter</h2>
            <div class="row">
                        <div class="col-sm-3 mb-2">
                            <label><b>Tanggal Dari</b></label>
                            <input type="date" name="tanggal_dari" class="form-control" value="<?= $dari; ?>"> 
                        </div>
                        <div class="col-sm-3 mb-2">
                            <label><b>Tanggal Sampai</b></label>
                            <input type="date" name="tanggal_sampai" class="form-control" value="<?= $sampai; ?>"> 
                        </div>
                        <div class="col-sm-3 mb-2">
                            <label><b>Line</b></label>
                            <select name="line_new"  class="form-control" required=""  >
                                <option value="All" <?= (!isset($line) || $line == 'All' || $line === '') ? 'selected' : ''; ?>>All</option>
                                <?php if (!empty($lines)) { ?>
                                    <?php foreach ($lines as $ln) { $ln = (string) $ln; ?>
                                        <option value="<?= htmlspecialchars($ln); ?>" <?= (isset($line) && (string) $line === $ln) ? 'selected' : ''; ?>><?= htmlspecialchars($ln); ?></option>
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
            }
        });
    }

    var ws = new WebSocket("ws://192.168.50.127/ws");

    ws.onopen = function() {
        // Get plan data and send it through WebSocket
        getPlanforMachineToday(machine_number, function(planData) {
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
                "plan": planData.plan,  // Assuming planData contains the plan information
                "totalprod": 1000,
                "totalnc": 10,
                "user": "Joko",
                "opname": planData.opname,
                "lot": "LotNo"
            };

            // Send the message
            ws.send("2s" + JSON.stringify(message));
            alert("Message is sent...");
        });
    };

    ws.onmessage = function(evt) {
        var received_msg = evt.data;
        alert("Message is received : " + received_msg);
    };

    ws.onclose = function() {
        // WebSocket is closed.
        alert("Connection is closed...");
    };
});
        });


    </script>




