
                <div class="ibox-content">
                  <!-- <?= form_open('c_report/productivity_quartal'); ?>  
                    <div class="card rounded mb-4">
                        <div class="card-header">
                            <h2>Filter Data</h2>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin-left:2px;">
                                <div class="col-sm-3"> <b>Pilih Tahun</b> 
                                    <select name="tahun" value="<?php echo $tahun ?>" class="form-control">
                                      <?php
                                        $tahuns = date('Y')-1;
                                        $now=date('Y')+1;
                                        for($i=$tahuns; $i<=$now; $i++){?>   
                                          <option value="<?php echo $i ?>" <?php if($i == $tahun) 
                                          { 
                                            echo 'selected="selected"';}?>><?php echo $i; ?></option>;<?php
                                          }                          
                                      ?>
                                    </select>
                                </div>
                                <div class="col"><input type="submit" name="show" class="btn btn-primary" style="margin-top:20px;" value="Show"></div>
                            </div>
                        </div>
                        <div class="row ml-4">
                            <p><strong>*Catatan :</strong> Data yang muncul hanya data pada <strong>tahun ini saja</strong>, jika ingin melihat data yang lain silahkan gunakan fitur <strong>filter</strong>.</p>
                        </div>
                    </div>
                    <?= form_close(); ?> -->
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                                <li><a class="nav-link active" data-toggle="tab" href="#tab-1">Productivity Q1</a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-2">Productivity Q2</a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-3">Productivity Q3</a></li>
                               <!--  <li><a class="nav-link " data-toggle="tab" href="#tab-4">Detail Productivity By Part By Month</a></li> -->
                                
                            </ul>
                        <div class="tab-content">
                            <div role="tabpanel" id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <?php $this->load->view('global/productivity/productivity_q1'); ?>
                                </div>
                            </div>
                            <div role="tabpanel" id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <?php $this->load->view('global/productivity/productivity_q2'); ?>
                                </div>
                            </div>
                            <div role="tabpanel" id="tab-3" class="tab-pane">
                                <div class="panel-body">
                                    <?php $this->load->view('global/productivity/productivity_q3'); ?>
                                </div>
                            </div>           
                        </div>
                     </div>
                </div>
