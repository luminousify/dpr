 <title>DPR | Screen Monitoring Detail</title>
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
              <?php foreach($view_detail_layar->result_array() as $additional): ?>
              <?php endforeach; ?>
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>View Detail Screen Monitoring | Tanggal : <?php echo $additional['tanggal'] ?> | Shift : <?php echo $additional['shift'] ?></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                  
                  <div class="card">
                    <div class="card-body">
                      <div id="container3"></div>
                      <hr>             
                      <table class="table table-bordered dataTables-example" id="datatable3" style="width: 100%;display: none ">
                        <thead>
                          <tr>
                            <th>Shift</th>
                            <th>Target</th>
                            <th>Aktual</th>
                          </tr>
                        </thead>
                          <tbody>
                            <?php $id = -1; $no = 0 ; foreach($view_detail_layar->result_array() as $data)
                            {  $no++; $id++; ?>
                            <tr>
                              <th>Shift <?= $data['shift']; ?></th>
                              <td><?= $data['target_mc']; ?></td>
                              <td><?= $data['qty_ok']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                      </table> 
                      <div class="row">
                        <div class="card rounded ml-4 mb-3">
                          <div class="col-md-12 mt-2">
                            <p><strong>Keterangan<br><button type="button" class="btn btn-sm btn-primary" style="text-shadow: 1px 1px 1px #000000;"></button> : Actual = Target<br> <button type="button" class="btn btn-sm btn-danger" style="text-shadow: 1px 1px 1px #000000;"></button> : Actual < Target</strong></p>
                          </div>
                      </div>
                    </div>
                      <table class="table table-striped table-bordered table-hover dataTables-example2"  style="width:100%" >
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>No. Mesin</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Target</th>
                            <th>Aktual</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $id = -1; $no = 0 ; foreach($view_detail_layar->result_array() as $isi)
                            {  $no++; $id++; ?>
                            <?php 
                              if($isi['qty_ok'] < $isi['target_mc']){
                                $back = '#FFB6C1';
                              } else{
                                $back = '#9dff9d';         
                              }
                            ?>
                            <tr>
                              <td style="background-color:<?php echo $back ?>"><?php echo $no ?></td>
                              <td style="background-color:<?php echo $back ?>"><?= $isi['tanggal']; ?></td>
                              <td style="background-color:<?php echo $back ?>"><?= $isi['shift']; ?></td>
                              <td style="background-color:<?php echo $back ?>"><?= $isi['no_mesin']; ?></td>
                              <td style="background-color:<?php echo $back ?>"><?= $isi['kp_pr']; ?></td>
                              <td style="background-color:<?php echo $back ?>"><?= $isi['np_pr']; ?></td>
                              <td style="background-color:<?php echo $back ?>"><?= $isi['target_mc']; ?></td>
                              <td style="background-color:<?php echo $back ?>"><?= $isi['qty_ok']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <!-- <tfooot>
                          <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>No. Mesin</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Target</th>
                            <th>Aktual</th>
                          </tr>
                        </tfooot> -->
                      </table>
                
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
    <!-- Page-Level Scripts -->
    <script>
      
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


      $(document).ready(function() {         
       var table = document.getElementById('datatable3'),
       options = {
             chart: {
                renderTo: 'container3',
                defaultSeriesType: 'column'
             },
             title: {
                text: 'Hasil Produksi Mesin <?php echo $additional['no_mesin'] ?> (Part No <?php echo $additional['kp_pr'] ?> - <?php echo $additional['np_pr'] ?>)'
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
        }
          };
       
                         
       Highcharts.visualize(table, options);
    });

      
    </script>

    <script type="text/javascript">
      $(document).ready(function(){
            $('.dataTables-example2 tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example2').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
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
    </script>

    
