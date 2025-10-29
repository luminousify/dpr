<div class=" card rounded">
    <div id="container4"></div>
</div>
    <br/>

        <table class="table table-bordered" id="datatable4" style="display: none;">
                <thead>
                <tr>
                    <th></th>
                    <th>Ton 10</th>
                    <th>Ton 40</th>
                    <th>Ton 60</th>
                    <th>Ton 80</th>
                    <th>Ton 90</th>
                    <th>Ton 100</th>
                    <th>Ton 110</th>
                    <th>Ton 120</th>
                    <th>Ton 140</th>
                    <th>Ton 160</th>
                    <th>Ton 180</th>
                    <th>Ton 100</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 0 ; foreach($grafik_total_machine_use_byton->result_array() as $data)
                        {  $no++;                           
                               echo '<tr>';
                               echo '<th></th>';
                               echo '<td>'.round($data['ton10'],1).'</td>';
                               echo '<td>'.round($data['ton40'],1).'</td>';
                               echo '<td>'.round($data['ton60'],1).'</td>';
                               echo '<td>'.round($data['ton80'],1).'</td>';
                               echo '<td>'.round($data['ton90'],1).'</td>';
                               echo '<td>'.round($data['ton100'],1).'</td>';
                               echo '<td>'.round($data['ton110'],1).'</td>';
                               echo '<td>'.round($data['ton120'],1).'</td>';
                               echo '<td>'.round($data['ton140'],1).'</td>';
                               echo '<td>'.round($data['ton160'],1).'</td>';
                               echo '<td>'.round($data['ton180'],1).'</td>';
                               echo '<td>'.round($data['ton200'],1).'</td>';
                            echo '</tr>'; 
                        }?>

                </tbody>
            </table>
            <br/>
            <br/>
<div class="table-responsive">
         <table class="table table-striped table-bordered table-hover dataTables-example">
                   <thead>
                        <tr>
                            <?php foreach ($avail_capacity_machine->result_array() as $acm) : ?>  
                            <?php endforeach; ?>
                            <?php foreach ($getTotalAvHour->result_array() as $av_hour) : ?>  
                            <?php endforeach; ?>
                            <?php foreach ($total_machine_byton->result_array() as $tmt) : ?>  
                            <?php endforeach; ?>
                            <th class="align-middle text-center" rowspan="4" >No</th>
                            <th class="align-middle text-center" rowspan="4">Customer</th>
                        </tr>
                        <tr>
                            <th class="align-middle text-center" colspan="2"  style="background:#1ab394;color: white;text-align: center;">Tons 10</th>
                            <th class="align-middle text-center" colspan="2"  style="background:#1ab394;color: white;text-align: center;">Tons 40</th>
                            <th class="align-middle text-center" colspan="2"  style="background:#1ab394;color: white;text-align: center;">Tons 60</th>
                            <th class="align-middle text-center" colspan="2"  style="background:#1ab394;color: white;text-align: center;">Tons 80</th>
                            <th class="align-middle text-center" colspan="2"  style="background:#1ab394;color: white;text-align: center;">Tons 90</th>
                            <th class="align-middle text-center" colspan="2"  style="background:#1ab394;color: white;text-align: center;">Tons 100</th>
                            <th class="align-middle text-center" colspan="2"  style="background:#1ab394;color: white;text-align: center;">Tons 110</th>
                            <th class="align-middle text-center" colspan="2"  style="background:#1ab394;color: white;text-align: center;">Tons 120</th>
                            <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Tons 140</th>
                            <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Tons 160</th>
                            <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Tons 180</th>
                            <th class="align-middle text-center" colspan="2" style="background:#1ab394;color: white;text-align: center;">Tons 200</th>
                        </tr>
                        <tr>
                        <?php for($i=1;$i<=12;$i++) { ?>
                            <th style="background:#1ab394;color: white;text-align: center;" colspan="2"><center>Av. Hour = <?php echo $av_hour['av'.$i]; ?></center></th>
                        <?php } ?> 
                        </tr>
                        <tr>
                        <?php for($i=1;$i<=12;$i++) { ?>
                            <th style="background:#1ab394;color: white;text-align: center;"><center>Mc. Use (Hour)</center></th>
                            <th style="background:#1ab394;color: white;text-align: center;"><center>%</center></th>
                        <?php } ?> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $total = 0;
                                $no = 0 ; foreach($total_machine_use_byton_bycustomer->result_array() as $data)
                                {  $no++;
                                    echo '<tr>';
                                    echo '<td>'.$no;'</td>'; 
                                    echo '<td>'.$data['customer'];'</td>';
                                    for($i=1;$i<=12;$i++) 
                                    {
                                        $persen = ($tmt['ton'.$i] != 0) ? ($data['t'.$i] / $av_hour['av'.$i]) *100 : 0;
                                        $persen_fix = round($persen,2);
                                        echo '<td>'.round($data['t'.$i],1).'</th>';
                                        echo '<td>'.$persen_fix.'</th>';
                                    }
                                    // echo '<td>'.round($data['t1'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t2'],2),'</td>';
                                    // echo '<td>'.$data['t2'];'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t3'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t4'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t5'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t6'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t7'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t8'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t9'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t10'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t11'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.round($data['t12'],2),'</td>';
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '<td>'.$total;'</td>'; 
                                    // echo '</tr>';
                                } 
                                // $total += $data['total'] 
                                ?>
                    </tbody>
                </table>

</div>