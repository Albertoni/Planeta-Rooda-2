<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("../../file.class.php");

$turma = (int) $_POST['turma'];

if (isset($_POST['edicao'])){
	if (is_numeric($_POST['edicao'])){
		$arraydono = array();
		foreach($_POST as $chave => $valor){
			if (is_numeric($chave))
				$arraydono[] = $chave;
		}
		$donos = implode(';', $arraydono);
		$consulta = new conexao(); global $tabela_blogs;

		$tituloSafe = $consulta->sanitizaString($_POST['titulo']);
		$idSafe = $consulta->sanitizaString($_POST['edicao']);
		$donosSafe = $consulta->sanitizaString($donos);

		$consulta->solicitar("UPDATE $tabela_blogs SET Title='$tituloSafe', OwnersIds='$donosSafe' WHERE Id='$idSafe'");
	}else{
		die("Valor n&atilde;o v&aacute;lido na id do blog. Por favor volte e tente novamente."); // sql injection bro
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

		$tituloSafe = $consulta->sanitizaString($_POST['titulo']);
		$donosSafe = $consulta->sanitizaString($donos);
		$descricaoSafe = $consulta->sanitizaString($_POST['descricao']);

		// cria o blog
		$consulta->solicitar("INSERT INTO $tabela_blogs (Title, OwnersIds, Tipo, Turma) VALUES ('$tituloSafe', '$donosSafe', 2, '$turma')");
		$blog_id = $consulta->ultimo_id();
		// insere o post
		$consulta->solicitar("INSERT INTO $tabela_posts (BlogId, Title, Text, IsPublic, Date) VALUES ($blog_id, 'Descrição', '$descricaoSafe', 1, '".date("Y-m-d H:i:s")."')");
		// pega a imagem
		$fileName = $_FILES['userfile']['name'];
		$tmpName  = $_FILES['userfile']['tmp_name'];
		$fileSize = $_FILES['userfile']['size'];
		$fileType = $_FILES['userfile']['type'];
		$file = new File(TIPOBLOG, $blog_id,$fileName, $fileType, $fileSize, $tmpName);
		// Ok, finalmente se dá upload.
		$consulta->solicitar("INSERT INTO $tabela_imagem_blog VALUES ($blog_id, '".$file->getConteudoArquivo()."')");
		
	} else die ("&Eacute; necess&aacute;rio um titulo e descricao para o webf&oacute;lio.");
} else die ("&Eacute; necess&aacute;rio escolher um arquivo de imagem para o webf&oacute;lio.");
?>
<script>history.go(-2);</script>