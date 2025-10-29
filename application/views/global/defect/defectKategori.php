<div class="table-responsive">
<div class="card rounded p-2">
    <div id="container2" class="p-3"></div>
</div>

<br/>
<table class="table table-bordered" id="datatable1" style="display: none;">
                <thead>
                <tr>
                    <th></th>
                    <th>Total (Pcs)</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data_DefectGrafik->result_array() as $data)
                        { 
                             echo '<tr>';
                             echo '<th>'.$data['kategori'].'</th>';
                             echo '<td>'.$data['qtyNG'].'</td>';
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
                        <th>Total (Pcs)</th>
                        <th>View Detail Defect</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0; $total_ng = 0;
                        $view_detail = base_url('c_report/view_detail_ng');
                        $no = 0 ; foreach($data_DefectGrafik->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['kategori'],'</td>';
                            echo '<td>'.$data['qtyNG'],'</td>';
                            echo '<td><center><a href="'.$view_detail.'/'.$data['kategori'].'/'.$bulan.'"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-search"></i></button></a></td>';
                            echo '</tr>';


                        }  ?>
                    </tbody>
                     <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Total (Pcs)</th>
                        <th>View Detail Defect</th>
                    </tr>
                    </tfoot>
                </table>

</div>