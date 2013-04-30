<?php
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../file.class.php");
require_once("../../usuarios.class.php");
require_once("../../funcoes_aux.php");

$idFile = isset($_GET['idFile']) ? (int)$_GET['idFile'] : die("Erro: nenhuma ID de material foi enviada.");
$turma = isset($_SESSION['biblio_turma']) ? $_SESSION['biblio_turma'] : die("Erro: A ID da turma não foi passada corretamente. Feche seu navegador e tente novamente.");
global $tabela_Materiais;

$permissoes = checa_permissoes(TIPOBIBLIOTECA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

if($usuario->podeAcessar($permissoes['biblioteca_excluirArquivos'], $turma)){
	if(isset($_GET['t']) and $_GET['t'] == 'a'){ // arquivo
		$con = new conexao();
		$con->solicitar("SELECT refMaterial from $tabela_Materiais WHERE codMaterial = $idFile");
		
		$delete = new conexao();
		$delete->solicitar("DELETE FROM arquivos WHERE arquivo_id = ".$con->resultado['refMaterial']);
	
		if ($delete->erro == ""){
			$material = new conexao();
			$material->solicitar("DELETE FROM $tabela_Materiais WHERE codMaterial = $idFile");
			
			if($material->erro == "")
				echo '1';
			else
				echo "Ocorreu algum erro na consulta ao banco de dados para o tipo A, tente novamente mais tarde!";
		}else{
			echo "Erro ao apagar o arquivo do banco de dados, tente novamente mais tarde!";
		}
	
	} else if(isset($_GET['t']) and $_GET['t'] == 'l'){ // link
		global $tabela_links;
		$con = new conexao();
		$con->solicitar("SELECT refMaterial from $tabela_Materiais WHERE codMaterial = $idFile");
		
		$con->solicitar("DELETE FROM $tabela_links WHERE Id = ".$con->resultado['refMaterial']);
		
		if ($con->erro == ""){
			$material = new conexao();
			$material->solicitar("DELETE FROM $tabela_Materiais WHERE codMaterial = $idFile");
			if($material->erro == "")
				echo '1';
			else
				echo "Ocorreu algum erro na consulta ao banco de dados para o tipo T, tente novamente mais tarde!";
		}else{
			echo "Ocorreu erro na consulta ao banco de dados para pegar a referencia do material, tente novamente mais tarde.";
		}
	}else{
		die("Tipo errado passado para a página, favor apertar F5 e tentar de novo");
	}
}else{
	die("Você não tem permissão para deletar coisas na biblioteca.");
}
