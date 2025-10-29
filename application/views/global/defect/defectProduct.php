<div class="table-responsive">
<div class="card rounded">
    <div id="container4" class="p-3"></div>
</div>

<br/>
<table class="table table-bordered" id="datatable4" style="display: none;" >
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Total (Pcs)</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data_DefectGrafikProductLimit->result_array() as $data)
                        { 
                             echo '<tr>';
                             echo '<th>'.$data['kode_product'].'<br/>'.$data['nama_product'].'</th>';
                             echo '<td>'.$data['maxNG'].'</td>';
                            echo '</tr>';
                        }?>

                </tbody>
            </table>


    <br/>
         <table class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%;">
                   <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Kode Product</th>
                        <th>Nama Product</th>
                        <th>Total (Pcs)</th>
                        <th>View Detail Defect</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $view_detail = base_url('c_report/view_detail_ng_byproduct');
                        $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($data_DefectGrafikProduct->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.substr($data['tanggal'],0,4),'</td>';
                            echo '<td>'.substr($data['tanggal'],5,2),'</td>';
                            echo '<td>'.$data['kode_product'],'</td>';
                            echo '<td>'.$data['nama_product'],'</td>';
                            echo '<td>'.$data['totalNG'],'</td>';
                            echo '<td><center><a href="'.$view_detail.'/'.$data['kode_product'].'/'.$bulan.'"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-search"></i></button></a></td>';
                            echo '</tr>';


                        }  ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Kode Product</th>
                        <th>Nama Product</th>
                        <th>Total (Pcs)</th>
                        <th>View Detail Defect</th>
                    </tr>
                    </tfoot>
                </table>

</div>