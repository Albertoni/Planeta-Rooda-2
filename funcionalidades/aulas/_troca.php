<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("aula.class.php");

$usuario = usuario_sessao();

if (isset($_POST['turma']) and is_numeric($_POST['turma'])){
	$turma = $_POST['turma'];
}

$permissoes = checa_permissoes(TIPOAULA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if($usuario->podeAcessar($permissoes['aulas_editarAulas'], $turma)){
	$a = new aula();
	$a->abreAula($_POST['a1']);
	$erro = $a->getErro();
	if ($erro != "") die($erro);

	$a->trocaPosicoes($_POST['a1'], $_POST['a2']);
	$erro = $a->getErro();
	if ($erro != "") die($erro);
	
	echo "314159265";
}else{
	echo "Opa, você precisa ter permissão de editar aulas para editar a posição delas.";
}
