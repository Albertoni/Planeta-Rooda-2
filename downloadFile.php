<?php
session_start();
require_once("arquivo.class.php");
require_once("usuarios.class.php");
require_once("funcoes_aux.php");

global $tabela_arquivos;

$idUsuario = isset($_SESSION['SS_usuario_id']) ? $_SESSION['SS_usuario_id'] : 0;
$nome_usuario = isset($_SESSION['SS_usuario_nome']) ? $_SESSION['SS_usuario_nome'] : "";

// VERIFICANDO AUTENTICAÇÃO DO USUÁRIO
if ($idUsuario <= 0)
{
	die("Voc&ecirc; n&atilde;o est&aacute; autenticado.");
}

$usuario = new Usuario();
$usuario->openUsuario($idUsuario);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// CARREGANDO ARQUIVO
$arquivo = new Arquivo($id);

if ($arquivo->temErros()) {
	$erros = $arquivo->getErros();
	echo "<!DOCTYPE html>\n<html><head><meta charset=\"utf-8\"</head><body><ul><li>";
	echo implode('</li><li>', $erros);
	echo "</li></ul></body></html>";
	die();
}

$podeBaixar = false;
$tipoFuncionalidade = $arquivo->getTipoFuncionalidade();
$idFuncionalidade = $arquivo->getIdFuncionalidade();
switch ($tipoFuncionalidade)
{
	// VERIFICAR SE ALUNO PERTENCE A TURMA DESSAS FUNCIONALIDADES
	case TIPOAULA:
	case TIPOBIBLIOTECA:
	case TIPOFORUM:
	case TIPOPERGUNTA:
	case TIPOPORTFOLIO:
		$idTurma    = turmaFuncionalidade($tipoFuncionalidade,$idFuncionalidade);
		$podeBaixar = usuarioPertenceTurma($idUsuario,$idTurma);
		break;
	// DEIXAR QUALQUER USUARIO REGISTRADO A BAIXAR QUALQUER ARQUIVO DE 
	// QUALQUER FUNCIONALIDADE QUE NAO FOI DEFINIDA ACIMA.
	default:
		$podeBaixar = true;
		break;
}

if (!$podeBaixar)
{
	die("Voc&ecirc; nao pode baixar este arquivo");
}
else
{
	$conteudo = $arquivo->getConteudo();
	$nome     = $arquivo->getNome();
	$tipo     = $arquivo->getTipo();
	$tamanho  = $arquivo->getTamanho();

	if ($consulta->erro != "")
	{
		die("ERRO - \"".$consulta->erro."\"");
	}
	else
	{
		// registra download
		$registra = new conexao();
		$registra->solicitar("INSERT INTO $tabela_usuario_download (usuario_id,arquivo_id) VALUES ('$idUsuario','$id')");

		header("Content-length: {$arquivo->getTamanho()}");
		header("Content-type: {$arquivo->getTipo()}");
		header("Content-Disposition: attachment; filename={$arquivo->getNome()}");
		exit($arquivo->getConteudo());
	}
}
