<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
    require("../../login.class.php");	
	require("../../file.class.php");
	require("../../link.class.php");
	
	// DEFINIÇÕES DOS TIPOS DE BUSCA
	define("BUSCA_DONO", "1");
	define("BUSCA_BLOG", "2");
	
	$consulta = new conexao();
	$consulta_donos = new conexao(); // Para ser usado mais tarde
	$usuario_id = $_SESSION['SS_usuario_id'];
	$consulta->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = '$usuario_id'");
	$nome = $consulta->resultado['usuario_nome'];	// Sabe como é, coordenador/diretor velho acha mágico ler "Bem vindo, Fulanante".
	
?>



<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Manutenção de blogs</title>
	<script language="javascript">
		function apagar(id){
			if (confirm("Tem certeza que deseja apagar este blog?")){
				var myForm = document.createElement("form");
				myForm.method="post";
				myForm.action="processa_edicao_blog.php";
				
				var myInput = document.createElement("input");
				myInput.setAttribute("name", "Apagar");
				myInput.setAttribute("value", "1");
				myForm.appendChild(myInput);
				
				var myInput2 = document.createElement("input");
				myInput2.setAttribute("name", "Id");
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
	Bem vindo!
	
	<div style="white-space:pre"><form method="get" action="manutencao_blogs.php">
Pesquisar por nome do blog:&#9;<input type="text" name="nome" /> <input type="submit" value="Pesquisar"/>
	</form></div>
	<ul>
<?php
	$nome = $_GET['nome'];
	if ($nome == NULL){
		$consulta->solicitar("SELECT Id, Title, OwnersIds, Tipo FROM $tabela_blogs");
	} else {
		$consulta->solicitar("SELECT Id, Title, OwnersIds, Tipo FROM $tabela_blogs WHERE Title LIKE '%$nome%'");
	}
	
	for ($i=0; $i<count($consulta->itens); $i++){
		if ($i%2){ // Se for impar
			echo '	<li class="blog2">';}
		else { // É par. Derp.
			echo '	<li class="blog1">';}
			
		$id_donos = explode(';', $consulta->resultado['OwnersIds']);
		$num_donos = count($id_donos);
			
		echo "ID: ".$consulta->resultado['Id']." --- Nome do blog: ".$consulta->resultado['Title']." --- Donos: ";
		$donos = '';
		for ($j=0; $j<$num_donos; $j++) {
			$consulta_donos->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = '$id_donos[$j]'");
			$donos .= $consulta_donos->resultado['usuario_nome'].';';
			if ($j == $num_donos - 1)
				echo $consulta_donos->resultado['usuario_nome'];
			else
				echo $consulta_donos->resultado['usuario_nome'].', ';
		}
		
		
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"edicao_blog.php?blog_id=".$consulta->resultado['Id']."&user_names=".urlencode($donos)."\">EDITAR ESTE BLOG</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:apagar('".$consulta->resultado['Id']."')\">DELETAR ESTE BLOG</a><br />\n";
		
		
		$consulta->proximo();
	}
	
	$consulta_donos->fechar();
	
?>
	</ul>
</body>
</html>
