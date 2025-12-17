 <title>DPR | Master Defect & Loss Time</title>
<?php $this->load->view('layout/sidebar'); ?>

<link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">


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
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Action</h2>
                    <div class="row">
                    	<div class="col">
                            <a href="<?= base_url('c_new/master_defectAct/id'); ?>"><button class="btn btn-success">Add Master Defect & Loss Time</button></a>
                    	</div>
                    </div>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Master Data Defect & Loss Time</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
    <div class="ibox-content">
            
            <div class="table-responsive">

    <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
        <thead>
          <tr style="text-align: center;">
            <th>No</th>
            <th><b>Nama</b></th>
            <th><b>Defect/Loss Time</b></th>
            <th><b>Kategori</b></th>
            <th><b>Satuan</b></th>
            <th>Action</th>
          </tr>
         </thead>
         <tbody>
        <?php 
        $delete     = base_url('c_new/Delete/master_defect/t_defectdanlosstime/id/');
        $no = 0;  foreach($data_tabel as $row): $no++;?>
          <tr>
              <td><?php echo $no; ?></td>
              <td><?php echo ($row->nama) ?></td>
              <td><?php echo ($row->type) ?></td>
              <td><?php echo ($row->kategori_defect) ?></td>
              <td><?php echo ($row->satuan) ?></td>
              <td>
                <center>
                 <a href="<?= $delete.'/'.$row->id; ?>" onclick="return confirm('Are you sure delete ?')" ><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a>
              </td>
          </tr>
        <?php endforeach; ?>


        
         </tbody>
         <tfoot>
          <tr style="text-align: center;">
           <th>No</th>
            <th><b>Nama</b></th>
            <th><b>Defect/Loss Time</b></th>
            <th><b>Kategori</b></th>
            <th><b>Satuan</b></th>
            <th>Action</th>
          </tr>
         </tfoot>
    </table>
            </div>

                    </div>
                </div>
            </div>
            </div>
        </div>

        </div>
        </div>






<?php $this->load->view('layout/footer'); ?>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>



    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
			        // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 3,
                        right: 2
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Data_2025_12_09'},
                    {extend: 'pdf', title: 'Data_2025_12_09'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
               

            });

        });

    </script>

    
<?php
    for($i = 1; $i <= 31 ; $i++)
    {
        //echo 'SUM(CASE WHEN DAY(p.`tanggal`) = '.$i.' THEN dl.qty ELSE 0 END ) AS tg'.$i.',<br/>';

    }
 ?>