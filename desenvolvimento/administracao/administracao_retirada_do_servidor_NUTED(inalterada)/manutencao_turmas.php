<?php
session_start();

require("../../cfg.php");
require("../../bd.php");

$consulta = new conexao();
$consulta_nomeprof = new conexao();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Manutenção de blogs</title>
	<script language="javascript">
		function apagar(id){
			if (confirm("Tem certeza que deseja apagar esta conta?")){
				var myForm = document.createElement("form");
				myForm.method="post";
				myForm.action="processa_edicao_turma.php";
				
				var myInput = document.createElement("input");
				myInput.setAttribute("name", "apagar");
				myInput.setAttribute("value", "1");
				myForm.appendChild(myInput);
				
				var myInput2 = document.createElement("input");
				myInput2.setAttribute("name", "codTurma");
				myInput2.setAttribute("value", id);
				myForm.appendChild(myInput2);
				
				document.body.appendChild(myForm);
				myForm.submit();
				document.body.removeChild(myForm);
			}
		}
	</script>
</head>

<body>
	<form method="get" action="manutencao_turmas.php">
Pesquisar por nome da turma:&#9;<input type="text" name="nome" /><input type="submit" value="Pesquisar"/>
	</form>
<ul>
<?php
	$nome = $_GET['nome'];
	if ($nome == NULL){
		$consulta->solicitar("SELECT * FROM $tabela_turmas"); 
	} else {
		$consulta->solicitar("SELECT * FROM $tabela_turmas WHERE nomeTurma LIKE '%$nome%'");
	}

	for ($i=0; $i<count($consulta->itens); $i++){
		if ($i%2){ // Se for impar
			echo '	<li class="blog2">';}
		else { // É par. Derp.
			echo '	<li class="blog1">';}
			
		$codTurma = $consulta->resultado['codTurma'];
		$nomeTurma = $consulta->resultado['nomeTurma'];
		$profResponsavel = $consulta->resultado['profResponsavel'];
		$descricao = $consulta->resultado['descricao'];
		$nomeDisciplina = $consulta->resultado['nomeDisciplina'];
		$serie = $consulta->resultado['serie'];

		$consulta_nomeprof->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = $profResponsavel");
		$nomeProf = $consulta_nomeprof->resultado['usuario_nome'];
			
		echo "ID: $codTurma Nome: $nomeTurma Professor: $profResponsavel - $nomeProf <a href='editar_turma.php?id=$codTurma'>EDITAR ESTA TURMA</a><br />Descricao: $descricao<br />Disciplina: $nomeDisciplina Serie: $serie<br /><a href=\"javascript:apagar($codTurma)\">DELETAR ESTA CONTA</a><br /><br />";
			
		$consulta->proximo();
	}
				
			
?>
