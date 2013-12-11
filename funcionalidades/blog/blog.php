<?php
session_start();
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../login.class.php");
//  require_once("verifica_user.php");
require_once("blog.class.php");
require_once("../../file.class.php");
require_once("../../link.class.php");
//  require_once("visualizacao_blog.php");
require_once("../../reguaNavegacao.class.php");

header('Content-type: text/html; charset=utf-8');
$usuario = usuario_sessao();
if (!$usuario) { die("voce nao esta logado"); }

$blog_id = isset($_GET['id']) ? $_GET['id'] : die("N&atilde;o foi fornecido id de Webf&oacute;lio.");
$turma = (int) (isset($_GET['turma']) ? $_GET['turma'] : 0);

$blog = new Blog($blog_id, $turma); // se não existe, isso cria o blog

if(!is_numeric($blog_id)){
	$blog_id = $blog->getId();
}

$ini = isset($_GET['ini']) && $_GET['ini'] >= 0 ? floor($_GET['ini']/$blog->getPaginacao())*$blog->getPaginacao() : 0;
$ini = $ini < 0 ? 0 : $ini;
$ini = $ini > $blog->getSize() ? floor($blog->getSize()/$blog->getPaginacao())*$blog->getPaginacao() : $ini;

$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);


$permissoes = checa_permissoes(TIPOBLOG, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

// "blog_inserirPost,blog_editarPost,blog_inserirComentarios,blog_excluirPost,blog_adicionarLinks,blog_adicionarArquivos";
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8" />
	<title>Planeta ROODA 2.0</title>
	<link type="text/css" rel="stylesheet" href="../../planeta.css" />
	<link type="text/css" rel="stylesheet" href="blog.css" />
</head>

<body onload="thumbnailImgsFromClass('tabela_blog',250,350,true);atualiza('ajusta()');inicia();coment();">
<div id="descricao"></div>
<div id="fundo_lbox"></div>
<div id="light_box" class="bloco"></div>
<div id="topo">
<div id="centraliza_topo">
		<?php 
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("Webfólio");
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
				<div id="rel"><p id="balao">Aqui, você pode encontra um espaço para escrita pessoal onde pode compartilhar 
				diversos assuntos com seus colegas e permitir que eles, além de visualizar, publiquem comentários 
				em suas postagens.</p></div>
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
		<a href="blog_inicio.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_voltar.png" align="left"/></a>
<?php
if ($usuario->podeAcessar($permissoes["blog_inserirPost"], $turma)){
	echo "		  <a href=\"blog_postagem.php?blog_id=$blog_id&amp;turma=$turma\" class=\"right\"><img src=\"../../images/botoes/bt_criar_postagem.png\" border=\"0\" align=\"right\"/></a>";
}
?>
		</div>
		<div class="troca_paginas">
			<center>
			<div class="paginas_padding">
				<?=$blog->mostraPaginacao($ini)?>
			</div>
			</center>
		</div>
		<div id="esq" class="margem_paginas">
			<div class="bloco" id="ident">
				<h1><?=fullUpper($blog->getTitle())?></h1>

<?php
// script para a exibição dos posts
	$id_estilo = 1;
	for($i=$ini;($i<$ini+$blog->getPaginacao()) && ($i<$blog->getSize());$i++) {
		imprimePost($blog->posts[$i], $blog->getId(), $id_estilo, $blog->owners, $usuario_id, $usuario, $permissoes, $turma);
		$id_estilo = 3 - $id_estilo; // alterna o estilo da div entre 2 e 1
	}
?>
			</div>
		</div>
		<div id="dir" class="margem_paginas">
			<div class="bloco" id="perfil">
				<h1 id="nomeblog"><a class="toggle" id="toggle_perfil">▼</a> <?php echo count($blog->owners) > 1 ? "AUTORES" : fullUpper($blog->owners[0]->getName()) ?></h1>
				<ul class="sem_estilo" id="caixa_perfil">
<?php
	foreach($blog->owners as $owner) {
		imprimeDono($owner, $usuario_id);
	}
?>
				</ul>
			</div>
			<div class="bloco" id="post">
				<h1><a class="toggle" id="toggle_post">▼</a> POSTAGENS</h1>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_post">
<?php
imprimeListaPosts($blog->getId(), $turma);
?>
					</ul>
				</div>
			</div>
			<div class="bloco" id="arquivos">
				<?php 
				$consulta = new conexao();
				$consulta->solicitar("SELECT Tipo FROM $tabela_blogs WHERE Id = $blog_id");
				$tipoBlog = $consulta->resultado['Tipo'];
				$funcionalidade_id = $blog->getId();
				$funcionalidade_tipo = $tipoBlog;
				?>
				
				<h1><a class="toggle" id="toggle_arq">▼</a> ARQUIVOS </h1>
				<!-- <div class="add" id="divLinkAdicionarArquivo">adicionar</div> -->
				<div class="add" onclick="botaoAdicionar('addFileDiv')">adicionar</div>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_arq">
					<li id="addFileDiv" style="display:none">
						<form id="file_form" method="post" enctype="multipart/form-data" action="../../uploadFile.php?funcionalidade_id=<?=$blog_id?>&amp;funcionalidade_tipo=<?=TIPOBLOG?>" onsubmit="submitFileForm(this);return false;">
							<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
							<div class="file_input" style="display:inline-block">
								<input name="userfile" type="file" id="procura_arquivo" class="upload_file" title="Procurar Arquivo" style="" required />
							</div>
							<div id="f_arquivo" style="display:inline-block;width: 80px;" class="falso_text">&nbsp;</div>
							<br>
							<button type="submit" class="submit" name="upload" value="Enviar" style="float:right">Enviar</button>
						</form>
						<script>


	// -------------
	var bt_arquivo = document.getElementById('procura_arquivo');
	var f_arquivo = document.getElementById('f_arquivo');
	

	var change_file = function (){
		f_arquivo.innerHTML = '&nbsp;';
		for (i=0;i<bt_arquivo.files.length;i++){
			f_arquivo.innerHTML = bt_arquivo.files[i].name + ' ';
		}
	};
	bt_arquivo.onchange = change_file;
	bt_arquivo.form.onreset = change_file;
						</script>
						</li>
<?php
							//jquery com javascript
							//colocar um evento onClick no adicionar
							//evento tornarah uma div invisivel em visivel reestruturando adequadamente a pagina
							//
							$consulta = new conexao();
							$id = $blog->getId();
							$consulta->solicitar("SELECT nome,arquivo_id FROM $tabela_arquivos WHERE funcionalidade_tipo='$tipoBlog' AND funcionalidade_id='$blog_id'");

							$downloadFile="../../downloadFile.php";
							$funcionalidade_tipo=(string)$tipoBlog;
							$funcionalidade_id=(string)$id;
							for($i=0 ; $i<count($consulta->itens);$i++) {
								$file_name= $consulta->resultado['nome'];
								$destino =$downloadFile;
								$destino.="?id=".$consulta->resultado['arquivo_id'];
?>
								<li class="tabela_blog" id="liFile<?=$consulta->resultado['arquivo_id']?>">
									<a href="<?=$destino?>" target='_blank'><?=$file_name ?></a> 
									<div class="bts_caixa"><img class="apagar" src="../../images/botoes/bt_x.png" onclick="ROODA.ui.confirm('Tem certeza que deseja excluir este arquivo?',function(){deleteFile(<?=$consulta->resultado['arquivo_id']?>);});"/></div>
								</li>
<?php
								$consulta->proximo();
							}
?>
					</ul>
				</div>
			</div>
			<div id="links" class="bloco">
				<h1><a class="toggle" id="toggle_link">▼</a> LINKS</h1>
				<div class="add" id="addLink" onclick="botaoAdicionar('addLinkLi');">adicionar</div>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_link">
					<li id="addLinkLi" class="tabela_blog" style="display:none;">
						<form name="addLinkForm" action="../../inserirLink.php?funcionalidade_tipo=<?=$funcionalidade_tipo?>&amp;funcionalidade_id=<?=$funcionalidade_id?>" onsubmit="submitLinkForm(this);return false;" method="post">
							Novo Link: <br><input name="novoLink" id="novoLink" type="text"/><br>
							<input name="submit" type="submit" id="submit" value="Submit" />
						</form>
					</li>
<?php
						$consulta = new conexao();
						$consulta->solicitar("SELECT * FROM $tabela_links WHERE funcionalidade_tipo = '$funcionalidade_tipo' AND funcionalidade_id = '$funcionalidade_id'");
						while ($consulta->resultado){
							$linkId = $consulta->resultado['Id'];
							$endereco = $consulta->resultado['endereco'];
							$titulo = trim($consulta->resultado['titulo']);
									 if ($titulo === "") { 
										 $titulo = $endereco;
									 } else {
										 $titulo = $consulta->resultado['titulo'];
									 }
?>
								 <li class="tabela_blog" id=liLink<?=$linkId?>>
							 <a href="<?=$endereco?>" target="_blank" align="left" ><?=$titulo?></a>
							 <img onclick="ROODA.ui.confirm('Tem certeza que deseja apagar este link?',function(){deleteLink(<?=$linkId?>);});" src="../../images/botoes/bt_x.png" align="right"/>
						 </li>
<?php
									 $consulta->proximo();
								}
?>
					</ul>
				</div>
			</div>
			<div class="bloco" id="tag">
				<h1><a class="toggle" id="toggle_tag">▼</a> TAGS</h1>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_tag">
<?php
foreach ($blog->tags as $tag){
	imprimeTags($tag, $blog_id);
}
?>
					</ul>
				</div>
			</div>
		</div>
			<div class="troca_paginas">
				<center>
				<div class="paginas_padding">
					<?=$blog->mostraPaginacao($ini)?>
				</div>
				</center>
			</div>

		<div class="bts_baixo">
			<a href="blog_inicio.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_voltar.png" align="left"/></a>
<?php
if ($usuario->podeAcessar($permissoes["blog_inserirPost"], $turma)){
	echo "		  <a href=\"blog_postagem.php?blog_id=$blog_id&amp;turma=$turma\" class=\"right\"><img src=\"../../images/botoes/bt_criar_postagem.png\" border=\"0\" align=\"right\"/></a>";
}
?>
		</div>
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

<script src="../../jquery.js"></script>
<script src="../../js/compatibility.js"></script>
<script src="../../js/rooda.js"></script>
<script src="../../js/ajax.js"></script>
<script src="../../js/ajaxFileManager.js"></script>
<script src="../../postagem_wysiwyg.js"></script><!--para o mostraDescri()-->
<script src="../../planeta.js"></script>
<script src="blog.js"></script>
<script src="blog_ajax.js"></script>
<script src="../lightbox.js"></script>
<script src="blog_ajax2.js"></script>
<script src="../../js/thumbnailImages.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->
</body>
</html>