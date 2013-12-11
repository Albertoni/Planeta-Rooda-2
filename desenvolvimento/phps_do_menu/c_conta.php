<?php
	session_start();

	require_once("../../cfg.php");
	require_once("../../bd.php");

//Vari�veis
	$operacaoRealizadaComSucesso = true;
	$mensagemDeErro = "";
	
//Dados para cadastro
	$nome = $_POST['nome'];
	$professor = $_POST['professor'];
	$descricao = $_POST['descricao'];
	
//Tabela Turmas
	//nome
	if($nome != null){
		//deduplica��o de nomes?
	}
	else{
		$mensagemDeErro.= "Erro no nome.";
		$operacaoRealizadaComSucesso = false;
	}
	//professor
	switch($professor){
		case null:	$mensagemDeErro.= "Erro no professor.";
					$operacaoRealizadaComSucesso = false;
		break;
		case "":	$codigoDoProfessor = 0;
		break;
		default:	$pesquisaProfessor = new conexao();
					$pesquisaProfessor->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_nome = '$professor'");
								 
					if($pesquisaProfessor->registros == 1){
						$codigoDoProfessor = $pesquisaProfessor->resultado['usuario_id'];
					}
					else if($pesquisaProfessor->registros > 1){
						$mensagemDeErro = "Ha mais de um professor com este nome.";
						$operacaoRealizadaComSucesso = false;
					}
					else{
						$mensagemDeErro = "Nao foi possivel encontrar este professor.";
						$operacaoRealizadaComSucesso = false;
					}
		break;
	}
	//descri��o
	if($descricao != null){
		//compress�o do texto?
	}
	else{
		$mensagemDeErro.= "Erro na descri��o.";
		$operacaoRealizadaComSucesso = false;
	}
	
//Cadastro
	if($operacaoRealizadaComSucesso){
		$cadastroTurma = new conexao();
		$cadastroTurma->solicitar("INSERT INTO $tabela_turmas (nomeTurma, profResponsavel, descricao) VALUES ('$nome', '$codigoDoProfessor', '$descricao')");
		
		//Chamar script de cadastro de planeta.
	}

//Exporta��o
	$dados = '&mensagemDeErro='.$mensagemDeErro; 
    $dados.= '$operacaoRealizadaComSucesso'.$operacaoRealizadaComSucesso;
	$exportar = utf8_encode($dados);

    echo $exportar;	
//A partir do fim do php, n�o escrever absolutamente nada. Nem c�digo. &numDadosEncontrados receber� TUDO o que for escrito.
?>
