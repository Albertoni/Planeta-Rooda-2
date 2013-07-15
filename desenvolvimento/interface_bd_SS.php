<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../file.class.php");
require_once("../bd.php");
require("../funcoes_aux.php");


$id 		= $_SESSION['SS_usuario_id'];
$string_img = $_POST['img'];			//string_img contém as cores de todos os pixels, separadas por vírgulas, começando no ponto superior esquerdo da imagem e varrendo linha a linha.
$height_img = (int)$_POST['height_img'];
$width_img  = (int)$_POST['width_img'];
			
																								//print_r($width_img);
//algoritmo encontrado em (http://www.sephiroth.it/tutorials/flashPHP/print_screen/page002.php) no dia 02.09.11 às 09:32.
$img_jpeg = imagecreatetruecolor($width_img, $height_img);
					
// aqui é preciso processar string_img para converte-la em pixels
// converte-se a string em um array de 192*192 elementos
$array_pixels = explode(",", $string_img);
																								//print_r($array_pixels[15269]);
// varre-se as linhas da imagem, convertendo-as em pixels
for($coluna = 0; $coluna < $height_img; $coluna++){
	// preparar array para organizar pixels da imagem
	for($num_vezes = 0; $num_vezes < $width_img; $num_vezes++){//a imagem vinda do flash é deformada. 
		//este trecho reconstitui a imagem.
		$posicao_pixel_acima_no_array = ($width_img * $coluna) + 0 + $num_vezes;
		$posicao_pixel_abaixo_no_array = ($width_img * $coluna) + $coluna + $num_vezes;
		
		$temp = $array_pixels[$posicao_pixel_acima_no_array];
		$array_pixels[$posicao_pixel_acima_no_array] = $array_pixels[$posicao_pixel_abaixo_no_array];
		$array_pixels[$posicao_pixel_abaixo_no_array] = $temp;
	}
	for($linha = $width_img-1; $linha >= 0 ; $linha--){
		// encontrar a cor do pixel
		$posicao_pixel_no_array = ($width_img * $coluna) + $linha;
		$cor_pixel_string = $array_pixels[$posicao_pixel_no_array];
																								//print_r("pos:" . $posicao_pixel_no_array . ",");
		// consistência, pode haver pixels sem cor especificada (estes deverão ter o verde do fundo)
		if($cor_pixel_string != ""){
			// nova consistência, um valor hexadecimal precisa ter 6 dígitos
			$cor_pixel_hexadecimal = $cor_pixel_string;
			while(strlen($cor_pixel_hexadecimal) < 6){
				$cor_pixel_hexadecimal = "0" . $cor_pixel_hexadecimal;
			}
			// conversão para rgb
			$r = hexdec(substr($cor_pixel_hexadecimal, 0, 2));
			$g = hexdec(substr($cor_pixel_hexadecimal, 2, 2));
			$b = hexdec(substr($cor_pixel_hexadecimal, 4, 2));
			
			// elimina ruído "branco"
			if($r == 16 and $g == 0 and $b == 0 ){
				$r = 255;
				$g = 255;
				$b = 255;
			}
																	//print_r("r:" .$r . "," . "g:" .$g . "," . "b:" .$b . "|||");
			// cada cor precisa ser alocada somente uma vez, tornar isto mais eficiente
			$alocador_cor = imagecolorallocate($img_jpeg, 255 - $r, 255 - $g, 255 - $b);
			// finalmente, coloca a cor na imagem, em sua posição certa
			imagesetpixel($img_jpeg, $coluna, $linha, $alocador_cor);
		}
	}
}

$name = tempnam("/tmp", "avimg");

imagejpeg($img_jpeg, $name, 100);

$file	= fopen($name, 'rb');
$fileContent = fread($file, filesize($name));
$fileContent = addslashes($fileContent);
//$this->fileContent = $fileContent;
fclose($file);

/*---------------------------------------------------
 *	Salvar JPEG gerado no BD.
---------------------------------------------------*/  

	$bd = new conexao();
	$bd->solicitar("SELECT id FROM avatar_usuario WHERE id=$id");
	/*echo "<pre>";
	print_r($bd);
	echo "</pre>";*/
	if($bd->registros == 0){
		$bd->solicitar("INSERT INTO avatar_usuario (id, imagem) values ($id, '".$fileContent."')");
	}
	else{
		$bd->solicitar("UPDATE avatar_usuario SET imagem='".$fileContent."' WHERE id=$id");
	}

// usado para debug 
//header("Content-type:image/jpeg");
//echo $fileContent;
//imagejpeg($img_jpeg, "", 100);
?>
<script>self.close()</script>
