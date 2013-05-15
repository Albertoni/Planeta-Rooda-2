<?php
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("verifica_user.php");
	
	session_start();
	
	require("../../reguaNavegacao.class.php");
	
	$user=$_SESSION['user'];
	
	$turma = (isset($_GET['turma']) and is_numeric($_GET['turma']))?$_GET['turma']:die("Uma id de turma incorreta (nao-numerica) foi passada para essa pagina.");
	
	$perm = checa_permissoes(TIPOFORUM, $turma);
	
	if($user->podeAcessar($perm['forum_criarTopico'], $turma)){
		$topico = (isset($_GET['tid']) and is_numeric($_GET['tid'])) ? $_GET['tid']:'-1'; // TID = TÓPICO ID
		$editar = ($topico != '-1');
		$titulo = '';
		$conteudo = '';
		
		$pai = "-1";
		if ($editar){
			if($user->podeAcessar($perm['forum_editarTopico'], $turma)){
				$pesquisa1 = new conexao();
				$pesquisa1->solicitar("select * from $tabela_forum where msg_id = '$topico' and forum_id = '$FORUM_ID' LIMIT 1");
				$pai = $pesquisa1->resultado['msg_pai'];
				$titulo = $pesquisa1->resultado['msg_titulo'];
				$conteudo = $pesquisa1->resultado['msg_conteudo'];
				$criador = $pesquisa1->resultado['msg_usuario'];
				$conteudo = str_replace("<br>", "\n", $conteudo);
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
if (isset($_GET['tid'])) // Editando
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
	
	<form name="criatop" action="forum_salva_topico.php?turma=<?=$turma?>" method="post">
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
<?php if ($pai == "-1"){ ?>
					<div class="box_dados">
						Título do Tópico
						<input type="text" name="msg_titulo" value="<?php echo $titulo?>"/>
					</div>
<?php } ?>				
					<div class="bts_dir" align="right">
					</div>
				</li>
				
				<li class="espaco_linhas">Mensagem
					<textarea name="msg_conteudo" rows="15" class="msg_dimensao"><?php echo $conteudo?></textarea>
				</li>
			</ul>
			<input type="hidden" name="topico" value="<?php echo $topico?>" />
			<input type="hidden" name="fid" value="<?php echo $FORUM_ID?>" />
			<input type="hidden" name="criador" value="<?php echo $criador?>" />
			<input type="hidden" name="pai" value="<?php echo $pai?>" />
			<input type="hidden" name="turma" value="<?php echo $turma?>" />

	</div><!-- fim da div criar_topicos -->

	<div class="bts_baixo">
		<img align="left" id="voltar" src="../../images/botoes/bt_voltar.png" style="cursor:pointer" onclick="history.go(-1)"/>
		<img align="right" src="../../images/botoes/bt_confirm.png" style="cursor:pointer" onclick="document.criatop.submit();" />
	</div>
	
	</form>
	</div>
	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>
