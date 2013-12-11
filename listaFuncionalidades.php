<?php
session_start();
header('Content-type: text/html; charset=utf-8');
require_once("cfg.php");
require_once("bd.php");
require_once("funcoes_aux.php");

if (!isset($_SESSION['SS_usuario_id'])){ // Se isso não estiver setado, o usuario não está logado
	die('<a href="index.php">Por favor volte e entre em sua conta.</a>');
}
$turma = isset($_GET['turma']) ? (int) $_GET['turma'] : 0;
if ($turma <= 0) {
	die("Turma não encontrada. <a href=\"index.php\">Voltar</a>");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Planeta Rooda - Funcionalidades</title>
		<link href="planeta.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<div id="geral">
		<!-- **************************
						cabecalho
		***************************** -->
		<div id="cabecalho">
			 <div id="ajuda">
				  <div id="ajuda_meio">
						<div id="ajudante">
							 <div id="personagem"><img src="images/desenhos/ajudante.png" height=145 align="left" alt="Ajudante" /></div>
							 <div id="rel"><p id="balao">Aqui você pode acessar todas as funcionalidades habilitadas na sua turma.</p></div>
						</div>
				  </div>
				  <div id="ajuda_base"></div>
			 </div>
		</div><!-- fim do cabecalho -->
		<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
		<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->
			<div id="conteudo" style="position:relative;margin-top:0;"><!-- tem que estar dentro da div 'conteudo_meio' -->
				<ul class="listaFun">
<?php
// Biblioteca
if(checa_permissoes(TIPOBIBLIOTECA, $turma)) { 
?>
					<li><a href="funcionalidades/biblioteca/biblioteca.php?turma=<?=$turma?>">Biblioteca</a></li>
<?php
}	
// Blog
if(checa_permissoes(TIPOBLOG, $turma)) { 
?>
					<li><a href="funcionalidades/blog/blog_inicio.php?turma=<?=$turma?>">Webfólio</a></li>
<?php
}	
// Forum
if(checa_permissoes(TIPOFORUM, $turma)) { 
?>
					<li><a href="funcionalidades/forum/forum.php?turma=<?=$turma?>">Forum</a></li>
<?php
}	
// Portfolio
if(checa_permissoes(TIPOPORTFOLIO, $turma)) { 
?>
					<li><a href="funcionalidades/portfolio/portfolio.php?turma=<?=$turma?>">Projetos</a></li>
<?php
}
// Arte
if(checa_permissoes(TIPOARTE, $turma)) { 
?>
					<li><a href="funcionalidades/arte/planeta_arte2.php?turma=<?=$turma?>">Arte</a></li>
<?php
}
// Pergunta
if(checa_permissoes(TIPOPERGUNTA, $turma)) { 
?>
					<li><a href="funcionalidades/pergunta/planeta_pergunta.php?turma=<?=$turma?>">Pergunta</a></li>
<?php
}
// Aulas
if(checa_permissoes(TIPOAULA, $turma)) { 
?>
					<li><a href="funcionalidades/aulas/planeta_aulas.php?turma=<?=$turma?>">Aulas</a></li>
<?php
}
// Player
if(checa_permissoes(TIPOPLAYER, $turma)) { 
?>
					<li><a href="funcionalidades/roodaplayer/index.php?turma=<?=$turma?>">Player</a></li>
<?php
}
?>
				</ul>
			</div>
		</div>
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
	</div>
	</body>
</html>
