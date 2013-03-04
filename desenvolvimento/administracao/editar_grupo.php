<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");

	$consulta = new conexao();
	$id = $_GET['id'];
	if($id != null){
		$consulta->solicitar("SELECT * FROM $tabela_grupos WHERE grupo_id = '$id'");
	} else if (is_numeric($id) == false) {
		die('you have been injection-blocked');
	} else {
		die('N&atilde;o foi fornecida id de planeta. Por favor utilize <a href="manutencao_planetas.php">esta p&aacute;gina</a> para selecionar um planeta.');
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Edição de planetas</title>
	<script language="javascript">
		var donos; // global just because it's easier, mang
		var nomes;
		var frame;

		function marcaboxes(ac, ed){
			if (ac > 0){
				document.getElementsByName('acesso')[0].checked = true;
			}
			if (ed > 0) {
				document.getElementsByName('edicao')[0].checked = true;
			}
		}

		function mostrardonos (){
			if (document.getElementById('users').style.display == 'block')
				document.getElementById('users').style.display = 'none';
			else
				document.getElementById('users').style.display = 'block';
		}
	
		function change_owner(id){
			var array       = id.split(";"); //Aqui é onde se espera que ninguém tenha ponto-e-vírgula no nome.
			var split_nome  = array.pop();
			var split_id    = array.pop();
			
			donos = split_id;
			nomes = split_nome;
			
			update_form();
		}

		function update_form(){
			document.getElementById('dono').value = nomes;
			document.getElementById('id_dono').value = donos;
		}
		
		function setglobals(){
			frame = document.getElementById('usersframe');
		}
	</script>
</head>

<body onload="marcaboxes(<?=$consulta->resultado['grupo_acesso_livre']?>,<?=$consulta->resultado['grupo_edicao_livre']?>);setglobals()">
<form method="post" action="processa_edicao_grupo.php">
	Nome do grupo: <input name="nome" type="text" value="<?=$consulta->resultado['grupo_nome']?>" /> <br />
	Nível do grupo: <input name="nivel" type="text" value="<?=$consulta->resultado['grupo_nivel']?>" /> <br />
	Grupo pai: <input name="pai" type="text" value="<?=$consulta->resultado['grupo_pai_id']?>" /> <br />
	Acesso livre? <input name="acesso" type="checkbox" value="acesso" /> <br />
	Edição livre? <input name="edicao" type="checkbox" value="edicao" /> <br />
	Dono do terreno: <input id="dono" name="dono" type="text" readonly="readonly" /> <a href="#" onclick="mostrardonos()">Selecionar dono</a>
		<div id="users" style="display:none">
			<iframe id="usersframe" src="selecionar_single_user_iframe.php" >
				Por favor atualize seu navegador. Planeta ROODA recomenda <a href="http://www.getfirefox.com">Mozilla Firefox.</a>
			</iframe>
		</div> <br />
	<input type="submit" value="Enviar!" />
	<input type="hidden" name="id_grupo" value="<?=$consulta->resultado['grupo_id']?>" />
	<input type="hidden" id="id_dono" name="id_dono"/>
</form>
</body>
</html>
