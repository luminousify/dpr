<div class="table-responsive">
    <?php foreach ($data_total_mesin->result_array() as $total_mesin) : ?>
    <?php endforeach; ?>
    <div class="card rounded">
        <div id="container3"></div>
    </div>
    <br />
    <p><strong>Jika data available capacity dan Machine use % tidak muncul, silahkan lengkapi jumlah mesin pada halaman <a href="<?= base_url(); ?>c_new/master_work_days">Ini</a></strong></p>

    <table class="table table-bordered" id="datatable3" style="display: none;">
        <thead>
            <tr>
                <th></th>
                <th>
                    <center>Available Capacity (Hours)</center>
                </th>
                <th>
                    <center>Machine Use (Hours)</center>
                </th>
                <th>
                    <center>Loss Time (Hours)</center>
                </th>
                <th>
                    <center>Overtime (Hours)</center>
                </th>
                <th>
                    <center>Idle (Hours)</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $namaBulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
            foreach ($data_EffGrafikYear->result_array() as $data) {
                echo '<tr>';
                echo '<th>' . (isset($namaBulan[$data['bulan']]) ? $namaBulan[$data['bulan']] : 'Unknown') . '</th>';
                echo '<td>' . round($data['total'] * $total_mesin['total_mesin'], 1) . '</td>';
                echo '<td>' . $data['total_machine_use'] . '</td>';
                echo '<td>' . $data['total_LT'] . '</td>';
                echo '<td>' . $data['total_OT'] . '</td>';
                echo '<td>' . $data['total_idle'] . '</td>';
                echo '</tr>';
            } ?>

        </tbody>
    </table>
    <br>
    <hr>
    <table class="table table-striped table-bordered table-hover dataTables-example3" style="width:100%">
        <thead>
            <tr>
                <th class="text-center align-middle">
                    <center>No</center>
                </th>
                <th class="text-center align-middle">
                    <center>Bulan</center>
                </th>
                <th class="text-center align-middle">Av. Capacity<br> (Hours)</th>
                <th class="text-center align-middle">Total Prod. Time <br> (Hours)</th>
                <th class="text-center align-middle">Total Loss Time <br> (Hours)</th>
                <th class="text-center align-middle">OT <br> (Hours)</th>
                <th class="text-center align-middle">Idle<br> (Hours)</th>
                <th class="text-center align-middle">NWT <br> (Hours)</th>
                <th class="text-center align-middle" class="text-center align-middle" style='background:#5bc0de;color: white;'>Machine Use <br>(Hours)</th>
                <th class="text-center align-middle" style='background:#5bc0de;color: white;'>Machine Use <br>(%)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $namaBulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
            foreach ($data_EffGrafikYear->result_array() as $data) {
                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                echo '<td>' . $data['bulan_new'] . '</td>';
                echo '<td>' . $data['total_available_capacity'] . '</td>';
                echo '<td>' . $data['total_production_time'] . '</td>';
                echo '<td>' . $data['total_LT'] . '</td>';
                echo '<td>' . $data['total_OT'] . '</td>';
                echo '<td>' . $data['total_idle'] . '</td>';
                echo '<td>' . $data['total_nwt'] . '</td>';
                echo '<td style="background:#5bc0de;color: white;">' . $data['total_machine_use'] . '</td>';
                echo '<td style="background:#5bc0de;color: white;">' . $data['machine_use_persen'] . '</td>';
                echo '</tr>';
            } ?>

        </tbody>
        <tfoot>
            <tr>
                <th class="text-center align-middle">
                    <center>No</center>
                </th>
                <th class="text-center align-middle">
                    <center>Bulan</center>
                </th>
                <th class="text-center align-middle">Av. Capacity<br> (Hours)</th>
                <th class="text-center align-middle">Total Prod. Time <br> (Hours)</th>
                <th class="text-center align-middle">Total Loss Time <br> (Hours)</th>
                <th class="text-center align-middle">OT <br> (Hours)</th>
                <th class="text-center align-middle">Idle<br> (Hours)</th>
                <th class="text-center align-middle">NWT <br> (Hours)</th>
                <th class="text-center align-middle" class="text-center align-middle" style='background:#5bc0de;color: white;'>Machine Use <br>(Hours)</th>
                <th class="text-center align-middle" style='background:#5bc0de;color: white;'>Machine Use <br>(%)</th>
            </tr>
        </tfoot>
    </table>
    <br>
    <hr>

    <div class="card rounded">
        <div id="container4"></div>
    </div>
    <br />

    <table class="table table-bordered" id="datatable4" style="display: none;">
        <thead>
            <tr>
                <th></th>
                <th>
                    <center>Machine Use (%)</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $namaBulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
            foreach ($data_EffGrafikYear->result_array() as $data) {
                echo '<tr>';
                echo '<th>' . (isset($namaBulan[$data['bulan']]) ? $namaBulan[$data['bulan']] : 'Unknown') . '</th>';
                echo '<td>' . $data['machine_use_persen'] . '</td>';
                echo '</tr>';
            } ?>

        </tbody>
    </table>



</div>