<?php
/**
 * Print Monthly NG Report View - BON TRANSAKSI Format
 * Displays aggregated Not Good (NG) items for disposal for a specific month
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>BON TRANSAKSI - <?php echo $month_name; ?></title>
    <link href="<?= base_url(); ?>template/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/font-awesome/css/font-awesome.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            font-size: 11px;
            background: #fff;
        }
        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }
        
        /* Header Section */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            border: 2px solid #000;
            padding: 8px;
            vertical-align: top;
        }
        .header-left {
            width: 30%;
            text-align: left;
            font-size: 10px;
            line-height: 1.4;
        }
        .header-center {
            width: 40%;
            text-align: center;
            vertical-align: middle;
        }
        .header-right {
            width: 30%;
            text-align: right;
            position: relative;
        }
        .document-title {
            font-weight: bold;
            font-size: 18px;
            margin: 0;
        }
        
        /* Info Section */
        .info-section {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .info-col {
            flex: 1;
        }
        .info-label {
            display: inline-block;
            width: 100px;
        }
        .info-value {
            font-weight: bold;
        }
        
        /* Checkbox Row */
        .checkbox-row {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .checkbox {
            width: 12px;
            height: 12px;
            border: 2px solid #000;
            display: inline-block;
            vertical-align: middle;
        }
        .checkbox.checked {
            background-color: #000;
        }
        
        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        
        /* Signature Section */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .signature-table td {
            border: 2px solid #000;
            padding: 10px;
            height: 120px;
            vertical-align: top;
            text-align: center;
            width: 50%;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
        }

        
        .no-print {
            display: block;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 10px;
            }
            .container {
                max-width: 100%;
            }
            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Table -->
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <strong>PT. Padma Soode Indonesia</strong><br>
                    Bekasi - Indonesia
                </td>
                <td class="header-center">
                    <div class="document-title">BON TRANSAKSI</div>
                </td>
                <td class="header-right">
                </td>
            </tr>
        </table>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-col">
                    <span class="info-label">Bulan</span> : 
                    <span class="info-value"><?= $month_name; ?></span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-col">
                    <span class="info-label">No Bon</span> : 
                    <span class="info-value">BB6.<?= date('Ym', strtotime($month_year . '-01')); ?>.001</span>
                </div>
            </div>
        </div>

        <!-- Checkbox Row -->
        <div class="checkbox-row">
            <div class="checkbox-item">
                <span class="checkbox"></span>
                <span>Permintaan</span>
            </div>
            <div class="checkbox-item">
                <span class="checkbox checked"></span>
                <span>Penyerahan</span>
            </div>
            <div class="checkbox-item">
                <span class="checkbox"></span>
                <span>Pengembalian</span>
            </div>
        </div>

        <div class="checkbox-row">
            <div class="checkbox-item">
                <span class="checkbox"></span>
                <span>Bahan Baku</span>
            </div>
            <div class="checkbox-item">
                <span class="checkbox"></span>
                <span>Produk Jadi</span>
            </div>
            <div class="checkbox-item">
                <span class="checkbox"></span>
                <span>Retur</span>
            </div>
            <div class="checkbox-item">
                <span class="checkbox checked"></span>
                <span>Disposal</span>
            </div>
            <div class="checkbox-item">
                <span class="checkbox"></span>
                <span>Scrap</span>
            </div>
        </div>

        <!-- Print Button -->
        <div class="no-print" style="text-align: right; margin: 15px 0;">
            <button class="btn btn-primary" onclick="window.print();">
                <i class="fa fa-print"></i> Print
            </button>
            <button class="btn btn-default" onclick="window.close();">
                Close
            </button>
        </div>

        <!-- Data Table -->
        <?php if ($ng_reports->num_rows() > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Nomer Mesin</th>
                    <th style="width: 15%;">Part /Product ID</th>
                    <th style="width: 30%;">Part /Product Name</th>
                    <th style="width: 10%;">Unit</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 15%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_ng = 0;
                foreach ($ng_reports->result_array() as $row):
                    $total_ng += $row['qty_ng'];
                ?>
                <tr>
                    <td class="text-center"><?= $row['mesin']; ?></td>
                    <td><?= $row['kode_product']; ?></td>
                    <td><?= $row['nama_product']; ?></td>
                    <td class="text-center"><?= $row['unit']; ?></td>
                    <td class="text-center"><?= number_format($row['qty_ng']); ?></td>
                    <td class="text-left" style="font-size: 9px;"><?= $row['keterangan']; ?></td>
                </tr>
                <?php endforeach; ?>
                
                <!-- Total row -->
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="4" class="text-right"><strong>TOTAL</strong></td>
                    <td class="text-center"><?= number_format($total_ng); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <?php else: ?>
        <div style="text-align: center; padding: 30px; border: 1px solid #ddd; font-style: italic; color: ##777;">
            Tidak ada data untuk bulan ini.
        </div>
        <?php endif; ?>

        <!-- Signature Section -->
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-title">Diserahkan Oleh</div>
                </td>
                <td>
                    <div class="signature-title">Diterima Oleh</div>
                </td>
            </tr>
        </table>

    </div>

    <script src="<?= base_url(); ?>template/js/jquery-3.1.1.min.js"></script>
    <script src="<?= base_url(); ?>template/js/bootstrap.min.js"></script>
</body>
</html>
