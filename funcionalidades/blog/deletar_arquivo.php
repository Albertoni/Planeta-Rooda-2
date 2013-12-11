<?php
session_start();

require_once("blog.class.php");
require_once("../../usuarios.class.php");
require_once("../../cfg.php");
require_once("../../bd.php");

global $tabela_arquivos;

$usuario_id = $_SESSION['SS_usuario_id'];
$file_id = $_GET['kill'];
if (is_numeric($file_id) and is_numeric($usuario_id)) {
	$consulta = new conexao();
	$consulta->solicitar("SELECT uploader_id FROM $tabela_arquivos WHERE arquivo_id=$file_id");
	if ($consulta->resultado['uploader_id'] == $usuario_id or $consulta->resultado['uploader_id'] == 0) { // O 0 é pra conseguir detonar usuário nulo.
		$consulta->solicitar("DELETE FROM $tabela_arquivos WHERE arquivo_id=$file_id");
	}
} else {
	die('Algo de errado aconteceu ou voc&ecirc; intencionalmente causou um erro. Por favor <a href="javascript:history.go(-1)">volte</a> para tentar novamente.');
}?>
<script language="javascript">
	history.go(-1)
</script>
