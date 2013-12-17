<?php //>
require_once('../../cfg.php');
require_once('../../bd.php');
require_once('../../usuarios.class.php');
header("Content-Type: application/json");
session_start();
$codUsuario = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
$codPost = isset($_POST['codPost']) ? (int) $_POST['codPost'] : 0;
$mensagem = isset($_POST['mensagem']) ? nl2br(htmlspecialchars(trim($_POST['mensagem']), ENT_QUOTES), false) : "";
$json = array(
	'codPost' => $codPost,
	'ok' => false,
	'mensagem' => array(
		'codUsuario' => $codUsuario,
		'texto' => $mensagem
	)
);
if ($codUsuario <= 0)
{
	$json['errors'][] = 'Sua sessão expirou.';
	$json['errors'][] = 'Volte para a tela de login.';
}
else
{
	$user = new Usuario();
	$user->openUsuario($codUsuario);
	$json['mensagem']['nomeUsuario'] = $user->getName();;
	if($codPost <= 0 || !is_string($mensagem))
	{
		$json['errors'][] = 'Parâmetros inválidos.';
	}
	else {
		if($mensagem === "") {
			$json['errors'][] = 'Mensagem vazia.';
		} else
		{
			// PEGA ID DA TURMA
			$bd = new conexao();
			$bd->solicitar(
				"SELECT Proj.turma as turma
				FROM $tabela_portfolioPosts AS Post
					INNER JOIN $tabela_portfolioProjetos AS Proj
					ON Proj.id = Post.projeto_id
				WHERE Post.id = '$codPost'"
			);
			if ($bd->erro) {
				$json['errors'][] = $bd->erro;
			}
			// ID DA TURMA:
			$turma = $bd->resultado['turma'];
			// pegando permissão do usuario para inserir comentário
			$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
			$perm = $user->podeAcessar($perm['portfolio_inserirComentarios'],$turma);
			if (!$perm) {
				$json['errors'][] = "Você não tem permissão para comentar neste post.";
			}
			else 
			{
				$bd = new conexao();
				$dateTime = new DateTime();
				$data = $bd->sanitizaString($dateTime->format("Y-m-d H:i:s"));
				$mensagem = $bd->sanitizaString($mensagem);
				$bd->solicitar(
					"INSERT INTO $tabela_portfolioComentarios
					       (  codPost,   codUsuario,   data,   texto)
					VALUES ('$codPost','$codUsuario','$data','$mensagem')"
				);
				if ($bd->erro) {
					$json['errors'][] = $bd->erro;
				}
				else
				{
					// comentário inserido com sucesso
					$json['mensagem']['codComentario'] = $bd->ultimo_id();
					$json['mensagem']['data'] = $data;
					$json['mensagem']['podeApagar'] = true;
					$json['ok'] = true;
				}
			}
		}
	}
}
echo json_encode($json);