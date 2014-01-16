<?php
/* Tentar manter este arquivo o menor possivel, tendo apenas o codigo que diferencia uma funcionalidade da outra */
require_once("../../comentarios.class.php");
/*---------------------------------------------------------------
	PARA PORTAR PARA OUTRA FUNCIONALIDADE, MUDAR SOMENTE ABAIXO
------------------------------------------ --------------------*/
// tabela onde ficam os comentarios dessa funcionalidade
Comentario::$tabelaBD = "ArteComentarios";
// para verificação de permissões: identificador da funcionalidade e nome das permissoes
define('FUNCIONALIDADE',      TIPOARTE);
define('PERM_COMENT_VER',     'player_verComentarios');
define('PERM_COMENT_INSERIR', 'player_inserirComentarios');
define('PERM_COMENT_EXCLUIR', 'player_excluirComentarios');
// recebe o id do recurso que pode ter comentarios e retorna o id da turma que ele pertence
// require("portfolio.class.php");
function tituloDaRef($idRef) {}
function usuarioDaRef($idRef) {}
function turmaDaRef($idRef) {}
/*---------------------------------------------------------------
	NAO MUDAR NADA ABAIXO SE FOR APENAS PORTAR
------------------------------------------ --------------------*/
require_once("../../comentarios.json.php");
?>