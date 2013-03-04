<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("../../reguaNavegacao.class.php");

session_start();

$id = $_GET['id'];

$usuario = $_SESSION['user'];
$usuario->openUsuario($_SESSION['SS_usuario_id']);

if (isset($_GET['turma']) and $_GET['turma'] != ""){
	$turma = $_GET['turma'];
}else{
	$turma = $_SESSION['SS_turmas'][0];
}

$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

$q = new conexao(); // questionario
$q->solicitar("SELECT * FROM $tabela_PerguntaQuestionarios WHERE id = $id");

if ($q->resultado['liberarGabarito'] == 0){
	if(!$usuario->podeAcessar($permissoes['pergunta_editarQuestionario'], $turma)){
		die("Voce precisa ter permissoes de professor para acessar esta pagina.");
	}
}else{
	$datalibera = explode("-", $consulta->resultado['datainicio']); // Separa pra array
	$dataatual  = explode("-", date("y-m-d"));
	
	if ($dataatual[0] <= $datalibera[0]) { // se o ano for maior passa.
		if ($dataatual[0] = $datalibera[0]) { // mesmo ano
			if ($dataatual[1] <= $datalibera[1]) { // se o mês for maior, passa
				if ($dataatual[1] = $datalibera[1]){
					if ($dataatual[2] < $datalibera[2]){ // Se o dia for menor, trava. igual, passa.
						die("O gabarito ainda n&atilde;o foi liberado. Por favor volte.");
					}
				} else {
					die("O gabarito ainda n&atilde;o foi liberado. Por favor volte.");
				}
			}
		} else{
			die("O gabarito ainda n&atilde;o foi liberado. Por favor volte.");
		}
	}
	
	
}

if ((isset($_GET['user']) and isset($_GET['quest'])) == false){
	die("Voce precisa acessar essa pagina pela pagina principal do Planeta Pergunta, por favor volte.");
}

$p = new conexao(); // perguntas
$r = new conexao(); // respostas
$p->solicitar("SELECT * FROM $tabela_PerguntaPerguntas WHERE id_questionario = $id ORDER BY id ASC");
$r->solicitar("SELECT resposta FROM $tabela_PerguntaRespostas WHERE usuario = $uid AND questionario = $id");

$respostas = explode("¦", $r->resultado['resposta']);

$arraydata = explode("-", $q->resultado['datainicio']); // Separa pra array
$datainicio = $arraydata[2]."/".$arraydata[1]."/".$arraydata[0]; // Monta a data no formato ok

$nomecriador = new conexao();
$nomecriador->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = ".$q->resultado['criador']);

////////////////////////////////////////////////////////////////////////////////////////////////////q

function VFbonitinho($aluno, $correta){
	if ($aluno == 'v' or $aluno == 'V')
		$texto = "VERDADEIRO";
	elseif ($aluno == 'f')
		$texto = "FALSO";
	else
		$texto = "DEIXADO EM BRANCO";
	
	if ($aluno == $correta)
		return "<p class=\"correta\">$texto</p>";
	else
		return "<p class=\"errada\">$texto</p>";
}


////////////////////////////////////////////////////////////////////////////////////////////////////q

function resposta($modo, $i){ // Boa sorte a quem tiver que manter essa função.

global $p;
global $respostas;

switch ($modo){
	case 1: // MURTIPRA ESCOIA
	/* Explanação rápida:
	Sempre vai ter UMA opção. Portanto, o while é da segunda em diante e começa no 1.
	Não, não precisa ter duas opções. */

$lista_respostas_questao = explode("¦", $p->resultado['respostas']); // Faz um array das respostas
$numops = count($lista_respostas_questao);
?>

<div id="criar_topico" class="bloco">
	<h1>PERGUNTA <?=$i?></h1> 
	<ul class="sem_estilo">
		<li class="tabela">
			<b><?=$p->resultado['questao']?></b>
			<?php if ($p->resultado['id_imagem'] != 0) echo "<center><img src='imageOutput.php?id=".$p->resultado['id_imagem']."'/></center>" ?>
			<p class="pedreiragem">&nbsp;</p>
			<b>Opção marcada pelo Aluno:</b>
			<div id="opcao" style="margin:10px">
				<?=isset($lista_respostas_questao[$respostas[$i-1]-1]) ? $lista_respostas_questao[$respostas[$i-1]-1] : "Questão em branco." // chega a dar dó, meu?>
			</div>
		</li>
		<li class="espaco_linhas">
			<li class="tabela">
				<b>Opção correta:</b>
				<div id="opcao" style="margin:10px">
					<?=isset($lista_respostas_questao[$p->resultado['correta']-1]) ? $lista_respostas_questao[$p->resultado['correta']-1] : "Deixado em branco."?>
				</div>
			</li>
		</li>
	</ul>
</div>

<?php
		break;
	case 2: // ¬objetiva
?>

<div id="criar_topico" class="bloco">
	<h1>PERGUNTA <?=$i?></h1> 
	<ul class="sem_estilo">
		<li class="tabela">
			<b><?=$p->resultado['questao']?></b>
			<p class="pedreiragem">&nbsp;</p>
			<b>Resposta do aluno:</b>
			<div id="opcao" style="margin:10px">
				<?=isset($respostas[$i-1]) ? $respostas[$i-1] : "Deixado em branco."?>
			</div>
			<b>Resposta:</b>
			<div id="opcao" style="margin:10px">
				<?=$p->resultado['respostas']?>
			</div>
		</li>
	</ul>
</div>

<?php
		break;
	case 3: // fake or not fake
	/* Explanação rápida:
	Sempre vai ter UMA opção. Portanto, o while é da segunda em diante e começa no 1.
	Não, não precisa ter duas opções. */

$lista_respostas_questao = explode("¦", $p->resultado['respostas']); // Faz um array das respostas
$numops = count($lista_respostas_questao);
$aluno_respostas = explode(";", $respostas[$i-1]);
$respostas_corretas = explode("¦", $p->resultado['correta']);
//print_r($respostas_corretas);
?>

	<div class="pergunta">
		<div id="criar_topico" class="bloco">
			<h1>PERGUNTA <?=$i?></h1>
			<ul class="sem_estilo">
				<li class="tabela">
					<b><?=$p->resultado['questao']?></b>
					<?php if ($p->resultado['id_imagem'] != 0) echo "<center><img src='imageOutput.php?id=".$p->resultado['id_imagem']."'/></center>" ?>
				</li>
			</ul>
		</div>
		<div id="criar_topico" class="bloco" style="border:0; background-color:transparent;">
			<table width="100%" cellpadding="0px" cellspacing="0">
				<tr style="background-color:#53686F; height:30px;">
					<td style="width:65px;">
						<h1 style="margin:0px; font-color:#FFFFFF">OPÇÕES</h1>
					</td>
					<td style="width:65px; border-left: 2px solid #EEE8EF">
						<div align="center">
							<h1 style="margin:0; margin-left:-15px">ALUNO</h1>
						</div>
					</td>
					<td style="width:65px; border-left: 2px solid #EEE8EF">
						<div align="center">
							<h1 style="margin:0; margin-left:-15px">CORRETA</h1>
						</div>
					</td>
				</tr>
				<tr style="background-color:#EEE8EF">
					<td> Opção 1: <?=$lista_respostas_questao[0]?>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><?=VFbonitinho($aluno_respostas[0], $respostas_corretas[0])?></div>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><?=$respostas_corretas[0]?></div>
					</td>
				</tr>

<?php
$j=1;
while ($j < $numops) { // ARRAY DE PHP COMEÇA NO ZERO
?>

		<tr style="background-color:#EEE8EF">
					<td>Opção <?=$j+1?>: <?=$lista_respostas_questao[$j]?>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><?=VFbonitinho(isset($aluno_respostas[$j]) ? $aluno_respostas[$j] : "b", isset($respostas_corretas[$j]) ? $respostas_corretas[$j] : "8D")?></div>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><?=isset($respostas_corretas[$j]) ? $respostas_corretas[$j] : "ERROR: HACKERS ESTÃO INVADINDO O SEU SISTEM. MILHARES DELES."?></div>
					</td>
				</tr>
<?php
	$j++;
}
?>

			</table>
		</div>
	</div>

<?php

		break;
}


}
/////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Planeta ROODA 2.0</title>
	<link type="text/css" rel="stylesheet" href="../../planeta.css" />
	<link type="text/css" rel="stylesheet" href="planeta_pergunta.css" />
	<script type="text/javascript" src="../../jquery.js"></script>
	<script type="text/javascript" src="../../planeta.js"></script>
	<script type="text/javascript" src="planeta_pergunta.js"></script>
	<script type="text/javascript" src="pergunta_ajudante.js"></script>
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
			$regua->adicionarNivel("Ver");
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
				<div id="personagem"></div>
				<div id="rel"><p id="balao"><b>Instruções: </b>O Planeta Pergunta é uma funcionalidade que permite a criação de questionários com exercícios que podem variar entre Perguntas, Verdadeiro ou Falso e Múltipla Escolha.</p></div>
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
		<a href="planeta_pergunta.php"><img src="../../images/botoes/bt_voltar.png" align="left" style="margin-top:20px"/></a>
	</div>

	<div id="bloco_mensagens" class="bloco">
		<h1>QUESTIONÁRIOS</h1>
		<div class="cor1">
			<ul>
				<li class="tabela">
					<div class="info">
						<p class="nome"><b><?=$q->resultado['titulo']?></b></p>
						<p class="data"><?=$datainicio?></p>
						<br />
						<div id="autor" class="criado_por">Criado por: <?=$nomecriador->resultado['usuario_nome']?></div>
					</div>
				</li>
				<li>
					<p class="texto_resposta"><?=$q->resultado['descricao']?></p>
				</li>
			</ul>
		</div>
	</div><!-- fim da div topicos -->
	<p class="pedreiragem">&nbsp;</p>
<?php
for($i=0 ; $i < count($p->itens) ;$i++) {
	resposta($p->resultado['tipo'], $i+1); // mostra a questão com sua resposta
	echo "<p class=\"pedreiragem\">&nbsp;</p>\n"; // cospe um separador
	$p->proximo();
}
?>
	<div class="bts_cima">
		<a href="planeta_pergunta.php"><img src="../../images/botoes/bt_voltar.png" align="left" style="margin-top:20px"/></a>
	</div>
	</div>
	<!-- fim do conteudo -->

	</div>
	<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->

</div><!-- fim da geral -->
</body>
</html>
