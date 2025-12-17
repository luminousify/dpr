 <title>DPR | Screen Monitoring</title>
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
           
                    <div class="ibox ">
                        <div class="ibox-title">
                             <div class="row">
                                <div class="col-lg-12">
                                    <h5 class="mr-3">Screen Monitoring | Tanggal : <?php echo $tanggal ?> | Shift : <?php echo $shift ?></h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="ibox-content">
                        <?php foreach($total_running->result_array() as $running): ?>
                        <?php endforeach; ?>
                        <?php foreach($total_idle->result_array() as $idle): ?>
                        <?php endforeach; ?>
                        <?= form_open('c_machine/layar'); ?>  
                        <div class="card rounded mb-4">
                            <div class="card-header">
                                <h2>Filter Data</h2>
                            </div>
                            <div class="card-body">
                                <div class="row" style="margin-left:2px;">
                                    <div class="col-sm-3"> <b>Pilih Tanggal (mm/dd/yyyy)</b> 
                                    <input type="date" name="tanggal" class="form-control" value="<?= $tanggal; ?>"> </div>
                                    <div class="col-sm-3"> <b>Shift</b>
                                        <select name="shift" class="form-control">
                                            <option <?php if( $shift =='1'){echo "selected"; } ?> value='1'>1</option>
                                            <option <?php if( $shift =='2'){echo "selected"; } ?> value='2'>2</option>
                                            <option <?php if( $shift =='3'){echo "selected"; } ?> value='3'>3</option>
                                        </select></div>
                                    <div class="col"> <br/><input type="submit" name="show" class="btn btn-primary" value="Show"></div>
                                </div>
                            </div>
                            <div class="row ml-4">
                                <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>hari</strong> dan <strong>shift</strong> hari ini saja, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary">
                              Running <span class="badge badge-light"><?php echo $running['total_running'] ?></span>
                            </button>
                            <button type="button" class="btn btn-warning">
                              Idle <span class="badge badge-light"><?php echo $idle['total_idle'] ?></span>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                    <?php foreach($layar_monitoring->result() as $m): ?>
                        <?php  
                            $cek_running = $m->running;
                                if ($cek_running == 0) {
                                        $warna = '#fff';
                                        $back = '#FFD700';
                                        $product_name_code = '';
                                        $aktual_target = '';
                                        $operator_lot = '';
                                        $persentase = 0;
                                        $ncqt = '';
                                        $ctt_operator = '';
                                } else {
                                        $warna = '#fff';
                                        $back = '#4ed44e';
                                        $product_name_code = $m->np_pr.' ('.$m->kp_pr.')';
                                        $aktual_target = $m->qty_ok.' / '.$m->target_mc;
                                        $operator_lot = $m->pic .' / '.$m->lot_global;
                                        if (!empty($m->qty_ok)) {
                                            if (!empty($m->target_mc)) {
                                                $persentase = round(($m->qty_ok / $m->target_mc) * 100);
                                            } else {
                                                $persentase = 0;
                                            }
                                        }
                                        else {
                                            $persentase = 0;
                                        } 
                                        if ($m->qty_ok > $m->target_mc) {
                                            $keterangan = '+';
                                        } else if ($m->qty_ok < $m->target_mc) {
                                            $keterangan = '-';
                                        } else {
                                            $keterangan = '';
                                        }
                                        
                                        $ncqt = '('.$keterangan.abs($m->qty_ok-$m->target_mc).')';
                                        if ($m->keterangan == null || $m->keterangan == '') {
                                            $ctt_operator = '-';
                                        } else {
                                            $ctt_operator = $m->keterangan;
                                        }
                                        

                                } 
                            ?>
                        <div class="col-md-3 mb-4">
                            <div class="card rounded" style="background-color: <?= $back; ?>;margin:0px;display:inline-block; width: 240px;height: 450px">
                                <div class="features-icon text-center" >
                                    <h2 style="color: <?= $warna; ?>;font-weight: 700;">MC. <?php echo $m->no_mesin ?></h2>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?= $persentase; ?>%" aria-valuenow="<?php echo $persentase ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $persentase ?></div>
                                </div>
                                <br/>
                                <div class="row">
                                </div>
                                <div class="col-sm-12">
                                        Product Name
                                        <br/><b class="tebal" style="color: <?= $warna; ?>"><?php echo $product_name_code?></b>
                                    </div>
                                    <hr/>
                                    <div class="col-sm-12">
                                        Actual / Target (Qty) 
                                        <br/><b class="tebal" style="color: <?= $warna; ?>"><?php echo $aktual_target ?></b><b class="tebal" style="color: #00008B"> <?php echo $ncqt ?></b>
                                    </div>
                                    <hr/>
                                    <div class="col-sm-12">
                                        Operator & Lot 
                                        <br/><b class="tebal" style="color: <?= $warna; ?>"><?php echo $operator_lot ?></b>
                                    </div>
                                    <hr/>
                                    <div class="col-sm-12">
                                        Catatan Operator
                                        <br/><b class="tebal" style="color: <?= $warna; ?>"><?php echo $ctt_operator ?></b>
                                    </div>
                                    <br/>

                                    <div class="col-sm-12 text-center">
                                        <!-- <font color="white"><h3>Alarm Message</h3></font> -->
                                        <a href="<?php echo base_url('c_machine/view_detail_layar/'.$m->tanggal.'/'.$m->no_mesin) ?>" class="btn-edit" >
                                        <button class="btn btn-sm btn-block btn-info border mb-2" style="font-weight: 700">Info Detail</button></a>
                                    </div>
                             </div>
                        </div>
                    <br>
                    <?php endforeach; ?>
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
        });
    </script>




