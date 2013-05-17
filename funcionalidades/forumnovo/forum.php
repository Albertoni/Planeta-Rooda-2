<?php
	error_reporting(E_ALL);
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
	
	session_start();
	
	$turma = isset($_GET['turma']) ? $_GET['turma'] : 0;
	
	
	
	//require("verifica_user.php");
	require("sistema_forum.php");
	//require("visualizacao_forum.php");
	require("../../reguaNavegacao.class.php");
	
	$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
	
	$forum = new dadosForum($turma);
	$forum->carregaTopicos();
	
	//$paginas = array();
	//$paginas = $FORUM->paginas($pagina,10);
	
	$permissoes = checa_permissoes(TIPOFORUM, $turma);
	if($permissoes === false){
		die("Funcionalidade desabilitada para a sua turma. Favor voltar.");
	}
	
	$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);

/*
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="forum.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<script type="text/javascript">
	var forum_pg = <?php echo $pagina; ?>;
	var deltipo = 0;
</script>
<script type="text/javascript" src="forum.js"></script>
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
				<div id="rel"><p id="balao">Espaço destinado à discussão de diferentes
				temáticas com usuários contribuindo em tempos e espaços diferentes.</p></div>
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
<?php
if ($user->podeAcessar($permissoes['forum_criarTopico'], $FORUM_ID)){
	echo "<a href=\"forum_cria_topico.php?fid=$FORUM_ID&amp;turma=$turma\"><img src=\"../../images/botoes/bt_criar_topico.png\"></a>\n";
};
?>
	<a href="forum_procurar.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_procurar_topico.png"></a>
	</div>
	
	<div id="dinamica">
<?php
		if ($VERIFICA_USER_ERRO_ID == 0) {
			if ($FORUM->contador > 0){
				mostraPaginas ($paginas, $pagina, false, "forum.php?turma=$FORUM_ID");
?>

	<div id="topicos" class="bloco">
		<h1>TÓPICOS</h1> <?php
				$forum_msg_cont = count($FORUM->mensagem);
				for ($i=0; $i<$forum_msg_cont; $i++){
					$topico = $FORUM->mensagem[$i];
					mostraTopicos($topico->msgId,$topico->msgUserName,$topico->msgTitulo,$topico->msgTexto,$topico->msgData,$topico->msgQntFilhos,($i % 2), $topico->msgUserId);
				}
?>
	</div><!-- fim da div topicos -->
<?php			
				mostraPaginas ($paginas, $pagina, false, "forum.php?turma=$FORUM_ID");

			}else{
?>
	<div id="topicos" class="bloco">
		<h1>TÓPICOS</h1><?php mostraAviso(5);?>
	</div><!-- fim da div topicos -->
<?php
			}
		}else{
?>
	<div id="topicos" class="bloco">
		<h1>TÓPICOS</h1><?php mostraAviso($VERIFICA_USER_ERRO_ID);?>
	</div><!-- fim da div topicos -->
<?php
		}
?>
	
	</div>
	
	<div class="bts_baixo">
<?php
$linkcria = "forum_cria_topico.php?turma=$FORUM_ID";
if ($user->podeAcessar($permissoes['forum_criarTopico'], $FORUM_ID)) echo '<input align="left" type="image" src="../../images/botoes/bt_criar_topico.png" onclick="document.location = \''.$linkcria.'\';"/>'; ?>
	<input align="right" type="image" src="../../images/botoes/bt_procurar_topico.png" onclick="document.location='forum_procurar.php?turma=<?=$turma?>'"/>
	</div>
	
	</div>

	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->
</body>
</html>
