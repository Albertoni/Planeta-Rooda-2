<?php
/*
 * funcionalidades/biblioteca/biblioteca.json.php
 */
require_once('../../cfg.php');
require_once('../../bd.php');
require_once('../../user.class.php')
require_once('../../turma.class.php')
header("Content-Type: application/json");
session_start();

$json = array();

$usuario_id = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
$turmaId = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
$acao = isset($_GET['action']) ? (int) $_GET['action'] : "";
$ultimoId = isset($_GET['ultimo']) ? (int) $_GET['ultimo'] : 0;
$usuario = new Usuario();
$usuario->openUsuario($usuario_id);
$turma = new turma($turmaId);


function listar($ultimoId = 0) {
	global $turmaId;
	global $usuario;
	$perm = checa_permissoes(TIPOBIBLIOTECA, $turmaId);
	if ($perm === false) {
		$json['errors'][] = "Portfólio desabilitado para esta turma.";
	} else {

	}
	return $json;
}

if (!$usuario_id) {
	$json['session'] = false;
} else {
	$json['session'] = true;
	switch ($acao) {
		case 'listar':
			$json = listar();
			
			break;
		case 'enviar':
			# code...
			break;
		default:
			# code...
			break;
	}
}
echo json_encode($json);
class Material {
	private $id;
	private $titulo;
	private 
}