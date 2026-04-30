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
    
    #container2, #container_productivity, #container_ppm, #container_quality {
        width: 100% !important;
        height: 100% !important;
        min-height: 0;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Minimal Highcharts overrides - let the API handle styling */
    .chart-card-grid .highcharts-container {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
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
        #container2, #container_productivity, #container_ppm, #container_quality {
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
        #container2, #container_productivity, #container_ppm, #container_quality {
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
            
            <!-- Chart 4: Quality Rate (OK vs NG) Donut -->
            <div class="chart-card chart-card-grid">
                <div class="chart-card-header">
                    <i class="fa fa-pie-chart"></i> Quality Rate
                </div>
                <div class="chart-card-body">
                    <div id="container_quality" class="chart-container"></div>
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
    // ============================================================
    // Global Highcharts Configuration (Best Practices)
    // ============================================================
    Highcharts.setOptions({
        colors: ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#fcb69f'],
        credits: { enabled: false },
        chart: {
            style: {
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            animation: { duration: 800, easing: 'easeOutCubic' },
            backgroundColor: 'transparent',
            spacing: [10, 15, 10, 15]
        },
        title: { text: null },
        xAxis: {
            lineColor: '#e2e8f0',
            lineWidth: 1,
            tickColor: 'transparent',
            labels: {
                style: { color: '#718096', fontSize: '10px', fontWeight: '400' }
            },
            gridLineWidth: 0
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.98)',
            borderColor: '#e2e8f0',
            borderRadius: 8,
            borderWidth: 1,
            shadow: true,
            style: { fontSize: '11px', color: '#2d3748' },
            useHTML: true,
            padding: 10
        },
        plotOptions: {
            column: {
                borderRadius: 4,
                borderWidth: 0,
                pointPadding: 0.15,
                groupPadding: 0.2,
                states: { hover: { brightness: -0.1 } }
            },
            line: {
                lineWidth: 2.5,
                marker: { radius: 4, symbol: 'circle', lineWidth: 2, lineColor: '#fff', fillColor: '#fff' },
                states: { hover: { lineWidth: 3.5 } }
            }
        },
        exporting: {
            enabled: true,
            fallbackToExportServer: false,
            buttons: {
                contextButton: {
                    menuItems: ['viewFullscreen', 'separator', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                }
            }
        }
    });

    // Helper: parse hidden table into Highcharts categories + series
    Highcharts.visualize = function(table, options) {
        options.xAxis.categories = [];
        $('tbody th', table).each(function() {
            options.xAxis.categories.push(this.innerHTML);
        });
        options.series = [];
        $('tr', table).each(function(i) {
            var tr = this;
            $('th, td', tr).each(function(j) {
                if (j > 0) {
                    if (i === 0) {
                        options.series[j - 1] = { name: this.innerHTML, data: [] };
                    } else {
                        options.series[j - 1].data.push(parseFloat(this.innerHTML));
                    }
                }
            });
        });
        return new Highcharts.Chart(options);
    };

    // Common legend config
    var bottomLegend = {
        enabled: true,
        align: 'center',
        verticalAlign: 'bottom',
        layout: 'horizontal',
        itemStyle: { color: '#4a5568', fontWeight: '500', fontSize: '10px' },
        itemHoverStyle: { color: '#2d3748' },
        symbolRadius: 3
    };

    // Common export source sizing
    var exportOpts = { sourceWidth: 800, sourceHeight: 400, scale: 2 };

    // ============================================================
    // Chart 1: Daftar Operasional Mesin
    // ============================================================
    $(document).ready(function() {
        var table = document.getElementById('datatable1');
        if (!table || $('tbody tr', table).length === 0) {
            document.getElementById('container2').innerHTML =
                '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#a0aec0;font-size:13px;">No machine data for selected date</div>';
            return;
        }
        Highcharts.visualize(table, {
            chart: { renderTo: 'container2', reflow: true },
            xAxis: { categories: [] },
            yAxis: {
                title: { text: 'Jumlah', style: { color: '#718096', fontSize: '10px' } },
                gridLineColor: '#f1f5f9',
                gridLineDashStyle: 'Dash'
            },
            tooltip: {
                shared: true,
                crosshairs: true,
                formatter: function() {
                    var s = '<b>' + this.x + '</b>';
                    this.points.forEach(function(p) {
                        s += '<br/>' + p.series.name + ': <b>' + p.y + '</b>';
                    });
                    return s;
                }
            },
            plotOptions: {
                column: { dataLabels: { enabled: false } }
            },
            legend: bottomLegend,
            exporting: exportOpts
        });
    });

    // ============================================================
    // Chart 2: Production Productivity (Grouped Bar + Target Lines)
    // ============================================================
    $(document).ready(function() {
        var table = document.getElementById('datatable_productivity');
        if (!table) return;

        // Parse data directly for full control
        var categories = [];
        var nettData = [], grossData = [], nettTarget = null, grossTarget = null;

        $('tbody tr', table).each(function() {
            var $cells = $('td', this);
            if ($cells.length < 4) return;
            categories.push($('th', this).text());
            nettData.push(parseFloat($cells.eq(0).text()) || 0);
            grossData.push(parseFloat($cells.eq(1).text()) || 0);
            nettTarget = parseFloat($cells.eq(2).text()) || 0;
            grossTarget = parseFloat($cells.eq(3).text()) || 0;
        });

        // Build target line series (constant across all months)
        var nettTargetLine = categories.map(function() { return nettTarget; });
        var grossTargetLine = categories.map(function() { return grossTarget; });

        new Highcharts.Chart({
            chart: {
                renderTo: 'container_productivity',
                reflow: true,
                zoomType: 'x'
            },
            xAxis: {
                categories: categories,
                lineColor: '#e2e8f0',
                lineWidth: 1,
                tickColor: 'transparent'
            },
            yAxis: {
                title: { text: 'Productivity (%)', style: { color: '#718096', fontSize: '10px' } },
                max: 120,
                min: 0,
                gridLineColor: '#f1f5f9',
                gridLineDashStyle: 'Dash',
                labels: { format: '{value}%' }
            },
            tooltip: {
                shared: true,
                crosshairs: true,
                formatter: function() {
                    var s = '<b>' + this.x + '</b>';
                    this.points.forEach(function(p) {
                        s += '<br/><span style="color:' + p.color + '">\u25CF</span> ' +
                             p.series.name + ': <b>' + Highcharts.numberFormat(p.y, 1) + '%</b>';
                    });
                    return s;
                }
            },
            plotOptions: {
                column: {
                    borderRadius: 4,
                    borderWidth: 0,
                    pointPadding: 0.15,
                    groupPadding: 0.25,
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            if (this.y === 0) return '';
                            return Highcharts.numberFormat(this.y, 1) + '%';
                        },
                        style: { fontSize: '9px', fontWeight: '600', textOutline: 'none', color: '#2d3748' }
                    },
                    states: { hover: { brightness: -0.1 } }
                },
                line: {
                    dashStyle: 'Dash',
                    lineWidth: 2,
                    marker: { enabled: false },
                    enableMouseTracking: true,
                    states: { hover: { lineWidth: 3 } },
                    dataLabels: { enabled: false }
                }
            },
            legend: bottomLegend,
            series: [
                {
                    name: 'Nett Actual',
                    type: 'column',
                    data: nettData,
                    color: '#667eea',
                    zIndex: 2
                },
                {
                    name: 'Gross Actual',
                    type: 'column',
                    data: grossData,
                    color: '#43e97b',
                    zIndex: 2
                },
                {
                    name: 'Nett Target',
                    type: 'line',
                    data: nettTargetLine,
                    color: '#667eea',
                    dashStyle: 'LongDash',
                    lineWidth: 2,
                    marker: { enabled: false, symbol: 'diamond', radius: 5 },
                    zIndex: 3
                },
                {
                    name: 'Gross Target',
                    type: 'line',
                    data: grossTargetLine,
                    color: '#43e97b',
                    dashStyle: 'LongDash',
                    lineWidth: 2,
                    marker: { enabled: false, symbol: 'diamond', radius: 5 },
                    zIndex: 3
                }
            ],
            exporting: exportOpts
        });
    });

    // ============================================================
    // Chart 3: Production Qty & PPM
    // ============================================================
    $(document).ready(function() {
        var table = document.getElementById('datatable_ppm');
        if (!table) return;

        // Parse data manually for dual-axis control
        var categories = [];
        $('tbody th', table).each(function() { categories.push(this.innerHTML); });

        var seriesData = [];
        var seriesNames = [];
        $('thead th', table).each(function(i) {
            if (i > 0) seriesNames.push(this.innerHTML);
        });
        $('tr', table).each(function(rowIdx) {
            if (rowIdx === 0) {
                seriesNames.forEach(function() { seriesData.push([]); });
            } else {
                $('td', this).each(function(colIdx) {
                    seriesData[colIdx].push(parseFloat(this.innerHTML));
                });
            }
        });

        // Table columns: Total Production, OK, NG, PPM, Target PPM
        // Columns 0-2 (Qty) -> left axis; Columns 3-4 (PPM/Target) -> right axis
        var series = seriesNames.map(function(name, i) {
            var isQty = (i < 3);
            var isPPM = (i === 3);
            var isTarget = (i === 4);
            return {
                name: name,
                type: (isQty ? 'column' : 'line'),
                data: seriesData[i],
                yAxis: (isQty ? 0 : 1),
                color: isTarget ? '#ed5565' : undefined,
                dashStyle: isTarget ? 'Dash' : undefined,
                lineWidth: isQty ? undefined : 2,
                marker: { enabled: !isQty, radius: 4 }
            };
        });

        new Highcharts.Chart({
            chart: {
                renderTo: 'container_ppm',
                reflow: true
            },
            xAxis: { categories: categories },
            yAxis: [{
                title: { text: 'Production Qty', style: { color: '#718096', fontSize: '10px' } },
                gridLineColor: '#f1f5f9',
                gridLineDashStyle: 'Dash',
                labels: {
                    formatter: function() {
                        var v = this.value;
                        if (v >= 1000000) return (v / 1000000).toFixed(1) + 'M';
                        if (v >= 1000) return (v / 1000).toFixed(0) + 'K';
                        return v;
                    }
                }
            }, {
                title: { text: 'PPM', style: { color: '#718096', fontSize: '10px' } },
                opposite: true,
                gridLineWidth: 0
            }],
            tooltip: {
                shared: true,
                crosshairs: true,
                formatter: function() {
                    var s = '<b>' + this.x + '</b>';
                    this.points.forEach(function(p) {
                        var val = p.y;
                        if (p.series.yAxis === 0 && val >= 1000000) val = (val / 1000000).toFixed(1) + 'M';
                        else if (p.series.yAxis === 0 && val >= 1000) val = (val / 1000).toFixed(0) + 'K';
                        s += '<br/>' + p.series.name + ': <b>' + val + '</b>';
                    });
                    return s;
                }
            },
            plotOptions: {
                column: { dataLabels: { enabled: false } },
                line: { dataLabels: { enabled: false } }
            },
            legend: bottomLegend,
            series: series,
            exporting: exportOpts
        });
    });

    // ============================================================
    // Chart 4: Quality Rate (OK vs NG) - Donut Chart
    // ============================================================
    $(document).ready(function() {
        var kpiSection = document.querySelector('.kpi-card.ok');
        var ngSection = document.querySelector('.kpi-card.ng');
        if (!kpiSection || !ngSection) return;

        var okText = kpiSection.querySelector('.kpi-value').textContent.replace(/[^0-9.]/g, '');
        var ngText = ngSection.querySelector('.kpi-value').textContent.replace(/[^0-9.]/g, '');
        var okVal = parseFloat(okText) || 0;
        var ngVal = parseFloat(ngText) || 0;

        new Highcharts.Chart({
            chart: {
                renderTo: 'container_quality',
                type: 'pie',
                reflow: true,
                options3d: { enabled: false }
            },
            title: { text: null },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y:,.0f}</b> ({point.percentage:.2f}%)',
                shared: false
            },
            plotOptions: {
                pie: {
                    innerSize: '65%',
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br/>{point.percentage:.2f}%',
                        style: { fontSize: '11px', fontWeight: '600', color: '#2d3748', textOutline: 'none' },
                        connectorColor: '#a0aec0'
                    },
                    center: ['50%', '50%'],
                    states: { hover: { brightness: -0.08 } }
                }
            },
            legend: bottomLegend,
            series: [{
                name: 'Production',
                colorByPoint: true,
                data: [
                    { name: 'OK (Good)', y: okVal, color: '#43e97b' },
                    { name: 'NG (Not Good)', y: ngVal, color: '#f5576c' }
                ]
            }],
            exporting: exportOpts
        });
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
