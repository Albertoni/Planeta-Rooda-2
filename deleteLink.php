<?
header("Content-Type: application/json");
session_start();
require_once('cfg.php');
require_once('bd.php');
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
			"SELECT Id,funcionalidade_tipo,funcionalidade_id,uploader_id 
			FROM $tabela_links 
			WHERE Id = '$id'"
		);
		if ($consulta->erro) {
			$json['errors'][] = $consulta->erro;
		} else {
			if ($consulta->registros === 0) {
				// Arquivo nao existe ou ja foi excluido previamente:
				//  => Tratar como sucesso;
				$json['ok'] = true;
			} else {
				if ($consulta->registros !== 1) {
					// a consulta retornou mais do que 1 resultado;
					$json['errors'][] = 'Erro de consulta 3528';
				} else {
					$podeDeletar = false;
					// VERIFICAR SE USUARIO REALMENTE PODE APAGAR O LINK
					$podeDeletar = true;
					// Fim da verificação
					if(!$podeDeletar) {
						$json['errors'][] = 'Você não tem permissão para baixar este arquivo.';
					} else {
						$consulta = new conexao();
						$consulta->solicitar("DELETE FROM $tabela_links WHERE Id = '$id'");
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
print json_encode($json);
