<div class="table-responsive">
 <table class="table table-bordered">
          <tr>
            <th>No</th>
            <th>Kode Product</th>
            <th>Nama Product</th>
            <th>Proses</th>
            <th>Usage</th>
            <th>Qty</th>
          </tr>
          <?php $i = -1; $no = 0; foreach($view_release as $data): $no++; $i++?>
          <tr>
          	<td><?php echo $no; ?></td>
          	<td><?php echo $data->kode_product; ?><input type="hidden" name="ress[0][id_product]" value="<?= $data->id_product; ?>"></td>
            <td><?php echo $data->nama_product; ?></td>
            <td><?php echo $data->nama_proses; ?></td>
            <td><?php echo $data->usage; ?>
            <input type="hidden" name="" class="iniUsage<?= $i; ?>" value="<?php echo $data->usage; ?>"></td>
            <td><input type="hidden" name="ress[0][qty]" class="ubahQtyRelease<?= $i; ?>">
              <input type="hidden" name="ress[0][id_bom]" Value="<?= $data->id_bom; ?>">
              <p class="ubahQtyReleaseText<?= $i; ?>"></p></td>
          </tr>
          <?php endforeach; ?>
        </table>
</div>