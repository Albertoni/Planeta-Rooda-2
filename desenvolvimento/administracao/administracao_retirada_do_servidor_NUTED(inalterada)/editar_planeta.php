<?php

session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../planeta.class.php");

if ($_GET['id'] != NULL){
	$id = (int)$_GET['id'];	
	
	$planeta = new Planeta();
	$planeta->loadFromBd($id);
	$tipo = $planeta->getTipo();
	$nome = $planeta->getNome();
	$idResponsavel = $planeta->getIdResponsavel();
	$teste = $planeta->getIdsPais();
	$idsPais = implode(",",$planeta->getIdsPais());
	if($planeta->temErro()){
		echo $planeta->getErrosString();	
	}
	//echo $teste[0]."   ".$teste[1]."   ".$teste[2]."   ".$teste[3]."   ";
?>

	<form name="form" method="post" action="editar_planeta.php" > <BR />
		
		Nome Planeta: <input type="text" name="nome" value=<?=$nome ?>  /><BR />
		IdResponsavel: <input type="text" name="responsavel"  value=<?=$idResponsavel ?> /><BR />
		IdsPais: <input type="text" name="pais" value=<?=$idsPais ?>  /><BR />
		<input type="hidden" name="id" value=<?=$id?> />
		<input type="submit" name="botaoSubmit" value="editar"/><BR />
	</form>

<?	
}

if ($_POST['botaoSubmit']){
	$id = 			 (int)$_POST['id'];
	$nome = 		 $_POST['nome'];
	$idResponsavel = (int)$_POST['responsavel'];
	$idsPais = 		 $_POST['pais'];
	
	$planeta = new Planeta();
	$planeta->loadFromBd($id);
	if ($planeta->temErro()){
		echo $planeta->getErrosString();
	}
	$planeta->editarPlaneta($nome,$idResponsavel,$idsPais);
	if ($planeta->temErro()){
		echo $planeta->getErrosString();
	}
	else echo "planeta editado com sucesso!";
	
}


?>