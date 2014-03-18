<?php
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../reguaNavegacao.class.php");
	require_once("../../usuarios.class.php");
	require_once("blog.class.php");

$usuario = usuario_sessao();
if (!$usuario) { die("voce nao esta logado"); }

$turma = (int)(isset($_GET['turma']) ? $_GET['turma'] : 0);
$permissoes = checa_permissoes(TIPOBLOG, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if (!$usuario->podeAcessar($permissoes["blog_inserirPost"], $turma)){
	die("Adicionar posts esta desabilitado para a sua turma. Voce nem deveria estar vendo esse erro.");
}

$blog_id = isset($_GET['blog_id']) ? (int)$_GET['blog_id'] : die("não foi fornecido id de blog");
$blog = new Blog($blog_id, $turma);
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
$post = new Post();


if($post_id!=0) {
	$post->open($post_id);
	$edita = true;
	$tags = str_replace(', ',';', $post->printPostTags($post->getPostTags($post->id, 1)));
} else {
	$edita = false;
	$tags = '';
}


$funcionalidade_tipo = $blog->getTipo();
$funcionalidade_id = $blog->getId();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css"/>
<link type="text/css" rel="stylesheet" href="blog.css"/>
<script type="text/javascript" src="../../js/compatibility.js"></script>
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="blog.js"></script>
<script type="text/javascript" src="../../js/ajax.js"></script>
<script type="text/javascript" src="../../js/ajaxFileManager.js"></script>
<script type="text/javascript" src="../../postagem_wysiwyg.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>


<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

<script type="text/javascript">

var objContent;
var objHolder;

function Init() {
	var ua = navigator.appName; 
	objHolder = document.getElementById('iView');
	if(ua == "Netscape") {
		objContent = objHolder.contentDocument;
	} else {
		objContent = objHolder.document;
	}
	objContent.designMode = "On";

<?php  if($edita && ($post->getText() != "")) {		// TEM UM INICIALIZADOR DE PHP AQUI MINHA BOA GENTE, SE LIGUEM
	$cont = trim(str_replace("'","&prime;",$post->getText()));
	$cont = trim(str_replace("\r\n"," ",$cont));
?>
	objContent.write('<?=trim(str_replace("\r\n"," ",$cont))?>');
<?php } /* */ ?>
	
	objContent.body.style.fontFamily = 'Verdana';
	objContent.body.style.fontSize = '11px';
}
</script>
</head>
<body onload="inicia(); checar(); ajusta_img(); Init();">
	<div id="descricao"></div>
	<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
		<img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
<?php

// Pode inserir links?


?>
		<div id="link_lbox">
			<h1>INSERIR LINK</h1>
			<ul class="sem_estilo">
				<li>Texto a ser exibido: <input id="addlinktext" type="text" /></li>
				<li style="margin-bottom:172px">Link para: <input id="addlinkurl" type="text" value="http://" /></li>
				<li>
					<div align="right"><img src="../../images/botoes/bt_confir_pq.png" alt="Confirmar" onclick="addLink()" /></div>
				</li>
			</ul>
		</div>
<?php

?>
		
	</div>
	
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Blog", "blog_inicio.php", false);
				$regua->adicionarNivel("Postagem");
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
					<div id="rel"><p id="balao">São pequenos textos escritos pelo autor do blog que podem conter imagens,
					vídeos, arquivos anexados e <i>links</i>.
					Podem ser comentados por outras pessoas, desde que estas façam login.</p></div>
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
			<form name="fConteudo" method="post" enctype="multipart/form-data" action="_blog_escreve_postagem.php" onsubmit="javascript:gravaConteudo();">
			<div class="bts_cima">
				<a href="javascript:history.go(-1)"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
				<input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
				<div id="info_post" class="bloco">
					<input type="hidden" name="blog_id" value="<?=$blog_id?>" />
					<input type="hidden" name="post_id" value="<?=$post->getId()?>" />
					<input type="hidden" name="turma" value="<?=$turma?>" />
					<input type="hidden" name="text" value="" />
					<h1><?=mb_strtoupper($blog->getTitle())?>: NOVA POSTAGEM</h1>
					<ul class="sem_estilo">
						<li>Título</li>
						<li><input type="text" name="title" value="<?=$post->getTitle()?>" class="blog_info"/></li>
						<li>Tags <span class="exemplo">(Escreva as tags separadas por ponto e vírgula. Ex: Matemática; Português; Artes)</span></li>
						<li><input type="text" class="blog_info" name="tags" value="<?=$tags?>"/></li>
						<li class="blog_info"><input type="radio" name="is_public" value="1" <?php if($post->getIsPublic()==1) echo "checked"; ?>/>Postagem Pública
							<input type="radio" name="is_public" <?php if($post->getIsPublic()==0) echo "checked"; ?> value="0" class="input_espaco" />Postagem Privada
						</li>
							<li style="height:22px; margin-bottom:4px; margin-top:10px">
								<div class="tool_bt" id="alt_negrito"><img src="../../images/botoes/tool_negrito.png" onClick="doBold()" /></div>
								<div class="tool_bt" id="alt_italico"><img src="../../images/botoes/tool_italico.png" onClick="doItalic()" /></div>
								<div class="tool_bt" id="alt_sublinhado"><img src="../../images/botoes/tool_sublinhado.png" onClick="doUnderline()" /></div>
								<div class="tool_bt" id="alt_tamanho"><img src="../../images/botoes/tool_tamanho.png" onClick="doSize()" /></div>
								<div class="tool_bt" id="alt_link"><img src="../../images/botoes/tool_link.png" /></div>
							</li>
						<li><iframe class="blog_info" id="iView" style="border:solid 1px #74d3ed; background-color:#fff; height:400px"></iframe></li>
						<li>Anexos: <br><input type="hidden" name="addAttachments"></li>
					</ul>
				</div>
			<div style="clear:both"><!-- um terrivel hack porque a margem do de baixo não funciona logo apos um elemento em float --></div>
			<div class="bts_baixo">
				<a href="javascript:history.go(-1)"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
				<input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
		</form>
		<div style="clear:both;"></div>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->

	</div><!-- fim da geral -->
	<!-- loading -->
	<div id="loading" style="display:none;">
		<div class="spacer_50"><!-- empty --></div>
		<div class="loading_anim">
			<h2>Processando</h2>
		</div>
	</div>
	<script type="text/javascript">
(function () {
	var placeholder = $('input[name=addAttachments]');
	var button = $('<button type="button">');
	button.html('adicionar anexo');
	button.click(function () {
		$(this).before($('<input type="file" name="file[]"><br>'));
	});
	placeholder.replaceWith(button);
}());
	</script>
</body>
</html>