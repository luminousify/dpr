<div class="table-responsive">
<div class="card rounded">
    <div id="container5" class="p-3"></div>
</div>

<br/>
<table class="table table-bordered" id="datatable5" style="display: none;" >
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Total Losss Time (Hours)</th>
                    <!-- <th>Total Mc. Use (Hours)</th> -->
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data_losstime_byname_limit_bymonth->result_array() as $data)
                        { 
                             echo '<tr>';
                             echo '<th>'.$data['nama'].'</th>';
                             echo '<td>'.round($data['qty_lt'],1).'</td>';
                             // echo '<td>'.round($data['total_machine_use'],1).'</td>';
                            echo '</tr>';
                        }?>

                </tbody>
            </table>


    <br/>
    <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                   <thead>
                    <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center" class="align-middle text-center">Nama</th>
                        <th class="align-middle text-center">Kategori</th>
                        <th class="align-middle text-center">Total Loss Time<br> (Hours)</th>
                        <th class="align-middle text-center">Total MC. Use<br> (Hours)</th>
                        <th class="align-middle text-center">Persentase Loss Time<br> (%)</th>
                        <!-- <th>View Detail Loss Time</th> -->
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        //$view_detail = base_url('c_report/view_detail_lt_byproduct');
                        $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($data_losstime_byname_limit_bymonth->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['nama'],'</td>';
                            echo '<td>'.$data['kategori'],'</td>';
                            echo '<td>'.round($data['qty_lt'],1),'</td>';
                            echo '<td>'.$data['total_machine_use'],'</td>';
                            echo '<td>'.round($data['persen_lt'],2),'</td>';
                            //echo '<td><center><a href="'.$view_detail.'/'.$data['kode_product'].'/'.$bulan.'"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-search"></i></button></a></td>';
                            echo '</tr>';


                        }  ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Total Loss Time<br> (Hours)</th>
                        <th>Total MC. Use<br> (Hours)</th>
                        <th>Persentase Loss Time<br> (%)</th>
                        <!-- <th>View Detail Loss Time</th> -->
                    </tr>
                    </tfoot>
                </table>
</div>