<?php
header('Content-type: text/html; charset=utf-8');
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("lista_posts.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");
require_once("portfolio.class.php");
require_once("ArquivosPost.class.php");

$user = usuario_sessao();

$projeto_id = isset($_GET['projeto_id']) ? (int) $_GET['projeto_id'] : 0;
$funcionalidade_tipo = TIPOPORTFOLIO;
$funcionalidade_id = $projeto_id;

if (!isset($_SESSION['SS_usuario_nivel_sistema'])) // if not logged in
	die("Voce precisa estar logado para acessar essa p&aacute;gina. <a href=\"../../\">Favor voltar.</a>");

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}else{
	die("Voce n&atilde;o passou a ID da sua turma para a p&aacute;gina, favor voltar e tentar novamente.");
}

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);

if($perm === false){
	die("Desculpe, mas o Portf&oacute;lio esta desabilitado para esta turma.");
}

$projeto = new projeto($projeto_id);
$titulo = $projeto->getTitulo();
$palavras = $projeto->getPalavrasString();
$donos = $projeto->getOwners();
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="portfolio.css" />
</head>

<body onload="thumbnailImgsFromClass('postagem',150,380,true);atualiza('ajusta()');inicia();coment();">
<div id="topo">
	<div id="centraliza_topo">
		<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Projetos", "portfolio_inicio.php", false);
				$regua->adicionarNivel($titulo);
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
				<div id="rel"><p id="balao">Funcionalidade destinada aos formadores e voltada à construção de um histórico
				da turma através do registro e da publicação de arquivos, possibilitando acompanhar os alunos e as práticas
				pedagógicas.</p></div>
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
			<a href="portfolio.php?turma=<?=$turma?>">
				<img src="../../images/botoes/bt_voltar.png" border="0"/>
			</a>
<?php
if(($user->podeAcessar($perm['portfolio_inserirPost'], $turma)) && (in_array($user->getId(), $donos))){
	echo "			<a href=\"portfolio_postagem.php?projeto_id=$projeto_id&amp;turma=$turma\" style=\"float:right\">
				<img src=\"../../images/botoes/bt_criar_postagem.png\" border=\"0\"/>
			</a>";
}
?>
		</div>
		<div id="esq">
			<div id="postagens" class="bloco">
				<h1 ><a class="toggle" id="toggle_posts">▼</a> POSTAGENS</h1>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_posts">
					<?php
						$posts = new lista_posts($projeto_id, $tabela_portfolioPosts);
						
						for ($i=0; $i < $posts->tamanho_lista; $i++){
							if($posts->lista[$i][0] == "\n"){ // Caso seja um marcador de fim de alguma coisa...
								switch (substr($posts->lista[$i], 1)){
								case "end_month":
									echo "
									</ul>
								</li>";
									break;


								case "end_year":
									echo "
							</ul>
						</li>";
									break;


								case "new_year":
									$i += 1;// GAMBIARRAS 8D
											// Ele incrementa em um tanto aqui quando na abaixo porque o new_algo não contem os dados do mes/ano. Precisa incrementar pra pegar ele.
									echo "
						<li class=\"post_ano\">
							<a href=\"javascript:abre_topico($i);\" class=\"no_underline\">".$posts->lista[$i]."</a>
						</li>
						<li class=\"tabela_oculta\" id=\"topico_oculto$i\"> <!--safadeza_oculta-->
							<ul>";
									break;


								case "new_month":
									$i += 1;
									echo "
								<li class=\"post_mes\">
									<a href=\"javascript:abre_topico($i);\" class=\"no_underline\">" /*NOTE QUE TEM UM $i AO LADO DE abre_topico*/.getMonth($posts->lista[$i])."</a>
								</li>
								<li class=\"tabela_oculta\" id=\"topico_oculto$i\">
									<ul>";
									break;
								}
							} else {
								$pingas = explode("\n", $posts->lista[$i]); // SnooPING AS usual, I see.
								// Falando mais sério, é passado o id do post e o nome dele, separados por um \n, que a chamada acima divide em um array.
								// pingas[0] = nome, [1] = id.
								echo "
										<li class=\"post_topico\">
											<a name=\"".$pingas[1]."\" class=\"no_underline\">".$pingas[0]."</a>
										</li>";
							}
						}?>
					</ul>
				</div>
			</div>
			<div id="arquivos" class="bloco">
				<h1 ><a class="toggle" id="toggle_arq">▼</a> ARQUIVOS</h1>
				<!-- criar uma funcao no javascript para abrir a tela "formNovoArquivo" e esperar uma resposta.
				Se arquivo criado no BD devolver uma resposta ao javascript para atualizar a pagina -->
				<div class="add" onclick="botaoAdicionar('addFileDiv')">adicionar</div>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_arq">
					<li id="addFileDiv" style="display:none">
						<form id="file_form" method="post" enctype="multipart/form-data" action="../../uploadFile.php?funcionalidade_id=<?=$funcionalidade_id?>&amp;funcionalidade_tipo=<?=$funcionalidade_tipo?>" onsubmit="submitFileForm(this);return false;">
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
						global $tabela_arquivos;
						$tipoPortfolio = TIPOPORTFOLIO;
						$consulta = new conexao();
						$consulta->solicitar("SELECT arquivo_id,titulo,nome FROM $tabela_arquivos WHERE funcionalidade_tipo = '$tipoPortfolio' AND funcionalidade_id = '$projeto_id'");
						while($consulta->resultado)
						{
							$fileId = $consulta->resultado['arquivo_id'];
							
							$nomeArquivo = $consulta->resultado['nome'];
					?>
							<li class="tabela_port" id="liFile<?=$fileId?>">
								<a href="../../downloadFile.php?id=<?=$fileId?>" target="_blank" ><?=$nomeArquivo?></a>
								<button type="button" class="bt_excluir" onclick="ROODA.ui.confirm('Tem certeza que deseja excluir este arquivo?',function(){deleteFile(<?=$fileId?>);});" align="right">excluir</button>
							</li>
					<?php
							$consulta->proximo();
						}
					?>
					</ul>
					<div style="clear:both"><!-- empty --></div>
				</div>
			</div>
			<div id="links" class="bloco">
				<h1><a class="toggle" id="toggle_link">▼</a> LINKS</h1>
				<div class="add" id="addLink" onclick="botaoAdicionar('addLinkLi');">adicionar</div>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_link">
					<li id="addLinkLi" class="tabela_port" style="display:none;">
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
								 <li class="tabela_port" id=liLink<?=$linkId?>>
							 <a href="<?=$endereco?>" target="_blank" align="left" ><?=$titulo?></a>
							 <button type="button" class="bt_excluir" onclick="ROODA.ui.confirm('Tem certeza que deseja apagar este link?',function(){deleteLink(<?=$linkId?>);});" align="right"></button>
						 </li>
<?php
									 $consulta->proximo();
								}
?>
					</ul>
				</div>
			</div>
		</div>
		<div id="dir">
			<div id="posts" class="bloco" >
				<h1 id="nome_projeto"><?=$titulo?></h1>
				<?php
				$postsProjeto = $projeto->getPosts();
				$numPosts = count($postsProjeto);
				
				for ($i=0; $i < $numPosts; $i++){
						echo $postsProjeto[$i]->geraHtmlPost();
				}
?>
			</div>
		</div>
		<div class="bts_baixo">
			<a href="portfolio.php?turma=<?=$turma?>">
				<img src="../../images/botoes/bt_voltar.png" border="0"/>
			</a>
<?php
if(($user->podeAcessar($perm['portfolio_inserirPost'], $turma)) && (in_array($user->getId(), $donos))){
	echo "			<a href=\"portfolio_postagem.php?projeto_id=$projeto_id&amp;turma=$turma\" style=\"float:right\">
				<img src=\"../../images/botoes/bt_criar_postagem.png\" border=\"0\"/>
			</a>";
}
?>
		</div>
	</div><!-- Fecha Div conteudo -->
	</div><!-- Fecha Div conteudo_meio -->
	<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
	<!-- loading -->
	<div id="loading" style="display:none;">
		<div class="spacer_50"><!-- empty --></div>
		<div class="loading_anim">
			<h2>Processando</h2>
		</div>
	</div>
<iframe name="deletante" style="visibility: hidden;"></iframe>
<script src="../../js/compatibility.js"></script>
<script src="../../js/rooda.js"></script>
<script src="../../js/ajax.js"></script>
<script src="../../js/ajaxFileManager.js"></script>
<script src="portfolio_ajax.js"></script>
<script src="../../jquery.js"></script>
<script src="../../comentarios.js"></script>
<script src="../../planeta.js"></script>
<script src="portfolio.js"></script>
<script src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->
<script src="../../postagem_wysiwyg.js"></script>
<script src="../../js/thumbnailImages.js"></script>
<script>
function coment() {
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		document.getElementById('ie_coments').style.width = 85 + '%';
	}
}
</script>
</body>
</html>