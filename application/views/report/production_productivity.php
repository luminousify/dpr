<div class=" card rounded">
    <div id="container2"></div>
                <br/>

        <table class="table table-bordered" id="datatable1" style="display: none;">
                <thead>
                <tr>
                    <th></th>
                    <th>Net Prod Aktual (%)</th>
                    <th>Gross Prod Aktual (%)</th>
                    <th>Net Prod Target (%)</th>
                    <th>Gross Prod Target (%)</th>
                </tr>
                </thead>
                <tbody>
                    <?php $total = 0; $total_gross = 0;
                        $no = 0 ; foreach($data_productionGrafik->result_array() as $data)
                        {  $no++;
                            $namaBulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                             for($i=1;$i<=12;$i++) {
                             echo '<tr>';
                             echo '<th>'.$namaBulan[$i].'</th>';
                               //$rata=($data['MP2s']!=0)?($data['MP2s']/$data['MP2']) * 100:0;
                               // $nett = ($data['nett'.$i] != 0) ? ($data['ct_std'.$i]/$data['nett'.$i])*100 : 0;
                               // echo '<td>'.number_format($nett,2).'</td>';
                               // $gross = ($data['nett'.$i] != 0) ? ($data['ct_std'.$i]/$data['gross'.$i])*100 : 0;
                               // $gross_fix = round($gross,2);
                               echo '<td>'.round($data['persen_nett'.$i],2).'</td>';
                               echo '<td>'.round($data['persen_gross'.$i],2).'</td>';
                               echo '<td>'.$data['target_nett'].'</td>';
                               echo '<td>'.$data['target_gross'].'</td>';
                            echo '</tr>'; 
                            //$total += $nett;
                            //$total_gross += $nett;
                            }
                            

                        }?>

                </tbody>
            </table>
            <br/><br/>
<?php $namaBulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"); ?>
            <div class="table-responsive">
                <table class="table table-bordered dataTables-example3">
                <thead>
                <tr>
                    <th></th>
                    <?php for($i=1;$i<=12;$i++) { ?>
                    <th><?= $namaBulan[$i]; ?></th>
                    <?php } ?>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                     <?php foreach($data_productionGrafik->result_array() as $data)
                        {  
                             echo '<tr>';
                             echo '<td>Net Productivity Aktual (%)</td>';
                              for($i=1;$i<=12;$i++) 
                            {
                               //$nett = ($data['nett'.$i] != 0) ? ($data['ct_std'.$i]/$data['nett'.$i])*100 : 0;
                               echo '<td>'.round($data['persen_nett'.$i],2).'</td>';
                            }
                             echo '<td>'.number_format($total,2).'</td>';
                             echo '</tr>';
                        } ?>
                    <?php foreach($data_productionGrafik->result_array() as $data)
                        {   
                             echo '<tr>';
                             echo '<td>Gross Productivity Aktual (%)</td>';
                              for($i=1;$i<=12;$i++) 
                            {
                               //$gross = ($data['nett'.$i] != 0) ? ($data['ct_std'.$i]/$data['gross'.$i])*100 : 0;
                               echo '<td>'.round($data['persen_gross'.$i],2).'</td>';
                            }
                            echo '<td>'.number_format($total_gross,2).'</td>';
                             echo '</tr>';
                        } 
                        echo '<tr>';
                        echo '<td>Net Prod Target (%)</td>';
                            for($i=1;$i<=12;$i++) 
                            {  
                               echo '<td>'.$data['target_nett'].'</td>';
                               
                            }
                            echo "<td></td>";
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>Gross Prod Target (%)</td>';
                            for($i=1;$i<=12;$i++) 
                            {  
                               echo '<td>'.$data['target_gross'].'</td>';
                               
                            }
                            echo "<td></td>";
                            echo '</tr>';
                            ?>
                </tbody>
            </table>
        </div>
</div>

            <br/><br/>