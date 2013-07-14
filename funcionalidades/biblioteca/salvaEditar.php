<?php
// !SQLINJECTION
	require_once("biblioteca.inc.php");
	require_once("../cfg.php");		
	require_once("../db.inc.php");

	$codUsuario   = $_SESSION['SS_usuario_id'];
	$codTurma     = $_SESSION['SS_terreno_id'];
	$associacao   = "A";
	
	$titulo = $_POST['titulo'];
	$palavras = $_POST['palavras'];
	$link = $_POST['link'];	
	
	$codMaterial = $_GET['t'];
	$tipo = $_GET['a'];	
	
	if($tipo=='l'){
		$update="UPDATE BibliotecaMateriais SET  titulo=\"$titulo\" ,palavras=\"$palavras\" ,material=\"$link\"
		WHERE codMaterial=$codMaterial"; 
	}else{
		$update="UPDATE BibliotecaMateriais SET  titulo=\"$titulo\" ,palavras=\"$palavras\"
		WHERE codMaterial=$codMaterial"; 
	}
	db_faz($update);
	
	echo "<script>window.location=('index.php')</script>";
?>