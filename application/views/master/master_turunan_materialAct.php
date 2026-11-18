<title>DPR | Master Turunan Material <?= $action; ?></title>
<?php $this->load->view('layout/sidebar'); ?>

<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: auto;
        height: auto;
        margin: 0 auto;
    }
    tr {background-color: white} 
}
</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><?= $action; ?> Master Data Turunan Material</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                
                <div class="ibox-content">
                    <div class="table-responsive">
                        <?php echo form_open($action == 'Add' ? 'c_new/Add/master_turunan_material/master_turunan_material' : 'c_new/update_master_turunan_material'); ?> 
                        <!-- ke function add / nama_table / redirect kemana -->
                        <table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" id="customFields" style="width: 100%">
                            <tr style="background:#1ab394;color: white;text-align: center;">
                                <td>Kode Product</td>
                                <td>Nama Product</td>
                                <td>Atas</td>
                                <td>Bawah</td>
                                <td><?= $action == "Add" ? '<span class="btn btn-warning" onclick="addMoreRows(this.form);"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
                            </tr>
                            
                            <?php if($action ==  'Edit') {
                                foreach($data_tabel->result() as $data):
                                echo '<tr>';
                                echo '<td><input type="text" name="kode_product" value="'.$data->kode_product.'" class="form-control"><input type="hidden" name="id" value="'.$data->id.'"></td>';
                                echo '<td><input type="text" name="nama_product" value="'.$data->nama_product.'" class="form-control"></td>';
                                echo '<td><input type="number" name="atas" value="'.$data->atas.'" class="form-control"></td>';
                                echo '<td><input type="number" name="bawah" value="'.$data->bawah.'" class="form-control"></td>';
                                <?php 
                                echo '</tr>';
                                endforeach;
                            } ?>
                        </table>
                        
                        <div class="d-flex justify-content-center">
                            <input type="submit" name="simpan" value="<?= $action; ?>" class="btn btn-success">
                            <a href="<?= base_url('c_new/master_turunan_material'); ?>" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                        
                        <?php echo form_close(); ?>
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
        var recRow = '<tr><td><input type="text" name="user['+save+'][kode_product]" class="form-control" placeholder="Kode Product" required></td>' +
            '<td><input type="text" name="user['+save+'][nama_product]" class="form-control" placeholder="Nama Product" required></td>' +
            '<td><input type="number" name="user['+save+'][atas]" class="form-control" placeholder="Atas" value="0"></td>' +
            '<td><input type="number" name="user['+save+'][bawah]" class="form-control" placeholder="Bawah" value="0"></td>' +
            '<td><a href="javascript:void(0);" class="remCF"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a></td></tr>';
        jQuery('#customFields').append(recRow);
    }

    $("#customFields").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });
</script>
