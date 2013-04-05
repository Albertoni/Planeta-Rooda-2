<?php
session_start();
require("cfg.php");
require("bd.php");

global $tabela_arquivos;

$id_usuario = isset($_SESSION['SS_usuario_id']) ? $_SESSION['SS_usuario_id'] : 0;
$nome_usuario = isset($_SESSION['SS_usuario_nome']) ? $_SESSION['SS_usuario_nome'] : "";


$json['ok'] = false;

if ($id_usuario > 0)
{
	if (!isset($_GET['id']) || !is_numeric($_GET['id']))
	{
		$json['error'] = "N&atilde;o sei o que deu errado, mas n&atilde;o se preocupe. Nossa equipe de macacos altamente treinados est&aacute; tentando resolver o problema.";
	}
	else
	{
		$id = $_GET['id'];

		$consulta = new conexao();
		$consulta->connect();
		$consulta->solicitar("SELECT * FROM $tabela_arquivos WHERE arquivo_id = $id");

		if($consulta->registros === 1)
		{
			$consulta2 = new conexao();
			$consulta2->connect();
			$consulta2->solicitar("DELETE FROM $tabela_arquivos WHERE arquivo_id = $id");

			if ($consulta2->erro != "")
			{
				$json['error'] = "ERRO - \"".$consulta2->erro."\"";
			} else {
				$json['ok'] = true;
			}
		}
		else
		{
			if ($consulta->registros === 0)
			{
				$json['ok'] = true;
			}
			else
			{
				$json['error'] = "Algum problema aconteceu, isso poderia deletar mais de 1 arquivo.";
			}
		}
	}
}
else
{
	$json['error'] = "Voc&ecirc; n&atilde;o est&aacute; autenticado.";
}

echo json_encode($json);
