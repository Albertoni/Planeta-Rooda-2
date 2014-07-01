<?php
	require_once("cfg.php");
	require_once("bd.php");
	
	$id = $_GET['file'];
	$fonte_mensagem_erro = "../../fonte_erros.ttf";
	
	
	// COMENTE ISSO ANTES DE DEBUGAR, MALANDRO!
////////////////////////////////////////////////////////////////////////////////
	error_reporting(0);
////////////////////////////////////////////////////////////////////////////////
	
	
	
	if (is_numeric($id) == false){
		die('RAAAAAAAAAA, pegadinha do Mallandro!'); // Sabe SQL injection?
	}

	$consulta = new conexao();
	$consulta->connect();
	if (isset($_GET['blogpic']) and $_GET['blogpic'] == 1) { // Para listagens de blogs. Pega do blog_imagens
		$consulta->solicitar("SELECT imagem FROM $tabela_imagem_blog WHERE id = '$id'");
		$image = imagecreatefromstring($consulta->resultado['imagem']);
        if($image===false){
            $image= imagecreatefrompng("images/desenhos/stubAnonimo.png");
        }
		// Processamento, resize.
		$nova = imagecreatetruecolor(66, 66);
		imagecopyresampled($nova, $image, 0, 0, 0, 0, 66, 66, imagesx($image), imagesy($image));


	} else if (isset($_GET['userpic']) and $_GET['userpic'] == 1) { // pega do avatar_usuario, usado dentro de blogs
	$consulta->solicitar("SELECT imagem FROM $tabela_avatares WHERE id = '$id'");
		$image = imagecreatefromstring($consulta->resultado['imagem']);
		
		if ($image === false) {
            $image= imagecreatefrompng("images/desenhos/stubAnonimo.png");
            if(isset($_GET['forum']) and $_GET['forum']==1){
                //($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
                $nova = imagecreatetruecolor(66,66);
                imagecopyresized($nova, $image, 0, 0, 0, 0, 66, 66, imagesx($image), imagesy($image));
            }
            else if(isset($_GET['forum']) and $_GET['forum']==0){
                //($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
                $nova = imagecreatetruecolor(192,192);
                imagecopyresized($nova, $image, 0, 0, 0, 0, 192, 192, imagesx($image), imagesy($image));
            }

		} else {// Processamento, resize.
            if(isset($_GET['forum']) and $_GET['forum']==1){
			    $nova = imagecreatetruecolor(66, 66);
                imagecopyresized($nova, $image, 0, 0, 0, 0, 66, 66, imagesx($image), imagesy($image));
            }
            else if(isset($_GET['forum']) and $_GET['forum']==0){
                $nova = imagecreatetruecolor(192, 192);
            }

		}


	} else { // da tabela de arquivos.
		$consulta->solicitar("SELECT arquivo FROM $tabela_arquivos WHERE arquivo_id = '$id'");

		// MAGIA!!!
		$image = imagecreatefromstring($consulta->resultado['arquivo']);

		// Processamento, resize.
		$nova = imagecreatetruecolor(75, 44);
		imagecopyresampled($nova, $image, 0, 0, 0, 0, 75, 44, imagesx($image), imagesy($image));
	}
	
	error_reporting(E_ALL);
	// Cuspindo a imagem. Sim, PNG.
	header('Content-type: image/png');
	imagepng($nova);
	imagedestroy($nova); // free(ram);
?>
