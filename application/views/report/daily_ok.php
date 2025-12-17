<title>DPR | DPR Online</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
<style>
    /* Make footer visible with FixedColumns */
    .dataTables_scrollFoot {
        display: block !important;
        overflow: visible !important;
    }
    .dataTables_scrollFootInner {
        display: table !important;
        width: 100% !important;
        table-layout: fixed !important;
    }
    .dataTables_scrollFootInner tfoot th {
        height: auto !important;
        min-height: 40px !important;
        padding: 10px !important;
        display: table-cell !important;
        visibility: visible !important;
        overflow: visible !important;
        box-sizing: border-box !important;
    }
    /* Ensure footer columns match table column widths */
    .dataTables_scrollFootInner tfoot th {
        /* Remove width: auto to allow proper width inheritance */
    }
    /* Hide footer in scrollBody - we only want it in scrollFoot */
    .dataTables_scrollBody tfoot {
        display: none !important;
    }
    /* FixedColumns footer alignment */
    .DTFC_LeftFoot tfoot th,
    .DTFC_RightFoot tfoot th {
        height: auto !important;
        min-height: 40px !important;
        padding: 10px !important;
        display: table-cell !important;
        visibility: visible !important;
        box-sizing: border-box !important;
    }
    
    /* Ensure grand total row has black text */
    .grand-total-row th,
    .grand-total-row th b {
        color: black !important;
    }
    
    /* Specific override for footer cells */
    .dataTables_scrollFoot .grand-total-row th {
        color: black !important;
    }
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="d-flex">
                        <div class="mr-auto p-2">
                            <h5>Report Daily OK</h5>
                        </div>

                    </div>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <?= form_open('c_report/report_daily_ok'); ?>
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-4"> <b>Tanggal Dari (mm/dd/yyyy)</b>
                                    <input type="date" name="tanggal_dari" class="form-control" value="<?= $dari; ?>">
                                </div>
                                <div class="col-sm-4"> <b>Tanggal Sampai (mm/dd/yyyy)</b>
                                    <input type="date" name="tanggal_sampai" class="form-control" value="<?= $sampai; ?>">
                                </div>
                                <div class="col"> <b>Shift</b>
                                    <select name="shift" class="form-control">
                                        <option <?php if ($shift == 'All') {
                                                    echo "selected";
                                                } ?> value='All'>All</option>
                                        <option <?php if ($shift == '1') {
                                                    echo "selected";
                                                } ?> value='1'>1</option>
                                        <option <?php if ($shift == '2') {
                                                    echo "selected";
                                                } ?> value='2'>2</option>
                                        <option <?php if ($shift == '3') {
                                                    echo "selected";
                                                } ?> value='3'>3</option>
                                    </select>
                                </div>
                                <div class="col"> <br /><input type="submit" name="show" class="btn btn-primary" value="Show"></div>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>


                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Part Code</th>
                                    <th>Nama Part</th>
                                    <th>TOTAL OK</th>
                                    <th>TOTAL NG</th>
                                    <th>Total Produksi</th>
                                    <th>Customer</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grand_total_ok = 0;
                                $grand_total_ng = 0;
                                $grand_total_production = 0;
                                foreach ($data_tabel->result_array() as $data) {
                                    $grand_total_ok += $data['total_ok'];
                                    $grand_total_ng += isset($data['total_ng']) ? $data['total_ng'] : 0;
                                    $grand_total_production += isset($data['total_production']) ? $data['total_production'] : 0;

                                    $background = '#02b2c2';

                                    echo '<tr >';
                                    echo '<td style="text-align: center;"><b>' . $data['kode_product'] . '</b></td>';
                                    echo '<td style="text-align: center;"><b>' . $data['nama_product'] . '</b></td>';
                                    echo '<td style="text-align: center;"><b>' . number_format($data['total_ok']) . '</b></td>';
                                    echo '<td style="text-align: center;"><b>' . number_format(isset($data['total_ng']) ? $data['total_ng'] : 0) . '</b></td>';
                                    echo '<td style="text-align: center;"><b>' . number_format(isset($data['total_production']) ? $data['total_production'] : 0) . '</b></td>';
                                    echo '<td style="text-align: center;"><b>' . (isset($data['customers']) ? $data['customers'] : '') . '</b></td>';

                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <!-- Grand Total Row - Will be populated by footerCallback -->
                                <tr class="grand-total-row" style="background-color: #1ab394; font-weight: bold;">
                                    <th style="text-align: center; padding: 10px;"></th>
                                    <th style="text-align: center; padding: 10px; color: black;">Grand Total:</th>
                                    <th style="text-align: center; padding: 10px; color: black;"></th>
                                    <th style="text-align: center; padding: 10px; color: black;"></th>
                                    <th style="text-align: center; padding: 10px; color: black;"></th>
                                    <th style="text-align: center; padding: 10px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>



                    <!-- Modal -->
                    <form action="<?php echo base_url('c_report/report_daily_ok') ?>" method="post">

                        <?php
                        $nama = $this->session->userdata('nama_actor');
                        ?>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Verifikasi Kasie</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Modal Data verif kanit -->


                    <!-- Modal Data verif kasi -->
                    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Data Verifikasi Kasi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php $this->load->view('layout/footer'); ?>
                    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
                    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
                    <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>
                    <!-- Page-Level Scripts -->
                    <script>
                        $(document).ready(function() {
                            // Initialize DataTable with fixed columns
                            // Function to sync footer column widths with header column widths
                            function syncFooterColumnWidths() {
                                // Hide ALL duplicate footers - only keep the one in .dataTables_scrollFoot
                                $('.dataTables_scrollBody tfoot .grand-total-row').hide();
                                $('.DTFC_LeftFoot tfoot .grand-total-row, .DTFC_RightFoot tfoot .grand-total-row').hide();
                                
                                // Ensure main footer (.dataTables_scrollFoot) is visible
                                $('.dataTables_scrollFoot').css({
                                    'display': 'block',
                                    'height': 'auto',
                                    'visibility': 'visible'
                                });
                                
                                // Sync footer column widths with table header column widths
                                var headerCells = $('.dataTables_scrollHead thead th');
                                var footerCells = $('.dataTables_scrollFoot tfoot th').not('.DTFC_LeftFoot th, .DTFC_RightFoot th');
                                
                                headerCells.each(function(index) {
                                    if (footerCells.eq(index).length) {
                                        var headerWidth = $(this).outerWidth();
                                        footerCells.eq(index).css({
                                            'width': headerWidth + 'px',
                                            'min-width': headerWidth + 'px',
                                            'max-width': headerWidth + 'px'
                                        });
                                    }
                                });
                                
                                // Also sync FixedColumns footer cells
                                var leftHeaderCells = $('.DTFC_LeftHead thead th');
                                var leftFooterCells = $('.DTFC_LeftFoot tfoot th');
                                
                                leftHeaderCells.each(function(index) {
                                    if (leftFooterCells.eq(index).length) {
                                        var headerWidth = $(this).outerWidth();
                                        leftFooterCells.eq(index).css({
                                            'width': headerWidth + 'px',
                                            'min-width': headerWidth + 'px',
                                            'max-width': headerWidth + 'px'
                                        });
                                    }
                                });
                                
                                var rightHeaderCells = $('.DTFC_RightHead thead th');
                                var rightFooterCells = $('.DTFC_RightFoot tfoot th');
                                
                                rightHeaderCells.each(function(index) {
                                    if (rightFooterCells.eq(index).length) {
                                        var headerWidth = $(this).outerWidth();
                                        rightFooterCells.eq(index).css({
                                            'width': headerWidth + 'px',
                                            'min-width': headerWidth + 'px',
                                            'max-width': headerWidth + 'px'
                                        });
                                    }
                                });
                                
                                $('.dataTables_scrollFoot tfoot th').not('.DTFC_LeftFoot th, .DTFC_RightFoot th').css({
                                    'height': 'auto',
                                    'min-height': '40px',
                                    'padding': '10px',
                                    'display': 'table-cell',
                                    'visibility': 'visible'
                                });
                                $('.dataTables_scrollFoot tfoot th div').not('.DTFC_LeftFoot th div, .DTFC_RightFoot th div').css({
                                    'height': 'auto',
                                    'overflow': 'visible'
                                });
                            }

                            var table = $('.dataTables-example').DataTable({
                                pageLength: 10,
                                responsive: false,
                                scrollX: true,
                                scrollCollapse: true,
                                paging: true,
                                fixedColumns: {
                                    leftColumns: 2,
                                    rightColumns: 2
                                },
                                initComplete: function(settings, json) {
                                    // Sync column widths after initialization
                                    setTimeout(function() {
                                        syncFooterColumnWidths();
                                    }, 200);
                                },
                                drawCallback: function(settings) {
                                    // This is called every time the table is drawn
                                    var api = new $.fn.dataTable.Api(settings);
                                    
                                    // Calculate grand totals
                                    var grandTotalOK = 0;
                                    var grandTotalNG = 0;
                                    var grandTotalProduction = 0;
                                    
                                    api.rows({search: 'applied'}).every(function(rowIdx, tableLoop, rowLoop) {
                                        var totalOKCell = api.cell(rowIdx, 2).data();
                                        var totalNGCell = api.cell(rowIdx, 3).data();
                                        var totalProductionCell = api.cell(rowIdx, 4).data();
                                        
                                        var totalOKStr = String(totalOKCell).replace(/<[^>]*>/g, '').replace(/,/g, '');
                                        var totalNGStr = String(totalNGCell).replace(/<[^>]*>/g, '').replace(/,/g, '');
                                        var totalProductionStr = String(totalProductionCell).replace(/<[^>]*>/g, '').replace(/,/g, '');
                                        
                                        var totalOK = parseFloat(totalOKStr) || 0;
                                        var totalNG = parseFloat(totalNGStr) || 0;
                                        var totalProduction = parseFloat(totalProductionStr) || 0;
                                        
                                        grandTotalOK += totalOK;
                                        grandTotalNG += totalNG;
                                        grandTotalProduction += totalProduction;
                                    });
                                    
                                    // Format numbers with commas
                                    function number_format(num) {
                                        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    }
                                    
                                    // Find and update all footer cells using DataTables API
                                    var footerCells = api.columns().footer();
                                    
                                    // Update column 2 (Total OK) - index 2 (moved from index 3)
                                    if (footerCells.length > 2) {
                                        $(footerCells[2]).html('<b style="color: black !important;">' + number_format(grandTotalOK) + '</b>');
                                    }
                                    
                                    // Update column 3 (Total NG) - index 3 (moved from index 4)
                                    if (footerCells.length > 3) {
                                        $(footerCells[3]).html('<b style="color: black !important;">' + number_format(grandTotalNG) + '</b>');
                                    }
                                    
                                    // Update column 4 (Total Production) - index 4 (moved from index 5)
                                    if (footerCells.length > 4) {
                                        $(footerCells[4]).html('<b style="color: black !important;">' + number_format(grandTotalProduction) + '</b>');
                                    }
                                    
                                    // Also update via direct DOM manipulation for FixedColumns compatibility
                                    setTimeout(function() {
                                        // Hide ALL duplicate footers - only keep the one in .dataTables_scrollFoot
                                        $('.dataTables_scrollBody tfoot .grand-total-row').hide();
                                        $('.DTFC_LeftFoot tfoot .grand-total-row, .DTFC_RightFoot tfoot .grand-total-row').hide();
                                        
                                        // Update footer cells ONLY in .dataTables_scrollFoot (the main footer)
                                        $('.dataTables_scrollFoot tfoot th').each(function() {
                                            // Skip if this is in a FixedColumns container
                                            if ($(this).closest('.DTFC_LeftFoot, .DTFC_RightFoot').length > 0) {
                                                return;
                                            }
                                            
                                            // Check if this is a Grand Total row
                                            if ($(this).closest('tr').hasClass('grand-total-row')) {
                                                var cellIndex = $(this).index();
                                                
                                                // Update based on column index
                                                if (cellIndex === 2) { // Total OK column (moved from index 3)
                                                    var currentContent = $(this).html();
                                                    // Only update if empty or doesn't contain the formatted number
                                                    if (!currentContent || currentContent.trim() === '' || !currentContent.match(/\d/)) {
                                                        $(this).html('<b style="color: black !important;">' + number_format(grandTotalOK) + '</b>');
                                                    }
                                                } else if (cellIndex === 3) { // Total NG column (moved from index 4)
                                                    var currentContent = $(this).html();
                                                    // Only update if empty or doesn't contain the formatted number
                                                    if (!currentContent || currentContent.trim() === '' || !currentContent.match(/\d/)) {
                                                        $(this).html('<b style="color: black !important;">' + number_format(grandTotalNG) + '</b>');
                                                    }
                                                } else if (cellIndex === 4) { // Total Production column (moved from index 5)
                                                    var currentContent = $(this).html();
                                                    // Only update if empty or doesn't contain the formatted number
                                                    if (!currentContent || currentContent.trim() === '' || !currentContent.match(/\d/)) {
                                                        $(this).html('<b style="color: black !important;">' + number_format(grandTotalProduction) + '</b>');
                                                    }
                                                }
                                            }
                                        });
                                        
                                        // Force style on grand total cells in main footer only
                                        $('.dataTables_scrollFoot tfoot .grand-total-row th').not('.DTFC_LeftFoot th, .DTFC_RightFoot th').css({
                                            'color': 'black'
                                        });
                                        
                                        // Use the sync function instead of the duplicated code
                                        syncFooterColumnWidths();
                                    }, 50);
                                },
                                // footerCallback is not needed as we're using drawCallback instead
                                dom: '<"html5buttons"B>lTfgitp',
                                buttons: [{
                                        extend: 'copy',
                                        footer: true
                                    },
                                    {
                                        extend: 'csv',
                                        filename: 'Report_Daily_OK',
                                        footer: true,
                                        exportOptions: {
                                            format: {
                                                footer: function(data, row, column, node) {
                                                    var $cell = $(node);
                                                    var textContent = $cell.text().trim();
                                                    return textContent || data;
                                                }
                                            }
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        filename: 'Report_Daily_OK',
                                        title: 'Report Daily OK',
                                        footer: true,
                                        exportOptions: {
                                            format: {
                                                footer: function(data, row, column, node) {
                                                    var $cell = $(node);
                                                    // Get clean text content, removing all HTML tags including <b>
                                                    var textContent = $cell.text().trim();
                                                    // Also strip any remaining HTML tags from data parameter
                                                    if (data && typeof data === 'string') {
                                                        textContent = data.replace(/<[^>]*>/g, '').trim();
                                                    }
                                                    return textContent || data;
                                                }
                                            }
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        filename: 'Report_Daily_OK',
                                        title: 'Report Daily OK',
                                        footer: true,
                                        exportOptions: {
                                            format: {
                                                footer: function(data, row, column, node) {
                                                    var $cell = $(node);
                                                    var textContent = $cell.text().trim();
                                                    return textContent || data;
                                                }
                                            }
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        footer: true,
                                        exportOptions: {
                                            format: {
                                                footer: function(data, row, column, node) {
                                                    var $cell = $(node);
                                                    var textContent = $cell.text().trim();
                                                    return textContent || data;
                                                }
                                            }
                                        },
                                        customize: function(win) {
                                            $(win.document.body)
                                                .addClass('white-bg')
                                                .css('font-size', '10px');

                                            $(win.document.body).find('table')
                                                .addClass('compact')
                                                .css('font-size', 'inherit');
                                        }
                                    }
                                ]
                            });

                            // Force initial draw to trigger the drawCallback
                            setTimeout(function() {
                                table.draw();
                            }, 100);
                            
                            // Force footer visibility after table initialization
                            setTimeout(function() {
                                syncFooterColumnWidths();
                                
                                // Add resize handler to maintain alignment
                                $(window).on('resize', function() {
                                    setTimeout(function() {
                                        syncFooterColumnWidths();
                                    }, 100);
                                });
                            }, 100);
                        });
                    </script>