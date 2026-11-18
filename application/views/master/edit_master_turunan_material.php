<title>DPR | Edit Master Turunan Material</title>
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
                    <h5>Edit Master Data Turunan Material</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                
                <div class="ibox-content">
                    <div class="table-responsive">
                        <?php echo form_open('c_new/update_master_turunan_material'); ?> 
                        <table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" style="width: 100%">
                            <tr style="background:#1ab394;color: white;text-align: center;">
                                <td>Kode Product</td>
                                <td>Nama Product</td>
                                <td>Atas</td>
                                <td>Bawah</td>
                            </tr>
                            
                            <?php 
                            foreach($data_turunan_material->result() as $data):
                            echo '<tr>';
                            echo '<input type="hidden" name="id" value="'.$data->id.'">';
                            echo '<td><input type="text" name="kode_product" value="'.$data->kode_product.'" class="form-control" required></td>';
                            echo '<td><input type="text" name="nama_product" value="'.$data->nama_product.'" class="form-control" required></td>';
                            echo '<td><input type="number" name="atas" value="'.$data->atas.'" class="form-control"></td>';
                            echo '<td><input type="number" name="bawah" value="'.$data->bawah.'" class="form-control"></td>';
                            echo '</tr>';
                            endforeach; ?>
                        </table>
                        
                        <div class="d-flex justify-content-center">
                            <input type="submit" name="simpan" value="Update" class="btn btn-success">
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
    // Add any necessary JavaScript here
</script>
