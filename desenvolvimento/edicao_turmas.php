<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require_once("../../cfg.php");
	require_once("../../bd.php");
	
	$operacaoRealizadaComSucesso = true;
	$mensagemDeErro = "";
	$identificacao = $_POST['identificacao'];
	$nome = $_POST['nome'];
	$descricao = $_POST['descricao'];
	$professor = $_POST['professor'];
	$numeroProfessores = $_POST['numeroProfessores'];
	$numeroMonitores = $_POST['numeroMonitores'];
	$numeroAlunos = $_POST['numeroAlunos'];
	
	$conexaoBuscaProfessores = new conexao();
	$conexaoBuscaMonitores = new conexao();
	$conexaoBuscaAlunos = new conexao();
	
	$sqlBuscaProfessores = "SELECT * FROM $tabela_usuarios WHERE ";
	for($i=0; $i<$numeroProfessores; $i++){
		if($i != 0){
			$sqlBuscaProfessores .= " OR ";
		}
		$sqlBuscaProfessores .= "usuario_nome = '".$_POST['professor'.$i]."'";
	}
	
	$sqlBuscaMonitores = "SELECT * FROM $tabela_usuarios WHERE ";
	for($i=0; $i<$numeroMonitores; $i++){
		if($i != 0){
			$sqlBuscaMonitores .= " OR ";
		}
		$sqlBuscaMonitores .= "usuario_nome = '".$_POST['monitor'.$i]."'";
	}
	
	$sqlBuscaAlunos = "SELECT * FROM $tabela_usuarios WHERE ";
	for($i=0; $i<$numeroAlunos; $i++){
		if($i != 0){
			$sqlBuscaAlunos .= " OR ";
		}
		$sqlBuscaAlunos .= "usuario_nome = '".$_POST['aluno'.$i]."'";
	}
		
	$conexaoBuscaProfessores->registros = 0;
	if(0 < $numeroProfessores){
		$conexaoBuscaProfessores->solicitar($sqlBuscaProfessores);
	}
	
	$conexaoBuscaMonitores->registros = 0;
	if(0 < $numeroMonitores){
		$conexaoBuscaMonitores->solicitar($sqlBuscaMonitores);
	}
	
	$conexaoBuscaAlunos->registros = 0;
	if(0 < $numeroAlunos){
		$conexaoBuscaAlunos->solicitar($sqlBuscaAlunos);
	}
	
	if($conexaoBuscaProfessores->erro != '' or  $conexaoBuscaProfessores->registros < $numeroProfessores){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = utf8_encode("Não foi possível encontrar um dos professores informados.");
	}
	if($conexaoBuscaMonitores->erro != '' or  $conexaoBuscaMonitores->registros < $numeroMonitores){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = utf8_encode("Não foi possível encontrar um dos monitores informados.");
	}
	if($conexaoBuscaAlunos->erro != '' or  $conexaoBuscaAlunos->registros < $numeroAlunos){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = utf8_encode("Não foi possível encontrar um dos alunos informados.");
	}
	
	//Dados Atuais
	$pesquisaDadosAtuais = new conexao();
	$pesquisaDadosAtuais->solicitar("SELECT * FROM $tabela_turmas WHERE codTurma = $identificacao");
	$nomeAtual = $pesquisaDadosAtuais->resultado['nomeTurma'];
	$descricaoAtual = $pesquisaDadosAtuais->resultado['descricao'];
	$idProfessorAtual = $pesquisaDadosAtuais->resultado['profResponsavel'];
	
	//Tabela Turmas
	$pesquisaEdicaoTurmasSQL = "UPDATE $tabela_turmas SET ";
	if($nome != null){
		$pesquisaEdicaoTurmasSQL.="nomeTurma = '$nome', ";
	}
	else{
		$pesquisaEdicaoTurmasSQL.="nomeTurma = '$nomeAtual', ";
	}
	if($descricao != null){
		$pesquisaEdicaoTurmasSQL.="descricao = '$descricao', ";
	}
	else{
		$pesquisaEdicaoTurmasSQL.="descricao = '$descricaoAtual', ";
	}
	if($professor != null){
		$pesquisaProfessor = new conexao();
		$pesquisaProfessor->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_nome = '$professor'");
											 
		if($pesquisaProfessor->registros == 1){
			$codigoDoProfessor = $pesquisaProfessor->resultado['usuario_id'];
			$pesquisaEdicaoTurmasSQL.="profResponsavel = $codigoDoProfessor";
		}
		else if($pesquisaProfessor->registros > 1){
			$mensagemDeErro = utf8_encode("Há mais de uma pessoa com este nome.");
			$operacaoRealizadaComSucesso = false;
		}
		else{
			$mensagemDeErro = utf8_encode("Não foi possível encontrar essa pessoa.");
			$operacaoRealizadaComSucesso = false;
		}
	}
	else{
		$pesquisaEdicaoTurmasSQL.="profResponsavel = $idProfessorAtual";
	}
	$pesquisaEdicaoTurmasSQL.=" WHERE codTurma = $identificacao";
	
	if($operacaoRealizadaComSucesso){
		$pesquisaEdicaoTurmas = new conexao();
		$pesquisaEdicaoTurmas->solicitar($pesquisaEdicaoTurmasSQL);
		if($pesquisaEdicaoTurmas->erro != ''){
			$operacaoRealizadaComSucesso = false;
			$mensagemDeErro = "Erro na pesquisa.";
		} 
		
		if($operacaoRealizadaComSucesso){
			$pesquisaEdicaoTurmas->solicitar("DELETE FROM TurmasUsuario 
											WHERE codTurma = $identificacao
												AND associacao = $nivelProfessor");
		}
		
		if($operacaoRealizadaComSucesso and 0 < $numeroProfessores){
			$sqlInsercaoProfessores = "INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES ";
			for($i=0; $i<$numeroProfessores; $i++){
				if($i != 0){
					$sqlInsercaoProfessores .= ",";
				}
				$sqlInsercaoProfessores .= " (".$identificacao.",".($conexaoBuscaProfessores->resultado['usuario_id']).",".$nivelProfessor.")";
				$conexaoBuscaProfessores->proximo();
			}
			$pesquisaEdicaoTurmas->solicitar($sqlInsercaoProfessores);
			if($pesquisaEdicaoTurmas->erro != ''){
				$operacaoRealizadaComSucesso = false;
				$mensagemDeErro = utf8_encode("Erro na gravação dos professores.");
			}
		}
		
		if($operacaoRealizadaComSucesso){
			$pesquisaEdicaoTurmas->solicitar("DELETE FROM TurmasUsuario 
											WHERE codTurma = $identificacao
												AND associacao = $nivelMonitor");
		}
		
		if($operacaoRealizadaComSucesso and 0 < $numeroMonitores){
			$sqlInsercaoMonitores = "INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES ";
			for($i=0; $i<$numeroMonitores; $i++){
				if($i != 0){
					$sqlInsercaoMonitores .= ",";
				}
				$sqlInsercaoMonitores .= " (".$identificacao.",".($conexaoBuscaMonitores->resultado['usuario_id']).",".$nivelMonitor.")";
				$conexaoBuscaMonitores->proximo();
			}
			$pesquisaEdicaoTurmas->solicitar($sqlInsercaoMonitores);
			if($pesquisaEdicaoTurmas->erro != ''){
				$operacaoRealizadaComSucesso = false;
				$mensagemDeErro = utf8_encode("Erro na gravação dos monitores.");
			}
		}
		
		if($operacaoRealizadaComSucesso){
			$pesquisaEdicaoTurmas->solicitar("DELETE FROM TurmasUsuario 
											WHERE codTurma = $identificacao
												AND associacao = $nivelAluno");
		}
		
		if($operacaoRealizadaComSucesso and 0 < $numeroAlunos){
			$sqlInsercaoAlunos = "INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES ";
			for($i=0; $i<$numeroAlunos; $i++){
				if($i != 0){
					$sqlInsercaoAlunos .= ",";
				}
				$sqlInsercaoAlunos .= " (".$identificacao.",".($conexaoBuscaAlunos->resultado['usuario_id']).",".$nivelAluno.")";
				$conexaoBuscaAlunos->proximo();
			}
			$pesquisaEdicaoTurmas->solicitar($sqlInsercaoAlunos);
			if($pesquisaEdicaoTurmas->erro != ''){
				$operacaoRealizadaComSucesso = false;
				$mensagemDeErro = utf8_encode("Erro na gravação dos alunos.");
			}
		}
	}
	
	$dados = '&operacaoRealizadaComSucesso'    .'='.$operacaoRealizadaComSucesso;
	$dados.= '&mensagemDeErro'                 .'='.$mensagemDeErro;
	
    echo $dados;	
?>
