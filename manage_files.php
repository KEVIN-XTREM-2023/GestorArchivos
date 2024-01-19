<?php
include_once('db_connect.php');
$dbInstance = Database::getInstance();
$db = $dbInstance->getConnection();

if (isset($_GET['id'])) {
	$qry = $db->query("SELECT * FROM files where id=" . $_GET['id']);
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_array() as $k => $v) {
			$meta[$k] = $v;
		}
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-files">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
		<input type="hidden" name="folder_id" value="<?php echo isset($_GET['fid']) ? $_GET['fid'] : '' ?>">
		<!-- <div class="form-group">
			<label for="name" class="control-label">File Name</label>
			<input type="text" name="name" id="name" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" class="form-control">
		</div> -->
		<?php if (!isset($_GET['id']) || empty($_GET['id'])) : ?>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text">Subir</span>
				</div>
				<div class="custom-file">
					<input type="file" class="custom-file-input" name="upload" id="upload" onchange="displayname(this,$(this))">
					<label class="custom-file-label" for="upload">Elija el archivo</label>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group">
			<label for="" class="control-label">Descripción</label>
			<textarea name="description" id="" cols="30" rows="10" class="form-control"><?php echo isset($meta['description']) ? $meta['description'] : '' ?></textarea>
		</div>

		<div class="form-group">
			<label for="is_public" class="control-label"><input type="checkbox" name="is_public" id="is_public"><i>Compartir con todos los usuarios</i></label>
		</div>

		<div class="form-group">
			<label><i>Compartir con:</i></label>
			<?php include_once 'db_connect.php'; ?>
			<select name="receptor_id" id="receptor_id">
				<option selected value="">No compartir</option>
				<?php 
				
				$query = $db->query("SELECT * FROM users");
				while ($valores = mysqli_fetch_array($query)) {
					echo '<option value="' . $valores['id'] . '">' . $valores['name'] . '</option>';
				}
				?>
			</select>

		</div>
		<div class="form-group">
			<input type="hidden" name="descargar" id="descargar">
			<input type="checkbox" id="op_descargar" name="op_descargar"><label for="op_descargar"> <i>opción descargar</i> </label>
		</div>
		<div class="form-group" id="msg"></div>

	</form>
</div>
<script>
	// let selected = document.getElementById('op_descargar');
	// // let id = document.getElementById('receptor_id');
	// selected.addEventListener('change', function() {
	// 	// selected.value = id.value;
	// 	alert(selected.value);
	// })


	$(document).ready(function() {
		// $('#op_descargar').change(function(e) {
		// 	console.log(e);
		// })

		$('#op_descargar').change(function() {
			if (this.checked) { 
				$('#descargar').val(1)
			} else {
				$('#descargar').val(0) 
			}
			// $('#textbox1').val(this.checked);
		});

		$('#manage-files').submit(function(e) {
			e.preventDefault()
			start_load();
			$('#msg').html('')
			$.ajax({
				url: 'ajax.php?action=save_files',
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				success: function(resp) {
					if (typeof resp != undefined) {
						resp = JSON.parse(resp);
						if (resp.status == 1) {
							alert_toast("Nuevo archivo agregado con éxito.", 'success')
							setTimeout(function() {
								location.reload()
							}, 1500)
						} else {
							$('#msg').html('<div class="alert alert-danger">' + resp.msg + '</div>')
							end_load()
						}
					}
				}
			})
		})
	})

	function displayname(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				_this.siblings('label').html(input.files[0]['name'])

			}

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>