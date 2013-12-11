<?php
require_once("../turma.class.php");
require_once("../usuarios.class.php");

session_start();

if (!isset($_SESSION['SS_usuario_id'])){ // Se isso não estiver setado, o usuario não está logado
	die("<a href=\"index.php\">Por favor volte e entre em sua conta.</a>");
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- CSS -->
		<link href="menuAdministracao.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div id="containerMenu">
			<div id="centraliza">
				<div id="infoTurma">
					<img src="images/admin/cadastro.png">
					<br>
					<a href="cadastro_usuario.php"><div class="esquerda" id="usuario"></div></a>
					<a href="cadastro_turma.php"><div class="direita" id="turma"></div></a>
					<br><br><br><br><br>
					<img src="images/admin/gerencia.png">
					<br>
					<a href="gerencia_usuarios.php"><div class="esquerda" id="usuarios"></div></a>
					<a href="gerencia_turmas.php"><div class="direita" id="turmas"></div></a>
				</div>
			</div>
		</div>
	</body>
</html>
