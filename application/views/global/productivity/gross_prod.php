<div class="table-responsive">
<table class="table table-striped table-bordered table-hover dataTables-example">
                   <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Product</th> 
                        <th>Nama Product</th>
                        <th>Max SPM Std</th>
                        <th>Max SPM Std2</th>
                        <th>Min SPM Act</th>
                        <th>Max SPM Act</th>
                        <?php for($i=1;$i<=12;$i++) { ?><th style="background:#1ab394;color: white;text-align: center;"><center><?php echo $i; ?></center></th><?php } ?> 
                    </tr>

                    </thead>
                    <tbody>
                        <?php $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($data_production->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['kode_product'],'</td>';
                            echo '<td>'.$data['nama_product'],'</td>';
                            echo '<td style="text-align: center;">'.number_format($data['max_CTStd'],2),'</td>';
                            echo '<td style="text-align: center;">'.number_format($data['max_CTStd2'],2),'</td>';
                            echo '<td style="text-align: center;">'.number_format($data['min_CTSet'],2),'</td>';
                            echo '<td style="text-align: center;">'.number_format($data['max_CTSet'],2),'</td>';
                            for($i=1;$i<=12;$i++) {
                               echo '<td>'.number_format($data['sG'.$i]).'</td>';
                            }
                            echo '</tr>';


                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                        <th>No</th>
                        <th>Kode Product</th> 
                        <th>Nama Product</th>
                        <th>Max SPM Std</th>
                        <th>Max SPM Std2</th>
                        <th>Min SPM Act</th>
                        <th>Max SPM Act</th>
                        <?php for($i=1;$i<=12;$i++) { ?><th style="background:#1ab394;color: white;text-align: center;"><center><?php echo $i; ?></center></th><?php } ?> 
                    </tr>
                    </tfoot>
                    

                </table>
</div>

