<?php
session_start();
require("../bd.php");
require("../cfg.php");
require("../usuarios.class.php");

$userId = (int)$_GET['userId'];
$user = new Usuario();
$user->openUsuario($userId);

?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="carteira.css" />
	</head>
	<body>
		<div id="carteira-usuario" style="background-color:<?=$user->getCorLuva()?>">
			<h2>Fulaninho</h2>
			<div id="descricao-usuario">
				<ul>
					<li><strong>Nome:</strong> <?=$user->getName()?></li>
					<li><strong>Email:</strong> <?=$user->getEmail()?></li>
					<li><strong>Turma:</strong> <?="VER NA REUNIÃO QUE TURMA DEVE SER EXIBIDA AQUI"?></li>
					<li><strong>Gosto:</strong> <?=$user->getGosto()?></li>
					<li><strong>Não gosto:</strong> <?=$user->getNaoGosto()?></li>
				</ul>
			</div>
			<div id="imagem-carteira">
				<img id="img-cart-1" src="fadeout1.png" alt="Imagem do avatar do usu&aacute;rio" border="0" />
				<img id="img-cart-2" src="fadeout2.jpg" alt="Imagem real do usu&aacute;rio" border="0" />
			</div>
			<button onclick="clearInterval(idIntervaloFoto); idIntervaloFoto = window.setInterval(trocaFoto, 10);">VAI!</button>
		</div>
	</body>
</html>
