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

// prevenção de injeção sql
$funcionalidade_id = mysql_real_escape_string($_POST['projeto_id']);
$endereco = mysql_real_escape_string($_POST['newLink']);
if (strpos($endereco, 'http://') !== 0){
	$endereco = "http://".$endereco;
}

$consulta->solicitar(
"INSERT INTO $tabela_links 
(  endereco, funcionalidade_tipo, funcionalidade_id, uploader_id) VALUES
('$endereco','$funcionalidade_tipo','$funcionalidade_id', '".$_SESSION['SS_usuario_id']."');");


$turma = is_numeric($_POST['turma_id']) ? $_POST['turma_id'] : die("Oops! Algo de errado aconteceu, mas o link provavelmente foi adicionado com sucesso. Por favor, volte e tudo deverá funcionar corretamente.");
echo "<script type=\"text/javascript\">document.location.href=\"portfolio_projeto.php?projeto_id=$funcionalidade_id&turma=$turma\";</script>";

?>
