 <title>DPR | Inventory</title>
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
<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <?php 
                        $dh = $data_header->row_array(); // Get first row as array
                        ?>
                        <h5>Product Analysis ( <?php echo $dh['kode_product'] ?> - <?php echo $dh['nama_product'] ?> )</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                <div class="ibox-content">
                    <?= form_open('c_inventory/total_prod_analisis'); ?>  
                    <div class="card rounded mb-2">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-3"> <b>Pilih Tahun - Bulan</b> 
                                    <select name="tahun" style="width: 300px;" value="<?php echo $tanggal ?>" class="form-control">
                                        <?php
                                        $tahuns = date('Y')-1;
                                        $now=date('Y');
                                        for($i=$tahuns; $i<=$now; $i++){
                                        $monts = array("01","02","03","04","05","06","07","08","09","10","11","12");
                                           foreach ($monts as $value) { ?>
                                                <option value="<?php echo $i.'-'.$value?>" <?php if($value == date('m') && $i == date('Y')) { echo 'selected="selected"';}?>><?php echo $i.'-'.$value; ?></option>;<?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:18px; margin-left: -10px;" value="Show"></div>
                                <input type="hidden" name="id_product" class="form-control" value="<?php echo $dh['id_product'] ?>">
                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>bulan ini saja</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                        </div>
                    </div>
                    <?= form_close(); ?>
                    <br>
                    <div class="table-responsive mt-2">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable1" style="width:100%" >
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Prod Date</th>
                                                    <th>Total OK</th>
                                                    <th>Total NG</th>
                                                    <th>% NG</th>
                                                    <th>Pin Seret</th>
                                                    <th>Blackdot</th>
                                                    <th>Hike</th>
                                                    <th>Sink Mark</th>
                                                    <th>Oversize</th>
                                                    <th>Undersize</th>
                                                    <th>Settingan</th>
                                                    <th>Waving</th>
                                                    <th>Dirty</th>
                                                    <th>Broken</th>
                                                    <th>Oil</th>
                                                    <th>Undercut</th>
                                                    <th>Foreign Material</th>
                                                    <th>White Mark</th>
                                                    <th>White Dot</th>
                                                    <th>Pin Plong</th>
                                                    <th>Bending</th>
                                                    <th>Short Shoot</th>
                                                    <th>Flash</th>
                                                    <th>Gate Bolong</th>
                                                    <th>Crack</th>
                                                    <th>Berawan</th>
                                                    <th>Gas Mark</th>
                                                    <th>Ejector Mark</th>
                                                    <th>Hangus</th>
                                                    <th>Gas Burn</th>
                                                    <th>Scratch</th>
                                                    <th>Discolour</th>
                                                    <th>Silver</th>
                                                    <th>Dent</th>
                                                    <th>Flow Gate</th>
                                                    <th>Gate Long</th>
                                                    <th>Weld Line</th>
                                                    <th>Void</th>
                                                    <th>Flow Mark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
$no = 0; foreach ($data_tabel->result_array() as $data): $no++; ?>
    <tr>
        <td><?= $no ?></td>
        <td><?= $data['tanggal'] ?></td>

        <!-- grand totals -->
        <td><?= $data['qty_ok'] ?></td>
        <td><?= $data['qty']     ?></td>
        <td>
            <?= $data['qty_ok'] ? round($data['qty'] / $data['qty_ok'] * 100, 2) : 0 ?>%
        </td>

        <!-- individual defects – one echo per column -->
        <td><?= $data['pin_seret']   ?></td>
        <td><?= $data['blackdot']    ?></td>
        <td><?= $data['hike']        ?></td>
        <td><?= $data['sink_mark']   ?></td>
        <td><?= $data['oversize']    ?></td>
        <td><?= $data['undersize']   ?></td>
        <td><?= $data['settingan']   ?></td>
        <td><?= $data['waving']      ?></td>
        <td><?= $data['dirty']       ?></td>
        <td><?= $data['broken']      ?></td>
        <td><?= $data['oil']         ?></td>
        <td><?= $data['undercut']    ?></td>
        <td><?= $data['fm']          ?></td>
        <td><?= $data['white_mark']  ?></td>
        <td><?= $data['white_dot']   ?></td>
        <td><?= $data['pin_plong']   ?></td>
        <td><?= $data['bending']     ?></td>
        <td><?= $data['short_shoot'] ?></td>
        <td><?= $data['flash']       ?></td>
        <td><?= $data['gate_bolong'] ?></td>
        <td><?= $data['crack']       ?></td>
        <td><?= $data['berawan']     ?></td>
        <td><?= $data['gas_mark']    ?></td>
        <td><?= $data['ejector_mark']?></td>
        <td><?= $data['hangus']      ?></td>
        <td><?= $data['gas_burn']    ?></td>
        <td><?= $data['scratch']     ?></td>
        <td><?= $data['discolour']   ?></td>
        <td><?= $data['silver']      ?></td>
        <td><?= $data['dent']        ?></td>
        <td><?= $data['flow_gate']   ?></td>
        <td><?= $data['gate_long']   ?></td>
        <td><?= $data['weld_line']   ?></td>
        <td><?= $data['void']        ?></td>
        <td><?= $data['flow_mark']   ?></td>
    </tr>
<?php endforeach; ?>
</tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Prod Date</th>
                                                    <th>Total OK</th>
                                                    <th>Total NG</th>
                                                    <th>% NG</th>
                                                    <th>Pin Seret</th>
                                                    <th>Blackdot</th>
                                                    <th>Hike</th>
                                                    <th>Sink Mark</th>
                                                    <th>Oversize</th>
                                                    <th>Undersize</th>
                                                    <th>Settingan</th>
                                                    <th>Waving</th>
                                                    <th>Dirty</th>
                                                    <th>Broken</th>
                                                    <th>Oil</th>
                                                    <th>Undercut</th>
                                                    <th>Foreign Material</th>
                                                    <th>White Mark</th>
                                                    <th>White Dot</th>
                                                    <th>Pin Plong</th>
                                                    <th>Bending</th>
                                                    <th>Short Shoot</th>
                                                    <th>Flash</th>
                                                    <th>Gate Bolong</th>
                                                    <th>Crack</th>
                                                    <th>Berawan</th>
                                                    <th>Gas Mark</th>
                                                    <th>Ejector Mark</th>
                                                    <th>Hangus</th>
                                                    <th>Gas Burn</th>
                                                    <th>Scratch</th>
                                                    <th>Discolour</th>
                                                    <th>Silver</th>
                                                    <th>Dent</th>
                                                    <th>Flow Gate</th>
                                                    <th>Gate Long</th>
                                                    <th>Weld Line</th>
                                                    <th>Void</th>
                                                    <th>Flow Mark</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                     </div>
                     <hr>
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example2" id="datatable2" style="width:100%" >
                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Total OK</th>
                                                    <th>Total NG</th>
                                                    <th>Pin Seret</th>
                                                    <th>Blackdot</th>
                                                    <th>Hike</th>
                                                    <th>Sink Mark</th>
                                                    <th>Oversize</th>
                                                    <th>Undersize</th>
                                                    <th>Settingan</th>
                                                    <th>Waving</th>
                                                    <th>Dirty</th>
                                                    <th>Broken</th>
                                                    <th>Oil</th>
                                                    <th>Undercut</th>
                                                    <th>Foreign Material</th>
                                                    <th>White Mark</th>
                                                    <th>White Dot</th>
                                                    <th>Pin Plong</th>
                                                    <th>Bending</th>
                                                    <th>Short Shoot</th>
                                                    <th>Flash</th>
                                                    <th>Gate Bolong</th>
                                                    <th>Crack</th>
                                                    <th>Berawan</th>
                                                    <th>Gas Mark</th>
                                                    <th>Ejector Mark</th>
                                                    <th>Hangus</th>
                                                    <th>Gas Burn</th>
                                                    <th>Scratch</th>
                                                    <th>Discolour</th>
                                                    <th>Silver</th>
                                                    <th>Dent</th>
                                                    <th>Flow Gate</th>
                                                    <th>Gate Long</th>
                                                    <th>Weld Line</th>
                                                    <th>Void</th>
                                                    <th>Flow Mark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $id = -1; $no = 0 ; foreach($data_detail->result_array() as $data)
                                                {  $no++; $id++; ?>
                                                <tr>
                                                    <td><strong>Grand Total</strong></td>
                                                    <td><?= $data['total_qty']; ?></td>
                                                    <td>
                                                        <?php 
                                                        if ($data['total_ng'] ==  null) {
                                                             echo '0';
                                                         } else {
                                                             echo $data['total_ng'];
                                                         }
                                                        ?>
                                                    </td>
                                                    <td><?= $data['pin_seret']; ?></td>
                                                    <td><?= $data['blackdot']; ?></td>
                                                    <td><?= $data['hike']; ?></td>
                                                    <td><?= $data['sink_mark']; ?></td>
                                                    <td><?= $data['oversize']; ?></td>
                                                    <td><?= $data['undersize']; ?></td>
                                                    <td><?= $data['settingan']; ?></td>
                                                    <td><?= $data['waving']; ?></td>
                                                    <td><?= $data['dirty']; ?></td>
                                                    <td><?= $data['broken']; ?></td>
                                                    <td><?= $data['oil']; ?></td>
                                                    <td><?= $data['undercut']; ?></td>
                                                    <td><?= $data['fm']; ?></td>
                                                    <td><?= $data['white_mark']; ?></td>
                                                    <td><?= $data['white_dot']; ?></td>
                                                    <td><?= $data['pin_plong']; ?></td>
                                                    <td><?= $data['bending']; ?></td>
                                                    <td><?= $data['short_shoot']; ?></td>
                                                    <td><?= $data['flash']; ?></td>
                                                    <td><?= $data['gate_bolong']; ?></td>
                                                    <td><?= $data['crack']; ?></td>
                                                    <td><?= $data['berawan']; ?></td>
                                                    <td><?= $data['gas_mark']; ?></td>
                                                    <td><?= $data['ejector_mark']; ?></td>
                                                    <td><?= $data['hangus']; ?></td>
                                                    <td><?= $data['gas_burn']; ?></td>
                                                    <td><?= $data['scratch']; ?></td>
                                                    <td><?= $data['discolour']; ?></td>
                                                    <td><?= $data['silver']; ?></td>
                                                    <td><?= $data['dent']; ?></td>
                                                    <td><?= $data['flow_gate']; ?></td>
                                                    <td><?= $data['gate_long']; ?></td>
                                                    <td><?= $data['weld_line']; ?></td>
                                                    <td><?= $data['void']; ?></td>
                                                    <td><?= $data['flow_mark']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>NG %</strong></td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_ng = ($data['total_ng'] / $data['total_qty']) * 100;
                                                        $total_ng_persen = round($hitung_total_ng,3);
                                                        ?>
                                                        <?php echo $total_ng_persen ?>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_pin_seret = ($data['pin_seret'] / $data['total_qty']) * 100;
                                                        $total_pin_seret_persen = round($hitung_total_pin_seret,3);
                                                        ?>
                                                        <?php echo $total_pin_seret_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_blackdot = ($data['blackdot'] / $data['total_qty']) * 100;
                                                        $total_blackdot_persen = round($hitung_total_blackdot,3);
                                                        ?>
                                                        <?php echo $total_blackdot_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_hike = ($data['hike'] / $data['total_qty']) * 100;
                                                        $total_hike_persen = round($hitung_total_hike,3);
                                                        ?>
                                                        <?php echo $total_hike_persen ?>
                                                     </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_sink_mark = ($data['sink_mark'] / $data['total_qty']) * 100;
                                                        $total_sink_mark_persen = round($hitung_total_sink_mark,3);
                                                        ?>
                                                        <?php echo $total_sink_mark_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_oversize = ($data['oversize'] / $data['total_qty']) * 100;
                                                        $total_oversize_persen = round($hitung_total_oversize,3);
                                                        ?>
                                                        <?php echo $total_oversize_persen ?>                     
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_undersize = ($data['undersize'] / $data['total_qty']) * 100;
                                                        $total_undersize_persen = round($hitung_total_undersize,3);
                                                        ?>
                                                        <?php echo $total_undersize_persen ?>    
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_settingan = ($data['settingan'] / $data['total_qty']) * 100;
                                                        $total_settingan_persen = round($hitung_total_settingan,3);
                                                        ?>
                                                        <?php echo $total_settingan_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_waving = ($data['waving'] / $data['total_qty']) * 100;
                                                        $total_waving_persen = round($hitung_total_waving,3);
                                                        ?>
                                                        <?php echo $total_waving_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_dirty = ($data['dirty'] / $data['total_qty']) * 100;
                                                        $total_dirty_persen = round($hitung_total_dirty,3);
                                                        ?>
                                                        <?php echo $total_dirty_persen ?> 
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_broken = ($data['broken'] / $data['total_qty']) * 100;
                                                        $total_broken_persen = round($hitung_total_broken,3);
                                                        ?>
                                                        <?php echo $total_broken_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_oil = ($data['oil'] / $data['total_qty']) * 100;
                                                        $total_oil_persen = round($hitung_total_oil,3);
                                                        ?>
                                                        <?php echo $total_oil_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_undercut = ($data['undercut'] / $data['total_qty']) * 100;
                                                        $total_undercut_persen = round($hitung_total_undercut,3);
                                                        ?>
                                                        <?php echo $total_undercut_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_fm = ($data['fm'] / $data['total_qty']) * 100;
                                                        $total_fm_persen = round($hitung_total_fm,3);
                                                        ?>
                                                        <?php echo $total_fm_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_white_mark = ($data['white_mark'] / $data['total_qty']) * 100;
                                                        $total_white_mark_persen = round($hitung_total_white_mark,3);
                                                        ?>
                                                        <?php echo $total_white_mark_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_white_dot = ($data['white_dot'] / $data['total_qty']) * 100;
                                                        $total_white_dot_persen = round($hitung_total_white_dot,3);
                                                        ?>
                                                        <?php echo $total_white_dot_persen ?> 
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_pin_plong = ($data['pin_plong'] / $data['total_qty']) * 100;
                                                        $total_pin_plong_persen = round($hitung_total_pin_plong,3);
                                                        ?>
                                                        <?php echo $total_pin_plong_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_bending = ($data['bending'] / $data['total_qty']) * 100;
                                                        $total_bending_persen = round($hitung_total_bending,3);
                                                        ?>
                                                        <?php echo $total_bending_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_short_shoot = ($data['short_shoot'] / $data['total_qty']) * 100;
                                                        $total_short_shoot_persen = round($hitung_total_short_shoot,3);
                                                        ?>
                                                        <?php echo $total_short_shoot_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_flash = ($data['flash'] / $data['total_qty']) * 100;
                                                        $total_flash_persen = round($hitung_total_flash,3);
                                                        ?>
                                                        <?php echo $total_flash_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_gate_bolong = ($data['gate_bolong'] / $data['total_qty']) * 100;
                                                        $total_gate_bolong_persen = round($hitung_total_gate_bolong,3);
                                                        ?>
                                                        <?php echo $total_gate_bolong_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_crack = ($data['crack'] / $data['total_qty']) * 100;
                                                        $total_crack_persen = round($hitung_total_crack,3);
                                                        ?>
                                                        <?php echo $total_crack_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_berawan = ($data['berawan'] / $data['total_qty']) * 100;
                                                        $total_berawan_persen = round($hitung_total_berawan,3);
                                                        ?>
                                                        <?php echo $total_berawan_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_gas_mark = ($data['gas_mark'] / $data['total_qty']) * 100;
                                                        $total_gas_mark_persen = round($hitung_total_gas_mark,3);
                                                        ?>
                                                        <?php echo $total_gas_mark_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_ejector_mark = ($data['ejector_mark'] / $data['total_qty']) * 100;
                                                        $total_ejector_mark_persen = round($hitung_total_ejector_mark,3);
                                                        ?>
                                                        <?php echo $total_ejector_mark_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_hangus = ($data['hangus'] / $data['total_qty']) * 100;
                                                        $total_hangus_persen = round($hitung_total_hangus,3);
                                                        ?>
                                                        <?php echo $total_hangus_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_gas_burn = ($data['gas_burn'] / $data['total_qty']) * 100;
                                                        $total_gas_burn_persen = round($hitung_total_gas_burn,3);
                                                        ?>
                                                        <?php echo $total_gas_burn_persen ?> 
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_scratch = ($data['scratch'] / $data['total_qty']) * 100;
                                                        $total_scratch_persen = round($hitung_total_scratch,3);
                                                        ?>
                                                        <?php echo $total_scratch_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_discolour = ($data['discolour'] / $data['total_qty']) * 100;
                                                        $total_discolour_persen = round($hitung_total_discolour,3);
                                                        ?>
                                                        <?php echo $total_discolour_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_silver = ($data['silver'] / $data['total_qty']) * 100;
                                                        $total_silver_persen = round($hitung_total_silver,3);
                                                        ?>
                                                        <?php echo $total_silver_persen ?>    
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_dent = ($data['dent'] / $data['total_qty']) * 100;
                                                        $total_dent_persen = round($hitung_total_dent,3);
                                                        ?>
                                                        <?php echo $total_dent_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_flow_gate = ($data['flow_gate'] / $data['total_qty']) * 100;
                                                        $total_flow_gate_persen = round($hitung_total_flow_gate,3);
                                                        ?>
                                                        <?php echo $total_flow_gate_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_gate_long = ($data['gate_long'] / $data['total_qty']) * 100;
                                                        $total_gate_long_persen = round($hitung_total_gate_long,3);
                                                        ?>
                                                        <?php echo $total_gate_long_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_weld_line = ($data['weld_line'] / $data['total_qty']) * 100;
                                                        $total_weld_line_persen = round($hitung_total_weld_line,3);
                                                        ?>
                                                        <?php echo $total_weld_line_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_void = ($data['void'] / $data['total_qty']) * 100;
                                                        $total_void_persen = round($hitung_total_void,3);
                                                        ?>
                                                        <?php echo $total_void_persen ?>
                                                    </td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_flow_mark = ($data['flow_mark'] / $data['total_qty']) * 100;
                                                        $total_flow_mark_persen = round($hitung_total_flow_mark,3);
                                                        ?>
                                                        <?php echo $total_flow_mark_persen ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>PPM</strong></td>
                                                    <td>
                                                        <?php  
                                                        $hitung_total_ppm = ($data['total_ng'] / $data['total_qty']) * 1000000;
                                                        $total_ppm = round($hitung_total_ppm);
                                                        ?>
                                                        <?php echo $total_ppm ?>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Total OK</th>
                                                    <th>Total NG</th>
                                                    <th>Pin Seret</th>
                                                    <th>Blackdot</th>
                                                    <th>Hike</th>
                                                    <th>Sink Mark</th>
                                                    <th>Oversize</th>
                                                    <th>Undersize</th>
                                                    <th>Settingan</th>
                                                    <th>Waving</th>
                                                    <th>Dirty</th>
                                                    <th>Broken</th>
                                                    <th>Oil</th>
                                                    <th>Undercut</th>
                                                    <th>Foreign Material</th>
                                                    <th>White Mark</th>
                                                    <th>White Dot</th>
                                                    <th>Pin Plong</th>
                                                    <th>Bending</th>
                                                    <th>Short Shoot</th>
                                                    <th>Flash</th>
                                                    <th>Gate Bolong</th>
                                                    <th>Crack</th>
                                                    <th>Berawan</th>
                                                    <th>Gas Mark</th>
                                                    <th>Ejector Mark</th>
                                                    <th>Hangus</th>
                                                    <th>Gas Burn</th>
                                                    <th>Scratch</th>
                                                    <th>Discolour</th>
                                                    <th>Silver</th>
                                                    <th>Dent</th>
                                                    <th>Flow Gate</th>
                                                    <th>Gate Long</th>
                                                    <th>Weld Line</th>
                                                    <th>Void</th>
                                                    <th>Flow Mark</th>
                                                </tr>
                                            </tfoot>
                        </table>
                     </div>
                </div>
</div>
</div>
</div>
</div>




<?php $this->load->view('layout/footer'); ?>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>
    <!-- Page-Level Scripts -->


<script>
        $(document).ready(function(){
            $('.dataTables-example tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 5,
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

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

        $(document).ready(function(){
            $('.dataTables-example2 tfoot th').each( function () {
                $(this).html( '<input type="text" placeholder="Search" style="width:100%" />' );
            } );
            //alert(iniValue);
            $('.dataTables-example2').DataTable({
                pageLength: 10,
                responsive: false,
                    // scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         true,
                    fixedColumns:   {
                        left: 3,
                    },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

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

