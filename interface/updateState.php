<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

$user = usuario_sessao();
if ((!$user) or (!usuarioPertenceTurma($user->getId(), $POST['turma']))) die ("ERRO USUARIO NAO LOGADO");

