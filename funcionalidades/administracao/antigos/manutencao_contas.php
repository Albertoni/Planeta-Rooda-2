<?php
	session_start();

	require("../../cfg.php");
	require("../../bd.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Edição de usuários</title>
	<script language="javascript">
		function apagar(id){
			if (confirm("Tem certeza que deseja apagar esta conta?")){
				var myForm = document.createElement("form");
				myForm.method="post";
				myForm.action="processa_edicao_conta.php";
				
				var myInput = document.createElement("input");
				myInput.setAttribute("name", "apagar");
				myInput.setAttribute("value", "1");
				myForm.appendChild(myInput);
				
				var myInput2 = document.createElement("input");
				myInput2.setAttribute("name", "id");
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
<form method="get" action="manutencao_contas.php">
Pesquisar por nome do aluno:&#9;<input type="text" name="nome" /> <input type="submit" value="Pesquisar"/><br />
Acentuação é importante.
</form>
<ul><div style="white-space:pre">
<?php
	$consulta = new conexao();
	$nome = $_GET['nome'];
	if($nome != null){
		$consulta->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_nome = '$nome'");
	} else {
		$consulta->solicitar("SELECT * FROM $tabela_usuarios");
	}

	function retornaNomeNivel($nivel){
		global $nivelAdmin;
		global $nivelCoordenador;
		global $nivelProfessor;
		global $nivelAluno;
		global $nivelVisitante;
		
		global $admin;
		global $coordenador;
		global $professor;
		global $aluno;
		global $visitante;
		
		switch ($nivel){
			case $nivelAdmin:
				return $admin;
				break;
			case $nivelCoordenador: 
				return $coordenador;
				break;
			case $nivelProfessor:
				return $professor;
				break;
			case $nivelAluno:
				return $aluno;
				break;
			case $nivelVisitante:
				return $visitante;
				break;
			default:
				return 'ENTRE EM PÂNICO';
		}
	}
	
	for ($i=0;$i<count($consulta->itens);$i++){
		if ($i%2){ // Se for impar
			echo '	<li class="blog2">';}
		else { // É par. Derp.
			echo '	<li class="blog1">';}
?>
ID de usuário: <?=$consulta->resultado['usuario_id']?>&#9;Login: <?=$consulta->resultado['usuario_login']?>&#9;Email: <?=$consulta->resultado['usuario_email']?>&#9;Nivel: <?=retornaNomeNivel($consulta->resultado['usuario_nivel']);?>&#9;<a href="editar_conta.php?id=<?=$consulta->resultado['usuario_id']?>">Editar</a>&#9;<a href="javascript:apagar(<?=$consulta->resultado['usuario_id']?>)">DELETAR ESTA CONTA</a>
<?php
		$consulta->proximo();
	}?>
</ul></div>
</body>
</html>
