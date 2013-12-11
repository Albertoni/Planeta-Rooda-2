<?php

if (!class_exists("Comentario") || !function_exists("turmaDaRef")) {
	exit("Uso inadequado.");
}

header("Content-Type: application/json; charset=UTF-8");
$json = array();

$usuario = usuario_sessao();
if ($usuario === false) {
	exit('{"erro":"você não está logado","usuario":false}');
}
$json['usuario'] = $usuario->getSimpleAssoc();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$idRef = isset($_GET['idRef']) ? (int) $_GET['idRef'] : 0;
$acao = isset($_GET['acao']) ? trim($_GET['acao']) : "";
$mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : "";

switch ($acao) {
	case 'listar':
		$turma = turmaDaRef($idRef);
		if (!$usuario->pertenceTurma($turma)) {
			$json['erro'] = 'Você não tem permissão para acessar esse recurso.';
			break;
		}
		$json['idRef'] = $idRef;
		$json['turma'] = $turma;
		$json['comentarios'] = array();
		$comentario = new Comentario();
		$comentario->abrirComentarios($idRef);
		while ($comentario->existe()) {
			$json['comentarios'][] = $comentario->getAssoc();
			$comentario->proximo();
		}
		break;
	
	case 'enviar':
		$turma = turmaDaRef($idRef);
		if (!$usuario->pertenceTurma($turma)) {
			$json['erro'] = 'Você não tem permissão para acessar esse recurso.';
			break;
		}
		$json['turma'] = $turma;
		$comentario = new Comentario();
		try {
			$comentario->setIdRef($idRef);
			$comentario->setUsuario($usuario);
			$comentario->setMensagem($mensagem);
			$comentario->salvar();
		} catch (Exception $e) {
			$json['erro'] = $e->getMessage();
			break;
		}
		$json['comentario'] = $comentario->getAssoc();
		break;
	
	case 'excluir':
		$comentario = new Comentario($id);
		$turma = turmaDaRef($idRef);
		$json['turma'] = $turma;
		if (!$usuario->pertenceTurma($turma)) {
			$json['erro'] = 'Você não tem permissão para acessar esse recurso.';
			break;
		}
		try {
			$comentario->excluir();
		} catch (Exception $e) {
			$json['erro'] = $e->getMessage();
			break;
		}
		$json['comentarioExcluido'] = $id;
		break;
	
	default:
		$json['erro'] = 'Nenhuma ação foi solicitada.';
		break;
}
echo json_encode($json);
?>