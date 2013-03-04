<html>
<head>
<title>Download File From MySQL</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

</body>
</html>
<?php

//include 'library/config.php';
//include 'library/opendb.php';

require_once("cfg.php");
require_once("bd.php");
//require_once("file.class.php");
echo("dups");

$consulta = new conexao();
$consulta->connect();
$consulta->solicitar("SELECT * FROM files WHERE arquivo_id = 5");



$id = $consulta->resultado["arquivo_id"];
$nome = $consulta->resultado["nome"];
$tipo = $consulta->resultado["tipo"];
$tamanho = $consulta->resultado["tamanho"];
$fileContent = $consulta->resultado["arquivo"];
$funcionalidade_tipo = $consulta->resultado["funcionalidade_tipo"];
$funcionalidade_id = $consulta->resultado["funcionalidade_id"];

header("Content-length: $tamanho");
header("Content-type: $tipo");
header("Content-Disposition: attachment; filename=$nome");
echo $fileContent;

exit;



?>

