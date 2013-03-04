<?php
	session_start();

	require("../../cfg.php");
	require("../../bd.php");
	require("../../planeta.class.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Edição de planetas</title>	
</head>

<body>
<form method="get" action="manutencao_planetas.php">
Pesquisar por nome de planeta:&#9;<input type="text" name="nome" /> <input type="submit" value="Pesquisar"/><br />
Acentuação é importante.
</form>
<ul><div style="white-space:pre">
<?php
	//apaga um planeta no bd
	function apagar($id){		
		$planeta = new Planeta();		
		$planeta->excluirPlaneta((int)$id);				
?>
		<script language="javascript">
			location.href("manutencao_planetas.php");
		</script>
<?		
	}//fim funcao apagar
	
	if ($_GET['apagarId']){
		apagar($_GET['apagarId']);
	}

	global $tabela_planetas;
	$consulta = new conexao();
	$nome = $_GET['nome'];
	if($nome != null){
		$consulta->solicitar("SELECT * FROM $tabela_planetas WHERE Nome = '$nome'");
	} else {
		$consulta->solicitar("SELECT * FROM $tabela_planetas");
	}	
	for ($i=0;$i<count($consulta->itens);$i++){
		if ($i%2){ // Se for impar
			echo '	<li class="blog2">';
		}
		else { // É par. Derp.
			echo '	<li class="blog1">';
		}
?>
ID: <?=$consulta->resultado['Id']?>&#9;Tipo: <?=$consulta->resultado['Tipo']?>&#9;Nome: <?=$consulta->resultado['Nome']?>&#9;Terrenos: <?=$consulta->resultado['Terrenos']?>&#9;IdResponsavel: <?=$consulta->resultado['IdResponsavel']?>&#9;IdFilhos: <?=$consulta->resultado['IdFilhos']?>&#9;IdsPais: <?=$consulta->resultado['IdsPais']?>&#9; <a href="editar_planeta.php?id=<?=$consulta->resultado['Id']?>">Editar</a>&#9;<a href=<?="manutencao_planetas.php?apagarId=".$consulta->resultado['Id']?> >DELETAR ESTA CONTA</a>
<?php
	
		$consulta->proximo();
	}
	/*
	$p = new Planeta();
	$p->loadFromBd(14);
	if (is_int($p->getId())){
		echo "Id is int = true ";
	}
	else echo "Id is int = false ";
	
	echo $p->getId().NL;
	
	if (is_string($p->getNome())){
		echo "nome is string = true ";
	}
	else echo "nome is string = false ";
	
	echo $p->getNome().NL;
	
	if (is_int($p->getIdResponsavel())){
		echo "IdResponsavel is int = true ";
	}
	else echo "IdResponsavel is int = false ";
	
	echo $p->getIdResponsavel().NL;
	
	if (is_int($p->getTipo())){
		echo "Tipo is int = true ";
	}
	else echo "Tipo is int = false ";
	
	echo $p->getTipo().NL;
	
	if (is_int($p->getIdsPais())){
		echo "IdsPais is int = true ";
	}
	else echo "IdsPais is int = false ";
	
	echo $p->getIdsPais().NL;
	*/
?>
</ul></div>
</body>
</html>
