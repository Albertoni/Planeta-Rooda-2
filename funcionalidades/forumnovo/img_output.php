<?php
	require("../../cfg.php");
	require("../../bd.php");

	$id = $_GET['id'];
	$fonte_mensagem_erro = "../../fonte_erros.ttf";


	// COMENTE ISSO ANTES DE DEBUGAR, MALANDRO!
////////////////////////////////////////////////////////////////////////////////
	error_reporting(0);
////////////////////////////////////////////////////////////////////////////////



	if (is_numeric($id) == false){
		die('RAAAAAAAAAA, pegadinha do Mallandro!'); // Sabe SQL injection?
	}

	$consulta = new conexao();
	$consulta->solicitar("SELECT imagem FROM $tabela_avatares WHERE id = '$id'");
	$image = imagecreatefromstring($consulta->resultado['imagem']);
	
	if ($image === false) {
		$nova = imagecreatetruecolor(66, 66);
		imagettftext($nova, 14, 0, 5, 20, imagecolorallocate($nova,255,255,255), $fonte_mensagem_erro, "USUARIO\nSEM\nIMAGEM."); // Em caso de erro...
	
	} else {// Processamento, resize.
		$nova = imagecreatetruecolor(66, 66);
		imagecopyresampled($nova, $image, 0, 0, 0, 0, 66, 66, imagesx($image), imagesy($image));
	}

	error_reporting(E_ALL);
	// Cuspindo a imagem. Sim, PNG.
	header('Content-type: image/png');
	imagepng($nova);
	imagedestroy($nova); // free(ram);
?>
