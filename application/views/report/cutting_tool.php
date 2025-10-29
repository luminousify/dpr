<?php $this->load->view('layout/sidebar'); ?>

<div class="ibox-content">
    <?= form_open('c_report/cutting_tool'); ?>  
    <div class="card rounded mb-4">
        <div class="card-header">
            <h2>Filter Data</h2>
        </div>
        <div class="card-body">
            <div class="row" style="margin-left:2px;">
                <div class="col-sm-3"> 
                    <b>Pilih Tahun - Bulan</b> 
                    <select name="tahun" style="width: 300px;" class="form-control">
                        <?php
                        $tahuns = date('Y')-1;
                        $now=date('Y');
                        for($i=$tahuns; $i<=$now; $i++){
                        $monts = array("01","02","03","04","05","06","07","08","09","10","11","12");
                           foreach ($monts as $value) { 
                                $option_value = $i.'-'.$value;
                                $selected = (isset($year_month) && $year_month == $option_value) ? 'selected="selected"' : '';
                                ?>
                                <option value="<?php echo $option_value?>" <?php echo $selected; ?>><?php echo $option_value; ?></option><?php
                           }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <b>Pilih Cutting Tool</b>
                    <select name="cutting_tool_id" style="width: 300px;" class="form-control">
                        <option value="">-- All Cutting Tools --</option>
                        <?php if(isset($all_cutting_tools) && !empty($all_cutting_tools)): ?>
                            <?php foreach($all_cutting_tools as $tool): ?>
                                <option value="<?= $tool['id'] ?>" <?= (isset($cutting_tool_id) && $cutting_tool_id == $tool['id']) ? 'selected' : '' ?>><?= htmlspecialchars($tool['code']) ?><?= !empty($tool['code_group']) ? ' - '.htmlspecialchars($tool['code_group']) : '' ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show"></div>
            </div>
        </div>
        <div class="row ml-4">
            <p><strong>*Catatan :</strong> Data yang muncul berdasarkan filter yang dipilih. Jika tidak ada filter, akan menampilkan data bulan ini saja.</p>
        </div>
    </div>
    <?= form_close(); ?>

    <?php if(isset($cutting_tool_id) && !empty($cutting_tool_id) && isset($product_list) && !empty($product_list)): ?>
    <!-- Section for showing products that use a specific cutting tool -->
    <div class="card rounded mb-4">
        <div class="card-header">
            <h2>Product Tracing for Cutting Tool: <?= htmlspecialchars($product_list[0]['cutting_tool_code'] ?? 'N/A') ?></h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="productTracingTable" class="table table-striped table-bordered table-hover" style="width:100%; padding: 10px;">
                    <thead class="thead-dark">
                        <tr>
                            <th style="padding: 10px;">Date</th>
                            <th style="padding: 10px;">Product Code</th>
                            <th style="padding: 10px;">Product Name</th>
                            <th class="text-center" style="padding: 10px;">Shift</th>
                            <th class="text-center" style="padding: 10px;">Operator</th>
                            <th class="text-right" style="padding: 10px;">Qty OK</th>
                            <th class="text-right" style="padding: 10px;">Qty NG</th>
                            <th class="text-right" style="padding: 10px;">Nett Production</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($product_list)) : ?>
                            <?php foreach ($product_list as $item) : ?>
                                <tr>
                                    <td style="padding: 10px;"><?= date('Y-m-d', strtotime($item['production_date'])) ?></td>
                                    <td style="padding: 10px;"><?= htmlspecialchars($item['kode_product']) ?></td>
                                    <td style="padding: 10px;"><?= htmlspecialchars($item['nama_product']) ?></td>
                                    <td class="text-center" style="padding: 10px;"><?= htmlspecialchars($item['shift']) ?></td>
                                    <td class="text-center" style="padding: 10px;"><?= htmlspecialchars($item['operator']) ?></td>
                                    <td class="text-right" style="padding: 10px;"><?= number_format($item['qty_ok']) ?></td>
                                    <td class="text-right" style="padding: 10px;"><?= number_format($item['qty_ng']) ?></td>
                                    <td class="text-right" style="padding: 10px;"><?= number_format($item['nett_prod']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="8" class="text-center" style="padding: 10px;">No product tracing data available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <h2 class="page-header">Cutting Tools and Products List</h2>
    
    <?php
    // Group by cutting tool for a more organized display
    $grouped_tools = [];
    if (!empty($cutting_tool_summary)) {
        // First, let's extract just the tools (for cases with no products)
        foreach ($cutting_tool_summary as $row) {
            $tool_id = $row['cutting_tools_id'];
            $tool_code = $row['cutting_tool_code'] ?: 'Unknown';
            
            if (!isset($grouped_tools[$tool_id])) {
                $grouped_tools[$tool_id] = [
                    'tool_id' => $tool_id,
                    'tool_code' => $tool_code,
                    'code_group' => $row['code_group'] ?? '',
                    'usage_count' => $row['usage_count'] ?? 0,
                    'products' => []
                ];
            }
            
            // Only add product if one exists (not null) and it's not the NULL placeholder from the UNION query
            if (!empty($row['kode_product'])) {
                $grouped_tools[$tool_id]['products'][] = [
                    'kode_product' => $row['kode_product'],
                    'nama_product' => $row['nama_product'],
                    'total_qty_ok' => $row['total_qty_ok'],
                    'total_qty_ng' => $row['total_qty_ng'],
                    'total_nett_prod' => $row['total_nett_prod']
                ];
            }
        }
    }
    ?>
    
    <?php if (!empty($grouped_tools)): ?>
        <?php foreach ($grouped_tools as $tool): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Cutting Tool: <?= htmlspecialchars($tool['tool_code']) ?> (ID: <?= $tool['tool_id'] ?>)</h3>
                            <?php if (!empty($tool['code_group'])): ?>
                                <p class="mb-0">Group: <?= htmlspecialchars($tool['code_group']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="<?= site_url('c_report/cutting_tool?cutting_tool_id=' . $tool['tool_id'] . '&tahun=' . $year_month) ?>" class="btn btn-light">View Detailed Tracing</a>
                            <?php if (!empty($tool['usage_count'])): ?>
                                <p class="text-white mt-2">Total Usage Count: <?= $tool['usage_count'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($tool['products'])): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="padding: 10px;">Product Code</th>
                                        <th style="padding: 10px;">Product Name</th>
                                        <th class="text-right" style="padding: 10px;">Total Qty OK</th>
                                        <th class="text-right" style="padding: 10px;">Total Qty NG</th>
                                        <th class="text-right" style="padding: 10px;">Total Nett Production</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tool['products'] as $product): ?>
                                        <tr>
                                            <td style="padding: 10px;"><?= htmlspecialchars($product['kode_product']) ?></td>
                                            <td style="padding: 10px;"><?= htmlspecialchars($product['nama_product']) ?></td>
                                            <td class="text-right" style="padding: 10px;"><?= number_format($product['total_qty_ok']) ?></td>
                                            <td class="text-right" style="padding: 10px;"><?= number_format($product['total_qty_ng']) ?></td>
                                            <td class="text-right" style="padding: 10px;"><?= number_format($product['total_nett_prod']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <strong>This cutting tool exists in the database but has no linked products for the selected period.</strong>
                            <p>Total usage references in database: <?= $tool['usage_count'] ?></p>
                            <?php if (!empty($tool['code_group'])): ?>
                                <p>Tool Group: <?= htmlspecialchars($tool['code_group']) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">
            No cutting tools data available for the selected period.
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#productTracingTable').DataTable({
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }
});
</script>

<?php $this->load->view('layout/footer'); ?>
