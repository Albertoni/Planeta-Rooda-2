<?php
	session_start();

	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
	require("blog.class.php");
	require("../../file.class.php");
	require("../../link.class.php");
	require("../../reguaNavegacao.class.php");
	$usuario_id = $_SESSION['SS_usuario_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="planeta.css" />
<link type="text/css" rel="stylesheet" href="blog.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="planeta.js"></script>
<script type="text/javascript" src="blog.js"></script>	
<script type="text/javascript" src="../lightbox.js"></script>
<script src="../../js/thumbnailImages.js"></script>
<script type="text/javascript" language="javascript">

function coment(){
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		document.getElementById('ie_coments').style.width = 85 + '%';
		$('.bloqueia ul').css('margin-right','17px');
	}
}

function thumbImgs() {
    thumbnailImgsFromClass("lista_dir",300,300);
}
</script>

<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="thumbImgs();atualiza('ajusta()');inicia();">
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Blog", "blog_inicio.php", false);
				$regua->adicionarNivel("Todos Blogs");
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
					<div id="rel"><p id="balao">O blog é um espaço pessoal para escrita, onde é possível anexar arquivos e links interessantes. Nele, você pode compartilhar diversos assuntos com seus colegas e permitir que eles, além de visualizar, publiquem comentários em seus posts e marquem suas reações ao lê-los.</p></div>
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
				<a href="blog_inicio.php"><img src="../../images/botoes/bt_voltar.png" align="left"/></a>
				<a href="criar_blog_coletivo.php"><img id="responder_topico" src="../../images/botoes/bt_criar_coletivo.png" align="right"/></a>
			</div>
			<div id="meus_coletivos" class="bloco">
				<h1>MEUS BLOGS COLETIVOS</h1>
<?
global $tabela_blogs;
$bd = new conexao();
$bd->connect();
unset($blogs_coletivos);
$bd->solicitar("SELECT Id FROM $tabela_blogs");

$i = 0; // Para a classe da cor.

foreach($bd->itens as $b) {
	$b = new Blog($b['Id']);
	$i = ($i%2)+1;
?>
				<div class="cor<?=$i?>">
					<div class="lista_esq">
						<div class="imagem"></div> <!--IMAGEM DO CRIADOR DO BROGUI VAI AQUI GENTE BOA-->
						<ul>
							<li><a href="blog.php?blog_id=<?=$b->getId()?>"><?=$b->getTitle()?></a></li>
							<li class="mensagens"><?=numeroMensagens($b->getSize())?></li>
						</ul>
					</div>
					<div class="lista_dir">
						<ul>
							<li><a href="blog.php?blog_id=<?=$b->getId()?>"><?=getTextSample($b->getId())?></a></li>
							<li class="criado_por">Criado Por: <?=getPrintableOwners($b->getId())?></li>
							<li>
								<div align="right">
									<input type="image" src="images/botoes/bt_editar.png" />
									<input type="image" src="images/botoes/bt_excluir.png" />
								</div>
							</li>
						</ul>
					</div>
				</div>
<?php
}
?>
			</div>
			<div class="bts_baixo">
				<input type="image" src="images/botoes/bt_voltar.png" align="left"/>
				<input type="image" id="responder_topico" src="images/botoes/bt_criar_coletivo.png" align="right"/>
			</div>
		</div><!-- Fecha Div conteudo -->
	</div><!-- Fecha Div conteudo_meio -->   
	<div id="conteudo_base">
	</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
</body>
</html>
