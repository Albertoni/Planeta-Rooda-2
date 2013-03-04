<form method="post">
	Novo Link: <input name="novoLink" id="novoLink" type="text"/>
	<input name="submit" type="submit" id="submit" value="Submit" />
</form>


<?php	
	require("../../cfg.php");
	require("../../bd.php");	
	require("../../usuarios.class.php");
	require("../../link.class.php");
	require("blog.class.php");
	
	//if(isset($_POST['submitButton'])){
	if(isset($_POST['submit'])){
	
		$endereco = $_POST['novoLink'];
		$funcionalidade_tipo = $_GET['funcionalidade_tipo'];
		$funcionalidade_id = $_GET['funcionalidade_id'];
		echo ("->$endereco  $funcionalidade_tipo  $funcionalidade_id".NL);
	
		$link = new Link($endereco, $funcionalidade_tipo, $funcionalidade_id);
		if ($link->temErro()){
			echo($link->getErrosString());
		}
		else echo("link adicionado: $endereco"); 
	}
	
?>
