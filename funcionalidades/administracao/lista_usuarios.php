<?php

/*\
 *
 * nova_turma.php
 *
\*/

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");

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
	<link type="text/css" rel="stylesheet" href="portfolio.css" />

	<script type="text/javascript" src="../../js/compatibility.js"></script>
	<script type="text/javascript" src="../../jquery.js"></script>
	<script type="text/javascript" src="../../planeta.js"></script>
	<script type="text/javascript" src="portfolio.js"></script>
	<script type="text/javascript" src="../../postagem_wysiwyg.js"></script>
	<script type="text/javascript" src="../lightbox.js"></script>

	<link type="text/css" rel="stylesheet" href="ui-lightness/jquery-ui-1.10.4.custom.min.css" />
	<script type="text/javascript" src="jquery-ui-1.10.4.custom.min.js"></script>

	<!--[if IE 6]>
	<script type="text/javascript" src="planeta_ie6.js"></script>
	<![endif]-->
</head>

<body onload="atualiza('ajusta()');inicia();Init(); checar(); ajusta_img();">
	<div id="descricao"></div>
	<div id="fundo_lbox"></div>
	<div id="light_box" class="bloco">
		<img src="../../images/botoes/bt_fechar.png" class="fechar_coments" onmousedown="abreFechaLB()" />
	</div>
	
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Criar turma", "portfolio_inicio.php", false);
				$regua->adicionarNivel("Novo Projeto");
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
					<div id="rel"><p id="balao">Clique em um usuário para editá-lo.</p></div>
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
			<form name="fConteudo" id="postFormId" action="salvaTurma.php" onsubmit="return gravaConteudo()" method="post">
			<input type="hidden" name="text" value="" />
			<input type="hidden" name="owner_ids" id="owner_ids" value="" />
			<div class="bts_cima">
				<a href="portfolio.php?turma=<?=$turma?>" align="left" >
					<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
				</a>
				<!-- <input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/> -->
			</div>
<ul class="sem_estilo">			
<?php
$consulta = new conexao();
$consulta->solicitar("SELECT * FROM $tabela_usuarios"); // Pega a lista de usuarios
	for($i=0; $i<$consulta->registros; $i++)
	{
		$id = $consulta->resultado['usuario_id'];
	?>
								<li class="cor<?=alterna()?>"><a href="edita_usuario-Novo.php?id=<?=$consulta->resultado['usuario_id']?>"><?=$consulta->resultado['usuario_nome']?></a></li>
	<?php
		$consulta->proximo();
	}
?>
</ul>
			<div class="bts_baixo">
			<a href="portfolio.php?turma=<?=$turma?>" align="left" >
				<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
			</a>
			<!-- <input type="image" src="../../images/botoes/bt_confirm.png" align="right"/> -->
			</div>
		</form>
		<div style="clear:both;"></div>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->
</body>

<script type="text/javascript">
function ajusta_img() { 
	if (navigator.appVersion.substr(0,3) == "4.0"){ //versao do ie 7
		$('#cont_img3').css('width','436px');
		$('#cont_img3').css('padding-right','20px');
		$('#cont_img').css('height','170px');
	}
} 

var objContent;
var objHolder;

function Init() {
	var ua = navigator.appName; 
	objHolder = document.getElementById('text_post');
	if(ua == "Netscape") {
		objContent = objHolder.contentDocument;
	} else {
		objContent = objHolder.document;
	}
	objContent.designMode = "On";

	objContent.body.style.fontFamily = 'Verdana';
	objContent.body.style.fontSize = '11px';
}
modo=2; 

// setando o formulário
document.getElementById('postFormId').onsubmit = function(){
	var selected = new Array();
	$('#lista_usuarios input:checked').each(function(){
		selected.push($(this).attr('name'));
	});
	document.getElementById('owner_ids').value = selected.join(';');

	gravaConteudo();

	return true;
};
</script>

</html>
