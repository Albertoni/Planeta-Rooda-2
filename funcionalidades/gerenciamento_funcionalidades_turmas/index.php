<!--
	Escolha da turma que será editada em gerenciamento_funcionalidades_turmas.php
-->
<?php
	if(isset($_GET['erro']) and isset($_GET['turma'])){
		?>
		Desculpe, não foi possível encontrar a turma <?=$_GET['turma']?>.<br>
		<?php
	}
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>

<style type="text/css"> 
	body{background-color:#FFFFFF;} /*Necessário, pois quando exibido em lightbox o fundo fica preto.*/
</style>
<form name="gerenciar_funcionalidades_turma" method="get" action="gerenciamento_funcionalidades_turmas.php">

Digite o nome da turma que será editada: <input type="text" name="nomeTurma">

<input type="submit" value="Editar Turma">
</form>

</body>
</html>
