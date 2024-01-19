 <?php
   include_once 'db_connect.php';
   // $qry = $conn->query("SELECT * FROM files where id=".$_GET['id'])->fetch_array();

   // extract($_POST);

   //  		$fname=$qry['file_path'];   
   //        $file = ("assets/uploads/".$fname);

   //        header ("Content-Type: ".filetype($file));
   //        header ("Content-Length: ".filesize($file));
   //        header ("Content-Disposition: attachment; filename=".$qry['name'].'.'.$qry['file_type']);

   //        readfile($file); 
   $id = $_GET["id"];
   $dbInstance = Database::getInstance();
   $db = $dbInstance->getConnection();

   $files = $db->query("SELECT * FROM files where id = $id");
   $num = $files->fetch_array();
   $nombre = $num['file_path'];
   $ruta = "assets/uploads/" . $nombre;
   use setasign\Fpdi\Fpdi;

   require_once('fpdf184/fpdf.php');
   require_once('fpdi2/src/autoload.php'); 
   $nombreFirma = 'Este archivo ha sido descargado'; 

   $pdf = new FPDI();

   $pdf->setSourceFile($ruta);
   $path = $ruta;
   $page = $pdf->setSourceFile($path);
   # Paginas
   for ($i = 1; $i <= $page; $i++) {

      $pdf->AddPage();
      $tplIdx1 = $pdf->importPage($i);
      $pdf->useTemplate($tplIdx1);

      $pdf->SetFont('Arial', 'B', '9');
      $pdf->SetTextColor(255,0,0);
      $pdf->SetXY(13, 265);
      $pdf->Write(10, $nombreFirma);
      // $pdf->Image('firmas/one.png', 20, 120, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
      // $pdf->Image('firmas/one.png', 100, 130, 22,38,'PNG', 'https://www.youtube.com');
   }
   $pdf->Output($ruta, 'I'); //SALIDA DEL PDF
   //    $pdf->Output('original_update.pdf', 'F');
   //    $pdf->Output('original_update.pdf', 'I'); //PARA ABRIL EL PDF EN OTRA VENTANA
   //	  $pdf->Output('original_update.pdf', 'D'); //PARA FORZAR LA DESCARGA
   
   ?> 