<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("../../reguaNavegacao.class.php");

session_start();
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

if (!isset($_SESSION['SS_usuario_nivel_sistema'])){ // if not logged in
	die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");
}

if (isset($_GET['turma']) and $_GET['turma'] != ""){
	$turma = $_GET['turma'];
}else{
	$turma = $_SESSION['SS_turmas'][0];
}

$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

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
			$regua->adicionarNivel("Planeta Pergunta");
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
	
<a name="topo"></a>
	
<div id="conteudo_topo"></div><!-- para a imagem de fundo do topo -->
<div id="conteudo_meio"><!-- para a imagem de fundo do meio -->

<!-- **************************
			conteudo
***************************** -->
	<div id="conteudo"><!-- tem que estar dentro da div 'conteudo_meio' -->

	<div class="bts_cima">
	<div class="bts_msg" align="right">
<?php
	if($usuario->podeAcessar($permissoes['pergunta_criarQuestionario'], $turma)){
		echo "		<a href=\"planeta_pergunta_criar.php?turma=$turma\"><img src=\"../../images/botoes/bt_criar_questionario.png\" /></a>";
	}
?>
	</div>
	</div>
<?php
if(1 < sizeof($_SESSION['SS_turmas'])){
	selecionaTurmas($turma);
}
?>
	<div id="bloco_mensagens" class="bloco">
		<h1>QUESTIONÁRIOS</h1>
<?php
$consulta = new conexao();
$nomecriador = new conexao();
$consulta->solicitar("SELECT * FROM $tabela_PerguntaQuestionarios WHERE turma = $turma ORDER BY titulo");

// Mais tarde temos que arrumar pra mostrar só as turmas que se pertence, ou alguma outra condição que as gurias inventarem

for($i=0 ; $i < $consulta->registros ;$i++) {
	$nomecriador->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = ".$consulta->resultado['criador']);
	$arraydata = explode("-", $consulta->resultado['datainicio']); // Separa pra array
	$datainicio = $arraydata[2]."/".$arraydata[1]."/".$arraydata[0]; // Monta a data no formato ok
	
	if($consulta->resultado['alunoInsere'] == 1){
?>	<div class="cor<?=alterna()?>">
		<ul>
			<li class="tabela">
				<a href="planeta_pergunta_inserir.php?id=<?=$consulta->resultado['id']?>&amp;turma=<?=$turma?>"><p class="nome"><b><?=$consulta->resultado['titulo']?></b></p></a>
				<div class="info">
<?php
	if($usuario->podeAcessar($permissoes['pergunta_editarQuestionario'], $turma)){
?>					<a class="pode_editar" href="planeta_pergunta_ver_inseridas.php?id=<?=$consulta->resultado['id']?>&amp;turma=<?=$turma?>">VER QUESTÕES</a><?
	}
	if($usuario->podeAcessar($permissoes['pergunta_deletarQuestionario'], $turma)){
?>					<a class="pode_deletar" href="planeta_pergunta_deletar.php?id=<?=$consulta->resultado['id']?>&amp;turma=<?=$turma?>"><b>DELETAR</b></a><?
	}
	//if($usuario)
?>
					<p class="data"><?=$datainicio?></p>
				</div>
			</li>
			<li>
				<p class="texto_resposta"><?=$consulta->resultado['descricao']?></p>
				<br /><div id="autor" class="criado_por">Criado por: <?=$nomecriador->resultado['usuario_nome']?></div>
			</li>
		</ul>
	</div>
<?php
	}else{
?>
	<div class="cor<?=alterna()?>">
		<ul>
			<li class="tabela">
				<a href="planeta_pergunta_responder.php?id=<?=$consulta->resultado['id']?>&amp;turma=<?=$turma?>"><p class="nome"><b><?=$consulta->resultado['titulo']?></b></p></a>
				<div class="info">
	<?php
	if($usuario->podeAcessar($permissoes['pergunta_editarQuestionario'], $turma)){
?>					<a class="pode_editar" href="planeta_pergunta_editar.php?id=<?=$consulta->resultado['id']?>&amp;turma=<?=$turma?>">EDITAR</a><?
	}
	if($usuario->podeAcessar($permissoes['pergunta_deletarQuestionario'], $turma)){
?>					<a class="pode_deletar" href="planeta_pergunta_deletar.php?id=<?=$consulta->resultado['id']?>&amp;turma=<?=$turma?>"><b>DELETAR</b></a><?
	}
	?>
					<p class="data"><?=$datainicio?></p>
				</div>
			</li>
			<li>
				<p class="texto_resposta"><?=$consulta->resultado['descricao']?></p>
				<br /><div id="autor" class="criado_por">Criado por: <?=$nomecriador->resultado['usuario_nome']?></div>
			</li>
<?php
if(checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelProfessor) == true){
	$alunos = new conexao();
	$nome = new conexao();
	$alunos->solicitar("SELECT usuario FROM $tabela_PerguntaRespostas WHERE questionario = ".$consulta->resultado['id']);
	
	if ($alunos->registros != 0) {
		// Preparando o HTML!
		echo "	Ver respostas do aluno:<br/>	<select class=\"nome\" id=\"lista_alunos_$i\">";
	
		for($j=0; $j < count($alunos->itens); $j++) {
			$nome->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = ".$alunos->resultado['usuario']);
		
			// Cospe os alunos!
			echo "\n<option value=\"".$alunos->resultado['usuario'].";".$consulta->resultado['id']."\">".$nome->resultado['usuario_nome']."</option>";
		
			$alunos->proximo();
		}
	
	echo "	</select>
	<a class=\"data\" onclick=\"verRespostas($i, $turma)\"><b>Ver respostas do aluno selecionado</b></a>";
	} else {
		echo "Nenhum aluno respondeu o questionário ainda!";
	}
}
?>
			</ul>
		</div>
<?php
	} // linha 119
	$consulta->proximo();
}

?>

	</div><!-- fim da div topicos -->
	<div class="bts_cima">
	<div class="bts_msg" align="right" style="margin-top:20px">
<?php
if($usuario->podeAcessar($permissoes['pergunta_criarQuestionario'], $turma)){
	echo "		<a href=\"planeta_pergunta_criar.php?turma=$turma\"><img src=\"../../images/botoes/bt_criar_questionario.png\" /></a>";
}
?>
	</div>
	</div>
	<div style="clear:both;"></div>
	</div>
	<!-- fim do conteudo -->

</div>
<div id="conteudo_base"></div><!-- para a imagem de fundo da base -->


</div><!-- fim da geral -->

</body>
</html>
