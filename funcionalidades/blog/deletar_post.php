<?php
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

global $tabela_posts;
global $tabela_tags;

$usuario_id = $_SESSION['SS_usuario_id'];
$turma   = (int)$_GET['turma'];
$blog_id = (int)$_GET['blog_id'];
$post_id = (int)$_GET['post_id'];
$erro = "";

$permissoes = checa_permissoes(TIPOBLOG, $turma);
	if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

$user = new Usuario();
$user->openUsuario($usuario_id);

if($user->podeAcessar($permissoes['blog_excluirPost'], $turma)){
	$consulta = new conexao();
	$consulta->solicitar("DELETE FROM $tabela_posts WHERE Id=$post_id");
	$erro = $consulta->erro;
	
	$consulta->solicitar("DELETE FROM $tabela_tags  WHERE Id=$post_id");
	$erro .= $consulta->erro;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
	history.go(-1);
</script>
</head>
<body>
O seu navegador está com JavaScript desligado ou algum erro ocorreu.
Por favor <a href="blog.php?blog_id=<?=$blog_id?>&amp;turma=<?=$turma?>">clique aqui</a> para voltar.
</body>
</html>
