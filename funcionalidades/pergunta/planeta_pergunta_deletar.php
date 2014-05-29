<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

if (isset($_GET['id']) == false){die ("Voce precisa acessar esta pagina com um id de questionario. Por favor, <a href=\"planeta_pergunta.php\">volte</a> e tente novamente.");}

if (is_numeric($_GET['id']) == false){
	die ("O id de questionario passado nao &eacute; um numero. Nao sabemos o que aconteceu, mas pelo menos estamos lhe dando uma mensagem de erro amigavel. Por favor <a href=\"planeta_pergunta.php\">clique aqui para voltar</a> e tente novamente.");
}else{
	$id = $_GET['id'];
}

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}else{
	if (isset($_GET['turma'])){
		die('A id da turma nao &eacute um numero. Volte e tente novamente, provavelmente ir&aacute; consertar.');
	}else{
		die('A id de turma nao foi passada para a pagina. Por favor avise os desenvolvedores disso, junto com o seu nome e o que voce estava tentando fazer.');
	}
}

$usuario = usuario_sessao();

$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if(!$usuario->podeAcessar($permissoes['pergunta_deletarQuestionario'], $turma)){
	die('voce nao tem permissao para deletar questionarios nessa turma');
}

$p = new conexao();
$p->solicitar("DELETE FROM $tabela_PerguntaPerguntas WHERE id_questionario = $id");
//print_r($p);
$p->solicitar("DELETE FROM $tabela_PerguntaQuestionarios WHERE id = $id");
//print_r($p);
?>
<script>
	window.location = "planeta_pergunta.php";
</script>
