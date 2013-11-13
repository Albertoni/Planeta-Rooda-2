<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("sistema_forum.php");
require_once("../../usuarios.class.php");
require_once("../../arquivo.class.php");

$user = usuario_sessao();

if (!$user) die ("Voc&ecirc; n&atilde;o est&aacute; logado");
$idUsuario = $user->getId();
$idMensagem = isset($_GET['m']) ? (int) $_GET['m'] : 0;
$idArquivo = isset($_GET['a']) ? (int) $_GET['a'] : 0;
$imagem = isset($_GET['img']) ? true : false;
$miniatura = isset($_GET['thumb']) ? true : false;
$q = new conexao();
$q->solicitar("SELECT idTurma FROM ForumMensagemAnexos AS fma
					INNER JOIN ForumMensagem AS fm
						ON fma.idMensagem = fm.idMensagem
					INNER JOIN ForumTopico AS ft
						ON fm.idTopico = ft.idTopico
				WHERE fma.idMensagem = $idMensagem
				AND fma.idArquivo = $idArquivo");
if ($q->registros !== 1) {
	die('Anexo n&atilde;o encontrado : '.$q->erro);
} 
$idTurma = (int) $q->resultado['idTurma'];
if (!usuarioPertenceTurma($idUsuario, $idTurma)) {
	die ('Anexo n&atilde;o encontrado');
}

if ($miniatura) {
	try {
		$imagem = new Imagem($idArquivo);
	} catch (Exception $e) {
		echo 'Este anexo não é uma imagem.';
	}
	$largura_maxima = isset($_GET['w']) ? (int) $_GET['w'] : 0;
	$altura_maxima = isset($_GET['h']) ? (int) $_GET['h'] : 0;
	$imagem->miniatura($largura_maxima, $altura_maxima);
	exit();
} else {
	$arquivo = new Arquivo($idArquivo);
	header("Content-length: {$arquivo->getTamanho()}");
	header("Content-type: {$arquivo->getTipo()}");
	if (!$imagem)
		header("Content-Disposition: attachment; filename={$arquivo->getNome()}");
	exit($arquivo->getConteudo());
}