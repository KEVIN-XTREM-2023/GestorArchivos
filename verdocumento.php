<?php
include_once 'db_connect.php';
$dbInstance = Database::getInstance();
$db = $dbInstance->getConnection();
$id = $_GET["id_archivo"];
$files = $db->query("SELECT * FROM files where id = $id");
$num = $files->fetch_array();
$nombre = $num['file_path'];
$ruta = "assets/uploads/" . $nombre . "#toolbar=0";
// var_dump($num);
?>
<div class="content-wrapper">
    <br>
    <button onclick="volver();" class="btn btn-primary">volver</button>
    <section class="content">
        <div align="center">
            <iframe id="iframe" src="<?php echo  $ruta ?>" width="100%" height="850"></iframe>
        </div>
    </section>
</div>
<script>
    $(document).contextmenu(function() {
        return false;
    });

    function volver() {
        history.back();
    }

    function desactivar() {

        // Or use this
        // document.getElementById("iframe").contentWindow.document.oncontextmenu = function() {
        //     alert("No way!");
        //     return false;
        // };;
    }
</script>