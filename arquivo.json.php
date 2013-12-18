<?php
require_once('cfg.php');
require_once('bd.php');
require_once('funcoes_aux.php');
require_once('usuarios.class.php');
require_once('arquivo.class.php');
$json = [];
$usuario = usuario_sessao();
if ($usuario === false) {
	$json['session'] = false;
	$json['errors'][] = "Você não está autenticado.";
} else {
	$json['session'] = $usuario->getSimpleAssoc();
}

if (!isset($json['errors'])) switch ($_GET['acao']) {
	case 'listar':
		$idUsuario = $usuario->getId();
		$arquivos = new Arquivo();
		try {
			if ($arquivos->abrirUsuario($usuario)) do {
				$json['arquivos'][] = $arquivos->getAssoc();
			} while ($arquivos->proximo());
		}
		catch (Exception $e) {
			$json['errors'] = $e->getMessage();
			break;
		}
		break;
}
header("Content-Type: application/json");
echo json_encode($json);