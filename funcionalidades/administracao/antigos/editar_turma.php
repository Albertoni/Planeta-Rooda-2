<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	
	global $nivelAdmin;
	
	/*if ($_SESSION['SS_usuario_nivel_sistema'] != $nivelAdmin){
		die("Você precisa ter nivel de administrador para acessar esta função.");
	}*/

	$id = $_GET['id'];
	if ($id == NULL){
		die("A id do blog &eacute; necess&aacute;ria, por favor <a href=\"manutencao_turmas.php\">volte</a> e tente novamente.");
	}
	if (is_numeric($id) == false){
		die("SQL injection? That's a paddlin' &#x266B;");
	}

	$consulta = new conexao();
	$consulta->solicitar("SELECT * FROM $tabela_turmas WHERE codTurma = $id");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Edição de turmas</title>
	<script type="text/javascript">	
		function contadorDescricao(){
			numChars = document.getElementById('desc').value.length;
			document.getElementById('numchars').innerHTML = numChars + '/5000 caracteres';
		}
		
		var donos; // global just because it's easier, mang
		var nomes;
		var frame;
		
		function setglobals(){
			frame = document.getElementById('usersframe');
		}
	
		function mostrardonos (){
			if (document.getElementById('users').style.display == 'block')
				document.getElementById('users').style.display = 'none';
			else
				document.getElementById('users').style.display = 'block';
		}
		
		function update_form(){
			document.getElementById('nomes').value = nomes;
			document.getElementById('donos').value = donos;
		}
		
		function change_owner(id){
			var array       = id.split(";"); //Aqui é onde se espera que ninguém tenha ponto-e-vírgula no nome.
			var split_nome  = array.pop();
			var split_id    = array.pop();
			
			donos = split_id;
			nomes = split_nome;
			
			update_form();
		}
		
		function validar(){
			var error = "Os seguintes erros foram encontrados:\n"
			var flag_erro = 0;
			
			if (document.forms['form']['titulo'].value == null || document.forms['form']['titulo'].value == ""){
				error += "Nome da Turma não pode ser vazio.\n";
				flag_erro++;
			}
			if (document.forms['form']['nomes'].value == null || document.forms['form']['nomes'].value == ""){
				error += "É necessário escolher um professor para a turma.\n";
				flag_erro++;
			}
			if (flag_erro > 0){
				alert(error);
				return false;
			}
			return true;
		}
	</script>
</head>
<body onload="contadorDescricao();setglobals()">
	<form method="post" action="processa_edicao_turma.php">
		Nome da Turma: <input type="text" name="nomeTurma" value="<?=$consulta->resultado['nomeTurma']?>" /> <br />
		Disciplina: <input type="text" name="nomeDisciplina" value="<?=$consulta->resultado['nomeDisciplina']?>" /> <br />
		Descrição: <textarea rows="3" cols="30" id="desc" name="descricao" onkeyup="contadorDescricao()"><?=$consulta->resultado['descricao']?></textarea> <br />
		<div id="numchars">Por favor ligue o Javascript em seu browser.</div>
		Série: <input type="text" name="serie" value="<?=$consulta->resultado['serie']?>" /> <br />
		Professor responsável: <input type="text" id="nomes" name="NameOwners" readonly="readonly" /> <a href="#" onclick="mostrardonos()">Selecionar dono</a>
		<div id="users" style="display:none">
			<iframe id="usersframe" src="selecionar_professor_iframe.php" width="100%">
				Por favor atualize seu navegador. Planeta ROODA recomenda <a href="http://www.getfirefox.com">Mozilla Firefox.</a>
			</iframe>
		</div>
		<input type="hidden" name="codTurma" value="<?=$id?>" />
		<input type="hidden" id="donos" name="idprof" /> <br />
		<input type="submit" value="Enviar" />
	</form>
</body>
</html>
