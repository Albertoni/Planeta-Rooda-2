<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
$queryComentarios = new conexao();
$queryComentarios->solicitar("SELECT * FROM $tabela_ArteComentarios WHERE CodDesenho = 3");
print_r($queryComentarios);
?>
