<?php
	require("../../planeta.class.php");
	//$p = new Planeta();
	//$p->loadFromBd("fun");
	//$p->createPlanet(2,"ahpara",72,"20,14");
	//$p->excluirPlaneta("ahpara");
	/*if ($p->temErro()){
		echo $p->getErrosString();
	
	}*/
	//$s = $p->stringTerrenoToArray("1,2:100;3,4:101  ; 5    ,6:102");
	/*
	echo $p->getId()."<BR/>";
	echo $p->getNome()."<BR/>";
	$s = $p->getTerrenosArray();
	for ($j = 0 ; $j < count($s) ; $j++){
		$temp = "";
		for($i = 0 ; $i < count($s[0]) ; $i++){
			$temp.=$s[$j][$i]."  ";
		}
		echo $temp."<BR />";
	}*/
	//$p->clear();
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
