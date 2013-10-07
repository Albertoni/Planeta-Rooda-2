<?php
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("sistema_forum.php");
	require_once("../../reguaNavegacao.class.php");
	require_once("../../usuarios.class.php");

	$user = usuario_sessao();

	if($user === false){
		die("Voce tem que estar logado para acessar essa pagina.");
	}
	
	$idTopico = (isset($_GET['topico']) and is_numeric($_GET['topico']))? $_GET['topico'] : die("Favor voltar e tentar novamente.<br>A id do topico nao foi passada corretamente.");
	$ordem = (isset($_GET['ordem'])   and is_numeric($_GET['ordem'])) ? $_GET['ordem'] : 1;
	$turma = (isset($_GET['turma']) and is_numeric($_GET['turma']))? $_GET['turma'] : die("Favor voltar e tentar novamente.<br>A id da turma nao foi passada corretamente.");
	
	$link_voltar = "forum.php?turma=$turma";
	
	$permissoes = checa_permissoes(TIPOFORUM, $turma);
	if($permissoes === false){
		die("Funcionalidade desabilitada para a sua turma. Favor voltar.");
	}
	
	/*$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);*/

	$topico = new visualizacaoTopico($idTopico);
	$nomeTopico = $topico->getTitulo();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="forum.css" />
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
	</div>
	
	<div id="li_resposta_-1" class="bloco nova_mensagem">
	<h1>NOVA MENSAGEM 
	</h1>
		<ul class="sem_estilo">
			<li>
				<textarea class="msg_dimensao" rows="10" id="msg_txt_-1"></textarea>
			</li>
			<li class="espaco_linhas">
				<div class="enviar" align="right">
					<button class="botao_transparente" id="cancela_msg"><img src="../../images/botoes/bt_cancelar_pq.png"></button>
					<button class="botao_transparente" id="envia_msg" onclick="enviaMensagem(<?php echo $turma?>, -1)"><img src="../../images/botoes/bt_enviar_pq.png"></button>
				</div>
			</li>
			<input type="hidden" id="topico" value="<?php echo $idTopico?>" />
			<input type="hidden" id="idTurma" value="<?php echo $turma?>" />
		</ul>
	</div>
	
	<div id="dinamica">
		<div id="bloco_mensagens" class="bloco">
			<h1>
				<span class="titulo_topico"><?php echo $nomeTopico ?> </span>
				<select onchange="postDinamico.reordenar(this)">
					<option>Ordenar mensagens</option>
					<option>Por data e hora</option>
					<option>Por respostas</option>
				</select>
			</h1>
			<h2 id="mensagem_espera">Aguarde um instante, carregando mensagens...</h2>
			<div id="areaMensagens"></div>
		</div>
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

<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<script type="text/javascript" src="forum.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->
<script>
userId = <?php echo $_SESSION['SS_usuario_id']; ?>;
post = <?php $topico->imprimeMensagens(); ?>;
postDinamico.imprimePosts(post);
postDinamico.removeMensagemEspera();
</script>

</body>
</html>