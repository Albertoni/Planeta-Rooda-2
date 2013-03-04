<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("../../reguaNavegacao.class.php");

session_start();

$projeto_id = isset($_GET['projeto_id']) and is_numeric($_GET['projeto_id']) ? '<input type="hidden" name="projeto_id" value="'.$_GET['projeto_id'].'" />' : NULL;

if (isset($_GET['turma']) and $_GET['turma'] != ""){
	$turma = $_GET['turma'];
}

$id_usuario = $_SESSION['SS_usuario_id'];

$user = new Usuario();
$user->openUsuario($id_usuario);

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
if($perm == false){
	die("Desculpe, mas o Portfolio esta desabilitado para esta turma.");
}
if(!$_SESSION['user']->podeAcessar($perm['portfolio_inserirPost'], $turma)){
	die("Desculpe, voce nao pode inserir posts nessa turma.");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Planeta ROODA 2.0</title>

	<link type="text/css" rel="stylesheet" href="../../planeta.css" />
	<link type="text/css" rel="stylesheet" href="portfolio.css" />

	<script type="text/javascript" src="../../jquery.js"></script>
	<script type="text/javascript" src="../../jquery-ui-1.8.1.custom.min.js"></script>
	<script type="text/javascript" src="../../planeta.js"></script>
	<script type="text/javascript" src="portfolio.js"></script>
	<script type="text/javascript" src="../../postagem_wysiwyg.js"></script>
	<script type="text/javascript" src="../lightbox.js"></script>

	<!--[if IE 6]>
	<script type="text/javascript" src="planeta_ie6.js"></script>
	<![endif]-->

	<script language="javascript">
	function ajusta_img(){ 
		if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
			$('#cont_img3').css('width','436px');
			$('#cont_img3').css('padding-right','20px');
			$('#cont_img').css('height','170px');
		}
	}
	function Init() {
		var ua = navigator.appName; 
		if(ua == "Netscape") 
			objContent = document.getElementById('text_post').contentDocument;
		else
			objContent = document.getElementById('text_post').document;
		objContent.designMode = "On";
	
		objContent.body.style.fontFamily = 'Verdana';
		objContent.body.style.fontSize = '11px';
	}
	
	var objContent;
	
	function teste621(){alert("socorro estou preso no computador");}
	</script>
</head>

<body onload="atualiza('ajusta()');inicia();Init(); checar(); ajusta_img();">
	<div id="descricao"></div>
	<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
		<img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
<?php
if($_SESSION['user']->podeAcessar($perm['portfolio_adicionarArquivos'], $turma))
{
?>
		<div id="imagem_lbox">
			<h1>INSERIR IMAGEM</h1>
			<ul class="sem_estilo" style="line-height:25px">
				<li>Você não pode enviar uma imagem antes de criar o Portfólio. Caso seja necessário, edite o post depois de criá-lo, ou use outro site para dar upload na imagem.</li>
				<li><input type="radio" id="troca_img2" class="select_img" name="select_img" onclick="modo=2"/>Imagem da Web</li>
				<li><input type="radio" id="troca_img3" class="select_img" name="select_img" onclick="modo=3"/>Procurar nas imagens já enviadas</li>
				<li>
					<div id="cont_img">
						<ul id="cont_img2">
							<li><input type="text" id="imagefromurl" value="http://" /></li>
							<li style="margin-top:-5px">Endereço da imagem</li>
						</ul>
						<div id="cont_img3">
						<table width="100%">
							<tr>
							<?php
							//	Dumpando a lista de imagens que tem no blog

							$consulta = new conexao();

							/*\
							 *	SELECT arquivo FROM $tabela_arquivos WHERE tipo LIKE 'image/%'
							 *	Pega o BLOB de todas as imagens pra dar resize.
							\*/
							global $tabela_arquivos;
							$consulta->solicitar("SELECT arquivo_id FROM $tabela_arquivos WHERE tipo LIKE 'image/%'");

							for($i=0 ; $i<count($consulta->itens);$i++) {
								$id = $consulta->resultado['arquivo_id']; 
								if ($i % 5 == 0 && $i != 0) { echo "</tr><tr>"; } // 5 imagens por linha, sabe.
							?>
							<td><? echo '<div class="img_enviadas" id="galeria'.$id.'" ><img src="../../image_output.php?file='.$id.'" onClick="fromgallery('.$id.')"/>'; ?></div></td>
<?php
								$consulta->proximo();
							}
						?>
							</tr>
						</table>
						</div>
					</div>
				</li>
				<li>
					<div align="right" onclick="addImage()"><img src="../../images/botoes/bt_confir_pq.png" /></div>
				</li>
			</ul>
		</div>
		
		<div id="arquivo_lbox">
			<h1>ANEXAR ARQUIVO</h1>
			<ul class="sem_estilo" style="line-height:25px">
				<li>Você não pode enviar um arquivo antes de criar o Portfólio. Caso seja necessário, edite o post depois de criá-lo.</li>
				<li><input type="radio" id="troca_arq2" class="select_arq" onclick="arquivos_mode=2" name="select_arq"/>Procurar nos arquivos já enviados</li>
				<li>
					<div id="cont_arq">
						<ul id="cont_arq2">

<?php
							$consulta = new conexao();
							$consulta->solicitar("SELECT nome,arquivo_id FROM $tabela_arquivos WHERE uploader_id='".$_SESSION['SS_usuario_id']."'");
							for($i=0 ; $i<count($consulta->itens);$i++) {
?>
								<li class="enviado<?=($i % 2) + 1?>"><input type="checkbox" id="file<?=$consulta->resultado['arquivo_id']?>" onclick="addRemove(<?=$consulta->resultado['arquivo_id']?>, '<?=$consulta->resultado['nome']?>')" /><?=$consulta->resultado['nome']?></li>
<?php
								$consulta->proximo();
							}
?>
						</ul>
					</div>
				</li>
				<li>
					<div align="right"><a href="javascript:arquivoInsert();"><img src="../../images/botoes/bt_confir_pq.png" onclick="confirmaAnexarArquivos()" /></a></div>
				</li>
			</ul>
		</div>
<?php
}

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
				$regua->adicionarNivel("Portfólio", "portfolio_inicio.php", false);
				$regua->adicionarNivel("Novo Projeto");
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
					<div id="rel"><p id="balao">Para inserir um novo projeto, basta inserir o título, os objetivos e o(s) autor(es). Outros campos também podem ser inseridos, além de <i>links</i>, arquivos, imagens e vídeos.</p></div>
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
		<?php
if(sizeof($_SESSION['SS_turmas']) > 1){
	selecionaTurmas($turma);
}
?>			<br />
			<form name="fConteudo" id="postFormId" action="formProcessingNovoProjeto.php" onsubmit="return gravaConteudo()" method="post">
			<input type="hidden" name="text" value="" />
			<input type="hidden" name="turma" value="<?=$turma?>" />
			<div class="bts_cima">
			<a href="portfolio.php?turma=<?=$turma?>" align="left" >
				<img src="../../images/botoes/bt_cancelar.png" border="0" align="left"/>
			</a>
			<input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
				<div id="info_post" class="bloco">
					<h1>NOVO PROJETO</h1>
					<ul class="sem_estilo">
						<li>Título <span class="exemplo">(Obrigatório)</span></li>
						<li><textarea name="titulo_projeto" type="text" class="port_info" rows="1"/></textarea></li>
						<li>Descrição</li>
						<li><textarea name="descricao_projeto" type="text" class="port_info"/></textarea></li>
						<li>Objetivos <span class="exemplo">(Obrigatório)</span></li>
						<li><textarea name="objetivos_projeto" type="text" class="port_info"/></textarea></li>
						<li>Conteúdos Abordados</li>
						<li><textarea name="conteudos_projeto" type="text" class="port_info" rows="1"/></textarea></li>
						<li>Metodologia</li>
						<li><textarea name="metodologia_projeto" type="text" class="port_info" rows="1"/></textarea></li>
						<li>Público-Alvo</li>
						<li><textarea name="publicoAlvo_projeto" type="text" class="port_info" rows="1"/></textarea></li>
						<li>Autor <span class="exemplo">(Obrigatório)</span></li>
						<li><textarea name="autor_projeto" type="text" class="port_info" rows="1"/></textarea></li>
						<li>Tags <span class="exemplo">(Escreva as tags separadas por ponto e vírgula. Ex: Matemática; Português; Artes)</span></li>
						<li><input name="tags_projeto" type="text" class="port_info"/></li>
							<li style="height:22px; margin-bottom:4px; margin-top:10px">
								<div class="tool_bt" id="alt_negrito"><img src="../../images/botoes/tool_negrito.png" onClick="doBold()" /></div>
								<div class="tool_bt" id="alt_italico"><img src="../../images/botoes/tool_italico.png" onClick="doItalic()" /></div>
								<div class="tool_bt" id="alt_sublinhado"><img src="../../images/botoes/tool_sublinhado.png" onClick="doUnderline()" /></div>
								<div class="tool_bt" id="alt_tamanho"><img src="../../images/botoes/tool_tamanho.png" onClick="doSize()" /></div>
<?php
if($_SESSION['user']->podeAcessar($perm['portfolio_adicionarLinks'], $turma))
{
?>
								<div class="tool_bt" id="alt_link"><img src="../../images/botoes/tool_link.png" /></div>
<?php
}

if($_SESSION['user']->podeAcessar($perm['portfolio_adicionarArquivos'], $turma))
{
?>
								<div class="tool_bt" id="alt_arquivo"><img src="../../images/botoes/tool_arquivo.png" /></div>
								<div class="tool_bt" id="alt_imagem"><img src="../../images/botoes/tool_imagem.png" /></div>
<?php
}
?>
							</li>
						<li><iframe id="text_post" width="100%" ></iframe></li>
					</ul>
				</div>
			<div class="bts_baixo">
			<a href="portfolio.php?turma=<?=$turma?>" align="left" >
				<img src="../../images/botoes/bt_cancelar.png" border="0" align="left"/>
			</a>
			<input type="image" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
		</form>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
</body>
</html>
