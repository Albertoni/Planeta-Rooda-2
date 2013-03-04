<?
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
    require("../../login.class.php");	
//	require("verifica_user.php");
	require("blog.class.php");
	require("../../file.class.php");
	require("../../link.class.php");
//	require("visualizacao_blog.php");
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
