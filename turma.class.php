<?php

require_once("funcoes_aux.php");
require_once("cfg.php");
require_once("bd.php");


class turma{
//dados
	
	/*
	* Funcionalidades
	*/
	const BIBLIOTECA='1';
	const BLOG='2';
	const FORUM='3';
	const ARTE='4';
	const PERGUNTA='5';
	const PORTFOLIO='6';
	const PLAYER='7';
	const AULAS='8';
	
	/*
	* Dados da turma no bd.
	*/
	private $id = 0;
	private $nome;
	private $idProfessorResponsavel;
	private $descricao;
	
	/*
	* Arrays de membros da turma
	*/
	private $professores;
	private $monitores;
	private $alunos;
	
	
//métodos
	/***/
	function turma($id = 0){
		if($id != 0){
			$this->openTurma($id);
		}
	}
	
	public function getId(){ return $this->id; }
	public function getNome(){ return $this->nome; }
	public function getProfessores(){return $this->professores;}
	public function getMonitores(){return $this->monitores;}
	public function getAlunos(){return $this->alunos;}
	public function getIdProfessorResponsavel(){return $this->idProfessorResponsavel;}
	public function getDescricao(){return $this->descricao;}
	
	private function setId		($id)	{ $this->id = (int) $id; }
	private function setNome		($nome)	{ $this->nome = $nome; }
	private function setDescricao	($desc)	{ $this->descricao = $desc; }
	private function setIdProfessorResponsavel($idProfessorResponsavel){ $this->idProfessorResponsavel = $idProfessorResponsavel; }
	
	/*
	* @return int Total de alunos nesta turma.
	*/
	public function getNumeroAlunos(){
		global $nivelAluno;
		
		$conexao = new conexao();
		$conexao->solicitar("SELECT COUNT(*) AS total_alunos
							FROM TurmasUsuario
							WHERE associacao = '$nivelAluno'
								AND codTurma = '".$this->id."'");
		
		return $conexao->resultado['total_alunos'];
	}
	
	/*
	* Carrega os integrantes da turma e divide eles de acordo com a posição deles na turma
	* 
	* @return bool Falha ou sucesso no SQL
	*/
	public function carregaMembros(){
		$q = new conexao();
		$q->solicitar("SELECT * 
						FROM TurmasUsuario
						WHERE codTurma=".$this->getId());
		
		if($q->erro != ""){
			return false;
		}else{
			global $nivelProfessor; global $nivelMonitor; global $nivelAluno;
			
			$this->professores = array();
			$this->monitores = array();
			$this->alunos = array();
			
			for($i=0; $i<$q->registros; $i++){
				$tempUser = new Usuario();
				$tempUser->openUsuario($q->resultado['codUsuario']);
				
				switch($q->resultado['associacao']){
					case $nivelProfessor:
						$this->professores[] = $tempUser;
						break;
					case $nivelManitor:
						$this->monitores[] = $tempUser;
						break;
					case $nivelAluno:
						$this->alunos[] = $tempUser;
						break;
				}
				$q->proximo();
			}
			
			return true;
		}
	}
	
	/*
	* Preenche dados da turma com o que encontrar no banco de dados.
	* @param id_param A id que será procurar no bd.
	*/
	public function openTurma($id_param){
		$id_param = (int) $id_param;
		$conexao = new conexao();
		$conexao->solicitar(
			"SELECT *
			 FROM Turmas
			 WHERE codTurma=$id_param"
		);
		if ($conexao->resultado) {
			$this->setId((int) $id_param);
			$this->setNome($conexao->resultado['nomeTurma']);
			$this->setIdProfessorResponsavel($conexao->resultado['profResponsavel']);
			$this->setDescricao($conexao->resultado['descricao']);
		}
	}
	
	/*
	* Determina se houve alterações na funcionalidade desta turma.
	* @param funcionalidade_param Funcionalidade a ser verificada.
	* @param data_param Data à partir da qual uma alteração deve ter acontecido para ser retornada.
	* @return Número de alterações que ocorreram na dada funcionalidade desta turma desde a data passada.
	*/
	public function getNumeroAlteracoes($funcionalidade_param, $data_param){
		$alteracoes = 0;
		$sql = '';
		
		// DEBUG REMOVER ISSO ASSIM QUE O TEMPO DE ULTIMO LOGIN FOR IMPLEMENTADO
		$data_param = strtotime("-5 years"); //código de teste para mostrar para as gurias na reunião...
		$data_param = date("Y-m-d H:i:s", $data_param);
		
		switch($funcionalidade_param){
			case turma::BIBLIOTECA: $sql = "SELECT *
											FROM (
													SELECT COUNT(*) AS alteracoes
													FROM BibliotecaMateriais
													WHERE codTurma = ".$this->id." AND '$data_param'<=data
											) AS BMalteracoes
											UNION ALL(
												SELECT COUNT(*) AS alteracoes
												FROM BibliotecaComentarios AS BC JOIN BibliotecaMateriais AS BM ON BC.codMaterial = BM.codMaterial
												WHERE BM.codTurma = ".$this->id." AND '$data_param'<=BC.data
											)";
				break;
			case turma::BLOG:		$sql = "SELECT *
											FROM (
													SELECT COUNT(*) AS alteracoes
													FROM blogblogs AS B JOIN usuarios AS U ON OwnersIds = usuario_id
																		JOIN TurmasUsuario AS TU ON TU.codUsuario = U.usuario_id
																		JOIN blogposts AS BP ON BP.BlogId = B.Id
													WHERE B.Tipo=1 AND TU.codTurma=".$this->id." AND '$data_param'<=BP.Date
											) AS BPalteracoes
											UNION ALL(
												SELECT COUNT(*) AS alteracoes
												FROM blogblogs AS B JOIN usuarios AS U ON OwnersIds = usuario_id
																	JOIN TurmasUsuario AS TU ON TU.codUsuario = U.usuario_id
																	JOIN blogposts AS BP ON BP.BlogId = B.Id
																	JOIN blogcomentarios AS BC ON BC.PostId = BP.Id
												WHERE B.Tipo=1 AND TU.codTurma=".$this->id." AND '$data_param'<=BC.Date
											)";
				break;
			case turma::FORUM:		$sql = "";
				break;
			case turma::ARTE:		$sql = "SELECT *
											FROM (
													SELECT COUNT(*) AS alteracoes
													FROM ArtesDesenhos
													WHERE codTurma=".$this->id." AND '$data_param'<=Data
											) AS ADalteracoes
											UNION ALL(
												SELECT COUNT(*) AS alteracoes
												FROM ArtesComentarios AS AC JOIN ArtesDesenhos AS AD ON AC.CodDesenho=AD.CodDesenho
												WHERE codTurma=".$this->id." AND '$data_param'<=AC.Data
											)";
				break;
			case turma::PERGUNTA:	$sql = "SELECT COUNT(*) AS alteracoes
											FROM PerguntaQuestionarios
											WHERE turma = ".$this->id." AND '$data_param'<=datainicio";
				break;
			case turma::PORTFOLIO:	$sql = "SELECT *
											FROM (
													SELECT COUNT(*) AS alteracoes
													FROM PortfolioProjetos 
													WHERE turma=".$this->id." AND '$data_param'<=dataCriacao
											) AS PPalteracoes
											UNION ALL(
												SELECT COUNT(*) AS alteracoes
												FROM PortfolioProjetos AS PP JOIN PortfolioPosts AS PPo ON PP.id=PPo.projeto_id
												WHERE PP.turma=".$this->id." AND ('$data_param'<=PPo.dataCriacao OR '$data_param'<=PPo.dataUltMod)
											)";
				break;
			case turma::PLAYER:		$sql = "SELECT COUNT(*)
											FROM PlayerComentarios AS PC JOIN PlayerVideos AS PV ON PC.id_video=PV.id
											WHERE PV.turma=".$this->id." AND '$data_param'<=PC.data";
				break;
			case turma::AULAS:		$sql = "";
				break;
		}
		if($sql != ''){
			$conexaoAlteracoes = new conexao();
			$conexaoAlteracoes->solicitar($sql);
			if(isset($conexaoAlteracoes->resultado['alteracoes'])){
				$alteracoes = 0;
				for($i=0; $i<$conexaoAlteracoes->registros; $i++){
					$alteracoes += $conexaoAlteracoes->resultado['alteracoes'];
					$conexaoAlteracoes->proximo();
				}
			}
			
		}
		return $alteracoes;
	}
}

