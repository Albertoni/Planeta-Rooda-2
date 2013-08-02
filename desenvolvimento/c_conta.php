<?php
	session_start();

	require_once("../../cfg.php");
	require_once("../../bd.php");

//Variáveis
	$operacaoRealizadaComSucesso = true;
	$mensagemDeErro = "";
	
//Dados para cadastro
	$nome = $_POST['nome'];
	$professor = $_POST['professor'];
	$descricao = $_POST['descricao'];
	
//Tabela Turmas
	//nome
	if($nome != null){
		//deduplicação de nomes?
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
	//descrição
	if($descricao != null){
		//compressão do texto?
	}
	else{
		$mensagemDeErro.= "Erro na descrição.";
		$operacaoRealizadaComSucesso = false;
	}
	
//Cadastro
	if($operacaoRealizadaComSucesso){
		$cadastroTurma = new conexao();
		$cadastroTurma->solicitar("INSERT INTO $tabela_turmas (nomeTurma, profResponsavel, descricao) VALUES ('$nome', '$codigoDoProfessor', '$descricao')");
		
		//Chamar script de cadastro de planeta.
	}

//Exportação
	$dados = '&mensagemDeErro='.$mensagemDeErro; 
    $dados.= '$operacaoRealizadaComSucesso'.$operacaoRealizadaComSucesso;
	$exportar = utf8_encode($dados);

    echo $exportar;	
//A partir do fim do php, não escrever absolutamente nada. Nem código. &numDadosEncontrados receberá TUDO o que for escrito.
?>
