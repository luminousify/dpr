<style>
    .result-section {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 30px;
        margin-top: 20px;
    }
    .success-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        margin-bottom: 30px;
    }
    .stats-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }
    .stats-number {
        font-size: 48px;
        font-weight: bold;
        color: #007bff;
    }
    .error-log {
        max-height: 400px;
        overflow-y: auto;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 15px;
    }
</style>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Import Result</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="result-section">
                            <?php if ($result['success'] > 0): ?>
                                <div class="success-box">
                                    <h2><i class="fa fa-check-circle"></i> Import Completed!</h2>
                                    <p>Your DPR production data has been successfully imported into the database.</p>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <h6>Total Rows</h6>
                                        <div class="stats-number"><?php echo $result['total_rows']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <h6>Successful</h6>
                                        <div class="stats-number" style="color: #28a745;"><?php echo $result['success']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <h6>Failed</h6>
                                        <div class="stats-number" style="color: #dc3545;"><?php echo $result['failed']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <h6>Success Rate</h6>
                                        <div class="stats-number" style="color: #17a2b8;">
                                            <?php
                                            $rate = $result['total_rows'] > 0 ? round(($result['success'] / $result['total_rows']) * 100, 2) : 0;
                                            echo $rate . '%';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($result['errors'])): ?>
                                <div class="alert alert-danger mt-4">
                                    <h6><i class="fa fa-exclamation-triangle"></i> Import Errors (<?php echo count($result['errors']); ?>)</h6>
                                    <p>The following rows could not be imported:</p>
                                </div>

                                <div class="error-log">
                                    <ul>
                                        <?php foreach ($result['errors'] as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($result['warnings'])): ?>
                                <div class="alert alert-warning mt-4">
                                    <h6><i class="fa fa-exclamation-circle"></i> Defect Reconciliation Warnings (<?php echo count($result['warnings']); ?>)</h6>
                                    <p>The following rows imported successfully but have inconsistent defect totals in the source file. The xlsx "Total Defect" column does not equal the sum of individual "NG-*" columns. Reports will show the sum of the NG-* breakdown.</p>
                                </div>

                                <div class="error-log">
                                    <ul>
                                        <?php foreach ($result['warnings'] as $warning): ?>
                                            <li><?php echo htmlspecialchars($warning); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="alert alert-info mt-4">
                                <h6><i class="fa fa-info-circle"></i> What Happened During Import</h6>
                                <ul>
                                    <li>Each row was processed in a transaction (all tables updated together)</li>
                                    <li>Main production data was inserted into <strong>t_production_op</strong></li>
                                    <li>NG defects were inserted into <strong>t_production_op_dl</strong> (type='NG')</li>
                                    <li>Loss time records were inserted into <strong>t_production_op_dl</strong> (type='LT')</li>
                                    <li>Cutting tools were linked in <strong>t_production_op_cutting_tools_usage</strong></li>
                                    <li>Decimal numbers were converted from Indonesian format (comma) to MySQL format (dot)</li>
                                    <li>Product IDs were looked up and validated against the <strong>select_bom</strong> table</li>
                                </ul>
                            </div>

                            <div class="form-group text-center mt-4">
                                <a href="<?php echo site_url('c_dpr/dpr'); ?>" class="btn btn-primary btn-lg">
                                    <i class="fa fa-list"></i> View Imported DPR Data
                                </a>
                                <a href="<?php echo site_url('c_import_csv/index'); ?>" class="btn btn-secondary btn-lg">
                                    <i class="fa fa-upload"></i> Import Another CSV File
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
