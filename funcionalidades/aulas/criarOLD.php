<?php
session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>quelque chose</title>
	<link type="text/css" rel="stylesheet" href="aulas.css" />
	<script type="text/javascript" src="aulas.js"></script>
</head>
<body>
	<h2>TROCAR DE TURMA</h2>
	<form name="troca_turma" method="get">
		<select style="vertical-align:middle" name="turma">
<?php selecionaTurmas($turma); ?>
		</select>
		<img style="vertical-align:middle; height:25px; padding-left:100px; cursor:pointer" src="../../images/botoes/bt_confirmar.png" onclick="troca_turma.submit()"/>
	</form>

	<h2>Criar aula</h2>
	<form enctype="multipart/form-data" method="post" action="_criaAula.php" onsubmit="return validaForm(this);">
		<input type="hidden" name="turma" value="<?=$turma?>">
		<ul>
			<li>Título da aula: <input type="text" name="titulo" />
			<li>Data da aula: <input type="text" name="data" />
			<li>Descrição curta da aula: (<span id="contador">0</span>/500 caracteres)<br />
				<textarea name="desc" cols=80 rows=10 onkeypress="descCurta(this);"></textarea><br />
			<li>Criar um tópico do fórum e vincular a aula a ele? <input type="checkbox" name="forum" value="sim" />
			<li>Tipo da aula:
				<select name="tipo" onchange="mudaInput(this)">
					<option value="1">Montar página</option>
					<option value="2">Enviar arquivo</option>
					<option value="3">Endereço web</option>
				</select><br />
			<p id="g">
				Editor de texto:<br />
				<textarea id="text" name="aula" cols=80 rows=10>ESSA NÃO É A VERSÃO FINAL DO EDITOR
A VERSÃO FINAL TERÁ MAIS FIRULAS!</textarea>
			</p>
			<input type="submit" value="Confirmar" />
			<br /><br />
			ISSO NÃO FAZ NADA NO MOMENTO, FAVOR DEIXAR COMO 1: <input type="text" name="fundo" value="1">
		</ul>
	</form>
</body>
</html>
