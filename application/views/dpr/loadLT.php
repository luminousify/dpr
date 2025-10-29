<?php $i = -1; $no = 0; foreach($data_productionLT->result() as $lt) {  $no++; $i++?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo base_url('c_new/delete_detail_dl/'.$lt->id_DL.'/'.$lt->id_production.'/LT') ?>" class="btn btn-sm btn-light border border-dark delete" style="font-size:14px">X
                                                        </td>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $lt->nama; ?></td>
                                                    <td><?= $lt->kategori; ?></td>
                                                    <td><?= $lt->qty; ?> <input type="hidden" value="<?= $lt->qty; ?>" class="nilai"></td>
                                                    <td><?= $lt->satuan; ?></td>
                                                </tr>
                                            <?php } ?>