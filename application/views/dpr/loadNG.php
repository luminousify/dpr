<?php $i = -1; $no = 0; foreach($data_productionNG->result() as $ng) {  $no++; $i++?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo base_url('c_new/delete_detail_dl/'.$ng->id_DL.'/'.$ng->id_production.'/NG') ?>" class="btn btn-sm btn-light border border-dark delete" style="font-size:14px">X

                                                    </td>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $ng->nama; ?></td>
                                                    <td><?= $ng->kategori; ?></td>
                                                    <td><?= $ng->qty; ?> <input type="hidden" value="<?= $ng->qty; ?>" class="nilai"></td>
                                                    <td><?= $ng->satuan; ?></td>
                                                </tr>
                                            <?php } ?>