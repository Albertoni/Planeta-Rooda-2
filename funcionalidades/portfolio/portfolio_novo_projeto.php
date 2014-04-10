<?php

/*\
 *
 * portfolio_novo_projeto.php
 *
\*/

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");

$user = usuario_sessao();

if($user === false){
	die("Voce nao esta logado em sua conta. Por favor volte e logue.");
}

$id_usuario = $_SESSION['SS_usuario_id'];

if (isset($_GET['turma'])){
	$turma = (int) $_GET['turma'];
}

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
if($perm == false){
	die("Desculpe, mas o Portfolio esta desabilitado para esta turma.");
}

$editar = isset($_GET['projeto_id']);

global $nivelProfessor;
if($user->getNivel($turma) != $nivelProfessor){
	die("Somente professores podem fazer isso, e voc&ecirc; não est&aacute; inserido como professor.");
}


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
<?php
if($_SESSION['user']->podeAcessar($perm['portfolio_adicionarArquivos'], $turma))
{
?>
		<div id="imagem_lbox">
			<h1>INSERIR IMAGEM</h1>
			<ul class="sem_estilo" style="line-height:25px">
				<li>Você não pode enviar uma imagem antes de criar o Projeto. Caso seja necessário, edite o post depois de criá-lo, ou use outro site para dar upload na imagem.</li>
				<li><input type="radio" id="troca_img2" class="select_img" checked name="select_img" value="2" />Imagem da Web</li>
				<li>
					<div id="cont_img">
						<ul id="cont_img2" style="display:block;">
							<li><input type="text" id="imagefromurl" value="http://" /></li>
							<li style="margin-top:-5px">Endereço da imagem</li>
						</ul>
					</div>
				</li>
				<li>
					<div align="right" onclick="addImage()"><img src="../../images/botoes/bt_confir_pq.png" /></div>
				</li>
			</ul>
		</div>
		
<?php
}

if($_SESSION['user']->podeAcessar($perm['portfolio_adicionarLinks'], $turma))
{
?>
		<div id="link_lbox">
			<h1>INSERIR LINK</h1>
			<ul class="sem_estilo">
				<li>Texto a ser exibido: <input id="addlinktext" type="text" /></li>
				<li style="margin-bottom:172px">Link para: <input id="addlinkurl" type="text" value="http://" /></li>
				<li>
					<div align="right"><img src="../../images/botoes/bt_confir_pq.png" alt="Confirmar" onclick="addLink()" /></div>
				</li>
			</ul>
		</div>
<?php
}
?>
	</div>
	
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Projetos", "portfolio_inicio.php", false);
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
					<div id="rel"><p id="balao">Para inserir um novo projeto, basta inserir o título, os objetivos e o(s) autor(es). Outros campos também podem ser inseridos, além de <i>links</i>, arquivos, imagens e vídeos.</p></div>
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
		<?php
if(sizeof($_SESSION['SS_turmas']) > 1){
	selecionaTurmas($turma);
}
?>			<br />
			<form name="fConteudo" id="postFormId" action="formProcessingNovoProjeto.php" onsubmit="return gravaConteudo()" method="post">
			<input type="hidden" name="text" value="" />
			<input type="hidden" name="owner_ids" id="owner_ids" value="" />
			<input type="hidden" name="turma" value="<?=$turma?>" />
			<div class="bts_cima">
			<a href="portfolio.php?turma=<?=$turma?>" align="left" >
				<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
			</a>
			<input type="image" id="responder_topico" src="../../images/botoes/bt_confirm.png" align="right"/>
			</div>
				<div id="info_post" class="bloco">
					<h1>NOVO PROJETO</h1>
					<ul class="sem_estilo">
						<li>Título <span class="exemplo">(Obrigatório)</span></li>
						<li><input name="titulo_projeto" type="text" class="port_info"></li>
						<li>Tags <span class="exemplo">(Escreva as tags separadas por ponto e vírgula. Ex: Matemática; Português; Artes)</span></li>
						<li><input name="tags_projeto" type="text" class="port_info"/></li>
						<li>Data de início <span class="exemplo">(Obrigatório)</span></li>
						<li><input name="data_inicio_projeto" type="text" class="port_info" id="data_inicio"></li>
						<li>Data de Encerramento <span class="exemplo">(Obrigatório)</span></li>
						<li><input name="data_encerramento_projeto" type="text" class="port_info" id="data_encerramento"></li>

				<div id="add_colegas" class="bloco">
					<h1>ESCOLHER COLEGAS</h1>
					<ul class="sem_estilo">
						<ul id="lista_usuarios">
<?php

$consulta = new conexao();
$consulta->solicitar("SELECT usuario_nome, codUsuario FROM $tabela_usuarios INNER JOIN $tabela_turmasUsuario ON codUsuario = usuario_id WHERE codTurma = $turma"); // Pega a lista de pessoas da turma

if(!isset($_GET['editId'])){ // Se não tá setado, não tá editando

	for($i=0; $i<$consulta->registros; $i++)
	{
		$id = $consulta->resultado['codUsuario'];
	?>
								<li class="enviado<?=alterna()?>"><input type="checkbox" name="<?=$id?>" /><?=$consulta->resultado['usuario_nome']?></li>
	<?php
		$consulta->proximo();
	}
}else{
	if (is_numeric($_GET['editId'])){
		$membrosProjeto = new conexao();
		$membrosProjeto->solicitar("SELECT owner_ids FROM PortfolioProjetos WHERE id=".$_GET['editId']);
		$membros = explode(";",$membrosProjeto->resultado['owner_ids']);
		
		for($i=0; $i<$consulta->registros; $i++)
		{
			$id = $consulta->resultado['codUsuario'];
			if (in_array($id, $membros)){
			?>
								<li class="enviado<?=alterna()?>"><input type="checkbox" checked="checked" name="<?=$id?>" /><?=$consulta->resultado['usuario_nome']?></li>
			<?php
			}else{
			?>
								<li class="enviado<?=alterna()?>"><input type="checkbox" name="<?=$id?>" /><?=$consulta->resultado['usuario_nome']?></li>
			<?php
			}
			$consulta->proximo();
		}
	}
}

?>
						</ul>
					</ul>
				</div>
					</ul>
				</div>
			<div class="bts_baixo">
			<a href="portfolio.php?turma=<?=$turma?>" align="left" >
				<img src="../../images/botoes/bt_voltar.png" border="0" align="left"/>
			</a>
			<input type="image" src="../../images/botoes/bt_confirm.png" align="right"/>
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


$.datepicker.regional['pt'] = {
	closeText: 'Fechar',
	prevText: '&#x3c;Anterior',
	nextText: 'Seguinte',
	currentText: 'Hoje',
	monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
	'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
	'Jul','Ago','Set','Out','Nov','Dez'],
	dayNames: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'],
	dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
	dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
	weekHeader: 'Sem',
	dateFormat: 'dd/mm/yy',
	firstDay: 0,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['pt']);
$("#data_inicio").datepicker();
$("#data_encerramento").datepicker();

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