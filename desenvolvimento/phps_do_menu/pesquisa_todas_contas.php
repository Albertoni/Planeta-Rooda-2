<?php
	/*
	* URL para debug:
	* sideshowbob/planeta2_diogo/desenvolvimento/phps_do_menu/pesquisa_contas.php?dado_pesquisado=d&pos_tupla_resultado_pesquisa=1
	*/
	
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	
	/*
	* Erros que podem acontecer neste arquivo.
	*/
	$SUCESSO = "9999";
	$ERRO_FALTA_PERMISSAO = "30";
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
	
	$id_usuario_que_procura    = $_SESSION['SS_usuario_id'];
	$nome                      = $_POST['dado_pesquisado'];
	
	$operacaoRealizadaComSucesso = true;
	
	/*
	* Sujeita às restrições de 'procurarDadosUsuario'.
	* @return Número total de usuários elegíveis para retorno em 'procurarDadosUsuario'.
	*/
	function getTotalResultados($nome_param){
		require_once("../../cfg.php");
		require_once("../../bd.php");
		require_once("../../funcoes_aux.php");
	
		global $nivelProfessor;
		global $tabela_usuarios;
		global $SUCESSO;
		global $ERRO_FALTA_PERMISSAO;
		global $ERRO_CONEXAO_BD;

		$statusBusca = $SUCESSO;
		
		$usuario_logado_id = $_SESSION['SS_usuario_id'];
		$nivel_usuario_logado = $_SESSION['SS_usuario_nivel_sistema'];
		$usuario_eh_professor = (checa_nivel($nivel_usuario_logado, $nivelProfessor) == 1) ? 1 : 0;
		
		//Caso o usuário não possua permissão para fazer este tipo de pesquisa, retorna $ERRO_FALTA_PERMISSAO
		if(checa_nivel($nivel_usuario_logado, $nivelProfessor) != 1
		   and checa_nivel($nivel_usuario_logado, $nivelProfessor) != "xyzzy"){
			$statusBusca = $ERRO_FALTA_PERMISSAO;
		}
		
		//Montagem do SQL.
		if($nome_param == null){
			$nomeBusca = '';
		} else {
			$nomeBusca = $nome_param;
		}
		if($statusBusca == $SUCESSO and !$usuario_eh_professor){
			$sql = "SELECT U.*
					FROM $tabela_usuarios AS U
					WHERE usuario_nome LIKE '%$nomeBusca%'
					ORDER BY U.usuario_nome ASC";
		} else if($statusBusca == $SUCESSO){
			$sql = "SELECT U.*
					FROM $tabela_usuarios AS U,
						 TurmasUsuario AS TUProfessor, 
						 TurmasUsuario AS TUAluno,
					WHERE U.usuario_nome LIKE '%$nomeBusca%'
						AND TUProfessor.codUsuario = $usuario_logado_id
						AND TUAluno.codUsuario = U.usuario_id
						AND TUProfessor.codTurma = TUAluno.codTurma
						ORDER BY U.usuario_nome ASC";
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
			return $conexao->registros;
		} else {
			return $statusBusca;
		}
	}
	
	/*
	* Procura por um usuário no banco de dados e retorna seus dados em array associativo. Não inclui dados de turmas do usuário.
	* Os resultados são dependentes do nível do usuário logado. Desta forma:
	*		Um administrador receberá dados de qualquer usuário que quiser, bem como um coordenador.
	*		Um professor receberá dados somente de usuários que forem seus alunos e de si mesmo.
	* 		Monitores, alunos e visitantes não podem fazer este tipo de pesquisa.
	* @param nome_param Parte do nome do usuário procurado.
	* @param posicao_dado_para_retorno_param É retornado somente um usuário, aquele que na ordem tiver o valor de posicao_dado_para_retorno_param.
	* @return Array associativo com os dados do usuário, caso tenha sucesso. Não inclui dados de turmas do usuário.
	*		  Caso contrário, retorna um erro definido no início deste arquivo.
	*/
	function procurarDadosUsuarios($nome_param){
		require_once("../../cfg.php");
		require_once("../../bd.php");
		require_once("../../funcoes_aux.php");
	
		global $nivelProfessor;
		global $tabela_usuarios;
		global $SUCESSO;
		global $ERRO_FALTA_PERMISSAO;
		global $ERRO_CONEXAO_BD;
		global $mensagemDeErro;

		$statusBusca = $SUCESSO;
		
		$usuario_logado_id = $_SESSION['SS_usuario_id'];
		$nivel_usuario_logado = $_SESSION['SS_usuario_nivel_sistema'];
		$usuario_eh_professor = (checa_nivel($nivel_usuario_logado, $nivelProfessor) == 1) ? 1 : 0;
		
		//Caso o usuário não possua permissão para fazer este tipo de pesquisa, retorna $ERRO_FALTA_PERMISSAO
		if(checa_nivel($nivel_usuario_logado, $nivelProfessor) != 1
		   and checa_nivel($nivel_usuario_logado, $nivelProfessor) != "xyzzy"){
			$statusBusca = $ERRO_FALTA_PERMISSAO;
		}
		   
		//Montagem do SQL.
		if($nome_param == null){
			$nomeBusca = '';
		} else {
			$nomeBusca = $nome_param;
		}
		if($statusBusca == $SUCESSO and !$usuario_eh_professor){
			$sql = "SELECT U.*
					FROM $tabela_usuarios AS U
					WHERE usuario_nome LIKE '%$nomeBusca%'
					ORDER BY U.usuario_nome ASC";
			$sqlTotal = "SELECT U.*
						 FROM $tabela_usuarios AS U
						 WHERE usuario_nome LIKE '%$nomeBusca%'
						 ORDER BY U.usuario_nome ASC";
		} else if($statusBusca == $SUCESSO){	
			$sql = "SELECT U.*
					FROM $tabela_usuarios AS U,
						 TurmasUsuario AS TUProfessor, 
						 TurmasUsuario AS TUAluno
					WHERE U.usuario_nome LIKE '%$nomeBusca%'
						AND TUProfessor.codUsuario = $usuario_logado_id
						AND TUAluno.codUsuario = U.usuario_id
						AND TUProfessor.codTurma = TUAluno.codTurma
						ORDER BY U.usuario_nome ASC";
			$sqlTotal = "SELECT U.*
						 FROM $tabela_usuarios AS U,
							  TurmasUsuario AS TUProfessor, 
							  TurmasUsuario AS TUAluno
						 WHERE U.usuario_nome LIKE '%$nomeBusca%'
							AND TUProfessor.codUsuario = $usuario_logado_id
							AND TUAluno.codUsuario = U.usuario_id
							AND TUProfessor.codTurma = TUAluno.codTurma
						 ORDER BY U.usuario_nome ASC";
		}
		
		//Efetuar pesquisa.
		if($statusBusca == $SUCESSO){
			$conexao = new conexao();
			$conexao->solicitar($sqlTotal);
			
			if($conexao->erro != ''){
				$statusBusca = $ERRO_CONEXAO_BD;
			} else {
				$total_resultados = $conexao->registros;
			}
			$conexao->solicitar($sql);
			if($conexao->erro != ''){
				$statusBusca = $ERRO_CONEXAO_BD;
			}
		}
		
		if($statusBusca == $SUCESSO){
			$todosResultados = array();
			for($resultadoAtual=0; $resultadoAtual<$conexao->registros; $resultadoAtual++){
				$todosResultados[$resultadoAtual] = $conexao->resultado;
				$conexao->proximo();
			}
			$auxiliar = array('total_usuarios'=>($total_resultados));
			$todosResultados = array_merge((array)$todosResultados, (array)$auxiliar);
			return $todosResultados;
		} else {
			return $statusBusca;
		}
	}
	
	/*
	* Procura por dados de turmas de um usuário no banco de dados e os retorna em um array associativo.
	* Os resultados são dependentes do nível do usuário logado. Desta forma:
	*		Um administrador receberá dados de qualquer usuário que quiser, bem como um coordenador.
	*		Um professor receberá dados somente de usuários que forem seus alunos e de si mesmo.
	* 		Monitores, alunos e visitantes não podem fazer este tipo de pesquisa.
	* @param id_usuario_param Id do usuário cujas turmas serão retornadas.
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
		global $SUCESSO;
		global $ERRO_FALTA_PERMISSAO;
		global $ERRO_CONEXAO_BD;

		$statusBusca = $SUCESSO;
		
		$usuario_logado_id = $_SESSION['SS_usuario_id'];
		$nivel_usuario_logado = $_SESSION['SS_usuario_nivel_sistema'];
		$usuario_eh_professor = (checa_nivel($nivel_usuario_logado, $nivelProfessor) == 1) ? 1 : 0;
		
		//Caso o usuário não possua permissão para fazer este tipo de pesquisa, retorna $ERRO_FALTA_PERMISSAO
		if(checa_nivel($nivel_usuario_logado, $nivelProfessor) != 1
		   and checa_nivel($nivel_usuario_logado, $nivelProfessor) != "xyzzy"){
			$statusBusca = $ERRO_FALTA_PERMISSAO;
		}
		
		//Montagem do SQL.
		if($statusBusca == $SUCESSO){
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
	
	$dadosEncontrados = procurarDadosUsuarios($nome);
	if(getMensagemErro($dadosEncontrados) != ''){
		$operacaoRealizadaComSucesso = false;
	}
	
	$dados = '';
	if($operacaoRealizadaComSucesso){
		for($usuarioAtual=0; $usuarioAtual<$dadosEncontrados['total_usuarios']; $usuarioAtual++){
			$usuario_id       = $dadosEncontrados[$usuarioAtual]['usuario_id'];
			$usuario_login    = $dadosEncontrados[$usuarioAtual]['usuario_login'];
			$usuario_senha    = $dadosEncontrados[$usuarioAtual]['usuario_senha'];
			
			$usuario_data_aniversario = $dadosEncontrados[$usuarioAtual]['usuario_data_aniversario'];
			$usuario_data_aniversario = explode("-", $usuario_data_aniversario);
			$diaAniversario = $usuario_data_aniversario[2];
			$mesAniversario = $usuario_data_aniversario[1];
			$anoAniversario = $usuario_data_aniversario[0];
			$usuario_nome     = $dadosEncontrados[$usuarioAtual]['usuario_nome'];
			$usuario_nome_mae = $dadosEncontrados[$usuarioAtual]['usuario_nome_mae'];
			$usuario_email    = $dadosEncontrados[$usuarioAtual]['usuario_email'];
			$usuario_nivel    = $dadosEncontrados[$usuarioAtual]['usuario_nivel'];
				$usuario_personagem_id = $dadosEncontrados[$usuarioAtual]['usuario_personagem_id'];
				$consultaPersonagem = new conexao();
				$consultaPersonagem->solicitar("SELECT * 
												FROM $tabela_personagens 
												WHERE personagem_id=$usuario_personagem_id");
			$usuario_apelido = $consultaPersonagem->resultado['personagem_nome'];
			$usuario_sexo = $consultaPersonagem->resultado['personagem_avatar_1'];
			
			//$dados .= '&usuario_id'.$usuarioAtual.				'='.$usuario_id;
			//$dados .= '&usuario_login'.$usuarioAtual.			'='.$usuario_login;
			//$dados .= '&usuario_senha'.$usuarioAtual.			'='.$usuario_senha;
			//$dados .= '&usuario_dia_aniversario'.$usuarioAtual.	'='.$diaAniversario;
			//$dados .= '&usuario_mes_aniversario'.$usuarioAtual.	'='.$mesAniversario;
			//$dados .= '&usuario_ano_aniversario'.$usuarioAtual.	'='.$anoAniversario;
			$dados .= '&usuario_nome'.$usuarioAtual.			'='.$usuario_nome;
			//$dados .= '&usuario_nome_mae'.$usuarioAtual.		'='.$usuario_nome_mae;
			//$dados .= '&usuario_email'.$usuarioAtual.			'='.$usuario_email;
			//$dados .= '&usuario_nivel'.$usuarioAtual.			'='.$usuario_nivel;
			//$dados .= '&usuario_apelido'.$usuarioAtual.			'='.$usuario_apelido;
			//$dados .= '&usuario_sexo'.$usuarioAtual.			'='.$usuario_sexo;
		}
		$dados .= '&numDadosEncontrados='.($dadosEncontrados['total_usuarios']);
	} else {
			
	}

	$dados .= '&dado_pesquisado='.$nome;
	$dados .= '&mensagemDeErro='.$mensagemDeErro;

    echo $dados;	
//A partir do fim do php, não escrever absolutamente nada. Nem código. &numDadosEncontrados receberá TUDO o que for escrito.
?>
