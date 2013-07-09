<?php
header("Content-Type: application/json");
session_start();
require_once("cfg.php");
require_once("bd.php");
require_once("arquivo.class.php");

$idUsuario = isset($_SESSION['SS_usuario_id']) ? $_SESSION['SS_usuario_id'] : 0;
$nomeUsuario = isset($_SESSION['SS_usuario_nome']) ? $_SESSION['SS_usuario_nome'] : "";

$idFuncionalidade = isset($_GET['funcionalidade_id']) ? (int) $_GET['funcionalidade_id'] : 0;
$tipoFuncionalidade = isset($_GET['funcionalidade_tipo']) ? (int) $_GET['funcionalidade_tipo'] : 0;

$jsonArray = array();
if ($idUsuario <= 0){
	$json['errors'][] = "Você não está mais logado. Por favor, autentique-se novamente.";
} else {
	if ($idFuncionalidade <= 0 || $tipoFuncionalidade <= 0)
	{
		$json['errors'][] = "Parametros invalidos"; // Sabe SQL injection?
	}
	else
	{

		if(isset($_POST['upload']) && isset($_FILES['userfile']) && $_FILES['userfile']['size'] > 0)
		{
			$fileName = $_FILES['userfile']['name'];
			$tmpName  = $_FILES['userfile']['tmp_name'];
			$fileSize = (int) $_FILES['userfile']['size'];
			$fileType = $_FILES['userfile']['type'];
			$titulo = isset($_POST['arquivo_titulo']) ? $_POST['arquivo_titulo'] : false;
			$autor = isset($_POST['arquivo_autor']) ? $_POST['arquivo_autor'] : false;

			$arquivo = new Arquivo();
			$arquivo->setArquivo($_FILES['userfile']);
			$arquivo->setFuncionalidade($tipoFuncionalidade, $idFuncionalidade);
			$arquivo->setIdUploader($idUsuario);
			
			if ($tags) $arquivo->setTags($tags);
			if ($titulo) $arquivo->setTitulo($titulo);
			if ($autor) $arquivo->setAutor($autor);
			
			if ($arquivo->temErros()) {
				$json['erros'] = $arquivo->getErros();
			} else {
				$arquivo->salvar();
			}
		}
		else
		{
			$json['errors'][] = "Nenhum arquivo selecionado!";
		}
	}
}
echo json_encode($json);