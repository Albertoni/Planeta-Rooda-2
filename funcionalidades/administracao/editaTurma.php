<?php
require_once("../../cfg.php");
require_once("../../bd.php");
$q = new conexao();//Yuri
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("verificaPermissoesAdministracao.php");

$user = usuario_sessao();

validaPermissaoAcesso($user->getId());

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
<form name="fConteudo" id="postFormId" action="salvaEdicaoTurma.php" method="post">
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
					<div id="rel"><p id="balao">Caso queira alterar algum dado de uma turma, primeiro encontre-a na lista abaixo. Após encontra-la, basta alterar o atributo desejado e confirmar as alterações. Os campos não preenchidos não serão editados.</p></div>
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
			<a href="listaFuncionalidadesAdministracao.php" align="left" >
				<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
			</a>
			<input form="postFormId" type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
		</div>
		<div id="esq">
		<div class="bloco">
				<h1>EDITAR TURMA</h1>
				<ul class="sem_estilo">
					<li>Turma <span class="exemplo">(Opcional)</span></li>
					<li><input form="postFormId" name="novoNomeTurma" type="text"></li>
					<li>Descrição <span class="exemplo">(Opcional)</span></li>
					<li><input form="postFormId" name="novaDescricao" type="text"></li>
				</ul>

				<div class="bloco">
					<h1>Editar a aparencia do Planeta</h1>
					<ul id="seletorPlaneta" class="sem_estilo">
						<li>
							<input form="postFormId" name="novaAparencia" type="radio" value="1"><img src="../../images/tela_inicial/planetagrama.png">
							<input form="postFormId" name="novaAparencia" type="radio" value="2" style="margin-left:150px;"><img src="../../images/tela_inicial/planetagelo.png">
						</li>
						<li>
							<input form="postFormId" name="novaAparencia" type="radio" value="3"><img src="../../images/tela_inicial/planetalava.png">
							<input form="postFormId" name="novaAparencia" type="radio" value="4" style="margin-left:150px"><img src="../../images/tela_inicial/planetaurbano.png">
						</li>
					</ul>
				</div>


		</div>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
		<input name="turmaLista" type="hidden" value="<?=$_GET['turma']?>">
	</form>
</body>

<script type="text/javascript">
function ajusta_img() { 
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		$('#cont_img3').css('width','436px');
		$('#cont_img3').css('padding-right','20px');
		$('#cont_img').css('height','170px');
	}
}
</script>

</html>
