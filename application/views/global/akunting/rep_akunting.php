 <title>DPR | Report Accounting</title>
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
                        <h5>Report Accounting</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                  <?php foreach ($rep_total->result_array() as $rep_total) : ?>  
                  <?php endforeach; ?>
                    <?= form_open('c_report/report_akunting'); ?>  
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col"> <b>Tanggal Dari (mm/dd/yyyy)</b> 
                                            <input type="date" name="tanggal_dari" class="form-control" value="<?= $dari; ?>"> 
                                        </div>
                                        <div class="col"> <b>Tanggal Sampai (mm/dd/yyyy)</b>
                                            <input type="date" name="tanggal_sampai" class="form-control" value="<?= $sampai; ?>"> 
                                        </div>
                                        <?php  
                                        if ($sampai == null) {
                                          $time=strtotime(date('Y-M-d'));
                                        }
                                        else{
                                          $time=strtotime($sampai);
                                        }
                                        $month=date("M",$time);
                                        ?>
                                        <input type="hidden" name="" value="<?php echo $month ?>">
                                <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show"></div>
                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>bulan ini saja</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                        </div>
                    </div>
                    <?= form_close(); ?>
                    <table class="table table-bordered dataTables-example" id="datatable1" style="width: 100% ">
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">No.MC</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Ton</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Production Name</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Part Nomor</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Material Name</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Pure Mat <br>(Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Mb <br>(Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Afv <br>(Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Afv Rcd <br>(Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Var <br>(Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">%</th>
                                                                    <th colspan="3" class="text-center align-middle" style="background:#1ab394;color: white">Penerimaan</th>
                                                                    <th colspan="5" class="text-center align-middle" style="background:#1ab394;color: white">Pengeluaran</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">KG <br> (Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Standard Use/Pce <br>(Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Total Kg</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Gr/Pc <br> (Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Runner/Shoot <br>(Kg)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">Cav</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">CT <br>(Hours)</th>
                                                                    <th rowspan="2" class="text-center align-middle" style="background:#1ab394;color: white">MOH <br> (Hours)</th>
                                                                </tr>
                                                                <tr>
                                                                  <th class="text-center align-middle" style="background:#1ab394;color: white">Produksi <br>(Pcs)</th>
                                                                  <th class="text-center align-middle" style="background:#1ab394;color: white">RTN dari FGW <br>(Pcs)</th>
                                                                  <th class="text-center align-middle" style="background:#1ab394;color: white">RTN dari ASSY <br>(Pcs)</th>
                                                                  <th class="text-center align-middle" style="background:#1ab394;color: white">WH FG <br>(Pcs)</th>
                                                                  <th class="text-center align-middle" style="background:#1ab394;color: white">OK Assy <br>(Pcs)</th>
                                                                  <th class="text-center align-middle" style="background:#1ab394;color: white">RTN ke FGW <br>(Pcs)</th>
                                                                  <th class="text-center align-middle" style="background:#1ab394;color: white">RTN ke ASSY <br>(Pcs)</th>
                                                                  <th class="text-center align-middle" style="background:#1ab394;color: white">NG <br>(Pcs)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $id = -1; $no = 0 ; foreach($rep_akunting1->result_array() as $data)
                                                                { $no++; $id++; 
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center align-middle"><?= $data['mesin']; ?></td>
                                                                    <td class="text-center align-middle"><?= $data['tonnase']; ?></td>
                                                                    <td><?= $data['np_pr']; ?></td>
                                                                    <td><?= $data['kp_pr']; ?></td>
                                                                    <td><?= $data['np_mr']; ?></td>
                                                                    <td><?= $data['virgin']; ?></td>
                                                                    <td></td>
                                                                    <td><?= $data['regrind']; ?></td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['afv_rcd'];
                                                                        $afv_rcd = str_replace('.', ',', $bahan);  
                                                                      ?>
                                                                      <?= $afv_rcd; ?>
                                                                    </td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['var_kg'];
                                                                        $var_kg = str_replace('.', ',', $bahan);  
                                                                      ?>
                                                                      <?= $var_kg; ?>
  
                                                                    </td>
                                                                    <td></td>
                                                                    
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['tot_prod'];
                                                                        
                                                                      ?>
                                                                      <?= $data['tot_prod']; ?>
                                                                    </td>
                                                                    <!-- Span -->
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['tot_ok'];
                                                                        $tot_ok = str_replace(',', '.', $bahan);  
                                                                      ?>
                                                                      <?=  $data['tot_ok']; ?>
                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = number_format($data['tot_ng']);
                                                                        $tot_ng = str_replace(',', '.', $bahan);  
                                                                      ?>
                                                                      <?= $tot_ng; ?>
                                                                    </td>
                                                                    <!-- Tambahan -->
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['kg'];
                                                                        $kg = str_replace('.', ',', $bahan);  
                                                                      ?>
                                                                      <?= $kg; ?>
                                                                    </td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['standar_use_kg'];
                                                                        $standar_use_kg = str_replace('.', ',', $bahan);  
                                                                      ?>
                                                                      <?= $standar_use_kg; ?>
                                                                    </td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['total_kg'];
                                                                        $total_kg = str_replace('.', ',', $bahan);  
                                                                      ?>
                                                                      <?= $total_kg; ?>
                                                                    </td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['gr_per_pc'];
                                                                        $gr_per_pc = str_replace('.', ',', $bahan);  
                                                                      ?>
                                                                      <?= $gr_per_pc; ?>
                                                                    </td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['runner_per_shots'];
                                                                        $runner_per_shots = str_replace('.', ',', $bahan);  
                                                                      ?>
                                                                      <?= $runner_per_shots; ?>
                                                                    </td>
                                                                    <td><?= $data['cavity']; ?></td>
                                                                    <td><?= $data['ct_mc']; ?></td>
                                                                    <td>
                                                                      <?php
                                                                        $bahan = $data['moh'];
                                                                        $moh = str_replace('.', ',', $bahan);  
                                                                      ?>
                                                                      <?= $moh; ?>
                                                                    </td>
                                                                </tr>
                                                                <?php } ?>
                                                                <tr>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white">Total</td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white">
                                                                    <?php
                                                                        $bahan = $rep_total['afv_rcd'];
                                                                        $hasil = str_replace('.', ',', $bahan);  
                                                                    ?>
                                                                    <?= $hasil; ?>
                                                                  </td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white">
                                                                    <?php
                                                                        $bahan = number_format($rep_total['tot_prod']);
                                                                        $hasil = str_replace(',', '.', $bahan);  
                                                                    ?> 
                                                                    <?= $hasil; ?>
                                                                  </td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white">
                                                                    <?php
                                                                        $bahan = number_format($rep_total['tot_ok']);
                                                                        $hasil = str_replace(',', '.', $bahan);  
                                                                    ?> 
                                                                    <?= $hasil; ?>
                                                                  </td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white">
                                                                    <?php
                                                                        $bahan = number_format($rep_total['tot_ng']);
                                                                        $hasil = str_replace(',', '.', $bahan);  
                                                                    ?> 
                                                                    <?= $hasil; ?>
                                                                  </td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white"></td>
                                                                  <td style="background:#f0ad4e;color: white">
                                                                    <?php
                                                                        $bahan = $rep_total['moh'];
                                                                        $hasil = str_replace('.', ',', $bahan);  
                                                                    ?>
                                                                    <?= $hasil; ?>
                                                                  </td>
                                                              </tr>
                                                            </tbody>
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
    <script src="<?= base_url(); ?>assets/scripts/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>assets/scripts/buttons.html5.min.js"></script>
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
            text: 'Defect By Kategori (<?= $bulan; ?> - <?= $tahun; ?>)'
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
               text: 'Defect (Pcs)'
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


// On document ready, call visualize on the datatable.
$(document).ready(function() {         
   var table = document.getElementById('datatable3'),
   options = {
         chart: {
            renderTo: 'container3',
            defaultSeriesType: 'column'
         },
         title: {
            text: 'Defect By Month'
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
               text: 'Defect (Pcs)'
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



// On document ready, call visualize on the datatable.
$(document).ready(function() {         
   var table = document.getElementById('datatable4'),
   options = {
         chart: {
            renderTo: 'container4',
            defaultSeriesType: 'column'
         },
         title: {
            text: 'Defect By Product (<?= $bulan; ?> - <?= $tahun; ?>)'
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
               text: 'Defect (Pcs)'
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
                        left: 2,
                    },
                    bSortCellsTop: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                  { extend: 'copy'},
                    {extend: 'csv'},
                    // START Replacement for Excel Button
                    {
                        extend: 'excel', 
                        title: 'Report Akunting <?php echo $month ?> - <?php echo date('Y') ?>',
                        messageTop: 'Monthly Injection Report \n<?php echo $month ?> - <?php echo date('Y') ?>', // Use messageTop for better compatibility, \n for newline in Excel
                        customize: function ( xlsx ) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            
                            // NG (Pcs) column is at position 18 (0-based index), which is column S in Excel (19th column)
                            // Find all cells in column S (NG column) and format as text
                            $('row c[r^="S"]', sheet).each(function () {
                                // Remove any existing number formatting and set as text
                                $(this).attr('t', 'str');
                                
                                // Get the cell value and remove any thousand separators
                                var cellValue = $(this).text();
                                if (cellValue && cellValue.includes('.')) {
                                    // Remove dots used as thousand separators, keep original number
                                    cellValue = cellValue.replace(/\./g, '');
                                    $(this).text(cellValue);
                                }
                            });
                        }
                    },
                    // END Replacement for Excel Button
                    {extend: 'pdf', title: 'ExampleFile'}, // Keep other buttons as they are

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    },
                ],               

            });
            table.columns().indexes().each( function (idx) {
            $( 'input', table.column( idx ).footer() ).on( 'keyup change', function () {
              table
                .column( idx )
                .search( this.value )
                .draw();
            } );
          } );
        });




    </script>




