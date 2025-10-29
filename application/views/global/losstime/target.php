<div class="table-responsive">
	<div class="card rounded">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h3 class="mb-0">Targets (Percent)</h3>
			<button class="btn btn-primary" data-toggle="modal" data-target="#targetCreateModal">Create Target</button>
		</div>
		<div class="card-body">
			<table class="table table-striped table-bordered dataTables-example" id="targets-table" style="width:100%">
				<thead>
					<tr>
						<th>Bagian</th>
						<th>Target (%)</th>
						<th>Year</th>
						<th>Month</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($targets) && $targets instanceof CI_DB_result): ?>
						<?php foreach($targets->result_array() as $row): ?>
						<tr data-id="<?= (int)$row['id'] ?>">
							<td><?= htmlspecialchars($row['bagian']) ?></td>
							<td><?= number_format((float)$row['target_percent'], 2) ?></td>
							<td><?= (int)$row['tahun'] ?></td>
							<td><?= str_pad((int)$row['bulan'], 2, '0', STR_PAD_LEFT) ?></td>
							<td>
								<button class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#targetEditModal">Edit</button>
								<button class="btn btn-sm btn-danger btn-delete">Delete</button>
							</td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Create Modal -->
	<div class="modal fade" id="targetCreateModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Create Target</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="target-create-form">
						<input type="hidden" name="tahun" value="<?= htmlspecialchars($tahun) ?>" />
						<input type="hidden" name="bulan" value="<?= htmlspecialchars($bulan) ?>" />
						<div class="form-group">
							<label>Bagian</label>
							<select name="bagian" class="form-control" required>
								<option value="">- Choose -</option>
								<?php if (isset($kategori)): ?>
									<?php if (is_array($kategori)): ?>
										<?php foreach($kategori as $k): ?>
											<option value="<?= htmlspecialchars($k['kategori_defect']) ?>"><?= htmlspecialchars($k['kategori_defect']) ?></option>
										<?php endforeach; ?>
									<?php elseif ($kategori instanceof CI_DB_result): ?>
										<?php foreach($kategori->result_array() as $k): ?>
											<option value="<?= htmlspecialchars($k['kategori_defect']) ?>"><?= htmlspecialchars($k['kategori_defect']) ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
							</select>
						</div>
						<div class="form-group">
							<label>Target (%)</label>
							<input type="number" name="target_percent" class="form-control" min="0" max="100" step="0.01" required />
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btn-create-submit">Save</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Edit Modal -->
	<div class="modal fade" id="targetEditModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Target</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="target-edit-form">
						<input type="hidden" name="id" />
						<div class="form-group">
							<label>Target (%)</label>
							<input type="number" name="target_percent" class="form-control" min="0" max="100" step="0.01" required />
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btn-edit-submit">Update</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
(function(){
	function post(url, data) {
		return fetch(url, {
			method: 'POST',
			headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
			body: new URLSearchParams(data).toString()
		}).then(r => r.json());
	}

	// Create (in-place update)
	document.getElementById('btn-create-submit')?.addEventListener('click', function(){
		var form = document.getElementById('target-create-form');
		var data = Object.fromEntries(new FormData(form).entries());
		post('<?= base_url('c_report/target_create') ?>', data).then(function(resp){
			if(resp && resp.success && resp.data){
				try {
					var tbody = document.querySelector('#targets-table tbody');
					var tr = document.createElement('tr');
					tr.setAttribute('data-id', resp.data.id);
					tr.innerHTML =
						'<td>'+ (resp.data.bagian || '') +'</td>'+
						'<td>'+ (Number(resp.data.target_percent).toFixed(2)) +'</td>'+
						'<td>'+ (resp.data.tahun) +'</td>'+
						'<td>'+ String(resp.data.bulan).padStart(2,'0') +'</td>'+
						'<td>'+
							'<button class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#targetEditModal">Edit</button> '+
							'<button class="btn btn-sm btn-danger btn-delete">Delete</button>'+
						'</td>';
					tbody.prepend(tr);
					// Wire up new buttons
					tr.querySelector('.btn-edit').addEventListener('click', function(){
						var id = tr.getAttribute('data-id');
						var val = tr.children[1].textContent.trim();
						var eform = document.getElementById('target-edit-form');
						eform.elements['id'].value = id;
						eform.elements['target_percent'].value = val;
					});
					tr.querySelector('.btn-delete').addEventListener('click', function(){
						if(!confirm('Delete this target?')) return;
						var id = tr.getAttribute('data-id');
						post('<?= base_url('c_report/target_delete') ?>/' + encodeURIComponent(id), {}).then(function(r){
							if(r && r.success){ tr.remove(); }
							else { alert('Delete failed'); }
						});
					});
					// Close modal
					$('[data-dismiss="modal"]').click();
				} catch (e) {
					console.error(e);
				}
			}else{
				alert(resp && resp.message ? resp.message : 'Create failed');
			}
		});
	});

	// Edit button opens modal with current row values
	document.querySelectorAll('#targets-table .btn-edit').forEach(function(btn){
		btn.addEventListener('click', function(){
			var tr = this.closest('tr');
			var id = tr.getAttribute('data-id');
			var val = tr.children[1].textContent.trim();
			var form = document.getElementById('target-edit-form');
			form.elements['id'].value = id;
			form.elements['target_percent'].value = val;
		});
	});

	// Edit submit (in-place update)
	document.getElementById('btn-edit-submit')?.addEventListener('click', function(){
		var form = document.getElementById('target-edit-form');
		var id = form.elements['id'].value;
		var data = { target_percent: form.elements['target_percent'].value };
		post('<?= base_url('c_report/target_update') ?>/' + encodeURIComponent(id), data).then(function(resp){
			if(resp && resp.success && resp.data){
				var tr = document.querySelector('#targets-table tr[data-id="'+id+'"]');
				if (tr) {
					tr.children[1].textContent = Number(resp.data.target_percent).toFixed(2);
				}
				$('[data-dismiss="modal"]').click();
			}else{
				alert('Update failed');
			}
		});
	});

	// Delete
	document.querySelectorAll('#targets-table .btn-delete').forEach(function(btn){
		btn.addEventListener('click', function(){
			if(!confirm('Delete this target?')) return;
			var tr = this.closest('tr');
			var id = tr.getAttribute('data-id');
			post('<?= base_url('c_report/target_delete') ?>/' + encodeURIComponent(id), {}).then(function(resp){
				if(resp && resp.success){
					tr.remove();
				}else{
					alert('Delete failed');
				}
			});
		});
	});
})();
</script>


