<?= form_open(site_url('c_dpr/report/'.$jenis.'/'.$name), array('method' => 'post', 'autocomplete' => 'off')); ?>  
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Filter Data</h2>
                    <div class="row">
                        <div class="col-sm-3 mb-2"> <b>Pilih Tahun - Bulan</b> 
                    	<select name="tahun" class="form-control">
                                        <?php
                                        // Show last 12 months, most recent first
                                        for($i = 0; $i < 12; $i++){
                                            $date = date('Y-m', strtotime("first day of -$i months"));
                                            // Ensure consistent string comparison with explicit casting and trim
                                            $selected = (strval($date) == strval(trim($tanggal))) ? 'selected="selected"' : '';
                                            echo "<option value='$date' $selected>$date</option>";
                                        }
                                        ?>
                                    </select>
                        </div>
                        <div class="col-sm-5 mb-2">
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
                        <div class="col-sm-2 mb-2" style="margin-top:27px;">
                            <button type="submit" name="show" value="Show" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i> Show
                            </button>
                        </div>
                    </div>
                </div>
            </div>
<?= form_close(); ?>
<!-- END HEADING ----> 