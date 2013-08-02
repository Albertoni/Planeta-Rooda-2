<?php
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
    require_once("../../login.class.php");	
//	require_once("verifica_user.php");
	require_once("blog.class.php");
	require_once("../../file.class.php");
	require_once("../../link.class.php");
//	require_once("visualizacao_blog.php");
	$usuario_id = $_SESSION['SS_usuario_id'];	
	
	$blog_id = isset($_GET['blog_id']) ? $_GET['blog_id'] : die("não foi fornecido id de blog");
	$blog_id = ($blog_id == "meu_blog") ? getMeuBlog() : $_GET['blog_id'];
	

	
	$blog = new Blog($blog_id);
	if(!$blog->getExiste())
		if($blog_id == $usuario_id)
			$blog->save();
		else
			die("Blog inexistente");
	$ini = isset($_GET['ini']) && $_GET['ini'] >= 0 ? floor($_GET['ini']/$blog->getPaginacao())*$blog->getPaginacao() : 0;
	$ini = $ini < 0 ? 0 : $ini;
	$ini = $ini > $blog->getSize() ? floor($blog->getSize()/$blog->getPaginacao())*$blog->getPaginacao() : $ini;
	?>
