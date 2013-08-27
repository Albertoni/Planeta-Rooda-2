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
} else {
	$json['session'] = $usuario->getSimpleAssoc();
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
	global $turma;
	global $turmaId;
	global $usuario;
	global $mais_velho;
	global $mais_novo;
	global $perm;
	$opcoes = 
		['turma'      => $turmaId,
		 'mais_velho' => $mais_velho,
		 'mais_novo'  => $mais_novo];
	if($usuario->podeAcessar($perm['biblioteca_aprovarMateriais'], $turmaId)) {
		$opcoes['nao_aprovados'] = true;
		$json['pode_aprovar'] = true;
	}
	$json['materiais'] = [];
	$material = new Material();
	try {
		$ok = $material->abrirTurma($opcoes);
	} catch (Exception $e) {
		$json['errors'][] = $e->getMessage();
		return;
	}
	if ($ok === true) do {
		$json['materiais'][] = $material->getAssoc();
	} while ($material->proximo());
}
function enviar() {
	global $json;
	global $turmaId;
	global $usuario;
	global $_POST;
	global $_FILES;
	global $perm;
	if (!$usuario->podeAcessar($perm['biblioteca_enviarMateriais'],$turmaId)) {
		$json['errors'][] = 'Você não tem permissão para enviar materiais nesta biblioteca.';
		return;
	}
	$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
	$autor = isset($_POST['autor']) ? trim($_POST['autor']) : '';
	$tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';
	$link = isset($_POST['link']) ? trim($_POST['link']) : '';
	if ($titulo === '') {
		$json['errors'][] = "Não pode enviar material sem título";
	};
	$material = new Material();
	$material->setUsuario($usuario);
	$material->setTurma($turmaId);
	$material->setTitulo($titulo);
	$material->setAutor($autor);
	if (isset($_POST['tipo'])) 
	{
		switch ($_POST['tipo']) {
			case MATERIAL_ARQUIVO:
				if (isset($_POST['arquivo']) && is_numeric($_POST['arquivo'])) {
					$arquivo = new Arquivo((int) $_POST['arquivo']);
					if (!$arquivo->temErros()) {
						$material->setMaterial($arquivo);
					} else {
						$json['errors'] = '[enviar] Arquivo nao encontrado';
						return;
					}
				}
				elseif (!isset($_FILES['arquivo'])) {
					//print_r($_FILES);
					$json['errors'][] = "[enviar] Nenhum arquivo enviado.";
					return;
				} else {
					$material->setMaterial($_FILES['arquivo']);
				}
				break;
			case MATERIAL_LINK:
				if($link === '') {
					$json['errors'][] = "[enviar] Nenhum link enviado";
					return;
				} else {
					$material->setMaterial($link);
				}
				break;
			default:
				$json['errors'][] = "[enviar] Não foi possivel enviar o material.";
				break;
		}
		if (!$material->temErros()) {
			$material->salvar();
		}
		if ($material->temErros()) {
			$er = $material->getErros();
			foreach ($er as $error) {
				$json['errors'][] = $error;
			}
		} else {
			$json['material'] = $material->getAssoc();
		}
	} else {
		$json['errors'][] = "Não foi possivel enviar o material.";
	}
}
function editar() {
	global $json;
	global $turmaId;
	global $usuario;
	global $_POST;
	global $_FILES;
	if (!$usuario->podeAcessar($perm['biblioteca_editarMateriais'],$turmaId)) {
		$json['errors'][] = 'Você não tem permissão para editar materiais nesta biblioteca.';
		return;
	}
	$id     = isset($_POST['id'])     ? trim($_POST['id'])     : 0;
	$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
	$autor  = isset($_POST['autor'])  ? trim($_POST['autor'])  : '';
	$tags   = isset($_POST['tags'])   ? trim($_POST['tags'])   : '';
	$link   = isset($_POST['link'])   ? trim($_POST['link'])   : '';
	$tipo   = isset($_POST['tipo'])   ? trim($_POST['tipo'])   : '';
	$material = new Material($id);
	if ($material->temErros()) {
		$json['errors'] = array_merge($json['errors'], $material->getErros());
		return;
	}
	if ($titulo !== '')
		$material->setTitulo($titulo);
	if ($autor !== '')
		$material->setAutor($autor);
	if ($tags !== '')
		$material->setTags($tags);
	switch ($tipo) {
		case MATERIAL_ARQUIVO:
			if (isset($_POST['arquivo'])) {
				$arquivo = new Arquivo((int) $_POST['arquivo']);
				if ($arquivo->temErros()) {
					$json['errors'] = array_merge($json['errors'], $arquivo->getErros());
					return;
				}
			}
			elseif (isset($_FILES['arquivo'])) {
				$material->setMaterial($_FILES['arquivo']);
			}
			break;
		case MATERIAL_LINK:
			if (isset($_POST['link'])) {
				if (is_numeric($_POST['link'])) {
					//
				}
			}
			break;
		default: 
			$json['errors'][] = 'Tipo de material inválido.';
			return;
	}
	$material->salvar();
	if ($material->temErros()) {
		$json['errors'] = array_merge($json['errors'], $material->getErros());
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