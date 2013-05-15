<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("sistema_forum.php");
require("verifica_user.php");
require("visualizacao_forum.php");

$user = new Usuario();
$user->openUsuario($_SESSION['SS_usuario_id']);

$turma = (int) $_POST['turma'];

$permissoes = checa_permissoes(TIPOFORUM, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

$pesquisa1 = new conexao();
$pesquisa2 = new conexao();

$ajax = $pesquisa1->sanitizaString((isset($_POST['ajax'])) ? $_POST['ajax'] : 0);
$titulo = $pesquisa1->sanitizaString((isset($_POST['msg_titulo'])) ? $_POST['msg_titulo'] : "");
$topico = $pesquisa1->sanitizaString($_POST['topico']);
$pai = $pesquisa1->sanitizaString($_POST['pai']);
$criador = isset($_POST['criador'])?$pesquisa1->sanitizaString($_POST['criador']):0;	// Possibilita edição de pessoas diferentes.
$conteudo = str_replace("\n", "<br>", $pesquisa1->sanitizaString($_POST['msg_conteudo']));

// ESPECIFICA ONDE REDIRECIONARÁ

if ($pai == '-1'){
	$link = "forum.php?turma=$FORUM_ID&pg=0&turma=$turma";
}else{
	$link = "forum_arvore.php?turma=$FORUM_ID&pagina=1&topico=".$pai;
}

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
}
?>
