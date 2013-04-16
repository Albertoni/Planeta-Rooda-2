<?php


require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("aula.class.php");
require("../../reguaNavegacao.class.php");

session_start();
if (! isset($_SESSION['SS_usuario_id'])) die("favor voltar e logar em sua conta");

$turma = isset($_GET['turma']) ? (int) $_GET['turma'] : false;
if (!$turma)
{
	die("Turma n&atilde;o encontrada");
}

$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);


$permissoes = checa_permissoes(TIPOAULA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if(!$usuario->podeAcessar($permissoes['aulas_criarAulas'], $turma)){
	$host	=	$_SERVER['HTTP_HOST'];
	$uri	=	rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: http://$host$uri/planeta_aulas.php?turma=$turma");
}

$funcionalidade_tipo=TIPOAULA;
$funcionalidade_id = $turma;

$aula_id = isset($_GET['aula_id']) ? $_GET['aula_id'] : 0;
$aula = new aula();


if($aula_id!=0) {
	$aula->abreAula($aula_id);
	$edita = true;
	
	if(($aula->getTipo() == 1) and ($aula->getMaterial() != "")){
		$cont = trim(str_replace("'","&prime;",$aula->getMaterial()));
		$cont = trim(str_replace("\r\n"," ",$cont));
	}
}else{
	$perm = checa_permissoes(TIPOAULA, $turma);
	//if ($perm[])
	$edita = false;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css"/>
<link type="text/css" rel="stylesheet" href="aulas.css"/>
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="aulas.js"></script>
<script type="text/javascript" src="../../js/ajax.js"></script>
<script type="text/javascript" src="../../js/ajaxFileManager.js"></script>
<script type="text/javascript" src="../../postagem_wysiwyg.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>


<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->


<!-- <script type="text/javascript" src="blog_postagem.js"></script> -->
<script language="javascript">
var refreshImageList = (function() {
	function getFileListHandler() {
		if (this.readyState !== this.DONE) {
			// requisição em andamento, nao fazer nada.
			return;
		}
		
		if (this.status !== 200) {
			return;
		}
		if(t = this.responseText) {
			try {
				res = JSON.parse(t);
			}
			catch (e) {
				console.log("JSON: " + e.message + ":\n" + t); 
				return;
			}
			if (!res.ok) {
				if (res.errors) {
					var erro = res.errors[0];
					for (var i=1; i < res.errors.length; i+=1) {
						erro += "\n"+res.errors[i];
					}
					console.log(erro);
				}
				console.log("Couldn't refresh image list");
				return;
			} else {
				// SUCCESS
				var n = res.files.length;
				var images_container = document.getElementById("cont_img3");
				if (images_container) {
					var html = "";
					for (var i=0;i<n;i+=1) {
						var id = res.files[i].file_id;
						html += '<div id="galeria'+id+'" class="img_enviadas"><img onclick="fromgallery('+id+')" src="../../image_output.php?file='+id+'" /></div>\n';
					}
					images_container.innerHTML = html;
				}
			}
		}
	}
	return getFileListFunction(getFileListHandler,<?=$turma?>,<?=TIPOAULA?>,"image/%");
}());

var uploadAttImage = (function () {
	function handler() {
		
		if (this.readyState !== this.DONE) {
			// requisição em andamento, nao fazer nada.
			return;
		}
		// Fim do request, remover tela de loading
		if (e = document.getElementById('loading')) {
			e.style.display = 'none';
		}
		if (this.status !== 200) {
			alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
			return;
		}
		if (t = this.responseText) {
			try {
				res = JSON.parse(t);
			}
			catch (e) {
				console.log("JSON: "+e.message+":\n"+t);
				alert ("Algo de errado aconteceu.");
			}
			if(res.errors) {
				var erro = res.errors[0];
				for(var i=1;i<res.errors.length;i+=1) {
					erro += "\n" + res.errors[i];
				};
				alert(erro);
			} else if (res.file_id && res.file_name) {
				// SUCCESS
				var html = imageHTML(res.file_id);
				objContent.execCommand('inserthtml',false,html);
				abreFechaLB();
				document.getElementById('troca_img3').onclick();
			} else {
				alert("Não sabemos o que aconteceu, mas estamos trabalhando para descobrir");
			}
		}
	}
	
	var upload = submitFormFunction(handler);
	
	return (function (oFormElement) {
		if (e = document.getElementById('loading')) {
			e.style.display = 'block';
		}
		upload(oFormElement);
	});
}());

var uploadAttFile = (function() {
	function handler() {
		if (this.readyState !== this.DONE) {
			// requisição em andamento, nao fazer nada.
			return;
		}
		// Fim do request, remover tela de loading
		if (e = document.getElementById('loading')) {
			e.style.display = 'none';
		}
		if (this.status !== 200) {
			alert("Não foi possivel contatar o servidor.\nVerifique sua conexão com a internet.");
			return;
		}
		if (t = this.responseText) {
			try {
				res = JSON.parse(t);
			}
			catch (e) {
				console.log("JSON: "+e.message+":\n"+t);
				alert ("Algo de errado aconteceu.");
			}
			if(res.errors) {
				var erro = res.errors[0];
				for(var i=1;i<res.errors.length;i+=1) {
					erro += "\n" + res.errors[i];
				};
				alert(erro);
			} else if (res.file_id && res.file_name) {
				// SUCCESS
				var html = fileHTML(res.file_id,res.file_name);
				objContent.execCommand('inserthtml',false,html);
				abreFechaLB();
				document.getElementById('troca_img3').onclick();
			} else {
				alert("Não sabemos o que aconteceu, mas estamos trabalhando para descobrir");
			}
		}
	}
	var upload = submitFormFunction(handler);
	return (function (f) {
		if (e = document.getElementById('loading')) {
			e.style.display = 'block';
		}
		upload(f);
	});
}());

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
	objHolder = document.getElementById('iView');
	if(ua == "Netscape") 
		objContent = objHolder.contentDocument;
	else
		objContent = objHolder.document;

	objContent.designMode = "On";
<?=($edita ? "objContent.write('$cont');" : " ");?>
	objContent.body.style.fontFamily = 'Verdana';
	objContent.body.style.fontSize = '11px';
}
</script>


</head>
<body onload="atualiza('ajusta()');Init();inicia(); checar(); ajusta_img(); fakeFile('botao_upload_frame', 'arquivo_frame', 'falso_frame'); fakeFile('botao_upload_frame_ins','arquivo_frame_ins', 'falso_frame_ins');">
	<div id="descricao"></div>
	<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
		<img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
		<div id="imagem_lbox">
			<h1>INSERIR IMAGEM</h1>
			<ul class="sem_estilo" style="line-height:25px">
				<li><input type="radio" id="troca_img1" class="select_img" name="select_img" checked="checked" onclick="modo=1"/>Procurar no Computador</li>
				<li><input type="radio" id="troca_img2" class="select_img" name="select_img" onclick="modo=2"/>Imagem da Web</li>
				<li><input type="radio" id="troca_img3" class="select_img" name="select_img" onclick="modo=3"/>Procurar nas imagens já enviadas</li>
				<li>
					<div id="cont_img">
						<ul id="cont_img1">
							<form method="post" enctype="multipart/form-data" action="../../uploadImage.php?funcionalidade_id=<?=$turma?>&funcionalidade_tipo=<?=TIPOAULA?>" target="alvoAJAXins" onsubmit="uploadAttImage(this); return false;">
								<input type="hidden" name="MAX_FILE_SIZE" value="2000000" /> 
								<input name="userfile" type="file" id="arquivo_frame_ins" class="upload_file" style="" onchange="trocador('falso_frame_ins', 'arquivo_frame_ins')" />
								<input name="falso" type="text" id="falso_frame_ins" />
								<img src="../../images/botoes/bt_procurar_arquivo.png" id="botao_upload_frame_ins" />
								<input type="submit" name="upload" value="upload!" />
							</form><br />
							<iframe id="alvoAJAXins" name="alvoAJAXins" style="display: none;" src=""></iframe>
							<iframe id="editavel" name="editavel" frameborder="0" src="">Por favor, atualize seu navegador.</iframe>
						</ul>
						
						<ul id="cont_img2">
							<li><input type="text" value="http://" id="imagefromurl" /></li>
							<li style="margin-top:-5px">Endereço da imagem</li>
						</ul>
						<div id="cont_img3">
<?php /*
						<table width="100%">
						<tr>
<?php
 */
							$consulta = new conexao();

							/*\
							 *	SELECT arquivo FROM $tabela_arquivos WHERE tipo LIKE 'image/%'
							 *	Pega o BLOB de todas as imagens pra dar resize.
							\*/
							global $tabela_arquivos;
							$consulta->solicitar("SELECT arquivo_id FROM $tabela_arquivos WHERE tipo LIKE 'image/%'");
							$consulta->solicitar("SELECT arquivo_id FROM $tabela_arquivos WHERE tipo LIKE 'image/%' AND funcionalidade_tipo = '$funcionalidade_tipo' AND funcionalidade_id = '$funcionalidade_id'");

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
		
		<div id="link_lbox">
			<h1>INSERIR LINK</h1>
			<ul class="sem_estilo">
				<li>Texto a ser exibido: <input type="text" id="addlinktext" /></li>
				<li style="margin-bottom:172px">Link para: <input type="text" value="http://" id="addlinkurl" /></li>
				<li>
					<div align="right"><img src="../../images/botoes/bt_confir_pq.png" alt="Confirmar" onclick="addLink()" /></div>
				</li>
			</ul>
		</div>
		
		<div id="customHTML_lbox">
			<h1>EDITAR HTML</h1>
			<ul class="sem_estilo">
				<li style="margin-bottom:172px"><textarea id="customHTML" rows="10" cols="68"></textarea></li>
				<li>
					<div align="right"><img src="../../images/botoes/bt_confir_pq.png" alt="Confirmar" onclick="submitCustomHTML()" /></div>
				</li>
			</ul>
		</div>
		
		<div id="arquivo_lbox">
			<h1>ANEXAR ARQUIVO</h1>
			<ul class="sem_estilo" style="line-height:25px">
				<li><input type="radio" id="troca_arq1" class="select_arq" onclick="arquivos_mode=1" name="select_arq" checked="checked" />Procurar no Computador</li>
				<li><input type="radio" id="troca_arq2" class="select_arq" onclick="arquivos_mode=2" name="select_arq"/>Procurar nos arquivos já enviados</li>
				<li>
					<div id="cont_arq">
						<ul id="cont_arq1">
							<li id="procurar_arq">
								Adicionar novo arquivo:
								<form method="post" enctype="multipart/form-data" action="uploadImage.php?funcionalidade_id=<?=$aula_id?>&funcionalidade_tipo=<?=TIPOAULA?>" target="alvoAJAX">
									<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
									<input type="hidden" name="gambiarra" value="3337333" />
									<input name="userfile" type="file" id="arquivo_frame" class="upload_file" style="" onchange="trocador('falso_frame', 'arquivo_frame')" />
									<input name="falso" type="text" id="falso_frame" />
									<img src="../../images/botoes/bt_procurar_arquivo.png" id="botao_upload_frame" />
									<input type="submit" name="upload" value="upload!" />
								</form>
								<iframe id="alvoAJAX" name="alvoAJAX" src="" style="display: none;"></iframe>
								<iframe id="previewarquivos" name="previewarquivos" src="" frameborder="0"></iframe>
						</ul>
						<ul id="cont_arq2">

<?php
							$consulta = new conexao();
							//global $tabela_arquivos;
							$consulta->solicitar("SELECT * FROM $tabela_arquivos WHERE funcionalidade_tipo = ".TIPOAULA." AND funcionalidade_id = $turma");
							for($i=0 ; $i<count($consulta->itens);$i++) {
?>
								<li class="enviado<?=($i % 2) + 1?>"><input type="checkbox" id="file<?=$consulta->resultado['arquivo_id']?>" onclick="addRemove(<?=$consulta->resultado['arquivo_id']?>, '<?=$consulta->resultado['nome']?>')" /><?=$consulta->resultado['nome']?></li>
<?php
								$consulta->proximo();
							}
/*								<li class="enviado1"><input type="checkbox" />Arquivo.ext</li>
								<li class="enviado2"><input type="checkbox" />Arquivo.ext</li>
								<li class="enviado1"><input type="checkbox" />Arquivo.ext</li>
								<li class="enviado2"><input type="checkbox" />Arquivo.ext</li>*/
?>
						</ul>
					</div>
				</li>
				<li>
					<div align="right"><a href="javascript:arquivoInsert();"><img src="../../images/botoes/bt_confir_pq.png" onclick="confirmaAnexarArquivos()" /></a></div>
				</li>
			</form>
			</ul>
		</div>
	</div>
	
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Aulas", "planeta_aulas.php?turma=$turma", false);
				$regua->adicionarNivel("Criar");
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
					<div id="rel"><p id="balao">Para criar uma nova aula basta inserir um título, data e uma breve descrição da aula, escolher o tipo de aula e a cor de fundo da página. Outros campos também podem ser inseridos, além de links, arquivos, imagens e vídeos. Após o preenchimento, basta clicar no botão “Confirmar”.</p></div>
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
			<form name="fConteudo" method="post" action="_criaAula.php?turma=<?=$turma?>" onsubmit="gravaConteudo();validaForm(this);">
			<div class="bts_cima">
			<a href="planeta_aulas.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
			<input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
				<div id="info_post" class="bloco">
					<input type="hidden" name="turma" value="<?=$turma?>" />
					<input type="hidden" name="aula_id" value="<?=$edita ? $aula->getId() : ''?>" />
					<input type="hidden" name="text" value="" />
					<h1><?=$edita ? fullUpper($aula->getTitulo()) : "NOVA AULA"?></h1>
					<ul class="sem_estilo">
						<li class="sem_margem">Título</li>
						<li class="sem_margem"><input type="text" name="titulo" <?=$edita ? "value=\"".$aula->getTitulo()."\"" : ''?> class="blog_info"/></li>
						<li class="sem_margem">Data</li>
						<li class="sem_margem"><input type="text" name="data" <?=$edita ? "value=\"".$aula->getData()."\"" : ''?> class="blog_info"/></li>
						<li class="sem_margem">Descrição curta da aula: (<span id="contador"><?=$edita ? strlen($aula->getDesc()) : 0?></span>/500 caracteres)</li>
						<li class="sem_margem"><input type="text" name="desc" <?=$edita ? "value=\"".$aula->getDesc()."\"" : ''?> class="blog_info" onkeypress="descCurta(this);"/></li>
						<li class="sem_margem">Tipo da aula:
							<select name="tipo" onchange="mudaInput(this)">
								<option value="1">Montar página</option>
								<option value="2">Enviar arquivo</option>
								<option value="3">Endereço web</option>
							</select>
						</li>
						<li class="sem_margem">Cor do fundo:
							<select name="fundo" onchange="mudaFundo(this.value)">
								<option value="1">Verde-água</option>
								<option value="2">Verde</option>
								<option value="3">Azul</option>
								<option value="5">Rosa</option>
								<option value="6">Laranja</option>
								<option value="7">Roxo</option>
							</select>
						</li>
						<div id="bala_de_gambiarra">
							<li style="height:22px; margin-bottom:4px; margin-top:10px">
								<div class="tool_bt" id="par_tit" onclick="addParTit()"><img src="../../images/botoes/tool_paragrafotitulo.png" /></div>
								<div class="tool_bt" id="only_tit" onclick="addTit()"><img src="../../images/botoes/tool_titulosomente.png" /></div>
								<div class="tool_bt" id="only_par" onclick="addPar()"><img src="../../images/botoes/tool_paragrafosomente.png" /></div>
								<div class="tool_bt" id="cust_html"><img src="../../images/botoes/tool_html.png" /></div>
								<br /><br />
								<div class="tool_bt" id="alt_negrito"><img src="../../images/botoes/tool_negrito.png" onClick="doBold()" /></div>
								<div class="tool_bt" id="alt_italico"><img src="../../images/botoes/tool_italico.png" onClick="doItalic()" /></div>
								<div class="tool_bt" id="alt_sublinhado"><img src="../../images/botoes/tool_sublinhado.png" onClick="doUnderline()" /></div>
								<div class="tool_bt" id="alt_tamanho"><img src="../../images/botoes/tool_tamanho.png" onClick="doSize()" /></div>
								<div class="tool_bt" id="alt_imagem"><img src="../../images/botoes/tool_imagem.png" /></div>
								<div class="tool_bt" id="alt_link"><img src="../../images/botoes/tool_link.png" /></div>
								<div class="tool_bt" id="alt_arquivo"><img src="../../images/botoes/tool_arquivo.png" /></div>
							</li>
							<br />
							<li><iframe class="blog_info" id="iView" style="border:solid 1px #74d3ed; background-color:#fff; height:400px"></iframe></li>
						</div>
						<div id="bala_de_arquivo" style="display:none">
							Arquivo: <input type="file" name="arqui">
						</div>
						<div id="bala_de_internet" style="display:none">
							Link: <input type="text" name="link" />
						</div>
					</ul>
				</div>
			<div class="bts_baixo">
			<a href="planeta_aulas.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
			<input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
		</form>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->

	</div><!-- fim da geral -->

</body>
</html>
