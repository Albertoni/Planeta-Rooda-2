<?php
include("_gd.php");
include("magic.php");	//fornece o tamanho da imagem

$canvas = new Bg_quad;					//inicializa area de desenho
$canvas->gera($tamanhogeralzao);						//seta o tamanho da tela, dentre outras variaveis

if(isset($_GET['pts'])){
	$pts = explode(",",$_GET['pts']);	//pega os pontos do argumento
	unset($pts[count($pts)-1]);			//elimina o ultimo ponto, q eh um valor 'nulo' q 
										//		sobra da operacao acima
	$canvas->pts($pts);
}

$canvas->draw();

?>