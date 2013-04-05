<?php
header("Content-Type: application/json");
session_start();
require_once("cfg.php");
require_once("bd.php");
require_once("file.class.php");

$id_usuario = $_SESSION['SS_usuario_id'];
$nome_usuario = $_SESSION['SS_usuario_nome'];


$funcionalidade_id = $_GET['funcionalidade_id'];
$funcionalidade_tipo = $_GET['funcionalidade_tipo'];

$jsonArray = array();

if (is_numeric($funcionalidade_id) == false || is_numeric($funcionalidade_tipo) == false)
{
	$json['erros'][] = "Parametros invalidos"; // Sabe SQL injection?
}
else
{

	if(isset($_POST['upload']) && isset($_FILES['userfile']) && $_FILES['userfile']['size'] > 0)
	{
		$fileName = $_FILES['userfile']['name'];
		$tmpName  = $_FILES['userfile']['tmp_name'];
		$fileSize = (int) $_FILES['userfile']['size'];
		$fileType = $_FILES['userfile']['type'];

		$file = new File($funcionalidade_tipo, $funcionalidade_id,$fileName, $fileType, $fileSize, $tmpName);
		$file->upload();

		if($file->temErro())
		{
			$json['erros'] = $file->getErrosArray();
		}
		else
		{
			$json['file_id'] = $file->getId();
			$json['file_name'] = $file->getNome();
			$json['file_title'] = $file->getTitulo();
			$json['file_type'] = $file->getTipo();
			$json['file_size'] = $file->getTamanho();
			$json['file_author'] = $file->getAutor();
		}
	}
	else
	{
		$json['erros'][] = "Nenhum arquivo selecionado!";
	}
}
echo json_encode($json);
?>
