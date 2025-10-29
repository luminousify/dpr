<?php 
/**
 * Print NG Report View
 * Displays Not Good (NG) items for a specific date
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>DPR | NG Report <?php echo date('d-m-Y', strtotime($date)); ?></title>
    <link href="<?= base_url(); ?>template/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/font-awesome/css/font-awesome.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 10px;
            font-size: 12px;
        }
        .company-info {
            margin-bottom: 10px;
        }
        .company-name {
            font-weight: bold;
            font-size: 14px;
        }
        .department {
            font-size: 12px;
        }
        .report-title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .date-info {
            text-align: right;
            margin-bottom: 8px;
            font-size: 12px;
        }
        .surat-jalan {
            margin-bottom: 8px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
            font-size: 11px;
        }
        .no-data {
            text-align: center;
            padding: 10px;
            font-style: italic;
            color: #777;
            font-size: 11px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
                font-size: 11px;
            }
            .container {
                width: 100%;
                max-width: none;
            }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="company-info">
            <div class="company-name">PT. PADMA SOODE INDONESIA</div>
            <div class="department">PPIC Department</div>
            <div class="report-title">SHIPPING DISPOSAL</div>
        </div>
        
        <div class="date-info">
            <strong>Date:</strong> <?php echo date('d-M-y', strtotime($date)); ?>
        </div>
        
        <div class="surat-jalan">
            <strong>No. Surat Jalan:</strong>
        </div>
        
        <!-- Button to print -->
        <div class="no-print" style="text-align: right; margin-bottom: 15px;">
            <button class="btn btn-primary" onclick="window.print();">
                <i class="fa fa-print"></i> Print Report
            </button>
            <button class="btn btn-default" onclick="window.close();">
                Close
            </button>
        </div>
        
        <?php /* Debug information section (will not print) */ ?>
        <?php if (isset($debug_info) && ENVIRONMENT !== 'production'): ?>
        <div class="alert alert-info no-print">
            <h4>Debug Information</h4>
            <ul>
                <li>Received Date: <?= $debug_info['received_date'] ?></li>
                <li>Formatted Date: <?= $debug_info['formatted_date'] ?></li>
                <li>Result Count: <?= $debug_info['result_count'] ?></li>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php if ($ng_reports->num_rows() > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 20%;">Product ID</th>
                    <th style="width: 30%;">Product Name</th>
                    <th style="width: 10%;">Unit</th>
                    <th style="width: 10%;">Qty NG</th>
                    <th style="width: 25%;">Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $total_ng = 0;
                foreach ($ng_reports->result_array() as $row): 
                    $total_ng += $row['qty_ng'];
                ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td><?= $row['kode_product']; ?></td>
                    <td><?= $row['nama_product']; ?></td>
                    <td style="text-align: center;"><?= $row['unit']; ?></td>
                    <td style="text-align: center;"><?= $row['qty_ng']; ?></td>
                    <td><?= !empty($row['keterangan']) ? $row['keterangan'] : ''; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">
            <p>No NG items found for this date.</p>
        </div>
        <?php endif; ?>

        <table style="width: 100%; margin-top: 40px; border-collapse: collapse;">
            <tr>
                <td style="border: 1px solid black; width: 60%; height: 100px;"></td>
                <td style="border: 1px solid black; width: 20%; height: 100px; text-align: center; vertical-align: top; padding: 5px;">
                    Shipping By
                </td>
                <td style="border: 1px solid black; width: 20%; height: 100px; text-align: center; vertical-align: top; padding: 5px;">
                    Received by
                </td>
            </tr>
        </table>
    </div>
    
    <script src="<?= base_url(); ?>template/js/jquery-3.1.1.min.js"></script>
    <script src="<?= base_url(); ?>template/js/bootstrap.min.js"></script>
</body>
</html>
