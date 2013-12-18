<?php
// !SQLINJECTION
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	
	$nome = $HTTP_POST_VARS['nomeTurma'];
	$idprof = $HTTP_POST_VARS['idprof'];
	$desc = $HTTP_POST_VARS['descricao'];
	$disciplina = $HTTP_POST_VARS['nomeDisciplina'];
	$serie = $HTTP_POST_VARS['serie'];
	$idturma = $HTTP_POST_VARS['codTurma'];

	$apagar = $HTTP_POST_VARS['apagar'];

	if ($apagar == 1){
		$consulta = new conexao();
		$consulta->solicitar("DELETE FROM $tabela_turmas WHERE codTurma = $idturma");
		

		$retorno = -1;
	}
	else if ($nome != null && $idprof != null && $desc != null && $disciplina != null && $serie != null&& $idturma != null){
		$consulta = new conexao();
		$consulta->solicitar("UPDATE $tabela_turmas SET nomeTurma = '$nome', profResponsavel = '$idprof', descricao = '$desc', nomeDisciplina = '$disciplina', serie = '$serie' WHERE codTurma = $idturma");
		

		$retorno = -3;
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
