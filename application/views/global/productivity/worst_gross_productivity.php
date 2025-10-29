<div class="table-responsive">
                            <div class="col">
                                <div class="card rounded">
                                    <div id="container4"></div>
                                </div>
                            </div>
            <table id="datatable4" class="table table-bordered" style="display: none;">
                <thead>
                    <th></th>
                    <th>Gross Productivity</th>
                </thead>
                <tbody>
                    <?php $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($tampil_worst_gross->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<th>'.$data['kode_product'].'<br/>('.$data['nama_product'].')','</th>';
                            echo '<td>'.round($data['persen_gross']).'</td>';
                            echo '</tr>';
                        } ?>
                </tbody>      
             </table>
             <br>

             <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                   <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Gross Productivity (%)</th>
                        <th>View Detail</th>
                        <!-- <th>View Detail Loss Time</th> -->
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $view_detail = base_url('c_report/view_detail_worst_gross');
                        $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($tampil_worst_gross_all->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['kode_product'],'</td>';
                            echo '<td>'.$data['nama_product'],'</td>';
                            echo '<td>'.round($data['persen_gross'],2),'</td>';
                            echo '<td><center><a href="'.$view_detail.'/'.$data['kode_product'].'/'.$bulan.'"><button class="btn btn-info btn-circle" type="button"><i class="fa fa-search"></i></button></a></td>';
                            echo '</tr>';


                        }  ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Gross Productivity (%)</th>
                        <th>View Detail</th>
                        <!-- <th>View Detail Loss Time</th> -->
                    </tr>
                    </tfoot>
                </table>

    </div>
