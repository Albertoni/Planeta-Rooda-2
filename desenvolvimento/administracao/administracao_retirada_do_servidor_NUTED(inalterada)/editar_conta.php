<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");

	$consulta = new conexao();
	$id = $_GET['id'];
	if($id != null){
		$consulta->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id = '$id'");
	} else {
		die('N&atilde;o foi fornecida id de usu&aacute;rio. Por favor utilize <a href="manutencao_contas.php">esta p&aacute;gina</a> para selecionar um usu&aacute;rio.');
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Edição de usuários</title>
	<script language="javascript">
	function marcaradio(nivel){
		itens = document.getElementsByName('nivel'); // Não confunda com o argumento!
		for (i=0;i<itens.length;i++){
			if(itens[i].value == nivel){
				itens[i].checked = true;
			}
		}
	}
	</script>
</head>

<body onload="marcaradio(<?=$consulta->resultado['usuario_nivel']?>)">
<form method="post" action="processa_edicao_conta.php">
	Nome do aluno: <input name="nome" type="text" value="<?=$consulta->resultado['usuario_nome']?>" /> <br />
	Login do aluno: <input name="login" type="text" value="<?=$consulta->resultado['usuario_login']?>" /> <br />
	Email do aluno: <input name="email" type="text" value="<?=$consulta->resultado['usuario_email']?>" /> <br />
	Data de nascimento: <input name="data" type="text" value="<?=$consulta->resultado['usuario_data_aniversario']?>" /> <br />
<div style="white-space:pre">Nivel do usuario:&#9;<input name="nivel" type="radio" value="0" /> Administrador
&#9;&#9;&#9;&#9;<input name="nivel" type="radio" value="5" /> Coordenador
&#9;&#9;&#9;&#9;<input name="nivel" type="radio" value="10" /> Professor
&#9;&#9;&#9;&#9;<input name="nivel" type="radio" value="20" /> Aluno
&#9;&#9;&#9;&#9;<input name="nivel" type="radio" value="100" /> Visitante </div>
	Nova senha: <b>(Deixe vazio para não trocá-la)</b> <input name="novasenha" type="text"/> <br />
	<input type="submit" value="Enviar!" />
	<input type="hidden" name="id" value="<?=$consulta->resultado['usuario_id']?>" />
</form>
</body>
</html>
