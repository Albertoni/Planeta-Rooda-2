<?php
session_start();
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");

global $tabela_portfolioProjetos;
$id_usuario = isset($_SESSION['SS_usuario_id']) ? $_SESSION['SS_usuario_id'] : 0;
$nome_usuario = isset($_SESSION['SS_usuario_nome']) ? $_SESSION['SS_usuario_nome'] : "";

$user = new Usuario();
$user->openUsuario($id_usuario);

if (!isset($_SESSION['SS_usuario_nivel_sistema'])) // if not logged in
	die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");

if (isset($_GET['turma']) and $_GET['turma'] != ""){
	$turma = $_GET['turma'];
}


$perm = checa_permissoes(TIPOPORTFOLIO, $turma);

if($perm == false){
	die("Desculpe, mas os Projetos est&atilde;o desabilitados para esta turma.");
}

function imprimeListaProjetos($nomeDiv, $resultado){
	global $user; global $perm; global $turma;

	echo "				<div id=\"$nomeDiv\">\n";
	for ($i=0 ; $i < count($resultado) ; $i++){
		$projeto_id = $resultado['id'];
?>
					<div class="cor<?=($i%2)+1?>" id="proj_id<?=$projeto_id?>">
						<ul class="sem_estilo">
							<li class="texto_port">
								<span class="valor">
									<a class="port_titulo" href="portfolio_projeto.php?projeto_id=<?=$projeto_id?>&amp;turma=<?=$resultado['turma']?>">
										<?=$resultado['titulo'] ?>
									</a>
								</span>
							</li>
							<li class="texto_port">
								<span class="dados">Autor:</span>
								<span class="valor">ASSIM Ó TEM QUE VER O QUE VAI AQUI</span>
							</li>
							<li>
								<span class="dados">Descrição:</span>
								<span class="valor">ASSIM Ó TEM QUE VER O QUE VAI AQUI</span>
							</li>
<?php
if($user->podeAcessar($perm['portfolio_editarPost'], $turma)){
	echo "								<a class=\"encerrar\" href=\"portfolio_novo_projeto.php?projeto_id=$projeto_id
	&amp;turma=".$resultado['turma']."\">[Editar projeto]</a>\n";
}

if($resultado['emAndamento']==true){
	echo "								<a class=\"encerrar\" onclick=\"fechaProjeto($projeto_id, $projeto_id);\">[Encerrar projeto]</a>\n";
}
?>
							</ul>
						</div>
<?php
	}
	echo "				</div>\n";
}

?><!DOCTYPE html>
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
<script type="text/javascript" src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">

<div id="topo">
	<div id="centraliza_topo">
		<?php 
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("Projetos");
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
				<div id="rel"><p id="balao">Funcionalidade destinada aos formadores e voltada à construção de um histórico da turma através do registro e da publicação de arquivos, possibilitando acompanhar os alunos e as práticas pedagógicas.<b> NECESSÁRIO UM NOVO TEXTO AQUI?</b></p>
				</div>
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
			<a href="portfolio_novo_projeto.php?turma=<?=$turma?>" style="float:right" >
				<img src="../../images/botoes/bt_postagem.png" border="0"/>
			</a>
		</div>&nbsp;<!--NÃO REMOVA ESSE NON-BREAKING SPACE-->
<?php
if(sizeof($user->getTurmas()) > 1){
	selecionaTurmas($turma);
}
?>
		<br />
		<div id="esq">
			<div id="procurar_proj" class="bloco">
				<h1>PROCURAR PROJETO</h1>
					<form name="procurar_projetos" method="post" action="portfolio.php">
						<ul class="sem_estilo">
							<li><input type="text" name="projeto_procurado" /></li>
							<li><input type="radio" name="p_proj" value="1" />Título</li>
							<li><input type="radio" name="p_proj" value="2" />Conteúdos Abordados</li>
							<li><input type="radio" name="p_proj" value="3" />Palavras do Projeto</li>
							<li><div class="enviar" align="right"><input type="image" name="bt_procurar_projetos" src="../../images/botoes/bt_procurar.png" /></div>
							</li>
						</ul>
					</form>
			</div>
			<div id="projetos" class="bloco">
				<h1>
					<div class="abas_port aberto" id="aba_andamento">MEUS PROJETOS</div>
					<div class="abas_port fechado" id="aba_encerrado">PROJETOS DOS COLEGAS</div>
				</h1>
<?php
			if(!$user->podeAcessar($perm['portfolio_visualizarPost'], $turma)){
				die("Voce nao tem permissoes para visualizar os projetos.");
			}

			$consulta = new conexao();
			
			$condicao = "owner_id = $id_usuario OR turma=$turma";
			
			if (isset($_POST['projeto_procurado'])){
				$procurar = $consulta->sanitizaString($_POST['projeto_procurado']); // bom dia SQL injection primária
				switch($_POST['p_proj']){
					case "1":
						$condicao .= " AND titulo LIKE '%$procurar%'";
					break;
					case "2":
						$condicao .= " AND descricao LIKE '%$procurar%'";
					break;
					case "3":
						$condicao .= " AND tags LIKE '%$procurar%'";
					break;
				}
			}
			
			$consulta->solicitar("SELECT * FROM $tabela_portfolioProjetos WHERE owner_id = $id_usuario AND turma = $turma ORDER BY id DESC");
			imprimeListaProjetos("proj_andamento", $consulta->resultado);

			$consulta->solicitar("SELECT * FROM $tabela_portfolioProjetos WHERE owner_id <> $id_usuario AND turma = $turma ORDER BY id DESC");
			imprimeListaProjetos("proj_encerrados", $consulta->resultado);
?>

			</div> <!-- fim da div de id="projetos" -->
		</div>
		<div class="bts_baixo">
			<a href="portfolio_novo_projeto.php?turma=<?=$turma?>" style="float:right" >
				<img src="../../images/botoes/bt_postagem.png" border="0"/>
			</a>
		</div>
		<div style="clear:both;"></div>
	</div><!-- Fecha Div conteudo -->
	</div><!-- Fecha Div conteudo_meio -->   
	<div id="conteudo_base">
	</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
</body>
</html>
