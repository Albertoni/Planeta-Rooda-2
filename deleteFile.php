<?php
header("Content-Type: application/json");
session_start();
require_once("cfg.php");
require_once("bd.php");

global $tabela_arquivos;

$id_usuario = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
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
            $json['id'] = $id;
            $podeDeletar = false;
            if($consulta->registros === 1)
            {
                $funcionalidade_id = $consulta->resultado['funcionalidade_id'];
                $funcionalidade_tipo = $consulta->resultado['funcionalidade_tipo'];
                $uploader_do_arquivo = ($consulta->resultado['uploader_id'] === $id_usuario);
                // ARQUIVO EXISTE
                // TODO: verificar se usuario pode deletar arquivo baseado no usuario/funcionalidade/turma/etc
                switch ($funcionalidade_tipo) {
                    case TIPOPORTFOLIO:
                        $verifica = new conexao();
                        $verifica->solicitar(
                            "SELECT owner_id,turma
                                FROM $tabela_portfolioProjetos
                                WHERE id = '$funcionalidade_id'"
                        );
                        $owner_id = (int) $verifica->resultado['owner_id'];
								$turma = $verifica->resultado['turma'];
                        if ($owner_id === $id_usuario) {
                            // É dono do portfolio
                            $podeDeletar = true;
                        } else {
                            $verifica = new conexao();
                            $verifica->solicitar(
                                "SELECT associacao
                                    FROM $tabela_turmasUsuario
                                    WHERE codUsuario = '$id_usuario'
                                    AND codTurma = '$turma'"
                            );
									 if ($verifica->registros === 1) {
										 if($uploader_do_arquivo || ((int) $verifica->resultado['associacao'] <= 4)) {
											  // Professor da turma do portfolio ou dono do arquivo
											  $podeDeletar = true;
										 }
									 }
                        }
                        break;
                    case TIPOBLOG:
                        $verifica = new conexao();
                        $verifica->solicitar(
                            "SELECT OwnersIds 
                                FROM $tabela_blogs 
                                WHERE Id = '$funcionalidade_id'"
                        );
                        $donos = explode(";",$verifica->resultado['OwnersIds']);
                        $n=count($donos);
                        for ($i=0;$i<$n;$i++)
                        {
                            // Só pode deletar se for um dos donos do blog.
                            if ($id_usuario === (int) $donos[$i])
                            {
                                $podeDeletar = true;
                            }
                        }
                        break;
                }
                if($podeDeletar) {
                    $deleta = new conexao();
                    $deleta->solicitar(
                        "DELETE FROM $tabela_arquivos WHERE arquivo_id = $id"
                    );

                    if ($deleta->erro != "")
                    {
                        $json['error'] = "ERRO - \"".$deleta->erro."\"";
                    } else {
                        $json['ok'] = true;
                    }
                }
                else
                {
                    $json['error'] = "Você não tem permissão para deletar este arquivo.";
                }
            }
            else
            {
                if ($consulta->registros === 0)
                {
                    // Arquivo não foi encontrado
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
