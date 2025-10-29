<?php foreach ($avail_capacity_machine->result_array() as $acm) : ?>  
<?php endforeach; ?>
<?php foreach ($get_total_machine->result_array() as $tmt) : ?>  
<?php endforeach; ?>
<div class="row">
    <div class="col-md-6">
        <div class=" card rounded">
            <div id="container7"></div>
        </div>
        <br/>
            <table class="table table-bordered" id="datatable7" style="display: none">
                <thead>
                    <tr>
                        <th></th>
                        <th>Total Machine Use (Hours)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 0 ; foreach($grafik_machine_use_bycustomer_bymonth->result_array() as $data)
                            {  $no++;                           
                                    echo '<tr>';
                                        echo '<th>'.$data['customer'].'</th>';
                                        echo '<td>'.round($data['machine_use'],1).'</td>';
                                    echo '</tr>'; 
                            }?>

                </tbody>
        </table>
    </div>

    <div class="col-md-6">
    <div class=" card rounded">
        <div id="container8"></div>
    </div>
    <br/>
        <table class="table table-bordered" id="datatable8" style="display: none">
            <thead>
                <tr>
                    <th></th>
                    <th>Persentase (%)</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 0 ; foreach($grafik_machine_use_bycustomer_bymonth->result_array() as $data)
                        {  $no++;                           
                                echo '<tr>';
                                    echo '<th>'.$data['customer'].'</th>';
                                    echo '<td>'.round($data['persen'],2).'</td>';
                                echo '</tr>'; 
                        }?>

            </tbody>
        </table>
    </div>
</div>
<br/>
<br/>

<div class="table-responsive">
         <table class="table table-striped table-bordered table-hover dataTables-example2" style="width: 100%">
                   <thead>
                        <tr>
                            <th class="align-middle text-center">No</th>
                            <th class="align-middle text-center">Tahun</th>
                            <th class="align-middle text-center">Bulan</th>
                            <th class="align-middle text-center">Customer</th>
                            <th class="align-middle text-center">Total MC. Use<br>1 Bulan (Hours)</th>
                            <th class="align-middle text-center" style="background:#5bc0de;color: white;">Machine Use <br>(Hours)</th>
                            <th class="align-middle text-center" style="background:#5bc0de;color: white;">Machine Use <br>(%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $total = 0;
                                $no = 0 ; foreach($grafik_machine_use_bycustomer_bymonth->result_array() as $data)
                                {  $no++;
                                    echo '<tr>';
                                    echo '<td>'.$no;'</td>'; 
                                    echo '<td>'.$data['tahun'];'</td>';
                                    echo '<td>'.$data['bulan'];'</td>';
                                    echo '<td>'.$data['customer'];'</td>';
                                    echo '<td>'.$data['total_mc_hour'];'</td>';
                                    echo '<td style="background:#5bc0de;color: white;">'.round($data['machine_use'],1);'</td>';
                                    echo '<td style="background:#5bc0de;color: white;">'.round($data['persen'],2);'</td>';
                                } 
                            
                                ?>
                    </tbody>
                </table>

</div>