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


$id = (int)$_GET['id'];
$user = new Usuario();
$user->openUsuario($id);

$data= DateTime::createFromFormat('Y-m-d', $user->getBirthday());
$usuarioDataAniversario = $data->format('d/m/Y');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edição de usuário</title>
	<meta charset="utf-8">

	<link type="text/css" rel="stylesheet" href="../../planeta.css" />

	<script type="text/javascript" src="../../js/compatibility.js"></script>
	<script type="text/javascript" src="../../jquery.js"></script>
	<script type="text/javascript" src="../../planeta.js"></script>
	<script type="text/javascript" src="../lightbox.js"></script>

	<link type="text/css" rel="stylesheet" href="../../calendario/ui-lightness/jquery-ui-1.10.4.custom.min.css" />
	<script type="text/javascript" src="../../calendario/jquery-ui-1.10.4.custom.min.js"></script>

	<!--[if IE 6]>
	<script type="text/javascript" src="planeta_ie6.js"></script>
	<![endif]-->
	<style>
		#usuarioSenha{
			display:none;
		}

		#campoEmail:invalid {
		background-color: #ffdddd;
		}

		#campoEmail:valid {
		background-color: #ddffdd;
		}
	</style>
</head>

<body onload="atualiza('ajusta()');inicia();Init(); checar(); ajusta_img();">
	<div id="descricao"></div>
	
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
					<div id="rel"><p id="balao">Para editar um usuário basta trocar os campos e enviar.</p></div>
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
			<div id="info_post" class="bloco">
			<h1>EDITAR USUÁRIO</h1>
			<form action="salvaEdicaoUsuario.php" method="post">
				<ul class="sem_estilo">
					<li>Nome</li> <li><input required name="usuario_nome" type="text" value="<?=$user->getName()?>"/></li>
					<li>Login</li> <li><input required name="usuario_login" type="text" value="<?=$user->getUser()?>"/></li>
					<li>
					<button type="button" onclick="mostraTrocarSenha()" id="botaoTrocaSenha">Clique aqui para trocar a senha do usuario</button>
					<div id="usuarioSenha" class="sem_estilo">
						Digite uma nova senha: <input name="usuario_senha" type="text"/>
					</div>
					</li>
					<li>Data de nascimento</li> <li><input required name="usuario_data_aniversario" id="dataNascimento" type="text" value="<?=$usuarioDataAniversario?>"/></li>
					<li>Nome da mãe</li> <li><input required name="usuario_nome_mae" type="text" value="<?=$user->getNomeMae()?>" /></li>
					<li>E-mail</li> <li><input required id="campoEmail" name="usuario_email" type="email" value="<?=$user->getEmail()?>"/></li>
					<li><input name="Salvar" type="submit" value="Salvar" /></li>
				</ul>
			</form>
			<div class="bts_baixo">
				<a href="lista_usuarios.php?"><img src="../../images/botoes/bt_voltar.png" align="left"/></a>
			</div>
			</div>
			
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
		</div>

<script>
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
	yearSuffix: '',
	defaultDate:'01/01/2006'
};
$.datepicker.setDefaults($.datepicker.regional['pt']);
$("#dataNascimento").datepicker();

function mostraTrocarSenha()
{
	$("#usuarioSenha").css('display','block');
	$("#botaoTrocaSenha").css('display','none');
}
</script>
</body>