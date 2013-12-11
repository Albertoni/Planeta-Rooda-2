<?php
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");	
	require_once("../../file.class.php");	
//	require_once("verifica_user.php");
	require_once("blog.class.php");
//	require_once("visualizacao_blog.php");
	
	//*************************************** TESTES =D **************************************************
	
	
	//***************************************************************************************************
	
	$blog_id = isset($_POST['blog_id']) ? $_POST['blog_id'] : die("nao foi fornecido id de blog");
	$coments = $_POST['comentario'];
	$blog = new Blog($blog_id);
	//$blog->getPost
	//inserir novo comentario

?>