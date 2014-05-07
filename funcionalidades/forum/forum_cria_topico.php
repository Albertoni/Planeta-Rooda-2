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
	
	$turma = (isset($_GET['turma']) and is_numeric($_GET['turma'])) ? (int) $_GET['turma'] : die("Uma id de turma incorreta (nao-numerica) foi passada para essa pagina.");
	
	$perm = checa_permissoes(TIPOFORUM, $turma);
	
	if($user->podeAcessar($perm['forum_criarTopico'], $turma)){
		$idTopico =  (isset($_GET['idTopico']) and is_numeric($_GET['idTopico'])) ? $_GET['idTopico']:'-1';
		$editar = ($idTopico != '-1');
		$titulo = '';
		$texto = '';

		$podeDeletarAnexo = (bool) $user->podeAcessar($perm['forum_excluirAnexos'], $turma);
		$podeEnviarAnexo = (bool) $user->podeAcessar($perm['forum_enviarAnexos'], $turma);
		
		if ($editar){
			if($user->podeAcessar($perm['forum_editarTopico'], $turma)){
				$q = new conexao();

				// dados do topico e primeira mensagem
				$q->solicitar("SELECT * FROM ForumTopico INNER JOIN ForumMensagem
					ON ForumTopico.idTopico = ForumMensagem.idTopico
					WHERE ForumTopico.idTopico = $idTopico
					ORDER BY idMensagem ASC LIMIT 1");

				if ((int) $q->resultado['idTurma'] === (int) $turma){
					$texto = str_replace("<br>", "\n", $q->resultado['texto']);
					$titulo = $q->resultado['titulo'];
					$idMensagem = $q->resultado['idMensagem'];
				}
			}else{
				die("Voce nao tem permissao para editar topicos.");
			}
		}else{
			$criador = -1;
		}
	}else{
		die("Voce nao tem permissao para criar topicos.");
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="../../planeta.css" />
<link type="text/css" rel="stylesheet" href="forum.css" />
<script type="text/javascript" src="../../jquery.js"></script>
<script type="text/javascript" src="../../planeta.js"></script>
<script type="text/javascript" src="forum.js"></script>
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
if (isset($_GET['idMensagem'])) // Editando
	echo "Nesse espaço, você pode editar o título e/ou a mensagem do tópico escolhido.";
else // senão, tá criando.
	echo "Para criar um tópico de discussão, basta preencher o título do mesmo e a mensagem, que explica o assunto que será debatido.";
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
	
	<form name="criatop" action="forum_salva_topico.php" method="post">
	<div class="bts_cima">
		<img align="left" id="voltar" src="../../images/botoes/bt_voltar.png" style="cursor:pointer" onclick="history.go(-1)"/>
		<img align="right" src="../../images/botoes/bt_confirm.png" style="cursor:pointer" onclick="document.criatop.submit();" />
	</div>
	
	<div id="criar_topico" class="bloco">
<?php
	if ($editar){
		echo "<h1>EDITAR TÓPICO</h1>";
	}else{
		echo "<h1>CRIAR TÓPICO</h1>";
}?>
			<ul class="sem_estilo">
			
				<li class="tabela">
					<div class="box_dados">
						Título do Tópico
						<input type="text" name="titulo" value="<?php echo $titulo?>">
					</div>
				</li>
				
				<li class="espaco_linhas">Mensagem
					<textarea name="texto" rows="15" class="msg_dimensao"><?php echo $texto?></textarea>
				</li>
			</ul>
			<?= $idTopico != -1 ? "<input type=\"hidden\" name=\"idTopico\" value=\"$idTopico\">" : "" ?>
			<input type="hidden" name="idTurma" value="<?php echo $turma?>" />
<?php
	if($editar){
		echo "			<input type=\"hidden\" name=\"idMensagem\" value=\"$idMensagem\">";
	}

?>

	</div><!-- fim da div criar_topicos -->

	<div class="bts_baixo">
		<img align="left" id="voltar" src="../../images/botoes/bt_voltar.png" style="cursor:pointer" onclick="history.go(-1)"/>
		<img align="right" src="../../images/botoes/bt_confirm.png" style="cursor:pointer" onclick="document.criatop.submit();" />
	</div>
	
	</form>
	<div style="clear:both;"></div>
	</div>
	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>