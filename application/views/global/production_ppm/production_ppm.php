<div class="card rounded">
        <div id="container2" class="p-3"></div>
    </div>
        <br/>
        <?php foreach ($ppm_fcost_target->result_array() as $target_ppm) : ?>  
        <?php endforeach; ?>
        <?php foreach ($f_cost_int_defect->result_array() as $fcost_int) : ?>  
        <?php endforeach; ?>
        <table class="table table-bordered" id="datatable1" style="display: none;">
            <thead>
                <tr>
                    <th></th>
                    <th>Total Production</th>
                    <th>OK</th>
                    <th>NG</th>
                    <th>PPM</th>
                    <th>Target PPM</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                    $namaBulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"); 
                    foreach($data_productionGrafik->result_array() as $data)
                        { 
                            for($i=1;$i<=12;$i++) {
                                echo '<tr>';
                                echo '<th>'.$namaBulan[$i].'</th>';
                                echo '<td>'.$data['total_prod'.$i].'</td>';
                                echo '<td>'.$data['ok'.$i].'</td>';
                                echo '<td>'.$data['ng'.$i].'</td>';
                                echo '<td>'.$data['ppm'.$i].'</td>';
                                echo '<td>'.$target_ppm['ppm_target'].'</td>';
                                echo '</tr>';
                            }
                        }?>
            </tbody>
        </table>
        <br>

        <table class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
            <?php foreach ($ppm_fcost_target->result_array() as $target) : ?>  
            <?php endforeach; ?>
            <?php foreach ($total_prod_qty_and_ppm->result_array() as $prod_ppm_detail) : ?>  
            <?php endforeach; ?>
            <?php foreach ($total_produksi->result_array() as $tp) : ?>  
            <?php endforeach; ?>
            <?php foreach ($total_produksi_ng->result_array() as $ng) : ?>  
            <?php endforeach; ?>
            <thead>
                <tr>
                    <th class="align-middle text-center" rowspan="2">No</th>
                    <th class="align-middle text-center" rowspan="2">Keterangan</th>
                    <th class="align-middle text-center" colspan="36" style="background:#1ab394;color: white;text-align: center;">Bulan</th>
                </tr>
                <tr>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Jan</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Feb</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Mar</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Apr</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">May</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Jun</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Jul</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Aug</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Sep</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Okt</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Nov</th>
                    <th class="align-middle text-center" style="background:#1ab394;color: white;text-align: center;">Des</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($total_produksi->result_array() as $total_prod) {
                    echo '<tr>';
                    echo '<td>1</td>';
                    echo '<td>Total Produksi</td>';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<td>' . round($total_prod['total_prod' . $i], 1) . '</td>';   
                    }
                    echo '</tr>';
                }
                ?>

                <?php
                foreach ($total_produksi_ok->result_array() as $total_prod_ok) {
                    echo '<tr>';
                    echo '<td>2</td>';
                    echo '<td>Total Produksi OK</td>';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<td>' . round($total_prod_ok['ok' . $i], 1) . '</td>';   
                    }
                    echo '</tr>';
                }
                ?>

                <?php
                foreach ($total_produksi_ng->result_array() as $total_prod_ng) {
                    echo '<tr>';
                    echo '<td>3</td>';
                    echo '<td>Total Defect</td>';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<td>' . round($total_prod_ng['ng' . $i], 1) . '</td>';   
                    }
                    echo '</tr>';
                }
                ?>

                <tr>
                    <td>4</td>
                    <td>Defect Rate (%)</td>
                  
                        <?php  
                            for ($i = 1; $i <= 12; $i++) {
                                $ppm = ($tp['total_prod' . $i] != 0) ? ($ng['ng'.$i] / $tp['total_prod' . $i]) *100 : 0; 
                                $ppm_fix = round($ppm,2);
                                echo '<td>' . $ppm_fix;'</td>';   
                            }
                        ?>
                 
                </tr>

                <?php
                foreach ($total_ppm->result_array() as $ppm) {
                    echo '<tr>';
                    echo '<td>5</td>';
                    echo '<td>PPM</td>';
                    for ($i = 1; $i <= 12; $i++) {
                        if ($ppm['ppm'.$i] > $target['ppm_target']) {
                            echo "<td style='background:#df4759;color: white;'>".$ppm['ppm'.$i];"</td>";
                        } else {
                            echo "<td>".$ppm['ppm'.$i];"</td>";
                        }
                    }
                    echo '</tr>';
                }
                ?>

                <?php
                foreach ($ppm_fcost_target->result_array() as $ppm_target) {
                    echo '<tr>';
                    echo '<td>6</td>';
                    echo '<td>PPM Target</td>';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<td>'.$ppm_target['ppm_target'].'</td>';
                    }
                    echo '</tr>';
                }
                ?>

                <tr>
                    <td>7</td>
                    <th></th>
                    <?php for($i=1;$i<=12;$i++) { ?>
                            <th></th>
                    <?php } ?> 
                </tr>

                <?php
                foreach ($fcost_target->result_array() as $f_cost_target) {
                    echo '<tr>';
                    echo '<td>8</td>';
                    echo '<td>F - COST (IDR) Target</td>';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<td>' . $f_cost_target['f_cost_target'] . '</td>';   
                    }
                    echo '</tr>';
                }
                ?>

                <?php
                foreach ($f_cost_int_defect->result_array() as $f_cost_int_defect) {
                    echo '<tr>';
                    echo '<td>9</td>';
                    echo '<td>F - COST (IDR) Internal Defect</td>';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<td>' . round($f_cost_int_defect['total' . $i]) . '</td>';   
                    }
                    echo '</tr>';
                }
                ?>

                <tr>
                    <td>10</td>
                    <td>F - COST (IDR) Eksternal Defect</td>
                    <?php for($i=1;$i<=12;$i++) { ?>
                            <td>-</td>
                    <?php } ?> 
                </tr>

                 <?php
                foreach ($f_cost_int_defect->result_array() as $fcost_total) {
                    echo '<tr>';
                    echo '<td>11</td>';
                    echo '<td>F - COST (IDR) Total Internal Defect</td>';
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<td>' . round($fcost_total['total' . $i]) . '</td>';   
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <br>
        
