<?php
header("Content-Type: application/json");
require_once('../../cfg.php');
require_once('../../bd.php');
require_once('../../funcoes_aux.php');

$user = usuario_sessao();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$json = array('id' => $id, 'ok' => false);

if ($user === false) { // Se não está logado
	$json['errors'][] = 'Sua sessão expirou.';
	$json['errors'][] = 'Volte para a tela de login.';
} else {

	$id_usuario = $user ->getId();

	if (!is_numeric($id)){ // se id não é um numero não numerico
		$json['errors'][] = 'Parametros inválidos.';
	}else{
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
					$nivelUsuario = $user->getNivel($turma);
					global $nivelProfessor;
					if($nivelUsuario != $nivelProfessor){
						$json['errors'][] = 'Você não tem permissão para deletar este arquivo.';
					}else{
						// Assume-se pior caso
						$deuErroAoApagarPosts = true;
						$deuErroAoApagarComentarios = true;

						$consulta = new conexao();

						// Apagar os posts
						$consulta->solicitar("DELETE FROM PortfolioPosts WHERE id='$id'");
						if($consulta->erro){
							$json['errors'][] = $consulta->erro;
						}else{
							$deuErroAoApagarPosts = false;
						}

						// Apagar os comentários pra não deixar lixo
						$consulta->solicitar("DELETE FROM PortfolioPostComentarios WHERE idRef='$id'");
						if($consulta->erro){
							$json['errors'][] = $consulta->erro;
						}else{
							$deuErroAoApagarComentarios = false;
						}

						if((!$deuErroAoApagarPosts) && (!$deuErroAoApagarComentarios)){
							$json['ok'] = true;
						}
					}
				}
			}
		}
	}
}
echo json_encode($json);