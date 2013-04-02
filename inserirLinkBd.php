<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");


//*********************************************************
//Esta inserindo no BD apenas o endereco do link, funcionalidade_id e funcionalidade_tipo. 
//Corrigir no futuro
//*********************************************************

global $tabela_links;

// pré-processamento
$funcionalidade_tipo = TIPOPORTFOLIO;

$consulta = new conexao();

$funcionalidade_id = (int) $_POST['projeto_id'];
$endereco = $_POST['newLink'];
if (strpos($endereco, 'http://') !== 0){
	$endereco = "http://".$endereco;
}

// prevencao contra injecao de SQL
$enderecoSQL = $consulta->sanitizaString($endereco);

$consulta->solicitar(
"INSERT INTO $tabela_links 
(  endereco, funcionalidade_tipo, funcionalidade_id, uploader_id) VALUES
('$enderecoSQL','$funcionalidade_tipo','$funcionalidade_id', '".$_SESSION['SS_usuario_id']."');");
?>
<!DOCTYPE html>
<html>
<head>
<title>Insere Link</title>
</head>
<body>
<ul class="ajax_return">
	<li>endereco: <span id="endereco"><?=$endereco?></span></li>
	<li>funcionalidade_tipo: <span id="funcionalidade_tipo"><?=$funcionalidade_tipo?></span></li>
	<li>funcionalidade_id: <span id="funcionalidade_id"><?=$funcionalidade_id?></span></li>
</ul>
<?
$turma = is_numeric($_POST['turma_id']) ? $_POST['turma_id'] : die("Oops! Algo de errado aconteceu, mas o link provavelmente foi adicionado com sucesso. Por favor, volte e tudo deverá funcionar corretamente.");

?>
<script type=\"text/javascript\">document.location.href=\"portfolio_projeto.php?projeto_id=$funcionalidade_id&turma=$turma\";</script>
</body>
