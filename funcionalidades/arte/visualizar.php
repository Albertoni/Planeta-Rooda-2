<?php
//	arquivo de testes dos desenhos

session_start();

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");
require("desenho.class.php");

$post_id = 1; //TODO: DEBUG
$user_id = $_SESSION['SS_usuario_id'];
$desenho = $_GET['desenho'];

$DESENHO = new Desenho($desenho); // ótimos nomes =p
echo $DESENHO->visualizar(700,0);

?>