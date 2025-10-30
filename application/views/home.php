<title>DPR | Dashboard</title>
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
<?php  
$posisi = $this->session->userdata('posisi');
$nama = $this->session->userdata('nama_actor');
$bagian = $this->session->userdata('divisi');
if ($posisi == 'ppic' || $posisi == 'tm' || $posisi =='qa' || $posisi == 'mixerA' || $posisi == 'mixerB') { ?>
    <div class="wrapper wrapper-content">
        <div class="card rounded p-4">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="center">
                        <h1 style="font-size: 40px;text-align: center;"><strong>Welcome, <?php echo $nama; ?> (<?php echo $posisi; ?> - <?php echo $bagian; ?>)</strong></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php  } else{ ?>
    <?= form_open('c_new/home'); ?>  
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fa fa-filter"></i> Filter Data</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label><b>Pilih Tanggal</b></label>
                                    <input type="date" name="tanggal" class="form-control" value="<?= $tanggal; ?>"> 
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label><b>Shift</b></label>
                                    <select name="shift"  class="form-control" required="">
                                        <option <?php if( $shift =='All'){echo "selected"; } ?> value='All'>All</option>
                                        <option <?php if( $shift =='1'){echo "selected"; } ?> value='1'>1</option>
                                        <option <?php if( $shift =='2'){echo "selected"; } ?> value='2'>2</option>
                                        <option <?php if( $shift =='3'){echo "selected"; } ?> value='3'>3</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2" style="margin-top: 27px">
                                    <button type="submit" name="show" class="btn btn-primary btn-sm">
                                        <i class="fa fa-search"></i> Show
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?= form_close(); ?>
 <div class="wrapper wrapper-content">
        <div class="row">
        <?php $id = -1; $no = 0 ; foreach($data_tabelHeader->result_array() as $data)
        {  $no++; $id++; ?>
                    <div class="col-lg">
                        <div class="ibox ">
                            <div class="ibox-title bg-primary">
                                <h5>OK</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?= number_format($data['ok']); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="ibox ">
                            <div class="ibox-title bg-danger" >
                                <h5>NG</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?= number_format($data['ng']); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="ibox ">
                            <div class="ibox-title bg-warning">
                                <h5>LT</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?= number_format($data['lt']); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="ibox ">
                            <div class="ibox-title bg-info">
                                <h5>Nett</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?= number_format($data['persen_Nett'],0); ?>%</h1>
                            </div>
                        </div>
            </div>
            <div class="col-lg">
                        <div class="ibox ">
                            <div class="ibox-title bg-success">
                                <h5>Gross</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?= number_format($data['persen_Gross'],0); ?>%</h1>
                            </div>
                        </div>
            </div>
        <?php } ?>
        </div>
        <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox ">
                            <div class="ibox-title">
                                <h5>Daftar Op. Mesin</h5>
                                <div class="float-right">
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="container2"></div>
                                        </div>
                                    </div>
                                        
                                        <table class="table table-striped table-bordered table-hover" id="datatable1" style="display:none;">
                                            <thead>
                                                <tr>
                                                    <th>Shift</th>
                                                    <th>Mesin Running</th>
                                                    <th>Man Power Dibutuhkan</th>
                                                    <th>Man Power Hadir</th>
                                                    <th>Man Power Tidak Hadir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $id = -1; $no = 0 ; foreach($data_tabelRekapNew->result_array() as $data)
                                                {  $no++; $id++; ?>
                                                <tr>
                                                    <th><?= $data['shift']; ?></th>
                                                    <td><?= $data['sumRun']; ?></td>
                                                    <td><?= round($data['sumPower'],1); ?></td>
                                                    <td><?= $data['total']; ?></td>
                                                    <td><?= $data['total_tidak_hadir']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="card">
                                            <div class="card-header">
                                                <h4><b>RASIO MAN POWER</b></h4>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered dataTables-example1" id="";>
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">Tanggal</th>
                                                            <th class="text-center align-middle">Shift</th>
                                                            <th class="text-center align-middle">Aktual</th>
                                                            <th class="text-center align-middle">Target</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-center align-middle">
                                                        <?php $id = -1; $no = 0 ; foreach($data_tabelRekap->result_array() as $data)
                                                        {  $no++; $id++; 
                                                        $rata=($data['sumPower']!=0)?number_format(($data['sumPower']/$data['sumRun']),1):0; ?>
                                                        <tr 
                                                        <?php if($rata > 0.7){ echo 'style="background-color:#FFB6C1"'; } else { echo ''; }?>>
                                                            <th><?= $data['tanggal']; ?></th>
                                                            <td><center><?= $data['shift']; ?></center></td>
                                                            <td><?php echo 
                                                            $rata=($data['sumPower']!=0)?number_format(($data['sumPower']/$data['sumRun']),1):0; ?></td>
                                                            <td>0.7</td> 
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php  
                                        $posisi = $this->session->userdata('posisi');
                                        if ($posisi == 'admin' || $posisi == 'Kadept' || $posisi == 'KaDiv' || $posisi == 'Kasie') { ?>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4><b>DEFECT & LOSSTIME BY KANIT</b></h4>
                                                </div>
                                                <div class="card-body">
                                                    <div id="container3"></div>
                                                    <hr>
                                                        <table class="table table-bordered dataTables-example2" id="datatable3" style="width: 100% ">
                                                            <thead>
                                                                <tr>
                                                                    <th>Kanit</th>
                                                                    <th>Total Produksi</th>
                                                                    <th>Total OK</th>
                                                                    <th>Total NG</th>
                                                                    <th>Total LT</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $id = -1; $no = 0 ; foreach($data_ng_lt_kanit->result_array() as $data)
                                                                {  $no++; $id++; ?>
                                                                <tr>
                                                                    <th><?= $data['kanit']; ?></th>
                                                                    <td><?= $data['total_produksi']; ?></td>
                                                                    <td><?= $data['total_ok']; ?></td>
                                                                    <td><?= $data['total_ng']; ?></td>
                                                                    <td><?= $data['total_lt']; ?></td>
                                                                </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                </div>
                                            </div>
                                        <?php }
                                        else{
                                            echo "";
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </div>
<?php }
?>

<?php $this->load->view('layout/footer'); ?>
<script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>
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
                  type: j <= 6 ? 'column' : 'line',
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
            text: 'Grafik Daftar Operasional Mesin <?= $tanggal; ?>'
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
               text: 'Op. Mesin'
            }
         },
         tooltip: {
            formatter: function() {
               return '<b>'+ this.series.name +'</b><br/>'+
                  this.y +' '+ this.x.toLowerCase();
            }
         },
         colors: [ '#4ec4ce', '#f7941d', '#cc113c',  'black', '#663399'],
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
   var table = document.getElementById('datatable3'),
   options = {
         chart: {
            renderTo: 'container3',
            defaultSeriesType: 'column'
         },
         title: {
            text: 'Grafik Defect & Losstime by Kanit'
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
               text: 'Total'
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
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 3,
                        right: 2
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


        $(document).ready(function(){
            $('.dataTables-example2 tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            $('.dataTables-example2').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 3,
                        right: 2
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





</body>
</html>
