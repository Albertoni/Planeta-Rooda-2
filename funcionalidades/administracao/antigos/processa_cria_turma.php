<?
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	
	$nome = $HTTP_POST_VARS['titulo'];
	$idprof = $HTTP_POST_VARS['idprof'];
	$desc = $HTTP_POST_VARS['descricao'];
	$disciplina = $HTTP_POST_VARS['disciplina'];
	$serie = $HTTP_POST_VARS['serie'];
	
	if ($nome != null && $idprof != null && $desc != null && $disciplina != null && $serie != null){
		$consulta = new conexao();
		$consulta->solicitar("INSERT $tabela_turmas VALUES(DEFAULT, '$nome', '$idprof', '$desc', '$disciplina', '$serie')");
			
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script language="javascript">
		history.go(-3);
	</script>
</head>
</html>
