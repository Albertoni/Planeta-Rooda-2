<?php
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../file.class.php");

//print_r($_FILES);
print_r($_POST);

if($_FILES['gambiarquivo']['size'] > 0){
	$fileName = $_FILES['gambiarquivo']['name'];
	$tmpName  = $_FILES['gambiarquivo']['tmp_name'];
	$fileSize = $_FILES['gambiarquivo']['size'];
	$fileType = $_FILES['gambiarquivo']['type'];
	
	$falha = "";
	
	echo("name=".$fileName.NL);
	echo("type=".$fileType.NL);
	echo("size=".$fileSize.NL);
	echo("tmp=".$tmpName.NL);
	
	$falha.=$tmpName."|";
	$falha.=$fileSize."|";
	$falha.=$fileType."|";
	$falha.=$fileName."|";
	
	$file = new File(TIPOPERGUNTA, $_SESSION['SS_usuario_id'],$fileName, $fileType, $fileSize, $tmpName);
	$file->upload();
	if ($file->temErro()){
		echo($file->getErrosString());
		$falha .= $file->getErrosString();
		
		$location = '#';
	}else{
		echo("upload com sucesso".NL);
		
		global $tabela_arquivos;
		
		$consulta = new conexao();
		$consulta->solicitar("SELECT arquivo_id FROM $tabela_arquivos WHERE nome = '$fileName'");
		$falha = 0;

		$falha = print_r($consulta, true);
	}
}

echo "<script type='text/javascript'>
	window.top.window.previewArquivo(\"$falha\", \"".$consulta->resultado['arquivo_id']."\", \"".$_POST['gambiid']."\");
</script>";
?>
