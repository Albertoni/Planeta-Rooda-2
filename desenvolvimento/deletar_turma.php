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
	* Deleta a turma cujo id � passado como par�metro, desde que o usu�rio online tenha permiss�o para isto.
	* @param turma_id_param Id da turma que ser� deletada.
	* @return $SUCESSO caso consiga.
	*		  $ERRO_CONEXAO_BD caso n�o consiga conectar com o banco de dados.
	*		  $ERRO_FALTA_PERMISSAO caso o usu�rio n�o possa deletar o usu�rio do par�metro.
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
		
		//Verifica��o de permiss�o do usu�rio.
		if(checa_nivel($nivel_usuario_logado, $nivelCoordenador) != 1
		   and checa_nivel($nivel_usuario_logado, $nivelCoordenador) != "xyzzy"){
			$statusBusca = $ERRO_FALTA_PERMISSAO;
		}
		
		//Dele��o da conta.
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
		$mensagemNaoFoiPossivel = utf8_encode('N�o foi poss�vel deletar.');
		$dados .= '&mensagemDeErro='.$mensagemNaoFoiPossivel;
	}
	
    echo $dados;
//A partir do fim do php, n�o escrever absolutamente nada. Nem c�digo. &numDadosEncontrados receber� TUDO o que for escrito.
?>
