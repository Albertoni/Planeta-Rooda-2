<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("../../reguaNavegacao.class.php");

if (isset($_GET['id']) == false)
	die ("Voce precisa acessar esta pagina com um id de questionario. Por favor, <a href=\"planeta_pergunta.php\">volte</a> e tente novamente.");


if (is_numeric($_GET['id']) == false)
	die ("Nao sabemos o que aconteceu, mas pelo menos estamos lhe dando uma mensagem de erro amigavel. Por favor <a href=\"planeta_pergunta.php\">clique aqui para voltar</a> e tente novamente.");
else
	$id = $_GET['id'];

// IMPORTANTE: TODO: NÃO PRECISA DE PERMISSÃO PRA RESPONDER UM QUESTIONÁRIO

$turma = (isset($_GET['turma']) and is_numeric($_GET['turma'])) ? $_GET['turma'] : die("Voce precisa de uma id de turma para acessar essa pagina. Por favor avise os desenvolvedores.");

$p = new conexao(); // perguntas
$q = new conexao(); // questionario
$q->solicitar("SELECT * FROM $tabela_PerguntaQuestionarios WHERE id = $id");
$p->solicitar("SELECT * FROM $tabela_PerguntaPerguntas WHERE id_questionario = $id ORDER BY id ASC");

$nomecriador = new conexao();
$nomecriador->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = ".$q->resultado['criador']);

$arraydata = explode("-", $q->resultado['datainicio']); // Separa pra array
$datainicio = $arraydata[2]."/".$arraydata[1]."/".$arraydata[0]; // Monta a data no formato ok

function questao($modo, $i){ // Boa sorte a quem tiver que manter essa função.

global $p;

switch ($modo){
	case 1: // MURTIPRA ESCOIA
	/* Explanação rápida:
	Sempre vai ter UMA opção. Portanto, o while é da segunda em diante e começa no 1.
	Não, não precisa ter duas opções. */

$lista_respostas = explode("¦", $p->resultado['respostas']); // Faz um array das respostas
$numops = count($lista_respostas);
?>
<div id="criar_topico" class="bloco">
	<h1>PERGUNTA <?=$i?></h1> 
	<ul class="sem_estilo" style="margin:0px">
		<li class="tabela">
			<b><?=$p->resultado['questao']?></b>
			<?php
			if ($p->resultado['id_imagem'] != 0) {echo "<center><img src='imageOutput.php?id=".$p->resultado['id_imagem']."'/></center>";}
			if (strlen($p->resultado['id_video']) > 3) {echo "<center><iframe width=\"640\" height=\"385\" frameborder=\"0\" class=\"youtube-player\" src=\"http://www.youtube.com/embed/".$p->resultado['id_video']."\"></iframe></center>";}
			?>
			<p class="pedreiragem">&nbsp;</p>
			<b>Opção 1:</b>
			<div id="opcao" style="margin-top:10px"><?=$lista_respostas[0]?> 
			<input type="radio" name="opmul_<?=$i?>" value="1"/>
			</div>
		</li>

<?php
$j=1;
while ($j < $numops) { // ARRAY DE PHP COMEÇA NO ZERO
?>
		<li class="espaco_linhas">
			<li class="tabela">
				<b>Opção <?=$j+1?>:</b>
				<div id="opcao" style="margin-top:10px"><?=$lista_respostas[$j]?> 
					<input type="radio" name="opmul_<?=$i?>" value="<?=$j+1?>"/>
				</div>
			</li>
		</li>

<?php
	$j++;
}
?>

	</ul>
</div>
<?php
		break;
	case 2: // ¬objetiva
?>
<div id="criar_topico" class="bloco">
	<h1>PERGUNTA <?=$i?></h1> 
	<ul class="sem_estilo" style="margin:0px">
		<li class="tabela">
			<b><?=$p->resultado['questao']?></b>
			<?php
			if ($p->resultado['id_imagem'] != 0) {echo "<center><img src='imageOutput.php?id=".$p->resultado['id_imagem']."'/></center>";}
			if (strlen($p->resultado['id_video']) > 3) {echo "<center><iframe width=\"640\" height=\"385\" frameborder=\"0\" class=\"youtube-player\" src=\"http://www.youtube.com/embed/".$p->resultado['id_video']."\"></iframe></center>";}
			?>
			<p class="pedreiragem">&nbsp;</p>
			<b>Resposta:</b>
			<div id="opcao" style="margin-top:10px">
			<input type="text" name="subj_<?=$i?>"/>
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

$lista_respostas = explode("¦", $p->resultado['respostas']); // Faz um array das respostas
$numops = count($lista_respostas);
?>
	<div class="pergunta">
		<div id="criar_topico" class="bloco">
			<h1>PERGUNTA <?=$i?></h1>
			<ul class="sem_estilo" style="margin:0px">
				<li class="tabela">
					<b><?=$p->resultado['questao']?></b>
			<?php
			if ($p->resultado['id_imagem'] != 0) {echo "<center><img src='imageOutput.php?id=".$p->resultado['id_imagem']."'/></center>";}
			if (strlen($p->resultado['id_video']) > 3) {echo "<center><iframe width=\"640\" height=\"385\" frameborder=\"0\" class=\"youtube-player\" src=\"http://www.youtube.com/embed/".$p->resultado['id_video']."\"></iframe></center>";}
			?>
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
							<h1 style="margin:0; margin-left:-15px">V</h1>
						</div>
					</td>
					<td style="width:65px; border-left: 2px solid #EEE8EF">
						<div align="center">
							<h1 style="margin:0; margin-left:-15px">F</h1>
						</div>
					</td>
				</tr>
				<tr style="background-color:#EEE8EF">
					<td> Opção 1: <?=$lista_respostas[0]?>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><input type="radio" name="radio1_<?=$i?>" value="v"/></div>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><input type="radio" name="radio1_<?=$i?>" value="f"/></div>
					</td>
				</tr>

<?php
$j=1;
while ($j < $numops) { // ARRAY DE PHP COMEÇA NO ZERO
?>
		<tr style="background-color:#EEE8EF">
					<td>Opção <?=$j+1?>: <?=$lista_respostas[$j]?>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><input type="radio" name="radio<?=$j+1?>_<?=$i?>" value="v"/></div>
					</td>
					<td style="width:65px; border-left: 2px solid c#EEE8EF">
						<div align="center"><input type="radio" name="radio<?=$j+1?>_<?=$i?>" value="f"/></div>
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
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Planeta ROODA 2.0</title>
	<link type="text/css" rel="stylesheet" href="../../planeta.css" />
	<link type="text/css" rel="stylesheet" href="../forum/forum.css" />
	<link type="text/css" rel="stylesheet" href="planeta_pergunta.css" />

	<script type="text/javascript" src="../../jquery.js"></script>

	<script type="text/javascript" src="../../planeta.js"></script>
	<script type="text/javascript" src="pergunta_ajudante.js"></script>
	<script type="text/javascript" src="planeta_pergunta.js"></script>
	<script type="text/javascript" src="../blog/blog.js"></script>
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
			$regua->adicionarNivel("Responder");
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
				<div id="rel"><p id="balao">O Planeta Pergunta é uma funcionalidade que permite a criação de questionários com exercícios que podem variar entre Perguntas, Verdadeiro ou Falso e Múltipla Escolha. </p></div>
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
	<form method="post" action="salvar_questionario_respondido.php" onsubmit="return validaRespostas()">
	
	<div class="bts_cima">
	<div class="bts_msg" align="right">
	</div>
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
<?php
for($i=0 ; $i < count($p->itens) ;$i++) {
	questao($p->resultado['tipo'], $i+1); // mostra a questão
	echo "<p class=\"pedreiragem\">&nbsp;</p>\n"; // cospe um separador
	echo "<input type=\"hidden\" name=\"tipo".($i+1)."\" value=\"".$p->resultado['tipo']."\">\n"; // não sei o que faz, mas é gambiarra
	$p->proximo();
}
?>
	<div class="bts_cima">
		<a href="planeta_pergunta.php?turma=<?=$turma?>"><img src="../../images/botoes/bt_cancelar.png" align="left" style="margin-top:20px"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" style="margin-top:20px"/>
	</div>
	<input type="hidden" name="idquest" value="<?=$id?>">
	<input type="hidden" name="numops" value="<?=count($p->itens)?>">
	</form>
	</div>
	<!-- fim do conteudo -->

	</div>
	<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->

</div><!-- fim da geral -->
</body>
</html>
