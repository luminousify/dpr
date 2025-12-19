            <!-- Batch Delete Controls for Shift 2 -->
<div class="row mb-2 batch-controls" id="batch-controls-shift2" style="display: none;">
    <div class="col-md-6">
        <div class="btn-group">
            <button type="button" class="btn btn-warning" id="select-all-shift2">
                <i class="fa fa-check-square-o"></i> Select All
            </button>
            <button type="button" class="btn btn-secondary" id="deselect-all-shift2">
                <i class="fa fa-square-o"></i> Deselect All
            </button>
        </div>
        <span class="ml-3" id="selected-count-shift2">
            <strong>0</strong> rows selected
        </span>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-danger" id="delete-selected-shift2">
            <i class="fa fa-trash"></i> Delete Selected
        </button>
    </div>
</div>

<table class="table table-striped table-bordered table-hover dataTables-example" id="shift2-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-checkbox-shift2" class="select-all-checkbox"></th>
                                    <th>Run</th>
                                    <th>Line</th>
                                    <th>No. MC</th>
                                    <th>Ton</th>
                                    <th>Kode Product</th>
                                    <th>Nama Product</th>
                                    <th>CT STD</th>
                                    <th>CT Run</th>
                                    <th>Cavity</th>
                                    <th>Operator</th>
                                    <th>Man Power</th>
                                    <th>Auto / Semi</th>
                                    <th>Ket.</th>
                                    
                                    <th>Tanggal</th>
                                    <th>Group</th>
                                    <th>Shift</th>
                                    <th>Run</th>
                                    <th>Edit</th>
                                    <?php
                                    $user = $this->session->userdata('posisi');
                                    if ($user == 'admin') { ?>
                                        <th><b>Delete</b></th>
                                    <?php } else {
                                        echo "";
                                    }
                                    ?>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php $totalRun = 0; $totalMP = 0; $id = -1; $no = 0 ; foreach($data_tabel2->result_array() as $data)
                                {  $no++; $id++; ?>
                                <tr data-id="<?= $data['id_machine_use']; ?>">
                                    <td>
                                        <input type="checkbox" class="row-checkbox shift2-checkbox" value="<?= $data['id_machine_use']; ?>">
                                    </td>
                                    <td>
                                        <center><?php if($data['running'] == 1) { ?><button class="btn btn-primary btn-circle" type="button"><i class="fa fa-check"></i></button><?php } else { echo '<button class="btn btn-danger btn-circle" type="button"><i class="fa fa-close"></i></button>';} ?></center>
                                    </td>
                                    <td><?= $data['line']; ?></td>
                                    <td><?= $data['no_mesin']; ?></td>
                                    <td><?= $data['tonnase']; ?></td>
                                    <td><?= $data['kp_pr']; ?></td>
                                    <td><?= $data['np_pr']; ?></td>
                                    <td><?= $data['CTStd2']; ?></td>
                                    <?php  
                                    if ($data['CTStd2'] != 0 || null) {
                                        if ($data['ct_running'] > $data['CTStd2']) {
                                                echo "<td style='background:#df4759;color: white;'>".$data['ct_running'];"</td>";
                                        } else {
                                                echo "<td>".$data['ct_running'];"</td>";
                                            } 
                                    } else {
                                        echo "<td>".$data['ct_running'];"</td>";
                                    }
                                    
                                    ?>
                                    <td><?= $data['cavity']; ?></td>
                                    <td><?= $data['operator']; ?></td>
                                    <td><?= $data['man_power']; ?></td>
                                    <td><?= $data['jenis_mesin']; ?></td>
                                    <td><?= $data['keterangan']; ?></td>
                                    
                                    <td><?= $data['tanggal']; ?></td>
                                    <td><?= $data['group']; ?></td>
                                    <td><?= $data['shift']; ?></td>
                                    <td><?= $data['running']; ?></td>
                                    <td>
                                        <a href="<?= base_url('c_machine/edit/'.$data['id_machine_use']); ?>"><button class="btn btn-success btn-circle" type="button"><i class="fa fa-pencil"></i></button>
                                    </td>
                                    <?php
                                    $hapus = base_url('c_machine/delete_machine_use');
                                    if ($user ==  'admin') {
                                        echo '<td><a href="'.$hapus.'/'.$data['id_machine_use'].'"><center><button class="btn btn-danger btn-circle delete"><i class="fa fa-trash"></i></button></center></a></td>';
                                    } else {
                                        echo "";
                                    }
                                    ?>
                                    
                                </tr>
                                <?php $totalRun += $data['running']; $totalMP += $data['man_power']; } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Run</th>
                                    <th>Line</th>
                                    <th>No. MC</th>
                                    <th>Ton</th>
                                    <th>Kode Product</th>
                                    <th>Nama Product</th>
                                    <th>CT STD</th>
                                    <th>CT Run</th>
                                    <th>Cavity</th>
                                    <th>Operator</th>
                                    <th>Man Power</th>
                                    <th>Auto / Semi</th>
                                    <th>Ket.</th>
                                    
                                    <th>Tanggal</th>
                                    <th>Group</th>
                                    <th>Shift</th>
                                    <th>Run</th>
                                    <th>Edit</th>
                                    <?php
                                    $user = $this->session->userdata('posisi');
                                    if ($user == 'admin') { ?>
                                        <th><b>Delete</b></th>
                                    <?php } else {
                                        echo "";
                                    }
                                    ?>
                                    
                                </tr>
                            </tfoot>
            </table>

<div class="row" style="margin-left:5px;">
                <div class="col-lg-6">
                    <div class="widget style1 navy-bg">
                        <div class="row vertical-align">
                            <div class="col-9">
                                <h2 class="font-bold">Total MC <?= $line; ?></h2>
                            </div>
                            <div class="col-3 text-left">
                                <h2 class="font-bold"><?= $totalRun; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="widget style1 red-bg">
                        <div class="row vertical-align">
                            <div class="col-9">
                                <h2 class="font-bold">Total MP <?= $line; ?></h2>
                            </div>
                            <div class="col-3 text-left">
                                <h2 class="font-bold"><?= $totalMP; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <?php foreach($data_tabelTotalMPHadir2->result_array() as $data)
                                {} ?>
                <div class="col-lg-6">
                    <div class="widget style1 yellow-bg">
                        <div class="row vertical-align">
                            <div class="col-9">
                                <h2 class="font-bold">Total MP Hadir</h2>
                            </div>
                            <div class="col-3 text-left">
                                <h2 class="font-bold"><?= $data['totals']; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>


                <?php foreach($data_tabelTotalMPTidakHadir2->result_array() as $data)
                                { } ?>
                <div class="col-lg-6">
                    <div class="widget style1 black-bg">
                        <div class="row vertical-align">
                            <div class="col-9">
                                <h2 class="font-bold text-white">Total MP Tidak Hadir</h2>
                            </div>
                            <div class="col-3 text-left">
                                <h2 class="font-bold text-white"><?= $data['totals']; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <?php foreach($data_tabelTotalSisaMPHadir2->result_array() as $sisa)
                                { } ?>
                <div class="col-lg-6">
                    <div class="widget style1 blue-bg">
                        <div class="row vertical-align">
                            <div class="col-9">
                                <h2 class="font-bold text-white">Total Sisa MP Hadir</h2>
                            </div>
                            <div class="col-3 text-left">
                                <h2 class="font-bold text-white"><?= $sisa['sisa']; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
