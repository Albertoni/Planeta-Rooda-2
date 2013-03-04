<?php
	/*
	* URL para debug.
	* sideshowbob/planeta2_diogo/desenvolvimento/phps_do_menu/edicao_contas.php?identificacao=469&login=drcravo&senha=drcravo&diaAniversario=25&mesAniversario=09&anoAniversario=1991&nome=Diogo Raphael Cravo&nomeMae=sdas&email=diogo_sitw@hotmail.com&nivel=1&apelido=Diogo Raphael Cravo&sexo=1&turmas=kkk,abc&turmasConvidado=abc
	*/
	
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require("../../cfg.php");
	require("../../bd.php");
	
	/*
	* Definição de erros que podem ocorrer neste arquivo.
	*/
	$SUCESSO = "999999";
	$ERRO_LOGIN_DUPLICADO = "20";
	$ERRO_FALTA_ID = "21";
	$ERRO_CONEXAO_BD = "22";
	$ERRO_FALTA_PERMISSAO  = "23";
	$ERRO_USUARIO_NAO_POSSUI_PERSONAGEM = "24";
	$ERRO_TURMA_INEXISTENTE = "25";
	
	
	$operacaoRealizadaComSucesso = true;
	$mensagemDeErro   = "";
	$identificacao    = $_POST['identificacao'];
	$login            = $_POST['login'];
	$senha            = $_POST['senha'];
	$diaAniversario   = $_POST['diaAniversario'];
	$mesAniversario   = $_POST['mesAniversario'];
	$anoAniversario   = $_POST['anoAniversario'];
	$nome             = $_POST['nome'];
	$nomeMae          = $_POST['nomeMae'];
	$email            = $_POST['email'];
	$nivel            = $_POST['nivel'];
	$apelido          = $_POST['apelido'];
	$sexo             = $_POST['sexo'];
	$turmasProfessor          = $_POST['turmasProfessor'];
	$turmasProfessorConvidado = $_POST['turmasProfessorConvidado'];
	$turmasMonitor            = $_POST['turmasMonitor'];
	$turmasMonitorConvidado   = $_POST['turmasMonitorConvidado'];
	$turmasAluno           	  = $_POST['turmasAluno'];
	$turmasAlunoConvidado  	  = $_POST['turmasAlunoConvidado'];
	
	/*
	* Edita um usuário para que fique de acordo com os dados passados.
	* Qualquer um dos parâmetros (à exceção da identificação) pode ser omitido, sendo substituído por "null".
	* @param identificacao_param A id do usuário na $tabela_usuarios do banco de dados.
	* @param login_param O login do usuário. Deve ser um login ainda não registrado no banco de dados.
	* @param senha_param A senha do usuário. 
	* @param dataAniversario_param Data de aniversário do usuário.
	* @param 
	*
	*
	* @return Os possíveis valores de retorno estão definidos no início deste arquivo.
	*			Em caso de sucesso, retorna-se $SUCESSO
	*			Caso a id não seja informada, retorna-se $ERRO_FALTA_ID
	*			Caso não tenha sido possível conectar ao banco de dados, retorna-se $ERRO_CONEXAO_BD
	*			Caso o login já exista no banco de dados, retorna-se $ERRO_LOGIN_DUPLICADO
	*			Caso o usuário logado não tenha permissão para editar o usuário de id passada, retorna-se $ERRO_FALTA_PERMISSAO
	*/
	function editarUsuario($identificacao_param, $login_param, $senha_param, $dataAniversario_param, $nome_param, $nomeMae_param, $email_param, $nivel_param){
		require_once("../../cfg.php");
		require_once("../../bd.php");
		
		global $tabela_usuarios;
		global $SUCESSO;
		global $ERRO_FALTA_ID;
		global $ERRO_CONEXAO_BD;
		global $ERRO_LOGIN_DUPLICADO;
		global $ERRO_FALTA_PERMISSAO;
		
		$statusOperacao = $SUCESSO; //Indica como anda a operação até o momento.
		
		$conexao = new conexao();
		
		//Caso a id não seja informada, retorna-se $ERRO_FALTA_ID
		if($identificacao_param == null){ 
			$statusOperacao = $ERRO_FALTA_ID;
		}
		
		//Caso o login já exista no banco de dados, retorna-se $ERRO_LOGIN_DUPLICADO
		$conexao->solicitar("SELECT *
							 FROM $tabela_usuarios
							 WHERE usuario_login = '$login_param'
								AND usuario_id != $identificacao_param");
		if($conexao->erro != ''){
			$statusOperacao = $ERRO_CONEXAO_BD; echo 1;
		} else if(0 < $conexao->registros){
			$statusOperacao = $ERRO_LOGIN_DUPLICADO;
		}
		
		//Caso o usuário logado não tenha permissão para editar o usuário de id passada, retorna-se $ERRO_FALTA_PERMISSAO
		//como fazer?!?!?
		
		//Montagem do SQL que editará o usuário.
		if($statusOperacao == $SUCESSO){
			$pesquisaEdicaoContasSQL = "UPDATE $tabela_usuarios SET ";
			if($login_param != null){
				$pesquisaEdicaoContasSQL.="usuario_login = '$login_param', ";
			}
			if($senha_param != null){
				$senha_param = md5($senha_param);
				$pesquisaEdicaoContasSQL.="usuario_senha = '$senha_param', ";
			}
			if($dataAniversario_param != null){
				$pesquisaEdicaoContasSQL.="usuario_data_aniversario = '$dataAniversario_param', ";
			}
			if($nome_param != null){
				$pesquisaEdicaoContasSQL.="usuario_nome = '$nome_param', ";
			}
			if($nomeMae_param != null){
				$pesquisaEdicaoContasSQL.="usuario_nome_mae = '$nomeMae_param', ";
			}
			if($email_param != null){
				$pesquisaEdicaoContasSQL.="usuario_email = '$email_param', ";
			}
			if($nivel_param != null){
				$pesquisaEdicaoContasSQL.="usuario_nivel = $nivel_param ";
			}
			$pesquisaEdicaoContasSQL.=" WHERE usuario_id = $identificacao_param";
		}
		
		//Edição do usuário.
		if($statusOperacao == $SUCESSO){
			$conexao->solicitar($pesquisaEdicaoContasSQL);
			if($conexao->erro != ''){
				$statusOperacao = $ERRO_CONEXAO_BD;
			}
		}
		
		return $statusOperacao;
	}
	/*
	* Edita um personagem para que fique de acordo com os dados passados.
	* Qualquer um dos parâmetros, à exceção da identificação, pode ser omitido, sendo substituído por null.
	* @param identificacao_param A id do usuário dono do personagem na $tabela_usuarios do banco de dados.
	* @param nome_param O nome que deseja-se que o personagem tenha.
	* @param sexo_param O sexo que deseja-se que o personagem tenha. A informação de sexo é utilizada para determinar o avatar default de um personagem.
	* @return Os possíveis valores de retorno estão definidos no início deste arquivo.
	*			Em caso de sucesso, retorna-se $SUCESSO
	*			Caso a id não seja informada, retorna-se $ERRO_FALTA_ID
	*			Caso não tenha sido possível conectar ao banco de dados, retorna-se $ERRO_CONEXAO_BD
	*			Caso o usuário logado não tenha permissão para editar o usuário de id passada, retorna-se $ERRO_FALTA_PERMISSAO
	*			Caso o usuário passado não possua personagem, retorna-se $ERRO_USUARIO_NAO_POSSUI_PERSONAGEM
	*/
	function editarPersonagem($identificacao_param, $nome_param, $sexo_param){
		require_once("../../cfg.php");
		require_once("../../bd.php");
		
		global $tabela_usuarios;
		global $tabela_personagens;
		global $SUCESSO;
		global $ERRO_FALTA_ID;
		global $ERRO_CONEXAO_BD;
		global $ERRO_FALTA_PERMISSAO;
		global $ERRO_USUARIO_NAO_POSSUI_PERSONAGEM;
		
		$statusOperacao = $SUCESSO;
		
		$conexao = new conexao();
		
		//Caso a id não seja informada, retorna-se $ERRO_FALTA_ID
		if($identificacao_param == null and $statusOperacao == $SUCESSO){
			$statusOperacao = $ERRO_FALTA_ID;
		}
		
		//Caso o usuário passado não possua personagem, retorna-se $ERRO_USUARIO_NAO_POSSUI_PERSONAGEM
		$conexao->solicitar("SELECT *
							 FROM $tabela_usuarios AS U, $tabela_personagens AS P
							 WHERE P.personagem_id = U.usuario_personagem_id
								AND U.usuario_id = '$identificacao_param'");
		if($conexao->erro != '' and $statusOperacao == $SUCESSO){
			$statusOperacao = $ERRO_CONEXAO_BD;
		} else if($conexao->registros == 0 and $statusOperacao == $SUCESSO){
			$statusOperacao = $ERRO_USUARIO_NAO_POSSUI_PERSONAGEM;
		} else {
			$personagem_id = $conexao->resultado['personagem_id'];
		}
		
		//Caso o usuário logado não tenha permissão para editar o usuário de id passada, retorna-se $ERRO_FALTA_PERMISSAO
		//??
		
		//Montagem do SQL.
		if($statusOperacao == $SUCESSO){
			$pesquisaEdicaoPersonagemSQL = "UPDATE $tabela_personagens SET ";
			if($nome_param != null){
				$pesquisaEdicaoPersonagemSQL.="personagem_nome = '$nome_param', ";
			}
			if($sexo_param != null){
				$pesquisaEdicaoPersonagemSQL.="personagem_avatar_1 = $sexo_param";
			}
			$pesquisaEdicaoPersonagemSQL.=" WHERE personagem_id = $personagem_id";
		}
		
		//Edição do personagem.
		if($statusOperacao == $SUCESSO){
			$conexao->solicitar($pesquisaEdicaoPersonagemSQL);
			if($conexao->erro != ''){
				$statusOperacao = $ERRO_CONEXAO_BD;
			}
		}
		
		return $statusOperacao;
	}
	/*
	* Relaciona erros com mensagens. Na prática, usada para limpar o código.
	* @param erro_param Um erro, segundo definido no início do arquivo. Também aceita $SUCESSO.
	* @return Mesangem de erro, segundo o erro passado. Caso $SUCESSO seja passada, retorna ''.
	*/
	function getMensagemErro($erro_param){
		global $SUCESSO;
		global $ERRO_LOGIN_DUPLICADO;
		global $ERRO_FALTA_ID;
		global $ERRO_CONEXAO_BD;
		global $ERRO_FALTA_PERMISSAO;
		global $ERRO_USUARIO_NAO_POSSUI_PERSONAGEM;
		global $ERRO_TURMA_INEXISTENTE;
		
		switch($erro_param){
			case $SUCESSO: return '';
				break;
			case $ERRO_TURMA_INEXISTENTE: return utf8_encode('Desculpe, esta turma não existe mais.');
				break;
			case $ERRO_LOGIN_DUPLICADO: return utf8_encode('Desculpe, o login já foi escolhido.');
				break;
			case $ERRO_FALTA_ID: return 'Desculpe, houve um erro no flash (falta_id)';
				break;
			case $ERRO_CONEXAO_BD: return utf8_encode('Desculpe, não foi possível conectar ao banco de dados.');
				break;
			case $ERRO_FALTA_PERMISSAO: return utf8_encode('Desculpe, você não possui permissão para esta conta.');
				break;
			case $ERRO_USUARIO_NAO_POSSUI_PERSONAGEM: return utf8_encode('Desculpe, o usuário não possui personagem.');
				break;
			default: return 'Desculpe, houve um erro desconhecido.';
				break;
		}
		
	}
	/*
	* Edita as turmas às quais um usuário pertence. Qualquer dado, à exceção da identificação, pode ser omitido.
	* @param identificacao_param A id na $tabela_usuario do usuário a ser editado.
	* @param turmas_X_param As turmas que o usuário terá após edição. Para deletar todas, use ''. X é o papel do usuário na turma.
	*					    As turmas devem estar identificadas por seus nomes e separadas por vírgula, em formato de String.
	* @param turmas_X_convidado_param As turmas às quais o usuário estará convidado a se juntar após a edição. Para deletar todas, use ''. X é o papel do usuário na turma.
	*					  			  As turmas devem estar identificadas por seus nomes e separadas por vírgula, em formato de String.
	* @return Os possíveis valores de retorno estão definidos no início deste arquivo.
	*			Em caso de sucesso, retorna-se $SUCESSO
	*			Caso a id não seja informada, retorna-se $ERRO_FALTA_ID
	*			Caso não tenha sido possível conectar ao banco de dados, retorna-se $ERRO_CONEXAO_BD
	*			Caso o usuário logado não tenha permissão para editar o usuário de id passada, retorna-se $ERRO_FALTA_PERMISSAO
	*			Caso alguma turma não exista, retorna-se $ERRO_TURMA_INEXISTENTE
	*/
	function editarTurmasUsuario($identificacao_param, $turmas_professor_param, $turmas_professor_convidado_param, $turmas_monitor_param, $turmas_monitor_convidado_param,
								 $turmas_aluno_param, $turmas_aluno_convidado_param){
		require_once("../../cfg.php");
		require_once("../../bd.php");
		
		/*
		* Após pesquisa no banco de dados pelos nomes de turmas passado por parâmetros, cria-se um array de arrays.
		* Em cada array que é elemento deste array de arrays criado, valem estes índices.
		*/
		$INDICE_ARRAY_GERADO_TURMAS_ID_TURMA = 0;
		$INDICE_ARRAY_GERADO_TURMAS_PAPEL_NA_TURMA = 1;
		
		global $mensagemDeErro;
		global $nivelProfessor;
		global $nivelMonitor;
		global $nivelAluno;
		global $SUCESSO;
		global $ERRO_FALTA_ID;
		global $ERRO_CONEXAO_BD;
		global $ERRO_FALTA_PERMISSAO;
		global $ERRO_TURMA_INEXISTENTE;
		
		$statusOperacao = $SUCESSO;
		
		$conexao = new conexao();
		
		//Caso a id não seja informada, retorna-se $ERRO_FALTA_ID
		if($identificacao_param == null and $statusOperacao == $SUCESSO){
			$statusOperacao = $ERRO_FALTA_ID;
		}
		
		//Caso o usuário logado não tenha permissão para editar o usuário de id passada, retorna-se $ERRO_FALTA_PERMISSAO
		//????
		
		//Caso alguma turma não exista, retorna-se $ERRO_TURMA_INEXISTENTE
		$turmas = array();
		$turmasConvidado = array();
		$nomesTurmasProfessor = explode(",", $turmas_professor_param);
		$nomesTurmasProfessor = array_unique($nomesTurmasProfessor);
		$nomesTurmasMonitor = explode(",", $turmas_monitor_param);
		$nomesTurmasMonitor = array_unique($nomesTurmasMonitor);
		$nomesTurmasAluno = explode(",", $turmas_aluno_param);
		$nomesTurmasAluno = array_unique($nomesTurmasAluno);
		$nomesTurmasConvidadoProfessor = explode(",", $turmas_professor_convidado_param);
		$nomesTurmasConvidadoProfessor = array_unique($nomesTurmasConvidadoProfessor);
		$nomesTurmasConvidadoMonitor = explode(",", $turmas_monitor_convidado_param);
		$nomesTurmasConvidadoMonitor = array_unique($nomesTurmasConvidadoMonitor);
		$nomesTurmasConvidadoAluno = explode(",", $turmas_convidado_param);
		$nomesTurmasConvidadoAluno = array_unique($turmas_aluno_convidado_param);
		if($statusOperacao == $SUCESSO){
			for($i=0; $i < count($nomesTurmasProfessor) and $statusOperacao == $SUCESSO; $i++){
				$conexao->solicitar("SELECT *
									 FROM Turmas
									 WHERE nomeTurma = '$nomesTurmasProfessor[$i]'");
				if($conexao->erro != ''){
					$statusOperacao == $ERRO_CONEXAO_BD;
				} else if($conexao->registros == 0){
					$statusOperacao == $ERRO_TURMA_INEXISTENTE;
				} else {
					array_push($turmas, array($conexao->resultado['codTurma'], $nivelProfessor));
				}
			}
			for($i=0; $i < count($nomesTurmasMonitor) and $statusOperacao == $SUCESSO; $i++){
				$conexao->solicitar("SELECT *
									 FROM Turmas
									 WHERE nomeTurma = '$nomesTurmasMonitor[$i]'");
				if($conexao->erro != ''){
					$statusOperacao == $ERRO_CONEXAO_BD;
				} else if($conexao->registros == 0){
					$statusOperacao == $ERRO_TURMA_INEXISTENTE;
				} else {
					array_push($turmas, array($conexao->resultado['codTurma'], $nivelMonitor));
				}
			}
			for($i=0; $i < count($nomesTurmasAluno) and $statusOperacao == $SUCESSO; $i++){
				$conexao->solicitar("SELECT *
									 FROM Turmas
									 WHERE nomeTurma = '$nomesTurmasAluno[$i]'");
				if($conexao->erro != ''){
					$statusOperacao == $ERRO_CONEXAO_BD;
				} else if($conexao->registros == 0){
					$statusOperacao == $ERRO_TURMA_INEXISTENTE;
				} else {
					array_push($turmas, array($conexao->resultado['codTurma'], $nivelAluno));
				}
			}
			for($i=0; $i < count($nomesTurmasConvidadoProfessor) and $statusOperacao == $SUCESSO; $i++){
				$conexao->solicitar("SELECT *
									 FROM Turmas
									 WHERE nomeTurma = '$nomesTurmasConvidadoProfessor[$i]'");
				if($conexao->erro != ''){
					$statusOperacao == $ERRO_CONEXAO_BD;
				} else if($conexao->registros == 0){
					$statusOperacao == $ERRO_TURMA_INEXISTENTE;
				} else {
					array_push($turmasConvidado, array($conexao->resultado['codTurma'], $nivelProfessor));
				}
			}
			for($i=0; $i < count($nomesTurmasConvidadoMonitor) and $statusOperacao == $SUCESSO; $i++){
				$conexao->solicitar("SELECT *
									 FROM Turmas
									 WHERE nomeTurma = '$nomesTurmasConvidadoMonitor[$i]'");
				if($conexao->erro != ''){
					$statusOperacao == $ERRO_CONEXAO_BD;
				} else if($conexao->registros == 0){
					$statusOperacao == $ERRO_TURMA_INEXISTENTE;
				} else {
					array_push($turmasConvidado, array($conexao->resultado['codTurma'], $nivelMonitor));
				}
			}
			for($i=0; $i < count($nomesTurmasConvidadoAluno) and $statusOperacao == $SUCESSO; $i++){
				$conexao->solicitar("SELECT *
									 FROM Turmas
									 WHERE nomeTurma = '$nomesTurmasConvidadoAluno[$i]'");
				if($conexao->erro != ''){
					$statusOperacao == $ERRO_CONEXAO_BD;
				} else if($conexao->registros == 0){
					$statusOperacao == $ERRO_TURMA_INEXISTENTE;
				} else {
					array_push($turmasConvidado, array($conexao->resultado['codTurma'], $nivelAluno));
				}
			}
		}
				
		//Deletar turmas já existentes.
		if($statusOperacao == $SUCESSO){
			$conexao->solicitar("DELETE FROM TurmasUsuario
								 WHERE codUsuario = $identificacao_param");
			if($conexao->erro != ''){
				$statusOperacao == $ERRO_CONEXAO_BD;
			} 
			$conexao->solicitar("DELETE FROM TurmasUsuarioConvidado
								 WHERE codUsuario = $identificacao_param");
			if($conexao->erro != ''){
				$statusOperacao == $ERRO_CONEXAO_BD;
			} 
		}
		
		//Montagem do SQL.
		if($statusOperacao == $SUCESSO){
			$sqlTurmas = "INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES ";
			for($i=0; $i < count($turmas) and $statusOperacao == $SUCESSO; $i++){
				if($i != 0){
					$sqlTurmas.= ",";
				}
				$sqlTurmas.= "(".$turmas[$i][$INDICE_ARRAY_GERADO_TURMAS_ID_TURMA].", $identificacao_param, '".$turmas[$i][$INDICE_ARRAY_GERADO_TURMAS_PAPEL_NA_TURMA]."') ";
			}
			$sqlTurmasConvidado = "INSERT INTO TurmasUsuarioConvidado (codTurma, codUsuario, associacao) VALUES ";
			for($i=0; $i < count($turmasConvidado) and $statusOperacao == $SUCESSO; $i++){
				if($i != 0){
					$sqlTurmasConvidado.= ",";
				}
				$sqlTurmasConvidado.= "(".$turmasConvidado[$i][$INDICE_ARRAY_GERADO_TURMAS_ID_TURMA].", $identificacao_param, '".$turmasConvidado[$i][$INDICE_ARRAY_GERADO_TURMAS_PAPEL_NA_TURMA]."') ";
			}
		}
		
		//Efetuar consulta.
		if($statusOperacao == $SUCESSO){
			$conexao->solicitar($sqlTurmas);
			if($conexao->erro != '' and $sqlTurmas != "INSERT INTO TurmasUsuario (codTurma, codUsuario, associacao) VALUES "){
				$mensagemDeErro = $sqlTurmas;
				$statusOperacao = $ERRO_CONEXAO_BD;
			}
			//echo '&mensagemDeErro='.$turmas[0];
			$conexao->solicitar($sqlTurmasConvidado);
			if($conexao->erro != '' and $sqlTurmasConvidado != "INSERT INTO TurmasUsuarioConvidado (codTurma, codUsuario, associacao) VALUES "){
				$statusOperacao = $ERRO_CONEXAO_BD;
			}
		}
		
		return $statusOperacao;
	}
	
	//Edição do usuário.
	$dataAniversario = $anoAniversario . "-" . $mesAniversario . "-" . $diaAniversario;
	$statusEdicaoUsuario = editarUsuario($identificacao, $login, $senha, $dataAniversario, $nome, $nomeMae, $email, $nivel);
	$mensagemErro = getMensagemErro($statusEdicaoUsuario);
	if($mensagemErro == ''){
		$operacaoRealizadaComSucesso = true;
	} else {
		$operacaoRealizadaComSucesso = false;
		$mensagemDeErro = '1'.$mensagemErro;
	}
	
	//Edição do personagem.
	if($operacaoRealizadaComSucesso){
		$statusEdicaoPersonagem = editarPersonagem($identificacao, $apelido, $sexo);
		$mensagemErro = getMensagemErro($statusEdicaoPersonagem);
		if($mensagemErro == ''){
			$operacaoRealizadaComSucesso = true;
		} else {
			$operacaoRealizadaComSucesso = false;
			$mensagemDeErro = '2'.$mensagemErro;
		}
	}
	
	//Edição de Turmas do Usuário.
	if($operacaoRealizadaComSucesso){
		$statusEdicaoTurmas = editarTurmasUsuario($identificacao, $turmasProfessor, $turmasProfessorConvidado, $turmasMonitor, $turmasMonitorConvidado, $turmasAluno, $turmasAlunoConvidado);
		$mensagemErro = getMensagemErro($statusEdicaoTurmas);
		if($mensagemErro == ''){
			$operacaoRealizadaComSucesso = true;
		} else {
			$operacaoRealizadaComSucesso = false;
			//$mensagemDeErro = '3'.$mensagemErro;
		}
	}
	
	$dados = '&operacaoRealizadaComSucesso'    .'='.$operacaoRealizadaComSucesso;
	$dados.= '&mensagemDeErro'                 .'='.$mensagemDeErro;
	
	echo $dados;
?>
