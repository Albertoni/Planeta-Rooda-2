<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("verifica_user.php");
	require("sistema_forum.php");
	require("visualizacao_forum.php");
	require("../../reguaNavegacao.class.php");
	
	$topico = (isset($_GET['topico']))? stripslashes($_GET['topico']) : 1;
	$pagina = (isset($_GET['pagina']))? stripslashes($_GET['pagina']) : 1;
	$turma  = (isset($_GET['turma']))? stripslashes($_GET['turma']) : 0;
	$link_voltar = "forum.php?turma=$turma";

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
	var deltipo = 2;
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
			$regua->adicionarNivel("Fórum", "forum.php", false);
			$regua->adicionarNivel("Procurar");
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
				<div id="rel"><p id="balao">Aqui você busca por discussões anteriores, é só clicar em “Procurar Tópico” e digitar o que procura (não se esqueça de selecionar se quer procurar por “Título”, “Nome” ou “Conteúdo”), após clique em “Procurar”.</p></div>
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
		<input type="image" src="../../images/botoes/bt_voltar.png" onclick="document.location = '<?php echo $link_voltar ?>'"/>
	</div>
	
	<div id="procurar_topico" class="bloco">
		<h1>PROCURAR TÓPICO</h1>
		<div class="sem_estilo">
			
			<ul class="tabela">
			<li>
			<div class="box_dados" id="box_procurar">
				<form name="radio">
					<ul>
						<li><input id="consulta" type="text" /></li>
						<li>
							<input type="radio" name="tipo" value="0" />Título 
							<input type="radio" name="tipo" value="1" class="espaco_input" />Autor
							<input type="radio" name="tipo" value="2" checked class="espaco_input" />Conteúdo
						</li>
					</ul>
				</form>
			</div>
			
			<div class="bts_dir" align="right">
				<input type="image" src="../../images/botoes/bt_procurar.png" onclick="pesquisar(1,true)" />
			</div>
			</li>
			</ul>
		</div>
	</div><!-- fim da div procurar_topicos -->
	<div id="dinamica">
	<div id="resultado_pesquisa" class="bloco"> <!-- começo da div topicos -->
		<h1>RESULTADOS DA PESQUISA</h1>
<?php
		mostraAviso(8);
?>
	</div>
	</div>
	
	<div class="bts_baixo">
		<input type="image" src="../../images/botoes/bt_voltar.png" onclick="document.location = '<?php echo $link_voltar ?>'"/>
	</div>
	<div style="clear:both;"></div>
	</div>
	<!-- fim do conteudo -->

</div>   
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>
