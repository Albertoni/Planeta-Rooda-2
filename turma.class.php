<?php

class Turma{
	
	private $id;
	private $nomeTurma;
	private $profResponsavel;
	private $descricao;
	private $serie;
	private $escola;
	private $idChat;
	private $idPlaneta;
	private $NivelPermissao = array(); //Array que conterá o nível de permissao dos usuarios de uma turma, indexado por CodUsuario.
	private $salvo;
	
	const BLOG = 1;
	const PORTFOLIO = 2;
	const BIBLIOTECA = 3;
	const PERGUNTA = 4;
	const AULAS = 5;
	const COMUNICADOR = 6;
	const FORUM = 7;
	const ARTE = 8;
	const PLAYER = 10;

	//Públicos porque são usados pelo script salvaEdicaoTurma.
	function setNomeTurma($novoNomeTurma){	$this->nomeTurma = $novoNomeTurma;}
	function setDescricao($novaDescricao){	$this->descricao = $novaDescricao;}
	
	function getId()				{return $this->id;}
	function getNome()				{return $this->nomeTurma;}
	function getProfResponsavel()	{return $this->profResponsavel;}
	function getDescricao()			{return $this->descricao;}
	function getSerie()				{return $this->serie;}
	function getEscola()			{return $this->escola;}
	function getIdChat()			{return $this->idChat;}
	function getIdPlaneta()			{return $this->idPlaneta;}
	
	function __construct(	$novoNomeTurma = "",
							$novoProfResponsavel = 0,
							$novaDescricao = "",
							$novaSerie = 0,
							$novaEscola = 0,
							$novoIdChat = 0,
							$novoIdPlaneta = 0 ){
		
		$this->nomeTurma = $novoNomeTurma;
		$this->profResponsavel = (int) $novoProfResponsavel;
		$this->descricao = $novaDescricao;
		$this->serie = $novaSerie;
		$this->escola = $novaEscola;
		$this->idChat = (int) $novoIdChat;
		$this->idPlaneta = (int) $novoIdPlaneta;
		$this->salvo = false;
		//O array nivel de permissao sera inicializado ao abrir uma turma.
	}
	
	function openTurma($id){
		$q = new conexao();
		$idSanitizado = (int) $id;

		$q->solicitar("SELECT * FROM Turmas WHERE codTurma = '$idSanitizado'");

		if($q->registros > 0){
			$this->__construct(
				$q->resultado['nomeTurma'],
				$q->resultado['profResponsavel'],
				$q->resultado['descricao'],
				$q->resultado['serie'],
				$q->resultado['Escola'],
				$q->resultado['chat_id'],
				$q->resultado['idPlaneta']
				);
			$this->id = (int) $q->resultado['codTurma'];
			$this->salvo = true;
			//consultado no DB pelos usuarios da turma e..
			$q->solicitar("SELECT * FROM TurmasUsuario WHERE codTurma = '$idSanitizado'");
			//.. atribui no array indexado pelo codUsuario o nivel de associacao de cada um.
			for($i=0; $i < $q->registros; $i++){
				$NivelPermissao[$q->resultado['codUsuario']] = $q->resultado['associacao'];
				$q->proximo();
			}
		}else{
			$this->__construct("Turma inexistente");
		}
		
		
	}
	
	function salvar(){
		$q = new conexao();
		
		$nomeTurmaSanitizado = $q->sanitizaString($this->nomeTurma);
		$profResponsavelSanitizado = (int) $this->profResponsavel;
		$descricaoSanitizada = $q->sanitizaString($this->descricao);
		$serieSanitizada = (int) $this->serie;
		$escolaSanitizada = (int) $this->escola;
		$idChatSanitizado = (int) $this->idChat;
		$idPlanetaSanitizado = (int) $this->idPlaneta;

		if($this->salvo === false){

			$q->solicitar("
				INSERT INTO Turmas
					(nomeTurma, profResponsavel, descricao, serie, Escola, chat_id, idPlaneta)
				VALUES(
					'$nomeTurmaSanitizado',
					'$profResponsavelSanitizado',
					'$descricaoSanitizada',
					'$serieSanitizada',
					'$escolaSanitizada',
					'$idChatSanitizado',
					'$idPlanetaSanitizado'
					)");

			if($q->erro == ""){
				$this->id = $q->ultimoId();
				$q->solicitar("
				    INSERT INTO TurmasUsuario
				        (codTurma, codUsuario, associacao)
					VALUES(
								'$this->id',
								'$profResponsavelSanitizado',".
								NIVELPROFESSOR.")");
				$this->salvo = true;
			}

		}else{
			$q->solicitar("
				UPDATE Turmas SET 
					nomeTurma   = '$nomeTurmaSanitizado',
					profResponsavel   = '$profResponsavelSanitizado',
					descricao = '$descricaoSanitizada',
					serie  = '$serieSanitizada',
					Escola = '$escolaSanitizada',
					chat_id = '$idChatSanitizado',
					idPlaneta = '$idPlanetaSanitizado'
				WHERE codTurma = '$this->id'");
		}
	}
	
	function toJson($sendHeaders = false){
		if($sendHeaders){
			header("Content-Type: application/json");
		}
		
		$json = array();
		$json['id']     = $this->id;
		$json['nomeTurma']   = $this->nomeTurma;
		$json['profResponsavel']   = $this->profResponsavel;
		$json['descricao'] = $this->descricao;
		$json['serie']  = $this->serie;
		$json['escola']  = $this->escola;
		$json['idChat']  = $this->idChat;
		$json['idPlaneta']  = $this->idPlaneta;

		return json_encode($json);
	}
	/*
		TODO:
	
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