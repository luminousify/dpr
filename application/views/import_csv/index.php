<style>
    .upload-section {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 30px;
        margin-top: 20px;
    }
    .upload-area {
        border: 2px dashed #007bff;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
        background-color: #f8f9ff;
        margin-bottom: 20px;
    }
    .upload-area:hover {
        background-color: #e9ecef;
    }
    .btn-upload {
        background-color: #007bff;
        color: white;
        padding: 10px 30px;
        border-radius: 5px;
    }
    .info-box {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .warning-box {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Import DPR CSV Data</h5>
                    </div>
                    <div class="ibox-content">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="upload-section">
                            <h3>Upload CSV File</h3>
                            <p>Upload your DPR production data CSV file to import into the database.</p>

                            <div class="info-box">
                                <h6><i class="fa fa-info-circle"></i> Supported CSV Format</h6>
                                <p>The CSV file should contain the following columns:</p>
                                <ul>
                                    <li>No., Tanggal, Mesin, Shift, Group Shift, W H, O T, Total LT</li>
                                    <li>Product ID, Product Name, Matl Lot No, Tool, OK, Target Qty, Total Defect</li>
                                    <li>CT Std, CT Actual, N-Prod, G-Prod</li>
                                    <li>NG Defects columns (NG-Bending, NG-Berawan, etc.)</li>
                                    <li>LT Loss Time columns (LT-Adjust Parameter, LT-Pre Heating Material, etc.)</li>
                                    <li>Operator, Kanit, Customer, Keterangan, Cutting Tool</li>
                                    <li>Verif. Kanit, Verif. Kasi</li>
                                </ul>
                            </div>

                            <div class="warning-box">
                                <h6><i class="fa fa-exclamation-triangle"></i> Important Notes</h6>
                                <ul>
                                    <li>Make sure your CSV file uses comma (,) as delimiter</li>
                                    <li>Decimal numbers should use comma as separator (e.g., 24,5 not 24.5)</li>
                                    <li>Date format should be YYYY-MM-DD</li>
                                    <li>The system will validate all data before importing</li>
                                    <li>Each row will be imported in a transaction (all or nothing)</li>
                                </ul>
                            </div>

                            <form action="<?php echo site_url('c_import_csv/upload_csv'); ?>" method="post" enctype="multipart/form-data">
                                <div class="upload-area" ondragover="event.preventDefault()" ondrop="dropHandler(event)">
                                    <i class="fa fa-cloud-upload-alt" style="font-size: 48px; color: #007bff;"></i>
                                    <h4>Drag & Drop CSV File Here</h4>
                                    <p>or</p>
                                    <div class="form-group">
                                        <input type="file" name="csv_file" class="form-control-file" accept=".csv" required id="csv_file" onchange="validateFile()">
                                        <small class="form-text text-muted">Maximum file size: 10MB</small>
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-upload btn-lg">
                                        <i class="fa fa-upload"></i> Upload & Preview
                                    </button>
                                    <a href="<?php echo site_url('c_import_csv/download_template'); ?>" class="btn btn-secondary btn-lg">
                                        <i class="fa fa-download"></i> Download Template
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="<?php echo base_url(); ?>template/js/jquery-3.6.0.min.js"></script>
<script>
        function validateFile() {
            const fileInput = document.getElementById('csv_file');
            const filePath = fileInput.value;
            const allowedExtensions = /(\.csv)$/i;

            if (!allowedExtensions.exec(filePath)) {
                alert('Please upload a CSV file only.');
                fileInput.value = '';
                return false;
            }

            // Check file size (10MB)
            if (fileInput.files && fileInput.files[0]) {
                const fileSize = fileInput.files[0].size;
                if (fileSize > 10 * 1024 * 1024) {
                    alert('File size exceeds 10MB limit.');
                    fileInput.value = '';
                    return false;
                }
            }

            return true;
        }

        function dropHandler(ev) {
            ev.preventDefault();
            const fileInput = document.getElementById('csv_file');

            if (ev.dataTransfer.items) {
                [...ev.dataTransfer.items].forEach((item, i) => {
                    if (item.kind === 'file') {
                        const file = item.getAsFile();
                        if (file.name.endsWith('.csv')) {
                            fileInput.files = ev.dataTransfer.files;
                        } else {
                            alert('Please upload a CSV file only.');
                        }
                    }
                });
            }
        }
    </script>
