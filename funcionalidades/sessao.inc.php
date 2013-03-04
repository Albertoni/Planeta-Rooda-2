<?

/*
 * Retorna os dados contidos na sess�o do usu�rio que est� logado no Planeta ROODA.
 */
function sessaoUsuario($tipo=1){
	global $base_loc;

	session_start("PlanetaRooda");
 	if(session_is_registered("sessao")){
	 	//if (($tipo == 0) && ($_SESSION["associacao"] == 0))
		//	$path = $base_loc."/erro2.php";
		//else {
			$usuario['login']      = $_SESSION["login"];
			$usuario['senha']      = $_SESSION["senha"];
			$usuario['codUsuario'] = $_SESSION["codUsuario"];
			$usuario['associacao'] = $_SESSION["associacao"];
			$usuario['busca_query'] = $_SESSION["busca_query"];

			return $usuario;
		//}
	}
	else
		$path = $base_loc."/erro.php";

	echo"<script>window.location='$path'</script>";
}


function atualizaBuscaArte($query_busca) {
	$_SESSION["busca_query"] = $query_busca;
	$usuario['busca_query'] = $_SESSION["busca_query"];
}

/*
 * Atualiza a sess�o do usu�rio de acordo com a associa��o.
 * � utilizada quando um professor/administrador troca sua vis�o para a de um aluno.
 */
function atualizaSessaoUsuario($associacao, $tipo=1){
	global $base_loc;

	session_start("PlanetaRooda");
 	if(session_is_registered("sessao")) {
	 	//if (($tipo == 0) && ($_SESSION["associacao"] == 0))
		//	$path = $base_loc."/erro2.php";
		//else {
			$_SESSION["associacao"] = $associacao;
			return 1;
		//}
	}
	else
		$path = $base_loc."/erro.php";

	echo"<script>window.location='$path'</script>";
}

/*
 * Retorna os dados contidos na sess�o da turma do usu�rio que est� logado no Planeta ROODA.
 */
function sessaoTurma(){
	global $base_loc;
	@session_start("PlanetaRooda");
	 	if (session_is_registered("sessao")) {
		 	if ($_SESSION["codTurma"] == "")
		 		return -1;
			else {
			 	$turma["codTurma"]        = $_SESSION["codTurma"];
				$turma["nomeTurma"]       = $_SESSION["nomeTurma"];
				$turma["profResponsavel"] = $_SESSION["profResponsavel"];
				$turma["descricao"]       = $_SESSION["descricao"];
				$turma["nomeDisciplina"]  = $_SESSION["nomeDisciplina"];
				$turma["serie"]           = $_SESSION["serie"];
				$turma["escola"]          = $_SESSION["escola"];
				$turma["ferramentas"]     = $_SESSION["ferramentas"];
				$turma["cor"]             = $_SESSION["cor"];
				$turma["foto"]            = $_SESSION["foto"];
				$turma["associacao"]      = $_SESSION["associacaoTurma"];
				return $turma;
			}
	 	}
	 	else
		 	$path=$base_loc."/erro.php";

		echo"<script>window.location='$path'</script>";
}

/*
 * Atualiza a sess�o da turma.
 * � utilizada quando um professor/monitor de uma turma altera algum dado da mesma.
 */
function atualizaSessaoTurma($codTurma) {
	$select="SELECT * FROM Turmas WHERE codTurma=$codTurma";
	$turmas=db_busca($select);

	if(count($turmas)==1){
		session_start("PlanetaRooda");
		$_SESSION["codTurma"]        = $turmas[0]['codTurma'];
		$_SESSION["nomeTurma"]       = $turmas[0]['nomeTurma'];
		$_SESSION["profResponsavel"] = $turmas[0]['profResponsavel'];
		$_SESSION["descricao"]       = $turmas[0]['descricao'];
		$_SESSION["nomeDisciplina"]  = $turmas[0]['nomeDisciplina'];
		$_SESSION["serie"]           = $turmas[0]['serie'];
		$_SESSION["escola"]          = $turmas[0]['escola'];
		$_SESSION["ferramentas"]     = $turmas[0]['ferramentas'];
		$_SESSION["cor"]             = $turmas[0]['cor'];
		$_SESSION["foto"]            = $turmas[0]['foto'];
//		$_SESSION["associacaoTurma"] = $turmas[0]['associacao'];
	}
	return sessaoTurma();
}

/*
 * Exclui a sess�o de uma turma.
 * � utilizada sempre que um usu�rio sai de uma turma (volta para a tela de sele��o de turma).
 */
function excluiSessaoTurma(){
	global $base_loc;
	session_start("PlanetaRooda");
	 	if (session_is_registered("sessao")) {
		 	if ($_SESSION["codTurma"] == "")
		 		return -1;
			else {
			 	$_SESSION["codTurma"]        = "";
				$_SESSION["nomeTurma"]       = "";
				$_SESSION["profResponsavel"] = "";
				$_SESSION["descricao"]       = "";
				$_SESSION["nomeDisciplina"]  = "";
				$_SESSION["serie"]           = "";
				$_SESSION["escola"]          = "";
				$_SESSION["ferramentas"]     = "";
				$_SESSION["cor"]             = "";
				$_SESSION["foto"]            = "";
				$_SESSION["associacaoTurma"] = "";
				return 1;
			}
	 	}
	 	else
		 	$path=$base_loc."/erro.php";

		echo"<script>window.location='$path'</script>";
}

?>