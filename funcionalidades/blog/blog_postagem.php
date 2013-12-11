<?php

/*\
 *
 * funcionalidades/blog/blog_postagem.php
 *
\*/
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../reguaNavegacao.class.php");
	require_once("../../usuarios.class.php");
	require_once("blog.class.php");
//	require_once("verifica_user.php");
//	require_once("visualizacao_blog.php");

	session_start();

	$usuario = new Usuario();
	$usuario->openUsuario($_SESSION['SS_usuario_id']);

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
var refreshImageList = (function() {
	function getFileListHandler() {
		var t, res, n, i, images_container, id, html = [];
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
					console.log(res.errors.join("\n"));
				}
				console.log("Couldn't refresh image list");
				return;
			} else {
				// SUCCESS
				n = res.files.length;
				images_container = document.getElementById("cont_img3");
				if (images_container) {
					for (i=0;i<n;i+=1) {
						id = res.files[i].file_id;
						html.push('<div id="galeria'+id+'" class="img_enviadas"><img onclick="fromgallery('+id+')" src="../../image_output.php?file='+id+'" /></div>');
					}
					images_container.innerHTML = html.join("\n");
				}
			}
		}
	}
	return getFileListFunction(getFileListHandler,<?=$funcionalidade_id?>,<?=$funcionalidade_tipo?>,"image/%");
}());

var uploadAttImage = (function () {
	function handler() {
		var loading, t, res, html;
		
		if (this.readyState !== this.DONE) {
			// requisição em andamento, nao fazer nada.
			return;
		}
		// Fim do request, remover tela de loading
		if (loading = document.getElementById('loading')) {
			loading.style.display = 'none';
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
				alert(res.errors.join("\n"));
			} else if (res.file_id && res.file_name) {
				// SUCCESS
				html = imageHTML(res.file_id);
				objHolder.focus();
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
		var e = document.getElementById('loading');
		if (e) {
			e.style.display = 'block';
		}
		upload(oFormElement);
	});
}());

var uploadAttFile = (function() {
	function handler() {
		var loading, t, res, html;
		if (this.readyState !== this.DONE) {
			// requisição em andamento, nao fazer nada.
			return;
		}
		// Fim do request, remover tela de loading
		if (loading = document.getElementById('loading')) {
			loading.style.display = 'none';
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
				alert(res.errors.join("\n"));
			} else if (res.file_id && res.file_name) {
				// SUCCESS
				html = fileHTML(res.file_id,res.file_name);
				objHolder.focus();
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
		var e = document.getElementById('loading');
		if (e) {
			e.style.display = 'block';
		}
		upload(f);
	});
}());

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
<body onload="atualiza('ajusta()');inicia(); checar(); ajusta_img(); Init(); fakeFile('botao_upload_frame', 'arquivo_frame', 'falso_frame'); fakeFile('botao_upload_frame_ins','arquivo_frame_ins', 'falso_frame_ins');">
	<div id="descricao"></div>
	<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
		<img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
		<div id="imagem_lbox">
			<h1>INSERIR IMAGEM</h1>
			<ul class="sem_estilo" style="line-height:25px">
				<li><input type="radio" id="troca_img1" class="select_img" name="select_img" checked="checked" value="1"/>Procurar no Computador</li>
				<li><input type="radio" id="troca_img2" class="select_img" name="select_img" value="2" />Imagem da Web</li>
				<li><input type="radio" id="troca_img3" class="select_img" name="select_img" value="3" onclick="refreshImageList();" />Procurar nas imagens já enviadas</li>
				<li>
					<div id="cont_img">
						<ul id="cont_img1">
							<form id="upload_image" method="post" enctype="multipart/form-data" action="../../uploadFile.php?funcionalidade_id=<?=$funcionalidade_id?>&amp;funcionalidade_tipo=<?=$funcionalidade_tipo?>" target="alvoAJAXins" onsubmit="uploadAttImage(this); return false;">
								<input type="hidden" name="MAX_FILE_SIZE" value="2000000" /> 
								<input name="userfile" type="file" id="arquivo_frame_ins" class="upload_file" allow="image/png,image/jpg,image/gif" onchange="trocador('falso_frame_ins', 'arquivo_frame_ins')" />
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
<?php
							//	Dumpando a lista de imagens que tem no blog
							$consulta = new conexao();

							/*\
							 *	SELECT arquivo FROM $tabela_arquivos WHERE tipo LIKE 'image/%'
							 *	Pega o BLOB de todas as imagens pra dar resize.
							\*/

							global $tabela_arquivos; // nao sei se precisa disso
							$consulta->solicitar("SELECT arquivo_id FROM $tabela_arquivos WHERE tipo LIKE 'image/%' AND funcionalidade_tipo = '$funcionalidade_tipo' AND funcionalidade_id = '$funcionalidade_id'");

							while($consulta->resultado) {
								$id = $consulta->resultado['arquivo_id']; 

								echo '<div class="img_enviadas" id="galeria'.$id.'" ><img src="../../image_output.php?file='.$id.'" onClick="fromgallery('.$id.')"/></div>';
								$consulta->proximo();
							}
?>
	<br style="clear:both;" />
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
				<li><input onclick="arquivos_mode = 1;" value="1" type="radio" id="troca_arq1" class="select_arq" name="select_arq" checked="checked" />Procurar no Computador</li>
				<li><input onclick="arquivos_mode = 0;" value="0" type="radio" id="troca_arq2" class="select_arq" name="select_arq"/>Procurar nos arquivos já enviados</li>
				<li>
					<div id="cont_arq">
						<ul id="cont_arq1">
							<li id="procurar_arq">
								Adicionar novo arquivo:
								<form method="post" enctype="multipart/form-data" action="../../uploadFile.php?funcionalidade_id=<?=$funcionalidade_id?>&amp;funcionalidade_tipo=<?=$funcionalidade_tipo?>" onsubmit="uploadAttFile(this);return false;" target="alvoAJAX">
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
							$consulta->solicitar("SELECT nome,arquivo_id FROM $tabela_arquivos WHERE funcionalidade_tipo='$funcionalidade_tipo' AND funcionalidade_id='$funcionalidade_id'");

							while($consulta->resultado) {
								$arquivo_id = $consulta->resultado['arquivo_id'];
								$arquivo_nome = $consulta->resultado['nome'];
?>
								<li class="enviado<?=($i % 2) + 1?>"><input type="checkbox" id="file<?=$arquivo_id?>" name="arquivo" value="<?=$arquivo_id?>" /><span id="fileN<?=$arquivo_id?>"><?=$arquivo_nome?></span></li>
<?php
								$consulta->proximo();
							}
?>
						</ul>
					</div>
				</li>
				<li>
					<div align="right"><input type="image" onclick="arquivoInsert();" src="../../images/botoes/bt_confir_pq.png" /></div>
				</li>
			</ul>
		</div>
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
			<form name="fConteudo" method="post" action="_blog_escreve_postagem.php" onsubmit="javascript:gravaConteudo();">
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
								<div class="tool_bt" id="alt_imagem"><img src="../../images/botoes/tool_imagem.png" /></div>
								<div class="tool_bt" id="alt_link"><img src="../../images/botoes/tool_link.png" /></div>
								<div class="tool_bt" id="alt_arquivo"><img src="../../images/botoes/tool_arquivo.png" /></div>
							</li>
						<li><iframe class="blog_info" id="iView" style="border:solid 1px #74d3ed; background-color:#fff; height:400px"></iframe></li>
					</ul>
				</div>
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
		<div class="spacer_50"><!-- empty --> </div>
		<div class="loading_anim">
			<h2>Processando</h2>
		</div>
	</div>
</body>
</html>
