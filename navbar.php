 <nav id="sidebar" class="mx-lt-5" style="background-color: #1B2631;">
 	<!--#5fb2e0 
627D93 
-->
 	<div class="sidebar-list">
 		<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Inicio</a>
 		<a href="index.php?page=files" class="nav-item nav-files"><span class='icon-field'><i class="fa fa-file"></i></span> Archivos</a>
 		<?php if ($_SESSION['login_type'] == 1) : ?>
 			<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Usuarios</a>
 		<?php endif; ?>
 		<a href="index.php?page=sharefolder" class="nav-item nav-files"><span class='icon-field'><i class="fa fa-folder"></i></span> Carpetas Compartidas</a>
 		<a href="index.php?page=sharefiles" class="nav-item nav-files"><span class='icon-field'><i class="fa fa-file"></i></span> Archivos Compartidos</a>

 	</div>
 </nav>
 <script>
 	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
 </script>