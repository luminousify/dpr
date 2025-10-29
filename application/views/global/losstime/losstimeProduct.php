<div class="table-responsive">

<div class="card rounded">
    <div id="container4" class="p-3"></div>
</div>
<br/>
<table class="table table-bordered" id="datatable4" style="display: none;" >
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Loss Time Product (Hours)</th>
                    <!-- <th>Total Mc. Use (Hours)</th> -->
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data_DefectGrafikProductLimit->result_array() as $data)
                        { 
                             echo '<tr>';
                             echo '<th>'.$data['kode_product'].'<br/>'.$data['nama_product'].'</th>';
                             echo '<td>'.round($data['maxLT'],1).'</td>';
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
                        <th class="align-middle text-center">Bulan-Tahun</th>
                        <th class="align-middle text-center">Kode Product</th>
                        <th class="align-middle text-center">Nama Product</th>
                        <th class="align-middle text-center">Loss Time Product<br>(Hours)</th>
                        <th class="align-middle text-center">Total Mc. Use<br>(Hours)</th>
                        <th class="align-middle text-center">Persentase Loss Time<br>(%)</th>
                        <th class="align-middle text-center">View Detail<br> Loss Time</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $view_detail = base_url('c_report/view_detail_lt_byproduct');
                        $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($data_DefectGrafikProduct->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['bulan'].'-'.$data['tahun'],'</td>';
                            echo '<td>'.$data['kode_product'],'</td>';
                            echo '<td>'.$data['nama_product'],'</td>';
                            echo '<td>'.round($data['totalLT'],1),'</td>';
                            echo '<td>'.$data['total_machine_use'],'</td>';
                            echo '<td>'.round($data['persen_lt'],2),'</td>';
                            echo '<td><center><a href="'.$view_detail.'/'.$data['kode_product'].'/'.$bulan.'"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-search"></i></button></a></td>';
                            echo '</tr>';


                        }  ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Bulan-Tahun</th>
                        <th>Kode Product</th>
                        <th>Nama Product</th>
                        <th>Loss Time Product<br>(Hours)</th>
                        <th>Total Mc. Use<br>(Hours)</th>
                        <th>Persentas Loss Timee<br>(%)</th>
                        <th>View Detail<br> Loss Time</th>
                    </tr>
                    </tfoot>
                </table>
</div>