<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("desenho.class.php");

$user = usuario_sessao();
if($user === false){ die("Você não está logado.");}
$user_id = $user->getId();

header('Expires: 0');
header('Pragma: no-cache');

$DESENHO = null;

if ( isset($_POST['imagem']) ){
	$id		= $_POST['id'];
	$imagem	= $_POST['imagem']; // CONTEM BASE64

	// Como a imagem é enviada em partes, precisa ser guardada em algum lugar. Nesse caso, a sessão.
	if(isset($_SESSION['arte_img_'.$id])){
		// se existe, concatena o base64
		$_SESSION['arte_img_'.$id] .= $imagem;
	}else{
		$_SESSION['arte_img_'.$id] = $imagem;
	}

	if (strlen($_SESSION['arte_img_'.$id]) >= $_SESSION['arte_tamanho_'.$id]){
		$DESENHO = new Desenho($id);
		$DESENHO->setDesenho($_SESSION['arte_img_'.$id]);
		$DESENHO->salvar();

		unset($_SESSION['arte_img_'.$id]);
		unset($_SESSION['arte_tamanho_'.$id]);

		echo strlen($DESENHO->getDesenho())."\n";				// o envio do arquivo acabou
	}else{
		echo "0";				// não terminou o envio
	}
}else{

	$id			= $_POST['id'];
	$tamanho		= $_POST['tamanho'];
	$titulo		= $_POST['titulo'];
	$turma		= $_POST['turma'];
	$existente	= $_POST['existente'];

	if ($existente == 0){	// novo desenho
		$DESENHO = new Desenho(0, $user_id, $turma, "", $titulo);
	}else{				// desenho já existente
		$DESENHO = new Desenho($id);
		$DESENHO->setDesenho("");
		$DESENHO->setTitulo($titulo);
	}
	$DESENHO->salvar();

	$id = $DESENHO->getId();
	$_SESSION['arte_tamanho_'.$id] = $tamanho;
	$_SESSION['arte_img_'.$id] = "";

	echo $id;
}