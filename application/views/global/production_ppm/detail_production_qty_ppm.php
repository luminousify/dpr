 <title>DPR | Report Prod. Qty & PPM</title>
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
                        <h5>Detail Production Qty & PPM</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                	<?php foreach ($total_prod_qty_and_ppm->result_array() as $prod_ppm_detail) : ?>  
            		<?php endforeach; ?>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                      <thead>
                          <tr>
                              <th class="align-middle text-center" rowspan="3">No</th>
                              <th class="align-middle text-center" rowspan="3">Part ID</th>
                              <th class="align-middle text-center" rowspan="3">Part Name</th>
                              <th class="align-middle text-center" rowspan="3">Cost</th>
                              <th class="align-middle text-left" colspan="36" style="background:#1ab394;color: white;text-align: center;">Bulan</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">Total Prod</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">Total Prod OK</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">Total Prod NG</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">% NG</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">Total PPm</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Jan ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Feb ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Mar ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Apr ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">May ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Jun ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Jul ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Aug ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Sep ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Okt ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Nov ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Des ($) Sub Total</th>
                          </tr>
                          <tr>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jan</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Feb</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Mar</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Apr</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">May</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jun</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jul</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Aug</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Sep</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Okt</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Nov</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Des</th>
                          </tr>
                          <tr>
                              <?php for ($i = 1; $i <= 12; $i++) { ?>
                                  <th style="background:#1ab394;color: white;text-align: center;">
                                      <center>Total Prod</center>
                                  </th>
                                  <th style="background:#1ab394;color: white;text-align: center;">
                                      <center>Prod OK</center>
                                  </th>
                                  <th style="background:#1ab394;color: white;text-align: center;">
                                      <center>Prod NG</center>
                                  </th>
                              <?php } ?>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          $total = 0;
                          $no = 0;
                          $sum = 0;
                          foreach ($prod_qty_and_ppm->result_array() as $data) {
                              $no++;
                              echo '<tr>';
                              echo '<td>'.$no;'</td>';
                              echo '<td>'.$data['kode_product'];'</td>';
                              echo '<td>'.$data['nama_product'];'</td>';
                              echo '<td>'.$data['cost'];'</td>';
                              for ($i = 1; $i <= 12; $i++) {
                                  echo '<td>' . round($data['tot_prod' . $i]) . '</td>';
                                  echo '<td>' . round($data['ok' . $i]) . '</td>';
                                  echo '<td>' . round($data['ng' . $i]) . '</td>';
                              }
                              echo '<td>'.$data['total_prod'];'</td>';
                              echo '<td>'.$data['qty_ok'];'</td>';
                              echo '<td>'.$data['qty_ng'];'</td>';
                              echo '<td>'.$data['persen_ng'];'</td>';
                              echo '<td>'.$data['ppm'];'</td>';
                              echo '<td>'.$data['sub_total1'];'</td>';
                              echo '<td>'.$data['sub_total2'];'</td>';
                              echo '<td>'.$data['sub_total3'];'</td>';
                              echo '<td>'.$data['sub_total4'];'</td>';
                              echo '<td>'.$data['sub_total5'];'</td>';
                              echo '<td>'.$data['sub_total6'];'</td>';
                              echo '<td>'.$data['sub_total7'];'</td>';
                              echo '<td>'.$data['sub_total8'];'</td>';
                              echo '<td>'.$data['sub_total8'];'</td>';
                              echo '<td>'.$data['sub_total10'];'</td>';
                              echo '<td>'.$data['sub_total11'];'</td>';
                              echo '<td>'.$data['sub_total12'];'</td>';
                              echo '</tr>';
                          }
                          ?>

                          <?php  
                          foreach ($total_prod_qty_and_ppm->result_array() as $total_prod_ppm) {
                              echo '<tr>';
                              echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                              echo '<td style="background:#1ab394;color: white;text-align: center;">Total</td>';
                              echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                              echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                              for ($i = 1; $i <= 12; $i++) {
                                  echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($total_prod_ppm['total_prod' . $i],1) . '</td>';
                                  echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($total_prod_ppm['total_ok' . $i],2) . '</td>';
                                  echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($total_prod_ppm['total_ng' . $i],2) . '</td>';
                              }
                              echo '<td style="background:#5bc0de;color: white;text-align: center;">'.$total_prod_ppm['total_prod'].'</td>';
                              echo '<td style="background:#5bc0de;color: white;text-align: center;">'.$total_prod_ppm['total_ok'].'</td>';
                              echo '<td style="background:#5bc0de;color: white;text-align: center;">'.$total_prod_ppm['total_ng'].'</td>';
                              echo '<td style="background:#5bc0de;color: white;text-align: center;">'.$total_prod_ppm['total_persen_ng'].'</td>';
                              echo '<td style="background:#5bc0de;color: white;text-align: center;">'.$total_prod_ppm['total_ppm'].'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total1']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total2']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total3']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total4']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total5']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total6']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total7']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total8']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total9']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total10']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total11']).'</td>';
                              echo '<td style="background:#f0ad4e;color: white;text-align: center;">'.round($total_prod_ppm['total_sub_total12']).'</td>';
                              echo '</tr>';
                          }
                          ?>

                          <tr>
                              <td style="background:#1ab394;color: white;text-align: center;"></td>
                              <td style="background:#1ab394;color: white;text-align: center;">Total PPM</td>
                              <td style="background:#1ab394;color: white;text-align: center;"></td>
                              <td style="background:#1ab394;color: white;text-align: center;"></td>
                              <?php  
                              for ($i = 1; $i <= 12; $i++) {
                                  $ppm = ($prod_ppm_detail['total_prod'.$i] != 0) ? ($prod_ppm_detail['total_ng'.$i] / $prod_ppm_detail['total_prod'.$i]) *1000000 : 0;
                                  $ppm_fix = round($ppm);
                                  echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                                  echo '<td style="background:#1ab394;color: white;text-align: center;">' . $ppm_fix . '</td>';
                                  echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                              }
                              ?>
                              <td style="background:#5bc0de;color: white;text-align: center;"></td>
                              <td style="background:#5bc0de;color: white;text-align: center;"></td>
                              <td style="background:#5bc0de;color: white;text-align: center;"></td>
                              <td style="background:#5bc0de;color: white;text-align: center;"></td>
                              <td style="background:#5bc0de;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                              <td style="background:#f0ad4e;color: white;text-align: center;"></td>
                          </tr>
                      </tbody>
                      <tfoot>
                          <tr>
                              <th class="align-middle text-center" rowspan="3">No</th>
                              <th class="align-middle text-center" rowspan="3">Part ID</th>
                              <th class="align-middle text-center" rowspan="3">Part Name</th>
                              <th class="align-middle text-center" rowspan="3">Cost</th>
                              <th class="align-middle text-left" colspan="36" style="background:#1ab394;color: white;text-align: center;">Bulan</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">Total Prod</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">Total Prod OK</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">Total Prod NG</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">% NG</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#5bc0de;color: white;text-align: center;">Total PPm</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Jan ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Feb ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Mar ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Apr ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">May ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Jun ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Jul ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Aug ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Sep ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Okt ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Nov ($) Sub Total</th>
                              <th class="align-middle text-center" rowspan="3" style="background:#f0ad4e;color: white;text-align: center;">Des ($) Sub Total</th>
                          </tr>
                          <tr>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jan</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Feb</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Mar</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Apr</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">May</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jun</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jul</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Aug</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Sep</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Okt</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Nov</th>
                              <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Des</th>
                          </tr>
                          <tr>
                              <?php for ($i = 1; $i <= 12; $i++) { ?>
                                  <th style="background:#1ab394;color: white;text-align: center;">
                                      <center>Total Prod</center>
                                  </th>
                                  <th style="background:#1ab394;color: white;text-align: center;">
                                      <center>Prod OK</center>
                                  </th>
                                  <th style="background:#1ab394;color: white;text-align: center;">
                                      <center>Prod NG</center>
                                  </th>
                              <?php } ?>
                          </tr>
                      </tfoot>
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
    </script>
