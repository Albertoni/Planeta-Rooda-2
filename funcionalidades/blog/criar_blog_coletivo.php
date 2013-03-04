<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("../../reguaNavegacao.class.php");

if (isset($_SESSION['SS_usuario_id']) == false){die("Voc&ecirc; precisa estar logado para fazer essa a&ccedil;&atilde;o.");}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planeta ROODA 2.0</title>
<link type="text/css" rel="stylesheet" href="planeta.css" />
<link type="text/css" rel="stylesheet" href="blog.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript" src="planeta.js"></script>
<script type="text/javascript" src="blog.js"></script>
<script type="text/javascript" src="../lightbox.js"></script>

<!--[if IE 6]>
<script type="text/javascript" src="planeta_ie6.js"></script>
<![endif]-->

</head>

<body onload="atualiza('ajusta()');inicia();fakeFile('botao_upload_frame', 'arquivo_frame', 'falso_frame')">
	<div id="topo">
		<div id="centraliza_topo">
			<?php 
				$regua = new reguaNavegacao();
				$regua->adicionarNivel("Blog", "blog_inicio.php", false);
				$regua->adicionarNivel("Coletivo", " ", false);
				$regua->adicionarNivel("Criar");
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
					<div id="rel"><p id="balao">O blog é um espaço pessoal para escrita, onde é possível anexar arquivos e links interessantes. Nele, você pode compartilhar diversos assuntos com seus colegas e permitir que eles, além de visualizar, publiquem comentários em seus posts e marquem suas reações ao lê-los.</p></div>
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
			<form method="post" enctype="multipart/form-data" action="_blog_criar_coletivo.php">
			<div class="bts_cima">
			<a href="javascript:history.go(-1)"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
			<input type="image" id="responder_topico" src="images/botoes/bt_confirm.png" align="right"/>
			</div>
			<div id="esq">
				<div id="add_colegas" class="bloco">
					<h1>ADICIONAR COLEGAS</h1>
					<ul class="sem_estilo">
						<!--li id="salvar"></li-->
						<ul class="info_importada">
<?php

$consulta = new conexao();
$nome = new conexao();

$titulo="";

if(!isset($_GET['editId'])){ // Se não tá setado, não tá editando
	$consulta->solicitar("SELECT codTurma FROM $tabela_turmasUsuario WHERE codUsuario = ".$_SESSION['SS_usuario_id']); // Pega a turma do cara
	$temp = $consulta->resultado['codTurma'];
	$consulta->solicitar("SELECT codUsuario FROM $tabela_turmasUsuario WHERE codTurma = $temp"); // Pega a lista de pessoas da turma

	for($i=0; $i<$consulta->registros; $i++)
	{
		$id = $consulta->resultado['codUsuario'];
		$nome->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = $id");
	?>
								<li class="enviado<?=alterna()?>"><input type="checkbox" name="<?=$id?>" /><?=$nome->resultado['usuario_nome']?></li>
	<?php
		$consulta->proximo();
	}
} else {
	if (is_numeric($_GET['editId'])){
		$membrosBlog = new conexao();
		$membrosBlog->solicitar("SELECT OwnersIds, Title FROM $tabela_blogs WHERE id=".$_GET['editId']);
		$membros = explode(";",$membrosBlog->resultado['OwnersIds']);
		$titulo = $membrosBlog->resultado['Title'];
		
		if (!in_array($_SESSION['SS_usuario_id'], $membros) and !(checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelAdmin) === 1)){
			echo "<p align=\"justify\"><b>Você não tem permissão pra executar essa ação. Recomendamos que desista agora. Sua ação foi logada.</b></p>";
		}else{
			$consulta->solicitar("SELECT codTurma FROM $tabela_turmasUsuario WHERE codUsuario = ".$_SESSION['SS_usuario_id']); // Pega a turma do cara
			$temp = $consulta->resultado['codTurma'];
			$consulta->solicitar("SELECT codUsuario FROM $tabela_turmasUsuario WHERE codTurma = $temp"); // Pega a lista de pessoas da turma
		
			for($i=0; $i<$consulta->registros; $i++)
			{
				$id = $consulta->resultado['codUsuario'];
				$nome->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = $id");
				if (in_array($id, $membros)){
				?>
									<li class="enviado<?=alterna()?>"><input type="checkbox" checked="1" name="<?=$id?>" /><?=$nome->resultado['usuario_nome']?></li>
				<?php
				}else{
				?>
									<li class="enviado<?=alterna()?>"><input type="checkbox" name="<?=$id?>" /><?=$nome->resultado['usuario_nome']?></li>
				<?php
				}
				$consulta->proximo();
			}
		}
	} else { // usuário brincou com o GET, possivel tentativa de SQL injection
		echo "Quase lá. Continue tentando.";
	}
}
unset($temp); // sei lá, vamos fazer do jeito certo.
unset($consulta);

/*							<li class="enviado1"><input type="checkbox" />Fulaninho de Tal da Silva</li>
							<li class="enviado2"><input type="checkbox" />Fulaninho João da Fonseca</li>
							<li class="enviado1"><input type="checkbox" />Fulaninho de Taubaté</li>*/

?>
						</ul>
					</ul>
				</div>
			</div>
			<div id="dir">
				<div id="info_blog" class="bloco">
					<h1>INFORMAÇÕES DO BLOG</h1>
					<ul class="sem_estilo">
<?php
if ($titulo != ''){// tão editando
?>
						<li>Título</li>
						<li><input type="text" name="titulo" class="blog_info" value="<?=$titulo?>"/></li>
						<input type="hidden" name="edicao" value="<?=$_GET['editId']?>">
<?php
} else {
?>
						<li>Título</li>
						<li><input type="text" name="titulo" class="blog_info"/></li>
						<li>Descrição do Blog</li>
						<li><textarea class="blog_info" name="descricao" rows="4"></textarea></li>
						<li>Imagem do Blog</li>
						<input name="userfile" type="file" id="arquivo_frame" class="upload_file" style="" onchange="trocador('falso_frame', 'arquivo_frame')" />
						<input name="falso" type="text" id="falso_frame" />
						<img src="images/botoes/bt_procurar_arquivo.png" id="botao_upload_frame" />
<?php
}
?>
					</ul>
				</div>
			</div>
			<div class="bts_baixo">
				<a href="javascript:history.go(-1)"><img src="../../images/botoes/bt_cancelar.png" align="left"/></a>
				<input type="image" id="responder_topico" src="images/botoes/bt_confirm.png" align="right"/>
			</div>
			</form>
		</div><!-- Fecha Div conteudo -->
		</div><!-- Fecha Div conteudo_meio -->   
		<div id="conteudo_base">
		</div><!-- para a imagem de fundo da base -->
	</div><!-- fim da geral -->

</body>
</html>
