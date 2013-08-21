<?php
/*
 * funcionalidades/biblioteca/biblioteca.json.php
 */
require_once('../../cfg.php');
require_once('../../bd.php');
require_once('../../funcoes_aux.php');
require_once('../../usuarios.class.php');
require_once('../../turma.class.php');
require_once('material.class.new.php');
header("Content-Type: application/json");
$usuario = usuario_sessao();
$turmaId = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
$acao = isset($_GET['acao']) ? trim($_GET['acao']) : "";
// se as duas variaveis abaixo forem 0, deve mandar os 10 materiais mais novos.
// se definido o cliente pede por materiais mais novos do que o id nessa variavel
$mais_novo = isset($_GET['mais_novo']) ? (int) $_GET['mais_novo'] : 0;
// se definido o cliente pede para carregar materiais mais antigos que o id dessa variavel
$mais_velho = isset($_GET['mais_velho']) ? (int) $_GET['mais_velho'] : 0;
$turma = new turma($turmaId);
$json['turma'] = $turmaId;
$json['session'] = true;
if ($usuario === false) {
	$json['session'] = false;
}
if ($json['session']) {}
if ($turma->getId() !== $turmaId) {
	$json['errors'][] = "Turma não definida";
} else {
	$perm = checa_permissoes(TIPOBIBLIOTECA, $turmaId);
	if ($perm === false) {
		$json['errors'][] = "Biblioteca desabilitada para esta turma.";
	}
}
function listar($mais_novo = 0, $mais_velho = 0) {
	global $json;
	global $turmaId;
	global $usuario;
	global $mais_velho;
	global $mais_novo;
	global $turma;

	$material = new Material();
	try {
		$ok = $material->abrirTurma(['turma' => $turmaId , 'mais_velho' => $mais_velho, 'mais_novo' => $mais_novo]);
	} catch (Exception $e) {
		$json['errors'][] = $e->getMessage();
		return;
	}
	if ($ok);
	{
		do {
			$json['materiais'][] = $material->getAssoc();
		} while ($material->proximo());
	}
}
function enviar() {
	global $json;
	global $turmaId;
	global $usuario;
	global $_POST;
	global $_FILE;
	$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
	$autor = isset($_POST['autor']) ? trim($_POST['autor']) : '';
	$tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';
	if ($titulo === '') {
		$json['erros'][] = "Não pode enviar material sem título";
	};

	if (isset($_POST['tipo'])) switch ($_POST['tipo']) {
		case 'a':
			break;
		case 'l':
			break;
		default:
			$json['erros'][] = "Não foi possivel enviar o arquivo.";
			break;
	}
}
if($json['session'] && !isset($json['errors'])) {
	$json['session'] = true;
	switch ($acao) {
		case 'listar':
			listar($mais_novo, $mais_velho);
			break;
		case 'enviar':
			enviar();
			break;
		default:
			break;
	}
}
echo json_encode($json);