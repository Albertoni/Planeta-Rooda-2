<?php
header('Content-type: text/html; charset=utf-8');

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");

require_once("player_aux.php"); // IMPORTANTE

$usuario = usuario_sessao();

if (!isset($_SESSION['SS_usuario_nivel_sistema'])) // if not logged in
	die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");

if (sizeof($_SESSION['SS_turmas']) > 1){ // Pertence a mais de uma turma, então se valida os valores se o cara trocar de turma
	$mais_de_uma_turma = true;
}else{
	$mais_de_uma_turma = false;
}

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}

global $tabela_playerComentarios, $nivelAdmin;

$user_id=$_SESSION['SS_usuario_id'];
$pagina=isset($_GET['page'])?$_GET['page']:0;

$permissoes = checa_permissoes(TIPOPLAYER, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="player.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("swfobject", "2.1");
</script>
<script type="text/javascript" src="player.js"></script>
<style>
#player{
	top:<?=$mais_de_uma_turma?"182":"120"?>px;
}
#embede{
	top:<?=$mais_de_uma_turma?"203":"140"?>px;
}
</style>
</head>
<body onload="atualiza('ajusta()');inicia();inicia_player();">

<div id="descricao"></div>

<div id="fundo_lbox"></div>
<div id="light_box" class="bloco"></div>

<div id="topo">
<div id="centraliza_topo">
		<?php 
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("Player");
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
				<div id="rel"><p id="balao">Aqui, você encontra um espaço onde pode visualizar e compartilhar videos.</p></div>
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
			<a <?php
if($usuario->podeAcessar($permissoes['player_inserirVideos'], $turma)){
	echo "href=\"arquivo.php?codTurma=$turma&amp;codUsuario=$user_id\"";
}else{
	echo "style=\"visibility:hidden\""; // O botão TEM que ocupar o espaço, por causa das gambiarras com posicionamento relativo/absoluto do player
}
?>><img src="../../images/botoes/bt_criar_video.png" border="0" align="right"/></a>
		</div>
		&nbsp;<!--FAVOR NÃO REMOVER ESSA PEDREIRAGEM-->
<?php
if(sizeof($_SESSION['SS_turmas']) > 1){
	selecionaTurmas($turma);
}
?>
		&nbsp;<!--FAVOR NÃO REMOVER ESSA PEDREIRAGEM-->
		<div id="bloco_gambiarras" class="bloco">
		<h1 id="subindo" name="subindo">ROODA PLAYER</h1>
		
		<!-- CONTEÚDO INÍCIO -->
		<div id="gambiarrada">
			<div id="player">
				<div id="embede">
					<div id="videoDiv">Carregando, favor esperar...</div>
				</div>
				<div id="controles">
					<a href="javascript:void(0);" onclick="playVideo();"><div id="play"></div></a>
					<a href="javascript:void(0);" onclick="pauseVideo();"><div id="pause"></div></a>
					<div id="barra"><div style="position:absolute">
						<div id="barra_carrega" style="width:286px; background-color:red;"></div>
						<div id="barra_andamento" style="width:100px; background-color:green;"></div>
						<div id="barra_branco" style="width:35px; background-color:white;"></div>
						<div id="bolinha"></div>
					</div></div>
					<a href="javascript:void(0);" onclick="setSom(ROODAplayer.mudo)"><div id="mute"></div></a>
					<a href="javascript:void(0);" onclick="setVideoVolume(ROODAplayer.volume-12,-1);"><div id="menos"></div></a>
					<div id="niveis">
						<a href="javascript:void(0);" onclick="setVideoVolume(12,0);"><div id = "vol0" class="nivel"></div></a>
						<a href="javascript:void(0);" onclick="setVideoVolume(25,1);"><div id = "vol1" class="nivel"></div></a>
						<a href="javascript:void(0);" onclick="setVideoVolume(37,2);"><div id = "vol2" class="nivel"></div></a>
						<a href="javascript:void(0);" onclick="setVideoVolume(50,3);"><div id = "vol3" class="nivel"></div></a>
						<a href="javascript:void(0);" onclick="setVideoVolume(62,4);"><div id = "vol4" class="nivel"></div></a>
						<a href="javascript:void(0);" onclick="setVideoVolume(75,5);"><div id = "vol5" class="nivel"></div></a>
						<a href="javascript:void(0);" onclick="setVideoVolume(87,6);"><div id = "vol6" class="nivel"></div></a>
						<a href="javascript:void(0);" onclick="setVideoVolume(100,7);"><div id = "vol7" class="nivel"></div></a>
					</div>
					<a href="javascript:void(0);" onclick="setVideoVolume(ROODAplayer.volume+12,-1);"><div id="mais"></div></a>
				</div>
			</div>
			<div id="video">
			<table WIDTH="100%" class="tabela_videos">
				<tr>
					<th colspan="2" class="bg0"><b>Informaçoes do vídeo selecionado</b></th>
				</tr><tr>
					<th class="bg2">Nome:</th>
					<th class="bg1 big" id="nomeVideo">Nenhum video selecionado</th>
				</tr><tr>
					<th class="bg2">Descrição:</th>
					<th class="bg1 big" id="descricaoVideo"></th>
				</tr><tr>
					<th class="bg2">Dono:</th>
					<th class="bg1 big" id="donoVideo"></th>
				</tr><tr>
					<th class="bg2">Comentários:</th>
					<th class="bg1 big" id="comentariosVideo"></th>
				</tr>
			</table>
			<table width="100%" class="tabela_videos">
				<tr>
					<th class="bg0"><b>Meus Vídeos</b></th>
				</tr>
			</table>
			<table width="100%" class="tabela_videos">
				<tr>
<?php paginacao($turma, $pagina); /* player_aux.php */ ?>
				</tr>
			</table>
			<table WIDTH="100%" class="tabela_videos">
				<tr>
					<th class="bg0">Arquivo</th>
					<th class="bg0">Descrição</th>
					<th class="bg0">Enviado por</th>
					<th class="bg0">Deletar</th>
				</tr>
<?php imprimeVideos($turma, $pagina); ?>
			</table>
			</div>
		</div>
		<!-- CONTEÚDO FIM -->
	</div><!-- fim da div topicos -->
	</div>
	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->
<script src="../../js/ajax.js"></script>
<script src="../../js/rooda.js"></script>
<script src="../../comentarios.js"></script>
</body>
</html>
