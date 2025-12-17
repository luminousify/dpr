<title>DPR | Dashboard</title>
<?php $this->load->view('layout/sidebar'); ?>
<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
<style>
    /* CSS Variables for Theming */
    :root {
        --primary-color: #1ab394;
        --success-color: #1c84c6;
        --danger-color: #ed5565;
        --warning-color: #f8ac59;
        --info-color: #23c6c8;
        --text-primary: #2c3e50;
        --text-secondary: #7f8c8d;
        --bg-light: #f8f9fa;
        --border-color: #e7eaec;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 8px rgba(0,0,0,0.12);
        --shadow-lg: 0 8px 16px rgba(0,0,0,0.16);
        --spacing-xs: 4px;
        --spacing-sm: 8px;
        --spacing-md: 16px;
        --spacing-lg: 24px;
        --spacing-xl: 32px;
        --border-radius: 8px;
        --transition: all 0.3s ease;
    }

    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: auto;
        height: auto;
        margin: 0 auto;
    }
    tr {background-color: white}
    
    /* Make dashboard scrollable */
    body {
        overflow: auto !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .wrapper.wrapper-content {
        overflow-y: auto !important;
        min-height: calc(100vh - 120px);
        display: flex;
        flex-direction: column;
        padding: var(--spacing-sm);
    }
    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-md);
        padding: var(--spacing-md);
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }
    .dashboard-header h2 {
        margin: 0;
        font-weight: 700;
        color: var(--text-primary);
    }
    .dashboard-header small {
        display: block;
        color: var(--text-secondary);
    }
    .download-all-btn {
        min-width: 190px;
        font-weight: 600;
    }
    
    /* Enhanced Filter Card */
    .filter-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        margin-bottom: var(--spacing-lg);
        transition: var(--transition);
    }
    .filter-card:hover {
        box-shadow: var(--shadow-lg);
    }
    .filter-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: var(--spacing-md);
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        font-weight: 600;
    }
    .filter-card .card-body {
        padding: var(--spacing-lg);
    }
    .filter-card .form-control {
        border-radius: 6px;
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }
    .filter-card .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(26, 179, 148, 0.25);
    }
    .filter-card .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 6px;
        padding: 8px 20px;
        font-weight: 600;
        transition: var(--transition);
    }
    .filter-card .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .filter-card .btn-secondary {
        border-radius: 6px;
        padding: 8px 20px;
        transition: var(--transition);
    }
    
    /* Enhanced KPI Cards */
    .kpi-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: var(--transition);
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .kpi-card-header {
        padding: var(--spacing-md);
        color: white;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }
    .kpi-card-header i {
        font-size: 18px;
    }
    .kpi-card-body {
        padding: var(--spacing-lg);
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: white;
    }
    .kpi-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.2;
    }
    .kpi-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-top: var(--spacing-xs);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* KPI Card Color Variants */
    .kpi-card.ok .kpi-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .kpi-card.ng .kpi-card-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .kpi-card.lt .kpi-card-header {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        color: var(--text-primary);
    }
    .kpi-card.nett .kpi-card-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .kpi-card.gross .kpi-card-header {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    /* Chart Card Enhancements */
    .chart-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        margin-bottom: var(--spacing-lg);
        overflow: hidden;
    }
    .chart-card-header {
        padding: var(--spacing-md) var(--spacing-lg);
        background: var(--bg-light);
        border-bottom: 2px solid var(--border-color);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--text-primary);
    }
    .chart-card-body {
        padding: var(--spacing-lg);
        position: relative;
    }
    .chart-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: var(--text-secondary);
    }
    .chart-loading i {
        font-size: 2rem;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Chart Grid Layout - 2x2 */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: var(--spacing-sm);
        width: 100%;
        min-height: 600px;
        margin-bottom: var(--spacing-md);
    }
    
    /* Enhanced Chart Card Styling */
    .chart-card-grid {
        height: 100%;
        min-height: 250px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin: 0;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }
    
    .chart-card-grid:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }
    
    .chart-card-grid .chart-card-header {
        padding: 14px 18px;
        font-size: 0.95rem;
        font-weight: 600;
        flex-shrink: 0;
        margin: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .chart-card-grid .chart-card-header i {
        font-size: 1.1rem;
        opacity: 0.95;
    }
    
    .chart-card-grid .chart-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 12px !important;
        margin: 0 !important;
        min-height: 0;
        overflow: hidden;
        background: #fafbfc;
    }
    
    .chart-card-grid .chart-card-body .chart-container {
        flex: 1;
        min-height: 0;
        width: 100%;
        height: 100%;
        position: relative;
        margin: 0;
        padding: 0;
        background: #ffffff;
        border-radius: 8px;
    }
    
    #container2, #container_productivity, #container_ppm {
        width: 100% !important;
        height: 100% !important;
        min-height: 0;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Enhanced Highcharts Styling */
    .chart-card-grid .highcharts-container {
        width: 100% !important;
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
    }
    
    .chart-card-grid .highcharts-container svg {
        width: 100% !important;
        height: 100% !important;
    }
    
    .chart-card-grid .highcharts-root {
        width: 100% !important;
        height: 100% !important;
    }
    
    /* Enhanced Chart Title */
    .chart-card-grid .highcharts-title {
        font-size: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Enhanced Plot Area */
    .chart-card-grid .highcharts-plot-box {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Hide Legend */
    .chart-card-grid .highcharts-legend {
        display: none !important;
    }
    
    /* Enhanced Axis Styling */
    .chart-card-grid .highcharts-axis {
        margin: 0 !important;
    }
    
    .chart-card-grid .highcharts-axis-labels {
        font-size: 10px !important;
        font-weight: 400 !important;
        fill: #a0aec0 !important;
    }
    
    .chart-card-grid .highcharts-axis-line,
    .chart-card-grid .highcharts-tick {
        stroke: #e2e8f0 !important;
        stroke-width: 1 !important;
    }
    
    .chart-card-grid .highcharts-grid-line {
        stroke: #f1f5f9 !important;
        stroke-width: 1 !important;
        stroke-dasharray: 2, 4;
    }
    
    /* Enhanced Tooltip */
    .chart-card-grid .highcharts-tooltip {
        background: rgba(255, 255, 255, 0.98) !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        padding: 10px !important;
        font-size: 12px !important;
    }
    
    .chart-card-grid .highcharts-tooltip-box {
        fill: rgba(255, 255, 255, 0.98) !important;
        stroke: #e2e8f0 !important;
        stroke-width: 1 !important;
    }
    
    /* Enhanced Data Labels */
    .chart-card-grid .highcharts-data-label {
        font-size: 11px !important;
        font-weight: 600 !important;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8) !important;
    }
    
    /* Enhanced Export Button */
    .chart-card-grid .highcharts-button {
        fill: #667eea !important;
    }
    
    .chart-card-grid .highcharts-button:hover {
        fill: #5568d3 !important;
    }
    
    /* Improved Spacing */
    .ibox {
        margin-bottom: var(--spacing-md);
    }
    .card {
        margin-bottom: var(--spacing-md);
    }
    hr {
        margin: var(--spacing-lg) 0;
        border: none;
        border-top: 1px solid var(--border-color);
    }
    
    /* Make ibox-content scrollable if needed but keep wrapper non-scrollable */
    .ibox-content {
        max-height: calc(100vh - 300px);
        overflow-y: auto;
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .kpi-value {
            font-size: 2rem;
        }
        .charts-grid {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            height: calc(100vh - 200px);
            min-height: 600px;
        }
        .chart-card-grid {
            min-height: 280px;
        }
        #container2, #container_productivity, #container_ppm {
            min-height: 250px;
        }
    }
    @media (max-width: 768px) {
        .wrapper.wrapper-content {
            padding: var(--spacing-sm);
        }
        .kpi-card {
            margin-bottom: var(--spacing-md);
        }
        .kpi-value {
            font-size: 1.75rem;
        }
        .filter-card .card-body {
            padding: var(--spacing-md);
        }
        .charts-grid {
            grid-template-columns: 1fr;
            grid-template-rows: repeat(4, auto);
            height: auto;
            min-height: auto;
        }
        .chart-card-grid {
            min-height: 300px;
        }
        .chart-card-grid .chart-card-body .chart-container {
            min-height: 250px;
        }
        #container2, #container_productivity, #container_ppm {
            min-height: 250px;
        }
    }
    @media (max-width: 576px) {
        .kpi-value {
            font-size: 1.5rem;
        }
        .kpi-card-header {
            font-size: 12px;
        }
    }
    
    /* Improved Typography */
    h1, h2, h3, h4, h5, h6 {
        color: var(--text-primary);
        font-weight: 600;
    }
    
    /* Smooth Scrollbar */
    .ibox-content::-webkit-scrollbar {
        width: 8px;
    }
    .ibox-content::-webkit-scrollbar-track {
        background: var(--bg-light);
    }
    .ibox-content::-webkit-scrollbar-thumb {
        background: var(--text-secondary);
        border-radius: 4px;
    }
    .ibox-content::-webkit-scrollbar-thumb:hover {
        background: var(--text-primary);
    }
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
    <?= form_open('c_new/home', array('style' => 'display: none;')); ?>  
    <input type="hidden" name="tanggal" value="<?= $tanggal; ?>">
    <input type="hidden" name="shift" value="<?= $shift; ?>">
<?= form_close(); ?>
 <div class="wrapper wrapper-content">
        <div class="dashboard-header">
            <div>
                <h2>Production Monitoring Dashboard</h2>
                <small>Data tahun <?= isset($tahun) ? $tahun : date('Y'); ?></small>
            </div>
            <button type="button" id="downloadAllReports" class="btn btn-success download-all-btn" onclick="downloadAllReportsZip()">
                <i class="fa fa-download"></i> Download All Reports
            </button>
        </div>
        <!-- Charts Grid - 3x3 Layout -->
        <div class="charts-grid">
            <!-- Chart 1: Daftar Operasional Mesin -->
            <div class="chart-card chart-card-grid">
                <div class="chart-card-header">
                    <i class="fa fa-cogs"></i> Daftar Operasional Mesin
                </div>
                <div class="chart-card-body">
                    <div id="container2" class="chart-container"></div>
                    <div class="chart-loading" id="loading1" style="display: none;">
                        <i class="fa fa-spinner"></i>
                        <p>Loading chart...</p>
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
                </div>
            </div>
            
            <!-- Chart 2: Production Productivity -->
            <?php if (isset($productivity_annual) && $productivity_annual && $productivity_annual->num_rows() > 0) { ?>
            <div class="chart-card chart-card-grid">
                <div class="chart-card-header">
                    <i class="fa fa-line-chart"></i> Production Productivity <?= isset($tahun) ? $tahun : date('Y'); ?>
                </div>
                <div class="chart-card-body">
                    <div id="container_productivity" class="chart-container"></div>
                    <div class="chart-loading" id="loading2" style="display: none;">
                        <i class="fa fa-spinner"></i>
                        <p>Loading chart...</p>
                    </div>
                    <table class="table table-bordered" id="datatable_productivity" style="display: none;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Net Prod Aktual (%)</th>
                                <th>Gross Prod Aktual (%)</th>
                                <th>Net Prod Target (%)</th>
                                <th>Gross Prod Target (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $namaBulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                            foreach($productivity_annual->result_array() as $data) {
                                for($i=1;$i<=12;$i++) {
                                    echo '<tr>';
                                    echo '<th>'.$namaBulan[$i].'</th>';
                                    echo '<td>'.round($data['persen_nett'.$i] ?? 0, 1).'</td>';
                                    echo '<td>'.round($data['persen_gross'.$i] ?? 0, 1).'</td>';
                                    echo '<td>'.($data['target_nett'] ?? 0).'</td>';
                                    echo '<td>'.($data['target_gross'] ?? 0).'</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } else { ?>
            <!-- Placeholder Chart 2 -->
            <div class="chart-card chart-card-grid">
                <div class="chart-card-header">
                    <i class="fa fa-chart-area"></i> Chart 2
                </div>
                <div class="chart-card-body">
                    <div class="chart-container" style="display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                        <p>No data available</p>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <!-- Chart 3: Production Qty & PPM -->
            <?php if (isset($ppm_grafik) && $ppm_grafik && $ppm_grafik->num_rows() > 0) { ?>
            <div class="chart-card chart-card-grid">
                <div class="chart-card-header">
                    <i class="fa fa-bar-chart"></i> Production Qty & PPM <?= isset($tahun) ? $tahun : date('Y'); ?>
                </div>
                <div class="chart-card-body">
                    <div id="container_ppm" class="chart-container"></div>
                    <div class="chart-loading" id="loading3" style="display: none;">
                        <i class="fa fa-spinner"></i>
                        <p>Loading chart...</p>
                    </div>
                    <?php 
                    $target_ppm = 0;
                    if (isset($ppm_fcost_target) && $ppm_fcost_target && $ppm_fcost_target->num_rows() > 0) {
                        foreach ($ppm_fcost_target->result_array() as $target) {
                            $target_ppm = $target['ppm_target'] ?? 0;
                            break;
                        }
                    }
                    ?>
                    <table class="table table-bordered" id="datatable_ppm" style="display: none;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Total Production</th>
                                <th>OK</th>
                                <th>NG</th>
                                <th>PPM</th>
                                <th>Target PPM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $namaBulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                            foreach($ppm_grafik->result_array() as $data) {
                                for($i=1;$i<=12;$i++) {
                                    echo '<tr>';
                                    echo '<th>'.$namaBulan[$i].'</th>';
                                    echo '<td>'.($data['total_prod'.$i] ?? 0).'</td>';
                                    echo '<td>'.($data['ok'.$i] ?? 0).'</td>';
                                    echo '<td>'.($data['ng'.$i] ?? 0).'</td>';
                                    echo '<td>'.($data['ppm'.$i] ?? 0).'</td>';
                                    echo '<td>'.$target_ppm.'</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } else { ?>
            <!-- Placeholder Chart 3 -->
            <div class="chart-card chart-card-grid">
                <div class="chart-card-header">
                    <i class="fa fa-chart-pie"></i> Chart 3
                </div>
                <div class="chart-card-body">
                    <div class="chart-container" style="display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                        <p>No data available</p>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <!-- Chart 4: Placeholder for 2x2 grid -->
            <div class="chart-card chart-card-grid">
                <div class="chart-card-header">
                    <i class="fa fa-chart-line"></i> Chart 4
                </div>
                <div class="chart-card-body">
                    <div class="chart-container" style="display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                        <p>Chart 4 - Ready for data</p>
                    </div>
                </div>
            </div>
        </div>
                
                <!-- KPI Cards Section - Moved to Bottom -->
                <div class="row mt-2">
                <?php $id = -1; $no = 0 ; foreach($data_tabelHeader->result_array() as $data)
                {  $no++; $id++; ?>
                            <div class="col-xl col-lg-4 col-md-6 col-sm-6 mb-3">
                                <div class="kpi-card ok">
                                    <div class="kpi-card-header">
                                        <i class="fa fa-check-circle"></i>
                                        <span>OK</span>
                                    </div>
                                    <div class="kpi-card-body">
                                        <h1 class="kpi-value"><?= number_format($data['ok']); ?></h1>
                                        <p class="kpi-label">Good Production</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-lg-4 col-md-6 col-sm-6 mb-3">
                                <div class="kpi-card ng">
                                    <div class="kpi-card-header">
                                        <i class="fa fa-times-circle"></i>
                                        <span>NG</span>
                                    </div>
                                    <div class="kpi-card-body">
                                        <h1 class="kpi-value"><?= number_format($data['ng']); ?></h1>
                                        <p class="kpi-label">Not Good</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-lg-4 col-md-6 col-sm-6 mb-3">
                                <div class="kpi-card lt">
                                    <div class="kpi-card-header">
                                        <i class="fa fa-clock"></i>
                                        <span>LT</span>
                                    </div>
                                    <div class="kpi-card-body">
                                        <h1 class="kpi-value"><?= number_format($data['lt']); ?> Jam</h1>
                                        <p class="kpi-label">Loss Time</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-lg-4 col-md-6 col-sm-6 mb-3">
                                <div class="kpi-card nett">
                                    <div class="kpi-card-header">
                                        <i class="fa fa-chart-line"></i>
                                        <span>Nett</span>
                                    </div>
                                    <div class="kpi-card-body">
                                        <h1 class="kpi-value"><?= number_format($data['persen_Nett'],0); ?>%</h1>
                                        <p class="kpi-label">Net Production</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-lg-4 col-md-6 col-sm-6 mb-3">
                                <div class="kpi-card gross">
                                    <div class="kpi-card-header">
                                        <i class="fa fa-chart-bar"></i>
                                        <span>Gross</span>
                                    </div>
                                    <div class="kpi-card-body">
                                        <h1 class="kpi-value"><?= number_format($data['persen_Gross'],0); ?>%</h1>
                                        <p class="kpi-label">Gross Production</p>
                                    </div>
                                </div>
                            </div>
                <?php } ?>
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
        // Date validation and user experience enhancements
        function validateDate(input) {
            const selectedDate = new Date(input.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Check if date is in the future
            if (selectedDate > today) {
                alert('Tanggal tidak boleh melebihi hari ini!');
                input.value = input.defaultValue || '<?= date("Y-m-d"); ?>';
                return false;
            }
            
            // Check if date is too old (optional: limit to 1 year ago)
            const oneYearAgo = new Date();
            oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1);
            if (selectedDate < oneYearAgo) {
                alert('Tanggal tidak boleh lebih dari 1 tahun yang lalu!');
                input.value = input.defaultValue || '<?= date("Y-m-d"); ?>';
                return false;
            }
            
            // Update the display label
            updateDateDisplay(input.value);
            return true;
        }
        
        function updateDateDisplay(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            const formattedDate = date.toLocaleDateString('id-ID', options);
            
            // Update the small text showing current selected date
            const label = document.querySelector('label[for="tanggal"] small');
            if (label) {
                label.textContent = '(' + formattedDate + ')';
            }
        }
        
        function resetToDateToday() {
            const today = '<?= date("Y-m-d"); ?>';
            const dateInput = document.getElementById('tanggal');
            
            if (dateInput) {
                dateInput.value = today;
                updateDateDisplay(today);
                
                // Show visual feedback
                dateInput.classList.add('is-valid');
                setTimeout(() => {
                    dateInput.classList.remove('is-valid');
                }, 2000);
            }
        }
        
        // Enhanced form submission with loading state
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action*="c_new/home"]');
            const showButton = document.getElementById('showButton');
            
            if (form && showButton) {
                form.addEventListener('submit', function(e) {
                    const dateInput = document.getElementById('tanggal');
                    
                    // Validate date before submission
                    if (!validateDate(dateInput)) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Show loading state
                    showButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Loading...';
                    showButton.disabled = true;
                    
                    // Re-enable button after 3 seconds (fallback)
                    setTimeout(() => {
                        showButton.innerHTML = '<i class="fa fa-search"></i> Show';
                        showButton.disabled = false;
                    }, 3000);
                });
            }
            
            // Initialize date display on page load
            const dateInput = document.getElementById('tanggal');
            if (dateInput && dateInput.value) {
                updateDateDisplay(dateInput.value);
            }
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl+Enter or Cmd+Enter to submit form
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    e.preventDefault();
                    form.submit();
                }
                // Ctrl+T or Cmd+T to reset to today
                if ((e.ctrlKey || e.metaKey) && e.key === 't') {
                    e.preventDefault();
                    resetToDateToday();
                }
            });
        });
    </script>

<script>
    // Helper to submit hidden POST for Excel downloads
    function triggerExcelDownload(url, year) {
        const form = $('<form>', {
            method: 'POST',
            action: url,
            target: '_blank'
        });
        if (year) {
            form.append($('<input>', { type: 'hidden', name: 'year', value: year }));
        }
        $('body').append(form);
        form.submit();
        setTimeout(() => form.remove(), 1500);
    }

    // Download all required Excel reports sequentially
    // Single ZIP download to avoid popups
    function downloadAllReportsZip() {
        const $btn = $('#downloadAllReports');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Preparing...');

        const year = (new Date()).getFullYear();
        const form = $('<form>', {
            method: 'POST',
            action: '<?= base_url('c_report/export_all_reports_zip'); ?>',
            target: '_blank'
        });
        form.append($('<input>', { type: 'hidden', name: 'year', value: year }));
        $('body').append(form);
        form.submit();

        setTimeout(() => {
            form.remove();
            $btn.prop('disabled', false).html(originalText);
        }, 4000);
    }
</script>

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
            defaultSeriesType: 'column',
            height: null,
            reflow: true,
            spacingTop: 10,
            spacingRight: 15,
            spacingBottom: 10,
            spacingLeft: 15,
            marginTop: 10,
            marginRight: 15,
            marginBottom: 50,
            marginLeft: 60,
            backgroundColor: 'transparent',
            style: {
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            animation: {
                duration: 1000,
                easing: 'easeOutCubic'
            }
         },
         title: {
            text: null
         },
         xAxis: {
            lineColor: '#e2e8f0',
            lineWidth: 1,
            tickColor: 'transparent',
            labels: {
                style: {
                    color: '#a0aec0',
                    fontSize: '10px',
                    fontWeight: '400',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                }
            },
            gridLineColor: 'transparent',
            gridLineWidth: 0
         },
         yAxis: {
            title: {
               text: null
            },
            lineColor: '#e2e8f0',
            lineWidth: 1,
            tickColor: 'transparent',
            labels: {
                style: {
                    color: '#a0aec0',
                    fontSize: '10px',
                    fontWeight: '400',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                }
            },
            gridLineColor: '#f1f5f9',
            gridLineWidth: 1,
            gridLineDashStyle: 'Dash'
         },
         tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.98)',
            borderColor: '#e2e8f0',
            borderRadius: 6,
            borderWidth: 1,
            shadow: {
                color: 'rgba(0, 0, 0, 0.1)',
                offsetX: 0,
                offsetY: 2,
                opacity: 0.3,
                width: 2
            },
            style: {
                fontSize: '11px',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
                color: '#2d3748'
            },
            formatter: function() {
               return '<span style="color: ' + this.color + '; font-weight: 600;">' + this.series.name + '</span>: <b>' + this.y + '</b>';
            },
            useHTML: true,
            padding: 8
         },
         colors: ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a'],
         plotOptions: {
            column: {
                borderRadius: 4,
                borderWidth: 0,
                pointPadding: 0.2,
                groupPadding: 0.15,
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '9px',
                        fontWeight: '600',
                        textOutline: 'none',
                        color: '#2d3748',
                        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                    },
                    formatter: function() {
                        return this.y;
                    }
                },
                enableMouseTracking: true,
                states: {
                    hover: {
                        brightness: -0.15,
                        enabled: true
                    }
                },
                animation: {
                    duration: 800
                }
            },
            line: {
                lineWidth: 2.5,
                marker: {
                    radius: 4,
                    lineWidth: 2,
                    lineColor: '#ffffff',
                    fillColor: '#ffffff',
                    enabled: false
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '9px',
                        fontWeight: '600',
                        textOutline: 'none',
                        color: '#2d3748',
                        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                    },
                    formatter: function() {
                        return this.y;
                    }
                },
                enableMouseTracking: true,
                states: {
                    hover: {
                        lineWidth: 3.5
                    }
                },
                animation: {
                    duration: 800
                }
            }
        },
        legend: {
            enabled: false
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
        sourceHeight: 280,
        scale: 2,
        chartOptions: {
            chart: {
                width: 800,
                height: 280
            }
        }
    }
      };
   
                     
   Highcharts.visualize(table, options);
   
   // Reflow chart after a short delay to ensure proper sizing
   setTimeout(function() {
       var charts = Highcharts.charts;
       if (charts && charts.length > 0) {
           var chart = charts[charts.length - 1];
           if (chart && chart.renderTo && chart.renderTo.id === 'container2') {
               chart.reflow();
           }
       }
   }, 200);
});


// Annual Productivity Chart
$(document).ready(function() {         
   var table = document.getElementById('datatable_productivity');
   if (!table) return; // Exit if table doesn't exist
   
   var options = {
         chart: {
            renderTo: 'container_productivity',
            defaultSeriesType: 'column',
            height: null,
            reflow: true,
            spacingTop: 10,
            spacingRight: 15,
            spacingBottom: 10,
            spacingLeft: 15,
            marginTop: 10,
            marginRight: 15,
            marginBottom: 50,
            marginLeft: 60,
            backgroundColor: 'transparent',
            style: {
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            animation: {
                duration: 1000,
                easing: 'easeOutCubic'
            }
         },
         title: {
            text: null
         },
         xAxis: {
            lineColor: '#e2e8f0',
            lineWidth: 1,
            tickColor: 'transparent',
            labels: {
                style: {
                    color: '#a0aec0',
                    fontSize: '10px',
                    fontWeight: '400',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                }
            },
            gridLineColor: 'transparent',
            gridLineWidth: 0
         },
         yAxis: {
            title: {
               text: null
            },
            lineColor: '#e2e8f0',
            lineWidth: 1,
            tickColor: 'transparent',
            labels: {
                style: {
                    color: '#a0aec0',
                    fontSize: '10px',
                    fontWeight: '400',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                },
                format: '{value}%'
            },
            gridLineColor: '#f1f5f9',
            gridLineWidth: 1,
            gridLineDashStyle: 'Dash'
         },
         tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.98)',
            borderColor: '#e2e8f0',
            borderRadius: 6,
            borderWidth: 1,
            shadow: {
                color: 'rgba(0, 0, 0, 0.1)',
                offsetX: 0,
                offsetY: 2,
                opacity: 0.3,
                width: 2
            },
            style: {
                fontSize: '11px',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
                color: '#2d3748'
            },
            formatter: function() {
               return '<span style="color: ' + this.color + '; font-weight: 600;">' + this.series.name + '</span>: <b>' + this.y + '%</b>';
            },
            useHTML: true,
            padding: 8
         },
         colors: ['#667eea', '#f093fb', '#4facfe', '#43e97b'],
         plotOptions: {
            column: {
                borderRadius: 4,
                borderWidth: 0,
                pointPadding: 0.2,
                groupPadding: 0.15,
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '9px',
                        fontWeight: '600',
                        textOutline: 'none',
                        color: '#2d3748',
                        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                    },
                    formatter: function() {
                        return this.y;
                    }
                },
                enableMouseTracking: true,
                states: {
                    hover: {
                        brightness: -0.15,
                        enabled: true
                    }
                },
                animation: {
                    duration: 800
                }
            },
            line: {
                lineWidth: 3,
                marker: {
                    radius: 5,
                    lineWidth: 2,
                    lineColor: '#ffffff',
                    fillColor: '#ffffff'
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '10px',
                        fontWeight: '600',
                        textOutline: '1px contrast',
                        color: '#2d3748',
                        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                    },
                    formatter: function() {
                        return this.y + '%';
                    }
                },
                enableMouseTracking: true,
                states: {
                    hover: {
                        lineWidth: 4
                    }
                },
                animation: {
                    duration: 1000
                }
            }
         },
        legend: {
            enabled: false
        },
         exporting: {
            enabled: true,
            fallbackToExportServer: false,
            buttons: {
                contextButton: {
                    menuItems: ['viewFullscreen', 'separator', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                }
            },
            sourceWidth: 600,
            sourceHeight: 400,
            scale: 2,
            chartOptions: {
                chart: {
                    width: 600,
                    height: 400
                }
            }
         }
   };
   
   Highcharts.visualize(table, options);
   
   // Reflow chart after a short delay to ensure proper sizing
   setTimeout(function() {
       var charts = Highcharts.charts;
       if (charts && charts.length > 0) {
           var chart = charts[charts.length - 1];
           if (chart && chart.renderTo && chart.renderTo.id === 'container_productivity') {
               chart.reflow();
           }
       }
   }, 200);
});

// Annual PPM Chart
$(document).ready(function() {         
   var table = document.getElementById('datatable_ppm');
   if (!table) return; // Exit if table doesn't exist
   
   var options = {
         chart: {
            renderTo: 'container_ppm',
            defaultSeriesType: 'column',
            height: null,
            reflow: true,
            spacingTop: 10,
            spacingRight: 15,
            spacingBottom: 10,
            spacingLeft: 15,
            marginTop: 10,
            marginRight: 15,
            marginBottom: 50,
            marginLeft: 60,
            backgroundColor: 'transparent',
            style: {
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            animation: {
                duration: 1000,
                easing: 'easeOutCubic'
            }
         },
         title: {
            text: null
         },
         xAxis: {
            lineColor: '#e2e8f0',
            lineWidth: 1,
            tickColor: 'transparent',
            labels: {
                style: {
                    color: '#a0aec0',
                    fontSize: '10px',
                    fontWeight: '400',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                }
            },
            gridLineColor: 'transparent',
            gridLineWidth: 0
         },
         yAxis: [{
            title: {
               text: null
            },
            lineColor: '#e2e8f0',
            lineWidth: 1,
            tickColor: 'transparent',
            labels: {
                style: {
                    color: '#a0aec0',
                    fontSize: '10px',
                    fontWeight: '400',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                }
            },
            gridLineColor: '#f1f5f9',
            gridLineWidth: 1,
            gridLineDashStyle: 'Dash'
         }, {
            title: {
               text: null
            },
            opposite: true,
            lineColor: '#e2e8f0',
            lineWidth: 1,
            tickColor: 'transparent',
            labels: {
                style: {
                    color: '#a0aec0',
                    fontSize: '10px',
                    fontWeight: '400',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                }
            },
            gridLineColor: '#f1f5f9',
            gridLineWidth: 1,
            gridLineDashStyle: 'Dash'
         }],
         tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.98)',
            borderColor: '#e2e8f0',
            borderRadius: 6,
            borderWidth: 1,
            shadow: {
                color: 'rgba(0, 0, 0, 0.1)',
                offsetX: 0,
                offsetY: 2,
                opacity: 0.3,
                width: 2
            },
            style: {
                fontSize: '11px',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
                color: '#2d3748'
            },
            formatter: function() {
               return '<span style="color: ' + this.color + '; font-weight: 600;">' + this.series.name + '</span>: <b>' + this.y + '</b>';
            },
            useHTML: true,
            padding: 8
         },
         colors: ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a'],
         plotOptions: {
            column: {
                borderRadius: 4,
                borderWidth: 0,
                pointPadding: 0.2,
                groupPadding: 0.15,
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '9px',
                        fontWeight: '600',
                        textOutline: 'none',
                        color: '#2d3748',
                        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                    },
                    formatter: function() {
                        return this.y;
                    }
                },
                enableMouseTracking: true,
                states: {
                    hover: {
                        brightness: -0.15,
                        enabled: true
                    }
                },
                animation: {
                    duration: 800
                }
            },
            line: {
                lineWidth: 2.5,
                marker: {
                    radius: 4,
                    lineWidth: 2,
                    lineColor: '#ffffff',
                    fillColor: '#ffffff',
                    enabled: false
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '9px',
                        fontWeight: '600',
                        textOutline: 'none',
                        color: '#2d3748',
                        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                    },
                    formatter: function() {
                        return this.y;
                    }
                },
                enableMouseTracking: true,
                states: {
                    hover: {
                        lineWidth: 3.5
                    }
                },
                animation: {
                    duration: 800
                }
            }
         },
        legend: {
            enabled: false
        },
         exporting: {
            enabled: true,
            fallbackToExportServer: false,
            buttons: {
                contextButton: {
                    menuItems: ['viewFullscreen', 'separator', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                }
            },
            sourceWidth: 600,
            sourceHeight: 400,
            scale: 2,
            chartOptions: {
                chart: {
                    width: 600,
                    height: 400
                }
            }
         }
   };
   
   Highcharts.visualize(table, options);
   
   // Reflow chart after a short delay to ensure proper sizing
   setTimeout(function() {
       var charts = Highcharts.charts;
       if (charts && charts.length > 0) {
           var chart = charts[charts.length - 1];
           if (chart && chart.renderTo && chart.renderTo.id === 'container_ppm') {
               chart.reflow();
           }
       }
   }, 200);
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





</body>
</html>
