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

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}else{
	die("Favor voltar e tentar novamente, a turma em que voce esta nao foi passada corretamente.");
}

$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false and !$usuario->isAdmin()){die("Funcionalidade desabilitada para a sua turma.");}
if(!$usuario->podeAcessar($permissoes['pergunta_criarPergunta'], $turma)){
	die('Voce nao tem permissoes para inserir perguntas nessa turma.');
}

$p = new conexao(); // perguntas
$q = new conexao(); // questionario
$q->solicitar("SELECT * FROM $tabela_PerguntaQuestionarios WHERE id = $id");
$p->solicitar("SELECT * FROM $tabela_PerguntaPerguntas WHERE id_questionario = $id ORDER BY id ASC");

$nomecriador = new conexao();
$nomecriador->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = ".$q->resultado['criador']);

$arraydata = explode("-", $q->resultado['datainicio']); // Separa pra array
$datainicio = $arraydata[2]."/".$arraydata[1]."/".$arraydata[0]; // Monta a data no formato ok

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
			$regua->adicionarNivel("Inserir");
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
	<form method="post" action="salvar_insercao_questionario.php" onsubmit="return validaRespostas()">
	
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
					<input type="hidden" name="gambi" value="3331333"><!--pfvr ficar bem quietinho quem ver isso obg-->
					<input type="hidden" name="id-hidden" value="<?=$id?>">
					<hr>
					Selecione o tipo da questão:
					<select name="tipo" onchange="mudaQuest(this)">
						<option value="1">Multipla Escolha</option>
						<option value="2">Subjetiva</option>
						<option value="3">Verdadeiro ou Falso</option>
					</select>
					<hr>
					<div id="questao1">
						<div class="pergunta">
							<div id="criar_topico" class="bloco multipla_escolha">
								<h1>PERGUNTA</h1>
								<ul class="sem_estilo" style="margin:0px">
									<li class="tabela" id="mostraArquivo<?=$id?>">
										<textarea rows="3" name="pergmul_<?=$id?>"></textarea>
										<input type="hidden" name="numop_<?=$id?>" value="1">
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
										<input type="text" size=60 style="width:550px" name="opmul1_<?=$id?>"/>
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
					</div>
					<div id="questao2" style="display:none">
						<div class="pergunta">
							<div id="criar_topico" class="bloco subjetiva">
								<h1>PERGUNTA</h1>
								<ul class="sem_estilo" style="margin:0px">
									<li class="tabela" id="mostraArquivo<?=$id?>">
										<textarea rows="3" name="pergsubj_<?=$id?>"></textarea>
									</li>
								</ul>
							</div>
							<div id="criar_topico" class="bloco">
								<h1>RESPOSTA</h1>
								<ul class="sem_estilo" style="margin:0px">
									<li class="tabela">
										<textarea rows="2" name="respsubj_<?=$id?>"></textarea>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div id="questao3" style="display:none">
						<div class="pergunta">
							<div id="criar_topico" class="bloco pergunta_vf">
								<h1>PERGUNTA</h1>
								<ul class="sem_estilo" style="margin:0px">
									<li class="tabela" id="mostraArquivo<?=$id+1?>">
										<textarea rows="3" name="pergvf_<?=$id+1?>"></textarea>
										<input type="hidden" name="numop_<?=$id+1?>" value="1">
									</li>
								</ul>
							</div>
							<div id="criar_topico" class="bloco" style="border:0; background-color:transparent;">
								<table width="100%" cellpadding="0px" cellspacing="0" id="adicione_questoes_aqui<?=$id+1?>">
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
											<input type="text" size=60 style="width:450px" name="opvf1_<?=$id+1?>"/>
										</td>
										<td style="width:65px; border-left: 2px solid white">
											<div align="center"><input type="radio" name="radio1_<?=$id+1?>" value="v"/></div>
										</td>
										<td style="width:65px; bpartorder-left: 2px solid white">
											<div align="center"><input type="radio" name="radio1_<?=$id+1?>" value="f"/></div>
										</td>
									</tr>
				
									<tr style="background-color:#E7C7ED" id="bala_de_canela<?=$id+1?>">
										<td>
											<a onclick="maisPerguntasVF(<?=$id+1?>, 1)"><b>Mais opções</b></a>
											<div style="float:right">
												<a onclick="menosPerguntasVF(<?=$id+1?>, 1)"><b>Menos opções</b></a>
											</div>
										</td>
										<td></td>
										<td></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div><!-- fim da div topicos -->
	<div class="bts_cima">
		<a href="planeta_pergunta.php"><img src="../../images/botoes/bt_cancelar.png" align="left" style="margin-top:20px"/></a>
		<input type="image" src="../../images/botoes/bt_confirm.png" align="right" style="margin-top:20px"/>
	</div>
	<input type="hidden" name="idquest" value="<?=$id?>">
	<input type="hidden" name="numops" value="<?=count($p->itens)?>">
	</form>
	<div style="clear:both;"></div>
	</div>
	<!-- fim do conteudo -->

	</div>
	<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->

</div><!-- fim da geral -->
</body>
</html>
