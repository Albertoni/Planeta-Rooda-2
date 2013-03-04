<?php
	require_once("class/planeta.php");
	require_once("cfg.php");
	require_once("bd.php");
	require_once("funcoes_aux.php");
	require_once("reguaNavegacao.class.php");
	require_once("usuarios.class.php");
	require_once("turma.class.php");
	require_once("AlteracoesTurmasUsuario.php");
	
	session_start();
	
	if (!isset($_SESSION['SS_usuario_id'])){ // Se isso não estiver setado, o usuario não está logado
		die("<a href=\"index.php\">Por favor volte e entre em sua conta.</a>");
	}
	
	
	$usuario = new Usuario();
	$usuario->openUsuario($_SESSION['SS_usuario_id']);
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- CSS -->
		<link href="tela_inicial_geral.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<img id="logo" src="images/desenhos/logo_planeta.png">
		<img onclick="redirecionarParaFuncionalidade('criarAvatar', false, <?=$_SESSION['SS_personagem_id']?>)" src="images/planeta-personalizaravatar.png" 
			style="position:absolute; right:200px; top:10px; cursor:pointer;">
		<img src="images/botoes/sair.png" style="position:absolute; right:0px; top:5px; cursor:pointer;" onclick="document.location='index.php?action=log0001';">
		<img id="texto" src="images/tela_inicial/escolha_tela_inicial.png">
		<div id="container">
			<div id="containerMural" class="contemConteudo">
			<!-- JAVASCRIPT -->
				<!--Jquery para pegar o tamanho da tela, para alinhar os planetas-->
			<script type="text/javascript" src="jquery.js"></script>
				<!-- Class -->
			<script type="text/javascript" src="Janela.js"></script>
			<script type="text/javascript" src="Mural.js"></script>
			<script type="text/javascript" src="LinkPlaneta.js"></script>
			<script type="text/javascript" src="EscolhaPlanetas.js"></script>
				<!-- Functions -->
			<script type="text/javascript" src="tela_inicial_geral.js"></script>
				<!-- *************************************************************************************************************************
																		MURAL
					************************************************************************************************************************* -->
				<div id="divLugarDoMural">
				</div>
				<script>var Mural = new Mural();</script>
				<?php
					$alteracoesTurmas = new AlteracoesTurmasUsuario($usuario);
					$mensagensProfessor = $alteracoesTurmas->gerarMensagensAlteracoesTurmasComPapel($nivelProfessor);
					foreach($mensagensProfessor as $mensagem){ echo "<script>Mural.adicionarJanela(\"".$mensagem."\")</script>"; }
					$mensagensMonitor = $alteracoesTurmas->gerarMensagensAlteracoesTurmasComPapel($nivelMonitor);
					foreach($mensagensMonitor as $mensagem){ echo "<script>Mural.adicionarJanela(\"".$mensagem."\")</script>"; }
					$mensagensAluno = $alteracoesTurmas->gerarMensagensAlteracoesTurmasComPapel($nivelAluno);
					foreach($mensagensAluno as $mensagem){ echo "<script>Mural.adicionarJanela(\"".$mensagem."\")</script>"; }
				?>
				<script>
					var divLugarDoMural = document.getElementById("divLugarDoMural");
					divLugarDoMural.innerHTML = Mural.converterParaHtml();
				</script>
				<!-- ***************************************** FIM DO MURAL ***************************************** -->
			</div>
			<div id="containerPlanetas" class="contemConteudo">
				<!-- *************************************************************************************************************************
																		LINKS DOS PLANETAS 
					************************************************************************************************************************* -->
				<div id="divLugarDosPlanetas">
				</div>
				<script>
					var menuPlanetas = new EscolhaPlanetas();
				</script>
				<?php
					$planetasQuePodeAcessar = $usuario->getPlanetasQuePodeAcessar();
					foreach($planetasQuePodeAcessar as $planeta){
						$idPlaneta = $planeta->__get("IdTerenoPrincipal");
						$nomePlaneta = $planeta->__get("Nome");
						switch($planeta->getAparencia()){
							case PlanetaBD::APARENCIA_VERDE:
							case PlanetaBD::APARENCIA_GRAMA:	$tipoPlaneta = "LinkPlaneta.TiposPlanetas.GRAMA";
								break;
							case PlanetaBD::APARENCIA_LAVA:		$tipoPlaneta = "LinkPlaneta.TiposPlanetas.LAVA";
								break;
							case PlanetaBD::APARENCIA_GELO:		$tipoPlaneta = "LinkPlaneta.TiposPlanetas.NEVE";
								break;
							case PlanetaBD::APARENCIA_URBANO:	$tipoPlaneta = "LinkPlaneta.TiposPlanetas.URBANO";
								break;
							default:						$tipoPlaneta = "LinkPlaneta.TiposPlanetas.GRAMA";
						}
					
						echo "<script> menuPlanetas.adicionarLinkPlaneta(new LinkPlaneta(".$idPlaneta.", ".$tipoPlaneta.", '".$nomePlaneta."')); </script>";
					}
				?>
				<script>
					var divLugarDosPlanetas = document.getElementById("divLugarDosPlanetas");
					divLugarDosPlanetas.innerHTML = menuPlanetas.converterParaHtml(20,0);
				</script>
				<!-- ***************************************** FIM DOS LINKS DOS PLANETAS ***************************************** -->
			</div>
		</div>
	</body>
</html>
