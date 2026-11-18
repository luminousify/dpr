<title>DPR | Master Mesin <?= $action; ?></title>
<?php $this->load->view('layout/sidebar'); ?>


<style>
    th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        width: auto;
        /*width: 1000px;*/
        height: auto;
        margin: 0 auto;
    }

    tr {
        background-color: white
    }
    }
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><?= $action; ?> Master Data Mesin</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <?php
                        $form_table = ($action == 'Edit') ? $table : 't_' . $identifikasi . '_mesin';
                        echo form_open('c_new/' . $action . '/' . $form_table . '/master_mesin');
                        ?>
                        <!-- ke function add / nama_table / redirect kemana -->
                        <table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" id="customFields" style="width: 100%">
                            <tr style="background:#1ab394;color: white;text-align: center;">
                                <td><?= ($action == 'Edit') ? 'No' : $identifikasi; ?> mesin</td>
                                <td>Tonnase</td>
                                <td>Aktif</td>
                                <td>Divisi</td>
                                <td>Nama Mesin</td>
                                <td><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                            </tr>
                            <?php if ($action == 'Edit'): ?>
                                <?php foreach ($data_tabel->result() as $row): ?>
                                <tr>
                                    <td>
                                        <input type="text" name="user[0][no_mesin]" value="<?= $row->no_mesin; ?>" class="form-control">
                                        <input type="hidden" name="id" value="<?= $row->id_no_mesin; ?>">
                                        <input type="hidden" name="where" value="id_no_mesin">
                                    </td>
                                    <td><input type="text" name="user[0][tonnase]" value="<?= $row->tonnase; ?>" class="form-control"></td>
                                    <td>
                                        <select class="form-control" name="user[0][aktif]">
                                            <option value="1" <?= $row->aktif == 1 ? 'selected' : ''; ?>>Aktif</option>
                                            <option value="0" <?= $row->aktif == 0 ? 'selected' : ''; ?>>Tidak Aktif</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="user[0][divisi]" value="<?= $row->divisi; ?>" class="form-control" readonly></td>
                                    <td>
                                        <select class="form-control" name="user[0][id_nama_mesin]">
                                            <option value="">-- Select Machine Name --</option>
                                            <?php foreach ($machine_names as $mn): ?>
                                                <option value="<?= $mn['id_nama_mesin']; ?>" <?= $row->id_nama_mesin == $mn['id_nama_mesin'] ? 'selected' : ''; ?>><?= $mn['nama_mesin']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>

                        <input type="submit" name="simpan" value="<?= $action; ?>" class="btn btn-success">
                        <?php echo form_close(); ?>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>






<?php $this->load->view('layout/footer'); ?>

<!-- Page-Level Scripts -->

<script>
    var save = -1;
    var machineNames = <?php echo json_encode($machine_names); ?>; // Pass PHP array to JavaScript

    function addMoreRows(frm) {
        save++;
        var recRow = `<tr>
            <td><input type="text" name="user[${save}][<?= $identifikasi; ?>_mesin]" class="form-control"></td>
            <td><input type="text" name="user[${save}][tonnase]" class="form-control"></td>
            <td><select class="form-control" name="user[${save}][aktif]">
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</select></td>
            <td><input type="text" name="user[${save}][divisi]" class="form-control" readonly value="<?= $_SESSION['divisi'] ?>"></td>
            <td>
                <select class="form-control" name="user[${save}][id_nama_mesin]">
                    <option value="">-- Select Machine Name --</option>
                    ${machineNames.map(machine => `<option value="${machine.id_nama_mesin}">${machine.nama_mesin}</option>`).join('')}
                </select>
            </td>
            <td><center><a href="javascript:void(0);" class="remCF"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></center></td>
        </tr>`;
        jQuery('#customFields').append(recRow);
    }

    $("#customFields").on('click', '.remCF', function() {
        $(this).parent().parent().remove();
    });

    function keyupct(ct) {
        var ct_mc = parseFloat($('#ct_mc' + ct).val());
        var ct_mp = parseFloat($('#ct_mp' + ct).val());
        var hitung_jam = Math.floor(3600 / (ct_mc + ct_mp));
        $('#tg_jam' + ct).val(hitung_jam);
        var hitung_shift = hitung_jam * 7;
        $('#tg_shift' + ct).val(hitung_shift);
    }
</script>