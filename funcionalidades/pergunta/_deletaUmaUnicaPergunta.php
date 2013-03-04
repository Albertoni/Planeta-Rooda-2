<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");

session_start();
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

if (!isset($_SESSION['SS_usuario_nivel_sistema'])){ // if not logged in
	die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");
}

if (isset($_POST['turma']) and is_numeric($_POST['turma'])){
	$turma = $_POST['turma'];
}

$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if (isset($_POST['questao']) and is_numeric($_POST['questao'])){
	$id = $_POST['questao'];
}

if(!$usuario->podeAcessar($permissoes['pergunta_editarQuestionario'], $turma)){
	die();
}else{
	$q = new conexao(); global $tabela_PerguntaPerguntas;
	//$q->solicitar("DELETE FROM $tabela_PerguntaPerguntas WHERE id = $id");

	if ($q->erro == ""){
		echo "ok";
	}else{
		echo "Erro!!1 Favor tentar novamente. ERRO: ".$a->getErro();
	}
}
?>
