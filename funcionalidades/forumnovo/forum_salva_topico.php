<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

session_start();

require_once("sistema_forum.php");

$user=$_SESSION['user'];

$turma = (int) $_POST['idTurma'];

$permissoes = checa_permissoes(TIPOFORUM, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

$pesquisa1 = new conexao();

$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : die("Precisa de um titulo para criar um topico!");
$idTopico = (isset($_POST['idTopico']) and is_numeric($_POST['idTopico'])) ? $_POST['idTopico'] : NULL;
$conteudo = str_replace("\n", "<br>", $_POST['texto']);
$idMensagem = (isset($_POST['idMensagem']) and is_numeric($_POST['idMensagem'])) ? $_POST['idMensagem'] : false;
$editar = ($idMensagem !== false);
$acaoSendoEfetuada =  $editar ? "forum_editarTopico" : "forum_criarTopico";
$userId = $_SESSION['SS_usuario_id'];


if($user->podeAcessar($permissoes[$acaoSendoEfetuada], $turma)){
	if(!$editar){ // CRIAÇÃO
		$topico = new topico(NULL, $turma, $userId, $titulo);
		$topico->insereMensagem($conteudo);
	}else{ // EDIÇÃO
		$topico = new topico($idTopico);

		$topico->setTitulo($titulo);
		$topico->setMensagem(0, $conteudo);
	}

	$erro = $topico->salvar();
	if($idTopico == NULL){//criando
		magic_redirect("forum.php?turma=$turma");
	}else{//
		magic_redirect("forum_topico.php?turma=$turma&topico=$idTopico");
	}
	
}else{
	die("Voce nao tem permissao para fazer isso");
}