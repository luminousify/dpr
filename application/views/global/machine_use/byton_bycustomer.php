<?php foreach ($avail_capacity_machine->result_array() as $acm) : ?>
<?php endforeach; ?>
<?php foreach ($get_total_machine->result_array() as $tmt) : ?>
<?php endforeach; ?>
<div class="row">
    <div class="col-md-6">
        <div class=" card rounded">
            <div id="container5"></div>
        </div>
        <br />
        <table class="table table-bordered" id="datatable5" style="display: none">
            <thead>
                <tr>
                    <th></th>
                    <th>Total Machine Use (Hours)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                foreach ($grafik_machine_use_byton_bymonth->result_array() as $data) {
                    $no++;
                    echo '<tr>';
                    echo '<th>' . $data['tonnase'] . '</th>';
                    echo '<td>' . round($data['machine_use'], 1) . '</td>';
                    echo '</tr>';
                } ?>

            </tbody>
        </table>
    </div>

    <div class="col-md-6">
        <div class=" card rounded">
            <div id="container6"></div>
        </div>
        <br />
        <table class="table table-bordered" id="datatable6" style="display: none">
            <thead>
                <tr>
                    <th></th>
                    <th>Persentase (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                foreach ($grafik_machine_use_byton_bymonth->result_array() as $data) {
                    $no++;
                    echo '<tr>';
                    echo '<th>' . $data['tonnase'] . '</th>';
                    echo '<td>' . round($data['persentase'], 2) . '</td>';
                    echo '</tr>';
                } ?>

            </tbody>
        </table>
    </div>
</div>
<br />
<br />

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover dataTables-example1">
        <thead>
            <tr>

                <th class="align-middle text-center" rowspan="4">No</th>
                <th class="align-middle text-center" rowspan="4">Tahun</th>
                <th class="align-middle text-center" rowspan="4">Bulan</th>
                <th class="align-middle text-center" rowspan="4">Customer</th>
                <th class="align-middle text-left" colspan="36" style="background:#1ab394;color: white;text-align: center;">Available Capacity : <?php echo $acm['total'] ?></th>
            </tr>
            <tr>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 10</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 40</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 60</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 80</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 90</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 100</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 110</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 120</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 140</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 160</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 180</th>
                <th class="align-middle text-center" colspan="3" style="background:#1ab394;color: white;text-align: center;">Tons 200</th>
            </tr>
            <tr>
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>Av. Hour = <?php echo $acm['total'] * $tmt['ton' . $i]; ?></center>
                    </th>
                    <th style="background:#1ab394;color: white;text-align: center;" colspan="2">
                        <center>Qty Mc = <?php echo $tmt['ton' . $i] ?></center>
                    </th>
                <?php } ?>
            </tr>
            <tr>
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>Mc. Use (Hour)</center>
                    </th>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>%</center>
                    </th>
                    <th style="background:#1ab394;color: white;text-align: center;">
                        <center>Mc Use (Unit)</center>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $no = 0;
            foreach ($machine_use_byton_bycustomer_bymonth->result_array() as $data) {
                $no++;
                echo '<tr>';
                echo '<td>' . $no . '</td>';
                echo '<td>' . $data['tahun'] . '</td>';
                echo '<td>' . $data['bulan'] . '</td>';
                echo '<td>' . $data['customer'] . '</td>';
                for ($i = 1; $i <= 12; $i++) {
                    // Prevent division by zero by checking both $tmt['ton'.$i] and $acm['total']
                    $persen = (($tmt['ton' . $i] != 0) && ($acm['total'] != 0)) ? ($data['t' . $i] / ($acm['total'] * $tmt['ton' . $i])) * 100 : 0;
                    $persen_fix = round($persen, 2);
                    $unit = ($acm['total'] != 0) ? ($data['t' . $i] / $acm['total']) : 0;
                    $unit_fix = round($unit, 2);
                    echo '<td>' . round($data['t' . $i], 1) . '</td>';
                    echo '<td>' . $persen_fix . '</td>';
                    echo '<td>' . $unit_fix . '</td>';
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>

</div>