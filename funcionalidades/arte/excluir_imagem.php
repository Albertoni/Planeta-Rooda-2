<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("desenho.class.php");

$user = usuario_sessao();
$id = (int) (isset($_POST['desenho']) && is_numeric($_POST['desenho'])) ? $_POST['desenho'] : die("Erro. Tente de novo mais tarde.");

$permissoes = checa_permissoes(TIPOPORTFOLIO, $turma);
if($permissoes === false){
	die("O Planeta Arte est&aacute; desabilitado para a sua turma.");
}

if($user->podeAcessar($permissoes['arte_excluirDesenho'])){
	if ($id != 0){
		$DESENHO = new Desenho($id);
		$resultado = $DESENHO->excluir();
	}else{
		$resultado = "Id de desenho inválida (0).";
	}
}else{
	$resultado = "Voc&ecirc; n&atilde;o pode excluir desenhos.";
}

echo $resultado;