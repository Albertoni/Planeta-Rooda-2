<?php
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
	require_once("../../arquivo.class.php");
//	require_once("verifica_user.php");
	require_once("blog.class.php");
//	require_once("visualizacao_blog.php");

	if($_POST['text'] == "" or $_POST['title'] == ""){ // Isso causa problemas muito fofos.
		die("&Eacute; necess&aacute;rio que haja texto tanto no t&iacute;tulo quanto no post em si. Por favor <a href=\"javascript:history.go(-1)\">volte</a> para tentar novamente.");
	}

	$turma = ((isset($_POST['turma'])) ? $_POST['turma'] : die("N&atilde;o foi passada uma id de turma para a p&aacute;gina."));

	// SQL injections!
	if (is_numeric($_POST['post_id']) and is_numeric($_POST['blog_id'])) {
		
		$consulta = new conexao(); // Para poder usar a escape string ali embaixo, precisa de uma conexão aberta
		
		$blog_id = isset($_POST['blog_id']) ? (int)$_POST['blog_id'] : die("não foi fornecido id de blog");
		$blog = new Blog($blog_id, $turma);
		$usuario_id = $_SESSION['SS_usuario_id'];
		$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
		$post = new Post($post_id,$blog->getId(),$usuario_id,str_replace("\n", " ", $_POST['title']),$consulta->sanitizaString($_POST['text']),$_POST['is_public'],date("Y-m-d H:i:s"));
		$post->save();
		// um ou mais arquivos
		$arquivos = Arquivo::bulkFiles($_FILES['file'], (int) $usuario_id);
		foreach ($arquivos as $a) {
			// verificar se o arquivo foi salvo com sucesso
			if ($a->getId()) {
				$consulta->solicitar("INSERT INTO BlogArquivos (idArquivo, idPost) VALUES ({$a->getId()}, {$post->getId()})");
			}
		}
		if ($_POST['tags'] != ""){ // Se for um post novo ou uma edição de algo que não tenha tags, as tags serão inseridas.
			$consulta->solicitar("INSERT INTO $tabela_tags VALUES (".$post->getId().",".$_POST['blog_id'].",'".$consulta->sanitizaString(strtolower($_POST['tags']))."')");
			if ($consulta->erro != ""){ // agora, se for uma edição de algo COM tags, a anterior dá erro e aí atualiza. Espero.
				$consulta->solicitar("UPDATE $tabela_tags SET Tags='".$consulta->sanitizaString(strtolower($_POST['tags']))."' WHERE Id=".$post->id);
			}
		}
	} else {
		die("Por favor volte e tente novamente, algum erro desconhecido ocorreu");
	}
?>
	<script language="javascript">
		// history.go(-2)
	</script>

