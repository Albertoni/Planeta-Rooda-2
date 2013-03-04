<?php
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../file.class.php");

$funcionalidade_id = $_GET['funcionalidade_id'];
$funcionalidade_tipo = $_GET['funcionalidade_tipo'];

if (is_numeric($funcionalidade_id) == false || is_numeric($funcionalidade_tipo) == false){
		die('RAAAAAAAAAA, pegadinha do Mallandro!'); // Sabe SQL injection?
	}

if(isset($_POST['upload']) && $_FILES['userfile']['size'] > 0){
	$fileName = $_FILES['userfile']['name'];
	$tmpName  = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];
	
	
	echo("funcTipo=".$funcionalidade_tipo.NL);
	echo("funcID=".$funcionalidade_id.NL);
	echo("name=".$fileName.NL);
	echo("tipe=".$fileType.NL);
	echo("size=".$fileSize.NL);
	echo("tmp=".$tmpName.NL);
	
	
	$file = new File($funcionalidade_tipo, $funcionalidade_id,$fileName, $fileType, $fileSize, $tmpName);
	$file->upload();
	if ($file->temErro()){
		echo($file->getErrosString());
		$sucesso = 0;
		$location = '#';
	}
	else{
		echo("upload com sucesso".NL);
		
		$sucesso = 1;
		$location='downloadFile_certo.php?id=' . $file->getId();
	}	
	
} else die("uma morte horrivel e bonita");

	// Joao: Dando echo dentro do PHP pra nao desalocar as variaveis.
	echo "<script type='text/javascript'>
	// Adicionado: Joao, 17/03, passa o nome do arquivo e o sucesso (ja que sempre vai passar o nome) pra atualizar a lista do blog
	window.top.window.imprimeNomeArquivo($sucesso, \"$fileName\", \"$location\");
</script>";?>
