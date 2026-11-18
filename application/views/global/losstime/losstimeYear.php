<div class="table-responsive">

<div class="card rounded">
    <div id="container3" class="p-3"></div>
</div>

<br/>
<table class="table table-bordered" id="datatable3" style="display: none;">
                <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Losstime (Hours)</th>
                    <!-- <th>Total Mc. Use (Hours)</th> -->
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data_DefectGrafikYear->result_array() as $data)
                        { 
                             echo '<tr>';
                             echo '<th>'.$data['bulan'].'</th>';
                             echo '<td>'.round($data['totalLT'],1).'</td>';
                             // echo '<td>'.round($data['total_machine_use'],1).'</td>';
                            echo '</tr>';
                        }?>

                </tbody>
            </table>


    <br/>
         <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                   <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Total Loss Time (Hours)</th>
                        <th>Total Mc. Use (Hours)</th>
                        <th>Target (%)</th>
                        <th>Persentase Loss Time (%)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0; $total_ng = 0;
                        
                        // Prepare target data mapping for each month
                        $target_month_mapping = array();
                        if (isset($targets_by_month) && $targets_by_month instanceof CI_DB_result) {
                            foreach($targets_by_month->result_array() as $target) {
                                $target_month_mapping[$target['bulan']] = $target['total_target'];
                                // Debug: Uncomment to see what targets are being loaded
                                // echo "<!-- Debug: Month " . $target['bulan'] . " has target: " . $target['total_target'] . " -->";
                            }
                        }
                        // Debug: Uncomment to see current year
                        // echo "<!-- Debug: Current year: " . $tahun . " -->";
                        
                        $no = 0 ; foreach($data_DefectGrafikYear->result_array() as $data)
                        {  $no++;
                            // Get target for this month
                            $month_num = intval($data['bulan']);
                            $target_value = isset($target_month_mapping[$month_num]) ? $target_month_mapping[$month_num] : 0;
                            
                            echo '<tr>';
                            echo '<td>'.$data['bulan'],'</td>';
                            echo '<td>'.round($data['totalLT'],1),'</td>';
                            echo '<td>'.round($data['total_machine_use'],1).'</td>';
                            echo '<td>'.number_format($target_value, 2).'</td>';
                            echo '<td>'.round($data['persen_lt'],2).'</td>';
                            echo '</tr>';


                        }  ?>
                    </tbody>
                </table>

</div>