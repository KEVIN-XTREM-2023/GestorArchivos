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

	table th,
	td {
		/*border-left:1px solid gray;*/
	}

	a.custom-menu-list span.icon {
		width: 1em;
		margin-right: 5px
	}
</style>
<nav aria-label="breadcrumb ">
	<ol class="breadcrumb">
		<li class="breadcrumb-item ">Inicio</li>
	</ol>
</nav>
<div class="containe-fluid">
	<?php
	if (!isset($_SESSION['login_id']))
	?>
	<?php 
		include_once('db_connect.php');
		$dbInstance = Database::getInstance();
		$db = $dbInstance->getConnection();

		$files = $db->query("SELECT f.*,u.name as uname FROM files f inner join users u on u.id = f.user_id where  f.is_public = 1 order by date(f.date_updated) desc");

	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="card col-md-3 offset-2   float-left" style="background-color: #627D93;">
				<div class="card-body text-white">
					<h4><b>Usuarios </b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-users"></i></span>
					<h3 class="text-right"><b>
					<?php
						$result = $db->query('SELECT COUNT(*) AS num_rows FROM users_view');
						if ($result) {
							$row = $result->fetch_assoc();
							$numRows = $row['num_rows'];
							echo $numRows;
						} else {
							echo "Error: " . $db->error;
						}
						$result->close();
						?>
						</b></h3>
				</div>
			</div>
			<div class="card col-md-3 offset-2 ml-4 float-left" style="background-color: #627D93;">
				<div class="card-body text-white">
					<h4><b>Archivos</b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-file"></i></span>
					<h3 class="text-right"><b>
						<?php
						 $result =  $db->query('SELECT COUNT(*) AS num_rows FROM files_view');
						if ($result) {
							$row = $result->fetch_assoc();
							$numRows = $row['num_rows'];
							echo $numRows;
						} else {
							echo "Error: " . $db->error;
						}

						// Cerrar el resultado
						$result->close();
						
						?>
					</b></h3>
				</div>
			</div>

			<div class="card col-md-3 offset-2  ml-4 float-left" style="background-color: #627D93;">
				<div class="card-body text-white">
					<h4><b>Carpetas</b></h4>
					<hr>
					<span class="card-icon"><i class="fa fa-file"></i></span>
					<h3 class="text-right"><b>
						<?php 
						 $result =  $db->query('SELECT COUNT(*) AS num_rows FROM folders_view');
						 if ($result) {
							 $row = $result->fetch_assoc();
							 $numRows = $row['num_rows'];
							 echo $numRows;
						 } else {
							 echo "Error: " . $db->error;
						 }
 						 $result->close();
						?></b></h3>
				</div>
			</div>
		</div>
	</div>