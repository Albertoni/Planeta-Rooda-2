<?php
require_once("../../comentarios.class.php");
/**
	PARA PORTAR PARA OUTRA FUNCIONALIDADE, MUDAR SOMENTE ABAIXO
*/
require_once("material.class.new.php");
// função que pega o id da turma de acordo com o id do objeto que pode ter comentarios.
function turmaDaRef($idRef) {
	$material = new Material($idRef);
	return $material->getIdTurma();
}

// para verificação de permissões: identificador da funcionalidade e nome das permissoes
$funcionalidade = TIPOBIBLIOTECA;
$permissaoVer = 'biblioteca_verComentarios';
$permissaoComentar = 'biblioteca_inserirComentarios';
$permissaoExcluir = 'biblioteca_excluirComentarios';

// tabela onde ficam os comentarios dessa funcionalidade
Comentario::$tabelaBD = "BibliotecaComentarios";
/** 
	NAO MUDAR NADA ABAIXO SE FOR APENAS PORTAR
*/
require_once("../../comentarios.json.php");
?>