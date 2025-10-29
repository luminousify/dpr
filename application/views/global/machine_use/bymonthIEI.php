<div class="table-responsive">

    <table class="table table-striped table-bordered table-hover dataTables-example">
        <thead>
            <?php foreach ($avail_capacity_machine_total->result_array() as $acm) : ?>  
            <?php endforeach; ?>
            <?php foreach ($hitungTotalAVHourPerbulan->result_array() as $totalAVHour) : ?>  
            <?php endforeach; ?>
            <?php foreach ($total_ton_per_month->result_array() as $tonHour) : ?>  
            <?php endforeach; ?>
            <tr>
                <th class="align-middle text-center" rowspan="3">No</th>
                <th class="align-middle text-center" rowspan="3">Machine (Tons)</th>
                <th class="align-middle text-left" colspan="36" style="background:#1ab394;color: white;text-align: center;">Bulan</th>
            </tr>
            <tr>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jan</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Feb</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Mar</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Apr</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">May</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jun</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Jul</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Aug</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Sep</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Okt</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Nov</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Des</th>
            </tr>
            <tr>
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>Hours</center>
                    </th>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>Unit</center>
                    </th>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>Av. Capacity : <?php echo $acm['av_hour'.$i] ?></center>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $no = 0;
            $sum = 0;
            foreach ($machine_use_byton_bycustomer_IEI->result_array() as $data) {
                $no++;
                echo '<tr>';
                echo '<td>' . $no;
                '</td>';
                echo '<td>' . $data['tonnase'];
                '</td>';
                for ($i = 1; $i <= 12; $i++) {
                    
                    //$unit = ($acm['av_hour'.$i] != 0) ? $data['t'.$i] / $acm['av_hour'.$i] : 0;
                    //$unit_fix = round($unit,1);
                    echo '<td>' . round($data['t' . $i], 1) . '</d>';
                    echo '<td>' . round($data['unit' . $i], 2) . '</td>';
                    echo '<td></td>';
                }
                echo '</tr>';
            }
            ?>

            <?php  
            foreach ($total_ton_per_month->result_array() as $tot_hours) {
                echo '<tr>';
                echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                echo '<td style="background:#1ab394;color: white;text-align: center;">T. Machine Use (Hours)</td>';
                for ($i = 1; $i <= 12; $i++) {
                    echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($tot_hours['t' . $i],1) . '</td>';
                    echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($tot_hours['unit' . $i],2) . '</td>';
                    echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                }
                echo '</tr>';
            }
            ?>

            <?php  
            foreach ($hitungTotalAVHourPerbulan->result_array() as $dataAVHour) {
                echo '<tr>';
                echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                echo '<td style="background:#1ab394;color: white;text-align: center;">Total Machine Cap</td>';
                for ($i = 1; $i <= 12; $i++) {
                    echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($dataAVHour['total_avhour' . $i], 1) . '</td>';
                    echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                    echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                }
                echo '</tr>';
            }
            ?>
            <tr>
                <td style="background:#1ab394;color: white;text-align: center;"></td>
                <td style="background:#1ab394;color: white;text-align: center;">Total Machine Use (%)</td>
                <?php  
                for ($i = 1; $i <= 12; $i++) {
                    $persen = ($totalAVHour['total_avhour'.$i] != 0) ? ($tonHour['t'.$i] / $totalAVHour['total_avhour'.$i]) *100 : 0;
                    $persen_fix = round($persen,2);
                    echo '<td style="background:#1ab394;color: white;text-align: center;">' . $persen_fix . '</td>';
                    echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                    echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                }
                ?>
            </tr>
        </tbody>
    </table>
<!--     <?php
    //var_dump($sum);
    ?> -->
</div>