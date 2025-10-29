<div class="table-responsive">

<div class="card rounded">
    <div id="container2" class="p-3"></div>
</div>

<br/>
<table class="table table-bordered" id="datatable1" style="display: none;">
                <thead>
                <tr>
                    <th></th>
                    <th>LT Kategori (Hours)</th>
                    <!-- <th>Total MC. Use (Hours)</th> -->
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data_DefectGrafik->result_array() as $data)
                        { 
                             echo '<tr>';
                             echo '<th>'.$data['kategori'].'</th>';
                             echo '<td>'.round($data['qtyLT'],1).'</td>';
                             // echo '<td>'.round($data['total_machine_use'],1).'</td>';
                            echo '</tr>';
                        }?>

                </tbody>
            </table>


    <br/>
         <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                   <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Total Loss Time (Hours)</th>
                        <th>Total Mc. Use (Hours)</th>
                        <th>Persentase Loss Time (%)</th>
                        <th>View Detail LT</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0; $total_ng = 0;
                        $view_detail = base_url('c_report/view_detail_lt');
                        $no = 0 ; foreach($data_DefectGrafik->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['kategori'],'</td>';
                            echo '<td>'.round($data['qtyLT'],1),'</td>';
                            echo '<td>'.round($data['total_machine_use'],1).'</td>';
                            echo '<td>'.round($data['persen_lt'],1).'</td>';
                            echo '<td><center><a href="'.$view_detail.'/'.$data['kategori_new'].'/'.$bulan.'"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-search"></i></button></a></td>';
                            echo '</tr>';
                        }  ?>
                    </tbody>
                     <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Total</th>
                        <th>View Detail LT</th>
                    </tr>
                    </tfoot>
        </table>

<!-- Pilih Kategori Losstime <br/>
<select name="kategori" style="width:95%;height: 100%" class="form-control" id="kategori" required=""  >
    <option value="">-Choose-</option>
    <?php// foreach ($ketegori as $b) { echo "<option value='$b[kategori_defect]'>$b[kategori_defect]</option>";}?>
    </select> -->

</div>