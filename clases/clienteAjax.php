<?php
require_once '../config/database.php';
require_once 'clientefunciones.php';

$datos = [];


if(isset($_POST['action'])){
	$action = $_POST['action'];

	$db = new Database();
	$con = $db->conectar();

	if($action == 'existeUsuario'){
		
		$datos['ok'] = usuarioExiste($_POST['usuario'], $con);

	}elseif($action = 'existeEmail'){
		$datos['ok'] = emailExiste($_POST['email'], $con);

	}elseif($action = 'existedni'){
		$datos['ok'] = dniExiste($_POST['dni'], $con);

	}

}
echo json_encode($datos);