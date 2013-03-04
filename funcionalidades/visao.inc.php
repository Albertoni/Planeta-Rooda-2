<?

function visao($tipo) {
	$associacaoTurma_teste = $_SESSION["associacaoTurma"];
	$associacao_teste	   = $_SESSION["associacao"];
	$associacaoTemp	   	   = $_SESSION["associacaoTemp"];
	$associacaoTurmaTemp   = $_SESSION["associacaoTurmaTemp"];
	

	if(($associacaoTurmaTemp == "P" ) || ($associacaoTemp == 2) || ($associacaoTemp == 1)) {
		$texto = "<A href='javascript:mudarvisao()' id='visao'><B>ir para visão do Professor</B></A>";
	} else if (($associacaoTurma_teste == "P") || ($associacao_teste == 2) || ($associacao_teste == 1)){
		$texto = "<A href='javascript:mudarvisao()' id='visao'><B>ir para visão do Aluno</B></A>";
	}

	/* codigo antigo de mudança de visão professor -> aluno
	if ($tipo == 4)
	{
		$texto = "<A href='altera_visao.php' id='visao' target='principal'><B>ir para visão do Professor</B></A>";
	else
		$texto = "<A href='altera_visao.php' id='visao' target='principal'><B>ir para visão do Aluno</B></A>";
	}
	*/

//controle das funcionalidades onde a alteração de visão pode aparecer
$visao_aparecer = $_SESSION["visao_aparecer"]; 
if($visao_aparecer=="sumir") { 
$texto = "";
}
?>	
	<script type="text/javascript">parent.document.getElementById("visao").innerHTML = "<?=$texto?>"; </script>
<?
}

?>