<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">

<style>
    th, td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        width: auto;
        margin: 0 auto;
    }

    tr {
        background-color: white
    }
</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="d-flex">
                        <div class="mr-auto p-2">
                            <h5>Export DPR Data to Excel</h5>
                        </div>
                        <div class="p-2">
                            <a href="<?php echo base_url('c_export/index') ?>" class="btn btn-sm btn-primary">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>

                <div class="ibox-content">
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php } ?>

                    <?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>

                    <?= form_open('c_export/index', array('id' => 'exportForm', 'onsubmit' => 'return validateForm()')); ?>
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data for Export</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-3">
                                    <b>Month</b>
                                    <select name="month" id="month" class="form-control" required>
                                        <option value="">-- Select Month --</option>
                                        <option value="01" <?php if ($month == '01') echo 'selected'; ?>>January</option>
                                        <option value="02" <?php if ($month == '02') echo 'selected'; ?>>February</option>
                                        <option value="03" <?php if ($month == '03') echo 'selected'; ?>>March</option>
                                        <option value="04" <?php if ($month == '04') echo 'selected'; ?>>April</option>
                                        <option value="05" <?php if ($month == '05') echo 'selected'; ?>>May</option>
                                        <option value="06" <?php if ($month == '06') echo 'selected'; ?>>June</option>
                                        <option value="07" <?php if ($month == '07') echo 'selected'; ?>>July</option>
                                        <option value="08" <?php if ($month == '08') echo 'selected'; ?>>August</option>
                                        <option value="09" <?php if ($month == '09') echo 'selected'; ?>>September</option>
                                        <option value="10" <?php if ($month == '10') echo 'selected'; ?>>October</option>
                                        <option value="11" <?php if ($month == '11') echo 'selected'; ?>>November</option>
                                        <option value="12" <?php if ($month == '12') echo 'selected'; ?>>December</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <b>Year</b>
                                    <select name="year" id="year" class="form-control" required>
                                        <option value="">-- Select Year --</option>
                                        <?php foreach ($years as $y): ?>
                                            <option value="<?= $y ?>" <?php if ($year == $y) echo 'selected'; ?>><?= $y ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <b>Shift</b>
                                    <select name="shift" class="form-control">
                                        <option <?php if ($shift == 'All') echo 'selected'; ?> value='All'>All</option>
                                        <option <?php if ($shift == '1') echo 'selected'; ?> value='1'>1</option>
                                        <option <?php if ($shift == '2') echo 'selected'; ?> value='2'>2</option>
                                        <option <?php if ($shift == '3') echo 'selected'; ?> value='3'>3</option>
                                    </select>
                                </div>
                                <div class="col-sm-2" style="margin-top: 24px;">
                                    <button type="submit" name="view_export" value="view" id="viewExportBtn" class="btn btn-primary btn-block">
                                        <i class="fa fa-table"></i> View
                                    </button>
                                </div>
                                <div class="col-sm-1" style="margin-top: 24px;">
                                    <button type="submit" name="export_csv" value="csv" id="exportCsvBtn" class="btn btn-info btn-block" title="Export to CSV">
                                        <i class="fa fa-file-csv"></i>
                                    </button>
                                </div>
                                <div class="col-sm-1" style="margin-top: 24px;">
                                    <button type="submit" name="export" value="export" id="exportBtn" class="btn btn-success btn-block" title="Direct Excel Download">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>

                    <div class="card rounded">
                        <div class="card-header bg-info">
                            <h5>Export Instructions</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Select the <strong>Month</strong> and <strong>Year</strong> for the DPR data you want to export</li>
                                <li>Choose the <strong>Shift</strong> (All, 1, 2, or 3)</li>
                                <li>Click one of the export buttons:
                                    <ul>
                                        <li><strong>View</strong> - Preview data in table format</li>
                                        <li><strong>CSV</strong> - Download as CSV file (same data as view)</li>
                                        <li><strong>Excel</strong> - Download as Excel file with formatting</li>
                                    </ul>
                                </li>
                                <li>The CSV file will be downloaded with format: <code>DPR_Export_MonthName_Year.csv</code> (e.g., <code>DPR_Export_January_2025.csv</code>)</li>
                                <li>The exported file contains all DPR data for the selected month with all 104 columns:
                                    <ul>
                                        <li>Date, Shift, Machine information</li>
                                        <li>Product Code and Product Name</li>
                                        <li>Quantity OK, NG, and LT data</li>
                                        <li>Operator and Kanit names</li>
                                        <li>All defect types and loss time categories</li>
                                    </ul>
                                </li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateForm() {
    var month = document.getElementById('month').value;
    var year = document.getElementById('year').value;

    if (month === '' || month === null) {
        alert('Please select a month.');
        return false;
    }

    if (year === '' || year === null) {
        alert('Please select a year.');
        return false;
    }

    // Change button appearance but DON'T disable it yet
    // (disabled buttons don't send their value with the form!)
    document.getElementById('exportBtn').innerHTML = '<i class="fa fa-spinner fa-spin"></i> Exporting...';

    // Use setTimeout to disable button AFTER form submission
    setTimeout(function() {
        document.getElementById('exportBtn').disabled = true;
    }, 100);

    return true;
}
</script>

<?php $this->load->view('layout/footer'); ?>
</body>
</html>
