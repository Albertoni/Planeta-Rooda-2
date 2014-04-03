<?php
/* Tentar manter este arquivo o menor possivel, tendo apenas o codigo que diferencia uma funcionalidade da outra */
require_once("../../comentarios.class.php");
/*---------------------------------------------------------------
	PARA PORTAR PARA OUTRA FUNCIONALIDADE, MUDAR SOMENTE ABAIXO
------------------------------------------ --------------------*/
// tabela onde ficam os comentarios dessa funcionalidade
Comentario::$tabelaBD = "PlayerComentarios";
// para verificação de permissões: identificador da funcionalidade e nome das permissoes
define('FUNCIONALIDADE',      TIPOPLAYER);
define('PERM_COMENT_VER',     'player_verComentario');
define('PERM_COMENT_INSERIR', 'player_inserirComentario');
define('PERM_COMENT_EXCLUIR', 'player_excluirComentario');
// recebe o id do recurso que pode ter comentarios e retorna o id da turma que ele pertence

require_once("player_aux.php");
function tituloDaRef($idRef){
	$video = new video(true, (int)$idRef);
	return $video->getTitulo();
}
function usuarioDaRef($idRef){
	$video = new video(true, (int)$idRef);
	return $video->getUsuario(); // isso retorna a id
}
function turmaDaRef($idRef){
	$video = new video(true, (int)$idRef);
	return $video->getTurma();
}
/*---------------------------------------------------------------
	NAO MUDAR NADA ABAIXO SE FOR APENAS PORTAR
---------------------------------------------------------------*/
require_once("../../comentarios.json.php");
