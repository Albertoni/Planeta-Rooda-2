<?php
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

$idFile = isset($_GET['idFile']) ? (int)$_GET['idFile'] : die("Erro: nenhuma ID de material foi enviada.");
$turma = isset($_SESSION['biblio_turma']) ? $_SESSION['biblio_turma'] : die("Erro: A ID da turma não foi passada corretamente. Feche seu navegador e tente novamente.");
global $tabela_Materiais;

$permissoes = checa_permissoes(TIPOBIBLIOTECA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

if($usuario->podeAcessar($permissoes['biblioteca_aprovarMateriais'], $turma)){
	$q = new conexao();
	$q->solicitar("UPDATE $tabela_Materiais SET materialAprovado = 1 WHERE codMaterial = '$idFile'");
	
	if($q->erro == ""){
		echo 1;
	}else{
		die("Erro ao tentar aprovar o material.");
	}
}
?>
