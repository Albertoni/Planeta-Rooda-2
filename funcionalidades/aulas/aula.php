<?php
require_once("aula.class.php");
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");

$usuario = usuario_sessao();
if ($usuario === false){die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");}

global $tabela_Aulas;

$id = isset($_GET['id']) ? $_GET['id'] : die ("Por favor acesse essa pagina com uma id de aula setada.");

$aula = new aula();
$aula->abreAula($id);

$q = new conexao();

$dono = new Usuario();
$dono->openUsuario($aula->getAutor());

$idfundo = $aula->getFundo() == "1" ? "" : $aula->getFundo(); // pra diferenciar entre fundo.png e fundo#.png
switch($idfundo){
	case "":
		$corfundo="AACCCA";
		break;
	case 2:
		$corfundo="C0D7B6";
		break;
	case 3:
		$corfundo="B5D5E6";
		break;
	case 5:
		$corfundo="E4D5D7";
		break;
	case 6:
		$corfundo="EEBB85";
		break;
	case 7:
		$corfundo="A9ABC8";
		break;
	default:
		$corfundo="000";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="aulas.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="aulas.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<style type="text/css">
	body{background-color:#<?=$corfundo?>;
		background-image:url("../../images/fundos/fundo<?=$idfundo?>.png");}
</style>
<script language="javascript">

function coment(){
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		document.getElementById('ie_coments').style.width = 85 + '%';
		$('.bloqueia ul').css('margin-right','17px');
	}
};
</script>

<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');Init();inicia();coment();">

	<div id="descricao"></div>

<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
	</div>

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
				<div id="rel"><p id="balao">São pequenos textos escritos pelo autor do blog que podem conter imagens, vídeos, arquivos anexados e <i>links</i>. Podem ser comentados por outras pessoas, desde que estas façam login.</p></div>
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
			<a href="ver_aulas.php?turma=<?=$aula->getTurma()?>"><img src="../../images/botoes/bt_voltar.png" align="left"/></a>
		</div>
		<div id="esq" class="margem_paginas">
			<div class="bloco" id="identsingle">
				<a style="text-decoration:uppercase" href="ver_aulas.php?turma=<?=$aula->getTurma()?>"><h1><?=$aula->getTitulo()?></h1></a>
				<div class="cor1">
				<ul class="sem_estilo">
					<li class="tabela_blog">
						<span class="titulo">
							<?=$aula->getTitulo()?>
						</span>
						<span class="data">
							<?=$aula->getData()?>
						</span>
					</li>
					<li class="tabela_blog">
							<?=$aula->getFriendlyAndPrintableClassText()?>
					</li>
					<li class="tabela_blog">
						Por <?=$dono->getName()?><br />
					</li>
				</ul>
				</div>
			</div>
			<div class="bloco" style="margin-top:20px">
				<h1>Aulas da turma <?=$aula->getNomeTurma()?></h1>
<?php
$aulas = getListaAulas($aula->getTurma());
foreach($aulas as $a){
?>
				<div class="cor<?=alterna()?>">
					<ul class="pad_aulas">
						<a href="aula.php?id=<?=$a->getId()?>"><?=$a->getTitulo()?></a>
						<p class="data"><?=$a->getData()?></p><br />
						<a href="aula.php?id=<?=$a->getId()?>" class="no_underline"><?=$a->getDesc()?></a>
					</ul>
				</div>
<?php
}
?>
			</div>
		</div>
	</div><!-- Fecha Div conteudo -->
	</div><!-- Fecha Div conteudo_meio -->
	<div id="conteudo_base">
	</div><!-- para a imagem de fundo da base -->
</div><!-- fim da geral -->

</body>
</html>
