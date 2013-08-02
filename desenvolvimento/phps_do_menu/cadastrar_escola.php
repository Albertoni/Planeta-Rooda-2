<?php
	session_start();
header('Content-Type: text/html; charset=utf-8');

	require_once("../../cfg.php");
	require_once("../../bd.php");

	$nomeEscola = $_POST['nome'];
	$operacaoRealizadaComSucesso = true;
	$mensagemDeErro = '';
	
	$conexao_cadastro = new conexao();
	$conexao_cadastro->solicitar("SELECT * FROM Escolas");
	$escolaJaCadastrada = false;
	if(0 < $conexao_cadastro->registros){
		$escolaJaCadastrada = true;
	}
	
	$conexao_cadastro->solicitar("INSERT INTO Escolas (nome) VALUES ('$nomeEscola')");
	if($conexao_cadastro->erro != ''){
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = 'Desculpe, houve um erro ao gravar os dados no banco.';
	} else if($escolaJaCadastrada){
		$conexao_cadastro->solicitar("DELETE FROM Escolas");
		$conexao_cadastro->solicitar("INSERT INTO Escolas (nome) VALUES ('$nomeEscola')");
		if($conexao_cadastro->erro != ''){
			$operacaoRealizadaComSucesso = false;
			$mensagemDeErro = 'Desculpe, houve um erro ao gravar os dados no banco.';
		} else {
			$mensagemDeErro = 'O nome da escola foi modificado com sucesso.';
		}
	}
	
	$dados.='&operacaoRealizadaComSucesso='.$operacaoRealizadaComSucesso;
	$dados.='&mensagemDeErro='.$mensagemDeErro;
	
    echo $dados;	
//A partir do fim do php, não escrever absolutamente nada. Nem código.
?>
