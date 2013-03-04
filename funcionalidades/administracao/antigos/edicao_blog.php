<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");

	$consulta = new conexao();
	$usuario_id = $_SESSION['SS_usuario_id'];
	$consulta->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = '$usuario_id'");
	$nome = $consulta->resultado['usuario_nome'];	// Sabe como é, coordenador/diretor velho acha mágico ler "Bem vindo, Fulanante".
	
	$blog_id = $_GET['blog_id'];
	if ($blog_id == NULL){
		die("A id do blog &eacute; necess&aacute;ria, por favor <a href=\"manutencao_blogs.php\">volte</a> e tente novamente.");
	}
	if (is_numeric($blog_id) == false){
		die("SQL injection? That's a paddlin' &#x266B;");
	}
	
	$consulta->solicitar("SELECT Title, OwnersIds, Tipo FROM $tabela_blogs WHERE Id = $blog_id");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Manutenção de blogs</title>
	<script language="javascript">
	
		var donos; // global just because it's easier, mang
		var nomes;
		var frame;
		
		function setglobals(){
			donos = document.getElementById('donos').value.split(";");
			nomes = document.getElementById('nomes').value.split(";");
			frame = document.getElementById('usersframe');
		}
	
		function mostrardonos (){
			if (document.getElementById('users').style.display == 'block')
				document.getElementById('users').style.display = 'none';
			else
				document.getElementById('users').style.display = 'block';
		}
		
		function update_form(){
			document.getElementById('nomes').value = nomes.join(", "); // legibilidade
			document.getElementById('donos').value = donos.join(';'); // servidor, parseie com facilidade!
		}
		
		function add_owner(id){
			var array       = id.split(";"); //Aqui é onde se espera que ninguém tenha ponto-e-vírgula no nome.
			var split_nome  = array.pop();
			var split_id    = array.pop();
			
			donos.unshift(split_id);
			nomes.unshift(split_nome); // joga no começo da lista pra o usuário ver mais fácil
			
			update_form();
		}
		
		function remove_owner(id){
			var array       = id.split(";");
			var split_nome  = array.pop();
			var split_id    = array.pop();
			var i = 0;
			
			// Gambiarra pra remover o item do array.
			var newarray = [];
			var anothernewarray = [];
			while (donos[i] != undefined){
				if (donos[i] != split_id){
					newarray.push(nomes[i]);
					anothernewarray.push(donos[i]);
				}
				i++;
			}
			nomes = newarray;
			donos = anothernewarray;
			update_form();
		}
		
		function marcar_frame(ids){
			lista = ids.split(";");
			i=0;
			
			while (lista[i] != undefined){
				frame.contentWindow.document.getElementById(lista[i]).checked = true;
				i++;
			}
		}
	</script>
</head>

<body onload="setglobals();marcar_frame('<?=$consulta->resultado['OwnersIds']?>')">
	Bem vindo, <?=$nome?>!
	<form method="post" action="processa_edicao_blog.php">
		Nome: <input type="text" name="Title" value="<?=$consulta->resultado['Title']?>" /><br />
		Dono(s): <input type="textarea" id="nomes" name="NameOwners" readonly="readonly" value="<?=urldecode($_GET['user_names']);?>" /> <a href="#" onclick="mostrardonos()">Selecionar donos</a><br />
		<div id="users" style="display:none">
			<iframe id="usersframe" src="edicao_blog_iframe.php" >
				Por favor atualize seu navegador. Planeta ROODA recomenda <a href="http://www.getfirefox.com">Mozilla Firefox.</a>
			</iframe>
		</div>
		Tipo (1- Pessoal, 2- Coletivo): <input type="text" name="Tipo" value="<?=$consulta->resultado['Tipo']?>" /><br />
		<input type="hidden" name="Id" value="<?=$blog_id?>" />
		<input type="hidden" id="donos" name="OwnersIds" value="<?=$consulta->resultado['OwnersIds']?>" />
		<input type="submit" name="confirmar" value="Confirmar" />
	</div>
</body>
</html>
	
