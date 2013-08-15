<?php
/*
 * funcionalidades/biblioteca/biblioteca.json.php
 */
require_once('../../cfg.php');
require_once('../../bd.php');
require_once('../../funcoes_aux.php');
require_once('../../user.class.php');
require_once('../../turma.class.php');
require_once('material.class.new.php');
header("Content-Type: application/json");
$usuario = usuario_sessao();
$json = array();
if ($usuario === false) {
	die(json_encode(array('user' => false, 'errors' = array('Você não está logado.'))));
}
$turmaId = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
$acao = isset($_GET['action']) ? (int) $_GET['action'] : "";
$ultimoId = isset($_GET['ultimo']) ? (int) $_GET['ultimo'] : 0;
$usuario = new Usuario();
$usuario->openUsuario($usuario_id);
$turma = new turma($turmaId);
function listar($ultimoId = 0) {
	global $json;
	global $turmaId;
	global $usuario;
	$perm = checa_permissoes(TIPOBIBLIOTECA, $turmaId);
	if ($perm === false) {
		$json['errors'][] = "Portfólio desabilitado para esta turma.";
	}
	return $json;
}
function enviar() {
	global $json;
	global $turmaId;
	global $usuario;
	global $_POST;
	global $_FILE;
	$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
	$autor = isset($_POST['autor']) ? trim($_POST['autor'] : '';
	$tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';
	if ($titulo === '') {
		$json['erros'][] = "Não pode enviar material sem título";
	};

	if (isset($_POST['tipo'])) switch ($_POST['tipo']) {
		case 'a':
			# code...
			break;
		case 'l':
		default:
			$json['erros'][] = "Não foi possivel enviar o arquivo.";
			break;
	}
}
if (!$usuario_id) {
	$json['session'] = false;
} else {
	$json['session'] = true;
	switch ($acao) {
		case 'listar':
			listar($ultimoId);
			break;
		case 'enviar':
			enviar();
			break;
		default:
			break;
	}
}
echo json_encode($json);