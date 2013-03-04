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
				myForm.action="processa_edicao_planeta.php";
				
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
<form method="get" action="manutencao_planetas.php">
Pesquisar por nome do dono do planeta:&#9;<input type="text" name="nome" /> <input type="submit" value="Pesquisar"/><br />
Acentuação é importante.
</form>
<ul><div style="white-space:pre">
<?php
	$consulta = new conexao();
	$consulta_donos = new conexao();
	$nome = $_GET['nome'];
	if($nome != null){
		$consulta->solicitar("SELECT * FROM $tabela_grupos WHERE grupo_nome = '$nome'");
	} else {
		$consulta->solicitar("SELECT grupo_nome, grupo_id, grupo_proprietario_id FROM $tabela_grupos");
	}
	
	for ($i=0;$i<count($consulta->itens);$i++){
		if ($i%2){ // Se for impar
			echo '	<li class="planeta2">';}
		else { // É par. Derp.
			echo '	<li class="planeta1">';}
		$id_dono = $consulta->resultado['grupo_proprietario_id'];
		$consulta_donos->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = $id_dono");
?>
ID do grupo: <?=$consulta->resultado['grupo_id']?>&#9;Nome do grupo: <?=$consulta->resultado['grupo_nome']?>&#9;Nome do proprietário: <?=$consulta_donos->resultado['usuario_nome']?>&#9;<a href="editar_planeta.php?id=<?=$consulta->resultado['grupo_id']?>">Editar</a>&#9;<a href="javascript:apagar(<?=$consulta->resultado['usuario_id']?>)">DELETAR ESTA CONTA</a>
<?php
		$consulta->proximo();
	}?>
</ul></div>
</body>
</html>
