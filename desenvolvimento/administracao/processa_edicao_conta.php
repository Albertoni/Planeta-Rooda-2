<?php
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	
	$id = $HTTP_POST_VARS['id'];
	$nome = $HTTP_POST_VARS['nome'];
	$login = $HTTP_POST_VARS['login'];
	$email = $HTTP_POST_VARS['email'];
	$data = $HTTP_POST_VARS['data'];
	$nivel = $HTTP_POST_VARS['nivel'];
	$senha = $HTTP_POST_VARS['novasenha'];

	$apagar = $HTTP_POST_VARS['apagar'];

	if ($apagar == 1){
		$consulta = new conexao();
		$consulta->solicitar("DELETE FROM $tabela_usuarios WHERE usuario_id = $id");
		$consulta->fechar();

		$retorno = -1;
	}
	else if ($id != null && $nome != null && $login != null && $email != null && $data != null && $nivel != null){
		$consulta = new conexao();
		$retorno = -3;
		if ($senha != null){
			$consulta->solicitar("UPDATE $tabela_usuarios SET usuario_nome = '$nome', usuario_login = '$login', usuario_email='$email', usuario_data_aniversario = '$data', usuario_nivel = $nivel, usuario_senha = '$md5senha' WHERE usuario_id = $id");
		} else {
			$consulta->solicitar("UPDATE $tabela_usuarios SET usuario_nome = '$nome', usuario_login = '$login', usuario_email='$email', usuario_data_aniversario = '$data', usuario_nivel = $nivel WHERE usuario_id = $id");
		}
	}

echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<script language=\"javascript\">
		history.go($retorno);
	</script>
</head>
</html>";

?>
