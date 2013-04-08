<?php
header("Content-Type: application/json");
session_start();
require_once("cfg.php");
require_once("bd.php");

$json = array();
$json['ok'] = false;

$id_usuario = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
$nome_usuario = isset($_SESSION['SS_usuario_nome']) ? $_SESSION['SS_usuario_nome'] : "";

$funcionalidade_id = isset($_GET['funcionalidade_id']) ? (int) $_GET['funcionalidade_id'] : 0;
$funcionalidade_tipo = isset($_GET['funcionalidade_tipo']) ? (int) $_GET['funcionalidade_tipo'] : 0;

$arquivo_tipo = isset($_GET['arquivo_tipo']) ? $_GET['arquivo_tipo'] : false;

if ($id_usuario <= 0)
{
	$json['errors'][] = "Você não está mais logado. Por favor, autentique-se novamente.";
}
else
{
	if ($funcionalidade_id <= 0 || $funcionalidade_tipo <= 0) 
	{
		$json['errors'][] = "Funcionalidade nao especificada corretamente";
	}
	else
	{

		// GERANDO CONSULTA
		$consulta = new conexao();
		
		$sql = "SELECT arquivo_id,nome,titulo,tipo,tamanho,uploader_id"; 
		$sql .= " FROM $tabela_arquivos";
		$sql .= " WHERE funcionalidade_tipo = '$funcionalidade_tipo'";
		$sql .= " AND funcionalidade_id = '$funcionalidade_id'";
		
		if ($arquivo_tipo !== false)
		{
			$arquivo_tipo = $consulta->sanitizaString($arquivo_tipo);
			$sql .= " AND tipo LIKE '$arquivo_tipo'";
		}
		
		// EXECUTANDO CONSULTA
		$consulta->solicitar($sql);
		
		// VERIFICANDO RESULTADO
		if ($consulta->erro)
		{
			$json['errors'][] = $consulta->erro;
		}
		else
		{
			$json['ok'] = true;
			while ($consulta->resultado)
			{
				$json['files'][] = array(
					"file_id" => $consulta->resultado['arquivo_id']
				,	"file_name" => $consulta->resultado['nome']
				,	"file_title" => $consulta->resultado['titulo']
				,	"file_type" => $consulta->resultado['tipo']
				,	"file_size" => $consulta->resultado['tamanho']
				,	"file_author" => $consulta->resultado['uploader_id']
				);
				$consulta->proximo();
			}
		}
	}
}
echo json_encode($json);
