<?php
/*
 * funcionalidades/biblioteca/biblioteca.json.php
 */
//try {
require_once('../../cfg.php');
require_once('../../bd.php');
require_once('../../funcoes_aux.php');
require_once('../../usuarios.class.php');
require_once('../../turma.class.php');
require_once('material.class.new.php');
header("Content-Type: application/json; charset=UTF-8");
$usuario = usuario_sessao();
$acao = isset($_GET['acao']) ? trim($_GET['acao']) : "";
$id = isset($_GET['material']) ? (int) $_GET['material'] : 0;
if ($usuario === false) {
	$json['session'] = false;
	$json['errors'][] = "Você não está autenticado.";
} else {
	$idUsuario = $usuario->getId();
	$json['session'] = $usuario->getSimpleAssoc();
}
function listar() {
	global $json;
	global $usuario;
	global $_GET;
	$idTurma = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
	$turma = new turma();
	$turma->openTurma($idTurma);
	$idUsuario = $usuario->getId();
	if ($turma->getId() !== $idTurma) {
		$json['errors'][] = "Turma não definida";
		return;
	}
	if(!usuarioPertenceTurma($idUsuario, $idTurma)) {
		// usuário não pertence a turma.
		$json['errors'][] = "erro: voc&ecirc; n&atilde;o est&aacute; nesta setTurma.";
		return;
	} else {
		$perm = checa_permissoes(TIPOBIBLIOTECA, $idTurma);
		if ($perm === false) {
			$json['errors'][] = "Biblioteca desabilitada para esta turma.";
			return;
		}
	}


	// se as duas variaveis abaixo forem 0, deve mandar os 10 materiais mais novos.
	// se definido o cliente pede por materiais mais novos do que o id nessa variavel
	$mais_novo = isset($_GET['mais_novo']) ? (int) $_GET['mais_novo'] : 0;
	// se definido o cliente pede para carregar materiais mais antigos que o id dessa variavel
	$mais_velho = isset($_GET['mais_velho']) ? (int) $_GET['mais_velho'] : 0;
	// id da turma para listar
	$opcoes = array('turma'      => $idTurma,
		 'usuario'    => $usuario->getId(),
		 'mais_velho' => $mais_velho,
		 'mais_novo'  => $mais_novo);

	if($usuario->podeAcessar($perm['biblioteca_aprovarMateriais'], $idTurma)) {
        $opcoes['nao_aprovados'] = true;
		$json['pode_aprovar'] = true;
	}
	if($usuario->podeAcessar($perm['biblioteca_excluirArquivos'], $idTurma)) {
		$json['pode_excluir'] = true;
	}
	if($usuario->getNivel($idTurma) != NIVELALUNO) { // Alunos não podem editar: Vide ata de 2 Abril
		$json['pode_editar'] = true;
	}
	$json['materiais'] = array();
	$material = new Material();
	try {
		$ok = $material->abrirTurma($opcoes);
	} catch (Exception $e) {
		$json['errors'][] = $e->getMessage();
		return;
	}
	if ($material->registros() < 10 && $mais_velho > 0) {
		$json['todos'] = true;
	}
	if ($ok === true) 
	{
		do {
			$json['materiais'][] = $material->getAssoc();
		} while ($material->proximo());
	}
}
function enviar() {
	global $json;
	global $usuario;
	global $_GET;
	global $_POST;
	global $_FILES;
	global $perm;
	$idUsuario = $usuario->getId();
	$idTurma = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
	$turma = new turma($idTurma);
	$json['success'] = false;
	if(!usuarioPertenceTurma($idUsuario, $idTurma)) {
		// usuário não pertence a turma.
		$json['errors'][] = "erro: voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar este material.";
		return;
	}
    else{
		$perm = checa_permissoes(TIPOBIBLIOTECA, $idTurma);
		if ($perm === false) {
			$json['errors'][] = "Biblioteca desabilitada para esta turma.";
			return;
		}
    }

	if (!$usuario->podeAcessar($perm['biblioteca_enviarMateriais'],$idTurma)) {
		$json['errors'][] = 'Você não tem permissão para enviar materiais nesta biblioteca.'.$perm['biblioteca_enviarMateriais'];

		return;
	}
	$titulo = isset($_POST['titulo']) ? utf8_encode($_POST['titulo']) : '';
	$autor = isset($_POST['autor']) ? utf8_encode($_POST['autor']) : '';
	$tags = isset($_POST['tags']) ? utf8_encode($_POST['tags']) : '';
	$link = isset($_POST['link']) ? utf8_encode($_POST['link']) : '';
	if ($titulo === '') {
		$json['errors'][] = "Não pode enviar material sem título";
	};
	$material = new Material();
	$material->setUsuario($usuario);
	$material->setTurma($idTurma);
	$material->setTitulo($titulo);
	$material->setAutor($autor);
	$material->setTags($tags);
	if (isset($_POST['tipo'])) 
	{
		switch ($_POST['tipo']) {
			case MATERIAL_ARQUIVO:
				if (isset($_POST['arquivo']) && is_numeric($_POST['arquivo'])) {
					$arquivo = new Arquivo((int) $_POST['arquivo']);
					if (!$arquivo->temErros()) {
						$material->setMaterial($arquivo);
					} else {
						$json['errors'][] = '[enviar] Arquivo nao encontrado';
						return;
					}
				}
				elseif (!isset($_FILES['arquivo'])) {
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
			$json['success'] = true;
			//$json['material'] = $material->getAssoc();
		}
	} else {
		$json['errors'][] = "Não foi possivel enviar o material.";
	}
}
/* ------------- */
function editar() {
	global $json;
	global $usuario;
	global $turma;
	global $_POST;
	$id     = isset($_POST['id'])     ? (int) ($_POST['id'])     : 0;
	$titulo = isset($_POST['titulo']) ? utf8_encode($_POST['titulo']) : '';
	$autor  = isset($_POST['autor'])  ? utf8_encode($_POST['autor'])  : '';
	$tags   = isset($_POST['tags'])   ? utf8_encode($_POST['tags'])   : '';
	if (!$id) {
		$json['errors'][] = 'Náo foi possivel editar o material, referencia inválida.';
		return;
	}
	$material = new Material($id);
	if ($material->temErros()) {
		if (!isset($json['errors'])) $json['errors'] = array();
		$json['errors'] = array_merge($json['errors'], $material->getErros());
		return;
	}
	$idUsuario = $usuario->getId();
	$idTurma = $material->getIdTurma();
	$perm = checa_permissoes(TIPOBIBLIOTECA, $idTurma);
	
	
	if ($perm === false) {
		$json['errors'][] = "Biblioteca desabilitada para esta turma.";
		return;
	}

	//Comparar se usuario_nivel == 16,
	//	se for, não permite edição;
	//	senão, permite.
	$nivelUsuario = $usuario->getNivel($idTurma);
	
	if($nivelUsuario == NIVELALUNO){
		$json['errors'][] = 'Você não tem permissão para editar materiais.';
		return;
	}
	else{
			if ($idUsuario !== $material->getUsuario()->getId() && !$usuario->podeAcessar($perm['biblioteca_editarMateriais'],$idTurma)) {
				$json['errors'][] = 'Você não tem permissão para editar materiais nesta biblioteca.';
				return;
			}
			if ($titulo !== '')
				$material->setTitulo($titulo);
			if ($autor !== '')
				$material->setAutor($autor);
			if ($tags !== '')
				$material->setTags($tags);
				$material->salvar();
			if ($material->temErros()) {
				if (!isset($json['errors'])) $json['errors'] = array();
					$json['errors'] = array_merge($json['errors'], $material->getErros());
					$json['material'] = $material->getAssoc();
			} else {
				$json['material'] = $material->getAssoc();
			}
		}
}
/* ------------------ */
function excluir() {
	global $json;
	global $_GET;
	global $usuario;
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$material = new Material($id);
	if ($material->temErros()) {
		$json['errors'] = array_merge($json['errors'], $material->getErros());
		return;
	}
	if ($material->getId() === false) {
		// material nao existe
		$json['success'] = true;
		$json['id'] = $id;
		return;
	}
	$idUsuario = $usuario->getId();
	$idTurma = $material->getIdTurma();
	if(!usuarioPertenceTurma($idUsuario, $idTurma)) {
		// usuário não pertence a turma.
		$json['errors'][] = "erro: voc&ecirc; n&atilde;o est&aacute; nesta turma.";
		return;
	} else {
		$perm = checa_permissoes(TIPOBIBLIOTECA, $idTurma);
		if ($perm === false) {
			$json['errors'][] = "Biblioteca desabilitada para esta turma.";
			return;
		}
	}
	if ($idUsuario !== $material->getUsuario()->getId() && !$usuario->podeAcessar($perm['biblioteca_excluirArquivos'],$idTurma)) {
		$json['errors'][] = 'Você não tem permissão para excluir materiais nesta biblioteca.';
		return;
	}
	$material->excluir();
	$json['success'] = true;
	$json['id'] = $id;
}
function aprovar() {
	global $_GET;
	global $_POST;
	global $usuario;
	global $json;
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$material = new Material($id);
	if ($material->temErros()) {
		$json['errors'][] = "erro: Erro ao abrir material.";
		$json['errors'] = array_merge($json['errors'], $material->getErros());
		return;
	}
	$idUsuario = $usuario->getId();
	$idTurma = $material->getIdTurma();
	if (!usuarioPertenceTurma($idUsuario, $idTurma)) {
		// usuário não pertence a turma.
		$json['errors'][] = "erro: voc&ecirc; n&atilde;o est&aacute; nesta setTurma.";
		return;
	} else {
		$perm = checa_permissoes(TIPOBIBLIOTECA, $idTurma);
		if ($perm === false) {
			$json['errors'][] = "Biblioteca desabilitada para esta turma.";
			return;
		}
	}
	if (!$usuario->podeAcessar($perm['biblioteca_aprovarMateriais'],$idTurma)) {
		$json['errors'][] = 'Você não tem permissão para aprovar materiais nesta biblioteca.';
		return;
	}
	// APROVA
	$material->aprovar();
	if ($material->temErros()) {

		$json['errors'] = isset($json['errors']) ? array_merge($json['errors'], $material->getErros()) : $material->getErros();
		return;
	}
	$json['id'] = $material->getId();
	$json['success'] = true;
}
if($json['session'] && !isset($json['errors'])) {
	//$json['session'] = true;
	switch ($acao) {
		case 'listar':
			listar();
			break;
		case 'enviar':
			enviar();
			break;
		case 'aprovar':
			aprovar();
			break;
		case 'excluir':
			excluir();
			break;
		case 'editar':
			editar();
		default:
			break;
	}
}
echo json_encode($json);
//}
//catch (Exception $e) {
//	echo $e->getMessage();
//}

?>
