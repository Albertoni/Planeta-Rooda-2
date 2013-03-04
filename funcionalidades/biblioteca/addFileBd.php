<?php
session_start();
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../file.class.php");
require_once("../../link.class.php");

$envia_titulo = $_POST['envia_titulo'];
$envia_autor = $_POST['envia_autor'];
$envia_tags = $_POST['envia_tags'];

switch($_POST['e_material']){
	case "arquivo":			
		$fileName = $_FILES['userfile']['name'];
		$tmpName  = $_FILES['userfile']['tmp_name'];
		$fileSize = $_FILES['userfile']['size'];
		$fileType = $_FILES['userfile']['type'];				
		echo $fileName.NL.$tmpName.NL.$fileSize.NL.$fileType.NL;								
		$file = new File(TIPOBIBLIOTECA,1,$fileName, $fileType, $fileSize, $tmpName);
		
		//$file->setExtraInfo($envia_titulo, $envia_autor, $envia_tags);
		$file->setTitulo($envia_titulo);
		$file->setAutor($envia_autor);
		$file->setTags($envia_tags);
		
		$file->upload();	
		
		if ($file->temErro()){
			echo $file->getErrosString();				
		}
		else echo "1";

	
	break;
	case "link":
		$endereco_link = $_POST['endereco_link'];
		$link = new Link($endereco_link, TIPOBIBLIOTECA,1);
		$link->setTitulo($envia_titulo);
		$link->setAutor($envia_autor);
		$link->setTags($envia_tags);
		$link->upload();
	
	break;
}





?>