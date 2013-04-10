<?php
session_start();
require("cfg.php");
require("bd.php");

global $tabela_arquivos;

$id_usuario = isset($_SESSION['SS_usuario_id']) ? $_SESSION['SS_usuario_id'] : 0;
$nome_usuario = isset($_SESSION['SS_usuario_nome']) ? $_SESSION['SS_usuario_nome'] : "";

if ($id_usuario > 0)
{
	if (isset($_GET['id']) and is_numeric($_GET['id'])){
		$id = $_GET['id'];
	}else{
		die("N&atilde;o sei o que deu errado, mas n&atilde;o se preocupe. Nossa equipe de macacos altamente treinados est&aacute; tentando resolver o problema.");
	}

	$consulta = new conexao();
	$consulta->solicitar("SELECT * FROM $tabela_arquivos WHERE arquivo_id = $id");

	if($consulta->registros === 1){

		$podeBaixar = false;
		switch ($consulta->resultado['funcionalidade_tipo']) 
		{
			case TIPOBLOG:
				// QUALQUER USUARIO CADASTRADO PODE BAIXAR DE QUALQUER BLOG
				$podeBaixar = true;
				break;

			case TIPOPORTFOLIO:
				// VERIFICAR SE ALUNO PERTENCE A TURMA DO PROJETO
				
				$id_portfolio = $consulta->resultado['funcionalidade_id'];
				
				$verifica = new conexao();
				$verifica->solicitar(
					"SELECT T.codUsuario "
					."FROM $tabela_turmasUsuario as T "
					."INNER JOIN $tabela_portfolioProjetos as P "
					."ON T.codTurma = P.turma "
					."WHERE T.codUsuario = '$id_usuario' "
					."AND P.id = '$id_portfolio'"
				);
				if ($verifica->erro != "")
				{
					die("Erro: ".$verifica->erro);
				}
				if ($verifica->registros > 0)
				{
					$podeBaixar = true; // aluno pertence a turma do projeto
				}
				break;

			case TIPOBIBLIOTECA:
				// VERIFICAR SE ALUNO PERTENCE A TURMA DA BIBLIOTECA
				/* -/
				$verifica = new conexao();
				$verifica->solicitar(
					"SELECT T.codUsuario as id_usuario"
					."FROM $tabela_turmasUsuario as T "
					."INNER JOIN $tabela_Materiais as B "
					."ON T.codTurma = B.codTurma "
					."WHERE B.refMaterial = '$id' "
					."WHERE T.codUsuario = '$id_usuario'"
				);
				break;
				/* */
		}

		if (!$podeBaixar)
		{
			echo "Voc&ecirc; nao pode baixar este arquivo";
		}
		else
		{
			$fileContent = $consulta->resultado["arquivo"];
			$nome = $consulta->resultado["nome"];
			$tipo = $consulta->resultado["tipo"];
			$tamanho = $consulta->resultado["tamanho"];

			if ($consulta->erro != "")
			{
				echo "ERRO - \"".$consulta->erro."\"";
			}
			else
			{
				// registra download
				$registra = new conexao();
				$registra->solicitar("INSERT INTO $tabela_usuario_download (usuario_id,arquivo_id) VALUES ('$id_usuario','$id')");

				header("Content-length: $tamanho");
				header("Content-type: $tipo");
				header("Content-Disposition: attachment; filename=$nome");
				echo $fileContent;
			}
		}
	}
	else
	{
		die("Arquivo n&atilde;o encontrado.");
	}
}
else
{
	die("Voc&ecirc; n&atilde;o est&aacute; autenticado.");
}
