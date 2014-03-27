<?php
require_once('../../cfg.php');
require_once('../../bd.php');
require_once('../../funcoes_aux.php');
require_once('../../usuarios.class.php');
require_once('../../turma.class.php');
require_once('material.class.new.php');
$idMaterial = isset($_GET['id']) ? (int) $_GET['id'] : 0;
// abre usuario
$usuario = usuario_sessao();
if (!$usuario) {
	// não está logado
	echo "erro: voc&ecirc; n&atilde.o est&aacute; autenticado.";
	exit;
}
$idUsuario = $usuario->getId();
if ($idMaterial <= 0) {
	// id de material inválido
	echo "erro: referencia inv&aacute;lida";
	exit;
}
// abre material
$material = new Material($idMaterial);
if ($material->temErros()) {
	// algum problema com o material (talvez o material não existe).
	echo "erro: erro no material.";
	exit;
}
// abre turma do material
$idTurma = $material->getIdTurma();
$turma = new turma($idTurma);
if ($turma->getId() !== $idTurma) {
	// turma nao existe.
	echo "erro: material pertence a turma inexistente.";
	exit;
}
if(!usuarioPertenceTurma($idUsuario, $idTurma)) {
	// usuário não pertence a turma.
	echo "erro: voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar este material.";
	exit;
}
// verifica permissões da turma
$perm = checa_permissoes(TIPOBIBLIOTECA, $idTurma);
if ($perm === false) {
	// funcionalidade desabilitada na turma.
	echo "erro: a biblioteca foi desabilitada para esta turma.";
	exit;
}

// permissão concedids: joga o material no usuario.
switch ($material->getTipo()) {
	case MATERIAL_ARQUIVO:
		$arquivo = $material->getArquivo()->baixar();
		break;
	case MATERIAL_LINK:
		redireciona_externo($material->getConteudoMaterial());
		break;
	default:
		break;
}