<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	
	/*
	* Erros que podem acontecer neste arquivo.
	*/
	$SUCESSO = "9999";
	$ERRO_CONEXAO_BD = "30";
	$ERRO_FALTA_PERMISSAO = "31";
	
	/*
	* Dados vindos do flash.
	*/
	$id_turma_para_deletar = $_POST['identificacao'];
	$id_usuario_online = $_SESSION['SS_usuario_id'];
	
	/*
	* Deleta a turma cujo id é passado como parâmetro, desde que o usuário online tenha permissão para isto.
	* @param turma_id_param Id da turma que será deletada.
	* @return $SUCESSO caso consiga.
	*		  $ERRO_CONEXAO_BD caso não consiga conectar com o banco de dados.
	*		  $ERRO_FALTA_PERMISSAO caso o usuário não possa deletar o usuário do parâmetro.
	*/
	function deletarTurma($turma_id_param){
		require_once("../../cfg.php");
		require_once("../../bd.php");
		require_once("../../funcoes_aux.php");
	
		global $tabela_turmas;
		global $nivelCoordenador;
		global $SUCESSO;
		global $ERRO_CONEXAO_BD;
		global $ERRO_FALTA_PERMISSAO;
		
		$statusBusca = $SUCESSO;
		$nivel_usuario_logado = $_SESSION['SS_usuario_id'];
		
		//Verificação de permissão do usuário.
		if(checa_nivel($nivel_usuario_logado, $nivelCoordenador) != 1
		   and checa_nivel($nivel_usuario_logado, $nivelCoordenador) != "xyzzy"){
			$statusBusca = $ERRO_FALTA_PERMISSAO;
		}
		
		//Deleção da conta.
		if($statusBusca == $SUCESSO){
			$conexao = new conexao();
			$conexao->solicitar("DELETE FROM $tabela_turmas
								 WHERE codTurma = $turma_id_param");
			if($conexao->erro != ''){
				$statusBusca = $ERRO_CONEXAO_BD;	
			}
		}
		
		return $statusBusca;
	}
	
	$operacaoRealizadaComSucesso = true;
	$resultadoOperacao = deletarTurma($id_turma_para_deletar);
	
	if($resultadoOperacao == $SUCESSO){
		$operacaoRealizadaComSucesso = true;
		$dados = '&operacaoRealizadaComSucesso='.$operacaoRealizadaComSucesso;
	} else {
		$operacaoRealizadaComSucesso = false;
		$dados = '&operacaoRealizadaComSucesso='.$operacaoRealizadaComSucesso;
		$mensagemNaoFoiPossivel = utf8_encode('Não foi possível deletar.');
		$dados .= '&mensagemDeErro='.$mensagemNaoFoiPossivel;
	}
	
    echo $dados;
//A partir do fim do php, não escrever absolutamente nada. Nem código. &numDadosEncontrados receberá TUDO o que for escrito.
?>
