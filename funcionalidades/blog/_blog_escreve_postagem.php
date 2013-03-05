<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
//	require("verifica_user.php");
	require("blog.class.php");
//	require("visualizacao_blog.php");

	if ($_POST['text'] == "" or $_POST['title'] == ""){ // Isso causa problemas muito fofos.
		die("&Eacute; necess&aacute;rio que haja texto tanto no t&iacute;tulo quanto no post em si. Por favor <a href=\"javascript:history.go(-1)\">volte</a> para tentar novamente.");
	}

	// SQL injections!
	if (is_numeric($_POST['post_id']) and is_numeric($_POST['blog_id'])) {
		
		$consulta = new conexao(); // Para poder usar a escape string ali embaixo, precisa de uma conexão aberta
		
		$blog_id = isset($_POST['blog_id']) ? (int)$_POST['blog_id'] : die("não foi fornecido id de blog");
		$blog = new Blog($blog_id);
		$usuario_id = $_SESSION['SS_usuario_id'];
		$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
		$post = new Post($post_id,$blog->getId(),$usuario_id,str_replace("\n", " ", $_POST['title']),mysql_real_escape_string($_POST['text']),$_POST['is_public'],date("Y-m-d H:i:s"));	
		$post->save();
	
		
		if ($_POST['tags'] != ""){ // Se for um post novo ou uma edição de algo que não tenha tags, as tags serão inseridas.
			$consulta->solicitar("INSERT INTO $tabela_tags VALUES (".$post->id.",".$_POST['blog_id'].",'".mysql_real_escape_string(strtolower($_POST['tags']))."')");
			if ($consulta->erro != ""){ // agora, se for uma edição de algo COM tags, a anterior dá erro e aí atualiza. Espero.
				$consulta->solicitar("UPDATE $tabela_tags SET Tags='".mysql_real_escape_string(strtolower($_POST['tags']))."' WHERE Id=".$post->id);
			}
		}
	} else {
		die("Por favor volte e tente novamente, algum erro desconhecido ocorreu");
	}
?>
	<script language="javascript">
		history.go(-2)
	</script>
