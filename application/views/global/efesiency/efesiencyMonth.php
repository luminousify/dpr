<div class="table-responsive">

<div class="card rounded">
    <div id="container2"></div>
</div>

<br/>
<table class="table table-bordered" id="datatable1" style="display: none;">
                <thead>
                <tr>
                    <th></th>
                    <th>Machine Hours</th>
                    <th>Available Capacity</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data_EffGrafik->result_array() as $data)
                        { 
                             echo '<tr>';
                             echo '<th>'.$data['mesin'].'</th>';
                             echo '<td>'.$data['machine_use'].'</td>';
                             echo '<td>'.$data['total'].'</td>';
                            echo '</tr>';
                        }?>

                </tbody>
            </table>


    <br/>
         <table class="table table-striped table-bordered table-hover dataTables-example2" style="width:100%">
                   <thead>
                    <tr>
                        <th class="text-center align-middle">No</th>
                        <th class="text-center align-middle">MC</th>
                        <th class="text-center align-middle">Av. Capacity <br> (Hours)</th>
                        <th class="text-center align-middle">Prod. Time <br>(Hours)</th>
                        <th class="text-center align-middle">LT <br>(Hours)</th>
                        <th class="text-center align-middle">OT <br>(Hours)</th>
                        <th class="text-center align-middle" class="text-center align-middle">NWT <br>(Hours)</th>
                        <th class="text-center align-middle" class="text-center align-middle" style='background:#5bc0de;color: white;'>Machine Use <br>(Hours)</th>
                        <th class="text-center align-middle" style='background:#5bc0de;color: white;'>Machine Use <br>(%)</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($data_EffGrafik->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>'; 
                            echo '<td>'.$data['mesin'],'</td>';
                            echo '<td>'.$data['total'],'</td>';
                            echo '<td>'.$data['production_time'],'</td>';
                            echo '<td>'.$data['totalLT'],'</td>';
                            echo '<td>'.$data['totalOT'],'</td>';
                            echo '<td>'.$data['totalNWT'],'</td>';
                            echo '<td style="background:#5bc0de;color: white;">'.$data['machine_use'],'</td>';
                            echo '<td style="background:#5bc0de;color: white;">'.$data['machine_use_persen'],'</td>';
                            echo '</tr>';
                        } 
                        //$total += $data['total'] ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th class="text-center align-middle">No</th>
                        <th class="text-center align-middle">MC</th>
                        <th class="text-center align-middle">Av. Capacity <br> (Hours)</th>
                        <th class="text-center align-middle">Prod. Time <br>(Hours)</th>
                        <th class="text-center align-middle">LT <br>(Hours)</th>
                        <th class="text-center align-middle">OT <br>(Hours)</th>
                        <th class="text-center align-middle" class="text-center align-middle">NWT <br>(Hours)</th>
                        <th class="text-center align-middle" class="text-center align-middle" style='background:#5bc0de;color: white;'>Machine Use <br>(Hours)</th>
                        <th class="text-center align-middle" style='background:#5bc0de;color: white;'>Machine Use <br>(%)</th>
                    </tr>
                    </tfoot>
                </table>

</div>