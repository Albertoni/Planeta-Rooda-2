<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

$user = usuario_sessao();

/*if($user === false){
	die("Voce nao esta logado em sua conta. Por favor volte e logue.");
}*/

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Planeta ROODA 2.0</title>

	<link type="text/css" rel="stylesheet" href="../../planeta.css" />

	<script type="text/javascript" src="../../js/compatibility.js"></script>
	<script type="text/javascript" src="../../jquery.js"></script>
	<script type="text/javascript" src="../../planeta.js"></script>
	<script type="text/javascript" src="../lightbox.js"></script>

	<!--[if IE 6]>
	<script type="text/javascript" src="planeta_ie6.js"></script>
	<![endif]-->
	<style type="text/css">
	tr{
		background-color: #EEF5F5;
	}
	tr:nth-child(odd){
		background-color: #CCECF4;
	}
	table{
		width: 100%;
	}

	#containerPesquisa{
		margin-bottom: 20px;
		border: 1px solid gray;
		padding:3px;
	}
	</style>
</head>

<body onload="atualiza('ajusta()');inicia();ajusta_img();">
	<div id="descricao"></div>
	<div id="topo">
		<div id="centraliza_topo">
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
					<div id="rel"><p id="balao">Para criar uma nova turma, basta escrever o nome da turma, dar uma descrição a ela e selecionar os participantes.</p></div>
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
		<div class="bts_cima" style="float:none">
			<a href="listaFuncionalidadesAdministracao.php" align="left" > <!-- o link está errado porque não se sabe para onder retornaremos ainda-->
				<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
			</a>
			<input form="postFormId" type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
		</div>
		<div id="esq">
		<div class="bloco">
			
				<h1>NOVA TURMA</h1>
				<ul class="sem_estilo">
					<li>Turma <span class="exemplo">(Obrigatório)</span></li>
					<li><input form="postFormId" name="turma" type="text"></li>
					<li>Descrição <span class="exemplo">(Obrigatório)</span></li>
					<li><input form="postFormId" name="descricao" type="text"></li>
				</ul>

				<div class="bloco">
					<h1>Aparencia do Planeta</h1>
					<ul id="seletorPlaneta" class="sem_estilo">
						<li>
							<input form="postFormId" name="tipoTerreno" type="radio" value="1"><img src="../../images/tela_inicial/planetagrama.png">
							<input form="postFormId" name="tipoTerreno" type="radio" value="2" style="margin-left:150px;"><img src="../../images/tela_inicial/planetagelo.png">
						</li>
						<li>
							<input form="postFormId" name="tipoTerreno" type="radio" value="3"><img src="../../images/tela_inicial/planetalava.png">
							<input form="postFormId" name="tipoTerreno" type="radio" value="4" style="margin-left:150px"><img src="../../images/tela_inicial/planetaurbano.png">
						</li>
					</ul>
				</div>
					<form name="fConteudo" id="postFormId" action="salvaTurma.php" method="post">
			</div>

		</div>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
    <input name="idProfResponsavel" type="hidden" value="<?=$user->getId();?>">
</body>

</html>
