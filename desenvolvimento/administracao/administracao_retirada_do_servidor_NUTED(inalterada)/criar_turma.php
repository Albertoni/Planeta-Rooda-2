<?php
	session_start();
	
	require("file:///C|/DOCUME~1/NUTED/CONFIG~1/Temp/scp54200/planeta2/cfg.php");
	require("file:///C|/DOCUME~1/NUTED/CONFIG~1/Temp/scp54200/planeta2/bd.php");
	require("file:///C|/DOCUME~1/NUTED/CONFIG~1/Temp/scp54200/planeta2/funcoes_aux.php");
	require("file:///C|/DOCUME~1/NUTED/CONFIG~1/Temp/scp54200/planeta2/usuarios.class.php");
    require("file:///C|/DOCUME~1/NUTED/CONFIG~1/Temp/scp54200/planeta2/login.class.php");	
	require("file:///C|/DOCUME~1/NUTED/CONFIG~1/Temp/scp54200/planeta2/file.class.php");
	require("file:///C|/DOCUME~1/NUTED/CONFIG~1/Temp/scp54200/planeta2/link.class.php");
	
	global $nivelAdmin;
	
	/*if ($_SESSION['SS_usuario_nivel_sistema'] != $nivelAdmin){
		die("Você precisa ter nivel de administrador para acessar esta função.");
	}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Criação de turmas</title>
	<script type="text/javascript">
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
				error += "Nome do planeta não pode ser vazio.\n";
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

<body onload="setNumChars();setglobals()">

	
	<form name="form" method="post" action="file:///C|/DOCUME~1/NUTED/CONFIG~1/Temp/scp54200/planeta2/funcionalidades/administracao/processa_cria_planeta.php" onsubmit="return validar()">
		Nome do Planeta: <input type="text" name="titulo" /> <br />
		Planeta pai: <input type="text" id="nomes" name="NameOwners" readonly="readonly" /> <a href="#" onclick="mostrardonos()">Selecionar pai</a>
		<div id="users" style="display:none">
			<iframe id="usersframe" src="selecionar_terreno_iframe.php" >
				Por favor atualize seu navegador. Planeta ROODA recomenda <a href="http://www.getfirefox.com">Mozilla Firefox.</a>
			</iframe>
		</div> <br />
		Tema do terreno: <b>*fazer varios radios pros temas diferentes*</b>  <br />
		Clima: <b>*fazer varios radios pros temas diferentes*</b>  <br />
		Solo do terreno: <b>*fazer varios radios pros temas diferentes*</b>  <br />
		Avatar Default: ISSO EXISTE POR ACASO? <br />
		Escola: <input type="text" name="escola" /> <br />
		<input type="hidden" id="donos" name="idprof" />
		<input type="submit" value="Criar turma" /> <br />
	</form>
	
</body>
</html>
