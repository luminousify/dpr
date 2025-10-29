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
                        <?php echo form_open('c_new/' . $action . '/t_' . $identifikasi . '_mesin/master_mesin'); ?>
                        <!-- ke function add / nama_table / redirect kemana -->
                        <table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" id="customFields" style="width: 100%">
                            <tr style="background:#1ab394;color: white;text-align: center;">
                                <td><?= $identifikasi; ?> mesin</td>

                                <td><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                            </tr>
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

    function addMoreRows(frm) {
        save++;
        var recRow = '<tr><td><input type="text" name="user[' + save + '][<?= $identifikasi; ?>_mesin]" class="form-control"  ></td>' +
            '<td><center><a href="javascript:void(0);" class="remCF"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></td><tr/>';
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