<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("lista_posts.php");
require("../../usuarios.class.php");
require("../../reguaNavegacao.class.php");

$projeto_id = isset($_GET['projeto_id']) ? (int) $_GET['projeto_id'] : 0;

// print_r(get_defined_constants()); // Descomente isso para rir.

if (!isset($_SESSION['SS_usuario_nivel_sistema'])) // if not logged in
	die("Voce precisa estar logado para acessar essa p&aacute;gina. <a href=\"../../\">Favor voltar.</a>");

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}else{
	die("Voce n&atilde;o passou a ID da sua turma para a p&aacute;gina, favor voltar e tentar novamente.");
}

$user = new Usuario();

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);

if($perm === false){
	die("Desculpe, mas o Portf&oacute;lio esta desabilitado para esta turma.");
}
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="portfolio.css" />
<script src="../../js/rooda.js"></script>
</head>

<body onload="atualiza('ajusta()');inicia();coment();">
<?
		global $tabela_portfolioProjetos;
		$consulta= new conexao();
		$consulta->solicitar("SELECT * FROM $tabela_portfolioProjetos WHERE id = $projeto_id");
		$titulo = $consulta->resultado['titulo'];
		$descricao = $consulta->resultado['descricao'];
		$objetivos = $consulta->resultado['objetivos'];
		$conteudosAbordados = $consulta->resultado['conteudosAbordados'];
		$metodologia = $consulta->resultado['metodologia'];
		$publicoAlvo = $consulta->resultado['publicoAlvo'];
?>
	<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
		<h1>TÍTULO DA POSTAGEM</h1>
		<img src="../../images/botoes/bt_fechar.png" id="abre_coment" class="fechar_coments" onmousedown="abreFechaLB()" />
		<div class="recebe_coments">
		<ul class="sem_estilo" id="ie_coments">
			<ul>
			<li class="tabela_blog">
				FULANO DE TAL - Donec dignissim purus sit amet ligula lobortis quis congue sem pulvinar. Ut velit diam, pretium non varius vitae, placerat sit amet libero. Nam ornare condimentum est ac tincidunt. Mauris vitae ligula tellus. Sed orci diam, tempus nec accumsan non, facilisis in nisl. Curabitur dictum magna non mi interdum nec auctor massa feugiat. Sed sed lacus ac nisl sagittis tincidunt vitae nec mi.
			</li>
			<li class="tabela_blog">
				FULANO DE TAL - Donec dignissim purus sit amet ligula lobortis quis congue sem pulvinar. Ut velit diam, pretium non varius vitae, placerat sit amet libero. Nam ornare condimentum est ac tincidunt. Mauris vitae ligula tellus. Sed orci diam, tempus nec accumsan non, facilisis in nisl. Curabitur dictum magna non mi interdum nec auctor massa feugiat. Sed sed lacus ac nisl sagittis tincidunt vitae nec mi.
			</li>
			<li class="tabela_blog">
				FULANO DE TAL - Donec dignissim purus sit amet ligula lobortis quis congue sem pulvinar. Ut velit diam, pretium non varius vitae, placerat sit amet libero. Nam ornare condimentum est ac tincidunt. Mauris vitae ligula tellus. Sed orci diam, tempus nec accumsan non, facilisis in nisl. Curabitur dictum magna non mi interdum nec auctor massa feugiat. Sed sed lacus ac nisl sagittis tincidunt vitae nec mi.
			</li>
		</ul>
			<li id="novo_coment">
				POSTAR NOVO COMENTÁRIO
			</li>
			<li>
				<textarea class="msg_dimensao" rows="10"></textarea>
			</li>
			<li>
				<div class="enviar" align="right">
					<input type="image" src="../../images/botoes/bt_confir_pq.png" />
				</div>
			</li>
		</ul>
		</div>
	</div>

<div id="topo">
	<div id="centraliza_topo">
		<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Portfólio", "portfolio_inicio.php", false);
				$regua->adicionarNivel("Projeto");
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
			<a href="portfolio.php?turma=<?=$turma?>" align="left" >
				<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
			</a>
			<a href="portfolio_postagem.php?projeto_id=<?=$projeto_id?>&amp;turma=<?=$turma?>" align="right" >
				<img src="../../images/botoes/bt_postagem.png" border="0" align="right"/>
			</a>
		</div>
		<div id="esq">
			<div id="projeto" class="bloco">
				<h1 ><a class="toggle" id="toggle_projeto">▼</a> PROJETO</h1>
				<ul class="sem_estilo" id="caixa_projeto">
<?php
	if ($descricao != "")
		echo "					<li>
						<span class=\"dados\">Descrição:
						</span>
					</li>
					<li class=\"texto_port\">
						<span class=\"valor\">$descricao
						</span>
					</li>";
?>
					<li>
						<span class="dados">Objetivos:
						</span>
					</li>
					<li class="texto_port">
						<span class="valor"><?=$objetivos?>
						</span>
					</li>
<?php
	if ($conteudosAbordados != "")
		echo "					<li>
						<span class=\"dados\">Conteúdos Abordados:
						</span>
					</li>
					<li class=\"texto_port\">
						<span class=\"valor\">$conteudosAbordados
						</span>
					</li>";

	if ($metodologia != "")
		echo "					<li>
						<span class=\"dados\">Metodologia:
						</span>
					</li>
					<li class=\"texto_port\">
						<span class=\"valor\">$metodologia
						</span>
					</li>";
	if ($publicoAlvo != "")
		echo "					<li>
						<span class=\"dados\">Publico-Alvo:
						</span>
					</li>
					<li class=\"texto_port\">
						<span class=\"valor\">$publicoAlvo
						</span>
					</li>";
?>
					
				</ul>
			</div> <!-- fim da div projeto -->
			<div id="postagens" class="bloco">
				<h1 ><a class="toggle" id="toggle_posts">▼</a> POSTAGENS</h1>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_posts">
					<?
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
						<form name="form_arquivo" id="form_arquivo" method="post" enctype="multipart/form-data" action="../../uploadFile.php?funcionalidade_id=<?=$projeto_id?>&amp;funcionalidade_tipo=<?=TIPOPORTFOLIO?>">
							<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
							<div class="file_input" style="display:inline-block">
								<input name="userfile" type="file" id="procura_arquivo" class="upload_file" title="Procurar Arquivo" style="" required />
							</div>
							<div id="f_arquivo" style="display:inline-block;width: 80px;" class="falso_text">&nbsp;</div>
							<br>
							<input type="submit" name="upload" value="upload!" style="float:right" />
						</form>
							<script>

	var form_arquivo = new ROODA.AjaxForm("form_arquivo",["arquivo_id","arquivo_nome","arquivo_titulo","arquivo_tamanho","arquivo_tipo","erros"]);
	form_arquivo.onResponse = function(){
		if(this.response.erros != false){
			alert(this.response.erros);
		} else {
			alert("Arquivo enviado com sucesso.");
		}
	}

	// -------------
	var bt_arquivo = document.getElementById('procura_arquivo');
	var f_arquivo = document.getElementById('f_arquivo');
	
	bt_arquivo.onchange = function (){
		f_arquivo.innerHTML = '';
		for (i=0;i<this.files.length;i++){
			f_arquivo.innerHTML = this.files[i].name + ' ';
		}
	};
</script>
					</li>
					<?
						global $tabela_arquivos;
						$tipoPortfolio = TIPOPORTFOLIO;
						$consulta = new conexao();
						$consulta->solicitar("SELECT arquivo_id,titulo,nome FROM $tabela_arquivos WHERE funcionalidade_tipo = '$tipoPortfolio' AND funcionalidade_id = '$projeto_id'");
						while($consulta->resultado)
						{
							$fileId = $consulta->resultado['arquivo_id'];
							if(trim($consulta->resultado['titulo']) != ""){
								$nomeArquivo = $consulta->resultado['titulo'];
							}
							else $nomeArquivo = $consulta->resultado['nome'];
					?>
							<li class="tabela_port" id="liFile<?=$fileId?>">
								<a href="../../downloadFile.php?id=<?=$fileId?>" target="_blank" ><?=$nomeArquivo?></a>
								<img src="../../images/botoes/bt_x.png" align="right"/>
							</li>
					<?
							$consulta->proximo();
						}
					?>
					</ul>
					<div style="clear:both"><!-- empty --></div>
				</div>
			</div>
			<div id="links" class="bloco">
				<h1 ><a class="toggle" id="toggle_link">▼</a> LINKS</h1>
				<div class="add" id="addLink" onclick="botaoAdicionar('addLinkDiv');">adicionar</div>
				<div class="bloqueia">
					<ul class="sem_estilo" id="caixa_link">
					
					
					<div id="addLinkDiv" style="display:none;">
						<li class="tabela_port">
							<form name="addLinkForm" action="inserirLinkBd.php" method="post">
								<input type="text" name="newLink" align="left"/>
								<input type="hidden" name="projeto_id" value="<?=$projeto_id ?>" />
								<input type="image" onClick="addLinkForm.submit()" src="../../images/botoes/bt_confirm.png" width="50%" height="50%" align="right"/>
								<img onClick="hideDiv('addLinkDiv');" src="../../images/botoes/bt_cancelar.png" width="50%" height="50%" align="left"/>
							</form>
						</li>
					</div>
					<?
						global $tabela_links;
						$tipoPortfolio=TIPOPORTFOLIO;
						$consulta = new conexao();
						$consulta->solicitar("SELECT * FROM $tabela_links WHERE funcionalidade_tipo = '$tipoPortfolio' AND funcionalidade_id = '$projeto_id'");
						print $consulta->erro;
						print "SELECT * FROM $tabela_links WHERE funcionalidade_tipo = '$tipoPortfolio' AND funcionalidade_id = '$projeto_id'";
						
						for ($i=0 ; $i < count($consulta->itens) ; $i++){
							$liId = $consulta->resultado['Id'];
							
							$titulo="";
							if ($consulta->resultado['titulo'] == ""){
								$titulo = $consulta->resultado['endereco'];
							}
							else $titulo = $consulta->resultado['titulo'];
					?>
							<li class="tabela_port" id=<?=("liLink".$liId)?> >
								<a href="<?=$consulta->resultado['endereco']?>" target="_blank" align="left" ><?=$titulo?></a>
								<img onclick="if (confirm('Tem certeza que deseja apagar este link?')){this.style.visibility = 'hidden';deleteBd('<?=$liId?>','<?=$tabela_links?>','Id')}" src="../../images/botoes/bt_x.png" align="right"/>
							</li>
					<?
							$consulta->proximo();
						}
					?>

					
					
					</ul>
				</div>
			</div>
		</div>
		<div id="dir">
			<div id="posts" class="bloco" >
				<h1 id="nome_projeto"><?=$titulo /*fullUpper($titulo)*/?></h1>
				<?
					global $tabela_portfolioPosts;
					$consulta = new conexao();
					$consulta->solicitar("SELECT * FROM $tabela_portfolioPosts WHERE projeto_id = $projeto_id ORDER BY dataCriacao DESC");
					
					//print_r($consulta);
					
					for ($i = 0 ; $i < count($consulta->itens) ; $i++){
						$postId = $consulta->resultado['id'];
				?>
				<div class="cor<?=alterna()?>" id="postDiv<?=$postId?>" >
					<ul class="sem_estilo">
						<li class="tabela_port">
							<span class="titulo">
								<div class="textitulo"><?=$consulta->resultado['titulo']?></div>
								<img onclick="if (confirm('Tem certeza que deseja apagar este post?')){mataPost('postDiv<?=$postId?>');deleteBd('<?=$postId?>','<?=$tabela_portfolioPosts?>','id', '<?=$turma?>')}" src="../../images/botoes/bt_x.png" align="right"/>
							</span>
							<span class="data">
								<?=$consulta->resultado['dataCriacao'] ?>
							</span>
						</li>
						<li class="tabela_port">
						<p>
							<?=$consulta->resultado['texto']?>
						</p> 
						</li>
						<li class="tabela_port">
							<a id="abre_coment" onmousedown="abreComments('pid=<?=$postId?>&amp;turma=<?=$turma?>')">Ver comentários</a>
						</li>
					</ul>
				</div>
				<?
						$consulta->proximo();
					} //fim do for de geração de posts
				?>
			</div>
		</div>
		<div class="bts_baixo">
			<a href="portfolio.php?turma=<?=$turma?>" align="left" >
				<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
			</a>
			<a href="portfolio_postagem.php?projeto_id=<?=$projeto_id?>&amp;turma=<?=$turma?>" align="right" >
				<img src="../../images/botoes/bt_postagem.png" border="0" align="right"/>
			</a>
		</div>
	</div><!-- Fecha Div conteudo -->
	</div><!-- Fecha Div conteudo_meio -->
	<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
<iframe name="deletante" style="visibility: hidden;"></iframe>

<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="portfolio.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->
<script type="text/javascript" src="../../postagem_wysiwyg.js"></script>

<script language="javascript">
function coment(){
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		document.getElementById('ie_coments').style.width = 85 + '%';
	}
}
</script>
</body>
</html>
