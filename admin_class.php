<?php
include 'config.php';
session_start();
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   		include_once 'db_connect.php';
		$dbInstance = Database::getInstance();
	   	$this->db = $dbInstance->getConnection();
		
    	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
	
		// Obtener la información del usuario
		$qry = $this->db->query("SELECT * FROM users WHERE username = '".$username."'");
	
		if($qry->num_rows > 0){
			$user_data = $qry->fetch_assoc();
	
			$mensaje_encriptado = $user_data['password'];
			$datos = base64_decode($mensaje_encriptado);
			$iv = substr($datos, 0, openssl_cipher_iv_length("aes-128-cbc"));
			$mensaje_encriptado = substr($datos, openssl_cipher_iv_length("aes-128-cbc"));
			$decrypted_password = openssl_decrypt($mensaje_encriptado, "aes-128-cbc", CLAVE_SECRETA, 0, $iv);
	
			// Eliminar espacios en blanco al final de la cadena
			$decrypted_password = trim($decrypted_password);
	
			// Verificar si la contraseña proporcionada coincide con la almacenada
			if ($decrypted_password === $password) {
				// Contraseña correcta, establecer variables de sesión
				foreach ($user_data as $key => $value) {
					if(!is_numeric($key)) {
						$_SESSION['login_'.$key] = $value;
					}
				}
				return 1; // Inicio de sesión exitoso
			} else {
				return 2; // Contraseña incorrecta
			}
		} else {
			return 3; // Usuario no encontrado
		}
	}
	
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	

	function save_folder(){
		extract($_POST);
		$data = " name ='".$name."' ";
		if (isset($receptor_id) && $receptor_id =="") {
							$data .= ", receptor_id = NULL ";
						} else {
							$data .= ", receptor_id = '".$receptor_id."' ";
						}
		$data .= ", parent_id ='".$parent_id."' ";
		if(empty($id)){
			$data .= ", user_id ='".$_SESSION['login_id']."' ";
			
			$check = $this->db->query("SELECT * FROM folders where user_id ='".$_SESSION['login_id']."' and name  ='".$name."' and parent_id=".$parent_id)->num_rows;
			if($check > 0){
				return json_encode(array('status'=>2,'msg'=> 'El nombre de la carpeta ya existe'));
			}else{
				$save = $this->db->query("INSERT INTO folders set ".$data);
				if($save)
				return json_encode(array('status'=>1));
			}
		}else{
			$check = $this->db->query("SELECT * FROM folders where user_id ='".$_SESSION['login_id']."' and name  ='".$name."' and id !=".$id." and parent_id=".$parent_id)->num_rows;
			if($check > 0){
				return json_encode(array('status'=>2,'msg'=> 'El nombre de la carpeta ya existe'));
			}else{
				$save = $this->db->query("UPDATE folders set ".$data." where id =".$id);
				if($save)
				return json_encode(array('status'=>1));
			}

		}
	}

	function delete_folder(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM folders where id =".$id);
		if($delete)
			echo 1;
	}
	function delete_file(){
		extract($_POST);
		$path = $this->db->query("SELECT file_path from files where id=".$id)->fetch_array()['file_path'];
		$delete = $this->db->query("DELETE FROM files where id =".$id);
		if($delete){
					unlink('assets/uploads/'.$path);
					return 1;
				}
	}

	function delete_user(){
		extract($_POST);
		
		//$delete = $this->db->query("DELETE FROM users where id = ".$id);
		$delete = $this->db->prepare("CALL sp_eliminarUsuario(?)");
		$delete->bind_param("i", $id); 
		$delete->execute();
		$delete->close();

		if($delete)
			return 1;
	}

	function save_files(){
		extract($_POST);
		if(empty($id)){
		 		if($_FILES['upload']['tmp_name'] != ''){
					$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['upload']['name'];
					$move = move_uploaded_file($_FILES['upload']['tmp_name'],'assets/uploads/'. $fname); 
					if($move){
						$file = $_FILES['upload']['name'];
						$file = explode('.',$file);
						$chk = $this->db->query("SELECT * FROM files where SUBSTRING_INDEX(name,' ||',1) = '".$file[0]."' and folder_id = '".$folder_id."' and file_type='".$file[1]."' ");
						if($chk->num_rows > 0){
							$file[0] = $file[0] .' ||'.($chk->num_rows);
						}
						$data = " name = '".$file[0]."' ";
						$data .= ", folder_id = '".$folder_id."' ";
						$data .= ", description = '".$description."' ";
						if (isset($receptor_id) && $receptor_id =="") {
							$data .= ", receptor_id = NULL ";
						} else {
							$data .= ", receptor_id = '".$receptor_id."' ";
						} 
						$data .= ", user_id = '".$_SESSION['login_id']."' ";
						$data .= ", file_type = '".$file[1]."' ";
						$data .= ", file_path = '".$fname."' ";
						$data .= ", op_descargar = '" . $descargar . "'";
						if(isset($is_public) && $is_public == 'on')
						$data .= ", is_public = 1 ";
						else
						$data .= ", is_public = 0 "; 
						$save = $this->db->query("INSERT INTO files set ".$data);
						if($save)
						return json_encode(array('status'=>1)); 
					} 
				}
		}else{ 	
			$data = " description = '".$description."' ";
			 
			// $data = " receptor_id = '".$receptor_id."' ";
			$data .= ", op_descargar = '".$descargar."'";
			if(isset($is_public) && $is_public == 'on')
			$data .= ", is_public = 1 ";
			else
			$data .= ", is_public = 0 ";
			if (isset($receptor_id) && $receptor_id =="") 
				$data .= ", receptor_id = NULL ";
				else 
				$data .= ", receptor_id = '".$receptor_id."' ";
			// echo $data;
			$save = $this->db->query("UPDATE files set ".$data. " where id=".$id);
			if($save)
			return json_encode(array('status'=>1));
		}

	}
	function file_rename(){
		extract($_POST);
		$file[0] = $name;
		$file[1] = $type;
		$chk = $this->db->query("SELECT * FROM files where SUBSTRING_INDEX(name,' ||',1) = '".$file[0]."' and folder_id = '".$folder_id."' and file_type='".$file[1]."' and id != ".$id);
		if($chk->num_rows > 0){
			$file[0] = $file[0] .' ||'.($chk->num_rows);
			}
		$save = $this->db->query("UPDATE files set name = '".$name."' where id=".$id);
		if($save){
				return json_encode(array('status'=>1,'new_name'=>$file[0].'.'.$file[1]));
		}
	}
	
	function save_user(){
		extract($_POST);
        $name_data = " name = '$name' ";
		$username_data = " username = '$username' ";
		$password_data = " password = '$password' ";
		$type_data = " type = '$type' ";
	
	
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-128-cbc"));
        $mensaje_encriptado = openssl_encrypt($password, "aes-128-cbc", CLAVE_SECRETA, 0, $iv);
        $encrypted_password = base64_encode($iv . $mensaje_encriptado);

	
		if(empty($id)){
			/* $save = $this->db->query("INSERT INTO users set ".$data); */
			$stmt = $this->db->prepare("CALL sp_insertarUsuario(?, ?, ?, ?)");
			$stmt->bind_param("ssss", $name, $username, $encrypted_password, $type);
			$stmt->execute();
			$stmt->close();

			
		}else{
			/* $save = $this->db->query("UPDATE users set ".$data." where id = ".$id); */
			// Actualizar usuario utilizando el procedimiento almacenado
			$stmt = $this->db->prepare("CALL sp_actualizarUsuario(?, ?, ?, ?, ?)");
			$stmt->bind_param("issss", $id, $name, $username, $encrypted_password, $type);
			$stmt->execute();
			$stmt->close();
		}
		if($stmt){
			return 1;
		}

	}	


		
}

