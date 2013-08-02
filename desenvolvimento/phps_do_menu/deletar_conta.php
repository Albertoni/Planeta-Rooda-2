<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	
	/*
	* Erros que podem acontecer neste arquivo.
	*/
	$SUCESSO = "9999";
	$ERRO_CONEXAO_BD = "30";
	$ERRO_FALTA_PERMISSAO = "31";
	
	/*
	* Dados vindos do flash.
	*/
	$id_usuario_para_deletar = $_POST['identificacao'];
	$id_usuario_online = $_SESSION['SS_usuario_id'];
	
	/*
	* Deleta a conta cujo usuário é passado como parâmetro, desde que o usuário online tenha permissão para isto.
	* @param usuario_id_param Id do usuário que será deletado.
	* @return $SUCESSO caso consiga.
	*		  $ERRO_CONEXAO_BD caso não consiga conectar com o banco de dados.
	*		  $ERRO_FALTA_PERMISSAO caso o usuário não possa deletar o usuário do parâmetro.
	*/
	function deletarConta($usuario_id_param){
		require_once("../../cfg.php");
		require_once("../../bd.php");
		require_once("../../funcoes_aux.php");
	
		global $tabela_terrenos;
		global $tabela_usuarios;
		global $tabela_personagens;
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
			$conexao->solicitar("SELECT *
								 FROM $tabela_usuarios AS U, $tabela_personagens AS P
								 WHERE U.usuario_id = $usuario_id_param
									AND P.personagem_id = U.usuario_personagem_id");
			
			if($conexao->erro != ''){
				$statusBusca = $ERRO_CONEXAO_BD;	
			} else {
				$id_personagem = $conexao->resultado['usuario_personagem_id'];
				$id_quarto = $conexao->resultado['quarto_id'];
				$id_chat = $conexao->resultado['chat_id'];
				
				$conexao->solicitar("DELETE FROM $tabela_usuarios
									 WHERE usuario_id = $usuario_id_param");
				if($conexao->erro != ''){
					$statusBusca = $ERRO_CONEXAO_BD;				 
				}
				$conexao->solicitar("DELETE FROM $tabela_personagens
									 WHERE personagem_id = $id_personagem");
				if($conexao->erro != ''){
					$statusBusca = $ERRO_CONEXAO_BD;				 
				}
				$conexao->solicitar("DELETE FROM $tabela_terrenos
									 WHERE terreno_id = $id_quarto");
				$conexao->solicitar("DELETE FROM Chats
									 WHERE id = $id_chat");
				if($conexao->erro != ''){
					$statusBusca = $ERRO_CONEXAO_BD;				 
				}
				$conexao->solicitar("DELETE FROM TurmasUsuarios
									 WHERE codUsuario = $usuario_id_param");
				$conexao->solicitar("DELETE FROM TurmasUsuariosConvidados
									 WHERE codUsuario = $usuario_id_param");
			}
		}
		
		return $statusBusca;
	}
	
	$resultadoOperacao = deletarConta($id_usuario_para_deletar);
	
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
