<?php
header('Content-Type: application/json');
session_start();
require_once('../../cfg.php');
require_once('../../bd.php');
require_once('comment.class.php');

$id_usuario = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
$id_post = isset($_GET['post']) ? (int) $_GET['post'] : 0;
if ($id_usuario <= 0) {
    $json['errors'][] = 'Sua sessão expirou.';
    $json['errors'][] = 'Volte à tela inicial para efetuar o login novamente';
} else {
    $usuario = new Usuario($id_usuario);
}
