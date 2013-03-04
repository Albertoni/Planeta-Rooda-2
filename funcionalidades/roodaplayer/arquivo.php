<?php
session_start();
header('Content-type: text/html; charset=utf-8');

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("../../login.class.php");
require("../../reguaNavegacao.class.php");

if(isset($_GET["codTurma"]) and is_numeric($_GET['codTurma']))
{
	$codTurma = $_GET["codTurma"];
	$codUsuario = $_SESSION['SS_usuario_id'];
	$erro = "";
}
else{
	$erro = "<span class=\"destaque\">Dando erro! Favor voltar e tentar novamente!</span>";
}
$permissoes = checa_permissoes(TIPOPLAYER, $codTurma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

$user = new Usuario();
$user->openUsuario($codUsuario);
if (!$user->podeAcessar($permissoes['player_inserirVideos'], $codTurma)){
	die("Ops, voc&ecirc; n&atilde;o tem permiss&atilde;o para inserir um video.");
}
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
<script type="text/javascript" src="player.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
</head>
<body onload="atualiza('ajusta()');inicia();">

<div id="descricao"></div>

<div id="fundo_lbox"></div>
<div id="light_box" class="bloco"></div>

<div id="topo">
<div id="centraliza_topo">
		<?php 
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("ROODAPlayer", "index.php", false);
			$regua->adicionarNivel("Arquivo");
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
				<div id="rel"><p id="balao">Aqui, você pode encontra um espaço que foi mencionado no vidadeprogramador.com.br</p></div>
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
		<a href="index.php?turma=<?=$codTurma?>"><img src="../../images/botoes/bt_voltar.png" border="0" align="left"/></a>
	</div>
	&nbsp;<!--FAVOR NÃO REMOVER ESSA PEDREIRAGEM-->
	<form action="enviar.php" method="post">
		<div id="bloco_mensagens" class="bloco">
		<h1>ROODA PLAYER</h1>
<?=$erro?>
		<div class="bg0">Cadastro de vídeo</div>
		<table>
			<tr>
				<td class="bg1">Nome do video</td>
				<td><input type="text" name="nome" size="45" maxlenght="40"/></td>
			</tr>
			<tr>
				<td class="bg1">Link</td>
				<td><input type="text" name="link" size="45" maxlenght="42"/></td>
			</tr>
			<tr>
				<td class="bg1">Descrição</td>
				<td><textarea name="descricao" maxlenght="256" style="width:453px; height:100px"></textarea></td>
			</tr>
		</table>
		<input type="hidden" name="codTurma" value="<?=$codTurma?>"/>
		<input type="button" onclick="history.go(-1);" value="Cancelar"/>
		<input type="submit" value="Enviar"/>
		<table WIDTH="95%" style="margin:10px">
			<tr>
				<th align="center" class="bg1">*O link deve seguir um dos seguintes formatos:</th>
			</tr>
			<tr>
				<td class="bg1">http://www.youtube.com/v/id_do_video</td>
			</tr>
			<tr>
				<td class="bg1">http://youtu.be/id_do_video</td>
			<tr>
				<td class="bg1">http://www.youtube.com/watch?v=id_do_video</td>
			</tr>
		</table>
	</div><!-- fim da div topicos -->
	<div class="bts_cima">
	</div>
	</form>
	</div>
	<!-- fim do conteudo -->
</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->
</div><!-- fim da geral -->
</body>
</html>
