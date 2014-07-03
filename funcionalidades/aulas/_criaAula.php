<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("aula.class.php");

$usuario = usuario_sessao();
if ($usuario === false){die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");}

$turma = $_GET['turma'];
$permissoes = checa_permissoes(TIPOAULA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if(!$usuario->podeAcessar($permissoes['aulas_criarAulas'], $turma)){
	magic_redirect("planeta_aulas.php?turma=$turma");
	exit();
}


if( ! (isset($_POST['tipo']) and isset($_POST['turma']))){die("Aconteceu um erro que nao deveria acontecer, favor voltar e tentar novamente");}

//if( ! in_array($_POST['turma'], $_SESSION['SS_turmas'])){die("ERRO: VOCÊ NÃO PERTENCE A UMA TURMA DE HACKERS");} // Barra o cara tentando postar em turma a qual ele não pertence

if( ! (isset($_POST['data'])))	{die("Favor voltar e preencher a data corretamente");}
if( ! (isset($_POST['desc'])))	{die("Favor voltar e preencher a descricao corretamente");}
if( ! (isset($_POST['titulo']))){die("Favor voltar e preencher o titulo corretamente");}

$q = new conexao(); global $tabela_Aulas;

$turma	= $_POST['turma'];
$data	= $_POST['data'];
$desc	= $_POST['desc'];
$titulo	= $_POST['titulo'];
$fundo	= isset($_POST['fundo']) ? $_POST['fundo'] : 1;
$aula_a_editar = isset($_POST['aula_id']) ? $_POST['aula_id'] : 0;

if ($aula_a_editar != 0){
	switch($_POST['tipo']){
	case 1:
		$a = new aula($turma, $titulo, $data, $desc, $_POST['text'], $fundo, $_POST['tipo']);
		break;
	case 2:
		$a = new aula($turma, $titulo, $data, $desc, $_FILES['arqui'], $fundo, $_POST['tipo']);
		break;
	case 3:
		$a = new aula($turma, $titulo, $data, $desc, $_POST['link'], $fundo, $_POST['tipo']);
		break;
	}
	
	if ($a->temErro()){
		$_SESSION['erroAulas'] = $a->getErro();
	}else{
		$a->edita($aula_a_editar);
	}
	
} else switch($_POST['tipo']){
case 1:
	if(!isset($_POST['text'])) die("ERRO: o parametro de texto não foi passado corretamente. Voltar e tentar novamente deve consertar o erro.");
	if($_POST['text'] == "") die("Favor voltar e preencher a aula");
	
	$a = new aula($turma, $titulo, $data, $desc, $_POST['text'], $fundo, $_POST['tipo']);
	if ($a->temErro()){
		$_SESSION['erroAulas'] = $a->getErro();
	}else{
		$a->registra();
	}
	
	if(isset($_POST['forum']) and $_POST['forum'] == "sim"){// Aqui cria um tópico no fórum para discutir a aula
		require_once("../forum/sistema_forum.php");
		require_once("../forum/verifica_user.php");
		require_once("../forum/visualizacao_forum.php");

		$FORUM = new forum($turma);
		$FORUM->salvaMensagem(true, -1, $_SESSION['SS_usuario_id'], $titulo, $_POST['text']);
	}
	break;


case 2: // ARQUIVO
	if(!isset($_FILES['arqui'])) die("ERRO: o parametro do arquivo não foi passado corretamente. Voltar e tentar novamente deve consertar o erro.");
	require_once("../../file.class.php");
	
	$a = new aula($turma, $titulo, $data, $desc, $_FILES['arqui'], $fundo, $_POST['tipo']);
	if ($a->temErro())
		{$_SESSION['erroAulas'] = $a->getErro();}
	else
		{$a->registra();}
	
	if(isset($_POST['forum']) and $_POST['forum'] == "sim"){// Aqui cria um tópico no fórum para discutir a aula
		require_once("../forum/sistema_forum.php");
		require_once("../forum/verifica_user.php");
		require_once("../forum/visualizacao_forum.php");

		$FORUM = new forum($turma);
		$FORUM->salvaMensagem(true, -1, $_SESSION['SS_usuario_id'], $titulo, $desc." Arquivo: <a href=\"../downloadFile.php?fileId=".$a->fileId."\">".$_FILES['arqui']['name']."</a>");
	}
	break;



case 3: // LINK
	if(!isset($_POST['link'])) die("ERRO: o parametro link não foi passado corretamente. Voltar e tentar novamente deve consertar o erro.");
	if($_POST['link'] == "") die("Favor voltar e preencher o link");
	require_once("../../link.class.php");
	
	$a = new aula($turma, $titulo, $data, $desc, $_POST['link'], $fundo, $_POST['tipo']);
	if ($a->temErro())
		{$_SESSION['erroAulas'] = $a->getErro();}
	else
		{$a->registra();}
	
	if(isset($_POST['forum']) and $_POST['forum'] == "sim"){ // Aqui cria um tópico no fórum para discutir a aula
		require_once("../forum/sistema_forum.php");
		require_once("../forum/verifica_user.php");
		require_once("../forum/visualizacao_forum.php");

		$FORUM = new forum($turma);
		$FORUM->salvaMensagem(true, -1, $_SESSION['SS_usuario_id'], $titulo, $desc." Link: <a href=\"".$_POST['link']."\">".$_POST['link']."</a>");
	}
	break;


default:
	die("Favor voltar e enviar a aula novamente, algo de errado aconteceu.");
}
?>
<script>
	window.location = "ver_aulas.php?turma=<?=$turma?>";
</script>
