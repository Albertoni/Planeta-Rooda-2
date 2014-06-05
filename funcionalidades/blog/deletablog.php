<?php
require_once("blog.class.php");
require_once("../../usuarios.class.php");
require_once("../../funcoes_aux.php");
require_once("../../cfg.php");
require_once("../../bd.php");

$user = usuario_sessao();
if($user === false){
	die("nao esta logado");
}
$usuario_id = $user->getId();

$idBlog = isset($_GET['id']) ? ((int) $_GET['id']) : die("Favor acessar essa página com uma id setada muito obrigado até logo desculpequalquercoisaesperoquegostedoplanetarooda");


$b = new Blog($_GET['id']);
$b->deletar($user);
