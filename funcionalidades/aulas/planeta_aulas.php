<?php
require_once("../../funcoes_aux.php");
require_once("../../cfg.php");
require_once("../../reguaNavegacao.class.php");
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../file.class.php");
require_once("../../usuarios.class.php");
require_once("../../funcoes_aux.php");
require_once("../../reguaNavegacao.class.php");

session_start();
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

$turma = "";
if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}

$permissoes = checa_permissoes(TIPOAULA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}


$temPoderCriarEditarImportar = $usuario->podeAcessar($permissoes['aulas_criarAulas'], $turma)
	|| $usuario->podeAcessar($permissoes['aulas_editarAulas'], $turma)
	|| $usuario->podeAcessar($permissoes['aulas_importarAulas'], $turma);


if(!$temPoderCriarEditarImportar){ // Se for aluno...
	magic_redirect("ver_aulas.php?turma=$turma");
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="aulas.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
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
				$regua->adicionarNivel("Aulas");
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
					<div id="rel"><p id="balao">Na ferramenta Aulas, é possível esquematizar e explicar as aulas que serão dadas. As aulas podem ser criadas pelo coordenador, professor e monitor e acessadas pelos alunos. Após sua criação, elas podem ser editadas, de forma que suas inconsistências sejam corrigidas.</p></div>
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
				if($usuario->podeAcessar($permissoes['aulas_criarAulas'], $turma)){
					echo	"<div class=\"bloco opcao\">
								<h1>CRIAR</h1>
								<a href=\"criar.php?turma=$turma\"><img class=\"resize\" src=\"../../images/desenhos/aulas/criar.png\" border=\"0px\"/></a>
							</div>";
				}
				if($usuario->podeAcessar($permissoes['aulas_editarAulas'], $turma)){
					echo	"<div class=\"bloco opcao\">
								<h1>EDITAR</h1>
								<a href=\"seleciona_edicao.php?turma=$turma\"><img class=\"resize\" src=\"../../images/desenhos/aulas/editar.png\" border=\"0px\" /></a>
							</div>";
				}
			?>
			<div class="bloco opcao">
				<h1>VISUALIZAR</h1>
				<a href="ver_aulas.php?turma=<?=$turma?>"><img class="resize" src="../../images/desenhos/aulas/visualizar.png" border="0px" /></a>
			</div>
			<?php
				if($usuario->podeAcessar($permissoes['aulas_importarAulas'], $turma)){
					echo	"<div class=\"bloco opcao\">
								<h1>IMPORTAR</h1>
								<a href=\"importar.php?turma=$turma\"><img class=\"resize\" src=\"../../images/desenhos/aulas/importar.png\" border=\"0px\" /></a>
							</div>";
				}
			?>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->

</body>
</html>
