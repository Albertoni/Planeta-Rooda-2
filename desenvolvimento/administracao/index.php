<?php
	require("../../planeta.class.php");
	$p = new Planeta();
	$p->clear();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Página principal</title>
	<style type="text/css">
		li {list-style-type: square; text-align:center;}
	</style> 
</head>

<body>
<ul>
	<li><a href="aprovar_contas.php">Aprovar Contas</a></li>
	<li><a href="criar_turma.php">Criar Turma</a></li>
	<li><a href="manutencao_turmas.php">Editar/Apagar Turmas</a></li>
	<li><a href="manutencao_blogs.php">Editar/Apagar Blogs</a></li>
	<li><a href="manutencao_contas.php">Editar/Apagar Contas</a></li>
	<li><a href="criar_planeta.php">Criar planeta</a></li>
	<li><a href="manutencao_planetas.php">Editar/Apagar planetas</a></li>
</ul>
</body>
</html>
