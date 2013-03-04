<?php


require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("aula.class.php");
require("../../reguaNavegacao.class.php");

session_start();
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

$turma = "";
if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}

$permissoes = checa_permissoes(TIPOAULA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}


if(!$usuario->podeAcessar($permissoes['aulas_importarAulas'], $turma)){
	$host	=	$_SERVER['HTTP_HOST'];
	$uri	=	rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: http://$host$uri/planeta_aulas.php");
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
	<script type="text/javascript" src="aulas.js"></script>
	<script type="text/javascript" src="../lightbox.js"></script>
	<!--[if IE 6]>
	<script type="text/javascript" src="../../planeta_ie6.js"></script>
	<![endif]-->
</head>

<body onload="atualiza('ajusta()');inicia();">
<div id="topo">
	<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Aulas", "planeta_aulas.php?turma=$turma", false);
				$regua->adicionarNivel("Importar");
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
				<div id="rel"><p id="balao">Nesse espaço, é possível importar aulas de uma turma para outra. Basta selecionar a turma de que deseja importar as aulas e a nova turma em que serão utilizadas as aulas criadas anteriormente.</p></div>
			</div>
		</div>
		<div id="ajuda_base"></div>
	</div>
</div><!-- fim do cabecalho -->
<a name="topo"></a>
<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->
	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->
	
	<div class="bts_cima">
	<div class="bts_msg" align="left">
		<a href="planeta_aulas.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_voltar.png" /></a>
	</div>
	</div>
<?php
if(sizeof($_SESSION['SS_turmas']) > 1){
	selecionaTurmas($turma, "SELECIONAR AULAS DA TURMA");
}
?>
	<form name="importar" method="post" action="_importar.php">
	<div id="criar_topico" class="bloco">
		<h1>AULAS</h1>
<?php
$aulas = getListaAulas($turma);
$i =0;
foreach($aulas as $aula){
	$nomecriador = new conexao();
	$nomecriador->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = ".$aula->getAutor());
?>
		<div class="cor<?=alterna()?>">
			<ul class="margem_raquel">
				<li class="tabela">
					<div class="info">
						<a href="aula.php?turma=<?=$turma?>&id=<?=$aula->getId()?>"><p class="nome"><b><?=$aula->getTitulo()?></b></p></a>
						<p class="data">Data: <?=$aula->getData()?></p>
					</div>
				</li>
				<li>
					<a class="no_underline" href="aula.php?turma=<?=$turma?>&id=<?=$aula->getId()?>"><p class="texto_resposta"><?=$aula->getDesc()?></p></a>
					<div id="autor" class="criado_por">Criado por: <?=$nomecriador->resultado['usuario_nome']?></div>
					Importar essa aula? <input type="checkbox" name="<?=$i?>" value="<?=$aula->getId()?>">
				</li>
			</ul>
		</div>
<?php
	$i++;
}
?>
	<div id="bloco_mensagens" class="bloco">
		<h1>IMPORTAR AULAS PARA A TURMA</h1>
		<div class="cor1">
			<select style="vertical-align:middle" name="t">";
<?cospeSelectDeTurmas();?>

			</select>
			<img style="vertical-align:middle; height:25px; padding-left:100px; cursor:pointer" src="../../images/botoes/bt_confirmar.png" onclick="importar.submit()"/>
		</div>
	</div>
	</div><!-- fim da div topicos -->
	</form>
	</div>
	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->

</div><!-- fim da geral -->

</body>
</html>
