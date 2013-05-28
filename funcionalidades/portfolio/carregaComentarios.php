<?php //>
require_once('../../cfg.php');
require_once('../../bd.php');
header("Content-Type: application/json");
session_start();
$codUsuario = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
$codPost = isset($_GET['post']) ? (int) $_GET['post'] : 0;
$json = array('codPost' => $codPost, 'ok' => false);
if ($codUsuario <= 0)
{
	$json['errors'][] = 'Sua sessão expirou.';
	$json['errors'][] = 'Volte para a tela de login.';
}
else
{
	if($codPost <= 0)
	{
		$json['errors'][] = 'Parâmetros inválidos.';
	}
	else
	{
		// Varificando permissões do usuario
		$bd = new conexao();
		$bd->solicitar(
			"SELECT Post.user_id AS codAutorPost,
			        Proj.owner_id AS codDonoProjeto,
			        Turma.associacao AS nivelUsuario,
			        Post.titulo AS tituloPost
			FROM $tabela_portfolioPosts AS Post
				INNER JOIN $tabela_portfolioProjetos AS Proj
				ON Proj.id = Post.projeto_id
					INNER JOIN $tabela_turmasUsuario AS Turma
					ON Turma.codTurma = Proj.turma
			WHERE Post.id = '$codPost'
			  AND Turma.codUsuario = '$codUsuario'"
		);
		if ($bd->erro)
		{
			$json['errors'][] = $bd->erro;
		}
		else
		{
			if ($bd->registros === 0)
			{
				$json['errors'][] = 'Você não tem permissão para visualizar estes comentários.';
			}
			else if ($bd->registros !== 1)
			{
				$json['errors'][] = 'Erro desconhecido (E2202).';
			}
			else
			{
				$json['tituloPost'] = $bd->resultado['tituloPost'];
				$podeApagarQualquer = false;
				$podeApagarQualquer = $podeApagarQualquer || ($codUsuario === $bd->resultado['codAutorPost']);
				$podeApagarQualquer = $podeApagarQualquer || ($codUsuario === $bd->resultado['codDonoProjeto']);
				$podeApagarQualquer = $podeApagarQualquer || ($nivelMonitor >= $bd->resultado['nivelUsuario']);
				$bd = new conexao();
				$bd->solicitar(
					"SELECT C.codComentario AS codComentario, 
					        C.codUsuario AS codUsuario, 
					        U.usuario_nome AS nomeUsuario,
					        C.data AS data, 
					        C.texto AS texto
					FROM $tabela_portfolioComentarios AS C
						INNER JOIN $tabela_usuarios AS U
						ON U.usuario_id = C.codUsuario
					WHERE codPost = '$codPost'
					ORDER BY codComentario ASC"
				);
				if ($bd->erro)
				{
					$json['errors'][] = $bd->errors;
				}
				else
				{
					$json['ok'] = true;
					// gerando array de mensagens no json.
					$json['mensagens'] = array();
					while($bd->resultado)
					{
						$json['mensagens'][] = array(
							'codComentario' => (int) $bd->resultado['codComentario'],
							'codUsuario' => (int) $bd->resultado['codUsuario'],
							'nomeUsuario' => $bd->resultado['nomeUsuario'],
							'data' => $bd->resultado['data'],
							'texto' => $bd->resultado['texto'],
							'podeApagar' => $podeApagarQualquer || ($codUsuario === (int) $bd->resultado['codUsuario'])
						);
						$bd->proximo();
					}
				}
			}
		}
	}
}
echo json_encode($json);