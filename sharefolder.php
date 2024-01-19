<style>
	.custom-menu {
		z-index: 1000;
		position: absolute;
		background-color: #ffffff;
		border: 1px solid #0000001c;
		border-radius: 5px;
		padding: 8px;
		min-width: 13vw;
	}

	a.custom-menu-list {
		width: 100%;
		display: flex;
		color: #4c4b4b;
		font-weight: 600;
		font-size: 1em;
		padding: 1px 11px;
	}

	span.card-icon {
		position: absolute;
		font-size: 3em;
		bottom: .2em;
		color: #ffffff80;
	}

	.file-item {
		cursor: pointer;
	}

	a.custom-menu-list:hover,
	.file-item:hover,
	.file-item.active {
		background: #80808024;
	}

	a.custom-menu-list span.icon {
		width: 1em;
		margin-right: 5px
	}
</style>
<nav aria-label="breadcrumb ">
	<ol class="breadcrumb">
		<li class="breadcrumb-item ">Carpetas Compartidas</li>
	</ol>
</nav>
<div class="containe-fluid">
	<?php $user =  $_SESSION['login_id'] ?>
	<?php include_once('db_connect.php');
	$dbInstance = Database::getInstance();
	$db = $dbInstance->getConnection();
	$folder = $db->query("SELECT f.*,u.name as uname FROM folders f inner join users u on u.id = f.user_id where f.is_public = 1");

	?>


	<div class="row mt-3 ml-3 mr-3">
		<div class="card col-md-12">
			<div class="card-body">
				<table id="datsa" width="100%">
					<thead>
						<tr>
							<th width="20%" class="">Usuario</th>
							<th width="30%" class="">Nombre de la carpeta</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = $folder->fetch_assoc()) :
							$name = explode(' ||', $row['name']);
							$name = isset($name[1]) ? $name[0] . " (" . $name[1] : $name[0];
							$icon = 'fa-folder';
						?>
							<tr class='folder-item' data-id="<?php echo $row['id'] ?>" data-name="<?php echo $name ?>">
								<td><i><?php echo ucwords($row['uname']) ?></i></td>
								<td>
									<large><span><i class="fa <?php echo $icon ?>"></i></span><b> <?php echo $name ?></b></large>
								</td>
							</tr>
						<?php endwhile; ?>
						<?php include_once('db_connect.php');
						$dbInstance = Database::getInstance();
						$db = $dbInstance->getConnection();
						$folders = $db->query("SELECT f.*,u.name as uname FROM folders f inner join users u on u.id = f.user_id where f.receptor_id = $user");
						?>
						<?php
						while ($row = $folders->fetch_assoc()) :
							$name = explode(' ||', $row['name']);
							$name = isset($name[1]) ? $name[0] . " (" . $name[1] : $name[0];
							$icon = 'fa-folder';
						?>
							<tr class='folder-item' data-id="<?php echo $row['id'] ?>" data-name="<?php echo $name ?>">

								<td><i><?php echo ucwords($row['uname']) ?></i></td>

								<td>
									<large><span><i class="fa <?php echo $icon ?>"></i></span><b> <?php echo $name ?></b></large>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>

			</div>
		</div>

	</div>
</div>

</div>
<div id="menu-file-clone" style="display: none;">
	<a href="javascript:void(0)" class="custom-menu-list file-option download"><span><i class="fa fa-download"></i> </span>Descargar</a>
</div>


<?php
include_once 'db_connect.php';
$dbInstance = Database::getInstance();
$db = $dbInstance->getConnection();
$folder_parent = isset($_GET['fid']) ? $_GET['fid'] : 0;
$folders = $db->query("SELECT * FROM folders where parent_id = $folder_parent and user_id = '" . $_SESSION['login_id'] . "'  order by name asc");


$files = $db->query("SELECT * FROM files where folder_id = $folder_parent and user_id = '" . $_SESSION['login_id'] . "'  order by name asc");

?>
<style>
	.folder-item {
		cursor: pointer;
	}

	.folder-item:hover {
		background: #eaeaea;
		color: black;
		box-shadow: 3px 3px #0000000f;
	}

	.custom-menu {
		z-index: 1000;
		position: absolute;
		background-color: #ffffff;
		border: 1px solid #0000001c;
		border-radius: 5px;
		padding: 8px;
		min-width: 13vw;
	}

	a.custom-menu-list {
		width: 100%;
		display: flex;
		color: #4c4b4b;
		font-weight: 600;
		font-size: 1em;
		padding: 1px 11px;
	}

	.file-item {
		cursor: pointer;
	}

	a.custom-menu-list:hover,
	.file-item:hover,
	.file-item.active {
		background: #80808024;
	}

	a.custom-menu-list span.icon {
		width: 1em;
		margin-right: 5px
	}
</style>




<script>
	$('#new_folder').click(function() {
		uni_modal('', 'manage_folder.php?fid=<?php echo $folder_parent ?>')
	})
	$('#new_file').click(function() {
		uni_modal('', 'manage_files.php?fid=<?php echo $folder_parent ?>')
	})
	$('.folder-item').dblclick(function() {
		location.href = 'index.php?page=files&fid=' + $(this).attr('data-id')
	})
	$('.folder-item').bind("contextmenu", function(event) {
		event.preventDefault();
		$("div.custom-menu").hide();
		var custom = $("<div class='custom-menu'></div>")
		custom.append($('#menu-folder-clone').html())
		custom.find('.edit').attr('data-id', $(this).attr('data-id'))
		custom.find('.delete').attr('data-id', $(this).attr('data-id'))
		custom.appendTo("body")
		custom.css({
			top: event.pageY + "px",
			left: event.pageX + "px"
		});

		$("div.custom-menu .edit").click(function(e) {
			e.preventDefault()
			uni_modal('Renombrar Carpeta', 'manage_folder.php?fid=<?php echo $folder_parent ?>&id=' + $(this).attr('data-id'))
		})
		$("div.custom-menu .delete").click(function(e) {
			e.preventDefault()
			_conf("¿Estás seguro de eliminar esta carpeta?", 'delete_folder', [$(this).attr('data-id')])
		})
	})
</script>