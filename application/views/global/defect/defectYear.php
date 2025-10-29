<div class="table-responsive">
<div class="card rounded">
    <div id="container3" class="p-3"></div>
</div>
<br/>
<table class="table table-bordered" id="datatable3" style="display: none;">
                <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Total (Pcs)</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data_DefectGrafikYear->result_array() as $data)
                        { 
                             echo '<tr>';
                             echo '<th>'.$data['bulan'].'</th>';
                             echo '<td>'.$data['totalNG'].'</td>';
                            echo '</tr>';
                        }?>

                </tbody>
            </table>


    <br/>
         <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                   <thead>
                    <tr>
                        <th>No</th>
                        <th>Bulan</th>
                        <th>Total (Pcs)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($data_DefectGrafikYear->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['bulan'],'</td>';
                            echo '<td>'.$data['totalNG'],'</td>';
                            echo '</tr>';


                        }  ?>
                    </tbody>
                </table>

</div>