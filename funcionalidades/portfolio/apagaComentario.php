<?php //>
require_once('../../cfg.php');
require_once('../../bd.php');
header("Content-Type: application/json");
session_start();
$codUsuario = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
$codComentario = isset($_GET['comentario']) ? (int) $_GET['comentario'] : 0;
$json = array('codComentario' => $codComentario, 'ok' => false);
if ($codUsuario <= 0)
{
	$json['errors'][] = 'Sua sessão expirou.';
	$json['errors'][] = 'Volte para a tela de login.';
}
else
{
	if($codComentario <= 0)
	{
		$json['errors'][] = 'Parâmetros inválidos.';
	}
	else
	{
		$bd = new conexao();
		$bd->solicitar(
			"SELECT Com.codComentario AS codComentario,
			        Post.id AS codPost,
			        Post.user_id AS codAutorPost,
			        Com.codUsuario AS codAutorComentario,
			        Proj.owner_id AS codDonoProjeto,
			        Proj.turma AS codTurma
			FROM $tabela_portfolioComentarios AS Com
				INNER JOIN $tabela_portfolioPosts AS Post
				ON Com.codPost = Post.id
					INNER JOIN $tabela_portfolioProjetos AS Proj
					ON Proj.id = Post.projeto_id
			WHERE Com.codComentario = '$codComentario'"
		);
		if ($bd->erro)
		{
			$json['errors'][] = $bd->erro;
		}
		else
		{
			if ($bd->registros === 0)
			{
				$json['ok'] = true;
			}
			else if ($bd->registros !== 1)
			{
				$json['errors'][] = "Erro desconhecido (E2202)";
			}
			else
			{
				$json['codPost'] = $bd->resultado['codPost'];
				$donoProjeto = $bd->resultado['codDonoProjeto'];
				$autorComentario = $bd->resultado['codAutorComentario'];
				$autorPost = $bd->resultado['codAutorPost'];
				$codTurma = $bd->resultado['codTurma'];
				$perm = false;
				if (!$perm && $donoProjeto === $codUsuario)
				{
					// é dono do projeto
					$perm = true;
				}
				if (!$perm && $autorPost === $codUsuario)
				{
					// é autor do post
					$perm = true;
				}
				if (!$perm && $autorComentario === $codUsuario)
				{
					// é autor do comentário
					$perm = true;
				}
				if (!$perm)
				{
					// verifica se é admin/coordenador/professor/monitor da turma
					$bd = new conexao();
					$bd->solicitar(
						"SELECT associacao AS nivel
						FROM $tabela_turmasUsuario
						WHERE codUsuario = '$codUsuario'
							AND codTurma = '$codTurma'"
					);
					if ($bd->erro)
					{
						$json['errors'][] = $bd->erro;
					}
					else
					{
						if ($bd->registros === 1)
						{
							$nivel = $bd->resultado['nivel'];
							if ($nivel >= $nivelAdmin && $nivel < $nivelAluno)
							{
								$perm = true;
							}
						}
					}
				}
				if (!$perm) 
				{
					// não tem permissão para apagar o comentário
					$json['errors'][] = "Você não tem permissão para apagar este comentário.";
				}
				else
				{
					// tem permissão para apagar o comentário
					$bd = new conexao();
					$bd->solicitar(
						"DELETE FROM $tabela_portfolioComentarios WHERE codComentario = $codComentario"
					);
					if ($bd->erro)
					{
						$json['errors'][] = $bd->erro;
					}
					else
					{
						$json['ok'] = true;
					}
				}
			}
		}
	}
}
echo json_encode($json);