
<?php

//include 'library/config.php';
//include 'library/opendb.php';

require_once("../../cfg.php");
require_once("../../bd.php");
//require_once("file.class.php");

global $tabela_arquivos;
$id = $_GET['id'];
$consulta = new conexao();
$consulta->solicitar("SELECT nome,tipo,tamanho,arquivo FROM $tabela_arquivos WHERE arquivo_id = '$id'");

$nome = $consulta->resultado["nome"];
$tipo = $consulta->resultado["tipo"];
$tamanho = $consulta->resultado["tamanho"];
$fileContent = $consulta->resultado["arquivo"];


header("Content-length: $tamanho");
header("Content-type: $tipo");
header("Content-Disposition: attachment; filename=\"$nome\"");
echo $fileContent;

exit;
?>

