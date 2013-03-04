<?php

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("../../reguaNavegacao.class.php");

session_start();
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}else{
	die("Favor voltar e tentar novamente, a turma em que voce esta nao foi passada corretamente.");
}

if (isset($_GET['id']) == false)
	die ("Voce precisa acessar esta pagina com um id de questionario. Por favor, <a href=\"planeta_pergunta.php\">volte</a> e tente novamente.");

if (is_numeric($_GET['id']) == false)
	die ("Nao sabemos o que aconteceu, mas pelo menos estamos lhe dando uma mensagem de erro amigavel. Por favor <a href=\"planeta_pergunta.php\">clique aqui para voltar</a> e tente novamente.");
else
	$id = $_GET['id'];

$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false and !$usuario->isAdmin()){die("Funcionalidade desabilitada para a sua turma.");}
if(!$usuario->podeAcessar($permissoes['pergunta_editarQuestionario'], $turma)){
	die('Voce nao tem permissoes para editar questionarios nessa turma.');
}

$p = new conexao(); // perguntas
$q = new conexao(); // questionario
$q->solicitar("SELECT * FROM $tabela_PerguntaQuestionarios WHERE id = $id");
$p->solicitar("SELECT * FROM $tabela_PerguntaPerguntas WHERE id_questionario = $id ORDER BY id ASC");

/*	<input type="hidden" name="titulo" value="<?=$titulo?>"/>
	<input type="hidden" name="descricao" value="<?=$descricao?>"/>
	<input type="hidden" name="dia1" value="<?=$dia1?>"/>
	<input type="hidden" name="dia2" value="<?=$dia2?>"/>
	<input type="hidden" name="mes1" value="<?=$mes1?>"/>
	<input type="hidden" name="mes2" value="<?=$mes2?>"/>
	<input type="hidden" name="ano1" value="<?=$ano1?>"/>
	<input type="hidden" name="ano2" value="<?=$ano2?>"/>
	<input type="hidden" name="libera" value="<?=$libera?>"/>*/

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

function put_quest($tipo, $id, $idQuestaoNoBD){

global $p; global $turma;
$lista_respostas = explode("¦", $p->resultado['respostas']); // Faz um array das respostas
$numops = count($lista_respostas);

	switch ($tipo){
		case 1: // multipla ?>
	<div class="pergunta" id="pergunta<?=$id?>">
		<div id="criar_topico" class="bloco">
			<h1>PERGUNTA <?=$id?></h1>
			<ul class="sem_estilo">
				<a class="pode_editar" onclick="deletarQuestao(<?php echo $idQuestaoNoBD.','.$turma.','.$id; ?>)">DELETAR ESSA QUESTÃO</a>
				<li class="tabela" id="mostraArquivo<?=$id?>">
					<textarea rows="3" name="pergmul_<?=$id?>"><?=$p->resultado['questao']?></textarea>
					<input type="hidden" name="tipo_<?=$id?>" value="1">
					<input type="hidden" name="numop_<?=$id?>" value="1">
					<input type="hidden" name="idimg_<?=$id?>" value="0">
					<input type="hidden" name="idvid_<?=$id?>" value="0">
					<input type="hidden" name="idperg_<?=$id?>" value="<?=$p->resultado['id']?>">
					<center style="text-align: center;"><br /><a onclick="insere_imagem(<?=$id?>)">Clique aqui para ALTERAR a imagem</a></center>
					<br /><div style="float:left">Endereço do vídeo: <input id="video_<?=$id?>"></div><div style="float:right"><a onclick="insere_video(<?=$id?>)">Clique aqui para ALTERAR o vídeo</a></div>
				</li>
			</ul>
		</div>
		<div id="criar_topico" class="bloco" style="border:0; background-color:transparent;">
		<table width="100%" cellpadding="0px" cellspacing="0" id="adicione_questoes_aqui<?=$id?>">
			<tr style="background-color:#53686F; height:30px;">
				<td style="width:100px;">
					<h1 style="margin:0">OPÇÕES</h1>
				</td>
				<td></td>
				<td style="border-left: 2px solid c#EEE8EF">
					<h1 style="margin:0; margin-left:-25px"><div align="center">CORRETA</div></h1>
				</td>
			</tr>

			<tr style="background-color:#EEE8EF">
				<td>Opção 1:
					<input type="text" size=60 style="width:350px" name="opmul1_<?=$id?>" value="<?=$lista_respostas[0]?>"/>
				</td>
				<td></td>
				<td style="width:65px; border-left: 2px solid c#EEE8EF">
					<div align="center"><input type="radio" name="radio_<?=$id?>" value="1" <?php if($p->resultado['correta'] == "1") {echo"checked";} ?>/></div>
				</td>
			</tr>
<?php
$j=1;
while ($j < $numops) { // ARRAY DE PHP COMEÇA NO ZERO
?>

			<tr style="background-color:#EEE8EF">
				<td>Opção <?=$j+1?>:
					<input type="text" size=60 style="width:350px" name="opmul<?=$j+1?>_<?=$id?>" value="<?=$lista_respostas[$j]?>"/>
				</td>
				<td></td>
				<td style="width:65px; border-left: 2px solid c#EEE8EF">
					<div align="center"><input type="radio" name="radio_<?=$id?>" value="<?=$j+1?>" <?php if($p->resultado['correta'] == $j+1) {echo"checked";} ?>/></div>
				</td>
			</tr>

<?php
	$j++;
}
?>

			<tr style="background-color:#E7C7ED" id="bala_de_cocacola">
				<td>
					<a onclick="maisPerguntasME(<?=$id?>, 1)"><b>Mais opções</b></a>
				</td>
				<td></td>
				<td></td>
			</tr>
		</table>
		</div>
	</div>
	<p class="pedreiragem">&nbsp;</p>
	<p class="pedreiragem">&nbsp;</p>
	<p class="pedreiragem">&nbsp;</p>
<?php		break; 
		case 2: // subjetiva ******************************************************************************************************** ?>
	<div class="pergunta" id="pergunta<?=$id?>">
		<div id="criar_topico" class="bloco">
			<h1>PERGUNTA <?=$id?></h1>
			<ul class="sem_estilo">
				<a class="pode_editar" onclick="deletarQuestao(<?php echo $idQuestaoNoBD.','.$turma; ?>)">DELETAR ESSA QUESTÃO</a>
				<li class="tabela" id="mostraArquivo<?=$id?>">
					<textarea rows="3" name="pergsubj_<?=$id?>"><?=$p->resultado['questao']?></textarea>
					<input type="hidden" name="tipo_<?=$id?>" value="2">
					<input type="hidden" name="idperg_<?=$id?>" value="<?=$p->resultado['id']?>">
					<input type="hidden" name="idimg_<?=$id?>" value="0">
					<input type="hidden" name="idvid_<?=$id?>" value="0">
					<center style="text-align: center;"><br /><a onclick="insere_imagem(<?=$id?>)">Clique aqui para ALTERAR a imagem</a></center>
					<br /><div style="float:left">Endereço do vídeo: <input name="video_inputter" id="video_<?=$id?>"></div><div style="float:right"><a onclick="insere_video(<?=$id?>)">Clique aqui para ALTERAR o vídeo</a></div>
				</li>
			</ul>
		</div>
		<div id="criar_topico" class="bloco">
			<h1>RESPOSTA</h1>
			<ul class="sem_estilo">
				<li class="tabela">
					<textarea rows="2" name="respsubj_<?=$id?>"><?=$p->resultado['respostas']?></textarea>
				</li>
			</ul>
		</div>
	</div>
	<p class="pedreiragem">&nbsp;</p>
	<p class="pedreiragem">&nbsp;</p>
	<p class="pedreiragem">&nbsp;</p>
<?php		break;
		case 3: // V v F ********************************************************************************************************
			$respostas = explode("¦", $p->resultado['correta']);
?>
	<div class="pergunta" id="pergunta<?=$id?>">
		<div id="criar_topico" class="bloco">
			<h1>PERGUNTA <?=$id?></h1>
			<ul class="sem_estilo">
				<a class="pode_editar" onclick="deletarQuestao(<?php echo $idQuestaoNoBD.','.$turma; ?>)">DELETAR ESSA QUESTÃO</a>
				<li class="tabela" id="mostraArquivo<?=$id?>">
					<textarea rows="3" name="pergvf_<?=$id?>"><?=$p->resultado['questao']?></textarea>
					<input type="hidden" name="tipo_<?=$id?>" value="3">
					<input type="hidden" name="numop_<?=$id?>" value="1">
					<input type="hidden" name="idimg_<?=$id?>" value="0">
					<input type="hidden" name="idvid_<?=$id?>" value="0">
					<input type="hidden" name="idperg_<?=$id?>" value="<?=$p->resultado['id']?>">
					<center style="text-align: center;"><br /><a onclick="insere_imagem(<?=$id?>)">Clique aqui para ALTERAR a imagem</a></center>
					<br /><div style="float:left">Endereço do vídeo: <input id="video_<?=$id?>"></div><div style="float:right"><a onclick="insere_video(<?=$id?>)">Clique aqui para ALTERAR o vídeo</a></div>
				</li>
			</ul>
		</div>
		<div id="criar_topico" class="bloco" style="border:0; background-color:transparent;">
			<table width="100%" cellpadding="0px" cellspacing="0" id="adicione_questoes_aqui<?=$id?>">
				<tr style="background-color:#53686F; height:30px;">
					<td style="width:65px;">
						<h1 style="margin:0px; font-color:#FFFFFF">OPÇÕES</h1>
					</td>
					<td style="width:65px; border-left: 2px solid #EEE8EF">
						<div align="center">
							<h1 style="margin:0; margin-left:-15px">V</h1>
						</div>
					</td>
					<td style="width:65px; border-left: 2px solid #EEE8EF">
						<div align="center">
							<h1 style="margin:0; margin-left:-15px">F</h1>
						</div>
					</td>
				</tr>
				<!--tr style="background-color:#EEE8EF">
					<td> Opção 1:
						<input type="text" size=60 style="width:350px" name="opvf1_<?=$id?>" value="<?=$lista_respostas[0]?>"/>
					</td>
					<td style="width:65px; border-left: 2px solid white">
						<div align="center"><input type="radio" name="radio1_<?=$id?>" value="v"/></div>
					</td>
					<td style="width:65px; border-left: 2px solid white">
						<div align="center"><input type="radio" name="radio1_<?=$id?>" value="f"/></div>
					</td>
				</tr-->
<?php
$j=0;
while ($j < $numops) { // ARRAY DE PHP COMEÇA NO ZERO
?>
				<tr style="background-color:#EEE8EF">
					<td>Opção <?=$j+1?>:
						<input type="text" size=60 style="width:350px" name="opvf1_<?=$id?>" value="<?=$lista_respostas[$j]?>"/>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><input type="radio" name="radio<?=$j+1?>_<?=$i?>" value="v"<?php if($respostas[$j] == "v") echo " checked";?>/></div>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><input type="radio" name="radio<?=$j+1?>_<?=$i?>" value="f"<?php if($respostas[$j] == "f") echo " checked";?>/></div>
					</td>
				</tr>
<?php
	$j++;
}
?>
				<tr style="background-color:#E7C7ED" id="bala_de_canela">
					<td>
						<a onclick="maisPerguntasVF(<?=$id?>, 1)"><b>Mais opções</b></a>
					</td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>
	<p class="pedreiragem">&nbsp;</p>
	<p class="pedreiragem">&nbsp;</p>
	<p class="pedreiragem">&nbsp;</p>
<?php		break;
	}
	// HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI
	
	// HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI
	
	// HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI
	
	// HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI // HTML COMEÇA AQUI 
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
			$regua->adicionarNivel("Editar");
			$regua->imprimir();
		?>
		<p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
	</div>
</div>

<!--**************************
		inserção de imagens
*****************************-->

<div id="gambiajax" style="display:none">
	<iframe src="" name="framegambi"></iframe>
	<form method="post" enctype="multipart/form-data" target="framegambi" action="bota_imagem.php">
		<input type="submit" value="Enviar Imagem" id="submitgambi" />
		<input type="file" name="gambiarquivo" id="gambiselector" onchange="confirmaEnvio()" />
		<input type="hidden" name="gambiid" id="gambiid" />
	</form>
</div>

<div id="geral">

<!--**************************
			cabecalho
*****************************-->
<div id="cabecalho">
	<div id="ajuda">
		<div id="ajuda_meio">
			<div id="ajudante">
				<div id="personagem"></div>
				<div id="rel"><p id="balao">Para editar um questionário, basta inserir o título e a descrição do que você pretende abordar nas questões. Se quiser, é possível trocar a data limite para que as questões sejam respondidas e o gabarito seja liberado.</p></div>
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
	<form action="editar_questionario.php" method="post">
	<div class="bts_cima">
		<a href="planeta_pergunta.php"><input type="image" src="../../images/botoes/bt_cancelar.png" align="left"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" />
	</div>
<?php
	if($usuario->podeAcessar($permissoes['pergunta_editarQuestionario'], $turma)){
?>
	<div id="criar_topico" class="bloco">
		<h1>EDITAR QUESTIONÁRIO</h1>
			<ul class="sem_estilo">
				<li class="tabela">
					Título do Questionário <span class="observacao">(Máximo de 500 caracteres)</span>
					<input type="text" name="titulo" value="<?=$q->resultado['titulo']?>" />
				</li>
				<li class="espaco_linhas">
					Descrição <span class="observacao">(Máximo de 5000 caracteres)</span>
					<textarea name="descrição" rows="3"><?=$q->resultado['descricao']?></textarea>
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
					<pre class="fonte_pre"><input type="checkbox" name="liberar" <?php if ($q->resultado['liberarGabarito']) echo "checked=\"1\"" ?> />Liberar Gabarito em:</pre>
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
				<pre class="fonte_pre"><input type="checkbox" name="alquest" />Permitir aos alunos inserirem suas próprias questões?</pre>
			</ul> <!--FECHANDO O UL CLASS="sem_estilo"-->
			</div><!-- fim da div criar_topicos -->
			<p class="pedreiragem">&nbsp;</p>
			<p class="pedreiragem">&nbsp;</p>
			<p class="pedreiragem">&nbsp;</p>
<?php
	} else {
		echo 'Desculpe, você não possui permissão para editar este questionário.';
	}

if($usuario->podeAcessar($permissoes['pergunta_editarQuestionario'], $turma) and $usuario->podeAcessar($permissoes['pergunta_editarPergunta'], $turma)){
	for($i=0 ; $i < count($p->itens) ;$i++) {
		put_quest($p->resultado['tipo'], $i+1, $p->resultado['id']); // mostra a questão
		echo "<p class=\"pedreiragem\">&nbsp;</p>\n"; // cospe um separador
		echo "<input type=\"hidden\" name=\"tipo".($i+1)."\" value=\"".$p->resultado['tipo']."\">\n"; // não sei o que faz, mas é gambiarra
		$p->proximo();
	}
} else {
	echo 'Desculpe, você não possui permissão para editar as questões de questionários desta turma.';
}
?>

	<div class="bts_baixo">
		<a href="planeta_pergunta.php"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" />
	</div>
	<!--hic sunt gambiarres-->
	<input type="hidden" name="editar" value="1"/>
	<input type="hidden" name="id" value="<?=$id?>"/>
	</form>
	</div>
	<!-- fim do conteudo -->

</div> <!-- fim do conteudo_meio -->
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>
