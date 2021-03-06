<?php
	require_once("cfg.php");
	require_once("bd.php");
	require_once("funcoes_aux.php");

	require_once("planeta.class.php");
	require_once("usuarios.class.php");
	require_once("turma.class.php");
	require_once("AlteracoesTurmasUsuario.php");
    require_once("funcionalidades/administracao/verificaPermissoesAdministracao.php");

    $usuario = usuario_sessao();
	
	if (!$usuario){ // Se isso não estiver setado, o usuario não está logado
		die("<a href=\"index.php\">Por favor volte e entre em sua conta.</a>");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<!-- CSS -->
		<link href="tela_inicial_geral.css" rel="stylesheet" type="text/css">
	</head>
	<body>
       <?php 
       if(verificaSeAdministrador($usuario->getId())){
          echo  "<a href = \"funcionalidades/administracao/listaFuncionalidadesAdministracao.php\">
                <img src=\"images/botoes/bt_administracao.png\" style=\"position:absolute; right:400px; top:10px; cursor:pointer;\">
                </a>";
       }
       ?>
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
					$mensagensProfessor = $alteracoesTurmas->gerarMensagensAlteracoesTurmasComPapel(NIVELPROFESSOR);
					foreach($mensagensProfessor as $mensagem){ echo "<script>Mural.adicionarJanela(\"".$mensagem."\")</script>"; }
					$mensagensMonitor = $alteracoesTurmas->gerarMensagensAlteracoesTurmasComPapel(NIVELMONITOR);
					foreach($mensagensMonitor as $mensagem){ echo "<script>Mural.adicionarJanela(\"".$mensagem."\")</script>"; }
					$mensagensAluno = $alteracoesTurmas->gerarMensagensAlteracoesTurmasComPapel(NIVELALUNO);
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
						$idPlaneta = $planeta->__get("idTerrenoPrincipal");
						$turmaPlaneta = $planeta->__get("idTurma");
						$nomePlaneta = $planeta->__get("nome");

						switch($planeta->__get("aparencia")){
							case Planeta::APARENCIA_VERDE:
							case Planeta::APARENCIA_GRAMA:	$tipoPlaneta = "LinkPlaneta.TiposPlanetas.GRAMA";
								break;
							case Planeta::APARENCIA_LAVA:		$tipoPlaneta = "LinkPlaneta.TiposPlanetas.LAVA";
								break;
							case Planeta::APARENCIA_GELO:		$tipoPlaneta = "LinkPlaneta.TiposPlanetas.NEVE";
								break;
							case Planeta::APARENCIA_URBANO:	$tipoPlaneta = "LinkPlaneta.TiposPlanetas.URBANO";
								break;
							default:						$tipoPlaneta = "LinkPlaneta.TiposPlanetas.LAVA";
						}
					
						echo "<script> menuPlanetas.adicionarLinkPlaneta(new LinkPlaneta(".$turmaPlaneta.", ".$tipoPlaneta.", '".$nomePlaneta."')); </script>";
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
