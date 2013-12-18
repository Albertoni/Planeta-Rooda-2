<?php
	/* URL para teste
		sideshowbob/planeta2_diogo/desenvolvimento/phps_do_menu/pesquisa_turmas.php?dado_pesquisado=t&pos_tupla_resultado_pesquisa=1
	*/


	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");

	$nome = $_POST['dado_pesquisado'];
	$posicao_dado_para_retorno = $_POST['pos_tupla_resultado_pesquisa'];
	$consulta = new conexao();
	$consulta_nomeprof = new conexao();

	$usuario = new Usuario();
	$usuario->openUsuario($_SESSION['SS_usuario_id']);
	$usuario_administrador = $usuario->isAdmin();
	if($usuario_administrador){
		if ($nome == NULL){
			$consulta->solicitar("SELECT * FROM $tabela_turmas"); 
		} else {
			$consulta->solicitar("SELECT * FROM $tabela_turmas WHERE nomeTurma LIKE '%$nome%'");
		}
	} else {
		if ($nome == NULL){
			$consulta->solicitar("SELECT T.*, TU.associacao
								 FROM $tabela_turmas AS T LEFT JOIN TurmasUsuario AS TU 
										ON T.codTurma = TU.codTurma
								 WHERE (TU.associacao%$nivelProfessor = 0
									AND TU.codUsuario = ".$usuario->getId().")
									OR (T.profResponsavel = ".$usuario->getId().")
								 GROUP BY codTurma");
		} else {
			$consulta->solicitar("SELECT T.*, TU.associacao
								 FROM $tabela_turmas AS T LEFT JOIN TurmasUsuario AS TU
										ON T.codTurma = TU.codTurma
								 WHERE (nomeTurma LIKE '%$nome%'
									AND TU.associacao%$nivelProfessor = 0
									AND TU.codUsuario = ".$usuario->getId().")
									OR (T.profResponsavel = ".$usuario->getId().")
								 GROUP BY codTurma");
		}
	}

	$dados = '&dado_pesquisado='.$nome;

	$atualCorrente=0;
	$numDadosEncontrados = 0;
	for ($i=1; $i<=count($consulta->itens); $i++){
		echo 'id='.$consulta->resultado['codTurma'].'<br>';
		
		if($usuario_administrador){
			$atualCorrente++;
			$numDadosEncontrados = $numDadosEncontrados + 1;
			if($posicao_dado_para_retorno == $atualCorrente){
				$identificacao = $consulta->resultado['codTurma'];
				$nome = $consulta->resultado['nomeTurma'];
				$codigoProfessor = $consulta->resultado['profResponsavel'];
				$descricao = $consulta->resultado['descricao'];
				$idAno = $consulta->resultado['serie'];
				
				$consulta_ano = new conexao();
				$consulta_ano->solicitar("SELECT * FROM $tabela_anos WHERE id=$idAno");
				$ano = $consulta_ano->resultado['nome'];
				
				$consulta_nomeprof->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id = $codigoProfessor");
				$professor = $consulta_nomeprof->resultado['usuario_nome'];
				$maeProfessor = $consulta_nomeprof->resultado['usuario_nome_mae'];
				
				$consulta_professores = new conexao();
				$consulta_professores->solicitar("SELECT U.usuario_nome AS nome
											FROM TurmasUsuario AS TU, $tabela_usuarios AS U
											WHERE TU.codTurma = $identificacao 
												AND TU.associacao = $nivelProfessor
												AND TU.codUsuario = U.usuario_id");
				$numeroProfessores = $consulta_professores->registros;
				if($numeroProfessores == ''){
					$numeroProfessores = 0;
				}
											
				$consulta_monitores = new conexao();
				$consulta_monitores->solicitar("SELECT U.usuario_nome AS nome
											FROM TurmasUsuario AS TU, $tabela_usuarios AS U
											WHERE TU.codTurma = $identificacao 
													AND TU.associacao = $nivelMonitor
													AND TU.codUsuario = U.usuario_id");
				$numeroMonitores = $consulta_monitores->registros;
				if($numeroMonitores == ''){
					$numeroMonitores = 0;
				}
				
				$consulta_alunos = new conexao();
				$consulta_alunos->solicitar("SELECT U.usuario_nome AS nome
											FROM TurmasUsuario AS TU, $tabela_usuarios AS U
											WHERE TU.codTurma = $identificacao 
												AND TU.associacao = $nivelAluno
												AND TU.codUsuario = U.usuario_id");
				$numeroAlunos = $consulta_alunos->registros;
				if($numeroAlunos == ''){
					$numeroAlunos = 0;
				}
				
				$dados .= '&identificacao'.	   '='.$identificacao;
				$dados .= '&nome'.		       '='.$nome;
				$dados .= '&professor'.		   '='.$professor; 
				$dados .= '&nomeMaeProfessor'. '='.$maeProfessor;
				$dados .= '&descricao'.		   '='.$descricao;
				$dados .= '&ano'.		       '='.$ano;
				$dados .= '&numeroProfessores'.'='.$numeroProfessores;
				$dados .= '&numeroMonitores'.  '='.$numeroMonitores;
				$dados .= '&numeroAlunos'.     '='.$numeroAlunos;
				for($j=0; $j<$numeroProfessores; $j++){
					$nomeProfessor = $consulta_professores->resultado['nome'];
					$dados .= '&professor'.$j.'='.$nomeProfessor;
					$consulta_professores->proximo();
				}
				for($j=0; $j<$numeroMonitores; $j++){
					$nomeMonitor = $consulta_monitores->resultado['nome'];
					$dados .= '&monitor'.$j.'='.$nomeMonitor;
					$consulta_monitores->proximo();
				}
				for($j=0; $j<$numeroAlunos; $j++){
					$nomeAluno = $consulta_alunos->resultado['nome'];
					$dados .= '&aluno'.$j.'='.$nomeAluno;
					$consulta_alunos->proximo();
				}
			}
		}
		
		if(!$usuario_administrador and $usuario->podeAcessar($nivelProfessor, $consulta->resultado['codTurma'])){
			$atualCorrente++;
			$numDadosEncontrados = $numDadosEncontrados + 1;
			if($atualCorrente == $posicao_dado_para_retorno){
				$identificacao = $consulta->resultado['codTurma'];
				$nome = $consulta->resultado['nomeTurma'];
				$codigoProfessor = $consulta->resultado['profResponsavel'];
				$descricao = $consulta->resultado['descricao'];
				$idAno = $consulta->resultado['serie'];
				
				$consulta_ano = new conexao();
				$consulta_ano->solicitar("SELECT * FROM $tabela_anos WHERE id=$idAno");
				$ano = $consulta_ano->resultado['nome'];
	
				$consulta_nomeprof->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id = $codigoProfessor");
				$professor = $consulta_nomeprof->resultado['usuario_nome'];
				$maeProfessor = $consulta_nomeprof->resultado['usuario_nome_mae'];
				
				$consulta_professores = new conexao();
				$consulta_professores->solicitar("SELECT U.usuario_nome AS nome
											FROM TurmasUsuario AS TU, $tabela_usuarios AS U
											WHERE TU.codTurma = $identificacao 
												AND TU.associacao = $nivelProfessor
												AND TU.codUsuario = U.usuario_id");
				$numeroProfessores = $consulta_professores->registros;
				if($numeroProfessores == ''){
					$numeroProfessores = 0;
				}
											
				$consulta_monitores = new conexao();
				$consulta_monitores->solicitar("SELECT U.usuario_nome AS nome
											FROM TurmasUsuario AS TU, $tabela_usuarios AS U
											WHERE TU.codTurma = $identificacao 
													AND TU.associacao = $nivelMonitor
													AND TU.codUsuario = U.usuario_id");
				$numeroMonitores = $consulta_monitores->registros;
				if($numeroMonitores == ''){
					$numeroMonitores = 0;
				}
				
				$consulta_alunos = new conexao();
				$consulta_alunos->solicitar("SELECT U.usuario_nome AS nome
											FROM TurmasUsuario AS TU, $tabela_usuarios AS U
											WHERE TU.codTurma = $identificacao 
												AND TU.associacao = $nivelAluno
												AND TU.codUsuario = U.usuario_id");
				$numeroAlunos = $consulta_alunos->registros;
				if($numeroAlunos == ''){
					$numeroAlunos = 0;
				}
				
				$dados .= '&identificacao'.	   '='.$identificacao;
				$dados .= '&nome'.		       '='.$nome;
				$dados .= '&professor'.		   '='.$professor; 
				$dados .= '&nomeMaeProfessor'. '='.$maeProfessor;
				$dados .= '&descricao'.		   '='.$descricao;
				$dados .= '&ano'.		       '='.$ano;
				$dados .= '&numeroProfessores'.'='.$numeroProfessores;
				$dados .= '&numeroMonitores'.  '='.$numeroMonitores;
				$dados .= '&numeroAlunos'.     '='.$numeroAlunos;
				for($j=0; $j<$numeroProfessores; $j++){
					$nomeProfessor = $consulta_professores->resultado['nome'];
					$dados .= '&professor'.$j.'='.$nomeProfessor;
					$consulta_professores->proximo();
				}
				for($j=0; $j<$numeroMonitores; $j++){
					$nomeMonitor = $consulta_monitores->resultado['nome'];
					$dados .= '&monitor'.$j.'='.$nomeMonitor;
					$consulta_monitores->proximo();
				}
				for($j=0; $j<$numeroAlunos; $j++){
					$nomeAluno = $consulta_alunos->resultado['nome'];
					$dados .= '&aluno'.$j.'='.$nomeAluno;
					$consulta_alunos->proximo();
				}
			}
		}
		$consulta->proximo();
	}
	$dados .= '&numDadosEncontrados='.$numDadosEncontrados; 

    echo $dados;	
//A partir do fim do php, não escrever absolutamente nada. Nem código. &numDadosEncontrados receberá TUDO o que for escrito.
?>
