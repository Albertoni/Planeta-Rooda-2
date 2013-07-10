<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");

session_start();

require("sistema_forum.php");
require("visualizacao_forum.php");

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


// ESPECIFICA ONDE REDIRECIONARÁ

/*if ($pai == '-1'){
	$link = "forum.php?turma=$FORUM_ID&pg=0&turma=$turma";
}else{
	$link = "forum_arvore.php?turma=$FORUM_ID&pagina=1&topico=".$pai;
}*/

if($user->podeAcessar($permissoes[$acaoSendoEfetuada], $turma)){
	if(!$editar){ // CRIAÇÃO
		$topico = new topico(NULL, $turma, $userId, $titulo);
	}else{ // EDIÇÃO
		$topico = new topico($idTopico);

		$topico->setTitulo($titulo);
		$topico->setMensagem(0, $conteudo);
	}

	$erro = $topico->salvar();
	if($idTopico == NULL){//criando
		magic_redirect("forum.php?turma=$turma");
	}else{//
		# code...
	}
	
}else{
	die("Voce nao tem permissao para fazer isso");
}

/*
$pesquisa1->solicitar("select * from $tabela_forum where msg_id = '$topico' and forum_id = '$FORUM_ID' LIMIT 1");
$cria = false;
if ($topico == "-1"){ //-1 significa que está criando tópico
	$cria = true;
	$atualiza = false;
}else{
	if($pesquisa1->erro == ""){
		$uid = $pesquisa1->resultado['msg_usuario'];
		if (permissao($uid, $topico, 'forum_responderTopico')){
			$cria = true;
			$atualiza = ($pesquisa1->registros > 0);
		}
	}
}

if ($cria){
	$FORUM = new forum($FORUM_ID);
	if (($atualiza && $criador != 0) and $user->podeAcessar('forum_editarTopico', $turma)){
		$FORUM->salvaMensagem(false, $topico, $criador, $titulo, $conteudo); // sistema_forum.php
	}else if ($user->podeAcessar($permissoes['forum_responderTopico'], $turma)){
		$FORUM->salvaMensagem(true, $pai, $USUARIO_ID, $titulo, $conteudo);
	}

	if ($ajax == 0){
		echo "<script> document.location='$link'; </script>";		// REDIRECIONADOR VIVE NESTA LINHA
	}else{
		$pai = $FORUM->paiAbsoluto($pai);
		$FORUM->pegaMensagensArvore($pai,1, true, true);
		$pgI = ceil(($FORUM->contador)/10);
		$paginas = array();
		$paginas = $FORUM->paginas($pgI,10);
		
		mostraPaginas ($paginas, $pgI, false, "forum_arvore.php?turma=$FORUM_ID&topico=$pai");
?>
<div id="bloco_mensagens" class="bloco">
<?php
		echo "<h1>".$FORUM->titulo.'<select id="ordem" style="position:absolute; right:5px;" onchange="ordernar(this);"><option> -- Ordenar... --</option><option>Data</option><option>Árvore</option></select></h1>';
		$forum_msg_cont = count($FORUM->mensagem);
		for ($i=0; $i<$forum_msg_cont; $i++){
			$mens = $FORUM->mensagem[$i];
			mostraArvore($mens->msgId,$mens->msgUserName,$mens->msgTexto,$mens->msgData,($i % 2), $mens->msgGrau, true, $mens->msgUserId);
		}

?>
</div><!-- fim da div topicos -->
<?php
		mostraPaginas ($paginas, $pgI, false, "forum_arvore.php?turma=$FORUM_ID&topico=$pai");
	}
}else{
?>
	<div id="bloco_mensagens" class="bloco">
		<h1>MENSAGENS</h1>Você pode não ter permissão para fazer isso.
	</div><!-- fim da div topicos --><br />
<?php
}*/
?>
