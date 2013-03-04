<?php

session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../planeta.class.php");

if ($_GET['id'] != NULL){
	$id = (int)$_GET['id'];
	
	
	if ($_POST['botaoSubmit']){
	    
	
	}
	
	$planeta = new Planeta();
	$planeta->loadFromBd($id);
	$tipo = $planeta->getTipo();
	$nome = $planeta->getNome();
	$idResponsavel = $planeta->getIdResponsavel();
	$idPai = $planeta->getIdPai();
?>

	<form name="form" method="post" action="editar_planeta.php?id=<?=$id?>" > <BR />
		Tipo Planeta: <input type="text" name="tipo" value=<?=$tipo ?> /> <BR />
		Nome Planeta: <input type="text" name="nome" value=<?=$nome ?>  /><BR />
		IdResponsavel: <input type="text" name="responsavel"  value=<?=$idResponsavel ?> /><BR />
		IdPai: <input type="text" name="pai" value=<?=$idPai ?>  /><BR />
		
		<input type="submit" name="botaoSubmit" value="editar"/><BR />
	</form>

<?
	
}
?>