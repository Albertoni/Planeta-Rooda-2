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
	$ERRO_CONEXAO_BD = "31";
	
	/*
	* Possíveis relações entre usuários e turmas.
	*/
	$PROFESSOR = 1;
	$PROFESSOR_CONVIDADO = 2;
	$PROFESSOR_HABILITADO = 3;
	$MONITOR = 4;
	$MONITOR_CONVIDADO = 5;
	$MONITOR_HABILITADO = 6;
	$ALUNO = 7;
	$ALUNO_CONVIDADO = 8;
	$ALUNO_HABILITADO = 9;
	
	$identificacao = $_SESSION['SS_usuario_id'];
	$operacaoRealizadaComSucesso = true;
	
	/*
	* Procura por dados de turmas de um usuário no banco de dados e os retorna em um array associativo.
	* Os resultados são dependentes do nível do usuário logado. Desta forma:
	*		Um administrador receberá dados de qualquer usuário que quiser, bem como um coordenador.
	*		Um professor receberá dados somente de usuários que forem seus alunos e de si mesmo.
	* 		Monitores, alunos e visitantes não podem fazer este tipo de pesquisa.
	* @param id_usuario_param Id do usuário cujas turmas serão retornadas.
	* @param relacao_usuario_turma_param A relação entre o usuário e a turma, conforme definido no início deste arquivo.
	* @return Array associativo com os dados de tumas do usuário, caso tenha sucesso.
	*		  Caso contrário, retorna um erro definido no início deste arquivo.
	*/
	function procurarDadosTurmasUsuario($id_usuario_param, $relacao_usuario_turma_param){
		require_once("../../cfg.php");
		require_once("../../bd.php");
		require_once("../../funcoes_aux.php");
	
		global $nivelProfessor;
		global $nivelMonitor;
		global $nivelAluno;
		global $tabela_usuarios;
		global $SUCESSO;
		global $ERRO_FALTA_PERMISSAO;
		global $ERRO_CONEXAO_BD;
		global $PROFESSOR;
		global $PROFESSOR_CONVIDADO;
		global $PROFESSOR_HABILITADO;
		global $MONITOR;
		global $MONITOR_CONVIDADO;
		global $MONITOR_HABILITADO;
		global $ALUNO;
		global $ALUNO_CONVIDADO;
		global $ALUNO_HABILITADO;
		
		$statusBusca = $SUCESSO;
		//Montagem do SQL.
		if($statusBusca == $SUCESSO ){
			switch($relacao_usuario_turma_param){
				case $PROFESSOR: 
					$sql = "SELECT T.nomeTurma
							FROM TurmasUsuario AS TU,
								Turmas AS T
							WHERE TU.codUsuario = $id_usuario_param
								AND T.codTurma = TU.codTurma
								AND TU.associacao = $nivelProfessor";
					break;
				case $PROFESSOR_CONVIDADO: 
					$sql = "SELECT T.nomeTurma
							FROM TurmasUsuarioConvidado AS TU,
								Turmas AS T
							WHERE TU.codUsuario = $id_usuario_param
								AND T.codTurma = TU.codTurma
								AND TU.associacao = $nivelProfessor";
					break;
				case $PROFESSOR_HABILITADO: 
					$sql = "SELECT nomeTurma FROM Turmas";
					break;
				case $MONITOR: 
					$sql = "SELECT T.nomeTurma
							FROM TurmasUsuario AS TU,
								Turmas AS T
							WHERE TU.codUsuario = $id_usuario_param
								AND T.codTurma = TU.codTurma
								AND TU.associacao = $nivelMonitor";
					break;
				case $MONITOR_CONVIDADO: 
					$sql = "SELECT T.nomeTurma
							FROM TurmasUsuarioConvidado AS TU,
								Turmas AS T
							WHERE TU.codUsuario = $id_usuario_param
								AND T.codTurma = TU.codTurma
								AND TU.associacao = $nivelMonitor";
					break;
				case $MONITOR_HABILITADO: 
					$sql = "SELECT nomeTurma FROM Turmas";
					break;
				case $ALUNO: 
					$sql = "SELECT T.nomeTurma
							FROM TurmasUsuario AS TU,
								Turmas AS T
							WHERE TU.codUsuario = $id_usuario_param
								AND T.codTurma = TU.codTurma
								AND TU.associacao = $nivelAluno";
					break;
				case $ALUNO_CONVIDADO: 
					$sql = "SELECT T.nomeTurma
							FROM TurmasUsuarioConvidado AS TU,
								Turmas AS T
							WHERE TU.codUsuario = $id_usuario_param
								AND T.codTurma = TU.codTurma
								AND TU.associacao = $nivelAluno";
					break;
				case $ALUNO_HABILITADO: 
					$sql = "SELECT nomeTurma FROM Turmas";
					break;
			}
		}
		
		//Efetuar pesquisa.
		if($statusBusca == $SUCESSO){
			$conexao = new conexao();
			$conexao->solicitar($sql);
			if($conexao->erro != ''){
				$statusBusca = $ERRO_CONEXAO_BD;
			}
		}
		
		if($statusBusca == $SUCESSO){
			$resultado = array();
			$auxiliar = array('total_turmas'=>$conexao->registros);
			$resultado['total_turmas'] = $conexao->registros;
			for($i=0; $i < $conexao->registros; $i++){
				array_push($resultado, $conexao->resultado['nomeTurma']);
				$conexao->proximo();
			}
			return $resultado;
		} else {
			return $statusBusca;
		}
	}
	
	/*
	* @return Se for passado um erro, retorna uma mensagem para este erro.
	*		  Se for passado $SUCESSO, retorna ''.
	*/
	function getMensagemErro($erro_param){
		global $SUCESSO;
		global $ERRO_FALTA_PERMISSAO;
		global $ERRO_CONEXAO_BD;
		
		switch($erro_param){
			case $SUCESSO: return '';
				break;
			case $ERRO_FALTA_PERMISSAO: return utf8_encode('Desculpe, você não possui permissão para realizar esta pesquisa.');
				break;
			case $ERRO_CONEXAO_BD: return utf8_encode('Desculpe, não foi possível conectar ao banco de dados.');
				break;
			default: return '';
		}
	}
	
	
	$conexao = new conexao();
	$conexao->solicitar("SELECT *
						 FROM $tabela_usuarios
						 WHERE usuario_id = $identificacao");
	$dadosEncontrados = $conexao->resultado;
	
	if($conexao->erro != ''){
		$operacaoRealizadaComSucesso = false;
	} else {
		$turmasProfessor = procurarDadosTurmasUsuario($identificacao, $PROFESSOR);
		$turmasProfessorConvidado = procurarDadosTurmasUsuario($identificacao, $PROFESSOR_CONVIDADO);
		$turmasProfessorHabilitado = procurarDadosTurmasUsuario($identificacao, $PROFESSOR_HABILITADO);
		$turmasMonitor = procurarDadosTurmasUsuario($identificacao, $MONITOR);
		$turmasMonitorConvidado = procurarDadosTurmasUsuario($identificacao, $MONITOR_CONVIDADO);
		$turmasMonitorHabilitado = procurarDadosTurmasUsuario($identificacao, $MONITOR_HABILITADO);
		$turmasAluno = procurarDadosTurmasUsuario($identificacao, $ALUNO);
		$turmasAlunoConvidado = procurarDadosTurmasUsuario($identificacao, $ALUNO_CONVIDADO);
		$turmasAlunoHabilitado = procurarDadosTurmasUsuario($identificacao, $ALUNO_HABILITADO);
	}
	
	if(getMensagemErro($turmasProfessor) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	if(getMensagemErro($turmasProfessorConvidado) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	if(getMensagemErro($turmasProfessorHabilitado) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	if(getMensagemErro($turmasMonitor) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	if(getMensagemErro($turmasMonitorConvidado) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	if(getMensagemErro($turmasMonitorHabilitado) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	if(getMensagemErro($turmasAluno) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	if(getMensagemErro($turmasAlunoConvidado) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	if(getMensagemErro($turmasAlunoHabilitado) != '' and $operacaoRealizadaComSucesso){
		$operacaoRealizadaComSucesso = false;
	}
	
	if($operacaoRealizadaComSucesso){
		$usuario_id	   = $dadosEncontrados['usuario_id'];
		$usuario_login	= $dadosEncontrados['usuario_login'];
		$usuario_senha	= $dadosEncontrados['usuario_senha'];
		
		$usuario_data_aniversario = $dadosEncontrados['usuario_data_aniversario'];
		$usuario_data_aniversario = explode("-", $usuario_data_aniversario);
		$diaAniversario = $usuario_data_aniversario[2];
		$mesAniversario = $usuario_data_aniversario[1];
		$anoAniversario = $usuario_data_aniversario[0];
		$usuario_nome	 = $dadosEncontrados['usuario_nome'];
		$usuario_nome_mae = $dadosEncontrados['usuario_nome_mae'];
		$usuario_email	= $dadosEncontrados['usuario_email'];
		$usuario_nivel	= $dadosEncontrados['usuario_nivel'];
			$usuario_personagem_id = $dadosEncontrados['usuario_personagem_id'];
			$consultaPersonagem = new conexao();
			$consultaPersonagem->solicitar("SELECT * 
											FROM $tabela_personagens 
											WHERE personagem_id=$usuario_personagem_id");
		$usuario_apelido = $consultaPersonagem->resultado['personagem_nome'];
		$usuario_sexo = $consultaPersonagem->resultado['personagem_avatar_1'];
		
		$dados = '';
		$dados .= '&usuario_id'.				'='.$usuario_id;
		$dados .= '&usuario_login'.				'='.$usuario_login;
		$dados .= '&usuario_senha'.				'='.$usuario_senha;
		
		if($turmasProfessor != null and isset($turmasProfessor) and $turmasProfessor != ''){ 
			for($j = 0; $j < $turmasProfessor['total_turmas']; $j++){
				$dados .= '&turmasProfessor'.$j.	 '='.($turmasProfessor[$j]);
			}
			$dados .= '&num_turmas_professor'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_professor'.		'='.$j;
		}
		if($turmasProfessorConvidado != null and isset($turmasProfessorConvidado) and $turmasProfessorConvidado != ''){ 
			for($j = 0; $j < $turmasProfessorConvidado['total_turmas']; $j++){
				$dados .= '&turmasConvidadoProfessor'.$j.	 '='.($turmasProfessorConvidado[$j]);
			}
			$dados .= '&num_turmas_convidado_professor'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_convidado_professor'.		'='.$j;
		}
		if($turmasProfessorHabilitado != null and isset($turmasProfessorHabilitado) and $turmasProfessorHabilitado != ''){ 
			for($j = 0; $j < $turmasProfessorHabilitado['total_turmas']; $j++){
				$dados .= '&turmasHabilitadoProfessor'.$j.	 '='.($turmasProfessorHabilitado[$j]);
			}
			$dados .= '&num_turmas_habilitado_professor'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_habilitado_professor'.		'='.$j;
		}
		if($turmasMonitor != null and isset($turmasMonitor) and $turmasMonitor != ''){ 
			for($j = 0; $j < $turmasMonitor['total_turmas']; $j++){
				$dados .= '&turmasMonitor'.$j.	 '='.($turmasMonitor[$j]);
			}
			$dados .= '&num_turmas_monitor'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_monitor'.		'='.$j;
		}
		if($turmasMonitorConvidado != null and isset($turmasMonitorConvidado) and $turmasMonitorConvidado != ''){ 
			for($j = 0; $j < $turmasMonitorConvidado['total_turmas']; $j++){
				$dados .= '&turmasConvidadoMonitor'.$j.	 '='.($turmasMonitorConvidado[$j]);
			}
			$dados .= '&num_turmas_convidado_monitor'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_convidado_monitor'.		'='.$j;
		}
		if($turmasMonitorHabilitado != null and isset($turmasMonitorHabilitado) and $turmasMonitorHabilitado != ''){ 
			for($j = 0; $j < $turmasMonitorHabilitado['total_turmas']; $j++){
				$dados .= '&turmasHabilitadoMonitor'.$j.	 '='.($turmasMonitorHabilitado[$j]);
			}
			$dados .= '&num_turmas_habilitado_monitor'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_habilitado_monitor'.		'='.$j;
		}
		if($turmasAluno != null and isset($turmasAluno) and $turmasAluno != ''){ 
			for($j = 0; $j < $turmasAluno['total_turmas']; $j++){
				$dados .= '&turmasAluno'.$j.	 '='.($turmasAluno[$j]);
			}
			$dados .= '&num_turmas_aluno'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_aluno'.		'='.$j;
		}
		if($turmasAlunoConvidado != null and isset($turmasAlunoConvidado) and $turmasAlunoConvidado != ''){ 
			for($j = 0; $j < $turmasAlunoConvidado['total_turmas']; $j++){
				$dados .= '&turmasConvidadoAluno'.$j.	 '='.($turmasAlunoConvidado[$j]);
			}
			$dados .= '&num_turmas_convidado_aluno'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_convidado_aluno'.		'='.$j;
		}
		if($turmasAlunoHabilitado != null and isset($turmasAlunoHabilitado) and $turmasAlunoHabilitado != ''){ 
			for($j = 0; $j < $turmasAlunoHabilitado['total_turmas']; $j++){
				$dados .= '&turmasHabilitadoAluno'.$j.	 '='.($turmasAlunoHabilitado[$j]);
			}
			$dados .= '&num_turmas_habilitado_aluno'.		'='.$j;
		} else {
			$j = 0;
			$dados .= '&num_turmas_habilitado_aluno'.		'='.$j;
		}
		
		$dados .= '&usuario_dia_aniversario'.	'='.$diaAniversario;
		$dados .= '&usuario_mes_aniversario'.	'='.$mesAniversario;
		$dados .= '&usuario_ano_aniversario'.	'='.$anoAniversario;
		$dados .= '&usuario_nome'.				'='.$usuario_nome;
		$dados .= '&usuario_nome_mae'.			'='.$usuario_nome_mae;
		$dados .= '&usuario_email'.				'='.$usuario_email;
		$dados .= '&usuario_nivel'.				'='.$usuario_nivel;
		$dados .= '&usuario_apelido'.			'='.$usuario_apelido;
		$dados .= '&usuario_sexo'.				'='.$usuario_sexo;
	} else {
		
	}
	
	$dados .= '&operacaoRealizadaComSucesso'.'='.$operacaoRealizadaComSucesso;
	echo $dados;
//A partir do fim do php, não escrever absolutamente nada. Nem código. &numDadosEncontrados receberá TUDO o que for escrito.
?>
