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
define('PERM_COMENT_VER',     'arte_verComentarios');
define('PERM_COMENT_INSERIR', 'arte_inserirComentarios');
define('PERM_COMENT_EXCLUIR', 'arte_excluirComentarios');

function tituloDaRef($idRef) {
	$bd = new conexao();
	$bd->solicitar("SELECT Titulo FROM ArtesDesenhos WHERE CodDesenho = $idRef");
	if ($bd->resultado['Titulo'])
		return (int) $bd->resultado['Titulo'];
	else
		return false;
}
function usuarioDaRef($idRef) {
	$bd = new conexao();
	$bd->solicitar("SELECT CodUsuario FROM ArtesDesenhos WHERE CodDesenho = $idRef");
	if ($bd->resultado['CodUsuario'])
		return (int) $bd->resultado['CodUsuario'];
	else
		return false;
}
// recebe o id do recurso que pode ter comentarios e retorna o id da turma que ele pertence
function turmaDaRef($idRef) {
	$bd = new conexao();
	$bd->solicitar("SELECT CodTurma FROM ArtesDesenhos WHERE CodDesenho = $idRef");
	if ($bd->resultado['CodTurma'])
		return (int) $bd->resultado['CodTurma'];
	else
		return false;
}
/*---------------------------------------------------------------
	NAO MUDAR NADA ABAIXO SE FOR APENAS PORTAR
------------------------------------------ --------------------*/
require_once("../../comentarios.json.php");
?>