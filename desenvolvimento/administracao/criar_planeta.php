<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Criação de planetas</title>
	<script language="javascript">
		
	</script>
</head>
<body>
Acentuação é importante.
	<form name="form" method="post" action="criar_planeta.php" > <BR />
		Tipo Planeta: <input type="text" name="tipo" /> <BR />
		Nome Planeta: <input type="text" name="nome"/><BR />
		IdResponsavel: <input type="text" name="responsavel"/><BR />
		IdPai: <input type="text" name="pai"/><BR />
		
		<input type="submit" value="Criar"/><BR />
	</form>
	
	<?php
		require_once("../../cfg.php");
		require_once("../../bd.php");
		require_once("../../planeta.class.php");		
		
		if (($_POST['tipo']) and ($_POST['nome']) and ($_POST['responsavel']) and ($_POST['pai'])){
			
			$tipo = (int)$_POST['tipo'];
			$nome = $_POST['nome'];
			$responsavel= (int)$_POST['responsavel'];
			$pai= (int)$_POST['pai'];
			$planeta = new Planeta();
			$retorno = $planeta->createPlanet($tipo,$nome,$responsavel,$pai);
			if ($retorno === false){
				echo "planeta nao foi criado".NL;
			}
			if ($planeta->temErro()){
				echo $planeta->getErrosString();
			
			}
			
		}
	
	?>
		
</html>