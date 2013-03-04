<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");	
	require("../../file.class.php");	
//	require("verifica_user.php");
	require("blog.class.php");
//	require("visualizacao_blog.php");
	
	//*************************************** TESTES =D **************************************************
	
	
	//***************************************************************************************************
	
	$blog_id = isset($_POST['blog_id']) ? $_POST['blog_id'] : die("nao foi fornecido id de blog");
	$coments = $_POST['comentario'];
	$blog = new Blog($blog_id);
	//$blog->getPost
	//inserir novo comentario

?>