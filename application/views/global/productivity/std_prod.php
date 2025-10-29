<div class="table-responsive">
             <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                   <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>CT Quo</th>
                        <th>Nett Prod (Sec)</th>
                        <th>Gross Prod (Sec)</th>
                        <th>% Nett Prod.</th>
                        <th>% Gross Prod.</th>
                        <th>View Detail By Part</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $view_detail = base_url('c_report/view_detail_bypart');
                        $total = 0; $total_ng = 0;
                        $no = 0 ; foreach($detail_productivity_bypart_bymonth->result_array() as $data)
                        {  $no++;
                            echo '<tr>';
                            echo '<td>'.$no;'</td>';
                            echo '<td>'.$data['kode_product'],'</td>';
                            echo '<td>'.$data['nama_product'],'</td>';
                            echo '<td>'.$data['cyt_quo'],'</td>';
                            echo '<td>'.round($data['nett_prod'],2),'</td>';
                            echo '<td>'.round($data['gross_prod'],2),'</td>';
                            echo '<td>'.round($data['persen_nett'],2),'</td>';
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
                        <th>CT Quo</th>
                        <th>Nett Prod (Sec)</th>
                        <th>Gross Prod (Sec)</th>
                        <th>% Nett Prod.</th>
                        <th>% Gross Prod.</th>
                        <th>View Detail By Part</th>
                    </tr>
                    </tfoot>
                </table>

    </div>
