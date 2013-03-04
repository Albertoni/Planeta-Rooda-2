<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../file.class.php");

$idFile = (int)$_GET['idFile'];
$titulo = $_GET['titulo'];
$autor = $_GET['autor'];
$nome = $_GET['nome'];
$tags = $_GET['tags'];

if (isset($_GET['t']) and $_GET['t'] == 'a'){
	global $tabela_arquivos;
	$consulta = new conexao();
	$consulta->solicitar("UPDATE $tabela_arquivos 
		SET titulo='$titulo',autor='$autor',nome='$nome',tags='$tags' WHERE arquivo_id=$idFile");
	
	if ($consulta->erro != ""){
		die("Erro na atualização da tabela de arquivos!");
	}
} else if (isset($_GET['t']) and $_GET['t'] == 'l'){
	global $tabela_links;
	$consulta = new conexao();
	$consulta->solicitar("UPDATE $tabela_links 
		SET titulo='$titulo',autor='$autor',endereco='$nome',tags='$tags' WHERE Id=$idFile");
	
	if ($consulta->erro != ""){
		die("Erro na atualização da tabela de links!");
	}
} else {
	die("Tipo errado passado para a página, favor apertar F5 e tentar de novo");
}

global $tabela_materiais;
$materiais= new conexao();
$materiais->solicitar("UPDATE $tabela_Materiais 
SET titulo='$titulo',autor='$autor',material='$nome',palavras='$tags' WHERE codMaterial=$idFile");

if ($materiais->erro != ""){
	echo 'Erro na edição da tabela de materiais!';
}else{
	echo '1';
}?>
