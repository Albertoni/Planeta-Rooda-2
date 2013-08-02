<?php
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

session_start();

$turma =	is_numeric($_POST['turma']) ? $_POST['turma'] : 0;

$perm = checa_permissoes(TIPOPERGUNTA, $turma);
if($perm == false){
	die("Desculpe, mas o Planeta Pergunta esta desabilitado para esta turma.");
}
if(!$_SESSION['user']->podeAcessar($perm['pergunta_criarQuestionario'], $turma)){
	die("Desculpe, voce nao pode criar questionários nessa turma.");
}

$erro_data = "Aconteceu algo errado com as datas. Por favor, mande isso para os desenvolvedores:\n<pre>".print_r($_POST, true)."</pre>";

$titulo = str_replace("\n", "", $_POST['titulo']);
$descricao = str_replace("\n", "", $_POST['descrição']);
$turma = is_numeric($_POST['turma']) ? $_POST['turma'] : 0; // prevenir sql sacumé

$dia1		= is_numeric($_POST['dia1']) ? $_POST['dia1'] : die($erro_data);
$mes1		= is_numeric($_POST['mes1']) ? $_POST['mes1'] : die($erro_data);
$ano1		= is_numeric($_POST['ano1']) ? $_POST['ano1'] : die($erro_data);
$dia2		= is_numeric($_POST['dia2']) ? $_POST['dia2'] : die($erro_data);
$mes2		= is_numeric($_POST['mes2']) ? $_POST['mes2'] : die($erro_data);
$ano2		= is_numeric($_POST['ano2']) ? $_POST['ano2'] : die($erro_data);

$libera	= isset($_POST['liberar']) and $_POST['liberar'] == "on" ? true : false;
$alquest= isset($_POST['alquest']) and $_POST['alquest'] == "on" ? true : false;

function put_quest($tipo, $id){
	switch ($tipo){
		case 1: // multipla ?>
	<div class="pergunta">
		<div id="criar_topico" class="bloco multipla_escolha">
			<h1>PERGUNTA <?=$id?></h1>
			<ul class="sem_estilo">
				<li class="tabela" id="mostraArquivo<?=$id?>">
					<textarea rows="3" name="pergmul_<?=$id?>"></textarea>
					<input type="hidden" name="tipo_<?=$id?>" value="1">
					<input type="hidden" name="numop_<?=$id?>" value="1">
					<input type="hidden" name="idimg_<?=$id?>" value="0">
					<input type="hidden" name="idvid_<?=$id?>" value="0">
					<center><br /><a onclick="insere_imagem(<?=$id?>)">Clique aqui para adicionar uma imagem</a></center>
					<br /><div style="float:left">Endereço do vídeo: <input id="video_<?=$id?>"></div><div style="float:right"><a onclick="insere_video(<?=$id?>)">Clique aqui para adicionar um vídeo</a></div>
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
					<input type="text" size=60 style="width:350px" name="opmul1_<?=$id?>"/>
				</td>
				<td></td>
				<td style="width:65px; border-left: 2px solid c#EEE8EF">
					<div align="center"><input type="radio" name="radio_<?=$id?>" value="1"/></div>
				</td>
			</tr>

			<tr style="background-color:#E7C7ED" id="bala_de_cocacola<?=$id?>">
				<td>
				<div style="float:left">
					<a onclick="maisPerguntasME(<?=$id?>, 1)"><b>Mais opções</b></a>
				</div>
				<div style="float:right">
					<a onclick="menosPerguntasME(<?=$id?>, 1)"><b>Menos opções</b></a>
				</div>
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
	<div class="pergunta">
		<div id="criar_topico" class="bloco subjetiva">
			<h1>PERGUNTA <?=$id?></h1>
			<ul class="sem_estilo">
				<li class="tabela" id="mostraArquivo<?=$id?>">
					<textarea rows="3" name="pergsubj_<?=$id?>"></textarea>
					<input type="hidden" name="tipo_<?=$id?>" value="2">
					<input type="hidden" name="idimg_<?=$id?>" value="0">
					<input type="hidden" name="idvid_<?=$id?>" value="0">
					<center><br /><a onclick="insere_imagem(<?=$id?>)">Clique aqui para adicionar uma imagem</a></center>
					<br /><div style="float:left">Endereço do vídeo: <input id="video_<?=$id?>"></div><div style="float:right"><a onclick="insere_video(<?=$id?>)">Clique aqui para adicionar um vídeo</a></div>
				</li>
			</ul>
		</div>
		<div id="criar_topico" class="bloco">
			<h1>RESPOSTA</h1>
			<ul class="sem_estilo">
				<li class="tabela">
					<textarea rows="2" name="respsubj_<?=$id?>"></textarea>
				</li>
			</ul>
		</div>
	</div>
	<p class="pedreiragem">&nbsp;</p>
	<p class="pedreiragem">&nbsp;</p>
	<p class="pedreiragem">&nbsp;</p>
<?php		break;
		case 3: // V v F ******************************************************************************************************** ?>
	<div class="pergunta">
		<div id="criar_topico" class="bloco pergunta_vf">
			<h1>PERGUNTA <?=$id?></h1>
			<ul class="sem_estilo">
				<li class="tabela" id="mostraArquivo<?=$id?>">
					<textarea rows="3" name="pergvf_<?=$id?>"></textarea>
					<input type="hidden" name="tipo_<?=$id?>" value="3">
					<input type="hidden" name="numop_<?=$id?>" value="1">
					<input type="hidden" name="idimg_<?=$id?>" value="0">
					<input type="hidden" name="idvid_<?=$id?>" value="0">
					<center><br /><a onclick="insere_imagem(<?=$id?>)">Clique aqui para adicionar uma imagem</a></center>
					<br /><div style="float:left">Endereço do vídeo: <input id="video_<?=$id?>"></div><div style="float:right"><a onclick="insere_video(<?=$id?>)">Clique aqui para adicionar um vídeo</a></div>
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
				<tr style="background-color:#EEE8EF">
					<td> Opção 1:
						<input type="text" size=60 style="width:350px" name="opvf1_<?=$id?>"/>
					</td>
					<td style="width:65px; border-left: 2px solid white">
						<div align="center"><input type="radio" name="radio1_<?=$id?>" value="v"/></div>
					</td>
					<td style="width:65px; border-left: 2px solid white">
						<div align="center"><input type="radio" name="radio1_<?=$id?>" value="f"/></div>
					</td>
				</tr>
				
				<tr style="background-color:#E7C7ED" id="bala_de_canela<?=$id?>">
					<td>
						<a onclick="maisPerguntasVF(<?=$id?>, 1)"><b>Mais opções</b></a>
						<div style="float:right">
							<a onclick="menosPerguntasVF(<?=$id?>, 1)"><b>Menos opções</b></a>
						</div>
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
		<p id="hist"><a href="#">Planeta ROODA</a> > <a href="#">Professor Fulaninho de Tal</a> > <a href="#">Planeta Pergunta</a></p>
		<p id="bt_ajuda"><span class="troca">OCULTAR AJUDANTE</span><span style="display:none" class="troca">CHAMAR AJUDANTE</span></p>
	</div>
</div>

<!--**************************
		inserção de imagens
*****************************-->

<div id="gambiajax" style="display:none">
	<iframe src="" name="framegambi"></iframe>
	<form method="post" enctype="multipart/form-data" target="framegambi" id="formgambi" name="formgambi" action="bota_imagem.php">
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
	<form action="enviar_questionario.php" method="post">
	<div class="bts_cima">
		<a href="planeta_pergunta.php"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" />
	</div>

<?php
$i = 1;

while (isset($_POST['quest'.$i])) {
	put_quest($_POST['quest'.$i], $i);
	$i++;
}

?>

	<div class="bts_baixo">
		<a href="planeta_pergunta.php"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" />
	</div>
	<!--hic sunt dracones-->
	<input type="hidden" name="titulo" value="<?=$titulo?>"/>
	<input type="hidden" name="descricao" value="<?=$descricao?>"/>
	<input type="hidden" name="dia1" value="<?=$dia1?>"/>
	<input type="hidden" name="dia2" value="<?=$dia2?>"/>
	<input type="hidden" name="mes1" value="<?=$mes1?>"/>
	<input type="hidden" name="mes2" value="<?=$mes2?>"/>
	<input type="hidden" name="ano1" value="<?=$ano1?>"/>
	<input type="hidden" name="ano2" value="<?=$ano2?>"/>
	<input type="hidden" name="libera" value="<?=$libera?>"/>
	<input type="hidden" name="alquest" value="<?=$alquest?>"/>
	
	<input type="hidden" name="turma" value="<?=$turma?>"/>
	</form>
	<div style="clear:both;"></div>
	</div>
	<!-- fim do conteudo -->

</div> <!-- fim do conteudo_meio -->
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>
