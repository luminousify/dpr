<title>DPR | Duplicate Review</title>
<?php $this->load->view('layout/sidebar'); ?>

<style>
    th, td {
        white-space: nowrap;
    }
    .payload-preview {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        font-size: 11px;
        color: #6c757d;
        max-width: 380px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: help;
    }
    .payload-preview:hover {
        color: #495057;
    }
</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="d-flex">
                        <div class="mr-auto p-2">
                            <h5>Duplicate Review Queue</h5>
                            <small class="text-muted">
                                Records flagged by the drain worker as duplicates of an existing
                                <code>t_production_op</code> row (tanggal + mesin + shift + id_bom).
                                Approve to force-insert, reject to drop.
                            </small>
                        </div>
                        <div class="p-2">
                            <a href="<?php echo base_url('index.php/c_dpr/dpr'); ?>" class="btn btn-sm btn-default">Back to DPR</a>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>OK.</strong> <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error.</strong> <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($rows)): ?>
                        <div class="text-center text-muted p-5">
                            <h4>No duplicates awaiting review</h4>
                            <p>The drain worker found no duplicate-key collisions, or every flagged row has already been actioned.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Queued</th>
                                        <th>id_production</th>
                                        <th>Tanggal</th>
                                        <th>Mesin</th>
                                        <th>Shift</th>
                                        <th>id_bom</th>
                                        <th>Operator</th>
                                        <th class="text-right">Qty OK</th>
                                        <th class="text-right">Qty NG</th>
                                        <th>Payload</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row): ?>
                                        <?php $s = $row['summary']; ?>
                                        <tr>
                                            <td><?php echo (int) $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                            <td><code><?php echo htmlspecialchars($row['id_production']); ?></code></td>
                                            <td><?php echo htmlspecialchars($s['tanggal']); ?></td>
                                            <td><?php echo htmlspecialchars($s['mesin']); ?></td>
                                            <td><?php echo htmlspecialchars($s['shift']); ?></td>
                                            <td><?php echo htmlspecialchars($s['id_bom']); ?></td>
                                            <td><?php echo htmlspecialchars($s['operator']); ?></td>
                                            <td class="text-right"><?php echo htmlspecialchars($s['qty_ok']); ?></td>
                                            <td class="text-right"><?php echo htmlspecialchars($s['qty_ng']); ?></td>
                                            <td>
                                                <div class="payload-preview" title="<?php echo htmlspecialchars($row['payload'], ENT_QUOTES); ?>">
                                                    <?php echo htmlspecialchars($row['payload']); ?>
                                                </div>
                                            </td>
                                            <td class="text-center" style="min-width: 160px;">
                                                <?php echo form_open('c_sync/approve_duplicate/' . (int) $row['id'], array('style' => 'display:inline;')); ?>
                                                    <button type="submit" class="btn btn-xs btn-success"
                                                            onclick="return confirm('Approve this duplicate and force-insert it into t_production_op?');">
                                                        <i class="fa fa-check"></i> Approve
                                                    </button>
                                                <?php echo form_close(); ?>
                                                <?php echo form_open('c_sync/reject_duplicate/' . (int) $row['id'], array('style' => 'display:inline;')); ?>
                                                    <button type="submit" class="btn btn-xs btn-danger"
                                                            onclick="return confirm('Reject this duplicate? The queue row will be deleted (the duplicate stays untouched in t_production_op).');">
                                                        <i class="fa fa-times"></i> Reject
                                                    </button>
                                                <?php echo form_close(); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted">
                            <?php echo count($rows); ?> row(s) awaiting review. Supervisor: <?php echo htmlspecialchars(isset($data['user_name']) ? $data['user_name'] : ''); ?>
                        </p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
