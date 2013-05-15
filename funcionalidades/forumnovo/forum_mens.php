<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("verifica_user.php");
	require("sistema_forum.php");
	require("visualizacao_forum.php");
	
	$topico = stripslashes($_GET['topico']);
	$pagina = (isset($_GET['pagina']))? stripslashes($_GET['pagina']) : 1;
	if ($VERIFICA_USER_ERRO_ID == 0) {
		$FORUM = new forum($FORUM_ID);
		$FORUM->mensagens($topico,$pagina);
		
		$paginas = array();
		$paginas = $FORUM->paginas($pagina,10);
	}
	$link_voltar = "forum.php";

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
<script type="text/javascript">
	var forum_pg = <?=$pagina?>;
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
		<p id="hist"><?php echo $SISTEMA; ?> > Fórum </p>
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
				<div id="rel"><p id="balao">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. 
				Etiam eget ligula eu lectus lobortis condimentum. Aliquam nonummy auctor massa. Pellentesque 
				habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p></div>
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
	<input align="left" type="image" src="../../images/botoes/bt_voltar.png" onClick="document.location = '<?php echo $link_voltar; ?>';"/>
	<input align="right" type="image" id="responder_topico" src="../../images/botoes/bt_responder.png"/>
	</div>
	
	<div id="nova_mensagem" class="bloco">
	<h1>NOVA MENSAGEM</h1>
		<ul class="sem_estilo">
			<li><textarea class="msg_dimensao" rows="10" id="msg_txt"></textarea></li>
			<li class="espaco_linhas"><div class="enviar" align="right">
			<input type="image" id="cancela_msg" src="../../images/botoes/bt_excluir.png" />
			<input type="image" id="envia_msg" src="../../images/botoes/bt_confir_pq.png" /></div></li>
			<input type="hidden" id="msg_pai" value="<?php echo $topico?>" />
			<input type="hidden" id="msg_fid" value="<?php echo $FORUM_ID?>" />
		</ul>
	</div>
	
	<div id="dinamica">
<?php
		if ($VERIFICA_USER_ERRO_ID == 0) {
			if ($FORUM->contador > 0){
		mostraPaginas ($paginas, $pagina, false, "forum_mens.php?fid=$FORUM_ID&topico=$topico");
?>
	<div id="bloco_mensagens" class="bloco">
<?php
				echo "<h1>".$FORUM->titulo."</h1>";
				$forum_msg_cont = count($FORUM->mensagem);
				for ($c=0; $c<$forum_msg_cont; $c++){
					$mens = $FORUM->mensagem[$c];
					mostraMensagens($mens->msgId,$mens->msgUserName,$mens->msgTexto,$mens->msgData,($c % 2), $mens->msgEditavel);
				}

?>
	</div><!-- fim da div topicos -->
<?php
		mostraPaginas ($paginas, $pagina, false, "forum_mens.php?fid=$FORUM_ID&topico=$topico");
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
	<a href="<?=$link_voltar?>"><img align="left" type="image" src="../../images/botoes/bt_voltar.png"/></a>
	</div>
	
	</div>
	<!-- fim do conteudo -->

</div>   
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->
</body>
</html>
