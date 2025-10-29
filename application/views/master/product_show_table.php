<table id="example1" class="demo-table3" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Product ID</th>
            <th><b>Product Name</b></th>
            <th><b>Mom Product</b></th>
            <th><b>Cavity</b></th>
            <th><b>Acc Inv</b></th>
            <th><b>Kode Proses</b></th>
            <th><b>Proses</b></th>
            <th><b>Satuan</b></th>
            <th><b>Type</b></th>
            <th><b>Usage</b></th>
            <th><b>Warna</b></th>
            <th><b>Aktif</b></th>
            <th><b>Edit</b></th>
            <th><b>Delete</b></th>
        </tr>
    </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM t_product as p
                    WHERE p.`nama_product` LIKE '%$data%' OR p.`kode_product` LIKE '%$data%'" ;
            $query = $this->db->query($sql);
            foreach ($query->result() as $data)
            {  ?>
        
        <tbody>
                    <?php 
                    $edit       = base_url('c_new/master_productAct/t_product/id_product/');
                    $delete     = base_url('c_new/Delete/master_product/t_product/id_product/');
                        $no = 0 ; foreach($data as $data) 
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data->kode_product,'</td>';
                            echo '<td>'.$data->nama_product,'</td>';
                            echo '<td>'.$data->MomID,'</td>';
                            echo '<td>'.$data->cavity,'</td>';
                            echo '<td>'.$data->AccID,'</td>';
                            echo '<td>'.$data->AccInv,'</td>';
                            echo '<td>'.$data->kode_proses,'</td>';
                            echo '<td>'.$data->nama_proses,'</td>';
                            echo '<td>'.$data->sp,'</td>';
                            echo '<td>'.$data->type,'</td>';
                            echo '<td>'.$data->usage,'</td>';
                            if($data->warna ==  '#99ccff'){ $a = 'Biru';}else if($data->warna == '#ccffcc'){ $a = 'Hijau';}else if($data->warna == '#ffff99'){ $a ='Kuning';}else if($data->warna == '#ffcc99') { $a = 'Orange';}else{ $a = '';}
                            echo '<td>'.$a.'</td>';
                            echo '<td><center><button class="btn btn-warning btn-circle" type="button"><i class="fa fa-check"></i></button><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-times"></i></button></td>';
                            echo '<td><center><a href="'.$edit.'/'.$data->id_product.'"</a><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-pencil-square-o"></i></button></td>';
                            echo '<td><center><a href="'.$delete.'/'.$data->id_product.'" onclick="return confirm(\'Are you sure?\')"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Product ID</th>
                        <th><b>Product Name</b></th>
                        <th><b>Proses ID</b></th>
                        <th><b>Mom Product</b></th>
                        <th><b>Acc ID</b></th>
                        <th><b>Acc Inv</b></th>
                        <th><b>Kode Proses</b></th>
                        <th><b>Proses</b></th>
                        <th><b>Satuan</b></th>
                        <th><b>Type</b></th>
                        <th><b>Usage</b></th>
                        <th><b>Warna</b></th>
                        <th><b>Aktif</b></th>
                        <th><b>Edit</b></th>
                        <th><b>Delete</b></th>
                    </tr>
                </tfoot>
        <?php } ?>
        </tbody>
    </table>

<script type="text/javascript">
    $(document).ready(function() {
    $('#example1').DataTable();
    } );

</script>