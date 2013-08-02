<?php
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
//	require_once("verifica_user.php");
	require_once("blog.class.php");
//	require_once("visualizacao_blog.php");

	$post = new Post();
	$post->open("3");
	print_r($post);
	$post->setTitle("Mais um post sobre o mais lindo!");	
	$post->setId(0);
	print_r($post);	
	$post->save();
	echo "<br>" . $post->getId();
	die();
	
?>