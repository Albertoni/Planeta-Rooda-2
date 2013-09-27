<?php 
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../reguaNavegacao.class.php");
	require_once("../../funcoes_aux.php");
	
	session_start();
	$usuario_id = isset($_SESSION['SS_usuario_id']) ? $_SESSION['SS_usuario_id'] : 0;
	
	if ($usuario_id == 0){
		die("Voc&ecirc; n&atilde;o est&aacute; logado. Por favor volte.");
	}
	//$BLOG_ID = $_SESSION['SS_usuario_id'];
	
	$turma = isset($_GET['turma']) ? $_GET['turma'] : 0;
	$permissoes = checa_permissoes(TIPOBLOG, $turma);
	if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="blog.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="blog.js"></script>	
<script type="text/javascript" src="../lightbox.js"></script>

<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza(ajusta);inicia();">
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Blog");
				$regua->imprimir();
			?>
			<p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
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
					<div id="rel"><p id="balao">O webfólio é um espaço pessoal para
					escrita, onde é possível anexar arquivos e links interessantes.
					Nele, você pode compartilhar diversos assuntos com seus colegas
					e permitir que eles, além de visualizar, publiquem comentários
					em seus posts e marquem suas reações ao lê-los.</p></div>
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
			<div class="bloco" id="meu_blog">
				<h1>MEU WEBFÓLIO</h1>
				<ul class="sem_estilo">
					<a href="blog.php?blog_id=meu_blog&turma=<?=$turma?>"><img src="images/desenhos/meu_blog.png" border="0px" /></a>
				</ul>
			</div>
			<div class="bloco" id="blog_coletivo">
				<h1>WEBFÓLIOSCOLETIVOS</h1>
				<ul class="sem_estilo">
					<a href="blog_coletivo_lista.php?turma=<?=$turma?>"><img src="images/desenhos/blog_coletivo.png" border="0px" /></a>
				</ul>
			</div>
			<div class="bloco" id="outros_blogs">
				<h1>OUTROS WEBFÓLIOS</h1>
				<ul class="sem_estilo">
					<a href="todos_blogs.php?turma=<?=$turma?>"><img src="images/desenhos/outros_blogs.png" border="0px" /></a>
				</ul>

			</div>
			<div style="clear:both;"></div>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->

</body>
</html>
