<?php
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../file.class.php");

//print_r($_POST);
//print_r($_FILES);
if (isset($_POST['edicao'])){
	if (is_numeric($_POST['edicao'])){
		$arraydono = array();
		foreach($_POST as $chave => $valor){
			if (is_numeric($chave))
				$arraydono[] = $chave;
		}
		$donos = implode(';', $arraydono);
		$consulta = new conexao(); global $tabela_blogs;
		$consulta->solicitar("UPDATE $tabela_blogs SET Title='".$_POST['titulo']."', OwnersIds='$donos' WHERE Id=".$_POST['edicao']);
	}else{
		die("Inteligente, mas n&atilde;o foi dessa vez."); // sql injection bro
	}
} else if (isset($_FILES['userfile']['size']) and $_FILES['userfile']['size'] > 0) {
	if (isset($_POST['descricao']) and isset($_POST['titulo']) and $_POST['descricao'] != '' and $_POST['titulo'] != '') {
		$arraydono = array();
		foreach($_POST as $chave => $valor){
			if (is_numeric($chave))
				$arraydono[] = $chave;
		}

		$donos = implode(';', $arraydono);

		$consulta = new conexao(); global $tabela_blogs;
		// cria o blog
		$consulta->solicitar("INSERT INTO $tabela_blogs (Title, OwnersIds, Tipo) VALUES ('".$_POST['titulo']."', '$donos', 2)");
		$blog_id = $consulta->ultimo_id();
		// insere o post
		$consulta->solicitar("INSERT INTO $tabela_posts (BlogId, Title, Text, IsPublic, Date) VALUES ($blog_id, 'Descrição', '".$_POST['descricao']."', 1, '".date("Y-m-d H:i:s")."')");
		// pega a imagem
		$fileName = $_FILES['userfile']['name'];
		$tmpName  = $_FILES['userfile']['tmp_name'];
		$fileSize = $_FILES['userfile']['size'];
		$fileType = $_FILES['userfile']['type'];
		$file = new File(TIPOBLOG, $blog_id,$fileName, $fileType, $fileSize, $tmpName);
		// Ok, finalmente se dá upload.
		$consulta->solicitar("INSERT INTO $tabela_imagem_blog VALUES ($blog_id, '".$file->getConteudoArquivo()."')");
		
		//print_r($consulta);

		//echo date(date("Y-m-d H:i:s"));
	} else die ("&Eacute; necess&aacute;rio um titulo e descricao para o blog.");
} else die ("&Eacute; necess&aacute;rio escolher um arquivo de imagem para o blog.");
?>
<script>history.go(-2);</script>
