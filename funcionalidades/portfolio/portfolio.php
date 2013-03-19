<?php
session_start();
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("../../reguaNavegacao.class.php");

global $tabela_portfolioProjetos;
$id_usuario = $_SESSION['SS_usuario_id'];
$nome_usuario = $_SESSION['SS_usuario_nome'];

$user = new Usuario();
$user->openUsuario($id_usuario);

if (!isset($_SESSION['SS_usuario_nivel_sistema'])) // if not logged in
	die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");

if (isset($_GET['turma']) and $_GET['turma'] != ""){
	$turma = $_GET['turma'];
} else $turma = 192; // AAAAAAAAAAAAAAAAAAAAAAAAAA REMOVA ISSO DEPOIS DO CURSO


$perm = checa_permissoes(TIPOPORTFOLIO, $turma);

if($perm == false){
	die("Desculpe, mas o Portfolio esta desabilitado para esta turma.");
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
			$regua->adicionarNivel("Portfólio");
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
				<div id="rel"><p id="balao">Funcionalidade destinada aos formadores e voltada à construção de um histórico da turma através do registro e da publicação de arquivos, possibilitando acompanhar os alunos e as práticas pedagógicas.</p>
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
			<a href="portfolio_novo_projeto.php?turma=<?=$turma?>" align="right" >
				<img src="../../images/botoes/bt_postagem.png" border="0" align="right"/>
			</a>
		</div>&nbsp;<!--NÃO REMOVA ESSE NON-BREAKING SPACE-->
<?php
if(sizeof($_SESSION['SS_turmas']) > 1){
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
					<div class="abas_port aberto" id="aba_andamento"> PROJETOS EM ANDAMENTO</div>
					<div class="abas_port fechado" id="aba_encerrado"> PROJETOS ENCERRADOS</div>
				</h1>
<?php
			if(!$user->podeAcessar($perm['portfolio_visualizarPost'], $turma)){
				die("Voce nao tem permissoes para visualizar posts.");
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
			
			$consulta->solicitar("SELECT * FROM $tabela_portfolioProjetos WHERE $condicao ORDER BY id DESC");
			
			
			$projOpcao = "proj_andamento";
			for($opcao=1 ; $opcao<=2 ; $opcao++){
				echo "<div id=\"$projOpcao\">";
				for ($i=0 ; $i < count($consulta->itens) ; $i++){
					if ((($opcao==1) and ($consulta->resultado['emAndamento']==true)) or (($opcao==2) and ($consulta->resultado['emAndamento']==false)) ){
						$projeto_id = $consulta->resultado['id'];
?>
						<div class="cor<?=alterna() /*funcoes_aux.php*/?>" id="proj_id<?=$i?>">
							<ul class="sem_estilo">
								<li class="texto_port">
									<span class="valor">
										<a class="port_titulo" href="portfolio_projeto.php?projeto_id=<?=$projeto_id?>&amp;turma=<?=$consulta->resultado['turma']?>">
											<?=$consulta->resultado['titulo'] ?>
										</a>
									</span>
								</li>
								<li class="texto_port"><span class="dados">Autor:</span><span class="valor"><?=$consulta->resultado['autor'] ?></span></li>
								<li>
									<span class="dados">Descrição:</span>
									<span class="valor"><?=$consulta->resultado['descricao'] ? $consulta->resultado['descricao'] : "Sem descrição" ?></span>
								</li>
<?php
if ($consulta->resultado['emAndamento']==true)
	echo "								<a class=\"encerrar\" onclick=\"fechaProjeto($projeto_id, $i);\">[Encerrar projeto]</a>";
?>
							</ul>
						</div>
<?php
					}
					
					$consulta->proximo();
				} //fim for de dentro (consulta de itens)
?>
					</div>
<?php
				$consulta->primeiro();
				$projOpcao = "proj_encerrados";
			}//fim for de fora (troca das opcoes)
?>

			</div> <!-- fim da div de id="projetos" -->
		</div>
		<div class="bts_baixo">
			<a href="portfolio_novo_projeto.php?turma=<?=$turma?>" align="right" >
				<img src="../../images/botoes/bt_postagem.png" border="0" align="right"/>
			</a>
		</div>
	</div><!-- Fecha Div conteudo -->
	</div><!-- Fecha Div conteudo_meio -->   
	<div id="conteudo_base">
	</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
</body>
</html>
