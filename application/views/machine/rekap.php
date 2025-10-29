            <div id="container2"></div>
            <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable1" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Shift 1</th>
                                    <th>Shift 2</th>
                                    <th>Shift 3</th>
                                    <th>Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = -1; $no = 0 ; foreach($data_tabelRekap->result_array() as $data)
                                {  $no++; $id++; ?>
                                <tr>
                                    <th><?= $data['hasil']; ?></th>
                                    <td><?= $data['MP1']; ?></td>
                                    <td><?= $data['MP2']; ?></td>
                                    <td><?= $data['MP3']; ?></td>
                                    <td>0.7</td> 
                                </tr>
                                <?php } ?>
                            </tbody>
            </table>


 