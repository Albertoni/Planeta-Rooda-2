<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
//	require("verifica_user.php");
	require("blog.class.php");
//	require("visualizacao_blog.php");

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