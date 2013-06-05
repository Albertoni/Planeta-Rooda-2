<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("verifica_user.php");
	require("sistema_forum.php");
	require("visualizacao_forum.php");
	require("../../reguaNavegacao.class.php");
	
	$topico = stripslashes($_GET['topico']);
	$pagina = (isset($_GET['pagina']))? stripslashes($_GET['pagina']) : 1;
	$ordem = (isset($_GET['ordem']))? stripslashes($_GET['ordem']) : 1;
	if ($VERIFICA_USER_ERRO_ID == 0) {
		$FORUM = new forum($FORUM_ID);
		
		if ($ordem == 1)
			$FORUM->pegaMensagensArvore($topico,$pagina, true);
		else
			$FORUM->pegaMensagensArvore($topico,$pagina, false);
		
		$paginas = array();
		$paginas = $FORUM->paginas($pagina,10);
	}
	$link_voltar = "forum.php?turma=$FORUM_ID";
	
	$permissoes = checa_permissoes(TIPOFORUM, $FORUM_ID);
	if($permissoes === false){
		die("Funcionalidade desabilitada para a sua turma. Favor voltar.");
	}
	
	$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="forum.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<script type="text/javascript">
	var forum_pg = <?php echo $pagina; ?>;
	var deltipo = 1;
</script>
<script type="text/javascript" src="forum.js"></script>
<script type="text/javascript" src="forum_mens.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">

<div id="topo">
	<div id="centraliza_topo">
		<?php 
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("Fórum");
			$regua->imprimir();
		?>
		<p id="bt_ajuda" onmousedown="animacao('abrirAjuda()');">OCULTAR AJUDANTE</p>
	</div>
</div>

<div id="geral">

<!-- **************************
			cabecalho
***************************** -->
<div id="cabecalho">
	<div id="ajuda">
		<div id="ajuda_meio">
			<div id="ajudante">
				<div id="personagem"><img src="../../images/desenhos/ajudante.png" height=145 align="left" alt="Ajudante" /></div>
				<div id="rel"><p id="balao">Nesse espaço, você pode visualisar os tópicos que foram criados e os assuntos que estão em discussão.</p></div>
			</div>
		</div>
		<div id="ajuda_base"></div>
	</div>
</div><!-- fim do cabecalho -->

<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->
	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->
	
	<div class="bts_cima">
	<a href="<?php echo $link_voltar; ?>"><img src="../../images/botoes/bt_voltar.png"/></a>
<?php if ($user->podeAcessar($permissoes['forum_responderTopico'], $FORUM_ID)){?>
	<input align="right" type="image" id="responder_topico" src="../../images/botoes/bt_responder.png"/>
<?php } ?>
	</div>
	
	<div id="nova_mensagem" class="bloco">
	<h1>NOVA MENSAGEM</h1>
		<ul class="sem_estilo">
			<li>
				<textarea class="msg_dimensao" rows="10" id="msg_txt"></textarea>
			</li>
			<li class="espaco_linhas">
				<div class="enviar" align="right">
					<input type="image" id="cancela_msg" src="../../images/botoes/bt_cancelar_pq.png" />
					<input type="image" id="envia_msg" src="../../images/botoes/bt_enviar_pq.png" />
				</div>
			</li>
			<input type="hidden" id="msg_pai" value="<?php echo $topico?>" />
			<input type="hidden" id="msg_fid" value="<?php echo $FORUM_ID?>" />
			<input type="hidden" id="msg_criador" value="0">
		</ul>
	</div>
	
	<div id="dinamica">
<?php
		if ($VERIFICA_USER_ERRO_ID == 0) {
			if ($FORUM->contador > 0){
		mostraPaginas ($paginas, $pagina, false, "forum_arvore.php?turma=$FORUM_ID&topico=$topico&ordem=$ordem");
?>
	<div id="bloco_mensagens" class="bloco">
<?php
				echo "<h1>".$FORUM->titulo.'<select id="ordem" style="position:absolute; right:5px;" onchange="ordernar(this);"><option> -- Ordenar... --</option><option>Data</option><option>Árvore</option></select></h1>';
				$forum_msg_cont = count($FORUM->mensagem);
				for ($c=0; $c<$forum_msg_cont; $c++){
					$mens = $FORUM->mensagem[$c];
					mostraArvore($mens->msgId,$mens->msgUserName,$mens->msgTexto,$mens->msgData,($c % 2), $mens->msgGrau,$ESCRITA, $mens->msgUserId);
				}

?>
	</div><!-- fim da div topicos -->
<?php
		mostraPaginas ($paginas, $pagina, false, "forum_arvore.php?turma=$FORUM_ID&topico=$topico&ordem=$ordem");
			}else{
?>
	<div id="bloco_mensagens" class="bloco">
		<h1>MENSAGENS</h1><?php mostraAviso(6);?>
	</div><!-- fim da div topicos --><br />
<?php
			}
		}else{
?>
	<div id="bloco_mensagens" class="bloco">
		<h1>MENSAGENS</h1><?php mostraAviso($VERIFICA_USER_ERRO_ID);?>
	</div><!-- fim da div topicos --><br />
<?php
		}
		
?>

	</div><!-- fim da div topicos -->

	<div class="bts_baixo">
	<a href="<?php echo $link_voltar; ?>"><img src="../../images/botoes/bt_voltar.png"/></a>
	</div>
	<div style="clear:both;"></div>
	</div>
	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->

</div><!-- fim da geral -->
</body>
</html>
