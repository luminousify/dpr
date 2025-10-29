<script src="<?= base_url(); ?>template/js/jquery-3.1.1.min.js"></script>
<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: auto;
        height: auto;
        margin: auto;
    }
    tr {background-color: white} 
} 
</style> 
<?= form_open('c_machine/add_totalMP'); ?>
<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger">
    <?= $this->session->flashdata('error') ?>
</div>
<?php endif; ?>
<div class="row">
    <div class="col">
        <b>Tanggal : </b>
        <input type="date" name="user[0][tanggal]" class="form-control">
    </div>
    <div class="col">
        <b>Shift : </b>
         <select name="user[0][shift]" class="form-control">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
    </div>
    <div class="col">
        <b>Total MP Hadir : </b>
        <input type="text" name="user[0][total]" class="form-control">
        <input type="hidden" name="user[0][pic]" class="form-control" value="<?= $data['user_name']; ?>">
        
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <b>Total MP Tidak Hadir : </b>
        <input type="text" name="user[0][total_tidak_hadir]" class="form-control">        
    </div>
    <div class="col">
        <b>Total Sisa MP Hadir : </b>
        <input type="text" name="user[0][sisa_mp_hadir]" class="form-control">        
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <b>Keterangan MP Tidak Hadir : </b>
        <textarea class="form-control" name="user[0][keterangan]" rows="3"></textarea>     
    </div>
    <div class="col">
        <b>Keterangan Sisa MP : </b>
        <textarea class="form-control" name="user[0][keterangan_sisa]" rows="3"></textarea>     
    </div>
</div>
<div class="row mt-4">
    <div class="col-sm-12 d-flex justify-content-center">
        <br/>
        <input type="submit" name="simpan" class="btn btn-success">
    </div>
</div>
<?= form_close(); ?>
<br/>
<hr>
<div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable1" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Total MP Hadir</th>
                                    <th>Total MP Tidak Hadir</th>
                                    <th>Total Sisa MP Hadir</th>
                                    <th>Keterangan Tidak Hadir</th>
                                    <th>Keterangan Sisa Hadir</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                            </thead>
                            <tbody>
                                <?php 
                                //$delete     = base_url('c_machine/delete_total_mp');
                                $id = -1; 
                                $i = 1 ; 
                                foreach($data_tabelTotalMP->result_array() as $data)
                                {  $id++; ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?= $data['tanggal']; ?></td>
                                    <td><?= $data['shift']; ?></td>
                                    <td><?= $data['total']; ?></td>
                                    <td><?= $data['total_tidak_hadir']; ?></td>
                                    <td><?= $data['sisa_mp_hadir']; ?></td>
                                    <td><?= $data['keterangan']; ?></td>
                                    <td><?= $data['keterangan_sisa']; ?></td>
                                    <td style="text-align: center;" class="align-middle"><button class="btn btn-sm btn-info btn-circle bi bi-pen-fill mr-1 btn-edit" data-id="<?= $data['id_mp']?>" data-tanggal="<?= $data['tanggal']?>" data-shift="<?= $data['shift']?>" data-total="<?= $data['total']?>" data-total_tidak_hadir="<?= $data['total_tidak_hadir']?>" data-total_sisa_mp_hadir="<?= $data['sisa_mp_hadir']?>" data-keterangan="<?= $data['keterangan']?>" data-keterangan_sisa="<?= $data['keterangan_sisa']?>"><center><i class="fa fa-pencil"></i></center></button></td>
                                    <td><a href="<?php echo base_url('c_machine/delete_total_mp/'.$data['id_mp']) ?>"><center><button class="btn btn-danger btn-circle delete"><i class="fa fa-trash"></i></button></center></a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Total MP Hadir</th>
                                    <th>Total MP Tidak Hadir</th>
                                    <th>Sisa MP Hadir</th>
                                    <th>Keterangan Tidak Hadir</th>
                                    <th>Keterangan Sisa Hadir</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </tfoot>
                        </table>
                     </div>
<form action="<?php echo base_url('c_machine/update_mp') ?>" method="post">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update MP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach ($data_tabelTotalMP->result_array() as $data_modal) : ?>  
                <?php endforeach; ?>
                <input type="hidden" class="id" name="id" id="id">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Tanggal</label>
                        <input type="date" class="form-control tanggal" id="tanggal" name="tanggal" value="<?php echo $data_modal['tanggal'] ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Shift</label>
                        <select class="form-control shift" id="shift" name="shift">
                            <option <?php if( $data_modal['shift'] =='1'){echo "selected"; } ?> value='1'>1</option>
                            <option <?php if( $data_modal['shift'] =='2'){echo "selected"; } ?> value='2'>2</option>
                            <option <?php if( $data_modal['shift'] =='3'){echo "selected"; } ?> value='3'>3</option>
                        </select>
                        <!-- <input type="text" class="form-control shift" id="shift" name="shift" value="<?php //echo $data_modal['shift'] ?>"> -->
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Total MP Hadir</label>
                        <input type="number" class="form-control total" id="total" name="total" value="<?php echo $data_modal['total'] ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Total MP Tidak Hadir</label>
                        <input type="number" class="form-control total_tidak_hadir" id="total_tidak_hadir" name="total_tidak_hadir" value="<?php echo $data_modal['total_tidak_hadir'] ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Total Sisa MP Hadir</label>
                        <input type="number" class="form-control total_sisa_mp_hadir" id="total_sisa_mp_hadir" name="sisa_mp" value="<?php echo $data_modal['sisa_mp_hadir'] ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Keterangan</label>
                        <textarea type="text" class="form-control keterangan" id="keterangan" name="keterangan" rows="2"><?php echo $data_modal['keterangan'] ?></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Keterangan</label>
                        <textarea type="text" class="form-control keterangan_sisa" id="keterangan_sisa" name="keterangan_sisa" rows="2"><?php echo $data_modal['keterangan_sisa'] ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
                
            </div>
            
            </div>
        </div>
        </div>
    </form>
<div class="modal fade" id="Mymodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button> 
                    <h4 class="modal-title">Notification</h4>                                                             
                </div> 
                <div class="modal-body">
                    Are you sure you want to continue?
                </div>   
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                               
                </div>
            </div>                                                                       
        </div>                                          
    </div>

<script>


    $(document).ready(function() {
            $('.delete').click(function() {
            return confirm("Are you sure you want to delete?");
            });
        });


    $(document).ready(function(){
 
        // get Edit Product
        $('.btn-edit').click(function(){
            // get data from button edit
            const id                    = $(this).data('id');
            const tanggal               = $(this).data('tanggal');
            const shift                 = $(this).data('shift');
            const total                 = $(this).data('total');
            const total_tidak_hadir     = $(this).data('total_tidak_hadir');
            const total_sisa_mp_hadir   = $(this).data('total_sisa_mp_hadir');
            const keterangan            = $(this).data('keterangan');
            // Set data to Form Edit
            $('.id').val(id);
            $('.tanggal').val(tanggal);
            $('.shift').val(shift);
            $('.total').val(total);
            $('.total_tidak_hadir').val(total_tidak_hadir);
            $('.total_sisa_mp_hadir').val(total_sisa_mp_hadir);
            $('.keterangan').val(keterangan);
            $('#editModal').modal('show');
        });
         
    });
</script>