<?php

/*\
 *
 * funcionalidades/portfolio/portfolio_postagem.php 
 *
\*/
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../reguaNavegacao.class.php");
require_once("../../usuarios.class.php");
require_once("portfolio.class.php");

global $upload_max_filesize;

$user = usuario_sessao();
if($user === false){
	die("Voce precisa estar logado para postar em um projeto.");
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="portfolio.css" />
<script type="text/javascript" src="../../js/compatibility.js"></script>
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="portfolio.js"></script>
<script type="text/javascript" src="../../js/rooda.js"></script>
<script type="text/javascript" src="../../js/ajax.js"></script>
<script type="text/javascript" src="../../js/ajaxFileManager.js"></script>
<script type="text/javascript" src="../../postagem_wysiwyg.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->
<?php

$projeto_id	= isset($_GET['projeto_id'])	? $_GET['projeto_id']	: 0;
$post_id	= isset($_GET['post_id'])		? $_GET['post_id']		: 0;

if (!is_numeric($projeto_id) or !is_numeric($post_id))die("</head>\n<body>\n<h2>A ID do projeto precisa ser um número.\n</h2></center>\n</html>");

$update = isset($_GET['update']) ? "1" : "0";

$funcionalidade_tipo = TIPOPORTFOLIO;
$funcionalidade_id = $projeto_id;

$projeto = new projeto($projeto_id);
$donos = $projeto->getOwners();
if(!in_array($user->getId(), $donos)){
	die("</head>\n<body>\n<h2><center>Voc&ecirc; n&atilde;o est&aacute; nesse projeto e n&atilde;o pode postar nele.\n</h2></center>\n</body>\n</html>");
}

$turma = is_numeric($_GET['turma']) ? $_GET['turma'] : die("</head>\n<body>\n<h2><center>A id da turma precisa estar setada para acessar, por favor volte.\n</h2></center>\n</body>\n</html>");

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
if($perm == false){
	die("Desculpe, mas o Portfolio esta desabilitado para esta turma.");
}
?>

<script type="text/javascript">
function ajusta_img() {
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		$('#cont_img3').css('width','436px');
		$('#cont_img3').css('padding-right','20px');
		$('#cont_img').css('height','170px');
	}
}
var objContent;
var objHolder;

function Init() {
	var ua = navigator.appName; 
	objHolder = document.getElementById('text_post');
	if(ua == "Netscape") {
		objContent = objHolder.contentDocument;
	} else {
		objContent = objHolder.document;
	}
	objContent.designMode = "On";
	
	objContent.body.style.fontFamily = 'Verdana';
	objContent.body.style.fontSize = '11px';
}
</script>
</head>
<body onload="atualiza('ajusta()');inicia(); checar(); ajusta_img(); Init();">
	<div id="descricao"></div>
	<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
		<img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
<?php
// Pode inserir links?
if($_SESSION['user']->podeAcessar($perm['portfolio_adicionarLinks'], $turma))
{
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
}
?>
	</div>
	
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Projetos", "portfolio_inicio.php", false);
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
					<div id="rel"><p id="balao"><b>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
					Etiam eget ligula eu lectus lobortis condimentum. Aliquam nonummy auctor massa. Pellentesque 
					habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</b></p></div>
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
		<form name="fConteudo" id="postFormId" action="formProcessing.php" onsubmit="return gravaConteudo()" method="post" enctype="multipart/form-data">
			<input type="hidden" name="text" value="" />
			<div class="bts_cima">
				<a href="portfolio_projeto.php?projeto_id=<?=$projeto_id?>&amp;turma=<?=$turma?>" align="left" >
					<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
				</a>
				<input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
			<div id="info_post" class="bloco">
				<h1>NOVA POSTAGEM</h1>
				<ul class="sem_estilo">
					<li>Título</li>
					<li><input name="titulo" type="text" class="port_info"/></li>
					<li>Tags <span class="exemplo">(Escreva as tags separadas por ponto e vírgula. Ex: Matemática; Português; Artes)</span></li>
					<li><input name="tags" type="text" class="port_info"/></li>
						<li style="height:22px; margin-bottom:4px; margin-top:10px">
							<div class="tool_bt" id="alt_negrito"><img src="../../images/botoes/tool_negrito.png" onClick="doBold()" /></div>
							<div class="tool_bt" id="alt_italico"><img src="../../images/botoes/tool_italico.png" onClick="doItalic()" /></div>
							<div class="tool_bt" id="alt_sublinhado"><img src="../../images/botoes/tool_sublinhado.png" onClick="doUnderline()" /></div>
							<div class="tool_bt" id="alt_tamanho"><img src="../../images/botoes/tool_tamanho.png" onClick="doSize()" /></div>
<?php
if($user->podeAcessar($perm['portfolio_adicionarLinks'], $turma)){
echo "							<div class=\"tool_bt\" id=\"alt_link\"><img src=\"../../images/botoes/tool_link.png\" /></div>";
}
?>
						</li>
					<li><iframe id="text_post" width="100%"></iframe></li>
<?php
if($user->podeAcessar($perm['portfolio_enviarArquivos'], $turma)){
	global $upload_max_filesize; // vem do cfg.php
	echo "					<li>Anexos (Tamanho máximo $upload_max_filesize): <br><input type=\"hidden\" name=\"addAttachments\"></li>";
}
?>
					<input type="hidden" name="projeto_id" value="<?=$projeto_id?>"> <!--Para posterior edição-->
					<input type="hidden" name="post_id" value="<?=$post_id?>">
					<input type="hidden" name="update" value="<?=$update?>">
					<input type="hidden" name="turma" value="<?=$turma?>">
				</ul>
			</div>
			<div class="bts_baixo">
				<a href="portfolio_projeto.php?projeto_id=<?=$projeto_id?>&amp;turma=<?=$turma?>" align="left" >
					<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
				</a>
				<input type="image" onClick="postForm.submit()" src="../../images/botoes/bt_confirm.png" align="right"/>
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
		<div class="spacer_50"><!-- empty --> </div>
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