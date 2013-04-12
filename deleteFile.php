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
		$consulta->solicitar("SELECT funcionalidade_id,funcionalidade_tipo,uploader_id FROM $tabela_arquivos WHERE arquivo_id = $id");

		if($consulta->erro != "")
		{
			$json['error'] = $consulta->erro;
		}
		else
		{
			$podeDeletar = false;
			if($consulta->registros === 1)
			{
				$funcionalidade_id = $consulta->resultado['funcionalidade_id'];
				$funcionalidade_tipo = $consulta->resultado['funcionalidade_tipo'];
				// ARQUIVO EXISTE

				// TODO: verificar se usuario pode deletar arquivo baseado no usuario/funcionalidade/turma/etc
				switch ($funcionalidade_tipo) {
					case TIPOBLOG:
						$verifica = new conexao();
						$verifica->solicitar(
							"SELECT OwnersIds "
							."FROM $tabela_blogs "
							."WHERE Id = '$funcionalidade_id'"
						);
						$donos = explode($verifica->resultado['OwnersIds']);
						$n=count($donos);
						for ($i=0;$i<$n;$i++)
						{
							// Só pode deletar se for um dos donos do blog.
							if ($id_usuario === (int) $donos[$i])
							{
								$podeDeletar = true;
							}
						}
				}

				$deleta = new conexao();
				$deleta->solicitar("DELETE FROM $tabela_arquivos WHERE arquivo_id = $id");

				if ($deleta->erro != "")
				{
					$json['error'] = "ERRO - \"".$deleta->erro."\"";
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
}
else
{
	$json['error'] = "Voc&ecirc; n&atilde;o est&aacute; autenticado.";
}

echo json_encode($json);
