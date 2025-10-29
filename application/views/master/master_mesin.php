 <title>DPR | Master Mesin</title>
 <?php $this->load->view('layout/sidebar'); ?>

 <link href="<?= base_url(); ?>template/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

 <link href="<?= base_url(); ?>template/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">


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
 <div class="row wrapper border-bottom white-bg page-heading">
     <div class="col-lg-10">
         <h2>Action</h2>
         <div class="row">
             <div class="col">
                 <a href="<?= base_url('c_new/master_mesinAct/no'); ?>"><button class="btn btn-success">Add No Mesin / Tonnase</button></a>
                 <a href="<?= base_url('c_new/master_namamesinAct/nama'); ?>"><button class="btn btn-primary">Add Nama Mesin</button></a>
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
                     <h5>Master Data Mesin</h5>
                     <div class="ibox-tools">
                         <a class="collapse-link">
                             <i class="fa fa-chevron-up"></i>
                         </a>
                     </div>
                 </div>
                 <div class="ibox-content">
                     <div class="table-responsive">
                         <div class="row">
                             <div class="col">
                                 <table class="table table-striped table-bordered table-hover dataTables-example" style="width:100%">
                                     <thead>
                                         <tr style="text-align: center;">
                                             <th>No</th>
                                             <th>No Mesin</th>
                                             <th>Nama Mesin</th>
                                             <th>Tonnase</th>
                                             <th>Aktif</th>
                                             <th>Divisi</th>
                                             <th>Action</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <?php
                                            $delete     = base_url('c_new/Delete/master_mesin/t_no_mesin/id_no_mesin/');
                                            $edit  = base_url('c_new/master_mesinAct/edit');
                                            $no = 0;
                                            foreach ($data_tabel as $row): $no++; ?>
                                             <tr>
                                                 <td><?php echo $no; ?></td>
                                                 <td><?php echo ($row['no_mesin']) ?></td>
                                                 <td><?php echo ($row['nama_mesin']) ?></td>
                                                 <td><?php echo ($row['tonnase']) ?></td>
                                                 <td>
                                                     <center><?php if ($row['aktif'] == 1) { ?><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-check"></i></button><?php } else { ?><button class="btn btn-warning btn-circle" type="button"><i class="fa fa-times"></i></button><?php } ?></center>
                                                 </td>
                                                 <td><?php echo ($row['divisi']) ?></td>
                                                 <td>
                                                     <center>
                                                         <a href="<?= $edit . '/t_no_mesin/id_no_mesin/' . $row['id_no_mesin']; ?>"><button class="btn btn-success btn-circle" type="button"><i class="fa fa-edit"></i></button></a>

                                                         <a href="<?= $delete . '/' . $row['id_no_mesin']; ?>" onclick="return confirm('Are you sure delete ?')"><button class="btn btn-danger btn-circle" type="button"><i class="fa fa-trash"></i></button></a>
                                                     </center>
                                                 </td>
                                             </tr>
                                         <?php endforeach; ?>
                                     </tbody>
                                     <tfoot>
                                         <tr style="text-align: center;">
                                             <th>No</th>
                                             <th>No Mesin</th>
                                             <th>Nama Mesin</th>
                                             <th>Tonnase</th>
                                             <th>Aktif</th>
                                             <th>Divisi</th>
                                             <th>Action</th>
                                         </tr>
                                     </tfoot>
                                 </table>

                             </div>
                             <div class="col-md-6">
                                 <table class="table table-striped table-bordered table-hover dataTables-example1" style="width:100%">
                                     <thead>
                                         <tr style="text-align: center;">
                                             <th>No</th>
                                             <th>Nama Mesin</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <?php
                                            $delete     = base_url('c_new/Delete/master_mesin/t_no_mesin/id_no_mesin/');
                                            $edit_url   = base_url('c_new/master_mesinAct/edit/t_no_mesin/id_no_mesin/'); // Define edit URL
                                            $no = 0;
                                            foreach ($data_tabel as $row): $no++; ?>
                                             <tr>
                                                 <td><?php echo $no; ?></td>

                                                 <td><?php echo ($row['nama_mesin']) ?></td>

                                             </tr>
                                         <?php endforeach; ?>
                                     </tbody>
                                     <tfoot>
                                         <tr style="text-align: center;">
                                             <th>No</th>
                                             <th>Nama Mesin</th>
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

 </div>
 </div>






 <?php $this->load->view('layout/footer'); ?>
 <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
 <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
 <script src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>

 <!-- Page-Level Scripts -->
 <script>
     $(document).ready(function() {
         $('.dataTables-example tfoot th').each(function() {
             $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
         });
         $('.dataTables-example').DataTable({
             pageLength: 10,
             responsive: false,
             // scrollY:        "300px",
             scrollX: true,
             scrollCollapse: true,
             paging: true,
             fixedColumns: {
                 left: 3,
                 right: 2
             },
             dom: '<"html5buttons"B>lTfgitp',
             buttons: [{
                     extend: 'copy'
                 },
                 {
                     extend: 'csv'
                 },
                 {
                     extend: 'excel',
                     title: 'ExampleFile'
                 },
                 {
                     extend: 'pdf',
                     title: 'ExampleFile'
                 },

                 {
                     extend: 'print',
                     customize: function(win) {
                         $(win.document.body).addClass('white-bg');
                         $(win.document.body).css('font-size', '10px');

                         $(win.document.body).find('table')
                             .addClass('compact')
                             .css('font-size', 'inherit');
                     }
                 }
             ],
             initComplete: function() {
                 // Apply the search
                 this.api().columns().every(function() {
                     var that = this;

                     $('input', this.footer()).on('keyup change clear', function() {
                         if (that.search() !== this.value) {
                             that
                                 .search(this.value)
                                 .draw();
                         }
                     });
                 });
             }


         });

     });


     $(document).ready(function() {
         $('.dataTables-example1 tfoot th').each(function() {
             $(this).html('<input type="text" placeholder="Search" style="width:100%" />');
         });
         $('.dataTables-example1').DataTable({
             pageLength: 10,
             responsive: false,
             // scrollY:        "300px",
             scrollX: true,
             scrollCollapse: true,
             paging: true,
             fixedColumns: {
                 left: 3,
                 right: 2
             },
             dom: '<"html5buttons"B>lTfgitp',
             buttons: [{
                     extend: 'copy'
                 },
                 {
                     extend: 'csv'
                 },
                 {
                     extend: 'excel',
                     title: 'ExampleFile'
                 },
                 {
                     extend: 'pdf',
                     title: 'ExampleFile'
                 },

                 {
                     extend: 'print',
                     customize: function(win) {
                         $(win.document.body).addClass('white-bg');
                         $(win.document.body).css('font-size', '10px');

                         $(win.document.body).find('table')
                             .addClass('compact')
                             .css('font-size', 'inherit');
                     }
                 }
             ],
             initComplete: function() {
                 // Apply the search
                 this.api().columns().every(function() {
                     var that = this;

                     $('input', this.footer()).on('keyup change clear', function() {
                         if (that.search() !== this.value) {
                             that
                                 .search(this.value)
                                 .draw();
                         }
                     });
                 });
             }


         });

     });
 </script>