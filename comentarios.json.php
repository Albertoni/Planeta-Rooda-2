<?php

if (!class_exists("Comentario") || !function_exists("turmaDaRef")) {
	exit("Uso inadequado.");
}

/** 
* permissoesComentarios(@param idUsuario, @param idTurma)
* @return array(
*         	'visualizar' => bool,
*         	'comentar' => bool, 
*         	'excluir' => bool
*         )
*/ 
function permissoesComentarios($usuario,$turma) {
	global $funcionalidade;
	global $permissaoVer;
	global $permissaoComentar;
	global $permissaoExcluir;
	global $json;

	if (is_numeric($usuario)) {
		$usuario_id = (int) $usuario;
		$usuario = new Usuario();
		$usuario->openUsuario($usuario_id);
	}

	if (!is_object($usuario))
		throw new Exception("Error Processing Request", 1);

	if (get_class($usuario) !== 'Usuario')
		throw new Exception("Error Processing Request", 1);
	
	$return  = array('ver' => false, 'comentar' => false, 'excluir' => false);;
	$perm = checa_permissoes($funcionalidade, $turma);
	if ($perm) {
		if ($usuario->podeAcessar($perm[$permissaoVer], $turma)) {
			$return['ver'] = true;
		}
		if ($usuario->podeAcessar($perm[$permissaoComentar], $turma)) {
			$return['comentar'] = true;
		}
		if ($usuario->podeAcessar($perm[$permissaoExcluir], $turma)) {
			$return['excluir'] = true;
		}
	}
	return $return;
}

// ============================================================================

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


// keep it simple, stupid!
switch ($acao) {
	// retorna usuario com permissoes nos comentarios
	case 'verificar':
		$turma = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
		$json['usuario']['permissoes'] = $permissoesComentarios($usuario, $turma);
		break;

	case 'listar':
		$turma = turmaDaRef($idRef);
		if (!$usuario->pertenceTurma($turma)) {
			$json['erro'] = 'Você não tem permissão para acessar esse recurso.';
			break;
		}
		$permissoes = permissoesComentarios($usuario, $turma);
		if (!$permissoes['ver']) {
			$json['erro'] = 'Você não tem permissão para ver estes comentários.';
			break;
		}
		$json['idRef'] = $idRef;
		$json['turma'] = $turma;
		$json['usuario']['permissoes'] = $permissoes;
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
		$permissoes = permissoesComentarios($usuario, $turma);
		if (!$permissoes['comentar']) {
			$json['erro'] = 'Você não tem permissão para comentar aqui.';
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
		try {
			$comentario = new Comentario($id);
		} catch (Exception $e) {
			$json['erro'] = $e->getMessage();
			break;
		}
		$turma = turmaDaRef($idRef);
		$json['turma'] = $turma;
		if (!$usuario->pertenceTurma($turma)) {
			$json['erro'] = 'Você não tem permissão para acessar esse recurso.';
			break;
		}
		$permissoes = permissoesComentarios($usuario, $turma);
		if (!$permissoes['excluir']) {
			$json['erro'] = 'Você não tem permissão para excluir esse comentário.';
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