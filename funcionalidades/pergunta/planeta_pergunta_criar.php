<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../reguaNavegacao.class.php");

session_start();


function proximo_ano () { // A SER USADO SOMENTE NOS OPTIONS LÁ EMBAIXO
	$data = getdate(); // Pega a data
	$ano = $data['year']; // Separa só o ano
	$data = NULL; // liberando a ram~
	
	$ano -= 1;
	echo "						<option value=\"$ano\">$ano</option>\n";
	$ano += 1;
	echo "						<option value=\"$ano\" selected=\"\">$ano</option>\n";
	$ano += 1;
	echo "						<option value=\"$ano\">$ano</option>\n";
	$ano += 1;
	echo "						<option value=\"$ano\">$ano</option>\n";
}

if (isset($_GET['turma']) and $_GET['turma'] != ""){
	$turma = $_GET['turma'];
}else{
	$turma = $_SESSION['SS_turmas'][0];
}

$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);
$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
if(!$usuario->podeAcessar($permissoes['pergunta_criarQuestionario'], $turma)){
	die('Voce nao pode criar questionarios nessa turma');
}

?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="../forum/forum.css" />
<link type="text/css" rel="stylesheet" href="planeta_pergunta.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="pergunta_ajudante.js"></script>
<script type="text/javascript" src="planeta_pergunta.js"></script>
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
			$regua->adicionarNivel("Planeta Pergunta", "planeta_pergunta.php", false);
			$regua->adicionarNivel("Criar");
			$regua->imprimir();
		?>
		<p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
	</div>
</div>

<div id="geral">

<!--**************************
			cabecalho
*****************************-->
<div id="cabecalho">
	<div id="ajuda">
		<div id="ajuda_meio">
			<div id="ajudante">
				<div id="personagem"><img src="../../images/desenhos/ajudante.png" height=145 align="left" alt="Ajudante" /></div>
				<div id="rel"><p id="balao">Para criar um questionário, basta inserir o título e a descrição do que você pretende abordar nas questões. Se quiser, é possível inserir uma data limite para que as questões sejam respondidas e o gabarito seja liberado.</p></div>
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
	<div id="conteudo"> <!-- tem que estar dentro da div 'conteudo_meio' -->
	<div class="bts_cima">
		<a href="planeta_pergunta.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
		<img src="../../images/botoes/bt_confirm.png" align="right" style="cursor:pointer" onclick="document.gambiform.submit()"/>
	</div>
<?php
if(sizeof($_SESSION['SS_turmas']) > 1){
	selecionaTurmas($turma);
}
?>
	<form name="gambiform" action="criar_questionario.php" method="post" onsubmit="return valida1()">
	<div id="criar_topico" class="bloco">
		<h1>CRIAR QUESTIONÁRIO</h1>
			<ul class="sem_estilo">
			<div id="adicione_questoes_aqui">
				<li class="tabela">
					Título do Questionário <span class="observacao">(Máximo de 500 caracteres)</span>
					<input type="text" name="titulo" />
				</li>

				<li class="espaco_linhas">
						Descrição <span class="observacao">(Máximo de 5000 caracteres)</span>
						<textarea name="descrição" rows="3"></textarea>
				</li>
				
				
				<li class="espaco_linhas"></li>
				<li><pre class="fonte_pre">Início em:</pre>
					<select name="dia1">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select> /
					<select name="mes1">
						<option value="1">Janeiro</option>
						<option value="2">Fevereiro</option>
						<option value="3">Março</option>
						<option value="4">Abril</option>
						<option value="5">Maio</option>
						<option value="6">Junho</option>
						<option value="7">Julho</option>
						<option value="8">Agosto</option>
						<option value="9">Setembro</option>
						<option value="10">Outubro</option>
						<option value="11">Novembro</option>
						<option value="12">Dezembro</option>
					</select> /
					<select name="ano1">
<?php proximo_ano(); ?>
					</select>
				</li>
				<li class="espaco_linhas"></li>
				
				<li>
					<pre class="fonte_pre"><input type="checkbox" name="liberar" />Liberar Gabarito em:</pre>
					<select name="dia2">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select> /
					<select name="mes2">
						<option value="1">Janeiro</option>
						<option value="2">Fevereiro</option>
						<option value="3">Março</option>
						<option value="4">Abril</option>
						<option value="5">Maio</option>
						<option value="6">Junho</option>
						<option value="7">Julho</option>
						<option value="8">Agosto</option>
						<option value="9">Setembro</option>
						<option value="10">Outubro</option>
						<option value="11">Novembro</option>
						<option value="12">Dezembro</option>
					</select> /
					<select name="ano2">
<?php proximo_ano(); ?>
					</select>
				</li>
				
				<li class="espaco_linhas"></li>
				<pre class="fonte_pre"><input type="checkbox" name="alquest" />Permitir aos alunos inserirem suas próprias questões?</pre>
				<li class="espaco_linhas"></li>
				<pre class="fonte_pre">A primeira questão será: </pre>
				<select name="quest1">
					<option value="1">Múltipla Escolha</option>
					<option value="2">Subjetiva</option>
					<option value="3">Verdadeiro ou Falso</option>
				</select>
			</div> <!--Fim da div das questões-->
<input type="hidden" name="turma" value="<?=$turma?>">
				<li class="espaco_linhas"></li>
				<div style="margin-left:20px; margin-right:20px; background-color:#EEE8EF">
					<div id="maisperguntas" style="float:left">
						<a onclick="addQuestion()"><b>Mais Perguntas</b></a>
					</div>
					<div id="menosperguntas" style="float:right">
						<a onclick="removeQuest()"><b>Remover uma pergunta</b></a>
					</div>
					<p class="pedreiragem">&nbsp;</p>
				</div>
			</ul> <!--FECHANDO O UL CLASS="sem_estilo"-->
			</div><!-- fim da div criar_topicos -->

	<div class="bts_baixo">
		<a href="planeta_pergunta.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" />
	</div>
	</form>
	<div style="clear:both;"></div>
	</div>
	<!-- fim do conteudo -->

</div> <!-- fim do conteudo_meio -->
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->

</div><!-- fim da geral -->

</body>
</html>
