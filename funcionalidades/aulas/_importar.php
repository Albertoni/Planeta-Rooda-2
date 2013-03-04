<?php

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("aula.class.php");

session_start();
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

$permissoes = checa_permissoes(TIPOAULA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if(!$usuario->podeAcessar($permissoes['aulas_importarAulas'], $turma)){
	$host	=	$_SERVER['HTTP_HOST'];
	$uri	=	rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: http://$host$uri/planeta_aulas.php");
}


if (!isset($_SESSION['SS_usuario_nivel_sistema'])) // if not logged in
	die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");

$aulas = array();
foreach($_POST as $chave => $valor){
	if ($chave === "t") $turma = $valor;
	else $aulas[] = $valor;
}

foreach ($aulas as $a){
	$puxa = new aula();
	$puxa->abreAula($a);
	
	if($puxa->temErro()){
		die($puxa->getErro());
	}else{
		$devolve = new aula($turma, $puxa->getTitulo(), $puxa->getData(), $puxa->getDesc(), $puxa->getMaterial(), $puxa->getFundo(), $puxa->getTipo(), $puxa->getAutor());
		$devolve->registra();
	}
}
?>
<script>
	window.location = "ver_aulas.php?turma=<?=$turma?>";
</script>
