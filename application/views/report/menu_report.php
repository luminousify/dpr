<?= form_open('c_dpr/report/'.$jenis.'/'.$name); ?>  
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Filter Data</h2>
                    <div class="row" style="margin-left:2px;">
                        <div class="col-sm3"> <b>Pilih Tahun - Bulan</b> 
                    	<select name="tahun" style="width: 300px;" value="<?php echo $tahun ?>" class="form-control">
                                        <?php
                                        $tahuns = date('Y')-1;
                                        $now=date('Y');
                                        for($i=$tahuns; $i<=$now; $i++){
                                        $monts = array("01","02","03","04","05","06","07","08","09","10","11","12");
                                           foreach ($monts as $value) { ?>
                                                <option value="<?php echo $i.'-'.$value?>" <?php if($value == $bulan && $i == $tahun) { echo 'selected="selected"';}?>><?php echo $i.'-'.$value; ?></option>;<?php
                                           }
                                        }
                                        ?>
                                    </select>
                        </div>
                        <div class="col">
                            <b>Pilih Berdasarkan</b> 
                            <select name="pilihan" class="form-control">
                                <option value="1" <?php echo $valueNya == 1 ? "selected" : ''; ?>>Production <?= $name; ?> By Product</option>
                                <option value="2" <?php echo $valueNya == 2 ? "selected" : ''; ?>>Production <?= $name; ?> By Machine</option>
                                <option value="3" <?php echo $valueNya == 3 ? "selected" : ''; ?>>Production <?= $name; ?> By Machine & Shift</option>
                                <option value="4" <?php echo $valueNya == 4 ? "selected" : ''; ?>>Production <?= $name; ?> By Machine & Shift & Group</option>
                                <option value="5" <?php echo $valueNya == 5 ? "selected" : ''; ?>>Production <?= $name; ?> Summary Of The Year</option>
                                <option value="6" <?php echo $valueNya == 6 ? "selected" : ''; ?>>Production <?= $name; ?> By Product & Customer</option>
                            </select>
                        </div>
                        <div class="col"><br/>
                        <input type="submit" name="show" class="btn btn-primary" value="Show"></div>
                    </div>
                </div>
            </div>
<?= form_close(); ?>
<!-- END HEADING ----> 