<?php
/* Tentar manter este arquivo o menor possivel, tendo apenas o codigo que diferencia uma funcionalidade da outra */
require_once("../../comentarios.class.php");
/*---------------------------------------------------------------
	PARA PORTAR PARA OUTRA FUNCIONALIDADE, MUDAR SOMENTE ABAIXO
------------------------------------------ --------------------*/
// tabela onde ficam os comentarios dessa funcionalidade
Comentario::$tabelaBD = "WebfolioComentarios";
// para verificação de permissões: identificador da funcionalidade e nome das permissoes
define('FUNCIONALIDADE',      TIPOBLOG);
define('PERM_COMENT_VER',     'blog_verComentarios');
define('PERM_COMENT_INSERIR', 'blog_inserirComentarios');
define('PERM_COMENT_EXCLUIR', 'blog_excluirComentarios');
// recebe o id do recurso que pode ter comentarios e retorna o titulo
function tituloDaRef($idRef) {}
// recebe o id do recurso que pode ter comentarios e retorna o id da turma que ele pertence
function usuarioDaRef($idRef) {}
// recebe o id do recurso que pode ter comentarios e retorna o id da turma que ele pertence
function turmaDaRef($idRef) {}
/*---------------------------------------------------------------
	NAO MUDAR NADA ABAIXO SE FOR APENAS PORTAR
------------------------------------------ --------------------*/
require_once("../../comentarios.json.php");
?>