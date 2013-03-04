<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("aula.class.php");

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}

$permissoes = checa_permissoes(TIPOPLAYER, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

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
?>
