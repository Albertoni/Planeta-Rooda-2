<?php
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
	
	session_start();
	
	require_once("../../reguaNavegacao.class.php");
	require_once("sistema_forum.php");
	
	$user=$_SESSION['user'];

	$idTopico = -1;
	
	//$turma = (isset($_GET['turma']) and is_numeric($_GET['turma'])) ? $_GET['turma'] : die("Uma id de turma incorreta (nao-numerica) foi passada para essa pagina.");
	$idMensagem =  (isset($_GET['idMensagem']) and is_numeric($_GET['idMensagem'])) ? (int) $_GET['idMensagem']:'-1';
	$mensagem = new mensagem($idMensagem);
	$turma = $mensagem->getIdTurma();
	$perm = checa_permissoes(TIPOFORUM, $turma);
	
	if($user->podeAcessar($perm['forum_responderTopico'], $turma)){
		$editar = ($idMensagem != '-1');
		$titulo = '';
		$texto = '';
		
		if ($editar){
			if($user->podeAcessar($perm['forum_editarResposta'], $turma)){
				// dados do topico e primeira mensagem

				$texto = str_replace("<br>", "\n", $mensagem->getTexto());
				$idMensagem = $mensagem->getId();
				$idTopico = $mensagem->getIdTopico();
				$mensagemRespondida = $mensagem->getIdMensagemRespondida();
				$anexos = $mensagem->getAnexos();
				$podeDeletarAnexo = (bool) $user->podeAcessar($perm['forum_excluirAnexos'], $turma);
				//if ($user0->podeAcessar($perm['forum_enviarAnexos']))

				$argumentosJS = "$turma,$idMensagem,$idTopico";
			}else{
				die("Voce nao tem permissao para editar mensagens.");
			}
		}else{
			$criador = -1;
		}
	}else{
		die("Voce nao tem permissao para criar mensagens.");
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="forum.css" />
<link type="text/css" rel="stylesheet" href="../../fileicons.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();">

<div id="topo">
	<div id="centraliza_topo">
		<?php 
			$regua = new reguaNavegacao();
			$regua->adicionarNivel("Fórum", "forum.php", false);
			$regua->adicionarNivel("Criar Tópico");
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
				<div id="rel"><p id="balao">
<?php
if ($editar) // Editando
	echo "Nesse espaço, você pode editar a mensagem escolhida.";
else // senão, tá criando.
	echo "GURIAS PELO AMOR DE DEUS QUE MENSAGEM QUE TEM QUE BOTAR AQUI MESMO AI MEU DEUS SOCORRO AOAODMOASMDOSMAODMASOIDNIUFGNDAIUNFINDSIJNFIJDSJF DSJFSNDJFJDSNFDSFNDSKJF NDSKJN FKJDSNFKJDSNFDSNKJFNDKJSNF KJDSNFKJDSNFKJNDSKJFJDSN KJFDNSKJNFKJDNSKJNSKJDNFKJDSNFKJ DSNKJFNDSKJFNDS KJDSN KJFDSNKJFNDSKJFNDSFKJNDSKJFNDSKJ NKJDSNFKJNFKJDSNFKJDS NKJFDS.";
?>
				</p></div>
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
	<form onsubmit="confirmaEditarMensagem(this); event.preventDefault ? event.preventDefault() : event.returnValue = false; return false;" name="criatop" action="forum_salva_mensagem.php" enctype="multipart/form-data" method="post">
	<div class="bts_cima">
		<img align="left" id="voltar" src="../../images/botoes/bt_voltar.png" style="cursor:pointer" onclick="history.go(-1)"/>
		<img align="right" src="../../images/botoes/bt_confirm.png" style="cursor:pointer" onclick="confirmaEditarMensagem(<?=$argumentosJS?>)" />
	</div>
	
	<div id="criar_topico" class="bloco">
		<?php
		if ($editar){
			echo "<h1>EDITAR MENSAGEM</h1>";
		}else{
			echo "<h1>CRIAR MENSAGEM</h1>";
		}
		?>
		<ul class="sem_estilo">
			<li>Mensagem:
				<textarea name="msg_conteudo" rows="15" class="msg_dimensao" id="textarea"><?php echo $texto?></textarea></li>
			<li>
				<ul class="anexos">
					<?php
					if (count($anexos) > 0) {
						foreach ($anexos as $a) {
							$classes = explode("/", $a->getTipo());
							for ($i=0 ; $i < count($classes) ; $i++) {
								$classes[$i] = str_replace(".", "-", $classes[$i]);
							}
							$classes[] = "arquivo";
							echo "
							<li class=\"".implode(" ", $classes)."\">
								<a href=\"abre_anexo.php?m={$idMensagem}&amp;a={$a->getId()}\"><span class=\"icon\">&nbsp;</span> {$a->getNome()}</a>"
								.($podeDeletarAnexo ? " &nbsp;
								<label><input type=\"checkbox\" name=\"deletarAnexo\" value=\"{$a->getId()}\"> remover</label>" 
								: "")."
							</li>";
						}
					} else {
						echo "Incluir anexo: <input type=\"file\" name=\"arquivo\">";
					}
					?>
				</ul>
			</li>
		</ul>
		<input type="hidden" name="idTopico" value="<?=$idTopico?>">
		<input type="hidden" name="turma" value="<?php echo $turma?>" id="idTurma">
		<?php
		if($editar){
			echo "<input type=\"hidden\" name=\"idMensagem\" value=\"$idMensagem\">";
		}
		?>

	</div><!-- fim da div criar_topicos -->

	<div class="bts_baixo">
		<img align="left" id="voltar" src="../../images/botoes/bt_voltar.png" style="cursor:pointer" onclick="history.go(-1)"/>
		<img align="right" src="../../images/botoes/bt_confirm.png" style="cursor:pointer" onclick="confirmaEditarMensagem(<?=$argumentosJS?>);" />
	</div>
	
	</form>
	<div style="clear:both;"></div>
	</div>
	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->
<script type="text/javascript" src="forum.js"></script>
</body>
</html>