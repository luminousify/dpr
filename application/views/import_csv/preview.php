<style>
    .preview-section {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 30px;
        margin-top: 20px;
    }
    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
    }
    .info-badge {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 5px;
        margin-right: 10px;
    }
</style>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Preview CSV Data</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="preview-section">
                            <div class="alert alert-info">
                                <h6><i class="fa fa-info-circle"></i> Import Preview</h6>
                                <p>
                                    <strong>CSV File:</strong> <?php echo basename($csv_path); ?><br>
                                    <strong>Total Rows in File:</strong> <?php echo $total_rows; ?><br>
                                    <strong>Showing First 10 Rows:</strong>
                                </p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead style="position: sticky; top: 0; background: #fff; z-index: 10;">
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Mesin</th>
                                            <th>Shift</th>
                                            <th>Group</th>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>OK</th>
                                            <th>NG</th>
                                            <th>Operator</th>
                                            <th>Kanit</th>
                                            <th>Customer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($preview_data)): ?>
                                            <?php foreach ($preview_data as $index => $row): ?>
                                                <tr>
                                                    <td><?php echo $row['No.']; ?></td>
                                                    <td><?php echo $row['Tanggal']; ?></td>
                                                    <td><?php echo $row['Mesin']; ?></td>
                                                    <td><?php echo $row['Shift']; ?></td>
                                                    <td><?php echo $row['Group Shift']; ?></td>
                                                    <td><?php echo $row['Product ID']; ?></td>
                                                    <td><?php echo htmlspecialchars(substr($row['Product Name'], 0, 30)); ?>...</td>
                                                    <td><?php echo number_format($row['OK'], 0, ',', '.'); ?></td>
                                                    <td><?php echo number_format($row['Total Defect'], 0, ',', '.'); ?></td>
                                                    <td><?php echo htmlspecialchars($row['Operator']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['Kanit']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['Customer']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="12" class="text-center">No data found in CSV file</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-warning mt-3">
                                <h6><i class="fa fa-exclamation-triangle"></i> Before Importing</h6>
                                <ul>
                                    <li>Review the data above to ensure it looks correct</li>
                                    <li>The import process will insert data into 4 tables:
                                        <ul>
                                            <li><strong>t_production_op</strong> - Main production data</li>
                                            <li><strong>t_production_op_dl</strong> - Defects and loss time records</li>
                                            <li><strong>t_production_op_cutting_tools_usage</strong> - Cutting tools usage</li>
                                        </ul>
                                    </li>
                                    <li>Each row is processed in a transaction (all or nothing)</li>
                                    <li>Validation will be performed before insertion</li>
                                    <li>The import process may take several minutes for large files</li>
                                </ul>
                            </div>

                            <div class="form-group text-center mt-4">
                                <form action="<?php echo site_url('c_import_csv/process_import'); ?>" method="post" onsubmit="return confirmImport()">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fa fa-database"></i> Start Import Process
                                    </button>
                                    <a href="<?php echo site_url('c_import_csv/index'); ?>" class="btn btn-secondary btn-lg">
                                        <i class="fa fa-arrow-left"></i> Cancel & Upload New File
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    function confirmImport() {
        return confirm('Are you sure you want to start the import process?\n\n' +
                      'This will insert data into the database.\n' +
                      'Each row will be processed in a transaction.\n\n' +
                      'Click OK to proceed or Cancel to go back.');
    }
</script>
