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
            <?= form_open('c_machine/addNew'); ?>  
            <div class="row">
                <div class="col-sm-6">
                            <select name="line"  class="form-control" required=""  >
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
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
            <h2>Copy Transaksi</h2>
            <div class="row">
                        <div class="col-sm-5">
                            <input type="date" name="tanggal" class="form-control" value="<?= $dari; ?>"> 
                        </div>
                        <div class="col-sm-3">
                            <select name="group" class="form-control" id="kanit" required=""  >
                                <option>Kanit</option>
                                <?php foreach ($kanit as $b) { echo "<option value='$b[nama_operator]'>$b[nama_operator]</option>";}?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="line"  class="form-control" required=""  >
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                        <div class="col-sm-1"><input type="submit" name="copy" class="btn btn-primary" value="Copy"></div>
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
                        <div class="col-sm-4">
                            <input type="date" name="tanggal_dari" class="form-control" value="<?= $dari; ?>"> 
                        </div>
                        <div class="col-sm-4">
                            <input type="date" name="tanggal_sampai" class="form-control" value="<?= $sampai; ?>"> 
                        </div>
                        <div class="col-sm-2">
                            <select name="line_new"  class="form-control" required=""  >
                                <option value="All">All</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                        <div class="col-sm-2"><input type="submit" name="show" class="btn btn-warning" value="Show"></div>
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
window.jsPDF = (window.jspdf && window.jspdf.jsPDF) || window.jsPDF;

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
        // Define Highcharts.visualize function globally
        function defineHighchartsVisualize() {
            if (typeof Highcharts !== 'undefined' && typeof $ !== 'undefined') {
                Highcharts.visualize = function(table, options) {
                    // the categories
                    options.xAxis.categories = [];
                    $('tbody th', table).each( function(i) {
                        var content = $(this).html();
                        if (content && content.trim()) {
                            options.xAxis.categories.push(content);
                        }
                    });
                    
                    // the data series
                    options.series = [];
                    $('tr', table).each( function(i) {
                        var tr = this;
                        $('th, td', tr).each( function(j) {
                            if (j > 0) { // skip first column
                                if (i == 0) { // get the name and init the series
                                    var cellContent = $(this).html();
                                    options.series[j - 1] = {
                                        name: cellContent || 'Series ' + (j),
                                        type: j <= 3 ? 'column' : 'line',
                                        data: []
                                    };
                                } else { // add values
                                    var cellValue = parseFloat($(this).html());
                                    if (!isNaN(cellValue)) {
                                        options.series[j - 1].data.push(cellValue);
                                    } else {
                                        options.series[j - 1].data.push(0);
                                    }
                                }
                            }
                        });
                    });
                    
                    var chart = new Highcharts.Chart(options);
                };
            }
        }

        // Comprehensive initialization with proper error handling and timing
        function initializeAllComponents() {
            // Check if jQuery is loaded
            if (typeof $ === 'undefined') {
                console.error('jQuery not loaded, retrying...');
                setTimeout(initializeAllComponents, 100);
                return;
            }

            // Check if DataTables is loaded
            if (typeof $.fn.DataTable === 'undefined') {
                console.error('DataTables not loaded, retrying...');
                setTimeout(initializeAllComponents, 100);
                return;
            }

            // Check if Highcharts is loaded
            if (typeof Highcharts === 'undefined') {
                console.error('Highcharts not loaded, retrying...');
                setTimeout(initializeAllComponents, 100);
                return;
            }

            console.log('All libraries loaded, initializing components...');

            try {
                // Define Highcharts.visualize first
                defineHighchartsVisualize();

                // Initialize DataTables with comprehensive error handling
                initializeDataTables();
                
                // Initialize Highcharts visualization with proper checks
                initializeHighchartsVisualization();
                
                // Initialize other components
                initializeOtherComponents();

            } catch (error) {
                console.error('Component initialization error:', error);
            }
        }

        function initializeDataTables() {
            // Initialize shift tables (dataTables-example class) - ONLY VISIBLE ONES
            $('.dataTables-example').each(function() {
                var table = $(this);
                
                // Check if table exists and has structure
                if (!table || table.length === 0) {
                    console.warn('Table not found for DataTables initialization');
                    return;
                }

                // SKIP if table is in a hidden tab
                if (!table.is(':visible')) {
                    console.log('Skipping DataTable initialization for hidden table');
                    return;
                }

                // Add search inputs to footer if exists
                if (table.find('tfoot').length > 0) {
                    table.find('tfoot th').each( function () {
                        $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
                    } );
                }
                
                // Check if table has data before initializing DataTable
                if (table.find('tbody tr').length === 0) {
                    console.warn('No data found in table, skipping DataTable initialization');
                    return;
                }

                // Check if already initialized
                if ($.fn.DataTable.isDataTable(table)) {
                    console.log('DataTable already initialized for this table');
                    return;
                }

                try {
                    table.DataTable({
                        pageLength: 10,
                        responsive: false,
                        scrollY: true,
                        scrollX: true,
                        scrollCollapse: true,
                        paging: true,
                        fixedColumns: {
                            left: 1,
                            right: 2,
                        },
                        // Add column definitions to handle empty cells
                        columnDefs: [
                            {
                                targets: '_all',
                                render: function(data, type, row, meta) {
                                    // Handle empty or undefined data
                                    if (data === null || data === undefined || data === '') {
                                        return type === 'display' ? '' : '-';
                                    }
                                    return data;
                                }
                            }
                        ],
                        // Handle empty data in cells
                        createdRow: function(row, data, dataIndex) {
                            // Ensure all cells have proper content
                            try {
                                $('td', row).each(function(index) {
                                    var cellContent = $(this).html();
                                    if (cellContent === '' || cellContent === null || cellContent === undefined) {
                                        $(this).html('');
                                    }
                                });
                            } catch (cellError) {
                                console.warn('Error in createdRow callback:', cellError);
                            }
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
                            var api = this.api();
                            api.columns().every( function () {
                                var that = this;
                                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                                    if ( that.search() !== this.value ) {
                                        that.search( this.value ).draw();
                                    }
                                } );
                            } );
                        }
                    });
                } catch (dtError) {
                    console.error('DataTables initialization error:', dtError);
                }
            });

            // Initialize rekap table separately - ONLY IF VISIBLE
            $('.dataTables-example-rekap').each(function() {
                var table = $(this);
                if (table.length > 0 && table.is(':visible')) {
                    // Check if already initialized
                    if ($.fn.DataTable.isDataTable(table)) {
                        return;
                    }
                    try {
                        table.DataTable({
                            pageLength: 10,
                            responsive: false,
                            scrollY: true,
                            scrollX: true,
                            scrollCollapse: true,
                            paging: true,
                            columnDefs: [
                                {
                                    targets: '_all',
                                    render: function(data, type, row, meta) {
                                        if (data === null || data === undefined || data === '') {
                                            return type === 'display' ? '' : '-';
                                        }
                                        return data;
                                    }
                                }
                            ]
                        });
                    } catch (dtError) {
                        console.error('Rekap DataTables initialization error:', dtError);
                    }
                }
            });

            // Initialize total MP table separately - ONLY IF VISIBLE
            $('.dataTables-example-total-mp').each(function() {
                var table = $(this);
                if (table.length > 0 && table.is(':visible')) {
                    // Check if already initialized
                    if ($.fn.DataTable.isDataTable(table)) {
                        return;
                    }
                    try {
                        table.DataTable({
                            pageLength: 10,
                            responsive: false,
                            scrollY: true,
                            scrollX: true,
                            scrollCollapse: true,
                            paging: true,
                            columnDefs: [
                                {
                                    targets: '_all',
                                    render: function(data, type, row, meta) {
                                        if (data === null || data === undefined || data === '') {
                                            return type === 'display' ? '' : '-';
                                        }
                                        return data;
                                    }
                                }
                            ]
                        });
                    } catch (dtError) {
                        console.error('Total MP DataTables initialization error:', dtError);
                    }
                }
            });
        }

        function initializeHighchartsVisualization() {
            // Check if datatable1 exists before trying to visualize
            var table = document.getElementById('datatable1');
            if (!table) {
                console.warn('datatable1 not found for Highcharts visualization');
                return;
            }

            // Check if table has data
            var $table = $(table);
            if ($table.find('tbody tr').length === 0) {
                console.warn('No data in datatable1 for Highcharts visualization');
                return;
            }

            try {
                var options = {
                    chart: {
                        renderTo: 'container2',
                        defaultSeriesType: 'column'
                    },
                    title: {
                        text: 'Laporan Machine Use'
                    },
                    xAxis: {
                        labels: {
                            style: {
                                color: '#000',
                                font: '14px Trebuchet MS, Verdana, sans-serif'
                            }
                        }
                    },
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
            } catch (hcError) {
                console.error('Highcharts visualization error:', hcError);
            }
        }

        function initializeOtherComponents() {
            // Define helper function
            function convertToMachno(number) {
                // Add leading zero if the number is less than 10
                var machno = (number < 10) ? "MC0" + number : "MC" + number;
                return machno;
            }

            // Initialize delete confirmation
            $('.delete').click(function() {
                return confirm("Are you sure you want to delete?");
            });

            // Initialize edit button handler for total MP
            $('.btn-edit').click(function(){
                // get data from button edit
                const id                    = $(this).data('id');
                const tanggal               = $(this).data('tanggal');
                const shift                 = $(this).data('shift');
                const line                  = $(this).data('line');
                const total                 = $(this).data('total');
                const total_tidak_hadir     = $(this).data('total_tidak_hadir');
                const total_sisa_mp_hadir   = $(this).data('total_sisa_mp_hadir');
                const keterangan            = $(this).data('keterangan');
                // Set data to Form Edit
                $('.id').val(id);
                $('.tanggal').val(tanggal);
                $('.shift').val(shift);
                $('.line').val(line);
                $('.total').val(total);
                $('.total_tidak_hadir').val(total_tidak_hadir);
                $('.total_sisa_mp_hadir').val(total_sisa_mp_hadir);
                $('.keterangan').val(keterangan);
                $('#editModal').modal('show');
            });

            // Initialize WebSocket functionality
            $('.runBtn').click(function() {
                try {
                    var machine_number = convertToMachno($(this).data('machno'));
                    console.log(machine_number);
                    
                    // Function to get plan for machine today
                    function getPlanforMachineToday(machno, callback) {
                        if (typeof $ !== 'undefined') {
                            $.ajax({
                                type: "POST",
                                url: "<?php echo base_url(); ?>index.php/c_machine/getPlanforMachineToday/planformachine/machno/",
                                dataType: "JSON",
                                data: "machno=" + machno,
                                success: function(data) {
                                    callback(data);
                                }
                            });
                        } else {
                            console.warn('jQuery not available for AJAX call');
                        }
                    }

                    if (typeof WebSocket !== 'undefined') {
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
                    } else {
                        console.warn('WebSocket not available');
                    }
                } catch (wsError) {
                    console.error('WebSocket initialization error:', wsError);
                }
            });
        }

        // Initialize tables when tab is shown
        function initializeTabTables(tabPane) {
            // Initialize any DataTables in the newly shown tab
            $(tabPane).find('.dataTables-example, .dataTables-example-rekap, .dataTables-example-total-mp').each(function() {
                var table = $(this);
                
                // Skip if already initialized
                if ($.fn.DataTable.isDataTable(table)) {
                    return;
                }
                
                // Skip if no data
                if (table.find('tbody tr').length === 0) {
                    return;
                }
                
                try {
                    // Add footer inputs if needed
                    if (table.find('tfoot').length > 0 && !table.hasClass('dataTables-example-rekap') && !table.hasClass('dataTables-example-total-mp')) {
                        table.find('tfoot th').each( function () {
                            if ($(this).find('input').length === 0) {
                                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
                            }
                        } );
                    }
                    
                    // Initialize based on table type
                    if (table.hasClass('dataTables-example-rekap') || table.hasClass('dataTables-example-total-mp')) {
                        table.DataTable({
                            pageLength: 10,
                            responsive: false,
                            scrollY: true,
                            scrollX: true,
                            scrollCollapse: true,
                            paging: true,
                            columnDefs: [
                                {
                                    targets: '_all',
                                    render: function(data, type, row, meta) {
                                        if (data === null || data === undefined || data === '') {
                                            return type === 'display' ? '' : '-';
                                        }
                                        return data;
                                    }
                                }
                            ]
                        });
                    } else {
                        table.DataTable({
                            pageLength: 10,
                            responsive: false,
                            scrollY: true,
                            scrollX: true,
                            scrollCollapse: true,
                            paging: true,
                            fixedColumns: {
                                left: 1,
                                right: 2,
                            },
                            columnDefs: [
                                {
                                    targets: '_all',
                                    render: function(data, type, row, meta) {
                                        if (data === null || data === undefined || data === '') {
                                            return type === 'display' ? '' : '-';
                                        }
                                        return data;
                                    }
                                }
                            ],
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
                                var api = this.api();
                                api.columns().every( function () {
                                    var that = this;
                                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                                        if ( that.search() !== this.value ) {
                                            that.search( this.value ).draw();
                                        }
                                    } );
                                } );
                            }
                        });
                    }
                } catch (dtError) {
                    console.error('Tab DataTable initialization error:', dtError);
                }
            });
        }

        // Add event listener for tab changes
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href"); // activated tab
                initializeTabTables(target);
            });
        });

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeAllComponents);
        } else {
            initializeAllComponents();
        }
    </script>




