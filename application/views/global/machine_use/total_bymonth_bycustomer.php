<div class=" card rounded">
    <div id="container3"></div>
</div>
    <br/>

        <table class="table table-bordered" id="datatable3" style="display: none;">
                <thead>
                <tr>
                    <th></th>
                    <th>Total Machine Use (Hours)</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 0 ; foreach($grafik_total_machine_use_bycustomer->result_array() as $data)
                        {  $no++;                           
                                echo '<tr>';
                                    echo '<th>'.$data['customer'].'</th>';
                                    echo '<td>'.round($data['machine_use'],1).'</td>';
                                echo '</tr>'; 
                        }?>

                </tbody>
            </table>
            <br/>
            <br/>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover dataTables-example">
        <thead>
            <?php foreach ($hitungTotalHourPerbulan->result_array() as $totalHour) : ?>  
            <?php endforeach; ?>
            <?php foreach ($hitungTotalAVHourPerbulan->result_array() as $totalAVHour) : ?>  
            <?php endforeach; ?>
            <tr>
                <th class="align-middle text-center" rowspan="3">No</th>
                <th class="align-middle text-center" rowspan="3">Customer</th>
                <th class="align-middle text-center" colspan="36" style="background:#1ab394;color: white;text-align: center;">Bulan</th>
            </tr>
            <tr>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Jan</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Feb</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Mar</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Apr</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">May</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Jun</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Jul</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Aug</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Sep</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Okt</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Nov</th>
                <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Des</th>
            </tr>
            <tr>
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>Hours</center>
                    </th>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>%</center>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $no = 0;
            $sum = 0;
            foreach ($getTotalByMonthByCustomer->result_array() as $data) {
                $no++;
                echo '<tr>';
                echo '<td>' . $no;
                '</td>';
                echo '<td>' . $data['customer'];
                '</td>';
                for ($i = 1; $i <= 12; $i++) {
                    //$sum += $data['tot_hours' . $i];
                    $persen = ($totalHour['tot_hours'.$i] != 0) ? ($data['tot_hours'.$i] / $totalHour['tot_hours'.$i]) *100 : 0;
                    $persen_fix = round($persen,2);
                    echo '<td>' . round($data['tot_hours' . $i], 1) . '</d>';
                    echo '<td>' . $persen_fix . '</td>';
                    
                }
                echo '</tr>';
            }
            ?>
            <?php  
            foreach ($hitungTotalHourPerbulan->result_array() as $data2) {
                echo '<tr>';
                echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                echo '<td style="background:#1ab394;color: white;text-align: center;">T. Machine Use (Hours)</td>';
                for ($i = 1; $i <= 12; $i++) {
                    if ($data2['tot_hours' . $i] != 0) {
                        echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($data2['tot_hours' . $i], 1) . '</td>';
                        echo '<td style="background:#1ab394;color: white;text-align: center;">100</td>';
                    } else {
                        echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($data2['tot_hours' . $i], 1) . '</td>';
                        echo '<td style="background:#1ab394;color: white;text-align: center;">0</td>';
                    }
                }
                echo '</tr>';
            }
            ?>
            <?php  
            foreach ($hitungTotalLTPerbulan->result_array() as $dataLT) {
                echo '<tr>';
                echo '<td style="background:#1ab394;color: white;text-align: center;"></td>';
                echo '<td style="background:#1ab394;color: white;text-align: center;">Total Machine Losstime</td>';
                for ($i = 1; $i <= 12; $i++) {
                    echo '<td style="background:#1ab394;color: white;text-align: center;">' . round($dataLT['losstime' . $i], 2) . '</td>';
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
                }
                echo '</tr>';
            }
            ?>
            <tr>
                <td style="background:#1ab394;color: white;text-align: center;"></td>
                <td style="background:#1ab394;color: white;text-align: center;">Total Machine Use (%)</td>
                <?php  
                for ($i = 1; $i <= 12; $i++) {
                    $persen = ($totalAVHour['total_avhour'.$i] != 0) ? ($totalHour['tot_hours'.$i] / $totalAVHour['total_avhour'.$i]) *100 : 0;
                    $persen_fix = round($persen,2);
                    echo '<td style="background:#1ab394;color: white;text-align: center;">' . $persen_fix . '</td>';
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