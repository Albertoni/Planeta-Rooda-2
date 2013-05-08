<?php
header("Content-Type: application/json");
session_start();
require_once('../../cfg.php');
require_once('../../bd.php');
$id_usuario = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
if ($id_usuario <= 0) {
	$json['errors'][] = 'Sua sessão expirou.';
	$json['errors'][] = 'Volte para a tela de login.';
} else {
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$json = array('id' => $id, 'ok' => false);
	if ($id <= 0) {
		$json['errors'][] = 'Parametros inválidos';
	} else {
		$consulta = new conexao();
		$consulta->solicitar(
			"SELECT Posts.id as post, Projetos.id as projeto, Projetos.turma as turma
			FROM PortfolioPosts as Posts 
			INNER JOIN PortfolioProjetos as Projetos ON Posts.projeto_id = Projetos.id
			WHERE Posts.id = $id"
		);
			// INNER JOIN TurmasUsuario as TUsuarios ON Projetos.turma = TUsuarios.codTurma
			// AND TUsuarios.codUsuario = $id_usuario
		if ($consulta->erro) {
			$json['errors'][] = $consulta->erro;
		} else {
			if ($consulta->registros === 0) {
				// Post não existe ou já foi excluído previamente:
				//  => tratar como sucesso;
				$json['ok'] = true;
			} else {
				if ($consulta->registros !== 1) {
					// a consulta retornou mais do que 1 resltado
					$json['errors'][] = 'Erro de consulta 3245';
				} else {
					$turma = $consulta->resultado['turma'];
					$projeto = $consulta->resultado['projeto'];
					$podeDeletar = false;
					// Verificar se usuario realmente pode deletar o post.
					$verifica = new conexao();
					$verifica->solicitar("SELECT associacao as nivel FROM $tabela_turmasUsuario WHERE codTurma = '$turma' AND codUsuario = '$id_usuario'");
					if ($verifica->erro) {
						$json['errors'][] = $verifica->erro;
					} else {
						if ($verifica->registros === 1) {
							if ($verifica->resultado['nivel'] & 4) {
								$podeDeletar = true;
							}
						}
					}
					if (!$podeDeletar) {
						$json['errors'][] = 'Você não tem permissão para baixar este arquivo.';
					} else {
						$consulta = new conexao();
						$consulta->solicitar("DELETE FROM $tabela_portfolioPosts WHERE id='$id'");
						if ($consulta->erro) {
							$json['errors'][] = $consulta->erro;
						} else {
							$json['ok'] = true;
						}
					}
				}
			}
		}
	}
}
echo json_encode($json);
