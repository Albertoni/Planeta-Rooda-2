<?php
session_start();

require("blog.class.php");
require("../../cfg.php");
require("../../bd.php");
require("../../usuarios.class.php");
require_once("../../funcoes_aux.php");
require("../../reguaNavegacao.class.php");
global $tabela_tags;
global $tabela_posts;

$tag = isset($_GET['tag']) ? $_GET['tag'] : die ("Por favor acesse essa pagina com uma tag setada.");
$blog_id = isset($_GET['blog_id']) ? $_GET['blog_id'] : 0;

$consulta = new conexao();
if ($blog_id != 0){
	$consulta->solicitar("SELECT Id FROM $tabela_tags WHERE Tags LIKE '%".addslashes(strtolower($tag))."%' AND BlogId = $blog_id");
} else {
	$consulta->solicitar("SELECT Id FROM $tabela_tags WHERE Tags LIKE '%".addslashes(strtolower($tag))."%'");
}

$pergunta = new conexao();
$posts = array();

for ($i=0; $i < $consulta->registros; $i++){
	$pergunta->solicitar("SELECT * FROM $tabela_posts WHERE Id = ".$consulta->resultado['Id']); // Pega o post
	$consulta->proximo(); // Alterna o CONSULTA, aí avança na pesquisa.
	$posts[] = new Post($pergunta->resultado['Id'], $pergunta->resultado['BlogId'], $pergunta->resultado['UserId'], $pergunta->resultado['Title'], $pergunta->resultado['Text'], $pergunta->resultado['IsPublic'], $pergunta->resultado['Date']); // Cria um objeto post para usar os métodos dele e poder reaproveitar o código
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="planeta.css" />
<link type="text/css" rel="stylesheet" href="blog.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="planeta.js"></script>
<script type="text/javascript" src="blog.js"></script>
<script type="text/javascript" src="blog_ajax.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>

<script language="javascript">

function coment(){
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		document.getElementById('ie_coments').style.width = 85 + '%';
		$('.bloqueia ul').css('margin-right','17px');
	}
};
</script>

<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');Init();inicia();coment();">

	<div id="descricao"></div>

<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
	</div>

<div id="topo">
<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Blog", "blog_inicio.php", false);
				$regua->adicionarNivel("Tags");
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
				<div id="rel"><p id="balao">Nas tags, você encontra as palavras-chave
				que foram preenchidas na criação de um post e aparecem em diferentes posts.</p></div>
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
			<a href="javascript:history.go(-1)"><img src="../../images/botoes/bt_voltar.png" align="left"/></a>
		</div>
		<div id="esq" class="margem_paginas">
			<div class="bloco" id="identsingle">
				<h1>Pesquisa por Tag: <?=fullUpper($_GET['tag'])/*funcoes_aux*/?></h1>
<?php
// script para a exibição dos posts adaptado para tags!
	$cor_i = 0;
	for($i=0;$i < count($posts);$i++) {
		$p = $posts[$i];
?>
				<div class="cor<?=$cor_i%2+1?>">
				<ul class="sem_estilo">
					<li class="tabela_blog">
						<span class="titulo">
							<a href="blog_singlepost.php?post_id=<?=$p->getId()?>&blog_id=<?=$p->blogId?>"><?=$p->getTitle()?></a>
						</span>
						<span class="data">
							<?=$p->getDate()?>
						</span>
					</li>
					<li class="tabela_blog">
							<?=$p->getText()?>
					</li>
					<li class="tabela_blog">
						Por <?=$p->getAuthor()->getName()?><br />
						Tags: <?=$p->printPostTags($p->getPostTags($p->getId())) ?>
					</li>
				</ul>
				</div>
<?php 
		$cor_i++; // alterna o estilo da div
	}
?>
			</div>
		</div>
	
	</div><!-- Fecha Div conteudo -->
	
	</div><!-- Fecha Div conteudo_meio -->   
	<div id="conteudo_base">
	</div><!-- para a imagem de fundo da base -->
		
</div><!-- fim da geral -->

</body>
</html>
