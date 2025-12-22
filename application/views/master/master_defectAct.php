<title>DPR | Master Defect <?= $action; ?></title>
<?php $this->load->view('layout/sidebar'); ?>


<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: auto;
        /*width: 1000px;*/
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
                        <h5><?= $action; ?> Master Data Defect</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
    <div class="table-responsive">
     <?php echo form_open('c_new/'.$action.'/t_defectdanlosstime/master_defect'); ?> 
     <!-- ke function add / nama_table / redirect kemana -->
        <table class="table table-bordered stripe row-border order-column" rules="all" style="background:#fff;" id="customFields" style="width: 100%">
        <tr style="background:#1ab394;color: white;text-align: center;">
          <td>Nama</td>
          <td>Type</td>
          <td>Kategori <button type="button" class="btn btn-xs btn-primary" onclick="showKategoriModal()">
    <i class="fa fa-list"></i>
</button></td>
          <td>Satuan</td>
          <td><?= $action == "Add" ? '<span class="btn btn-warning" id="addMoreRowsBtn"><i class="fa fa-plus-square-o"></i></span>' : ''; ?></td>
        </tr>
        <?php if($action ==  'Edit') {
    foreach($data_tabel->result() as $data):
    echo '<tr>';
    echo '<td><input type="text" name="user[0][nama]" value="'.$data->nama.'" class="form-control"><input type="hidden" name="id" value="'.$data->id.'"><input type="hidden" name="where" value="id"></td>';
    
    // Type dropdown (now using types from t_defectdanlosstime table)
    echo '<td><select name="user[0][type]" class="form-control">';
    foreach($types as $type) {
        $selected = ($type->type == $data->type) ? 'selected' : '';
        echo '<option value="'.htmlspecialchars($type->type).'" '.$selected.'>'.htmlspecialchars($type->type).'</option>';
    }
    echo '</select></td>';
    
    // Kategori dropdown (now using categories from master_kategori_defect table)
    echo '<td><select name="user[0][kategori_defect]" class="form-control">';
    foreach($categories as $category) {
        $selected = ($category->nama_kategori == $data->kategori_defect) ? 'selected' : '';
        echo '<option value="'.htmlspecialchars($category->nama_kategori).'" '.$selected.'>'.htmlspecialchars($category->nama_kategori).'</option>';
    }
    echo '</select></td>';
    
    echo '<td><input type="text" name="user[0][satuan]" value="'.$data->satuan.'" class="form-control"></td>';
    echo '</tr>';
    endforeach;
} ?>
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

<!-- Add this modal structure before the closing </div> -->
<div class="modal inmodal" id="typeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Kategori Defect</h4>
            </div>
            <div class="modal-body">
                <!-- List of existing categories -->
                <div class="table-responsive mb-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td>
                                        <a href="#" class="select-kategori" data-kategori="<?= htmlspecialchars($category->nama_kategori) ?>">
                                            <?= htmlspecialchars($category->nama_kategori) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-xs delete-kategori" data-id="<?= $category->id_master_kategori_defect ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add new category form -->
                <form id="addKategoriForm" action="<?= base_url('c_new/add_kategori_defect') ?>" method="POST">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Kategori</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add this JavaScript -->
<script>
// Immediately Invoked Function Expression (IIFE) to avoid global scope pollution
(function($) {
    'use strict';

    // Constants
    const BASE_URL = '<?= base_url() ?>';
    
    // State management
    let rowCounter = 0;

    // DOM Elements
    const elements = {
        typeModal: $('#typeModal'),
        customFields: $('#customFields'),
        addKategoriForm: $('#addKategoriForm'),
        addMoreRowsBtn: $('#addMoreRowsBtn'),
        modalBody: $('.modal-body')
    };

    // Core Functions
    const MasterDefect = {
        init: function() {
            this.bindEvents();
            this.initializeModal();
        },

        bindEvents: function() {
            // Add this line for delete button
            elements.modalBody.on('click', '.delete-kategori', this.handleDeleteKategori);

            // Existing event bindings
            elements.addMoreRowsBtn.on('click', this.addNewRow);
            elements.typeModal.on('show.bs.modal', this.loadKategoriList);
            elements.addKategoriForm.on('submit', this.handleKategoriSubmit);
            elements.customFields.on('click', '.remCF', this.removeRow);
            $(document).on('click', '.select-kategori', this.selectKategori);
        },

        initializeModal: function() {
            // Initialize any modal-specific features
            elements.typeModal.modal({
                backdrop: 'static',
                keyboard: false,
                show: false
            });
        },

        // Modal Functions
        showKategoriModal: function() {
            elements.typeModal.modal('show');
        },

        loadKategoriList: function() {
            $.ajax({
                url: `${BASE_URL}c_new/get_kategori_defect`,
                type: 'GET',
                success: function(response) {
                    const tbody = response.categories.map(category => `
                        <tr>
                            <td>
                                <a href="#" class="select-kategori" data-kategori="${category.nama_kategori}">
                                    ${category.nama_kategori}
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-xs delete-kategori" data-id="${category.id_master_kategori_defect}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                    
                    $('#typeModal tbody').html(tbody);
                },
                error: function() {
                    toastr.error('Failed to load categories');
                }
            });
        },

        // Row Management
        addNewRow: function() {
            rowCounter++;
            const types = <?= json_encode($types) ?>;
            const categories = <?= json_encode($categories) ?>;
            const typeOptions = types.map(type => 
                `<option value="${type.type}">${type.type}</option>`
            ).join('');
            const categoryOptions = categories.map(category => 
                `<option value="${category.nama_kategori}">${category.nama_kategori}</option>`
            ).join('');

            const newRow = `
                <tr>
                    <td><input type="text" name="user[${rowCounter}][nama]" class="form-control"></td>
                    <td>
                        <select name="user[${rowCounter}][type]" class="form-control">
                            ${typeOptions}
                        </select>
                    </td>
                    <td>
                        <select name="user[${rowCounter}][kategori_defect]" class="form-control">
                            ${categoryOptions}
                        </select>
                    </td>
                    <td><input type="text" name="user[${rowCounter}][satuan]" class="form-control"></td>
                    <td>
                        <a href="javascript:void(0);" class="remCF">
                            <button class="btn btn-danger btn-circle" type="button">
                                <i class="fa fa-trash"></i>
                            </button>
                        </a>
                    </td>
                </tr>
            `;
            
            elements.customFields.append(newRow);
        },

        removeRow: function() {
            $(this).closest('tr').remove();
        },

        // Category Management
        handleKategoriSubmit: function(e) {
            e.preventDefault();
            const form = $(this);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if(response.success) {
                        MasterDefect.loadKategoriList();
                        form[0].reset();
                        toastr.success('Category added successfully');
                    } else {
                        toastr.error('Failed to add category');
                    }
                },
                error: function() {
                    toastr.error('Error occurred while adding category');
                }
            });
        },

        handleDeleteKategori: function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            
            if (!confirm('Are you sure you want to delete this category?')) {
                return;
            }

            $.ajax({
                url: `${BASE_URL}c_new/delete_kategori_defect/${id}`,
                type: 'POST',
                success: function(response) {
                    if(response.success) {
                        MasterDefect.loadKategoriList();
                        toastr.success('Category deleted successfully');
                    } else {
                        toastr.error('Failed to delete category');
                    }
                },
                error: function() {
                    toastr.error('Error occurred while deleting category');
                }
            });
        },

        selectKategori: function(e) {
            e.preventDefault();
            const selectedKategori = $(this).data('kategori');
            const activeInput = document.activeElement.closest('tr')?.querySelector('select[name*="[type]"]');
            
            if (activeInput) {
                activeInput.value = selectedKategori;
            }
            
            elements.typeModal.modal('hide');
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        MasterDefect.init();
        window.showKategoriModal = MasterDefect.showKategoriModal;
    });

})(jQuery);
</script>

    
