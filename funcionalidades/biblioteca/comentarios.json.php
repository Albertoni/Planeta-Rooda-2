<?php
/* Tentar manter este arquivo o menor possivel, tendo apenas o codigo que diferencia uma funcionalidade da outra */
require_once("../../comentarios.class.php");
/*---------------------------------------------------------------
	PARA PORTAR PARA OUTRA FUNCIONALIDADE, MUDAR SOMENTE ABAIXO
------------------------------------------ --------------------*/
// tabela onde ficam os comentarios dessa funcionalidade
Comentario::$tabelaBD = "BibliotecaComentarios";
// para verificação de permissões: identificador da funcionalidade e nome das permissoes
define('FUNCIONALIDADE',      TIPOBIBLIOTECA);
define('PERM_COMENT_VER',     'biblioteca_verComentarios');
define('PERM_COMENT_INSERIR', 'biblioteca_inserirComentarios');
define('PERM_COMENT_EXCLUIR', 'biblioteca_excluirComentarios');
// recebe o id do recurso que pode ter comentarios e retorna o id da turma que ele pertence
require_once("material.class.new.php");
function turmaDaRef($idRef) {
	$material = new Material($idRef);
	return $material->getIdTurma();
}
/*---------------------------------------------------------------
	NAO MUDAR NADA ABAIXO SE FOR APENAS PORTAR
------------------------------------------ --------------------*/
require_once("../../comentarios.json.php");
?>