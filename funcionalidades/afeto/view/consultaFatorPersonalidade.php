<?php
	$ARQUIVO_PHP_CONTROLLER = "../controller/controllerConsultaFatorPersonalidade.php";

?>
<html>
	<head></head>
	<body>
		Consultar Fatores de Personalidade
		<br><br><br>
		
		<form method="post" action="<?=$ARQUIVO_PHP_CONTROLLER?>">
			<label for="nomeUsuario">Nome do Usu&aacute;rio:</label>
			<input type="text" id="nomeUsuario" name="nomeUsuario" maxlength=512 size=80 />
			<br>
			<input type="submit" value="Consultar">
		</form>
	</body>
</html>