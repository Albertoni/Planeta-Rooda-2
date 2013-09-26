<?php
/*
 * usuarios.class.php
 */
//$tabela_usuarios = "usuarios";
require_once("funcoes_aux.php");
require_once("cfg.php");
require_once("bd.php");
require_once("class/planeta.php");


class Usuario { //estrutura para o item post do blog
	var $id = 0;
	var $user = "";
	var $pass = "";
	var $birthday = "";
	var $name = "";
	var $email = "";
	var $personagemId = 0;
	var $nivel = 0;
	var $turmas = array();
	var $nivelAbsoluto = 0;
	private $dataUltimoLogin;

	function Usuario($id=0, $user="", $pass="", $birthday="", $name="", $email="", $personagem_id=0, $nivel=-1){
		$this->id = (int) $id;
		$this->user = $user;
		$this->pass = $pass;
		$this->birthday = $birthday;
		$this->name = $name;
		$this->email = $email;
		$this->personagemId = $personagem_id;
		$this->nivel = $nivel;
	}

	// Recebe como parametro um id (inteiro maior que 0)
	// Segundo parametro não é usado, não removo por medo de quebrar algo.
	public function openUsuario($param , $param2="") {
		global $tabela_usuarios; global $tabela_turmasUsuario;
		$q = new conexao();

		$id = (int) $param;
		$q->solicitar("SELECT *
					  FROM $tabela_usuarios JOIN personagens ON usuario_personagem_id = personagem_id
					  WHERE usuario_id = '$id'");
		$numItens= count($q->itens);
		if($numItens == 0)
			return "Usuario inexistente (Id=$id)" ;
		else
			$this->popular($q->resultado);
	}

	/**
	*											 GETTERS & SETTERS
	*/
	private function setId($id)						{$this->id = (int) $id;}
	private function setUser($user)					{$this->user = $user;}
	private function setPass($pass)					{$this->pass = $pass;}
	private function setBirthday($birthday)			{$this->birthday = $birthday;}
	private function setName($name)					{$this->name = $name;}
	private function setEmail($email)				{$this->email = $email;}
	private function setPersonagemId($personagemId)	{$this->personagemId = $personagemId;}
	private function setNivelAbsoluto($nivel)		{$this->nivelAbsoluto = $nivel;}
	private function setNivel($turma, $valor)		{$this->nivel[$turma] = $valor;}
	private function setGosto($gosto){$this->gosto = $gosto;}
	private function setNaoGosto($desgosto){$this->naoGosto = $desgosto;}

	public function getId()			{return $this->id;}
	public function getUser()		{return $this->user;}
	public function getPass()		{return $this->pass;}
	public function getBirthday()	{return $this->birthday;}
	public function getName()		{return $this->name;}
	public function getEmail()		{return $this->email;}
	public function getPersonagemId(){return $this->personagemId;}
	private function getNivel($turma){return isset($this->nivel[$turma]) ? $this->nivel[$turma] : 0;} // $turma é o id da turma no banco de dados
	public function getNivelAbsoluto(){return $this->nivelAbsoluto;}
	public function getDataUltimoLogin(){return $this->dataUltimoLogin;}
	public function getGosto(){return $this->gosto;}
	public function getNaoGosto(){return $this->naoGosto;}
	public function getSimpleAssoc() {
		$assoc = array();
		$assoc['id'] = $this->id;
		$assoc['usuario'] = $this->user;
		$assoc['nome'] = $this->name;
		return $assoc;
	}

	/**
	* Popula este usuário com o resultado de uma consulta no BD.
	*
	* @param Array<String,String> $resultadoBD Resultado da consulta correspondente a um usuário. Os nomes das colunas devem ser preservados.
	*/
	private function popular($resultadoBD){
		$this->setId($resultadoBD['usuario_id']);
		$this->setUser($resultadoBD['usuario_login']);
		$this->setPass($resultadoBD['usuario_senha']);
		$this->setBirthday($resultadoBD['usuario_data_aniversario']);
		$this->setName($resultadoBD['usuario_nome']);
		$this->setEmail($resultadoBD['usuario_email']);
		$this->setPersonagemId($resultadoBD['usuario_personagem_id']);
		$this->setNivelAbsoluto($resultadoBD['usuario_nivel']);
		//$this->dataUltimoLogin = $resultadoBD['personagem_ultimo_acesso'];
		
		
		// Agora preparamos para setar o nível
		
		$this->nivel = array();
		$niveis = new conexao(); global $tabela_turmasUsuario;
		
		$niveis->solicitar("SELECT codTurma,associacao FROM $tabela_turmasUsuario WHERE codUsuario = ".$resultadoBD['usuario_id']);
		for($i=0; $i < $niveis->registros; $i++){
			$this->setNivel($niveis->resultado['codTurma'], $niveis->resultado['associacao']);
			$niveis->proximo();
		}
		return false;
		
		
		// DEBUG REMOVER ARROBAS E TESTAR ISSO CORRETAMENTE MAIS TARDE
		@$this->setGosto($resultadoBD['gosto']);
		@$this->setNaoGosto($resultadoBD['nao_gosto']);
	}

	/*
	* Busca no BD usuários com nome parecido ao dado e os retorna em um array.
	*
	* @param String nome Nome que é substring dos nomes dos usuários que devem ser retornados.
	* @return Array<Usuario> Todos os usuários que têm o nome dado.
	*/
	public static function buscaPorNome($nome){
		$resultados = array();
		$conexao = new conexao();

		$nome = $conexao->sanitizaString($nome);

		$conexao->solicitar("SELECT * FROM usuarios WHERE usuario_nome LIKE '%".$nome."%'");
		for($i=0; $i<$conexao->registros; $i++){
			array_push($resultados, new Usuario());
			$resultados[$i]->popular($conexao->resultado);
			$conexao->proximo();
		}
		return $resultados;
	}

	/**
	* @return array associativo com os indices 'codTurma' e 'nomeTurma'
	*         das turmas que o usuário pertence.
	*/
	public function getTurmas(){
		$idUsuario = $this->id;
		$turmas = array();
		$conexaoTurmas = new conexao();

		$conexaoTurmas->solicitar(
			"SELECT T.codTurma as codTurma, T.nomeTurma as nomeTurma
			FROM Turmas AS T 
			INNER JOIN TurmasUsuario AS TU 
				ON T.codTurma = TU.codTurma
			WHERE codUsuario = $idUsuario
				OR T.profResponsavel = $idUsuario"
		);
			// "SELECT TU.codTurma, T.codTurma
			// FROM TurmasUsuario AS TU, Turmas AS T
			// WHERE TU.codUsuario = '".($this->id)."'
			// 	OR T.profResponsavel = '".($this->id)."'"

		for($i=0; $i<$conexaoTurmas->registros; $i++){
			$turmas[$i]['codTurma'] = $conexaoTurmas->resultado['codTurma'];
			$turmas[$i]['nomeTurma'] = $conexaoTurmas->resultado['nomeTurma'];
			$conexaoTurmas->proximo();
		}

		// $turmasSemDuplicatas = array_unique($turmas);

		return $turmas;
		// return $turmasSemDuplicatas;
	}

	/**
	* @return Todos os planetas que o usuário pode acessar, em um array com objetos da classe Planeta.
	*/
	public function getPlanetasQuePodeAcessar(){
		$planetasQuePodeAcessar = array();
		$conexaoIdsPlanetas = new conexao();
		$conexaoIdsPlanetas->solicitar("SELECT P.*
										FROM TurmasUsuario AS TU JOIN Planetas AS P ON P.Turma = TU.codTurma
										WHERE TU.codUsuario = ".($this->id)."
										GROUP BY P.Nome");
		
		//print_r($conexaoIdsPlanetas);
		$planetasInseridos = 0;
		for($i=0; $i<$conexaoIdsPlanetas->registros; $i++){
			$planeta = Planeta::getPorId($conexaoIdsPlanetas->resultado['Id']);
			$planetaExiste = $planeta != null;
			if($planetaExiste){
				$planetasQuePodeAcessar[$planetasInseridos] = $planeta;
				$planetasInseridos++;
			}
			$conexaoIdsPlanetas->proximo();
		}
		return $planetasQuePodeAcessar;
	}

	/**
	* @param 	int	$nivelDeCorte	Nível que servirá para pesquisar as turmas. É importante que este nível seja atômico e não a soma de vários níveis.
	* @return 	Array<Turma>		As turmas em que o usuário desempenha (no máximo) o papel _nivelDeCorte.
	*								Isto é:
	*									se _nivelDeCorte = aluno, retornará somente turmas em que o usuário é somente aluno.
	*									se _nivelDeCorte = monitor, retornará somente turmas em que o usuário é aluno e monitor ou somente monitor.
	*									se _nivelDeCorte = professor, retornará somente turmas em que o usuário é aluno, monitor e professor, ou aluno e professor, ou monitor e professor.
	*/
	public function buscaTurmasComNivel(/*int*/ $nivelDeCorte){
		global $nivelAluno;
		global $nivelMonitor;
		global $nivelProfessor;

		$conexaoTurmas = new conexao();
		$conexaoTurmas->solicitar("SELECT *
								FROM TurmasUsuario
								WHERE codUsuario = ".$this->getId()."
								GROUP BY codTurma");
		$turmasUsuario = array();

		for($i=0; $i<$conexaoTurmas->registros; $i++){
			$associacaoDefinida = isset($conexaoTurmas->resultado['associacao']) && $conexaoTurmas->resultado['associacao'] != '';
			$ehNoMaximoAluno = $conexaoTurmas->resultado['associacao'] == strval($nivelAluno);
			$ehNoMaximoMonitor = $conexaoTurmas->resultado['associacao'] == strval($nivelMonitor)
				|| $conexaoTurmas->resultado['associacao'] == strval($nivelAluno+$nivelMonitor);
			$ehNoMaximoProfessor = $conexaoTurmas->resultado['associacao'] == strval($nivelProfessor)
				|| $conexaoTurmas->resultado['associacao'] == strval($nivelAluno+$nivelMonitor+$nivelProfessor)
				|| $conexaoTurmas->resultado['associacao'] == strval($nivelMonitor+$nivelProfessor)
				|| $conexaoTurmas->resultado['associacao'] == strval($nivelAluno+$nivelProfessor);
			$turma = new turma();
			$turma->openTurma($conexaoTurmas->resultado['codTurma']);
			if($associacaoDefinida && $nivelDeCorte == $nivelAluno && $ehNoMaximoAluno){
				array_push($turmasUsuario, $turma);
			} else if($associacaoDefinida && $nivelDeCorte == $nivelMonitor && $ehNoMaximoMonitor){
				array_push($turmasUsuario, $turma);
			} else if($associacaoDefinida && $nivelDeCorte == $nivelProfessor && $ehNoMaximoProfessor){
				array_push($turmasUsuario, $turma);
			}

			$conexaoTurmas->proximo();
		}

		return $turmasUsuario;
	}

	/*
	* @return Planeta de seu quarto.
	*/
	public function getIdTerrenoQuarto(){
		$conexaoQuarto = new conexao();
		$conexaoQuarto->solicitar("SELECT T.*
								   FROM terrenos AS T JOIN usuarios AS U ON T.terreno_id = U.quarto_id
								   WHERE U.usuario_id = ".($this->id));
		return $conexaoQuarto->resultado['terreno_id'];
	}

	public function podeAcessar($cutoff, $turma){ // cutoff = ponto de corte, o bitmap de niveis que podem acessar
		/*if ($this->isAdmin()){
			return true; // ISSO NÃO DEVE NUNCA MAIS SER USADO
		}*/
		
		$niveisTurma = $this->getNivel($turma);
		$cutoff = (int) $cutoff;

		return $niveisTurma & $cutoff;
	}
	public function isAdmin(){return $this->getNivelAbsoluto() & 1;}

	/*
	* @return cor da luva do personagem, para a carteira dele
	*/
	public function getCorLuva(){
		global $tabela_personagens; global $tabela_usuarios;
		$personagem_id = $this->personagemId;
		$q = new conexao();
		$q->solicitar(
			"SELECT personagem_cor_luvas_botas FROM $tabela_personagens
			WHERE personagem_id = $personagem_id"
		);
		return $q->resultado['personagem_cor_luvas_botas'] ? $q->resultado['personagem_cor_luvas_botas'] : false;
	}
	
	public function printListaTurmas(){
		$id = $this->id;
		$conexaoNomes = new conexao();
		$conexaoNomes->solicitar(
			"SELECT T.nomeTurma
			FROM TurmasUsuario AS TU, Turmas AS T
			WHERE TU.codUsuario = '$id'
			OR T.profResponsavel = '$id'"
		);
		while ($conexaoNomes->resultado) {
			$buffer = $conexaoNomes->resultado['nomeTurma'];
			$conexaoNomes->proximo();
		}
		echo implode(", ", $buffer);
	}
}
