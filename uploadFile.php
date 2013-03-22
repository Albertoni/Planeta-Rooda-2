<!DOCTYPE html>
<html>
<head>
	<title>Envio de Arquivo</title>
</head>
<body>
<ul id="file_info">
<?php
session_start();
require_once("cfg.php");
require_once("bd.php");
require_once("file.class.php");

$id_usuario = $_SESSION['SS_usuario_id'];
$nome_usuario = $_SESSION['SS_usuario_nome'];


$funcionalidade_id = $_GET['funcionalidade_id'];
$funcionalidade_tipo = $_GET['funcionalidade_tipo'];

if (is_numeric($funcionalidade_id) == false || is_numeric($funcionalidade_tipo) == false){
	die('<div id="erros">Parametros invalidos</div>'); // Sabe SQL injection?
}

if(isset($_POST['upload']) && $_FILES['userfile']['size'] > 0)
{
	$fileName = $_FILES['userfile']['name'];
	$tmpName  = $_FILES['userfile']['tmp_name'];
	$fileSize = (int) $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];

	$file = new File($funcionalidade_tipo, $funcionalidade_id,$fileName, $fileType, $fileSize, $tmpName);
	$file->upload();

	if($file->temErro())
	{
		$erros = $file->getErrosString();
		echo "<li><span id=\"erros\">$erros</span></li>";
	}
	else
	{
		$arquivo_id = $file->getId();
		$arquivo_nome = $file->getNome();
		$arquivo_titulo = $file->getTitulo();
		$arquivo_tipo = $file->getTipo();
		$arquivo_tamanho = $file->getTamanho();
		$arquivo_autor = $file->getAutor();
		echo "<li>id: <span id=\"arquivo_id\">$arquivo_id</span></li>";
		echo "<li>nome: <span id=\"arquivo_nome\">$arquivo_nome</span></li>";
		echo "<li>titulo: <span id=\"arquivo_titulo\">$arquivo_titulo</span></li>";
		echo "<li>tipo: <span id=\"arquivo_tipo\">$arquivo_tipo</span></li>";
		echo "<li>tamanho: <span id=\"arquivo_tamanho\">$arquivo_tamanho</span></li>";
		echo "<li>autor: <span id=\"arquivo_autor\">$arquivo_autor</span></li>";
	}
}
else
{
	echo "<li id=\"erros\">Nenhum arquivo selecionado!</li>";
}
?>
</ul>
</body>
</html>
