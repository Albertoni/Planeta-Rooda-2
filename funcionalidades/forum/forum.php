<?php
error_reporting(E_ALL);

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

$user = usuario_sessao();
if (!$user) die ("Voc&ecirc; n&atilde;o est&aacute; logado");
$turma = isset($_GET['turma']) ? $_GET['turma'] : 0;

//require_once("verifica_user.php");
require_once("sistema_forum.php");
//require_once("visualizacao_forum.php");
require_once("../../reguaNavegacao.class.php");

$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

$forum = new visualizacaoForum($turma);
$forum->carregaTopicos();

//$paginas = array();
//$paginas = $FORUM->paginas($pagina,10);

$permissoes = checa_permissoes(TIPOFORUM, $turma);
if($permissoes === false){
	die("Funcionalidade desabilitada para a sua turma. Favor voltar.");
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="forum.css" />
<script src="../../jquery.js"></script>
<script src="../../planeta.js"></script>
<script src="../lightbox.js"></script>
<script>
	var deltipo = 0;
</script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">

<div id="topo">
	<div id="centraliza_topo">
		<?php 
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("Fórum");
			$regua->imprimir();
		?>
		<p id="bt_ajuda" onmousedown="animacao('abrirAjuda()');">OCULTAR AJUDANTE</p>
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
				<div id="rel"><p id="balao">Espaço destinado à discussão de diferentes
				temáticas com usuários contribuindo em tempos e espaços diferentes.</p></div>
			</div>
		</div>
		<div id="ajuda_base"></div>
	</div>
</div><!-- fim do cabecalho -->
	
<input type="hidden" id="idTurma" value="<?php echo $turma?>" />
<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->
	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->
	
	<div class="bts_cima">
<?php
if ($user->podeAcessar($permissoes['forum_criarTopico'], $turma)){
	echo "<a href=\"forum_cria_topico.php?turma=$turma\"><img src=\"../../images/botoes/bt_criar_topico.png\"></a>\n";
}
?>
	<a class="botao_procurar" href="forum_procurar.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_procurar_topico.png"></a>
	</div>
	
	<div id="dinamica">
<?php
		$forum->imprimeNumTopicos();

		$forum->imprimeTopicos($user, $permissoes);
?>
	
	</div>
	
	<div class="bts_baixo">
<?php
if ($user->podeAcessar($permissoes['forum_criarTopico'], $turma)){
	echo "<a href=\"forum_cria_topico.php?turma=$turma\"><img src=\"../../images/botoes/bt_criar_topico.png\"></a>\n";
}
?>
	</div>
	
	</div>

	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->
<script type="text/javascript" src="forum.js"></script>
</body>
</html>
